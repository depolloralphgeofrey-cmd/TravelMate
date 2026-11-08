<?php
require_once __DIR__ . '/includes/matching.php';
require_once __DIR__ . '/includes/monetize.php';
require_login();

$rec = user_rec();
if (!has_profile($rec)) { header('Location: profile.php'); exit; }
$email = current_user()['email'];
$error = ''; $ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(strip_tags($_POST['name'] ?? ''));
    $bio  = trim(strip_tags($_POST['bio'] ?? ''));
    $ints = array_values(array_intersect((array)($_POST['interests'] ?? []), INTERESTS));
    $photo = $rec['photo'] ?? '';
    $removePhoto = !empty($_POST['remove_photo']);

    if ($name === '' || mb_strlen($name) > 60) {
        $error = 'Please enter your name (up to 60 characters).';
    } elseif (!$ints) {
        $error = 'Please pick at least one interest.';
    } else {
        // ----- profile picture upload -----
        if (!empty($_FILES['photo']['name'])) {
            $f = $_FILES['photo'];
            $types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
            if ($f['error'] !== UPLOAD_ERR_OK) {
                $error = $f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE ? 'That image is too large (max 2 MB).' : 'Upload failed. Please try again.';
            } elseif ($f['size'] > 2 * 1024 * 1024) {
                $error = 'That image is too large (max 2 MB).';
            } else {
                $info = @getimagesize($f['tmp_name']);
                if (!$info || !isset($types[$info[2]])) {
                    $error = 'Please upload a JPG, PNG, or WEBP image.';
                } else {
                    $dir = __DIR__ . '/uploads/avatars';
                    if (!is_dir($dir)) { mkdir($dir, 0775, true); }
                    $fname = substr(md5($email), 0, 12) . '-' . time() . '.' . $types[$info[2]];
                    if (move_uploaded_file($f['tmp_name'], $dir . '/' . $fname)) {
                        if ($photo && file_exists(__DIR__ . '/' . $photo)) { @unlink(__DIR__ . '/' . $photo); }
                        $photo = 'uploads/avatars/' . $fname;
                        $removePhoto = false;
                    } else {
                        $error = 'Could not save the image. Check that the uploads folder is writable.';
                    }
                }
            }
        }
        if (!$error && $removePhoto && $photo) {
            if (file_exists(__DIR__ . '/' . $photo)) { @unlink(__DIR__ . '/' . $photo); }
            $photo = '';
        }
        if (!$error) {
            update_user_rec(function (&$r) use ($name, $bio, $ints, $photo) {
                $r['name'] = $name; $r['bio'] = mb_substr($bio, 0, 200); $r['photo'] = $photo;
                $r['profile']['interests'] = $ints;
            });
            $_SESSION['user']['name'] = $name;
            header('Location: myprofile.php?saved=1'); exit;
        }
    }
    $rec['profile']['interests'] = $ints ?: ($rec['profile']['interests'] ?? []);
    $rec['name'] = $name; $rec['bio'] = $bio;
}
if (isset($_GET['saved'])) { $ok = 'Your profile has been updated.'; $rec = user_rec(); }

$p = $rec['profile'];
$photo = user_photo();
$displayName = $rec['name'] ?? current_user()['name'];
$premium = is_premium($rec);

$pageTitle = 'My Profile';
$currentPage = 'myprofile';
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs">Account</div>
    <h1>My Profile</h1>
    <p>This is how other travelers see you. Keep it fresh to get better matches.</p>
  </div>
</section>

