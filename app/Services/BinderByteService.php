<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BinderByteService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.binderbyte.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.binderbyte.key');
    }

    public function track($courier, $trackingNumber)
    {
        $response = Http::get("{$this->baseUrl}/track", [
            'api_key' => $this->apiKey,
            'courier' => $courier,
            'awb'     => $trackingNumber
        ]);

        return $response->json();
    }
}
