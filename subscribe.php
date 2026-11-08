<?php
require_once __DIR__ . '/includes/monetize.php';
require_once __DIR__ . '/includes/paybox.php';
require_login();
$noAds = true;   // never interrupt checkout with ads

$rec = user_rec();
$features = premium_features();
$fkey = $_GET['feature'] ?? $_POST['feature'] ?? '';
$featureMode = isset($features[$fkey]) && $features[$fkey][3];
if ($featureMode && is_premium($rec)) { header('Location: rewards.php'); exit; }

if ($featureMode) {
    $plans = ['feature' => ['Feature: ' . $features[$fkey][0], FEATURE_PRICE, FEATURE_DAYS_PAID, FEATURE_DAYS_PAID . ' days of access', $features[$fkey][2]]];
    $selPlan = 'feature';
} else {
    $plans = [
        'monthly' => ['Monthly', 199, 30, '≈ ₱199 per month', 'Full Premium access'],
        '3months' => ['3 Months', 499, 90, '≈ ₱166 per month', 'Save ₱98 vs monthly'],
        'annual'  => ['Annual', 1499, 365, '≈ ₱125 per month', 'Best value: save ₱889'],
    ];
    $selPlan = isset($plans[$_GET['plan'] ?? '']) ? $_GET['plan'] : 'monthly';
}
$flash = ''; $error = ''; $receipt = null;
$selMethod = ''; $selLocal = ''; $student = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? '';
    if ($act === 'pay') {
        $plan = $_POST['plan'] ?? '';
        $selPlan = isset($plans[$plan]) ? $plan : $selPlan;
        $selMethod = $_POST['method'] ?? ''; $selLocal = $_POST['local'] ?? '';
        $student = !empty($_POST['student']);
        list($label, $mkey, $perr) = read_pay_choice();
        if (!isset($plans[$plan])) {
            $error = 'Please choose a plan.';
        } elseif ($perr) {
            $error = $perr;
        } else {
            $subtotal = $plans[$plan][1];
            $disc = discount_for($rec, $student);
            $discount = (int)round($subtotal * $disc['pct'] / 100);
            $total = $subtotal - $discount;
            $ref = 'TM-' . strtoupper(substr(md5(uniqid('', true)), 0, 8));
            $days = $plans[$plan][2];
            $name = $plans[$plan][0];
            $rec = update_user_rec(function (&$r) use ($plan, $plans, $days, $label, $ref, $total, $discount, $disc, $featureMode, $fkey, $student, $name) {
                if ($featureMode) {
                    $cur = feature_until($fkey, $r);
                    $base = $cur ? strtotime($cur) : time();
                    $r['features'][$fkey] = date('c', strtotime("+$days days", $base));
                } else {
                    $base = (!empty($r['premium_until']) && strtotime($r['premium_until']) > time()) ? strtotime($r['premium_until']) : time();
                    $r['premium_until'] = date('c', strtotime("+$days days", $base));
                    $r['plan'] = $plan;
                }
                if ($student) { $r['student_pending'] = true; }
                $r['payments'][] = ['plan' => $name, 'amount' => $total, 'discount' => $discount, 'method' => $label, 'ref' => $ref, 'at' => date('c')];
            });
            $until = $featureMode ? feature_until($fkey, $rec) : $rec['premium_until'];
            $receipt = ['plan' => $name, 'subtotal' => $subtotal, 'discount' => $discount, 'dlabel' => $disc['label'], 'amount' => $total, 'method' => $label, 'key' => $mkey, 'ref' => $ref, 'until' => $until, 'feature' => $featureMode];
            $flash = 'ok';
        }
    } elseif ($act === 'cancel' && !$featureMode) {
        update_user_rec(function (&$r) { unset($r['premium_until'], $r['plan']); });
        $flash = 'cancel';
    }
}
$rec = user_rec();
$premium = is_premium($rec);
$newUserPct = discount_for($rec, false)['pct'];

$pageTitle = $featureMode ? 'Buy a Feature' : 'Go Premium';
$currentPage = 'match';
$extraScript = 'assets/js/pay.js';
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="rewards.php">← Rewards &amp; features</a></div>
    <h1><?= $featureMode ? e($features[$fkey][1] . ' ' . $features[$fkey][0]) : 'TravelMate Premium ⭐' ?></h1>
    <p><?= $featureMode ? 'Try this feature for ₱' . FEATURE_PRICE . ' before you subscribe.' : 'Travel farther. Match better. Travel together. 💜' ?></p>
  </div>
</section>

<section>
  <div class="container">
    <?php if ($flash === 'cancel'): ?><div class="alert ok">Premium cancelled. You are back on the free plan.</div><?php endif; ?>
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>

    <?php if ($receipt): ?>
      <div class="receipt">
        <div class="receipt-ico">✅</div>
        <h2>Payment successful!</h2>
        <p><?= $receipt['feature'] ? 'Your feature is unlocked.' : 'You&rsquo;re now Premium &ndash; no more ads!' ?> <a href="results.php">Continue matching →</a></p>
        <dl class="facts">
          <dt>Reference no.</dt><dd><b><?= e($receipt['ref']) ?></b></dd>
          <dt>Item</dt><dd><?= e($receipt['plan']) ?></dd>
          <dt>Subtotal</dt><dd><?= peso($receipt['subtotal']) ?></dd>
          <?php if ($receipt['discount'] > 0): ?><dt><?= e($receipt['dlabel']) ?></dt><dd>&minus;<?= peso($receipt['discount']) ?></dd><?php endif; ?>
          <dt>Total paid</dt><dd><b><?= peso($receipt['amount']) ?></b></dd>
          <dt>Paid with</dt><dd><?= e($receipt['method']) ?></dd>
          <dt>Active until</dt><dd><?= e(date('F j, Y', strtotime($receipt['until']))) ?></dd>
        </dl>
        <?php if ($receipt['key'] === 'bank'): ?><pre class="bank"><?= e(BANK_DETAILS) ?>
