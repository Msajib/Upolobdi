<?php

$routes = [
    ['name' => 'Home Page (Landing)', 'url' => 'http://127.0.0.1:8000/', 'method' => 'GET'],
    ['name' => 'Health Check (/up)', 'url' => 'http://127.0.0.1:8000/up', 'method' => 'GET'],
    ['name' => 'Money Receipt View', 'url' => 'http://127.0.0.1:8000/payment/receipt/pay-direct-rubel-mulla-1740889200', 'method' => 'GET'],
];

$results = [];

foreach ($routes as $r) {
    // Warm-up run
    @file_get_contents($r['url']);

    $times = [];
    for ($i = 0; $i < 10; $i++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $r['url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $start = microtime(true);
        $response = curl_exec($ch);
        $elapsed = (microtime(true) - $start) * 1000; // ms
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $times[] = $elapsed;
    }

    $min = min($times);
    $avg = array_sum($times) / count($times);
    $max = max($times);

    $results[] = [
        'name' => $r['name'],
        'url' => $r['url'],
        'status' => $httpCode,
        'min_ms' => round($min, 2),
        'avg_ms' => round($avg, 2),
        'max_ms' => round($max, 2),
        'passed' => $avg < 10.0,
    ];
}

file_put_contents(__DIR__.'/benchmark_results.json', json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "DONE\n";
