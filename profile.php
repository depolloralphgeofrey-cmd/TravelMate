<?php
require_once __DIR__ . '/includes/matching.php';
require_login();

$rec = user_rec();
$p = $rec['profile'] ?? [];
$error = '';

function pick($key, $allowed) {
    $v = $_POST[$key] ?? [];
    $v = is_array($v) ? $v : [$v];
    return array_values(array_intersect($v, $allowed));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new = [
        'interests'  => pick('interests', INTERESTS),
        'dest_types' => pick('dest_types', DEST_TYPES),
        'activities' => pick('activities', ACTIVITIES),
        'type'       => pick('type', TRAVELER_TYPES)[0] ?? '',
        'budget'     => pick('budget', BUDGETS)[0] ?? '',
        'style'      => pick('style', STYLES)[0] ?? '',
        'places'     => trim(strip_tags($_POST['places'] ?? '')),
        'when'       => trim(strip_tags($_POST['when'] ?? '')),
    ];
    if (!$new['interests'] || !$new['dest_types'] || !$new['activities'] || !$new['type'] || !$new['budget'] || !$new['style'] || $new['places'] === '' || $new['when'] === '') {
        $error = 'Please answer all 8 questions so we can find your best travel match.';
        $p = $new;
    } else {
        update_user_rec(function (&$r) use ($new) { $r['profile'] = $new; });
        header('Location: find.php'); exit;
    }
}

$pageTitle = 'Create Profile';
$currentPage = 'match';
include 'includes/header.php';

function pills($name, $options, $selected, $type = 'checkbox') {
    $sel = is_array($selected) ? $selected : [$selected];
    foreach ($options as $o) {
        $n = $type === 'checkbox' ? $name . '[]' : $name;
        echo '<label class="pill"><input type="' . $type . '" name="' . $n . '" value="' . e($o) . '"' . (in_array($o, $sel, true) ? ' checked' : '') . '><span>' . e($o) . '</span></label>';
    }
}
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs">Step 1 of 4</div>
    <h1>Create your travel profile</h1>
    <p>Hi <?= e(current_user()['name']) ?>! Tell us how you like to travel and we&rsquo;ll personalize your matches.</p>
  </div>
</section>

<section>
  <div class="container prose" style="max-width:860px">
    <?php flow_bar(1); ?>
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="q-form">
      <div class="q-card"><h3>1. What are your hobbies and interests?</h3><div class="pills"><?php pills('interests', INTERESTS, $p['interests'] ?? []); ?></div></div>
      <div class="q-card"><h3>2. What types of destinations do you prefer?</h3><div class="pills"><?php pills('dest_types', DEST_TYPES, $p['dest_types'] ?? []); ?></div></div>
      <div class="q-card"><h3>3. What activities do you enjoy while traveling?</h3><div class="pills"><?php pills('activities', ACTIVITIES, $p['activities'] ?? []); ?></div></div>
      <div class="q-card"><h3>4. What type of traveler are you?</h3><div class="pills"><?php pills('type', TRAVELER_TYPES, $p['type'] ?? '', 'radio'); ?></div></div>
      <div class="q-card"><h3>5. What is your preferred travel budget?</h3><div class="pills"><?php pills('budget', BUDGETS, $p['budget'] ?? '', 'radio'); ?></div>
        <p class="hint">Budget ₱10,000–₱25,000 &bull; Moderate ₱25,000–₱50,000 &bull; Luxury ₱50,000 and up</p></div>
      <div class="q-card"><h3>6. What is your preferred travel style?</h3><div class="pills"><?php pills('style', STYLES, $p['style'] ?? '', 'radio'); ?></div></div>
      <div class="q-card"><h3>7. What destinations or places do you want to visit?</h3><div class="form"><input name="places" placeholder="e.g. Tokyo, Palawan, Seoul" value="<?= e($p['places'] ?? '') ?>"></div></div>
      <div class="q-card"><h3>8. When do you plan or prefer to travel?</h3><div class="form"><input name="when" placeholder="e.g. December 2026, long weekends" value="<?= e($p['when'] ?? '') ?>"></div></div>
      <button class="btn" type="submit" style="width:100%">Save Profile &amp; Continue →</button>
    </form>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
