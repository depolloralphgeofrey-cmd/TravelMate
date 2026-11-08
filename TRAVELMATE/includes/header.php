<?php
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/monetize.php';
$showAds = empty($noAds) && show_ads();   // free users get pop-up ads; Premium users never do
$pageTitle   = isset($pageTitle) ? $pageTitle : 'Home';
$currentPage = isset($currentPage) ? $currentPage : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#0F172A">
  <title><?= htmlspecialchars($pageTitle) ?> | <?= SITE_NAME ?></title>
  <meta name="description" content="TravelMate - a travel buddy matching app that connects solo travelers with compatible companions.">
  <link rel="icon" href="assets/images/logo.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header" id="siteHeader">
  <div class="container nav-wrap">
    <a href="home.php" class="brand">
      <img src="assets/images/logo.svg" alt="TravelMate logo" width="40" height="46">
      <span class="brand-text">Travel<span>Mate</span></span>
    </a>
    <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
    <nav class="main-nav" id="mainNav">
      <?php foreach ($NAV as $key => $item): ?>
        <a href="<?= $item[0] ?>" class="<?= $key === $currentPage ? 'active' : '' ?>"><?= $item[1] ?></a>
      <?php endforeach; ?>
      <a href="subscribe.php" class="btn btn-small nav-cta">Get Premium</a>
      <?php $navPhoto = user_photo(); ?>
      <a href="myprofile.php" class="nav-profile <?= $currentPage === 'myprofile' ? 'active' : '' ?>" title="<?= htmlspecialchars(current_user()['email']) ?>">
        <span class="mini-av"><?php if ($navPhoto): ?><img src="<?= htmlspecialchars($navPhoto) ?>" alt=""><?php else: ?><?= htmlspecialchars(strtoupper(substr(current_user()['name'], 0, 1))) ?><?php endif; ?></span>
        My Profile
      </a>
      <a href="logout.php" class="logout-link">Logout</a>
    </nav>
  </div>
</header>
<main>
