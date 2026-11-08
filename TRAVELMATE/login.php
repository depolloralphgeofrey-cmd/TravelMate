<?php
require_once __DIR__ . '/includes/config.php';
if (is_logged_in()) { header('Location: ' . landing_url()); exit; }

$error = ''; $ok = ''; $mode = 'login';
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action   = $_POST['action'] ?? 'login';
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $users    = load_users();

    if ($action === 'signup') {
        $mode = 'signup';
        $name = trim(strip_tags($_POST['name'] ?? ''));
        $confirm = $_POST['confirm'] ?? '';
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter your name and a valid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif (isset($users[$email])) {
            $error = 'That email is already registered. Please log in.';
        } else {
            $users[$email] = ['name' => $name, 'password' => password_hash($password, PASSWORD_DEFAULT), 'created' => date('c')];
            save_users($users);
            $_SESSION['user'] = ['name' => $name, 'email' => $email];
            session_regenerate_id(true);
            header('Location: profile.php'); exit;
        }
    } else {
        if (isset($users[$email]) && password_verify($password, $users[$email]['password'])) {
            $_SESSION['user'] = ['name' => $users[$email]['name'], 'email' => $email];
            session_regenerate_id(true);
            header('Location: ' . landing_url()); exit;
        }
        $error = 'Incorrect email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log in | TravelMate</title>
  <link rel="icon" href="assets/images/logo.svg" type="image/svg+xml">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <div class="auth-logo">
      <img src="assets/images/logo.svg" alt="TravelMate logo" width="64">
      <h1>Travel<span>Mate</span></h1>
      <p>Find a Travel Match.</p>
    </div>

    <div class="auth-tabs">
      <button type="button" data-tab="login" class="<?= $mode === 'login' ? 'on' : '' ?>">Log in</button>
      <button type="button" data-tab="signup" class="<?= $mode === 'signup' ? 'on' : '' ?>">Sign up</button>
    </div>

    <?php if ($error): ?><div class="alert err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" class="form auth-form <?= $mode === 'login' ? 'on' : '' ?>" id="form-login">
      <input type="hidden" name="action" value="login">
      <div><label for="le">Email</label><input id="le" type="email" name="email" value="<?= $mode === 'login' ? htmlspecialchars($email) : '' ?>" required></div>
      <div><label for="lp">Password</label><input id="lp" type="password" name="password" required></div>
      <button class="btn" type="submit">Log in</button>
    </form>

    <form method="post" class="form auth-form <?= $mode === 'signup' ? 'on' : '' ?>" id="form-signup">
      <input type="hidden" name="action" value="signup">
      <div><label for="sn">Full name</label><input id="sn" name="name" value="<?= htmlspecialchars($name) ?>" required></div>
      <div><label for="se">Email</label><input id="se" type="email" name="email" value="<?= $mode === 'signup' ? htmlspecialchars($email) : '' ?>" required></div>
      <div><label for="sp">Password</label><input id="sp" type="password" name="password" minlength="6" required></div>
      <div><label for="sc">Confirm password</label><input id="sc" type="password" name="confirm" minlength="6" required></div>
      <button class="btn" type="submit">Create account</button>
    </form>

    <p class="auth-foot">Travel farther. Match better. Travel together. 💜</p>
  </div>
  <script>
    document.querySelectorAll('.auth-tabs button').forEach(function (b) {
      b.addEventListener('click', function () {
        document.querySelectorAll('.auth-tabs button').forEach(function (x) { x.classList.toggle('on', x === b); });
        document.getElementById('form-login').classList.toggle('on', b.dataset.tab === 'login');
        document.getElementById('form-signup').classList.toggle('on', b.dataset.tab === 'signup');
      });
    });
  </script>
</body>
</html>
