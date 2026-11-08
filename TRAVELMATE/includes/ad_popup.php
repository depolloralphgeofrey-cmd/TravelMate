<?php
// Pop-up (interstitial) ad shown ONLY to users who are not subscribed.
// Included from footer.php when $showAds is true. Premium users never receive this markup.
$adCreatives = [
    ['emoji' => '🏝️', 'brand' => 'Sunrise Beach Resort', 'head' => 'Weekend escape: 3 nights from ₱6,999', 'text' => 'Beachfront rooms, breakfast included. Sample partner ad.', 'cta' => 'View deal'],
    ['emoji' => '✈️', 'brand' => 'SkyHop Airlines', 'head' => 'Seat sale to Japan & Korea', 'text' => 'Book early and save on your next group trip. Sample partner ad.', 'cta' => 'Check flights'],
    ['emoji' => '🧭', 'brand' => 'Island Trails Tours', 'head' => 'Island-hopping day tours', 'text' => 'Small groups, local guides, free pickup. Sample partner ad.', 'cta' => 'Book a tour'],
];
?>
<div class="ad-pop" id="adPop" role="dialog" aria-modal="true" aria-label="Advertisement" hidden>
  <div class="ad-box">
    <div class="ad-top">
      <span class="ad-tag">Ad</span>
      <small>Google AdMob &middot; demo ad unit</small>
      <button type="button" id="adClose" disabled>Close in <b id="adCount">5</b></button>
    </div>
    <div class="ad-body" id="adBody"></div>
    <div class="ad-foot">
      <a href="subscribe.php">✨ Remove ads with Premium</a>
      <a href="rewards.php">🎁 Earn points instead</a>
    </div>
  </div>
</div>
<script>window.TM_ADS = <?= json_encode(['delay' => POPUP_DELAY_SEC, 'gap' => POPUP_GAP_SEC, 'unit' => ADMOB_INTERSTITIAL_UNIT, 'creatives' => $adCreatives], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;</script>
<script src="assets/js/ads.js"></script>
