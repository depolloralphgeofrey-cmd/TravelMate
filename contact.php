<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$pageTitle = 'Contact';
$currentPage = 'contact';

$status = ''; $msg = '';
$name = $email = $subject = $message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim(strip_tags($_POST['name'] ?? ''));
    $email   = trim($_POST['email'] ?? '');
    $subject = trim(strip_tags($_POST['subject'] ?? ''));
    $message = trim(strip_tags($_POST['message'] ?? ''));

    if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = 'err';
        $msg = 'Please enter your name, a valid email address, and a message.';
    } else {
        // Save message to data/messages.txt (create folder if missing)
        $dir = __DIR__ . '/data';
        if (!is_dir($dir)) { mkdir($dir, 0775, true); }
        $line = '[' . date('Y-m-d H:i:s') . "] $name <$email> | $subject | " . str_replace(["\r", "\n"], ' ', $message) . PHP_EOL;
        file_put_contents($dir . '/messages.txt', $line, FILE_APPEND | LOCK_EX);
        $status = 'ok';
        $msg = 'Thank you, ' . htmlspecialchars($name) . '! Your message has been received. We will get back to you soon.';
        $name = $email = $subject = $message = '';
    }
}
include 'includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="home.php">Home</a> / Contact</div>
    <h1>Contact Us</h1>
    <p>Questions, partnership ideas, or feedback? We&rsquo;d love to hear from you.</p>
  </div>
</section>

<section>
  <div class="container split" style="align-items:start">
    <div class="reveal">
      <h2>Send us a message</h2><br>
      <?php if ($status): ?><div class="alert <?= $status ?>"><?= $msg ?></div><?php endif; ?>
      <form class="form" method="post" action="contact.php">
        <div><label for="name">Full name</label><input id="name" name="name" value="<?= htmlspecialchars($name) ?>" required></div>
        <div><label for="email">Email</label><input id="email" type="email" name="email" value="<?= htmlspecialchars($email) ?>" required></div>
        <div><label for="subject">Subject</label>
          <select id="subject" name="subject">
            <?php foreach (['General inquiry', 'Partnership', 'Feedback', 'Join the beta'] as $s): ?>
              <option <?= $subject === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
          </select></div>
        <div><label for="message">Message</label><textarea id="message" name="message" rows="5" required><?= htmlspecialchars($message) ?></textarea></div>
        <button class="btn" type="submit">Send Message</button>
      </form>
    </div>
    <div class="reveal">
      <div class="card accent">
        <h3>📍 Headquarters</h3><p><?= SITE_ADDRESS ?></p><br>
        <h3>🕘 Office Hours</h3><p>Monday to Friday, 9:00 AM – 6:00 PM</p><br>
        <h3>✉️ Email</h3><p><?= SITE_EMAIL ?></p>
      </div>
      <div class="map-box" style="margin-top:20px"><iframe title="Map" loading="lazy" src="https://www.google.com/maps?q=Ayala+Triangle+Gardens+Tower+2,+Makati&output=embed"></iframe></div>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
