<?php
// "Let's Travel Now": trip summary, how much each traveler pays, and mode of payment
require_once __DIR__ . '/includes/monetize.php';
require_once __DIR__ . '/includes/paybox.php';
require_login();
$noAds = true;   // never interrupt payment with ads

$rec = user_rec();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? -1);
$t = $rec['matches'][$id] ?? null;
if (!$t) { header('Location: matches.php'); exit; }
if (empty($rec['chats'][$id])) { header('Location: chat.php?id=' . $id . '&talk=1'); exit; }   // must have chatted first

$trip = $t['trip'] ?? $rec['search'];
$group = max(2, (int)($trip['travelers'] ?? 2));                 // you + your travel match (at least 2)
$nights = max(1, (int)round((strtotime($trip['end']) - strtotime($trip['start'])) / 86400));
$perPerson = TRIP_PER_PERSON[$trip['budget']] ?? TRIP_PER_PERSON['Moderate'];
$groupTotal = $perPerson * $group;

// Cost items (App file: Trip Cost Splitter -> hotel, transportation, food, activities, entrance fees)
$items = [
    'hotel'     => ['🏨 Hotel / accommodation', 35],
    'transport' => ['🚌 Transportation', 30],
    'food'      => ['🍜 Food', 20],
    'activities'=> ['🎯 Activities', 10],
    'entrance'  => ['🎟️ Entrance fees', 5],
];
$defaults = [];
foreach ($items as $k => $it) { $defaults[$k] = (int)(round($groupTotal * $it[1] / 100 / 100) * 100); }

$error = ''; $done = null; $selMethod = ''; $selLocal = '';
$amt = $defaults;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selMethod = $_POST['method'] ?? ''; $selLocal = $_POST['local'] ?? '';
    foreach ($items as $k => $it) {
        $v = (float)($_POST['amt'][$k] ?? 0);
        $amt[$k] = (int)round(min(1000000, max(0, $v)));
    }
    $total = array_sum($amt);
    list($label, $mkey, $perr) = read_pay_choice();
    if ($total <= 0) {
        $error = 'Please enter at least one trip cost.';
    } elseif ($perr) {
        $error = $perr;
    } else {
        $share = (int)ceil($total / $group);
        $ref = 'TR-' . strtoupper(substr(md5(uniqid('', true)), 0, 8));
        $commission = (int)round($total * BOOKING_COMMISSION / 100);
        $dest = $trip['destination'];
        update_user_rec(function (&$r) use ($id, $t, $trip, $total, $share, $group, $label, $ref, $commission, $amt) {
            $r['trips'][] = [
                'ref' => $ref, 'with' => $t['name'], 'with_id' => $id, 'dest' => $trip['destination'],
                'start' => $trip['start'], 'end' => $trip['end'], 'group' => $group, 'items' => $amt,
                'total' => $total, 'share' => $share, 'method' => $label, 'commission' => $commission, 'at' => date('c'),
            ];
            $r['chats'][$id][] = ['from' => 'me', 'text' => "✈️ Let's travel! I booked my share (₱" . number_format($share) . ") for " . $trip['destination'] . ". Ref " . $ref, 'at' => time()];
        });
        $done = ['ref' => $ref, 'total' => $total, 'share' => $share, 'method' => $label, 'key' => $mkey, 'dest' => $dest];
    }
}

$pageTitle = "Let's Travel Now";
$currentPage = 'match';
$extraScript = 'assets/js/pay.js';
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="chat.php?id=<?= $id ?>">← Back to chat with <?= e($t['name']) ?></a></div>
    <h1>✈️ Let&rsquo;s Travel Now!</h1>
    <p>Review your trip with <?= e($t['name']) ?>, see how much you need to pay, and choose how to pay.</p>
  </div>
</section>

<section>
  <div class="container">
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>

    <?php if ($done): ?>
      <div class="receipt" style="max-width:640px;margin-inline:auto">
        <div class="receipt-ico">🎉</div>
        <h2>You&rsquo;re going to <?= e($done['dest']) ?>!</h2>
        <p>Your share is booked. <?= e($t['name']) ?> will receive a notification to pay their share.</p>
        <dl class="facts">
          <dt>Booking reference</dt><dd><b><?= e($done['ref']) ?></b></dd>
          <dt>Total trip cost</dt><dd><?= peso($done['total']) ?></dd>
          <dt>Your share (÷ <?= $group ?> travelers)</dt><dd><b><?= peso($done['share']) ?></b></dd>
          <dt>Paid with</dt><dd><?= e($done['method']) ?></dd>
        </dl>
        <?php if ($done['key'] === 'bank'): ?><pre class="bank"><?= e(BANK_DETAILS) ?>
