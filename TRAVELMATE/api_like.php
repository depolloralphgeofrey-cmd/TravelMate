<?php
require_once __DIR__ . '/includes/matching.php';
header('Content-Type: application/json; charset=utf-8');

if (!is_logged_in() || $_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(401); echo json_encode(['error' => 'auth']); exit; }
$rec = user_rec();
if (!has_profile($rec) || !has_search($rec)) { echo json_encode(['error' => 'setup']); exit; }

$id = (int)($_POST['id'] ?? -1);
if ($id < 0) { echo json_encode(['error' => 'bad id']); exit; }

$ctx = ctx_from($rec);
$t = gen_traveler($id, $ctx);
$sc = score_traveler($t, $ctx);

// Demo rule: a like becomes a mutual match when compatibility is high or the profile is "interested back".
$matched = $sc['total'] >= 70 || $id % 2 === 0;

$rec = update_user_rec(function (&$r) use ($id, $t, $sc, $matched) {
    $r['likes'][$id] = true;
    if ($matched) {
        $t['score'] = $sc['total'];
        $t['trip'] = $r['search'];
        $r['matches'][$id] = $t;
    }
});

echo json_encode(['ok' => true, 'matched' => $matched, 'id' => $id, 'name' => $t['name'], 'matchCount' => count($rec['matches'] ?? [])]);
