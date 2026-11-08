<?php
$pageTitle = 'Our Team';
$currentPage = 'team';
include 'includes/header.php';

function initials($n) { $p = preg_split('/\s+/', trim(preg_replace('/[()]/', '', $n))); return strtoupper(substr($p[0], 0, 1) . (isset($p[1]) ? substr($p[count($p) - 1], 0, 1) : '')); }

function org_photo($key) {
    if (!$key) return '';
    if (preg_match('#^https?://#i', $key)) return $key;            // full image link
    // Look in the TRAVELMATE root folder first, then assets/images/team/ (case-insensitive file names)
    foreach (['', 'assets/images/team/', 'images/', '../'] as $dir) {
        $path = __DIR__ . '/' . $dir;
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $x) {
            foreach ([$key . '.' . $x, strtolower($key) . '.' . $x, ucfirst(strtolower($key)) . '.' . $x] as $name) {
                if (is_file($path . $name)) return $dir . $name;
            }
        }
        foreach ((array)@scandir($path) as $f) {                      // fallback: any letter-case
            if (strcasecmp(pathinfo($f, PATHINFO_FILENAME), $key) === 0 && preg_match('/\.(jpe?g|png|webp)$/i', $f)) return $dir . $f;
        }
    }
    return '';
}
function org_avatar($name, $key, $vacant = false) {
    $src = org_photo($key);
    if ($src) return '<div class="avatar photo"><img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($name) . '" loading="lazy"></div>';
    return '<div class="avatar">' . ($vacant ? '?' : initials($name)) . '</div>';
}

$columns = [
  'OPERATING TEAM' => [
    ['Alleine Ivory T. Sudla', 'Operations & Tourism Manager', false, 'ivy1by1'],
    ['Raileigh Callah R. Serafin', 'Tourism & Partnership Officer', false, 'cali1by1'],
    ['(Name)', 'Accountant', true, ''],
  ],
  'TECHNOLOGY TEAM' => [
    ['Ralph Geofrey G. Depollo', 'IT Development & Technical Support Manager', false, 'Bap1by1'],
    ['Laurence C. Mendoza', 'App Developer / Technical Staff', false, 'Yenk1by1'],
  ],
  'QUALITY TEAM' => [
    ['(Name)', 'Quality Manager', true, ''],
    ['(Name)', 'Testers', true, ''],
  ],
  'MARKETING TEAM' => [
    ['Lara Alliyah O. Santos', 'Marketing Specialist', false, 'Lara1by1'],
    ['Jaimee Reica C. Silvestre', 'Customer Service Representative', false, 'Jaimee1by1'],
    ['Nicola Salve Leona G. Laguitan', 'Sales Officer', false, 'Le1by1'],
  ],
];

$jobs = [
  ['Managing Partners', 'Responsible for overall management and direction. Make major business decisions, manage finances, develop strategies, and supervise departments.', ['Graduate or knowledgeable in Tourism, Business, Management or related field', 'Strong leadership and decision-making skills', 'Good communication and organizational skills', 'Knowledge of business operations and financial management']],
  ['Operations and Tourism Manager', 'Oversees daily operations and develops partnerships with travel agencies, hotels, tour operators, and other tourism businesses.', ['Background in Tourism Management, Hospitality, or Business', 'Knowledge of tourism operations', 'Strong communication and partnership-building skills', 'Organized; able to manage multiple tasks']],
  ['Tourism and Partnership Officer', 'Communicates and coordinates with hotels, travel agencies, tour operators, local guides, and tourism establishments.', ['Background in Tourism, Hospitality, Marketing or related', 'Good communication and negotiation skills', 'Knowledge of tourism products and services', 'Ability to build professional relationships']],
  ['Accountant', 'Manages financial records and transactions; records income and expenses, prepares reports, monitors budget, and assists financial decisions.', ['Bachelor’s degree in Accountancy/Accounting/Finance', 'Must be a Certified Public Accountant (CPA)', 'Knowledge of basic accounting principles', 'Proficient in Microsoft Excel or accounting software', 'Honest; maintains confidentiality']],
  ['IT Development and Technical Support Manager', 'Oversees development, maintenance, security, and technical performance of the Travel Buddy app.', ['Background in IT or Computer Science', '1–3 years relevant experience', 'Knowledge of mobile app development', 'Knowledge of data security and system maintenance', 'Strong problem-solving skills']],
  ['App Developer / Technical Staff', 'Develops, tests, updates, and maintains the mobile app and helps resolve technical issues.', ['Background in IT, Computer Science or Software Development', '1–3 years mobile/software development experience', 'Knowledge of databases and basic cybersecurity', 'Strong analytical and problem-solving skills']],
  ['Quality Manager', 'Oversees quality assurance so features, usability, and user experience meet quality and safety standards.', ['Background in IT, Computer Science, Tourism, Business or related', 'Knowledge of QA/QC processes', 'Detail-oriented; good leadership skills', 'Able to identify issues and recommend improvements']],
  ['Tester', 'Evaluates functionality, usability, compatibility, and performance before launch and during major updates. Engaged per project.', ['Background in IT or Computer Science', 'Basic knowledge of software/mobile app testing', 'Attention to detail', 'Able to document errors accurately']],
  ['Marketing Specialist', 'Promotes TravelMate through social media, digital campaigns, tourism communities, and partnerships.', ['Background in Marketing, Tourism, Business', 'Knowledge of social media and digital marketing', 'Creative; able to create promotional campaigns']],
  ['Customer Support Representative', 'Assists users with inquiries, account concerns, technical concerns, reports, and feedback.', ['Good communication and interpersonal skills', 'Patient and customer-oriented', 'Basic knowledge of mobile apps', 'Able to handle complaints professionally']],
  ['Sales Officer', 'Promotes and sells premium memberships and tourism-related services; develops potential clients and partners.', ['Background in Sales, Marketing, Tourism or Business', 'Persuasive and goal-oriented', 'Knowledge of CRM', 'Good negotiation skills']],
];

