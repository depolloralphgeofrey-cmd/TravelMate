<?php
require_once __DIR__ . '/includes/matching.php';
require_once __DIR__ . '/includes/monetize.php';
require_login();

$rec = user_rec();
if (!has_profile($rec) || !has_search($rec)) { header('Location: profile.php'); exit; }
$id = (int)($_GET['id'] ?? -1);
if ($id < 0) { header('Location: results.php'); exit; }

$premium = is_premium($rec);
$ctx = ctx_from($rec);
$t = $rec['matches'][$id] ?? gen_traveler($id, $ctx);
$sc = score_traveler($t, $ctx);
$isMatch = isset($rec['matches'][$id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? '';
    if ($act === 'block') {
        update_user_rec(function (&$r) use ($id) {
            $r['blocked'] = array_values(array_unique(array_merge($r['blocked'] ?? [], [$id])));
            unset($r['matches'][$id]);
        });
        header('Location: results.php'); exit;
    }
    if ($act === 'report') {
        update_user_rec(function (&$r) use ($id, $t) { $r['reports'][] = ['id' => $id, 'name' => $t['name'], 'at' => date('c')]; });
        header('Location: view.php?id=' . $id . '&reported=1'); exit;
    }
}

$pageTitle = $t['name'] . "'s Profile";
$currentPage = 'match';
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="results.php">← Back to matches</a></div>
    <h1><?= e($t['name']) ?>, <?= (int)$t['age'] ?></h1>
    <p>📍 <?= e($t['dest']) ?></p>
  </div>
</section>

<section>
  <div class="container" style="max-width:900px">
    <?php if (isset($_GET['reported'])): ?><div class="alert ok">Thanks. Your report was recorded and will be reviewed by our team.</div><?php endif; ?>
    <div class="grid grid-2" style="align-items:start">
      <div class="card" style="text-align:center">
        <div class="avatar xl" style="<?= avatar_style($id) ?>"><?= e(substr($t['name'], 0, 1)) ?></div>
        <h3 style="margin-top:12px"><?= e($t['name']) ?>, <?= (int)$t['age'] ?></h3>
        <?php if ($t['verified']): ?><span class="verified">✔ Verified TravelMate</span><?php endif; ?>
        <div class="big-score"><?= (int)$sc['total'] ?>%<small>Compatibility</small></div>
        <p>⭐ <?= e($t['rating']) ?> &bull; <?= (int)$t['trips'] ?> TravelMate trips</p>
        <div class="phone-actions" style="margin-top:16px">
          <form method="post" onsubmit="return confirm('Block this user? They will no longer appear.')" style="flex:1"><input type="hidden" name="action" value="block"><button class="act pass" style="width:100%">🚫 Block</button></form>
          <form method="post" style="flex:1"><input type="hidden" name="action" value="report"><button class="act pass" style="width:100%">🚨 Report</button></form>
        </div>
        <?php if ($isMatch): ?><a class="btn" style="margin-top:14px;display:block" href="chat.php?id=<?= $id ?>">💬 Chat</a><?php endif; ?>
      </div>

      <div>
        <div class="card <?= $premium ? '' : 'blurred-card' ?>">
          <h3>About</h3>
          <?php if ($premium): ?>
            <p><?= e($t['bio']) ?></p>
            <p style="margin-top:10px">📅 <b>Availability:</b> <?= e(fmt_dates($t)) ?></p>
            <p>💰 <b>Budget:</b> <?= e($t['budget']) ?> (<?= e(BUDGET_LABELS[$t['budget']]) ?>)</p>
            <p>🎒 <b>Travel style:</b> <?= e($t['style']) ?> &bull; <?= e($t['type']) ?></p>
            <p>🌏 <b>Destinations visited:</b> <?= e(implode(', ', $t['visited'])) ?></p>
            <div class="chips" style="margin-top:8px"><?php foreach ($t['interests'] as $k => $in): ?><span class="chip <?= ['', 'o', 'p'][$k % 3] ?>"><?= e($in) ?></span><?php endforeach; ?></div>
          <?php else: ?>
            <p class="blur">██████ ██████████ ████ ███████ ████████ ██████ ███████████.</p>
            <p class="blur">📅 Availability: ██████ &nbsp; 💰 Budget: ████████</p>
            <p class="blur">🎒 Travel style: ████████ &nbsp; 🌏 Visited: ██████, ██████</p>
            <a class="btn" href="subscribe.php" style="margin-top:12px">🔒 Unlock full description</a>
          <?php endif; ?>
        </div>

        <div class="card" style="margin-top:20px">
          <h3>Compatibility breakdown</h3>
          <?php if (feature_active('recommend', $rec)): foreach ($sc['parts'] as $label => $v): ?>
            <div class="meter"><span><?= e($label) ?></span><div><i style="width:<?= (int)$v ?>%"></i></div><b><?= (int)$v ?>%</b></div>
          <?php endforeach; else: ?>
            <p class="hint">Detailed compatibility is a Premium feature (or unlock it for ₱70 / points).</p>
            <a class="btn btn-small" href="rewards.php">Unlock</a>
          <?php endif; ?>
        </div>

        <div class="card" style="margin-top:20px">
          <h3>Feedback from past travelers</h3>
          <?php if (feature_active('feedback', $rec)): foreach ($t['reviews'] as $rv): ?>
            <p style="margin:8px 0">&ldquo;<?= e($rv['text']) ?>&rdquo; <small>&mdash; <?= e($rv['by']) ?></small></p>
          <?php endforeach; else: ?>
            <p class="blur">&ldquo;██████ ████ ███████ ████ ████.&rdquo; &mdash; ████</p>
            <p class="hint">Feedback Visibility is a Premium feature (₱70 per feature, or use points).</p>
            <a class="btn btn-small" href="rewards.php">View feedback</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
