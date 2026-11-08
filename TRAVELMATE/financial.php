<?php
$pageTitle = 'Financial Plan';
$currentPage = 'financial';
include 'includes/header.php';

// All figures below follow Chapter VI (Financial Plan) of the TravelMate business plan.
function sumcol($rows, $i) { $t = 0; foreach ($rows as $r) $t += $r[$i]; return $t; }

// A. Building and facility cost (Table 12) — [item, description, qty, unit cost]
$facility = [
  ['Office Renovation', 'Minor wall repairs, surface preparation, and repainting', 1, 90000],
  ['Electrical Installation', 'Wiring, grounded outlets, switches, circuit breakers', 1, 35000],
  ['Internet Installation – PLDT Fiber Business (500 Mbps)', 'Fiber line, modem/ONT, network setup, Cat6 cables', 1, 25000],
  ['Water Installation', 'Connection to building water supply and basic plumbing', 1, 25000],
  ['Lighting Installation – LED Ceiling Lights', 'LED lights, switches, electrical connections', 1, 20000],
  ['Office Signage – Acrylic TravelMate Sign', 'Logo and name in brand colors', 1, 5000],
];
// B. Lease (Table 13)
$rent = 21000; $deposit = $rent * 2; $advance = $rent; $lease = $deposit + $advance;
$areas = [['Administrative and Management Area', 10], ['Customer Support Area', 12], ['Technical / App Development Area', 12], ['Meeting Area', 8], ['Storage and Utility Area', 6]];
// C. Furniture and fixtures (IKEA) — [item, qty, unit]
$furniture = [
  ['Office Table | LINNMON Desk', 12, 1999], ['Office Chair | ELDBERGET Swivel Chair', 12, 2499],
  ['Filing Cabinet | HELMER Drawer Unit', 1, 2499], ['Small Meeting Table | MITTZON Conference Table', 1, 11999],
  ['Storage Shelf | BAGGEBO Bookcase', 2, 1799], ['Pedal Waste Bin | TURRIDNING Pedal Bin', 3, 599], ['Window Blinds | SCHOTTIS', 2, 399],
];
// D. Machines, equipment and tools
$equipment = [
  ['Laptop for IT Development Team | Lenovo IdeaPad Slim 3', 2, 29999], ['Laptop for Accountant | Acer Aspire Lite AL14 32P-C5PN', 1, 24999],
  ['Smartphone for App Testing | TECNO SPARK Go 3 128GB', 2, 4999], ['Wi-Fi Router | PLDT', 1, 2000],
  ['Printer | Canon PIXMA G2010', 1, 7495], ['External Hard Drive | Seagate Expansion 1TB', 1, 1950],
  ['Power Strip | OMNI Universal Outlet Extension Cord (3-Gang)', 3, 449], ['Headset with Microphone | Logitech H111', 2, 450],
  ['Basic Tools and Office Supplies', 1, 2500],
];
// Software cost (per month)
$software = [['Visual Studio Code (VS Code)', 2, 0], ['GitHub Pro/Team', 2, 232], ['MySQL Managed Cloud Database', 2, 870], ['phpMyAdmin', 2, 0], ['Netlify Pro', 2, 1160]];

$facilityTotal = 0; foreach ($facility as $r) $facilityTotal += $r[2] * $r[3];
$furnTotal = 0; foreach ($furniture as $r) $furnTotal += $r[1] * $r[2];
$equipTotal = 0; foreach ($equipment as $r) $equipTotal += $r[1] * $r[2];
$softMonthly = 0; foreach ($software as $r) $softMonthly += $r[1] * $r[2];
$softAnnual = $softMonthly * 12;
$production = $facilityTotal + $lease + $furnTotal + $equipTotal + $softMonthly;

// Capital & loan
$members = 7; $perMember = 1000000; $capital = $members * $perMember; $loan = 1000000; $funding = $capital + $loan;

// Assets (H)
$assets = [
  'A. Current Assets' => [['Cash on Hand and in Bank (remaining cash reserve)', 3500000], ['Refundable Office Security Deposit', 42000], ['Prepaid Office Rent', 21000], ['Office Supplies', 2500]],
  'B. Property, Plant, and Equipment' => [['Furniture and Fixtures', $furnTotal], ['Machines, Equipment, and Tools (excluding office supplies)', $equipTotal - 2500]],
  'C. Leasehold Improvements and Facility Installations' => [['Office Renovation and Facility Improvements', $facilityTotal]],
];
$assetTotal = 0; foreach ($assets as $grp) foreach ($grp as $r) $assetTotal += $r[1];

