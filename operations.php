<?php
$pageTitle = 'Operations';
$currentPage = 'operations';
include 'includes/header.php';

$procedure = [
  ['Identification of User Needs', 'Identify solo travelers’ concerns: finding companions, safety, and travel costs.'],
  ['Feature Planning', 'Determine features: registration, profiles, matching, messaging, feedback, reporting/blocking, travel-plan sharing.'],
  ['App Design & Database Planning', 'IT team prepares interface, user flow, and database structure.'],
  ['Application Development', 'Developers build the app; the tourism team reviews content and functions.'],
  ['Safety & Data Protection Integration', 'User verification, reporting/blocking, secure communication, and user consent.'],
  ['Testing & Bug Fixing', 'IT team tests and corrects errors before introduction to users.'],
  ['User Testing & Improvement', 'Selected users test the app and give feedback for revisions.'],
  ['Final Review & Deployment', 'Final review, then the app is prepared for initial target users.'],
];
$sop = [
  ['Government Compliance', 'Secure SEC registration, LGU permits, BIR registration, and DOT accreditation if required.'],
  ['Application Operations', 'IT team monitors the app, performs maintenance, resolves issues, and implements approved updates.'],
  ['Data Privacy', 'Collect only necessary information, inform users, restrict access, follow the Data Privacy Act and NPC guidelines.'],
  ['Application Security', 'Authentication, secure storage, and secure network communication (OWASP MASVS).'],
  ['User Safety', 'Reporting and blocking tools; reports on suspicious accounts, harassment, scams documented and escalated.'],
  ['Quality Assurance', 'Quality Manager and Testers test features, usability, performance, and security before updates.'],
  ['Customer Support', 'Inquiries, complaints, and reports handled professionally and confidentially.'],
  ['Incident Response', 'Incidents documented, reported, investigated, and corrected.'],
  ['Workplace Cleanliness', 'Clean, organized workstations, common areas, and shared facilities.'],
  ['Monitoring', 'Regular checks on performance, user concerns, privacy compliance, security, and workplace conditions.'],
];
$foh = [
  ['Operations and Tourism Manager', 'Manages daily operations; coordinates with hotels, agencies, tour operators.'],
  ['Tourism and Partnership Officer', 'Develops partnerships with hotels, agencies, tour operators, and local guides.'],
  ['Marketing Specialist', 'Promotes TravelMate via social media, digital marketing, communities.'],
  ['Customer Support Representative', 'Assists users with questions, accounts, reports, and feedback.'],
  ['Sales Officer', 'Promotes premium memberships and tourism services; develops clients and partners.'],
];
$boh = [
  ['Accountant', 'Manages financial records, income and expenses, reports, and budget.'],
  ['IT Development & Technical Support Manager', 'Oversees development, maintenance, security, and performance.'],
  ['App Developer / Technical Staff', 'Develops, tests, updates, and maintains the app.'],
  ['Quality Manager', 'Oversees QA so features, usability, and safety meet standards.'],
  ['Tester', 'Finds bugs, technical problems, and usability issues; reports to IT and Quality teams.'],
];
$permits = [
  ['Business Name / Corporate Registration', 'DTI for sole proprietorship; SEC for partnership or corporation.'],
  ['BIR Registration', 'Tax registration, invoicing, and reporting.'],
  ['Local Business Permit', 'Makati City BPLO.'],
  ['Locational Clearance', 'If applicable, per Makati zoning and land-use requirements.'],
  ['Fire Safety Inspection Certificate (FSIC)', 'Bureau of Fire Protection.'],
  ['Certificate of Occupancy / Building Requirements', 'Where applicable to the premises.'],
  ['Tourism Accreditation / Registration', 'Department of Tourism, if tourism services require it.'],
  ['Sales Promotion Permit', 'DTI, when a covered promotional campaign is conducted.'],
];
$clean = ['Workstations are clean and organized', 'Shared equipment is cleaned regularly', 'Common areas are free from clutter', 'Trash is properly disposed of', 'Shared facilities are maintained', 'Equipment is properly stored after use'];
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="home.php">Home</a> / Operations</div>
    <h1>Technical Plan</h1>
    <p>How the Travel Buddy App is built, operated, protected, and permitted.</p>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Products &amp; Procedures</span>
      <h2>From idea to deployment</h2>
    </div>
    <div class="timeline">
      <?php foreach ($procedure as $i => $p): ?>
        <div class="tl-item reveal"><b>STEP <?= $i + 1 ?></b><h3 style="font-size:1.05rem"><?= $p[0] ?></h3><p><?= $p[1] ?></p></div>
      <?php endforeach; ?>
    </div>

    <h3 class="sub">Add-ons &amp; Subscription</h3>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>Add-on</th><th>Description</th><th>Price</th></tr></thead>
        <tbody>
          <tr><td>Premium Subscription</td><td>Optional paid service with enhanced app features.</td><td>₱199 / month</td></tr>
          <tr><td>Tourism-Related Booking</td><td>Commission from tourism transactions or partner establishments.</td><td>10% commission</td></tr>
          <tr><td>Advertisements (Google AdMob)</td><td>Rewarded ads: users earn 5 points per completed ad (max 5/day = 25 points &asymp; ₱10), redeemable for selected Premium features. Free users also see pop-up ads; Premium users see none. Points are non-transferable and not exchangeable for cash.</td><td>Points-based</td></tr>
          <tr><td>Partner Listing</td><td>Featured listing for partner tourism establishments.</td><td>₱5,000</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Software &amp; Tools</span>
      <h2>What the IT team builds with</h2>
    </div>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>Software</th><th>Description</th></tr></thead>
        <tbody>
          <tr><td><b>Visual Studio Code</b></td><td>Free, open-source code editor used to write, edit, and debug the Travel Buddy source code.</td></tr>
          <tr><td><b>GitHub</b></td><td>Source code management, version control, and team collaboration with private repositories.</td></tr>
          <tr><td><b>MySQL (Managed Cloud Database)</b></td><td>Stores user profiles, travel preferences, bookings, and other application data.</td></tr>
          <tr><td><b>phpMyAdmin</b></td><td>Free web interface for viewing, editing, and organizing MySQL records.</td></tr>
          <tr><td><b>Netlify</b></td><td>Cloud platform for deploying and hosting web application frontends.</td></tr>
          <tr><td><b>Google AdMob</b></td><td>Mobile advertising platform used for rewarded ads and the points system.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container prose">
    <div class="section-head reveal">
      <span class="eyebrow">Standard Operating Procedures</span>
      <h2>SOP for TravelMate &amp; Travel Buddy</h2>
      <p>Guidelines for organized operations, user protection, quality service, and compliance with government and data privacy standards.</p>
    </div>
    <?php foreach ($sop as $i => $s): ?>
      <div class="acc-item reveal"><button class="acc-btn"><?= ($i + 1) . '. ' . $s[0] ?></button><div class="acc-panel"><div><?= $s[1] ?></div></div></div>
    <?php endforeach; ?>
    <div class="card accent reveal" style="margin-top:30px">
      <h3>Cleanliness Checklist <small style="font-weight:400;color:var(--muted)">(daily)</small></h3>
      <ul class="check-list"><?php foreach ($clean as $c): ?><li><?= $c ?></li><?php endforeach; ?></ul>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Front &amp; Back of House</span>
      <h2>Who does what</h2>
    </div>
    <div class="grid grid-2">
      <div class="reveal">
        <h3 class="sub" style="margin-top:0">Front of House (FOH)</h3>
        <div class="table-wrap"><table><tbody><?php foreach ($foh as $r): ?><tr><td><b><?= $r[0] ?></b></td><td><?= $r[1] ?></td></tr><?php endforeach; ?></tbody></table></div>
      </div>
      <div class="reveal">
        <h3 class="sub" style="margin-top:0">Back of House (BOH)</h3>
        <div class="table-wrap"><table><tbody><?php foreach ($boh as $r): ?><tr><td><b><?= $r[0] ?></b></td><td><?= $r[1] ?></td></tr><?php endforeach; ?></tbody></table></div>
      </div>
    </div>
    <div class="prose reveal">
      <h3 class="sub">Management Operations</h3>
      <p>The manager supervises daily activities, coordinates between departments, monitors employee performance, addresses operational concerns, and oversees coordination with tourism partners. Regular communication keeps responsibilities clear and tasks on schedule, and management practices are evaluated periodically.</p>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Permits &amp; Licenses</span>
      <h2>Compliance before full operations</h2>
    </div>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>#</th><th>Requirement</th><th>Details</th></tr></thead>
        <tbody><?php foreach ($permits as $i => $p): ?><tr><td><?= $i + 1 ?></td><td><b><?= $p[0] ?></b></td><td><?= $p[1] ?></td></tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
