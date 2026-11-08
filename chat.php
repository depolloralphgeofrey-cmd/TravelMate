<?php
require_once __DIR__ . '/includes/matching.php';
require_login();

$rec = user_rec();
$id = (int)($_GET['id'] ?? -1);
$t = $rec['matches'][$id] ?? null;
if (!$t) { header('Location: matches.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? 'send';
    if ($act === 'send') {
        $text = trim(strip_tags($_POST['text'] ?? ''));
        if ($text !== '') {
            update_user_rec(function (&$r) use ($id, $text) { $r['chats'][$id][] = ['from' => 'me', 'text' => mb_substr($text, 0, 500), 'at' => time()]; });
        }
    } elseif ($act === 'itinerary') {
        $trip = $t['trip'] ?? $rec['search'];
        $text = '📍 Itinerary: ' . $trip['destination'] . ' • ' . date('M j', strtotime($trip['start'])) . '–' . date('M j, Y', strtotime($trip['end'])) . ' • Budget: ' . $trip['budget'] . ' (' . BUDGET_LABELS[$trip['budget']] . ')';
        update_user_rec(function (&$r) use ($id, $text) { $r['chats'][$id][] = ['from' => 'me', 'text' => $text, 'at' => time()]; });
    } elseif ($act === 'block') {
        update_user_rec(function (&$r) use ($id) {
            $r['blocked'] = array_values(array_unique(array_merge($r['blocked'] ?? [], [$id])));
            unset($r['matches'][$id]);
        });
        header('Location: matches.php'); exit;
    } elseif ($act === 'report') {
        update_user_rec(function (&$r) use ($id, $t) { $r['reports'][] = ['id' => $id, 'name' => $t['name'], 'at' => date('c')]; });
        header('Location: chat.php?id=' . $id . '&reported=1'); exit;
    }
    header('Location: chat.php?id=' . $id); exit;
}

$msgs = user_rec()['chats'][$id] ?? [];
$pageTitle = 'Chat with ' . $t['name'];
$currentPage = 'match';
include 'includes/header.php';
?>
<section style="padding:30px 0 60px">
  <div class="container" style="max-width:760px">
    <p><a href="matches.php">← My Matches</a></p>
    <div class="chat-box">
      <div class="chat-head">
        <div class="avatar big" style="<?= avatar_style($id) ?>"><?= e(substr($t['name'], 0, 1)) ?></div>
        <div class="grow"><b><?= e($t['name']) ?>, <?= (int)$t['age'] ?></b><br><small>📍 <?= e($t['dest']) ?> &bull; <?= e(fmt_dates($t)) ?></small></div>
        <a href="view.php?id=<?= $id ?>" class="mini-link">Profile</a>
      </div>
      <?php if (isset($_GET['talk'])): ?><div class="alert err" style="margin:10px">Chat with your match first, then tap “Let’s Travel Now”.</div><?php endif; ?>
      <?php if (isset($_GET['reported'])): ?><div class="alert ok" style="margin:10px">Report submitted. Thank you for keeping TravelMate safe.</div><?php endif; ?>
      <div class="chat-body" id="chatBody">
        <div class="sys">🎉 It&rsquo;s a Travel Match! You and <?= e($t['name']) ?> matched for your trip.</div>
        <?php foreach ($msgs as $m): ?>
          <div class="bubble me"><?= e($m['text']) ?><small><?= e(date('g:i A', $m['at'])) ?></small></div>
        <?php endforeach; ?>
      </div>
      <?php $canTravel = count($msgs) > 0; ?>
      <div class="travel-bar">
        <?php if ($canTravel): ?>
          <a class="btn travel-btn" href="travel.php?id=<?= $id ?>">✈️ Let&rsquo;s Travel Now</a>
          <small>Ready? See the trip cost and pay your share.</small>
        <?php else: ?>
          <span class="travel-locked">✈️ Say hi first to unlock &ldquo;Let&rsquo;s Travel Now&rdquo;</span>
        <?php endif; ?>
      </div>
      <div class="chat-tools">
        <form method="post"><input type="hidden" name="action" value="itinerary"><button type="submit">📍 Share itinerary</button></form>
        <a href="app.php#splitCalc">💰 Split expenses</a>
        <form method="post" onsubmit="return confirm('Block this user?')"><input type="hidden" name="action" value="block"><button type="submit">🚫 Block</button></form>
        <form method="post"><input type="hidden" name="action" value="report"><button type="submit">🚨 Report</button></form>
      </div>
      <form method="post" class="chat-form">
        <input type="hidden" name="action" value="send">
        <input name="text" placeholder="Type a message…" autocomplete="off" required>
        <button class="btn btn-small" type="submit">Send</button>
      </form>
    </div>
  </div>
</section>
<script>var b = document.getElementById('chatBody'); b.scrollTop = b.scrollHeight;</script>
<?php include 'includes/footer.php'; ?>
