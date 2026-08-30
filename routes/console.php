<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Statamic\Facades\User;
use Illuminate\Support\Facades\Auth;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('somiti:benchmark', function () {
    $app = app();

    $routes = [
        ['name' => 'Home Page (Landing)', 'uri' => '/', 'method' => 'GET', 'auth' => null],
        ['name' => 'Dashboard (President)', 'uri' => '/dashboard', 'method' => 'GET', 'auth' => 'sajib@upolobdi.org'],
        ['name' => 'Dashboard (Member: Kawser)', 'uri' => '/dashboard', 'method' => 'GET', 'auth' => 'kawser@upolobdi.org'],
        ['name' => 'Dashboard (Cashier: Saiful)', 'uri' => '/dashboard', 'method' => 'GET', 'auth' => 'saiful@upolobdi.org'],
        ['name' => 'Ledger Export (Print/PDF)', 'uri' => '/ledger/export', 'method' => 'GET', 'auth' => 'sajib@upolobdi.org'],
        ['name' => 'Money Receipt View', 'uri' => '/payment/receipt/pay-direct-rubel-mulla-1740889200', 'method' => 'GET', 'auth' => 'sajib@upolobdi.org'],
        ['name' => 'Health Check (/up)', 'uri' => '/up', 'method' => 'GET', 'auth' => null],
    ];

    $results = [];

    foreach ($routes as $r) {
        $user = null;
        if ($r['auth']) {
            $user = User::findByEmail($r['auth']);
        }

        // Warm up route
        if ($user) {
            Auth::setUser($user);
        } else {
            Auth::logout();
        }
        $req = Request::create($r['uri'], $r['method']);
        $res = $app->handle($req);

        // 10 Iterations
        $times = [];
        for ($i = 0; $i < 10; $i++) {
            if ($user) {
                Auth::setUser($user);
            } else {
                Auth::logout();
            }
            $req = Request::create($r['uri'], $r['method']);
            
            $start = microtime(true);
            $res = $app->handle($req);
            $elapsed = (microtime(true) - $start) * 1000; // ms
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

    file_put_contents(base_path('benchmark_results.json'), json_encode($results, JSON_PRETTY_PRINT));
    echo "BENCHMARK_COMPLETED\n";
})->purpose('Benchmark response time for all web routes');
