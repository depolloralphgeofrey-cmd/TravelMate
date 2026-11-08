<?php
// ===== TravelMate matching engine (demo) =====
require_once __DIR__ . '/config.php';

const INTERESTS       = ['Photography', 'Food and dining', 'Hiking', 'Shopping', 'Beach activities', 'Nightlife', 'Culture and history'];
const DEST_TYPES      = ['Beaches', 'Mountains', 'Cities', 'Historical sites', 'Nature destinations', 'Theme parks'];
const ACTIVITIES      = ['Adventure activities', 'Sightseeing', 'Trying local food', 'Relaxing', 'Nightlife', 'Cultural experiences'];
const TRAVELER_TYPES  = ['Adventure seeker', 'Nature lover', 'Foodie', 'Cultural traveler', 'Leisure traveler', 'Backpacker'];
const BUDGETS         = ['Budget', 'Moderate', 'Luxury'];
const BUDGET_LABELS   = ['Budget' => '₱10,000–₱25,000', 'Moderate' => '₱25,000–₱50,000', 'Luxury' => '₱50,000 and up'];
const STYLES          = ['Relaxed', 'Fast-paced', 'Flexible', 'Planned/itinerary-based'];
const FREE_LIMIT      = 10;   // profiles a free user can browse
const BATCH_SIZE      = 5;    // profiles loaded per scroll

const FIRST_NAMES = ['Maria', 'Alex', 'Jamie', 'Carlo', 'Bianca', 'Miguel', 'Tala', 'Renz', 'Kyla', 'Paolo', 'Sofia', 'Ian', 'Nina', 'Gabriel', 'Lian', 'Mika', 'Dane', 'Trisha', 'Jared', 'Cathy', 'Enzo', 'Hana', 'Luis', 'Yumi', 'Rico', 'Alyssa', 'Noel', 'Chloe', 'Marco', 'Ivy'];
const OTHER_DEST  = ['Tokyo, Japan', 'Seoul, South Korea', 'Palawan, Philippines', 'Bali, Indonesia', 'Siargao, Philippines', 'Bangkok, Thailand', 'Cebu, Philippines', 'Singapore', 'Hong Kong', 'Vigan, Philippines', 'Baguio, Philippines', 'Taipei, Taiwan'];
const REVIEWS     = ['Reliable, on time, and super fun to travel with.', 'Great at planning and very respectful of the budget.', 'Easy-going and a great photographer!', 'Felt safe the whole trip. Would travel again.', 'Open-minded and always up for trying local food.', 'Communicates clearly and keeps to the itinerary.'];

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ---------- User record helpers ----------
function user_rec() {
    $users = load_users();
    $em = current_user()['email'] ?? '';
    return $users[$em] ?? [];
}
function update_user_rec(callable $fn) {
    $users = load_users();
    $em = current_user()['email'];
    $rec = $users[$em] ?? [];
    $fn($rec);
    $users[$em] = $rec;
    save_users($users);
    return $rec;
}
function has_profile($rec) { return !empty($rec['profile']); }
function has_search($rec)  { return !empty($rec['search']); }
function is_premium($rec = null) {
    $rec = $rec ?? user_rec();
    return !empty($rec['premium_until']) && strtotime($rec['premium_until']) > time();
}
function ctx_from($rec) { return ['search' => $rec['search'], 'profile' => $rec['profile']]; }

// ---------- Flow progress bar ----------
function flow_bar($step) {
    $steps = ['Create Profile', 'Where are you going?', 'Start Matching', 'Your Matches'];
    echo '<div class="flow-bar">';
    foreach ($steps as $i => $s) {
        $cls = $i + 1 < $step ? 'done' : ($i + 1 === $step ? 'now' : '');
        echo '<div class="flow-step ' . $cls . '"><i>' . ($i + 1 < $step ? '✓' : $i + 1) . '</i><span>' . e($s) . '</span></div>';
    }
    echo '</div>';
}

// ---------- Traveler generator (deterministic demo profiles) ----------
function gen_traveler($i, $ctx) {
    $s = $ctx['search']; $p = $ctx['profile'];
    mt_srand((($i + 1) * 104729 + (crc32(strtolower($s['destination'])) % 100003)) & 0x7fffffff);

    $name  = FIRST_NAMES[$i % count(FIRST_NAMES)];
    $age   = mt_rand(21, 38);
    $dest  = (mt_rand(1, 100) <= 75) ? $s['destination'] : OTHER_DEST[mt_rand(0, count(OTHER_DEST) - 1)];
    $sStart = strtotime($s['start']); $sEnd = strtotime($s['end']);
    $len   = max(2, (int)(($sEnd - $sStart) / 86400) + mt_rand(-2, 2));
    $start = $sStart + mt_rand(-3, 5) * 86400;
    $end   = $start + $len * 86400;
    $budget = (mt_rand(1, 100) <= 60) ? $s['budget'] : BUDGETS[mt_rand(0, 2)];

    $ints = [];
    foreach (($p['interests'] ?? []) as $m) { if (mt_rand(1, 100) <= 50) $ints[] = $m; }
    foreach (INTERESTS as $x) { if (count($ints) >= 4) break; if (!in_array($x, $ints) && mt_rand(1, 100) <= 45) $ints[] = $x; }
    foreach (INTERESTS as $x) { if (count($ints) >= 3) break; if (!in_array($x, $ints)) $ints[] = $x; }

    $style = (mt_rand(1, 100) <= 50 && !empty($p['style'])) ? $p['style'] : STYLES[mt_rand(0, 3)];
    $type  = TRAVELER_TYPES[mt_rand(0, 5)];
    $visited = [];
    $pool = OTHER_DEST; shuffle($pool);
    $visited = array_slice($pool, 0, mt_rand(2, 4));
    $reviews = [];
    for ($k = 0; $k < 2; $k++) {
        $reviews[] = ['by' => FIRST_NAMES[mt_rand(0, count(FIRST_NAMES) - 1)], 'text' => REVIEWS[mt_rand(0, count(REVIEWS) - 1)]];
    }
    $bios = [
        "I love %s and slow mornings. Looking for a chill buddy to explore %s.",
        "First time doing %s on a trip with someone new! Heading to %s and open to splitting costs.",
        "Big on %s. Planning a trip to %s and hoping to meet like-minded travelers.",
    ];
    $bio = sprintf($bios[mt_rand(0, 2)], strtolower($ints[0]), $dest);

    return [
        'id' => $i, 'name' => $name, 'age' => $age, 'dest' => $dest,
        'start' => $start, 'end' => $end, 'budget' => $budget, 'interests' => $ints,
        'style' => $style, 'type' => $type, 'verified' => mt_rand(1, 100) > 25,
        'rating' => round(3.8 + mt_rand(0, 12) / 10, 1), 'trips' => mt_rand(1, 12),
        'visited' => $visited, 'reviews' => $reviews, 'bio' => $bio,
    ];
}