// Operating expenses — [item, monthly]
$opex = [
  'A. Salary & Employee Benefits' => [['Salaries and Wages (including 2 app developers)', 341000], ['Employer SSS, PhilHealth, and Pag-IBIG Contributions', 41155]],
  'B. Office and Utility Expenses' => [['Office Rent', 21000], ['Electricity', 8000], ['Water', 1500], ['Internet Service', 3000], ['Mobile Phone and Communication Expenses', 2000]],
  'C. Daily Administrative and Operating Expenses' => [['Transportation and Fare for Business Operations', 11000], ['Office Supplies and Printing', 2000], ['Cleaning and Sanitation Supplies', 1500], ['Software and Cloud Services', $softMonthly], ['App Maintenance and Technical Support Allowance', 5000], ['Bank and Payment Processing Fees', 2000]],
  'D. Marketing and Compliance' => [['Marketing and Promotion', 10000], ['Permits and Compliance Fees Reserve', 2500]],
];
$opexMonthly = 0; foreach ($opex as $grp) foreach ($grp as $r) $opexMonthly += $r[1];

// Opening expenses
$opening = [['Ribbon-Cutting Ceremony and Simple Decorations', 4000], ['Tarpaulin and Promotional Signage', 4000], ['Brochures and Flyers', 5000], ['Food and Refreshments for Guests', 10000], ['Invitations, Name Tags, and Guest Materials', 2000], ['Photography and Documentation', 4000], ['Application Demonstration Materials and QR Code Standees', 3000], ['Contingency Fund', 3000]];
$openingTotal = sumcol($opening, 1);

// F. Sales report, first week — [day, customers, premium, booking, partner listing, ad revenue]
$week = [
  ['Day 1 – Monday', 8, 199, 500, 0, 2.5], ['Day 2 – Tuesday', 9, 199, 500, 0, 3.0], ['Day 3 – Wednesday', 10, 199, 1000, 0, 3.5],
  ['Day 4 – Thursday', 10, 199, 1000, 0, 3.5], ['Day 5 – Friday', 12, 398, 1000, 0, 4.0], ['Day 6 – Saturday', 14, 398, 1500, 0, 5.0],
  ['Day 7 – Sunday', 12, 398, 1000, 5000, 4.5],
];
$wCust = sumcol($week, 1); $wPrem = sumcol($week, 2); $wBook = sumcol($week, 3); $wPart = sumcol($week, 4); $wAd = sumcol($week, 5);
$wTotal = $wPrem + $wBook + $wPart + $wAd;

// Pre-opening (Table 28)
$pre = [['Office Renovation and Facility Installations', 200000], ['Initial Lease Requirement (Security Deposit and Advance Rent)', 63000], ['Furniture and Fixtures (tables, chairs, cabinet, meeting table, shelves, bins, blinds)', 74667], ['Machines, Equipment, and Tools (laptops, smartphones, router, printer, drive, strips, headsets, supplies)', 111187], ['Initial Month of Software and Cloud Services', 4524], ['Business Registration and Permits (planning estimate)', 30000]];
$preTotal = sumcol($pre, 1);

// Balance sheet (Table 32) and income statement (Table 33)
$depF = 4977.80; $depM = 7245.80; $depL = 6666.67; $depTotal = $depF + $depM + $depL;
$netLoss = 5082502.27;
$salesY1 = 445536;

// Sales forecast (Tables 34, 36, 37, 38) — [month, premium, booking, partner, ads, buffer]
$mon = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$y1 = [[3980,10000,5000,100],[4378,11000,5000,110],[4975,12500,5000,125],[5572,14000,10000,140],[5970,15000,10000,150],[6567,16500,10000,165],[7164,18000,10000,180],[7960,20000,15000,200],[8756,22000,15000,220],[9552,24000,15000,240],[10348,26000,20000,260],[11144,28000,20000,280]];
function forecast($rows, $mult) {
    $out = [];
    foreach ($rows as $r) {
        $p = $r[0] * $mult; $b = $r[1] * $mult; $pa = $r[2] * $mult; $a = $r[3] * $mult;
        $out[] = [$p, $b, $pa, $a, ($p + $pa + $a) * 0.05 + $b * 0.10];
    }
    return $out;
}
$f1 = forecast($y1, 1); $f2 = forecast($y1, 1.25); $f3 = forecast($y1, 1.5);
function fsum($f, $i) { $t = 0; foreach ($f as $r) $t += $r[$i]; return $t; }
function fbase($f) { return fsum($f, 0) + fsum($f, 1) + fsum($f, 2) + fsum($f, 3); }
function n2($v) { return number_format($v, 2); }
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="home.php">Home</a> / Financial</div>
    <h1>Financial Plan</h1>
    <p>Funding, startup costs, assets, operating expenses, and sales projections for TravelMate.</p>
  </div>
