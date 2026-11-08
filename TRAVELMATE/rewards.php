<?php
require_once __DIR__ . '/includes/monetize.php';
require_login();
$noAds = true;   // this page has its own rewarded-ad flow

$features = premium_features();
$flash = ''; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'redeem') {
    $key = $_POST['feature'] ?? '';
    $cost = feature_points_cost();
    $rec = user_rec();
    if (is_premium($rec)) {
        $error = 'You are Premium, so every feature is already included.';
    } elseif (!isset($features[$key]) || !$features[$key][3]) {
        $error = 'That feature cannot be redeemed here.';
    } elseif (points_balance($rec) < $cost) {
        $error = 'You need ' . $cost . ' points for this feature. You have ' . points_balance($rec) . '.';
    } else {
        update_user_rec(function (&$r) use ($key, $cost) {
            $r['points'] = (int)$r['points'] - $cost;
            $cur = feature_until($key, $r);
            $base = $cur ? strtotime($cur) : time();
            $r['features'][$key] = date('c', strtotime('+' . FEATURE_DAYS_POINTS . ' days', $base));
            $r['redeems'][] = ['feature' => $key, 'points' => $cost, 'at' => date('c')];
        });
        $flash = $features[$key][0] . ' unlocked for ' . FEATURE_DAYS_POINTS . ' days!';
    }
}

$rec = user_rec();
$premium = is_premium($rec);
$pts = points_balance($rec);
$cost = feature_points_cost();
$today = ads_today($rec);
$left = max(0, AD_DAILY_MAX - $today);
$pct = min(100, (int)round($pts / $cost * 100));

$pageTitle = 'Rewards & Features';
$currentPage = 'myprofile';
$extraScript = 'assets/js/rewards.js';
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="myprofile.php">My Profile</a> / Rewards</div>
    <h1>Rewards &amp; Premium Features 🎁</h1>
    <p>Watch short ads to earn points, then use them to try Premium features. Prefer no ads at all? Subscribe to Premium.</p>
  </div>
</section>

<section>
  <div class="container">
    <?php if ($flash): ?><div class="alert ok">🎉 <?= e($flash) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>

    <?php if ($premium): ?>
      <div class="note">⭐ You&rsquo;re Premium &ndash; <b>no ads</b> and every feature is already included until <b><?= e(date('F j, Y', strtotime($rec['premium_until']))) ?></b>.</div>
    <?php else: ?>
      <div class="grid grid-2" style="align-items:stretch">
        <div class="card points-card">
          <h3>Your points</h3>
          <div class="points-big"><span id="ptsNow"><?= $pts ?></span> <small>pts</small></div>
          <p class="hint">&asymp; <b id="ptsPeso">₱<?= number_format(points_to_peso($pts), 2) ?></b> in value &bull; points are non-transferable and cannot be exchanged for cash.</p>
          <div class="meter big"><div><i id="ptsBar" style="width:<?= $pct ?>%"></i></div></div>
          <p class="hint"><?= $cost ?> points unlock one feature (₱<?= FEATURE_PRICE ?> value) for <?= FEATURE_DAYS_POINTS ?> days.</p>
        </div>
        <div class="card accent">
          <h3>Rewarded ad 🎬</h3>
          <p>Earn <b><?= AD_POINTS ?> points</b> for every completed ad. Max <b><?= AD_DAILY_MAX ?> ads per day</b> (<?= AD_POINTS * AD_DAILY_MAX ?> points &asymp; ₱<?= number_format(points_to_peso(AD_POINTS * AD_DAILY_MAX), 0) ?>).</p>
          <p class="hint">Today: <b id="adsToday"><?= $today ?></b> / <?= AD_DAILY_MAX ?> watched</p>
          <button class="btn" id="watchBtn" type="button" <?= $left <= 0 ? 'disabled' : '' ?> style="margin-top:12px"><?= $left <= 0 ? 'Come back tomorrow' : '▶ Watch an ad (+' . AD_POINTS . ' pts)' ?></button>
          <p class="hint" id="adMsg"></p>
          <p class="hint">Ads are optional and powered by Google AdMob (demo ad unit).</p>
        </div>
      </div>
    <?php endif; ?>

    <div class="section-head" style="margin-top:50px">
      <span class="eyebrow">Individual Premium Features</span>
      <h2>Try a feature before you subscribe</h2>
      <p>Buy a feature for <b>₱<?= FEATURE_PRICE ?></b> (<?= FEATURE_DAYS_PAID ?> days), or redeem <b><?= $cost ?> points</b> for <?= FEATURE_DAYS_POINTS ?> days.</p>
    </div>
    <div class="grid grid-3">
      <?php foreach ($features as $key => $f): $until = feature_until($key, $rec); ?>
        <div class="card <?= $f[3] ? '' : 'dim' ?>">
          <div class="icon"><?= $f[1] ?></div>
          <h3><?= e($f[0]) ?></h3>
          <p><?= e($f[2]) ?></p>
          <div class="feat-actions">
            <?php if ($premium): ?>
              <span class="pill-premium">Included in Premium</span>
            <?php elseif (!$f[3]): ?>
              <span class="pill-free">Mobile app feature</span>
            <?php else: ?>
              <?php if ($until): ?><span class="verified">Active until <?= e(date('M j', strtotime($until))) ?></span><?php endif; ?>
              <a class="btn btn-small" href="subscribe.php?feature=<?= e($key) ?>"><?= $until ? 'Extend' : 'Buy' ?> ₱<?= FEATURE_PRICE ?></a>
              <form method="post" style="display:inline">
                <input type="hidden" name="action" value="redeem"><input type="hidden" name="feature" value="<?= e($key) ?>">
                <button class="btn btn-small btn-dark" type="submit" <?= $pts < $cost ? 'disabled title="Not enough points"' : '' ?>>Use <?= $cost ?> pts</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (!$premium): ?>
      <div class="cta reveal" style="margin-top:50px">
        <h2>Want everything, with no ads?</h2>
        <p>Premium removes all ads and unlocks unlimited matching, full profiles, feedback, and more.</p>
        <a href="subscribe.php" class="btn">See Premium plans</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php if (!$premium): ?>
<div class="ad-pop rw" id="rwOverlay" hidden>
  <div class="ad-box">
    <div class="ad-top"><span class="ad-tag">Ad</span><small>Google AdMob &middot; rewarded demo ad</small></div>
    <div class="ad-body">
      <div class="ad-art">🎬</div>
      <small class="ad-brand">Sample rewarded ad</small>
      <h3>Watch to earn <?= AD_POINTS ?> points</h3>
      <p id="rwText">Please wait for the timer to finish&hellip;</p>
      <div class="meter big"><div><i id="rwBar" style="width:0"></i></div></div>
      <button class="btn" type="button" id="rwClaim" disabled>Reward in <b id="rwCount"><?= AD_WATCH_SECONDS ?></b>s</button>
    </div>
  </div>
</div>
<script>window.TM_RW = <?= json_encode(['seconds' => AD_WATCH_SECONDS, 'points' => AD_POINTS, 'max' => AD_DAILY_MAX, 'cost' => $cost]) ?>;</script>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>