Reference: <?= e($done['ref']) ?></pre><p class="hint">In the live app, the booking is confirmed once the transfer is verified.</p><?php endif; ?>
        <p style="margin-top:16px"><a class="btn" href="chat.php?id=<?= $id ?>">💬 Back to chat</a></p>
      </div>
    <?php else: ?>
    <form method="post" data-pay class="travel-layout" id="travelForm" data-group="<?= $group ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div>
        <div class="card">
          <h3>Trip summary</h3>
          <div class="trip-who">
            <div class="avatar big" style="<?= avatar_style(9999) ?>"><?= e(strtoupper(substr(current_user()['name'], 0, 1))) ?></div>
            <span class="plus">+</span>
            <div class="avatar big" style="<?= avatar_style($id) ?>"><?= e(substr($t['name'], 0, 1)) ?></div>
            <div class="grow"><b>You &amp; <?= e($t['name']) ?></b><br><small><?= $group ?> travelers</small></div>
          </div>
          <div class="summary">
            <div><small>📍 Destination</small><b><?= e($trip['destination']) ?></b></div>
            <div><small>📅 Travel dates</small><b><?= e(date('M j', strtotime($trip['start']))) ?> – <?= e(date('M j, Y', strtotime($trip['end']))) ?></b></div>
            <div><small>🌙 Length</small><b><?= $nights ?> night<?= $nights > 1 ? 's' : '' ?></b></div>
            <div><small>💰 Budget</small><b><?= e($trip['budget']) ?> <em><?= e(BUDGET_LABELS[$trip['budget']]) ?></em></b></div>
          </div>
        </div>

        <div class="card" style="margin-top:20px">
          <h3>Estimated trip costs</h3>
          <p class="hint">Starting estimates for a <b><?= e($trip['budget']) ?></b> trip. Adjust any amount. Final prices come from partner hotels, agencies, and tour operators.</p>
          <div class="cost-rows">
            <?php foreach ($items as $k => $it): ?>
              <label class="cost-row"><span><?= $it[0] ?></span>
                <span class="money"><i>₱</i><input type="number" name="amt[<?= $k ?>]" min="0" max="1000000" step="100" value="<?= (int)$amt[$k] ?>" inputmode="numeric"></span></label>
            <?php endforeach; ?>
          </div>
          <div class="order-sum">
            <div><span>Total trip cost</span><b id="tTotal">₱0</b></div>
            <div><span>Number of travelers</span><b>÷ <?= $group ?></b></div>
            <div class="grand"><span>Your share to pay</span><b id="tShare">₱0</b></div>
          </div>
          <p class="hint">💡 TravelMate earns an agreed <?= BOOKING_COMMISSION ?>% commission from the partner establishment &ndash; <b>no extra booking fee is added to your bill.</b> <?= e($t['name']) ?> pays their own share.</p>
        </div>
      </div>

      <aside>
        <div class="card sticky-pay">
          <h3>Mode of payment</h3>
          <?php render_pay_methods($selMethod, $selLocal); ?>
          <div class="pay-total" style="flex-direction:column;align-items:stretch;gap:12px">
            <div>You pay now <b id="tShare2">₱0</b></div>
            <button class="btn pay-submit" type="submit">🔒 Pay my share</button>
          </div>
          <p class="hint">Demo checkout: no real payment is processed.</p>
        </div>
      </aside>
    </form>
    <script>
      window.payUpdate = function (form) {
        var g = Number(form.dataset.group || 2), total = 0;
        form.querySelectorAll('.cost-row input').forEach(function (i) { total += Math.max(0, Number(i.value) || 0); });
        var share = Math.ceil(total / g);
        var f = function (n) { return '₱' + Number(n).toLocaleString(); };
        document.getElementById('tTotal').textContent = f(total);
        document.getElementById('tShare').textContent = f(share);
        document.getElementById('tShare2').textContent = f(share);
      };
    </script>
    <?php endif; ?>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
