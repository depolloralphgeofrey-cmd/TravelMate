<?php
$pageTitle = 'Market & Marketing';
$currentPage = 'market';
include 'includes/header.php';

$seg = [
  'Demographic' => ['Age: 21+', 'Gender: Female / Male', 'Occupation: College students, digital nomads, young professionals, tourists'],
  'Geographic' => ['Country: Worldwide, targeting users from different countries and regions, including Asia, Europe, North America, South America, Africa, and Oceania', 'Location: Urban areas and tourism destinations', 'Market reach: Domestic and international', 'Tourist type: Backpackers, international tourists, solo travelers'],
  'Psychographic' => ['Hobbies: Traveling, exploring, meeting new people', 'Lifestyle: Travel-oriented and experience-seeking', 'Personality: Sociable, adventurous, independent'],
  'Behavioral' => ['Purchasing: willing to spend on travel services and shared costs', 'Readiness: actively planning a trip; interested in premium features', 'Usage: uses mobile apps to plan trips and discover services'],
];
$fourP = [
  ['🧳', 'Product', 'A Travel Buddy mobile app with free basic features (matching, profile creation, profile viewing, in-app messaging, feedback) and Premium features (nearby traveler access, location control, feedback visibility, advanced search, enhanced visibility).'],
  ['🏷️', 'Price', 'A free basic version lets users explore the essential features. The Premium Subscription is ₱199 per user per month (enhanced matching, advanced traveler searches, increased profile visibility, extra travel-planning features). Other revenue: advertisements and commissions from completed bookings with tourism partners such as hotels, travel agencies, and tour operators.'],
  ['📱', 'Place', 'Distributed digitally via the mobile app. HQ at Ayala Triangle Gardens Tower 2, Makati City serves as the administrative base; users can access the app regardless of the HQ location.'],
  ['📣', 'Promotion', 'Social media, digital marketing, tourism communities, personal networks, and partnerships. The first 10 registered users get selected premium features free for one month.'],
];
$discounts = [
  ['New User', '10% off', 'First purchase after completing travel profile'],
  ['Seasonal', '5% off', 'Selected premium features during holidays, summer vacations, Christmas, long weekends'],
  ['Referral', '5% referrer / 10% referred', 'Referrer gets 5% off next purchase; new user gets 10% off first'],
  ['Off-Peak Season', '20% off', 'Premium matching services during low-travel periods'],
  ['Peak Season', '10% off', 'Limited-period purchases during high demand'],
  ['Student Traveler', '20% off', 'Verified student users'],
];
$ipo = [
  'Input' => ['TravelMate mobile app', 'Target market & customer needs', 'Unique features and benefits', 'Social media platforms & travel communities', 'PUBMATs, videos, reels, stories', 'Promotional objectives & user target'],
  'Process' => ['Determine target market needs', 'Establish brand identity & value proposition', 'Create promotional materials', 'Promote on Facebook, Instagram, TikTok', 'Run scheduled campaigns & referral promos', 'Collect feedback and monitor performance'],
  'Output' => ['Greater brand recognition', 'Growth in social reach & interactions', 'Increase in registrations', 'Higher engagement & travel buddy matches', 'Feedback, referrals, improved experience', 'Attainment of 30–50 initial users'],
];
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="home.php">Home</a> / Market</div>
    <h1>Economic Plan</h1>
    <p>Target market, segmentation, and marketing strategies for TravelMate.</p>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Target Market</span>
      <h2>Solo travelers aged 21 and above</h2>
      <p>College students, backpackers, digital nomads, young professionals, and international tourists who seek compatible companions for shared activities, experiences, and selected travel expenses. The age criterion reflects the need for independent decision-making and financial responsibility (Fan et al., 2021).</p>
    </div>
    <div class="grid grid-4">
      <?php $i = 0; foreach ($seg as $name => $items): $cls = ['accent', 'accent-o', 'accent-p', 'accent'][$i++ % 4]; ?>
        <div class="card <?= $cls ?> reveal"><h3><?= $name ?></h3><ul><?php foreach ($items as $it): ?><li><?= $it ?></li><?php endforeach; ?></ul></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Marketing Mix</span>
      <h2>The 4Ps of TravelMate</h2>
      <p>The marketing mix contributes to customer satisfaction (Yasmin, 2026).</p>
    </div>
    <div class="grid grid-4">
      <?php foreach ($fourP as $p): ?>
        <div class="card reveal"><div class="icon"><?= $p[0] ?></div><h3><?= $p[1] ?></h3><p><?= $p[2] ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Conceptual Framework</span>
      <h2>Input &ndash; Process &ndash; Output</h2>
      <p>The IPO model shows how resources are transformed through marketing activities into expected results (Alshaye et al., 2020).</p>
    </div>
    <div class="grid grid-3">
      <?php $c = ['accent', 'accent-o', 'accent-p']; $k = 0; foreach ($ipo as $title => $items): ?>
        <div class="card <?= $c[$k++] ?> reveal"><h3><?= $title ?></h3><ul><?php foreach ($items as $it): ?><li><?= $it ?></li><?php endforeach; ?></ul></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Guest Protocol</span>
      <h2>Discount protocols</h2>
      <p>A systematic approach to promotions and pricing that considers the financial impact on the business (Salazar, 2026).</p>
    </div>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>Discount</th><th>Rate</th><th>Details</th></tr></thead>
        <tbody><?php foreach ($discounts as $d): ?><tr><td><b><?= $d[0] ?></b></td><td><?= $d[1] ?></td><td><?= $d[2] ?></td></tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