function score_traveler($t, $ctx) {
    $s = $ctx['search']; $p = $ctx['profile'];
    $sStart = strtotime($s['start']); $sEnd = strtotime($s['end']);

    $dest = strcasecmp(trim($t['dest']), trim($s['destination'])) === 0 ? 100 : 10;
    $overlap = max(0, min($t['end'], $sEnd) - max($t['start'], $sStart)) / 86400;
    $span = max(1, ($sEnd - $sStart) / 86400);
    $dates = (int)round(min(1, $overlap / $span) * 100);
    $diff = abs((int)array_search($t['budget'], BUDGETS) - (int)array_search($s['budget'], BUDGETS));
    $budget = $diff === 0 ? 100 : ($diff === 1 ? 55 : 15);
    $mine = $p['interests'] ?? [];
    $shared = count(array_intersect($mine, $t['interests']));
    $ints = $mine ? min(100, (int)round($shared / max(1, min(count($mine), 3)) * 100)) : 50;
    $style = (($p['style'] ?? '') === $t['style']) ? 100 : 40;

    $total = (int)round($dest * .25 + $dates * .15 + $budget * .20 + $ints * .25 + $style * .15);
    return ['total' => $total, 'parts' => ['Destination' => $dest, 'Travel dates' => $dates, 'Budget' => $budget, 'Interests' => $ints, 'Travel style' => $style]];
}

function fmt_dates($t) {
    return date('M j', $t['start']) . '–' . (date('M', $t['start']) === date('M', $t['end']) ? date('j', $t['end']) : date('M j', $t['end']));
}
function avatar_style($id) {
    $h = ($id * 47) % 360;
    return 'background:linear-gradient(135deg,hsl(' . $h . ',85%,60%),hsl(' . (($h + 60) % 360) . ',80%,55%))';
}

// ---------- Result card ----------
function render_card($t, $sc, $premium, $liked) {
    $id = (int)$t['id'];
    ob_start(); ?>
    <article class="t-card" data-id="<?= $id ?>">
      <div class="t-head">
        <div class="avatar big" style="<?= avatar_style($id) ?>"><?= e(substr($t['name'], 0, 1)) ?></div>
        <div class="t-name">
          <h3><?= e($t['name']) ?>, <?= (int)$t['age'] ?> <?php if ($t['verified']): ?><span class="verified" title="Verified TravelMate">✔ Verified</span><?php endif; ?></h3>
          <p>📍 <?= e($t['dest']) ?></p>
        </div>
        <span class="score"><?= (int)$sc['total'] ?>% Match</span>
      </div>
      <?php if ($premium): ?>
        <div class="t-info">
          <p>📅 <?= e(fmt_dates($t)) ?> &nbsp; 💰 <?= e(BUDGET_LABELS[$t['budget']]) ?></p>
          <div class="chips"><?php foreach ($t['interests'] as $k => $in): ?><span class="chip <?= ['', 'o', 'p'][$k % 3] ?>"><?= e($in) ?></span><?php endforeach; ?></div>
          <p>🎒 Travel Style: <?= e($t['style']) ?></p>
        </div>
      <?php else: ?>
        <div class="t-info locked" title="Unlock full details with Premium">
          <p>📅 ██████ &nbsp; 💰 ██████████</p>
          <div class="chips"><span class="chip">██████</span><span class="chip o">████████</span><span class="chip p">███████</span></div>
          <p>🎒 Travel Style: █████████</p>
          <a class="unlock" href="subscribe.php">🔒 Unlock full profile</a>
        </div>
      <?php endif; ?>
      <div class="t-actions">
        <button class="act pass" type="button" data-act="pass">❌ Pass</button>
        <a class="act view" href="view.php?id=<?= $id ?>">👤 View Profile</a>
        <button class="act like <?= $liked ? 'liked' : '' ?>" type="button" data-act="like" <?= $liked ? 'disabled' : '' ?>><?= $liked ? '❤️ Liked' : '❤️ Like' ?></button>
      </div>
    </article>
    <?php
    return ob_get_clean();
}

function render_lock_card() {
    return '<article class="t-card lock-card"><div class="lock-ico">🔒</div><h3>You&rsquo;ve reached the free limit</h3>'
         . '<p>Free accounts can browse up to ' . FREE_LIMIT . ' profiles. Go Premium for <b>unlimited matches</b>, full profile details, and feedback visibility.</p>'
         . '<a class="btn" href="subscribe.php">Unlock Unlimited Matching &ndash; ₱199/mo</a></article>';
}
