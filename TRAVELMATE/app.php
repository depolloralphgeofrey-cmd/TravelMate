<?php
$pageTitle = 'The Travel Buddy App';
$currentPage = 'app';
include 'includes/header.php';

$free = [
  ['👤', 'Travel Profile Creation', 'Indicate hobbies, interests, destinations, activities, budget, travel dates, and travel style.'],
  ['🧩', 'Preference-Based Matching', 'Matches you with companions who share similar interests and preferences.'],
  ['👀', 'Profile Viewing', 'View potential companions. Profile photo, name, and age are visible; the full description is unlocked via Premium.'],
  ['💬', 'In-App Messaging', 'Chat with matched travelers inside the app.'],
  ['⭐', 'Profile Feedback', 'Leave feedback on your experience. Viewing feedback from others is a Premium feature.'],
  ['✨', 'Personalized Suggestions', 'Recommended companions and destinations based on your preferences.'],
];
$premium = [
  ['📍', 'Nearby Traveler Access', 'Discover compatible travelers in or near your selected destination.'],
  ['🌐', 'Unlimited Location Control', 'Change and select locations without limits when searching.'],
  ['🔎', 'Advanced Traveler Search', 'Extra filters: interests, budget, destination, dates, and travel style.'],
  ['📝', 'Feedback Visibility', 'See feedback from other users before connecting.'],
  ['🚀', 'Enhanced Profile Visibility', 'Appear higher and be seen by more potential companions.'],
  ['🎯', 'Advanced Recommendations', 'More detailed recommendations based on your travel preferences.'],
];
$questions = [
  'What are your hobbies and interests?' => 'Photography, Food and dining, Hiking, Shopping, Beach activities, Nightlife, Culture and history',
  'What types of destinations do you prefer?' => 'Beaches, Mountains, Cities, Historical sites, Nature destinations, Theme parks',
  'What activities do you enjoy while traveling?' => 'Adventure activities, Sightseeing, Trying local food, Relaxing, Nightlife, Cultural experiences',
  'What type of traveler are you?' => 'Adventure seeker, Nature lover, Foodie, Cultural traveler, Leisure traveler, Backpacker',
  'What is your preferred travel budget?' => 'Budget, Moderate, Luxury',
  'What is your preferred travel style?' => 'Relaxed, Fast-paced, Flexible, Planned/itinerary-based',
  'What destinations or places do you want to visit?' => 'Open answer',
  'When do you plan or prefer to travel?' => 'Open answer',
];
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="home.php">Home</a> / The App</div>
    <h1>The Travel Buddy App</h1>
    <p>Find someone who matches your destination, dates, budget, and travel style.</p>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">App Preview</span>
      <h2>Your journey in four screens</h2>
    </div>
    <div class="grid grid-4">
      <div class="reveal">
        <div class="phone" style="width:240px"><div class="phone-screen" style="min-height:400px">
          <div class="phone-top" style="text-align:center"><img src="assets/images/logo.svg" width="46" alt="" style="margin:0 auto 6px;filter:brightness(1.1)"><h4>TravelMate</h4><small>Find a Travel Match.</small></div>
          <div class="phone-body"><div class="phone-btn">Log in / Sign up</div><div class="field">Continue with Google</div><div class="field">Continue with Facebook</div><div class="field">Create Profile</div></div>
        </div></div><div class="phone-label">1. Welcome / Login</div>
      </div>
      <div class="reveal">
        <div class="phone" style="width:240px"><div class="phone-screen" style="min-height:400px">
          <div class="phone-top"><small>Where are you going? 🌍</small><h4>Find your match</h4></div>
          <div class="phone-body"><div class="field">📍 Destination</div><div class="field">📅 Travel dates</div><div class="field">💰 Budget</div><div class="field">👥 Number of travelers</div><div class="phone-btn">🔎 Find My Travel Match</div></div>
        </div></div><div class="phone-label">2. Home Screen</div>
      </div>
      <div class="reveal">
        <div class="phone" style="width:240px"><div class="phone-screen" style="min-height:400px">
          <div class="phone-top"><small>Match Results</small><h4>Maria, 21 &bull; 85%</h4></div>
          <div class="phone-body"><div class="match-card"><b>📍 Tokyo, Japan</b><br>📅 Dec 10–15<br>💰 ₱30–40K<div class="chips"><span class="chip">Food</span><span class="chip o">Photo</span><span class="chip p">Shopping</span></div><div class="phone-actions"><span>❌</span><span>👤</span><span class="like">❤️</span></div></div><div class="field">🎒 Chill &amp; Flexible</div></div>
        </div></div><div class="phone-label">3. Match Results</div>
      </div>
      <div class="reveal">
        <div class="phone" style="width:240px"><div class="phone-screen" style="min-height:400px">
          <div class="phone-top" style="text-align:center"><h4>🎉 It&rsquo;s a Travel Match!</h4><small>You and Alex matched for Japan</small></div>
          <div class="phone-body"><div class="field">💬 Chat</div><div class="field">📍 Share itinerary</div><div class="field">📅 Plan activities</div><div class="field">💰 Split expenses</div><div class="field">🚨 Report / Block</div></div>
        </div></div><div class="phone-label">4. Match &amp; Chat</div>
      </div>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Free Features</span>
      <h2>Free forever &mdash; basic matching included</h2>
    </div>
    <div class="grid grid-3">
      <?php foreach ($free as $f): ?>
        <div class="card reveal"><div class="icon"><?= $f[0] ?></div><h3><?= $f[1] ?></h3><p><?= $f[2] ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Premium Features</span>
      <h2>Go Premium. Match better.</h2>
    </div>
    <div class="grid grid-3">
      <?php foreach ($premium as $f): ?>
        <div class="card accent reveal"><div class="icon"><?= $f[0] ?></div><h3><?= $f[1] ?></h3><p><?= $f[2] ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-alt" id="pricing">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Pricing</span>
      <h2>Simple, flexible plans</h2>
      <p>Not ready to subscribe? Buy single features or earn points by watching ads.</p>
    </div>
    <div class="grid grid-4">
      <div class="card price-card reveal"><h3>Monthly</h3><div class="price">₱199<small>/mo</small></div><p>Full Premium access</p><ul><li>All 6 Premium features</li><li>Cancel anytime</li></ul></div>
      <div class="card price-card featured reveal"><span class="ribbon">BEST VALUE</span><h3>Annual</h3><div class="price">₱1,499<small>/yr</small></div><p>≈ ₱125 per month</p><ul><li>All Premium features</li><li>Save ₱889 vs monthly</li></ul></div>
      <div class="card price-card reveal"><h3>3 Months</h3><div class="price">₱499</div><p>≈ ₱166 per month</p><ul><li>All Premium features</li><li>Save ₱98 vs monthly</li></ul></div>
      <div class="card price-card reveal"><h3>Per Feature</h3><div class="price">₱70<small>/feature</small></div><p>Try before you subscribe</p><ul><li>Pick the feature you need</li><li>No commitment</li></ul></div>
    </div>

    <div class="pay-strip reveal">
      <b>Accepted payments:</b>
      <?php foreach (payment_methods() as $pm): ?><span><?= $pm[1] ?> <?= htmlspecialchars($pm[0]) ?></span><?php endforeach; ?>
    </div>

    <div class="grid grid-2" style="margin-top:36px">
      <div class="card accent-o reveal">
        <h3>🎬 Rewarded Advertisements (Google AdMob)</h3>
        <p>Watch an ad voluntarily and earn <b>5 points</b> per completed ad (max <b>5 ads/day</b> = 25 points &asymp; ₱10). Points are non-transferable and cannot be exchanged for cash. <b>Premium users see no ads at all.</b></p>
        <p style="margin-top:10px"><a href="rewards.php" class="mini-link">Earn points &rarr;</a></p>
      </div>
      <div class="card accent-p reveal">
        <h3>🎟️ Points-Based Feature Access</h3>
        <p>Use accumulated points to temporarily unlock selected Premium features instead of paying directly &mdash; a &ldquo;try before you buy&rdquo; option that may lead to a full subscription.</p>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Matching Algorithm</span>
      <h2>How we find your compatible match</h2>
      <p>The algorithm compares shared interests, preferred destinations, activities, budget, travel dates, and travel style, then recommends users with the highest compatibility.</p>
    </div>
    <div class="grid grid-2">
      <div class="reveal">
        <h3 class="sub" style="margin-top:0">Travel Preference Profile</h3>
        <?php foreach ($questions as $q => $a): ?>
          <div class="acc-item"><button class="acc-btn"><?= $q ?></button><div class="acc-panel"><div><?= $a ?></div></div></div>
        <?php endforeach; ?>
      </div>
      <div class="reveal">
        <h3 class="sub" style="margin-top:0">Try a match score demo</h3>
        <form id="matchDemo" class="calc">
          <p style="color:var(--muted);font-size:.92rem">Tick the interests you share with a traveler:</p>
          <label><input type="checkbox" style="width:auto"> Same destination</label>
          <label><input type="checkbox" style="width:auto"> Overlapping travel dates</label>
          <label><input type="checkbox" style="width:auto"> Similar budget</label>
          <label><input type="checkbox" style="width:auto"> Shared interests</label>
          <label><input type="checkbox" style="width:auto"> Same travel style</label>
          <br><button class="btn" type="submit">Calculate</button>
          <div class="calc-result" id="matchScore">— % Match</div>
        </form>

        <h3 class="sub">💰 Trip cost splitter</h3>
        <div class="calc" id="splitCalc">
          <label>Hotel (₱)</label><input type="number" data-cost value="12000">
          <label>Transportation (₱)</label><input type="number" data-cost value="6000">
          <label>Food &amp; activities (₱)</label><input type="number" data-cost value="6000">
          <label>Number of travelers</label><input type="number" id="people" value="4" min="1">
          <div class="calc-result" id="splitResult">₱0 / person</div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
