<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$zoneId = env('CLOUDFLARE_ZONE_ID');
$apiToken = env('CLOUDFLARE_API_TOKEN');
$url = "https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records?type=CNAME";
$response = Illuminate\Support\Facades\Http::withToken($apiToken)->get($url);

echo "Status: " . $response->status() . "\n";
echo "Body: " . $response->body() . "\n";
