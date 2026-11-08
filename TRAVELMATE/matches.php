<?php
require_once __DIR__ . '/includes/matching.php';
require_login();

$rec = user_rec();
$matches = $rec['matches'] ?? [];
$chats = $rec['chats'] ?? [];

$pageTitle = 'My Matches';
$currentPage = 'match';
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="results.php">← Back to matches</a></div>
    <h1>My Travel Matches 🎉</h1>
    <p>People who liked you back. Say hello and start planning together.</p>
  </div>
</section>

<section>
  <div class="container" style="max-width:820px">
    <?php if (!$matches): ?>
      <div class="card" style="text-align:center">
        <h3>No matches yet</h3>
        <p>Like a few travelers and they may like you back!</p><br>
        <a class="btn" href="results.php">Browse Matches</a>
      </div>
    <?php else: foreach ($matches as $id => $t):
      $last = !empty($chats[$id]) ? end($chats[$id]) : null; ?>
      <a class="match-row" href="chat.php?id=<?= (int)$id ?>">
        <div class="avatar big" style="<?= avatar_style($id) ?>"><?= e(substr($t['name'], 0, 1)) ?></div>
        <div class="grow">
          <b><?= e($t['name']) ?>, <?= (int)$t['age'] ?></b> <span class="score"><?= (int)($t['score'] ?? 0) ?>%</span>
          <p>📍 <?= e($t['dest']) ?> &bull; 📅 <?= e(fmt_dates($t)) ?></p>
          <small><?= $last ? e(mb_strimwidth($last['text'], 0, 70, '…')) : 'Say hi 👋' ?></small>
        </div>
        <span class="go">💬</span>
      </a>
    <?php endforeach; endif; ?>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
