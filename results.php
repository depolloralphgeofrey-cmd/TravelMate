<?php
require_once __DIR__ . '/includes/matching.php';
require_once __DIR__ . '/includes/monetize.php';
require_login();

$rec = user_rec();
if (!has_profile($rec)) { header('Location: profile.php'); exit; }
if (!has_search($rec))  { header('Location: find.php'); exit; }
$s = $rec['search'];
$premium = is_premium($rec);
$matchCount = count($rec['matches'] ?? []);
$canSearch = feature_active('search', $rec);

$pageTitle = 'Your Travel Matches';
$currentPage = 'match';
$extraScript = 'assets/js/results.js';
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs">Step 4 of 4</div>
    <h1>Recommended Travel Matches</h1>
    <p>📍 <?= e($s['destination']) ?> &nbsp;•&nbsp; 📅 <?= e(date('M j', strtotime($s['start']))) ?>–<?= e(date('M j', strtotime($s['end']))) ?> &nbsp;•&nbsp; 💰 <?= e($s['budget']) ?> &nbsp;•&nbsp; 👥 <?= (int)$s['travelers'] ?></p>
  </div>
</section>

<section style="padding-top:40px">
  <div class="container" style="max-width:900px">
    <?php flow_bar(4); ?>
    <div class="res-toolbar">
      <div>
        <?php if ($premium): ?><span class="pill-premium">⭐ Premium &ndash; unlimited matching</span>
        <?php else: ?><span class="pill-free">Free plan &ndash; <b id="seenCount">0</b> of <?= FREE_LIMIT ?> profiles</span> <a href="subscribe.php" class="mini-link">Upgrade</a><?php endif; ?>
      </div>
      <div class="tool-links"><a href="rewards.php">🎁 Rewards</a><a href="matches.php">💬 My Matches (<span id="matchCount"><?= $matchCount ?></span>)</a><a href="find.php">🔎 New search</a></div>
    </div>
    <div class="filter-bar <?= $canSearch ? '' : 'locked' ?>">
      <b>🔎 Advanced search</b>
      <label>Min. match
        <select id="fMin" <?= $canSearch ? '' : 'disabled' ?>><option value="0">Any</option><option value="60">60%+</option><option value="70">70%+</option><option value="80">80%+</option></select></label>
      <label class="chk"><input type="checkbox" id="fBudget" <?= $canSearch ? '' : 'disabled' ?>> Same budget</label>
      <label class="chk"><input type="checkbox" id="fShared" <?= $canSearch ? '' : 'disabled' ?>> Shared interests</label>
      <?php if (!$canSearch): ?><a class="mini-link" href="rewards.php">🔒 Unlock for ₱70 or points</a><?php endif; ?>
    </div>
    <p class="hint">Sample travelers are generated for demonstration purposes.</p>

    <div id="results" data-premium="<?= $premium ? '1' : '0' ?>"></div>
    <div id="sentinel" class="sentinel"><div class="spinner"></div><span>Finding more travelers…</span></div>
  </div>
</section>

<div class="modal" id="matchModal" aria-hidden="true">
  <div class="modal-box">
    <div class="confetti">🎉</div>
    <h2>It&rsquo;s a Travel Match!</h2>
    <p>You and <b id="matchName">someone</b> have matched for your trip.</p>
    <div class="modal-actions"><a class="btn" id="matchChat" href="matches.php">💬 Start Chat</a><button class="btn btn-dark" type="button" id="matchClose">Keep Browsing</button></div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
