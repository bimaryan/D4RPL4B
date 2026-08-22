<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cf = new App\Services\CloudflareService();
$res = $cf->createCnameRecord('tinker-test.ryaze.cloud');
echo "Result: " . ($res ? 'SUCCESS' : 'FAILED') . "\n";