Reference: <?= e($receipt['ref']) ?></pre><p class="hint">In the live app, access activates once the transfer is verified.</p><?php endif; ?>
      </div>
    <?php elseif ($premium && !$featureMode): ?>
      <div class="note">⭐ Premium active until <b><?= e(date('F j, Y', strtotime($rec['premium_until']))) ?></b> &ndash; ad-free. You can extend it below.
        <form method="post" style="display:inline"><input type="hidden" name="action" value="cancel"><button class="mini-link" style="border:0;background:none;cursor:pointer">Cancel (demo)</button></form></div>
    <?php endif; ?>

    <?php if (!$featureMode): ?>
    <div class="grid grid-2" style="margin-bottom:30px">
      <div class="card"><h3>Free</h3>
        <ul class="check-list">
          <li>Travel profile &amp; preference-based matching</li>
          <li>Browse up to <?= FREE_LIMIT ?> profiles</li>
          <li>Profile photo, name, and age only</li>
          <li>In-app messaging with matches</li>
          <li>Pop-up advertisements</li>
        </ul></div>
      <div class="card accent"><h3>Premium</h3>
        <ul class="check-list">
          <li><b>No ads</b> &ndash; no pop-ups, ever</li>
          <li><b>Unlimited</b> scrolling &amp; matching</li>
          <li>Full profile descriptions &amp; availability</li>
          <li>Feedback visibility &amp; detailed compatibility</li>
          <li>Advanced search, enhanced visibility, nearby travelers</li>
        </ul></div>
    </div>
    <?php endif; ?>

    <form method="post" class="checkout" id="checkout" data-pay data-newpct="<?= (int)$newUserPct ?>">
      <input type="hidden" name="action" value="pay">
      <?php if ($featureMode): ?><input type="hidden" name="feature" value="<?= e($fkey) ?>"><?php endif; ?>

      <h3 class="sub" style="margin-top:0"><?= $featureMode ? '1. Your purchase' : '1. Choose your plan' ?></h3>
      <div class="grid <?= $featureMode ? '' : 'grid-3' ?> plan-pick" <?= $featureMode ? 'style="max-width:340px"' : '' ?>>
        <?php foreach ($plans as $key => $pl): ?>
          <label class="plan-opt <?= $key === 'annual' ? 'best' : '' ?>">
            <input type="radio" name="plan" value="<?= e($key) ?>" data-amount="<?= $pl[1] ?>" data-name="<?= e($pl[0]) ?>" <?= $selPlan === $key ? 'checked' : '' ?>>
            <span class="plan-card">
              <?php if ($key === 'annual'): ?><em class="ribbon">BEST VALUE</em><?php endif; ?>
              <b><?= e($pl[0]) ?></b><strong><?= peso($pl[1]) ?></strong><small><?= e($pl[3]) ?></small><small><?= e($pl[4]) ?></small>
            </span>
          </label>
        <?php endforeach; ?>
      </div>

      <h3 class="sub">2. Discount</h3>
      <?php if ($newUserPct): ?><p class="disc-line">🎉 <b>New User Discount: 10% off</b> your first purchase &ndash; applied automatically.</p><?php endif; ?>
      <label class="check-inline"><input type="checkbox" name="student" value="1" id="student" <?= $student ? 'checked' : '' ?>> I am a student traveler (20% off, subject to ID verification)</label>
      <p class="hint">Only the best discount applies; discounts do not stack.</p>

      <h3 class="sub">3. Mode of payment</h3>
      <?php render_pay_methods($selMethod, $selLocal); ?>

      <div class="order-sum">
        <div><span>Subtotal</span><b id="osSub">₱0</b></div>
        <div id="osDiscRow"><span id="osDiscLbl">Discount</span><b id="osDisc">−₱0</b></div>
        <div class="grand"><span>Total today</span><b id="payTotal">₱0</b></div>
      </div>
      <div class="pay-total">
        <a href="rewards.php" class="mini-link">← Back</a>
        <button class="btn pay-submit" type="submit">🔒 Pay &amp; <?= $featureMode ? 'Unlock' : 'Subscribe' ?></button>
      </div>
      <p class="hint" style="text-align:center">Demo checkout: no real payment is processed and no card details are collected. A live version would connect a payment gateway such as PayMongo, Xendit, Stripe, or PayPal.</p>
    </form>
  </div>
</section>
<script>
  window.payUpdate = function (form) {
    var plan = form.querySelector('input[name=plan]:checked');
    var sub = plan ? Number(plan.dataset.amount) : 0;
    var student = document.getElementById('student').checked;
    var newPct = Number(form.dataset.newpct || 0);
    var pct = student ? Math.max(20, newPct) : newPct;
    var disc = Math.round(sub * pct / 100);
    var f = function (n) { return '₱' + Number(n).toLocaleString(); };
    document.getElementById('osSub').textContent = f(sub);
    document.getElementById('osDisc').textContent = '−' + f(disc);
    document.getElementById('osDiscLbl').textContent = pct ? 'Discount (' + pct + '%)' : 'Discount';
    document.getElementById('osDiscRow').style.display = pct ? '' : 'none';
    document.getElementById('payTotal').textContent = f(sub - disc);
  };
</script>
<?php include 'includes/footer.php'; ?>
