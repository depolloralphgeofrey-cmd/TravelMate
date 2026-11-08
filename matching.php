<?php
require_once __DIR__ . '/includes/matching.php';
require_login();

$rec = user_rec();
if (!has_profile($rec)) { header('Location: profile.php'); exit; }
if (!has_search($rec))  { header('Location: find.php'); exit; }
$s = $rec['search']; $p = $rec['profile'];
$premium = is_premium($rec);

$pageTitle = 'Start Matching';
$currentPage = 'match';
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs">Step 3 of 4</div>
    <h1>Ready to find your travel match?</h1>
    <p>We&rsquo;ll compare your trip with other travelers&rsquo; destinations, dates, budgets, interests, and travel style.</p>
  </div>
</section>

<section>
  <div class="container" style="max-width:820px">
    <?php flow_bar(3); ?>
    <div class="start-card" id="startCard">
      <div class="radar" id="radar"><i></i><i></i><i></i><span>✈️</span></div>
      <h2 id="startTitle">Your trip summary</h2>
      <div class="summary">
        <div><small>📍 Destination</small><b><?= e($s['destination']) ?></b></div>
        <div><small>📅 Travel dates</small><b><?= e(date('M j', strtotime($s['start']))) ?> – <?= e(date('M j, Y', strtotime($s['end']))) ?></b></div>
        <div><small>💰 Budget</small><b><?= e($s['budget']) ?> <em><?= e(BUDGET_LABELS[$s['budget']]) ?></em></b></div>
        <div><small>👥 Travelers</small><b><?= (int)$s['travelers'] ?></b></div>
      </div>
      <div class="chips" style="justify-content:center;margin:18px 0 6px">
        <?php foreach ($p['interests'] as $k => $in): ?><span class="chip <?= ['', 'o', 'p'][$k % 3] ?>"><?= e($in) ?></span><?php endforeach; ?>
        <span class="chip"><?= e($p['style']) ?></span>
      </div>
      <p class="hint"><?= $premium ? '⭐ Premium: unlimited matches with full profile details.' : 'Free plan: browse up to ' . FREE_LIMIT . ' profiles. <a href="subscribe.php">Go Premium for unlimited</a>.' ?></p>
      <a href="results.php" class="btn start-btn" id="startBtn">🚀 Start Matching</a>
      <p class="hint" style="margin-top:14px"><a href="find.php">← Change trip details</a></p>
    </div>
  </div>
</section>
<script>
  document.getElementById('startBtn').addEventListener('click', function (e) {
    e.preventDefault();
    var href = this.href;
    document.getElementById('startCard').classList.add('searching');
    document.getElementById('startTitle').textContent = 'Searching for travelers…';
    this.textContent = 'Matching…';
    setTimeout(function () { window.location.href = href; }, 2200);
  });
</script>
<?php include 'includes/footer.php'; ?>
