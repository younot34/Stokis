<!-- 
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AfterShipService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey  = config('services.aftership.key');
        $this->baseUrl = rtrim(config('services.aftership.base'), '/');
    }

    public function getCouriers()
    {
        $url = "{$this->baseUrl}/couriers";
        $response = Http::withHeaders([
            'as-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($url);

        return $response->json();
    }
    public function trackShipment($courier, $trackingNumber)
    {
        // 1️⃣ Buat (register) tracking dulu
        $createUrl = "{$this->baseUrl}/trackings";

        $createResponse = Http::withHeaders([
            'as-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($createUrl, [
            'tracking' => [
                'slug' => $courier,
                'tracking_number' => $trackingNumber
            ]
        ]);

        // Abaikan error kalau sudah pernah dibuat
        if ($createResponse->status() !== 201 && $createResponse->status() !== 409) {
            return [
                'error' => true,
                'message' => $createResponse->body()
            ];
        }

        // 2️⃣ Ambil detail tracking
        $getUrl = "{$this->baseUrl}/trackings/{$trackingNumber}";

        $response = Http::withHeaders([
            'as-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($getUrl);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => $response->body()
        ];
    }
}
-->