</section>

<section class="stats" style="padding-top:0">
  <div class="container">
    <div class="grid grid-4" style="margin-top:50px">
      <div class="stat"><b><?= peso($funding) ?></b><span>Total planned funding</span></div>
      <div class="stat"><b><?= peso($production) ?></b><span>Initial production cost</span></div>
      <div class="stat"><b><?= peso($opexMonthly) ?></b><span>Monthly operating expenses</span></div>
      <div class="stat"><b><?= peso($wTotal) ?></b><span>First-week sales forecast</span></div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Funding</span>
      <h2>Capital and proposed loan</h2>
      <p>TravelMate does not purchase land or property. It invests in essential office assets and the technology needed to run the Travel Buddy App.</p>
    </div>
    <div class="grid grid-3">
      <div class="card accent reveal"><h3>👥 Members&rsquo; capital</h3><p><?= $members ?> founding members &times; <?= peso($perMember) ?></p><div class="price" style="font-size:1.8rem"><?= peso($capital) ?></div></div>
      <div class="card accent-o reveal"><h3>🏦 Proposed business loan</h3><p>Illustrative 5-year repayment at 10% annual interest, subject to the lender&rsquo;s approval. Recognized as a liability.</p><div class="price" style="font-size:1.8rem"><?= peso($loan) ?></div></div>
      <div class="card accent-p reveal"><h3>💼 Total planned funding</h3><p>Capital plus loan, allocated to pre-opening and opening costs, app development, marketing, and working capital.</p><div class="price" style="font-size:1.8rem"><?= peso($funding) ?></div></div>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">A. Building &amp; Facility Cost</span>
      <h2>Setting up the Makati office</h2>
      <p>Ayala Triangle Gardens Tower 2: only basic renovation and installations for administration, app development, and customer support.</p>
    </div>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>Item</th><th>Description</th><th class="num">Qty</th><th class="num">Unit cost</th><th class="num">Estimated cost</th></tr></thead>
        <tbody>
          <?php foreach ($facility as $r): ?><tr><td><?= $r[0] ?></td><td><?= $r[1] ?></td><td class="num"><?= $r[2] ?></td><td class="num"><?= peso($r[3]) ?></td><td class="num"><?= peso($r[2] * $r[3]) ?></td></tr><?php endforeach; ?>
          <tr class="total"><td colspan="4">TOTAL</td><td class="num"><?= peso($facilityTotal) ?></td></tr>
        </tbody>
      </table>
    </div>

    <h3 class="sub">B. Office Lease / Rent</h3>
    <div class="grid grid-2">
      <div class="table-wrap reveal"><table>
        <thead><tr><th>Description</th><th class="num">Amount</th></tr></thead>
        <tbody>
          <tr><td>Office location</td><td class="num">Makati City</td></tr>
          <tr><td>Office size</td><td class="num">48 sqm</td></tr>
          <tr><td>Monthly rental rate (₱21,000 ÷ 48 sqm)</td><td class="num">₱<?= number_format($rent / 48, 2) ?>/sqm</td></tr>
          <tr><td>Monthly office rent</td><td class="num"><?= peso($rent) ?></td></tr>
          <tr><td>Annual rental expense (× 12)</td><td class="num"><?= peso($rent * 12) ?></td></tr>
          <tr><td>Security deposit (2 months)</td><td class="num"><?= peso($deposit) ?></td></tr>
          <tr><td>Advance rent (1 month)</td><td class="num"><?= peso($advance) ?></td></tr>
          <tr class="total"><td>Initial lease requirement</td><td class="num"><?= peso($lease) ?></td></tr>
        </tbody>
      </table></div>
      <div class="table-wrap reveal"><table>
        <thead><tr><th>Allocation of areas</th><th class="num">Size</th></tr></thead>
        <tbody>
          <?php foreach ($areas as $a): ?><tr><td><?= $a[0] ?></td><td class="num"><?= $a[1] ?> sqm</td></tr><?php endforeach; ?>
          <tr class="total"><td>Total office area</td><td class="num"><?= array_sum(array_column($areas, 1)) ?> sqm</td></tr>
        </tbody>
      </table></div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">C &amp; D. Owned Assets</span>
      <h2>Furniture, equipment &amp; tools</h2>
      <p>Furniture from IKEA, chosen for functionality, durability, and affordability. Equipment is company-owned for staff who handle confidential information.</p>
    </div>
    <div class="grid grid-2">
      <div class="table-wrap reveal"><table>
        <thead><tr><th>Furniture &amp; Fixtures</th><th class="num">Qty</th><th class="num">Unit</th><th class="num">Cost</th></tr></thead>
        <tbody>
          <?php foreach ($furniture as $f): ?><tr><td><?= $f[0] ?></td><td class="num"><?= $f[1] ?></td><td class="num"><?= peso($f[2]) ?></td><td class="num"><?= peso($f[1] * $f[2]) ?></td></tr><?php endforeach; ?>
          <tr class="total"><td colspan="3">TOTAL</td><td class="num"><?= peso($furnTotal) ?></td></tr>
        </tbody>
      </table></div>
      <div class="table-wrap reveal"><table>
        <thead><tr><th>Machine, Equipment &amp; Tools</th><th class="num">Qty</th><th class="num">Unit</th><th class="num">Cost</th></tr></thead>
        <tbody>
          <?php foreach ($equipment as $f): ?><tr><td><?= $f[0] ?></td><td class="num"><?= $f[1] ?></td><td class="num"><?= peso($f[2]) ?></td><td class="num"><?= peso($f[1] * $f[2]) ?></td></tr><?php endforeach; ?>
          <tr class="total"><td colspan="3">TOTAL</td><td class="num"><?= peso($equipTotal) ?></td></tr>
        </tbody>
      </table></div>
    </div>

    <h3 class="sub">Software cost</h3>
    <div class="table-wrap reveal" style="max-width:760px">
      <table>
        <thead><tr><th>Software</th><th class="num">Qty</th><th class="num">Unit cost / month</th><th class="num">Total / month</th></tr></thead>
        <tbody>
          <?php foreach ($software as $s): ?><tr><td><?= $s[0] ?></td><td class="num"><?= $s[1] ?></td><td class="num"><?= peso2($s[2]) ?></td><td class="num"><?= peso2($s[1] * $s[2]) ?></td></tr><?php endforeach; ?>
          <tr class="total"><td colspan="3">Total estimated monthly cost</td><td class="num"><?= peso2($softMonthly) ?></td></tr>
          <tr class="total"><td colspan="3">Total estimated annual cost</td><td class="num"><?= peso2($softAnnual) ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">E. Production Cost</span>
      <h2>Total initial production cost</h2>
      <p>TravelMate does not manufacture products. Its production cost is the facilities, assets, and resources needed to develop and support the Travel Buddy App.</p>
    </div>
    <div class="table-wrap reveal" style="max-width:640px;margin-inline:auto">
      <table>
        <thead><tr><th>Production Cost</th><th class="num">Estimated Cost</th></tr></thead>
        <tbody>
          <tr><td>Building and Facility Cost</td><td class="num"><?= peso($facilityTotal) ?></td></tr>
          <tr><td>Initial Lease Requirement</td><td class="num"><?= peso($lease) ?></td></tr>
          <tr><td>Furniture and Fixtures</td><td class="num"><?= peso($furnTotal) ?></td></tr>
          <tr><td>Machine, Equipment, and Tools</td><td class="num"><?= peso($equipTotal) ?></td></tr>
          <tr><td>Software (monthly)</td><td class="num"><?= peso($softMonthly) ?></td></tr>
          <tr class="total"><td>TOTAL PRODUCTION COST</td><td class="num"><?= peso($production) ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">H. Financial Statement</span>
      <h2>Assets of TravelMate</h2>
    </div>
    <div class="table-wrap reveal" style="max-width:820px;margin-inline:auto">
      <table>
        <thead><tr><th>Assets</th><th class="num">Estimated cost</th></tr></thead>
        <tbody>
          <?php foreach ($assets as $grp => $rows): ?>
            <tr><td colspan="2"><b><?= $grp ?></b></td></tr>
            <?php foreach ($rows as $r): ?><tr><td style="padding-left:30px"><?= $r[0] ?></td><td class="num"><?= peso($r[1]) ?></td></tr><?php endforeach; ?>
          <?php endforeach; ?>
          <tr class="total"><td>TOTAL IDENTIFIED ASSETS</td><td class="num"><?= peso($assetTotal) ?></td></tr>
        </tbody>
      </table>
    </div>

    <h3 class="sub" style="text-align:center">Estimated monthly and annual operating expenses</h3>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>Operating Expenses</th><th class="num">Monthly</th><th class="num">Annual</th></tr></thead>
        <tbody>
          <?php foreach ($opex as $grp => $rows): $sub = sumcol($rows, 1); ?>
            <tr><td colspan="3"><b><?= $grp ?></b></td></tr>
            <?php foreach ($rows as $r): ?><tr><td style="padding-left:30px"><?= $r[0] ?></td><td class="num"><?= peso($r[1]) ?></td><td class="num"><?= peso($r[1] * 12) ?></td></tr><?php endforeach; ?>
            <tr><td style="padding-left:30px"><i>Subtotal</i></td><td class="num"><i><?= peso($sub) ?></i></td><td class="num"><i><?= peso($sub * 12) ?></i></td></tr>
          <?php endforeach; ?>
          <tr class="total"><td>TOTAL ESTIMATED OPEX</td><td class="num"><?= peso($opexMonthly) ?></td><td class="num"><?= peso($opexMonthly * 12) ?></td></tr>
        </tbody>
      </table>
    </div>
    <p class="hint" style="text-align:center">Personnel is the largest cost. The Tester is contractual, so personnel expenses may decrease after the testing phase.</p>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Expense Report</span>
      <h2>Pre-opening, opening, and operating costs</h2>
    </div>
    <div class="grid grid-3">
      <div class="card accent reveal"><h3>A. Pre-opening</h3><p>Office improvements, initial lease payments, furniture, equipment, software, and a preliminary registration/permits budget (<?= peso($pre[5][1]) ?>).</p><div class="price" style="font-size:1.7rem"><?= peso($preTotal) ?></div></div>
      <div class="card accent-o reveal"><h3>B. Opening</h3><p>A simple launch event to introduce TravelMate to guests, potential users, and tourism partners.</p><div class="price" style="font-size:1.7rem"><?= peso($openingTotal) ?></div></div>
      <div class="card accent-p reveal"><h3>C. Operating</h3><p>Recurring monthly costs after launch (<?= peso($opexMonthly * 12) ?> per year).</p><div class="price" style="font-size:1.7rem"><?= peso($opexMonthly) ?> <small>/mo</small></div></div>
    </div>
    <h3 class="sub">Pre-opening expenses</h3>
    <div class="table-wrap reveal" style="max-width:820px">
      <table>
        <thead><tr><th>Description</th><th class="num">Estimated cost</th></tr></thead>
        <tbody>
          <?php foreach ($pre as $o): ?><tr><td><?= $o[0] ?></td><td class="num"><?= peso($o[1]) ?></td></tr><?php endforeach; ?>
          <tr class="total"><td>Total estimated pre-opening cash requirement</td><td class="num"><?= peso($preTotal) ?></td></tr>
        </tbody>
      </table>
    </div>
    <h3 class="sub">Opening expenses</h3>
    <div class="table-wrap reveal" style="max-width:700px">
      <table>
        <thead><tr><th>Description</th><th class="num">Estimated cost</th></tr></thead>
        <tbody>
          <?php foreach ($opening as $o): ?><tr><td><?= $o[0] ?></td><td class="num"><?= peso($o[1]) ?></td></tr><?php endforeach; ?>
          <tr class="total"><td>Total estimated opening expenses</td><td class="num"><?= peso($openingTotal) ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">G. Sales Report &ndash; First Week</span>
      <h2><?= peso($wTotal) ?> forecast in week one</h2>
      <p>Revenue comes from <b>Premium subscriptions</b> (₱199), <b>booking commissions</b> (10% of an average ₱5,000 booking = ₱500), <b>partner listings</b> (₱5,000), and <b>advertisement revenue through Google AdMob</b>.</p>
    </div>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>Day</th><th class="num">Customer forecast</th><th class="num">Premium (₱199)</th><th class="num">Booking commission (₱500)</th><th class="num">Partner listing (₱5,000)</th><th class="num">Ad revenue</th><th class="num">Sales forecast / day</th><th class="num">Average check / day</th></tr></thead>
        <tbody>
          <?php foreach ($week as $d): $day = $d[2] + $d[3] + $d[4] + $d[5]; ?>
            <tr><td><?= $d[0] ?></td><td class="num"><?= $d[1] ?></td><td class="num"><?= peso($d[2]) ?></td><td class="num"><?= peso($d[3]) ?></td><td class="num"><?= peso($d[4]) ?></td><td class="num"><?= peso2($d[5]) ?></td><td class="num"><?= peso2($day) ?></td><td class="num"><?= peso2($day / $d[1]) ?></td></tr>
          <?php endforeach; ?>
          <tr class="total"><td>TOTAL</td><td class="num"><?= $wCust ?></td><td class="num"><?= peso($wPrem) ?></td><td class="num"><?= peso($wBook) ?></td><td class="num"><?= peso($wPart) ?></td><td class="num"><?= peso2($wAd) ?></td><td class="num"><?= peso2($wTotal) ?></td><td class="num"><?= peso2($wTotal / $wCust) ?></td></tr>
        </tbody>
      </table>
    </div>

    <div class="card accent-o reveal" style="margin-top:10px">
      <h3>📺 How the advertisement revenue works</h3>
      <p>Using Google AdMob rewarded ads, users earn <b><?= AD_POINTS ?> points</b> per completed ad (max <?= AD_DAILY_MAX ?> ads/day = <?= AD_POINTS * AD_DAILY_MAX ?> points &asymp; ₱<?= number_format(points_to_peso(AD_POINTS * AD_DAILY_MAX), 0) ?>), redeemable for selected Premium features. Revenue depends on ads served, completed views, advertiser demand, and eCPM. Illustration: 1,000 eligible impressions &times; ₱<?= AD_ECPM ?> eCPM = ₱<?= AD_ECPM ?>. This is a planning assumption, not a guaranteed Google AdMob payment. Premium subscribers see no ads.</p>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Summary of Requirements</span>
      <h2>Initial and recurring financial requirements</h2>
    </div>
    <div class="table-wrap reveal" style="max-width:640px;margin-inline:auto">
      <table>
        <thead><tr><th>Expense category</th><th class="num">Estimated amount</th></tr></thead>
        <tbody>
          <tr><td>Pre-Opening Cash Requirement</td><td class="num"><?= peso($preTotal) ?></td></tr>
          <tr><td>Opening Expenses</td><td class="num"><?= peso($openingTotal) ?></td></tr>
          <tr class="total"><td>Total Initial Cash Requirement</td><td class="num"><?= peso($preTotal + $openingTotal) ?></td></tr>
          <tr><td>Regular Monthly Operating Expenses</td><td class="num"><?= peso($opexMonthly) ?></td></tr>
          <tr><td>Regular Annual Operating Expenses</td><td class="num"><?= peso($opexMonthly * 12) ?></td></tr>
        </tbody>
      </table>
    </div>
    <p class="hint" style="text-align:center">These are preliminary estimates. TravelMate should monitor actual spending and verify government fees and contributions as supplier quotations become available.</p>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">iv. Balance Sheet</span>
      <h2>Balance Sheet of TravelMate</h2>
    </div>
    <div class="grid grid-2">
      <div class="table-wrap reveal"><table>
        <thead><tr><th>Assets</th><th class="num">Amount</th></tr></thead>
        <tbody>
          <tr><td colspan="2"><b>Current Assets</b></td></tr>
          <tr><td style="padding-left:30px">Cash on Hand and in Bank</td><td class="num">₱2,511,034.00</td></tr>
          <tr><td style="padding-left:30px">Refundable Office Security Deposit</td><td class="num">₱42,000.00</td></tr>
          <tr><td colspan="2"><b>Non-Current Assets</b></td></tr>
          <tr><td style="padding-left:30px">Furniture and Fixtures (15 years)</td><td class="num">₱74,667.00</td></tr>
          <tr><td style="padding-left:30px">(Accumulated Depreciation)</td><td class="num">(₱<?= n2($depF) ?>)</td></tr>
          <tr><td style="padding-left:30px">Machines, Equipment, and Tools (15 years)</td><td class="num">₱108,687.00</td></tr>
          <tr><td style="padding-left:30px">(Accumulated Depreciation)</td><td class="num">(₱<?= n2($depM) ?>)</td></tr>
          <tr><td style="padding-left:30px">Leasehold Improvements and Facility Installations (30 years)</td><td class="num">₱200,000.00</td></tr>
          <tr><td style="padding-left:30px">(Accumulated Depreciation)</td><td class="num">(₱<?= n2($depL) ?>)</td></tr>
          <tr class="total"><td>Total Assets</td><td class="num">₱2,917,497.73</td></tr>
        </tbody>
      </table></div>
      <div class="table-wrap reveal"><table>
        <thead><tr><th>Liabilities &amp; Partners&rsquo; Equity</th><th class="num">Amount</th></tr></thead>
        <tbody>
          <tr><td>Business Loan Payable (Total Liabilities)</td><td class="num">₱1,000,000.00</td></tr>
          <tr><td colspan="2"><b>Partners&rsquo; Equity</b></td></tr>
          <?php foreach (range('A', 'G') as $L): ?><tr><td style="padding-left:30px">Partner <?= $L ?> Capital</td><td class="num">₱1,000,000.00</td></tr><?php endforeach; ?>
          <tr><td style="padding-left:30px">Less: Accumulated Net Loss for 1 year</td><td class="num">(₱<?= n2($netLoss) ?>)</td></tr>
          <tr><td>Total Partners&rsquo; Equity</td><td class="num">₱1,917,497.73</td></tr>
          <tr class="total"><td>Total Liabilities and Equity</td><td class="num">₱2,917,497.73</td></tr>
        </tbody>
      </table></div>
    </div>

    <div class="section-head reveal" style="margin-top:60px">
      <span class="eyebrow">v. Income Statement</span>
      <h2>Projected Income Statement &ndash; 1 year of operations</h2>
    </div>
    <div class="table-wrap reveal" style="max-width:780px;margin-inline:auto">
      <table>
        <thead><tr><th>Particulars</th><th class="num">Annual amount</th></tr></thead>
        <tbody>
          <tr><td><b>Gross Income / Total Sales</b></td><td class="num"><b>₱<?= n2($salesY1) ?></b></td></tr>
          <tr><td colspan="2"><b>Operating Expenses</b></td></tr>
          <tr><td style="padding-left:30px">Utilities (electricity, water, internet, mobile)</td><td class="num">₱174,000.00</td></tr>
          <tr><td style="padding-left:30px">Office Rent</td><td class="num">₱252,000.00</td></tr>
          <tr><td style="padding-left:30px">Employee Salaries and Wages</td><td class="num">₱4,092,000.00</td></tr>
          <tr><td style="padding-left:30px">Employer SSS, PhilHealth, and Pag-IBIG (estimated)</td><td class="num">₱493,860.00</td></tr>
          <tr><td style="padding-left:30px">Miscellaneous Expenses (transport, supplies, cleaning, software, maintenance, marketing, bank fees, permits)</td><td class="num">₱462,288.00</td></tr>
          <tr><td><b>Total Operating Expenses</b></td><td class="num"><b>₱5,474,148.00</b></td></tr>
          <tr><td>Opening Expenses</td><td class="num">₱<?= n2($openingTotal) ?></td></tr>
          <tr><td>Depreciation Expense (furniture ₱<?= n2($depF) ?> + equipment ₱<?= n2($depM) ?> + leasehold ₱<?= n2($depL) ?>)</td><td class="num">₱<?= n2($depTotal) ?></td></tr>
          <tr><td>Net Income Before Tax</td><td class="num">(₱<?= n2($netLoss) ?>)</td></tr>
          <tr><td>Income Tax</td><td class="num">₱0.00</td></tr>
          <tr class="total"><td>Net Income After Tax</td><td class="num">(₱<?= n2($netLoss) ?>)</td></tr>
          <tr class="total"><td>ROI</td><td class="num">-63.53%</td></tr>
        </tbody>
      </table>
    </div>
    <p class="hint" style="text-align:center;max-width:780px;margin-inline:auto">A first-year loss is reasonable for a new technology business: revenue is still low compared with staffing, office, software, marketing and maintenance costs. TravelMate needs to grow subscriptions, booking commissions, partner listings and other revenue while managing costs.</p>
  </div>
