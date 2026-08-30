<?php

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\SomitiService;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Statamic\Facades\User;
use Illuminate\Support\Facades\Auth;

$service = app(SomitiService::class);
$homeCtrl = app(HomeController::class);
$dashCtrl = app(DashboardController::class);
$req = Request::create('/', 'GET');

// Global Warm-up
$service->getSettings();
$service->getAllMembers();
$service->getPayments();
$service->getEvents();
$service->getRules();
$service->getProjects();
$service->getAnnouncements();
$homeCtrl->index($req);

$benchmarks = [];

// 1. SomitiService::getSettings()
$times = [];
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    $service->getSettings();
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'SomitiService::getSettings()',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

// 2. SomitiService::getAllMembers()
$times = [];
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    $service->getAllMembers();
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'SomitiService::getAllMembers()',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

// 3. SomitiService::calculateDue() (All 6 Members)
$times = [];
$members = $service->getAllMembers();
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    foreach ($members as $m) {
        $service->calculateDue($m);
    }
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'SomitiService::calculateDue (All 6 Members)',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

// 4. SomitiService::getPayments()
$times = [];
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    $service->getPayments();
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'SomitiService::getPayments()',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

// 5. SomitiService::getFinancialOverview()
$times = [];
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    $service->getFinancialOverview();
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'SomitiService::getFinancialOverview()',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

// 6. HomeController::index() (Landing Page)
$times = [];
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    $homeCtrl->index($req);
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'HomeController@index (Landing Page)',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

// 7. DashboardController::index() (President: Sajib)
$user = User::findByEmail('sajib@upolobdi.org');
Auth::setUser($user);
$dashCtrl->index($req); // warm-up
$times = [];
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    $dashCtrl->index($req);
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'DashboardController@index (President: Sajib)',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

// 8. DashboardController::index() (Member: Kawser)
$user = User::findByEmail('kawser@upolobdi.org');
Auth::setUser($user);
$dashCtrl->index($req); // warm-up
$times = [];
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    $dashCtrl->index($req);
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'DashboardController@index (Member: Kawser)',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

// 9. DashboardController::viewReceipt() (Money Receipt View)
$user = User::findByEmail('sajib@upolobdi.org');
Auth::setUser($user);
$allP = $service->getPayments();
$firstId = $allP[0]['id'] ?? '1';
$dashCtrl->viewReceipt($firstId); // warm-up
$times = [];
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    $dashCtrl->viewReceipt($firstId);
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'DashboardController@viewReceipt (Money Receipt)',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

// 10. DashboardController::exportLedger() (Print/PDF Ledger)
$dashCtrl->exportLedger($req); // warm-up
$times = [];
for ($i = 0; $i < 50; $i++) {
    $start = microtime(true);
    $dashCtrl->exportLedger($req);
    $times[] = (microtime(true) - $start) * 1000;
}
$benchmarks[] = [
    'Component' => 'DashboardController@exportLedger (Print/PDF Ledger)',
    'Min (ms)' => round(min($times), 3),
    'Avg (ms)' => round(array_sum($times) / count($times), 3),
    'Max (ms)' => round(max($times), 3),
    'Status' => (array_sum($times) / count($times) < 10) ? '✅ PASS (<10ms)' : 'FAIL',
];

file_put_contents(__DIR__.'/benchmark_results.json', json_encode($benchmarks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "BENCHMARK_COMPLETED\n";
