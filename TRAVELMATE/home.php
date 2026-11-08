<?php
$pageTitle = 'Find a Travel Match';
$currentPage = 'home';
include 'includes/header.php';
?>
<section class="hero">
  <div class="container">
    <div>
      <span class="badge">✈️ Travel Buddy App &bull; Makati City, Philippines</span>
      <h1>Never travel <em>alone</em> unless you want to.</h1>
      <p class="lead">TravelMate connects solo travelers with compatible companions based on destination, travel dates, budget, interests, and travel style &mdash; for safer, more affordable, and more meaningful trips.</p>
      <div class="hero-actions">
        <a href="app.php" class="btn">Explore the App</a>
        <a href="about.php" class="btn btn-outline">Our Story</a>
      </div>
    </div>
    <div>
      <div class="phone">
        <div class="phone-screen">
          <div class="phone-top"><small>Where are you going? 🌍</small><h4>Recommended Travel Matches</h4></div>
          <div class="phone-body">
            <div class="match-card">
              <div class="row"><div class="avatar">M</div><div><b>Maria, 21</b><br>📍 Tokyo, Japan</div><span class="score">85% Match</span></div>
              📅 Dec. 10–15 &nbsp;💰 ₱30,000–₱40,000
              <div class="chips"><span class="chip">Food trips</span><span class="chip o">Photography</span><span class="chip p">Shopping</span></div>
              <div class="phone-actions"><span>❌ Pass</span><span>👤 Profile</span><span class="like">❤️ Like</span></div>
            </div>
            <div class="match-card">
              <div class="row"><div class="avatar">A</div><div><b>Alex, 24</b><br>📍 Tokyo, Japan</div><span class="score">78% Match</span></div>
              📅 Dec. 9–16 &nbsp;💰 ₱35,000
              <div class="chips"><span class="chip">Hiking</span><span class="chip o">Culture</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="stats">
  <div class="container">
    <div class="grid grid-4 reveal">
      <div class="stat"><b data-count="30" data-suffix="–50">30–50</b><span>Initial user target</span></div>
      <div class="stat"><b data-count="199" data-suffix="">₱199</b><span>Premium / month</span></div>
      <div class="stat"><b data-count="3">3</b><span>Revenue streams</span></div>
      <div class="stat"><b>Nov 2026</b><span>App launch target</span></div>
    </div>
  </div>
</div>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">The Problem</span>
      <h2>Solo travel is exciting &mdash; and sometimes hard</h2>
      <p>Solo travelers commonly face the same four challenges. TravelMate was built to solve each one.</p>
    </div>
    <div class="grid grid-4">
      <div class="card accent reveal"><div class="icon">🛡️</div><h3>Safety</h3><p>Verified profiles, reporting and blocking, and travel-plan sharing help users connect with confidence.</p></div>
      <div class="card accent-o reveal"><div class="icon">🤝</div><h3>Loneliness</h3><p>Meet people who share your destination, schedule, and travel style.</p></div>
      <div class="card accent-p reveal"><div class="icon">💰</div><h3>Costs</h3><p>Share hotel, transport, food, and activity expenses with your travel match.</p></div>
      <div class="card accent reveal"><div class="icon">🔍</div><h3>Trust</h3><p>Profile feedback and compatibility scores make it easier to find reliable companions.</p></div>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">How It Works</span>
      <h2>From profile to travel match in 4 steps</h2>
    </div>
    <div class="grid grid-4 steps">
      <div class="step reveal"><h3>Create your profile</h3><p>Share your interests, preferred destinations, budget, dates, and travel style.</p></div>
      <div class="step reveal"><h3>Get matched</h3><p>Our algorithm ranks travelers by compatibility percentage.</p></div>
      <div class="step reveal"><h3>Like &amp; chat</h3><p>When both of you like each other, it&rsquo;s a Travel Match! Start chatting in-app.</p></div>
      <div class="step reveal"><h3>Plan &amp; go</h3><p>Share itineraries, plan activities, split expenses, and travel together.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="container split">
    <div class="reveal">
      <span class="eyebrow">Who It&rsquo;s For</span>
      <h2>Built for the modern traveler</h2>
      <p style="margin:14px 0 18px;color:var(--muted)">TravelMate primarily targets solo travelers aged 21 and above who are looking for compatible companions for shared activities, experiences, and selected travel expenses.</p>
      <ul class="check-list">
        <li>College students &amp; young professionals</li>
        <li>Backpackers &amp; digital nomads</li>
        <li>International tourists in the Philippines</li>
        <li>Anyone who wants to share the journey</li>
      </ul>
      <br><a href="market.php" class="btn">See Our Market</a>
    </div>
    <div class="vm-card reveal">
      <h3>Travel farther. Match better. Travel together. 💜</h3>
      <p>Basic matching is free. Go Premium for unlimited matches, advanced filters, and enhanced profile visibility &mdash; starting at just ₱199/month.</p>
      <br><a href="app.php#pricing" class="btn btn-outline">View Pricing</a>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="cta reveal">
      <h2>Ready to find your travel match?</h2>
      <p>Learn about the team, the plan, and the numbers behind TravelMate.</p>
      <a href="financial.php" class="btn">View Financial Plan</a>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
