<?php
// Rewarded-ad endpoint (simulated Google AdMob rewarded ad).
// start    -> issues a one-time token
// complete -> awards AD_POINTS if the ad was watched for AD_WATCH_SECONDS (server-side check) and the daily cap is not reached
require_once __DIR__ . '/includes/monetize.php';
header('Content-Type: application/json; charset=utf-8');

if (!is_logged_in() || $_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(401); echo json_encode(['error' => 'auth']); exit; }
$rec = user_rec();
if (is_premium($rec)) { echo json_encode(['error' => 'premium', 'message' => 'Premium members do not see ads.']); exit; }

$action = $_POST['action'] ?? '';
$left = max(0, AD_DAILY_MAX - ads_today($rec));

if ($action === 'start') {
    if ($left <= 0) { echo json_encode(['error' => 'limit', 'message' => 'You have watched all ' . AD_DAILY_MAX . ' rewarded ads for today. Come back tomorrow!', 'remaining' => 0]); exit; }
    $token = bin2hex(random_bytes(12));
    update_user_rec(function (&$r) use ($token) { $r['ad_token'] = ['k' => $token, 't' => time()]; });
    echo json_encode(['ok' => true, 'token' => $token, 'seconds' => AD_WATCH_SECONDS, 'remaining' => $left, 'unit' => ADMOB_REWARDED_UNIT]);
    exit;
}

if ($action === 'complete') {
    $token = $_POST['token'] ?? '';
    $res = ['error' => 'invalid', 'message' => 'Ad session not found. Please try again.'];
    $rec = update_user_rec(function (&$r) use ($token, &$res) {
        $t = $r['ad_token'] ?? null;
        $today = date('Y-m-d');
        $count = (($r['ads']['date'] ?? '') === $today) ? (int)$r['ads']['count'] : 0;
        if (!$t || !hash_equals($t['k'], (string)$token)) return;
        unset($r['ad_token']);                                   // one-time use
        if (time() - $t['t'] < AD_WATCH_SECONDS) { $res = ['error' => 'early', 'message' => 'Please watch the full ad to earn points.']; return; }
        if ($count >= AD_DAILY_MAX) { $res = ['error' => 'limit', 'message' => 'Daily limit reached.']; return; }
        $count++;
        $r['points'] = (int)($r['points'] ?? 0) + AD_POINTS;
        $r['ads'] = ['date' => $today, 'count' => $count, 'total' => (int)($r['ads']['total'] ?? 0) + 1];
        $res = ['ok' => true, 'earned' => AD_POINTS];
    });
    if (!empty($res['ok'])) {
        $res['points'] = points_balance($rec);
        $res['today'] = ads_today($rec);
        $res['remaining'] = max(0, AD_DAILY_MAX - ads_today($rec));
        $res['peso'] = points_to_peso($res['points']);
    }
    echo json_encode($res);
    exit;
}

echo json_encode(['error' => 'bad action']);
