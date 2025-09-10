<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../providers/ProviderInterface.php';
require_once __DIR__ . '/../providers/KitsuProvider.php';
require_once __DIR__ . '/../providers/ANNProvider.php';

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
if ($q === '') {
    echo json_encode(['results' => [], 'error' => 'EMPTY_QUERY'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Initialize providers
$providers = [
    new KitsuProvider(),
    new ANNProvider(),
];

// Aggregate results (sequential to keep it simple; can be optimized with curl_multi)
$results = [];
foreach ($providers as $p) {
    try {
        $items = $p->search($q);
        // Normalize and guard
        foreach ($items as $it) {
            $results[] = [
                'title' => (string)($it['title'] ?? 'Untitled'),
                'url' => isset($it['url']) ? (string)$it['url'] : null,
                'thumbnail' => $it['thumbnail'] ?? null,
                'description' => $it['description'] ?? null,
                'provider' => $it['provider'] ?? $p->name(),
            ];
        }
    } catch (Throwable $e) {
        // Continue on provider failure
        // You can log $e if desired
    }
}

// Deduplicate by title+provider+url
$uniq = [];
$out = [];
foreach ($results as $r) {
    $key = md5(($r['title'] ?? '') . '|' . ($r['provider'] ?? '') . '|' . ($r['url'] ?? ''));
    if (!isset($uniq[$key])) {
        $uniq[$key] = true;
        $out[] = $r;
    }
}

// Limit output
$out = array_slice($out, 0, 48);

echo json_encode(['results' => $out], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);