</main>
<footer class="site-footer">
  <div class="container footer-grid">
    <div>
      <a href="home.php" class="brand brand-light">
        <img src="assets/images/logo.svg" alt="TravelMate logo" width="40" height="46">
        <span class="brand-text">Travel<span>Mate</span></span>
      </a>
      <p class="footer-tag"><?= SITE_TAGLINE ?></p>
      <p class="muted-light">A safer, more affordable, and more meaningful way to travel together.</p>
    </div>
    <div>
      <h4>Explore</h4>
      <ul>
        <?php foreach ($NAV as $item): ?>
          <li><a href="<?= $item[0] ?>"><?= $item[1] ?></a></li>
        <?php endforeach; ?>
        <li><a href="references.php">References</a></li>
      </ul>
    </div>
    <div>
      <h4>Visit Us</h4>
      <p class="muted-light"><?= SITE_ADDRESS ?></p>
      <p class="muted-light">Mon–Fri, 9:00 AM – 6:00 PM</p>
      <p class="muted-light"><?= SITE_EMAIL ?></p>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <p>&copy; <?= date('Y') ?> TravelMate. A business plan by BSITTM3A &ndash; School of Nutrition and Hospitality Management, Centro Escolar University Manila.</p>
    </div>
  </div>
</footer>
<script src="assets/js/main.js"></script>
<?php foreach ((array)($extraScript ?? []) as $__s): ?><script src="<?= htmlspecialchars($__s) ?>"></script>
<?php endforeach; ?>
<?php if (!empty($showAds)) { include __DIR__ . '/ad_popup.php'; } ?>
</body>
</html>
