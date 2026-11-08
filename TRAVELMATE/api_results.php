<?php
require_once __DIR__ . '/includes/matching.php';
require_once __DIR__ . '/includes/monetize.php';
header('Content-Type: application/json; charset=utf-8');

if (!is_logged_in()) { http_response_code(401); echo json_encode(['error' => 'auth']); exit; }
$rec = user_rec();
if (!has_profile($rec) || !has_search($rec)) { echo json_encode(['error' => 'setup']); exit; }

$ctx = ctx_from($rec);
$premium = is_premium($rec);
$offset = max(0, (int)($_GET['offset'] ?? 0));

// Free users stop at FREE_LIMIT
if (!$premium && $offset >= FREE_LIMIT) {
    echo json_encode(['html' => '', 'next' => $offset, 'count' => 0, 'locked' => true, 'lock' => render_lock_card()]);
    exit;
}

$filters = feature_active('search', $rec);
$minScore = max(0, min(100, (int)($_GET['min'] ?? 0)));
$blocked = $rec['blocked'] ?? [];
$likes = $rec['likes'] ?? [];
$items = [];
for ($i = $offset; $i < $offset + BATCH_SIZE; $i++) {
    if (in_array($i, $blocked, true)) continue;
    $t = gen_traveler($i, $ctx);
    $sc = score_traveler($t, $ctx);
    if ($filters) {   // Advanced Traveler Search (Premium or purchased feature)
        if ($sc['total'] < $minScore) continue;
        if (!empty($_GET['budget']) && $t['budget'] !== $ctx['search']['budget']) continue;
        if (!empty($_GET['shared']) && !array_intersect($t['interests'], $ctx['profile']['interests'] ?? [])) continue;
    }
    $items[] = [$t, $sc];
}
usort($items, function ($a, $b) { return $b[1]['total'] <=> $a[1]['total']; });

$html = '';
foreach ($items as $it) { $html .= render_card($it[0], $it[1], $premium, isset($likes[$it[0]['id']])); }

$next = $offset + BATCH_SIZE;
$locked = !$premium && $next >= FREE_LIMIT;
echo json_encode([
    'html' => $html, 'next' => $next, 'count' => count($items),
    'locked' => $locked, 'lock' => $locked ? render_lock_card() : '',
]);
