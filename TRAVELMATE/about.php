<?php
$pageTitle = 'About Us';
$currentPage = 'about';
include 'includes/header.php';

$objectives = [
  ['Aug 2026', 'Identify the common concerns and challenges of solo travelers regarding safety, expenses, and finding compatible companions — the basis for the app.'],
  ['Aug – Mid-Sep 2026', 'Design and develop the Travel Buddy App with IT developers: user registration, profiles, preference-based matching, and in-app communication.'],
  ['1st week of Oct 2026', 'Complete the application so core features and the matching system are operational and ready for user testing.'],
  ['Oct – Nov 2026', 'Conduct user testing and gather feedback on functionality, usability, and overall experience.'],
  ['By Nov 2026', 'Acquire at least 30 initial users (target up to 50) through promotions, personal networks, and travel communities.'],
  ['Nov 2026', 'Present the fully functional app and its initial user results as the final outcome of the study.'],
];
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="home.php">Home</a> / About</div>
    <h1>About TravelMate</h1>
    <p>A travel buddy matching app that makes solo travel more enjoyable, social, and convenient.</p>
  </div>
</section>

<section>
  <div class="container prose reveal">
    <span class="eyebrow">Introduction</span>
    <h2>Why TravelMate?</h2><br>
    <p>The Travel Buddy App is a proposed mobile application designed to help solo travelers find compatible travel companions going to the same destination. It addresses difficulty finding trustworthy companions, loneliness, safety concerns, and the high cost of traveling alone.</p>
    <p>Research shows mobile apps play a growing role in tourism. Kuo et al. (2019) found that e-servicescape and electronic word-of-mouth influence intentions to adopt tourism apps, while Hamouda (2022) showed perceived usefulness and attitude affect users&rsquo; intention to use them. Birenboim et al. (2023) highlighted the role of mobility apps in supporting travelers&rsquo; activities during trips.</p>
    <p>TravelMate combines the convenience of mobile tourism technology with compatibility-based companion matching &mdash; making solo travel more connected, affordable, and meaningful.</p>
    <div class="note"><b>Concept / Theme:</b> It allows travelers to meet new people, share travel expenses, discover new experiences together, and create meaningful friendships while exploring different destinations.</div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="grid grid-2">
      <div class="vm-card reveal"><h3>🔭 Vision</h3><p>To become a trusted and innovative travel companion platform that connects travelers with compatible companions, promoting safer, more affordable, and more meaningful travel experiences through technology, reliable connections, and personalized travel matching.</p></div>
      <div class="vm-card pink reveal"><h3>🎯 Mission</h3><p>TravelMate is dedicated to providing a safe, reliable, and accessible platform that connects travelers with compatible companions based on destinations, budgets, interests, travel dates, and travel preferences &mdash; through innovative technology and trusted connections.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Name &amp; Logo</span>
      <h2>The meaning behind TravelMate</h2>
      <p>&ldquo;Travel&rdquo; represents the main purpose of the business, while &ldquo;Mate&rdquo; refers to a companion or buddy. Tagline: <b>&ldquo;Find a Travel Match.&rdquo;</b></p>
    </div>
    <div class="split">
      <div class="reveal" style="text-align:center">
        <img src="assets/images/logo.svg" alt="TravelMate logo" width="170" style="margin:0 auto">
        <h2 style="margin-top:14px">Travel<span style="color:var(--pink)">Mate</span></h2>
        <p style="color:var(--muted)">Find a Travel Match.</p>
      </div>
      <div class="reveal">
        <ul class="check-list">
          <li><b>Location pin</b> &ndash; travel destinations</li>
          <li><b>Two travelers</b> &ndash; companionship and connection</li>
          <li><b>Airplane &amp; dotted route</b> &ndash; movement and discovery</li>
          <li><b>Mountains, water &amp; sunset</b> &ndash; adventure and memorable experiences</li>
        </ul>
        <h3 class="sub">Brand colors</h3>
        <div class="palette">
          <div class="swatch"><i style="background:#1B2A4A"></i><div>Dark Blue<br>Trust, safety</div></div>
          <div class="swatch"><i style="background:#FF2E93"></i><div>Pink<br>Energy</div></div>
          <div class="swatch"><i style="background:#FF7A00"></i><div>Orange<br>Adventure</div></div>
          <div class="swatch"><i style="background:#7C3AED"></i><div>Purple<br>Creativity</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Objectives</span>
      <h2>Our roadmap to launch</h2>
    </div>
    <div class="timeline">
      <?php foreach ($objectives as $o): ?>
        <div class="tl-item reveal"><b><?= $o[0] ?></b><p><?= $o[1] ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Feasible Location</span>
      <h2>Headquartered in the heart of Makati CBD</h2>
      <p>Ayala Triangle Gardens Tower 2, Paseo de Roxas corner Makati Avenue, Makati City &mdash; surrounded by offices, hotels, restaurants, and major transport access.</p>
    </div>
    <div class="map-box reveal">
      <iframe title="TravelMate HQ map" loading="lazy" src="https://www.google.com/maps?q=Ayala+Triangle+Gardens+Tower+2,+Makati&output=embed"></iframe>
    </div>
    <div class="prose reveal" style="margin-top:24px">
      <p>Since TravelMate&rsquo;s main product is a mobile app, a small office or coworking space is enough for administration, meetings, customer support, and operations. The multi-storey building also offers networking and partnership opportunities with neighboring businesses (Chin, 2020).</p>
      <p><b>Office lease.</b> TravelMate will lease a 48-sqm office in Makati City. Based on an online property listing (Facebook Marketplace), the space is advertised at ₱21,000 per month, reduced from its original ₱40,000, and is on the 21st floor with its own toilet. Annual rent is estimated at ₱252,000, with an initial lease payment of ₱63,000 (two-month security deposit of ₱42,000 plus one month advance rent of ₱21,000). All figures are preliminary estimates.</p>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