</section>

<section class="section-alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">vi. Estimated Sales</span>
      <h2>Projected sales forecast &ndash; Year 1</h2>
    </div>
    <div class="table-wrap reveal">
      <table>
        <thead><tr><th>Month</th><th class="num">Premium Subscriptions</th><th class="num">Booking Commissions</th><th class="num">Partner Listings</th><th class="num">Ad Revenue</th><th class="num">Total Sales</th></tr></thead>
        <tbody>
          <?php foreach ($f1 as $i => $r): ?><tr><td><?= $mon[$i] ?></td><td class="num"><?= peso($r[0]) ?></td><td class="num"><?= peso($r[1]) ?></td><td class="num"><?= peso($r[2]) ?></td><td class="num"><?= peso($r[3]) ?></td><td class="num"><?= peso($r[0] + $r[1] + $r[2] + $r[3]) ?></td></tr><?php endforeach; ?>
          <tr class="total"><td>TOTAL YEAR 1</td><td class="num"><?= peso(fsum($f1, 0)) ?></td><td class="num"><?= peso(fsum($f1, 1)) ?></td><td class="num"><?= peso(fsum($f1, 2)) ?></td><td class="num"><?= peso(fsum($f1, 3)) ?></td><td class="num"><?= peso(fbase($f1)) ?></td></tr>
        </tbody>
      </table>
    </div>

    <div class="section-head reveal" style="margin-top:60px">
      <span class="eyebrow">vii. Sales Forecast</span>
      <h2>Assumptions and buffer</h2>
    </div>
    <div class="table-wrap reveal" style="max-width:760px;margin-inline:auto">
      <table>
        <thead><tr><th>Revenue category</th><th>Selling price</th><th class="num">Buffer margin</th></tr></thead>
        <tbody>
          <tr><td>Premium Subscription</td><td>₱199 per subscription/month</td><td class="num">5%</td></tr>
          <tr><td>Travel Booking Commission</td><td>₱500 per completed booking</td><td class="num">10%</td></tr>
          <tr><td>Partner Listing Package</td><td>₱5,000 per package</td><td class="num">5%</td></tr>
          <tr><td>Rewarded Advertisement</td><td>Estimated ₱50 eCPM</td><td class="num">5%</td></tr>
        </tbody>
      </table>
    </div>

    <?php foreach ([['Year 1', $f1, 'Base year.'], ['Year 2', $f2, 'Assumes 25% growth over Year 1 base sales.'], ['Year 3', $f3, 'Assumes 20% growth over Year 2, equivalent to 50% growth over Year 1 base sales.']] as $Y): ?>
      <h3 class="sub"><?= $Y[0] ?> &mdash; Monthly Sales Forecast</h3>
      <p class="hint"><?= $Y[2] ?></p>
      <div class="table-wrap reveal">
        <table>
          <thead><tr><th>Month</th><th class="num">Premium</th><th class="num">Bookings</th><th class="num">Partners</th><th class="num">Ads</th><th class="num">Buffer</th><th class="num">Target Sales</th></tr></thead>
          <tbody>
            <?php foreach ($Y[1] as $i => $r): ?><tr><td><?= $mon[$i] ?></td><td class="num"><?= n2($r[0]) ?></td><td class="num"><?= n2($r[1]) ?></td><td class="num"><?= n2($r[2]) ?></td><td class="num"><?= n2($r[3]) ?></td><td class="num"><?= n2($r[4]) ?></td><td class="num"><?= n2($r[0] + $r[1] + $r[2] + $r[3] + $r[4]) ?></td></tr><?php endforeach; ?>
            <tr class="total"><td>Total</td><td class="num"><?= n2(fsum($Y[1], 0)) ?></td><td class="num"><?= n2(fsum($Y[1], 1)) ?></td><td class="num"><?= n2(fsum($Y[1], 2)) ?></td><td class="num"><?= n2(fsum($Y[1], 3)) ?></td><td class="num"><?= n2(fsum($Y[1], 4)) ?></td><td class="num"><?= n2(fbase($Y[1]) + fsum($Y[1], 4)) ?></td></tr>
          </tbody>
        </table>
      </div>
    <?php endforeach; ?>

    <h3 class="sub">Three-year summary</h3>
    <div class="table-wrap reveal" style="max-width:760px">
      <table>
        <thead><tr><th>Particulars</th><th class="num">Year 1</th><th class="num">Year 2</th><th class="num">Year 3</th></tr></thead>
        <tbody>
          <tr><td>Base Sales</td><?php foreach ([$f1, $f2, $f3] as $f): ?><td class="num">₱<?= n2(fbase($f)) ?></td><?php endforeach; ?></tr>
          <tr><td>Buffer Allowance</td><?php foreach ([$f1, $f2, $f3] as $f): ?><td class="num"><?= n2(fsum($f, 4)) ?></td><?php endforeach; ?></tr>
          <tr class="total"><td>Target Sales</td><?php foreach ([$f1, $f2, $f3] as $f): ?><td class="num">₱<?= n2(fbase($f) + fsum($f, 4)) ?></td><?php endforeach; ?></tr>
        </tbody>
      </table>
    </div>
    <p class="hint">Buffer: 5% on premium subscriptions, partner listings and ads; 10% on booking commissions. Target sales are planning estimates, not guaranteed revenue.</p>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
