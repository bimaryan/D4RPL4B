<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudflareService
{
    protected $zoneId;
    protected $apiToken;
    protected $tunnelUrl;

    public function __construct()
    {
        $this->zoneId = env('CLOUDFLARE_ZONE_ID');
        $this->apiToken = env('CLOUDFLARE_API_TOKEN');
        $this->tunnelUrl = env('CLOUDFLARE_TUNNEL_URL');
    }

    /**
     * Create a CNAME record pointing to the Cloudflare Tunnel.
     */
    public function createCnameRecord($domain)
    {
        if (!$this->zoneId || !$this->apiToken || !$this->tunnelUrl) {
            Log::warning("Cloudflare credentials missing. Skipping DNS creation for {$domain}");
            return false;
        }

        $url = "https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/dns_records";
        
        // 1. Cek apakah record sudah ada
        $check = Http::withToken($this->apiToken)->get($url, [
            'type' => 'CNAME',
            'name' => $domain,
        ]);

        if ($check->successful() && !empty($check->json('result'))) {
            // Sudah ada
            return true;
        }

        // 2. Buat record baru (Proxied wajib true untuk tunnel)
        $response = Http::withToken($this->apiToken)->post($url, [
            'type' => 'CNAME',
            'name' => $domain,
            'content' => $this->tunnelUrl,
            'proxied' => true,
            'comment' => 'Auto-created by Laravel for student hosting',
        ]);

        if ($response->successful()) {
            return true;
        }

        Log::error("Cloudflare DNS Creation Failed: " . $response->body());
        return false;
    }

    /**
     * Delete a DNS record by domain name.
     */
    public function deleteCnameRecord($domain)
    {
        if (!$this->zoneId || !$this->apiToken) return false;

        $url = "https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/dns_records";
        
        $check = Http::withToken($this->apiToken)->get($url, [
            'type' => 'CNAME',
            'name' => $domain,
        ]);

        if ($check->successful() && !empty($check->json('result'))) {
            $recordId = $check->json('result')[0]['id'];
            $deleteUrl = "https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/dns_records/{$recordId}";
            
            $response = Http::withToken($this->apiToken)->delete($deleteUrl);
            return $response->successful();
        }

        return false;
    }
}
