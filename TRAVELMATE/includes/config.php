<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// ===== TRAVELMATE - Global configuration & shared data =====
define('SITE_NAME', 'TravelMate');
define('SITE_TAGLINE', 'Find a Travel Match.');
define('SITE_EMAIL', 'hello@travelmate.ph');
define('SITE_ADDRESS', 'Ayala Triangle Gardens Tower 2, Paseo de Roxas cor. Makati Ave., Makati City, Metro Manila');

function peso($n) {
    return '₱' . number_format($n);
}
function peso2($n) {
    return '₱' . number_format($n, 2);
}

$NAV = [
    'home'       => ['home.php',      'Home'],
    'match'      => ['find.php',       'Find Match'],
    'about'      => ['about.php',      'About'],
    'app'        => ['app.php',        'The App'],
    'team'       => ['team.php',       'Team'],
    'market'     => ['market.php',     'Market'],
    'operations' => ['operations.php', 'Operations'],
    'financial'  => ['financial.php',  'Financial'],
    'contact'    => ['contact.php',    'Contact'],
];

// ===== Simple authentication (users saved in data/users.json) =====
define('USERS_FILE', __DIR__ . '/../data/users.json');

function load_users() {
    if (!file_exists(USERS_FILE)) { return []; }
    $d = json_decode(file_get_contents(USERS_FILE), true);
    return is_array($d) ? $d : [];
}
function save_users($users) {
    $dir = dirname(USERS_FILE);
    if (!is_dir($dir)) { mkdir($dir, 0775, true); }
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT), LOCK_EX);
}
function is_logged_in() { return !empty($_SESSION['user']); }
function require_login() {
    if (!is_logged_in()) { header('Location: login.php'); exit; }
}
function current_user() { return $_SESSION['user'] ?? null; }

// Where to send a user right after logging in: profile first, then the search page
function landing_url() {
    $u = load_users();
    $em = current_user()['email'] ?? '';
    return !empty($u[$em]['profile']) ? 'find.php' : 'profile.php';
}

// ===== Profile photo helpers =====
function user_photo($email = null) {
    $u = load_users();
    $em = $email ?? (current_user()['email'] ?? '');
    $f = $u[$em]['photo'] ?? '';
    return ($f && file_exists(__DIR__ . '/../' . $f)) ? $f : '';
}

// ===== Payment methods =====
// Edit BANK_DETAILS with your real account before going live.
define('BANK_DETAILS', "Account name: TravelMate Inc.\nBank: (your bank here)\nAccount no.: (your account number)");

function payment_methods() {
    return [
        'card'    => ['Visa / Mastercard', '💳', 'Pay with a Visa or Mastercard credit/debit card.'],
        'paypal'  => ['PayPal', '🅿️', 'You will approve the payment in your PayPal account.'],
        'wise'    => ['Wise', '🌍', 'Pay from your Wise balance or bank with low international fees.'],
        'apple'   => ['Apple Pay', '🍎', 'Confirm with Face ID / Touch ID on your Apple device.'],
        'google'  => ['Google Pay', '🔵', 'Confirm with your saved Google Pay card.'],
        'bank'    => ['Bank Transfer', '🏦', 'Transfer the exact amount, then keep the reference number as proof.'],
        'gcash'   => ['GCash', '📱', 'Approve the payment in your GCash app.'],
        'maya'    => ['Maya', '💚', 'Approve the payment in your Maya app.'],
        'revolut' => ['Revolut', '🟣', 'Pay instantly from your Revolut account.'],
        'local'   => ['Local Instant Payment', '⚡', 'Pay through your country’s instant payment network.'],
    ];
}
function local_payment_options() {
    return ['UPI (India)', 'PayNow (Singapore)', 'Zelle (USA)', 'Interac e-Transfer (Canada)', 'DuitNow (Malaysia)', 'PromptPay (Thailand)'];
}