$wages = [
  ['Managing Partners', '₱40,000–₱50,000 + 5% annual net profit sharing per Managing Partner'],
  ['Operations and Tourism Manager', '₱30,000–₱40,000'],
  ['Tourism and Partnership Officer', '₱22,000–₱30,000'],
  ['Accountant', '₱20,000–₱28,000'],
  ['IT Development and Technical Support Manager', '₱35,000–₱50,000'],
  ['App Developer / Technical Staff', '₱25,000–₱40,000'],
  ['Quality Manager', '₱22,000–₱28,000'],
  ['Tester', 'Project-based fee (per scope and duration of testing)'],
  ['Marketing Specialist', '₱22,000–₱28,000'],
  ['Customer Support Representative', '₱18,000–₱25,000'],
  ['Sales Officer', '₱20,000–₱25,000 + 5% incentive on sales above quota + ₱10,000 monthly travel & representation allowance'],
];

$uniform = [
  ['Monday', 'Type A – White TravelMate Polo'],
  ['Tuesday', 'Type B – Navy TravelMate Polo'],
  ['Wednesday', 'Wash Day – Casual / Comfortable Attire'],
  ['Thursday', 'Type A – White TravelMate Polo'],
  ['Friday', 'Smart Casual – Professional but relaxed attire'],
];
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="home.php">Home</a> / Team</div>
    <h1>Management Plan &amp; Team</h1>
    <p>A partnership that combines tourism expertise with technical skills.</p>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Type of Ownership</span>
      <h2>Organized as a Partnership</h2>
      <p>Business partnerships and strategic alliances contribute to organizational performance by promoting collaboration, efficiency, and market reach (Muwada &amp; Richard, 2026). The tourism team handles operations, marketing, customer service, and tourism coordination; the technical team handles development, maintenance, and security of the app.</p>
    </div>

    <h3 class="sub" style="text-align:center">Organizational Chart</h3>
    <div class="org reveal">
      <div class="org-level">
        <div class="org-node top"><?= org_avatar('Melissa Rose C. Orpilla', 'Mel1by1') ?><b>Melissa Rose C. Orpilla</b><span>General Partner A</span></div>
        <div class="org-node top"><?= org_avatar('Ma. Zenaida M. Cinco', 'Zen1by1') ?><b>Ma. Zenaida M. Cinco</b><span>General Partner B</span></div>
      </div>
      <div class="team-cols">
        <?php foreach ($columns as $team => $people): ?>
          <div class="team-col">
            <h4><?= $team ?></h4>
            <?php foreach ($people as $p): ?>
              <div class="org-node <?= $p[2] ? 'vacant' : '' ?>">
                <?= org_avatar($p[0], $p[3], $p[2]) ?>
                <b><?= $p[0] ?></b><span><?= $p[1] ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container prose">
    <div class="section-head reveal">
      <span class="eyebrow">Job Positions</span>
      <h2>Descriptions &amp; Specifications</h2>
    </div>
    <?php foreach ($jobs as $j): ?>
      <div class="acc-item reveal">
        <button class="acc-btn"><?= $j[0] ?></button>
        <div class="acc-panel"><div>
          <h5>Job Description</h5><p><?= $j[1] ?></p>
          <h5>Job Specifications</h5><ul><?php foreach ($j[2] as $s): ?><li><?= $s ?></li><?php endforeach; ?></ul>
        </div></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Compensation &amp; Wages</span>
      <h2>Fair pay for a startup</h2>
      <p>Compensation is based on position, responsibilities, qualifications, and skills, and follows DOLE and NWPC regulations for NCR.</p>
    </div>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>Position</th><th>Proposed Monthly Compensation</th></tr></thead>
        <tbody>
          <?php foreach ($wages as $w): ?><tr><td><?= $w[0] ?></td><td><?= $w[1] ?></td></tr><?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="grid grid-2">
      <div class="card accent reveal">
        <h3>Benefits &amp; Privileges</h3>
        <ul>
          <li>SSS, PhilHealth, and Pag-IBIG contributions</li>
          <li>13th-month pay (at least 1/12 of basic salary earned)</li>
          <li>Service Incentive Leave, holiday, premium, overtime, and night-shift pay when applicable</li>
          <li>Performance incentives, training, recognition, team building</li>
          <li>Communication/internet allowance and flexible work for qualified roles</li>
        </ul>
      </div>
      <div class="card accent-o reveal">
        <h3>Employment Policies</h3>
        <ul>
          <li>Selection based on qualifications and skills</li>
          <li>Punctuality &mdash; report 1 hour before opening; inform supervisor if late/absent</li>
          <li>Prescribed uniform and professional appearance</li>
          <li>Limited personal cellphone use during work</li>
          <li>Confidentiality and data privacy at all times</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Work Hours &amp; Uniform</span>
      <h2>Mon–Fri, 9:00 AM – 6:00 PM</h2>
      <p>With a one-hour meal break (12:00–1:00 PM) for eight regular working hours per day, consistent with Articles 83 and 85 of the Labor Code. IT staff and testers follow a hybrid schedule (up to 3 remote days, at least 2 office days weekly); the Sales Officer follows a flexible schedule.</p>
    </div>
    <div class="grid grid-2">
      <div class="reveal">
        <div class="table-wrap"><table>
          <thead><tr><th>Day</th><th>Uniform</th></tr></thead>
          <tbody><?php foreach ($uniform as $u): ?><tr><td><?= $u[0] ?></td><td><?= $u[1] ?></td></tr><?php endforeach; ?></tbody>
        </table></div>
      </div>
      <div class="card reveal">
        <h3>Wash Day Guidelines</h3>
        <p><b>May wear:</b> clean T-shirt/casual top, jeans or casual pants, clean sneakers, TravelMate colors, neat clothing.</p>
        <p style="margin-top:10px"><b>Avoid:</b> slippers, ripped clothing, crop tops, overly revealing outfits, and offensive graphics/text.</p>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Weekly Roster</span>
      <h2>Proposed staffing schedule</h2>
      <p>A draft roster that may change as the business moves from app development to actual operations. The 8:00 AM&ndash;5:00 PM shift lets one key holder open the office; the 10:00 AM&ndash;7:00 PM shift lets the other stay for closing.</p>
    </div>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>Position</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th></tr></thead>
        <tbody>
          <?php
          $a = '9:00 AM – 6:00 PM'; $o = '8:00 AM – 5:00 PM'; $c = '10:00 AM – 7:00 PM'; $h = 'Hybrid / project-based'; $f = 'Flexible schedule';
          $roster = [
            ['Managing Partners', [$a, $a, $a, $a, $a]],
            ['Operations and Tourism Manager', [$o, $c, $o, $c, $o]],
            ['Tourism and Partnership Officer', [$c, $o, $c, $o, $c]],
            ['Accountant', [$a, $a, $a, $a, $a]],
            ['IT Development & Technical Support Manager', [$h, $h, $h, $h, $h]],
            ['App Developer / Technical Staff', [$h, $h, $h, $h, $h]],
            ['Quality Manager', [$a, $a, $a, $a, $a]],
            ['Tester', [$h, $h, $h, $h, $h]],
            ['Marketing Specialist', [$a, $a, $a, $a, $a]],
            ['Customer Support Representative', [$a, $a, $a, $a, $a]],
            ['Sales Officer', [$f, $f, $f, $f, $f]],
          ];
          foreach ($roster as $r): ?>
            <tr><td><b><?= $r[0] ?></b></td><?php foreach ($r[1] as $cell): ?><td><?= $cell ?></td><?php endforeach; ?></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Payroll Policies</span>
      <h2>Accurate, timely, and compliant</h2>
    </div>
    <div class="grid grid-3">
      <div class="card reveal"><h3>💵 Salary</h3><p>Paid twice a month (15th and last working day). NCR wage orders are followed; minimum wage range cited: ₱718–₱755 (Madrazo, 2026).</p></div>
      <div class="card reveal"><h3>⏱️ Overtime</h3><p>Authorized overtime is paid at regular hourly rate plus at least 25%. Night shift differential is at least 10%. Managerial staff are exempt based on actual duties.</p></div>
      <div class="card reveal"><h3>🏛️ Deductions</h3><p>SSS (15% MSC; 10% employer / 5% employee), PhilHealth, Pag-IBIG (2% + 2%, max ₱10,000 fund salary), and BIR withholding tax.</p></div>
      <div class="card reveal"><h3>🗂️ Records</h3><p>Payslips for every employee; employment and payroll records kept at least three years.</p></div>
      <div class="card reveal"><h3>🔒 Confidentiality</h3><p>Payroll data is accessible only to authorized management, accounting, or admin personnel.</p></div>
      <div class="card reveal"><h3>🕘 Attendance</h3><p>Digital or manual time records form the basis of regular hours, overtime, and adjustments.</p></div>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>