<?php
require_once __DIR__ . '/includes/matching.php';
require_login();

$rec = user_rec();
if (!has_profile($rec)) { header('Location: profile.php'); exit; }
$s = $rec['search'] ?? [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new = [
        'destination' => trim(strip_tags($_POST['destination'] ?? '')),
        'start'       => $_POST['start'] ?? '',
        'end'         => $_POST['end'] ?? '',
        'budget'      => $_POST['budget'] ?? '',
        'travelers'   => max(1, min(20, (int)($_POST['travelers'] ?? 1))),
    ];
    $ds = strtotime($new['start']); $de = strtotime($new['end']);
    if ($new['destination'] === '') {
        $error = 'Please enter your destination.';
    } elseif (!$ds || !$de) {
        $error = 'Please choose your travel dates.';
    } elseif ($de < $ds) {
        $error = 'Your return date must be on or after your departure date.';
    } elseif (!in_array($new['budget'], BUDGETS, true)) {
        $error = 'Please choose a budget.';
    } else {
        $new['destination'] = ucwords(strtolower($new['destination']));
        update_user_rec(function (&$r) use ($new) { $r['search'] = $new; });
        header('Location: matching.php'); exit;
    }
    $s = $new;
}

$pageTitle = 'Where are you going?';
$currentPage = 'match';
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs">Step 2 of 4</div>
    <h1>Where are you going? 🌍</h1>
    <p>Find someone who matches your destination, dates, budget, and travel style.</p>
  </div>
</section>

<section>
  <div class="container" style="max-width:760px">
    <?php flow_bar(2); ?>
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="calc form">
      <div><label for="destination">📍 Destination</label>
        <input id="destination" name="destination" list="dests" placeholder="e.g. Tokyo, Japan" value="<?= e($s['destination'] ?? '') ?>" required>
        <datalist id="dests"><?php foreach (OTHER_DEST as $d): ?><option value="<?= e($d) ?>"><?php endforeach; ?></datalist></div>
      <div class="grid grid-2" style="gap:14px">
        <div><label for="start">📅 Travel dates &ndash; from</label><input id="start" type="date" name="start" value="<?= e($s['start'] ?? '') ?>" required></div>
        <div><label for="end">📅 To</label><input id="end" type="date" name="end" value="<?= e($s['end'] ?? '') ?>" required></div>
      </div>
      <div><label for="budget">💰 Budget</label>
        <select id="budget" name="budget" required>
          <option value="">Choose your budget</option>
          <?php foreach (BUDGETS as $b): ?><option value="<?= $b ?>" <?= ($s['budget'] ?? '') === $b ? 'selected' : '' ?>><?= $b ?> (<?= BUDGET_LABELS[$b] ?>)</option><?php endforeach; ?>
        </select></div>
      <div><label for="travelers">👥 Number of travelers</label><input id="travelers" type="number" name="travelers" min="1" max="20" value="<?= (int)($s['travelers'] ?? 1) ?>" required></div>
      <button class="btn" type="submit">🔎 Find My Travel Match</button>
      <p class="hint" style="text-align:center"><a href="profile.php">Edit my travel profile</a></p>
    </form>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