<section>
  <div class="container">
    <?php if ($ok): ?><div class="alert ok"><?= e($ok) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>

    <div class="profile-layout">
      <aside class="card profile-side">
        <div class="avatar xl photo-wrap" id="avatarPreview">
          <?php if ($photo): ?><img src="<?= e($photo) ?>" alt="Your profile picture"><?php else: ?><span><?= e(strtoupper(substr($displayName, 0, 1))) ?></span><?php endif; ?>
        </div>
        <h2><?= e($displayName) ?></h2>
        <p class="hint"><?= e($email) ?></p>
        <p><?= $premium ? '<span class="pill-premium">⭐ Premium</span>' : '<span class="pill-free">Free plan</span>' ?></p>
        <p class="hint">🎁 <b><?= points_balance($rec) ?></b> points &bull; <a href="rewards.php">Rewards &amp; features</a></p>
        <?php if (feature_active('visibility', $rec)): ?><p><span class="verified">🚀 Boosted profile visibility</span></p><?php endif; ?>
        <?php if (!empty($rec['bio'])): ?><p class="bio">&ldquo;<?= e($rec['bio']) ?>&rdquo;</p><?php endif; ?>

        <h4>My interests</h4>
        <div class="chips" style="justify-content:center">
          <?php foreach ($p['interests'] as $k => $in): ?><span class="chip <?= ['', 'o', 'p'][$k % 3] ?>"><?= e($in) ?></span><?php endforeach; ?>
        </div>

        <dl class="facts">
          <dt>Traveler type</dt><dd><?= e($p['type'] ?? '—') ?></dd>
          <dt>Budget</dt><dd><?= e($p['budget'] ?? '—') ?></dd>
          <dt>Travel style</dt><dd><?= e($p['style'] ?? '—') ?></dd>
          <dt>Places I want to visit</dt><dd><?= e($p['places'] ?? '—') ?></dd>
          <dt>When I travel</dt><dd><?= e($p['when'] ?? '—') ?></dd>
        </dl>
        <a class="btn btn-small" href="profile.php">Edit all travel preferences</a>
      </aside>

      <div>
        <form method="post" enctype="multipart/form-data" class="card form">
          <h3>Edit profile</h3>
          <div>
            <label for="photo">Profile picture</label>
            <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp">
            <p class="hint">JPG, PNG, or WEBP &bull; max 2 MB &bull; square photos look best.</p>
            <?php if ($photo): ?><label class="check-inline"><input type="checkbox" name="remove_photo" value="1"> Remove my current picture</label><?php endif; ?>
          </div>
          <div><label for="name">Full name</label><input id="name" name="name" maxlength="60" value="<?= e($displayName) ?>" required></div>
          <div><label for="bio">Short bio</label><textarea id="bio" name="bio" rows="3" maxlength="200" placeholder="Tell other travelers about yourself…"><?= e($rec['bio'] ?? '') ?></textarea></div>
          <div>
            <label>Interests</label>
            <div class="pills">
              <?php foreach (INTERESTS as $in): ?>
                <label class="pill"><input type="checkbox" name="interests[]" value="<?= e($in) ?>" <?= in_array($in, $p['interests'], true) ? 'checked' : '' ?>><span><?= e($in) ?></span></label>
              <?php endforeach; ?>
            </div>
          </div>
          <button class="btn" type="submit">Save changes</button>
        </form>

        <?php if (!empty($rec['trips'])): ?>
          <div class="card" style="margin-top:20px">
            <h3>✈️ My trips</h3>
            <div class="table-wrap" style="margin-bottom:0"><table>
              <thead><tr><th>Trip</th><th>Dates</th><th>With</th><th class="num">My share</th><th>Ref.</th></tr></thead>
              <tbody><?php foreach (array_reverse($rec['trips']) as $tr): ?>
                <tr><td><?= e($tr['dest']) ?></td><td><?= e(date('M j', strtotime($tr['start']))) ?>–<?= e(date('M j, Y', strtotime($tr['end']))) ?></td><td><?= e($tr['with']) ?></td><td class="num"><?= peso($tr['share']) ?></td><td><?= e($tr['ref']) ?></td></tr>
              <?php endforeach; ?></tbody>
            </table></div>
          </div>
        <?php endif; ?>

        <?php if (!empty($rec['payments'])): ?>
          <div class="card" style="margin-top:20px">
            <h3>Payment history</h3>
            <div class="table-wrap" style="margin-bottom:0"><table>
              <thead><tr><th>Date</th><th>Plan</th><th>Method</th><th class="num">Amount</th><th>Ref.</th></tr></thead>
              <tbody><?php foreach (array_reverse($rec['payments']) as $pay): ?>
                <tr><td><?= e(date('M j, Y', strtotime($pay['at']))) ?></td><td><?= e($pay['plan']) ?></td><td><?= e($pay['method']) ?></td><td class="num"><?= peso($pay['amount']) ?></td><td><?= e($pay['ref']) ?></td></tr>
              <?php endforeach; ?></tbody>
            </table></div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<script>
  // live preview of the chosen picture
  var inp = document.getElementById('photo');
  if (inp) inp.addEventListener('change', function () {
    var f = this.files[0]; if (!f) return;
    var box = document.getElementById('avatarPreview');
    box.innerHTML = '<img alt="Preview">';
    box.firstChild.src = URL.createObjectURL(f);
  });
</script>
<?php include 'includes/footer.php'; ?>
