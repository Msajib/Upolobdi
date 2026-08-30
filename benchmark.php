<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

use Illuminate\Http\Request;
use Statamic\Facades\User;
use Illuminate\Support\Facades\Auth;

/** @var \Illuminate\Foundation\Application $app */
$app = require __DIR__.'/bootstrap/app.php';
$app->boot();

$routes = [
    ['name' => 'Home Page (Landing)', 'uri' => '/', 'method' => 'GET', 'auth' => null],
    ['name' => 'Dashboard (President)', 'uri' => '/dashboard', 'method' => 'GET', 'auth' => 'sajib@upolobdi.org'],
    ['name' => 'Dashboard (Member: Kawser)', 'uri' => '/dashboard', 'method' => 'GET', 'auth' => 'kawser@upolobdi.org'],
    ['name' => 'Dashboard (Cashier: Saiful)', 'uri' => '/dashboard', 'method' => 'GET', 'auth' => 'saiful@upolobdi.org'],
    ['name' => 'Ledger Export (Print/PDF)', 'uri' => '/ledger/export', 'method' => 'GET', 'auth' => 'sajib@upolobdi.org'],
    ['name' => 'Money Receipt View', 'uri' => '/payment/receipt/pay-direct-rubel-mulla-1740889200', 'method' => 'GET', 'auth' => 'sajib@upolobdi.org'],
    ['name' => 'Health Check (/up)', 'uri' => '/up', 'method' => 'GET', 'auth' => null],
];

echo "\n========================================================================================\n";
echo "                   🚀 UPOLOBDI SOMITI ROUTE BENCHMARK RESULTS (LARAVEL 11)               \n";
echo "========================================================================================\n\n";

$results = [];

foreach ($routes as $r) {
    if ($r['auth']) {
        $user = User::findByEmail($r['auth']);
        if ($user) {
            Auth::setUser($user);
        }
    } else {
        Auth::logout();
    }
    
    // Warm-up
    $req = Request::create($r['uri'], $r['method']);
    $res = $app->handleRequest($req);

    // 10 Benchmark Iterations
    $times = [];
    for ($i = 0; $i < 10; $i++) {
        if ($r['auth'] && isset($user) && $user) {
            Auth::setUser($user);
        } else {
            Auth::logout();
        }
        $req = Request::create($r['uri'], $r['method']);
        $start = microtime(true);
        $res = $app->handleRequest($req);
        $elapsed = (microtime(true) - $start) * 1000; // milliseconds
        $times[] = $elapsed;
    }

    $min = min($times);
    $avg = array_sum($times) / count($times);
    $max = max($times);
    $status = $res->getStatusCode();
    
    $results[] = [
        'name' => $r['name'],
        'uri' => $r['uri'],
        'method' => $r['method'],
        'status' => $status,
        'min_ms' => round($min, 2),
        'avg_ms' => round($avg, 2),
        'max_ms' => round($max, 2),
        'passed' => $avg < 10.0,
    ];
}

printf("%-32s | %-6s | %-6s | %-8s | %-8s | %-8s | %-10s\n", "Route Name", "Method", "Status", "Min (ms)", "Avg (ms)", "Max (ms)", "Benchmark");
echo str_repeat("-", 92) . "\n";

foreach ($results as $res) {
    $passText = $res['passed'] ? "✅ < 10ms" : "⚡ " . $res['avg_ms'] . "ms";
    printf(
        "%-32s | %-6s | %-6d | %-8.2f | %-8.2f | %-8.2f | %-10s\n",
        $res['name'],
        $res['method'],
        $res['status'],
        $res['min_ms'],
        $res['avg_ms'],
        $res['max_ms'],
        $passText
    );
}

echo "\n========================================================================================\n";
