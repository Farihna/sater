<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; 

class RajaOngkirController extends Controller
{
    private $apiKey;
    private $baseUrl = 'https://rajaongkir.komerce.id/api/v1';

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
        
        if (empty($this->apiKey)) {
            Log::error('RAJAONGKIR_API_KEY is not set or empty.');
        }
    }

    /**
     * Membuat HTTP Client dengan default headers (key)
     */
    protected function rajaOngkirClient()
    {
        // Kunci API dikirimkan sekali di sini
        return Http::withHeaders([
            'accept' => 'application/json',
            'key' => $this->apiKey,
        ]);
    }

    public function getLocation(Request $request)
    {
        $search = $request->get('search');

        try {
            $response = $this->rajaOngkirClient() 
                ->get($this->baseUrl . '/destination/domestic-destination', [
                    'search' => $search,
                    'limit' => 50
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json($data['data']);
            }
            
            Log::warning('RajaOngkir GET Error: ' . $response->status() . ' - ' . $response->body());
            return response()->json(['error' => 'Gagal mengambil data lokasi. Cek log server.'], $response->status());
        } catch (\Exception $e) {
            Log::error('RajaOngkir Client Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Kesalahan koneksi.'], 500);
        }
    }

    public function getCostByPostalCode($originPostalCode, $destinationPostalCode, $weight, $courier)
    {
        try {
            $response = $this->rajaOngkirClient()
                ->asMultipart()
                ->post($this->baseUrl . '/calculate/domestic-cost', [
                    ['name' => 'origin', 'contents' => $originPostalCode],
                    ['name' => 'destination', 'contents' => $destinationPostalCode],
                    ['name' => 'weight', 'contents' => $weight],
                    ['name' => 'courier', 'contents' => $courier]
                ]);

            if (!$response->successful()) {
                Log::warning('RajaOngkir Cost Error: ' . $response->status() . ' - ' . $response->body());
                return null;
            }
            $data = $response->json();

            return $data;

        } catch (\Exception $e) {
            Log::error('RajaOngkir getCostByPostalCode Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function getCost(Request $request)
    {
        $origin = $request->get('origin');
        $destination = $request->get('destination');
        $weight = $request->get('weight');
        $courier = $request->get('courier');
        
        if (empty($origin) || empty($destination) || empty($weight) || empty($courier)) {
            return response()->json(['error' => 'Semua parameter (origin, destination, weight, courier) wajib diisi.'], 400);
        }
        
        if (!is_numeric($weight) || $weight <= 0) {
            return response()->json(['error' => 'Berat harus berupa angka positif.'], 400);
        }

        $result = $this->getCostByPostalCode($origin, $destination, $weight, $courier); // Ubah: Memanggil method baru

        if ($result) {
            return response()->json($result); 
        }
        return response()->json(['error' => 'Gagal menghitung biaya kirim dari API eksternal. Cek log server.'], 500); 
    }

    public function calculateShippingByPostalCode(Request $request)
    {
        Log::info('Calculate Shipping Request Received', [
            'data' => $request->all()
        ]);

        try {
            $validated = $request->validate([
                'origin_postal_code' => 'required|string',
                'destination_postal_code' => 'required|string',
                'weight' => 'required|numeric|min:100', // Minimal 100 gram
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json(['error' => 'Data tidak valid: ' . json_encode($e->errors())], 422);
        }

        $originPostalCode = $validated['origin_postal_code'];
        $destinationPostalCode = $validated['destination_postal_code'];
        $weight = $validated['weight'];

        Log::info('Validated Data', [
            'origin' => $originPostalCode,
            'destination' => $destinationPostalCode,
            'weight' => $weight
        ]);

        try {
            Log::info('Fetching origin location', ['postal_code' => $originPostalCode]);
            
            $originResponse = $this->rajaOngkirClient()
                ->get($this->baseUrl . '/destination/domestic-destination', [
                    'search' => $originPostalCode,
                    'limit' => 1
                ]);

            Log::info('Origin Response', [
                'status' => $originResponse->status(),
                'successful' => $originResponse->successful(),
                'body' => $originResponse->json()
            ]);

            if (!$originResponse->successful()) {
                Log::warning('Failed to fetch origin location', [
                    'postal_code' => $originPostalCode,
                    'status' => $originResponse->status(),
                    'response' => $originResponse->body()
                ]);
                return response()->json(['error' => 'Gagal mendapatkan data lokasi asal. Status: ' . $originResponse->status()], 500);
            }

            $originData = $originResponse->json();
            
            Log::info('Origin Data Structure', [
                'has_data' => isset($originData['data']),
                'data_count' => isset($originData['data']) ? count($originData['data']) : 0,
                'full_data' => $originData
            ]);
            
            if (empty($originData['data'])) {
                return response()->json(['error' => 'Lokasi asal tidak ditemukan untuk kode pos: ' . $originPostalCode], 404);
            }

            $originLocationId = $originData['data'][0]['id'] ?? null;
            
            if (!$originLocationId) {
                Log::error('Origin location ID is null', ['data' => $originData['data'][0]]);
                return response()->json(['error' => 'ID lokasi asal tidak valid.'], 500);
            }

            Log::info('Origin Location Found', ['id' => $originLocationId]);

            Log::info('Fetching destination location', ['postal_code' => $destinationPostalCode]);
            
            $destinationResponse = $this->rajaOngkirClient()
                ->get($this->baseUrl . '/destination/domestic-destination', [
                    'search' => $destinationPostalCode,
                    'limit' => 1
                ]);

            Log::info('Destination Response', [
                'status' => $destinationResponse->status(),
                'successful' => $destinationResponse->successful(),
                'body' => $destinationResponse->json()
            ]);

            if (!$destinationResponse->successful()) {
                Log::warning('Failed to fetch destination location', [
                    'postal_code' => $destinationPostalCode,
                    'status' => $destinationResponse->status(),
                    'response' => $destinationResponse->body()
                ]);
                return response()->json(['error' => 'Gagal mendapatkan data lokasi tujuan. Status: ' . $destinationResponse->status()], 500);
            }

            $destinationData = $destinationResponse->json();
            
            Log::info('Destination Data Structure', [
                'has_data' => isset($destinationData['data']),
                'data_count' => isset($destinationData['data']) ? count($destinationData['data']) : 0,
                'full_data' => $destinationData
            ]);
            
            if (empty($destinationData['data'])) {
                return response()->json(['error' => 'Lokasi tujuan tidak ditemukan untuk kode pos: ' . $destinationPostalCode], 404);
            }
            $destinationLocationId = $destinationData['data'][0]['id'] ?? null;

            if (!$destinationLocationId) {
                Log::error('Destination location ID is null', ['data' => $destinationData['data'][0]]);
                return response()->json(['error' => 'ID lokasi tujuan tidak valid.'], 500);
            }

            Log::info('Destination Location Found', ['id' => $destinationLocationId]);

            $couriers = ['jne', 'pos', 'tiki', 'jnt', 'sicepat', 'anteraja'];
            $shippingOptions = [];

            Log::info('Calculating shipping costs', [
                'origin_id' => $originLocationId,
                'destination_id' => $destinationLocationId,
                'weight' => $weight,
                'couriers' => $couriers
            ]);

            foreach ($couriers as $courier) {
                try {
                    Log::info("Fetching cost for courier: {$courier}");
                    
                    $costResponse = $this->rajaOngkirClient()
                        ->asMultipart()
                        ->post($this->baseUrl . '/calculate/domestic-cost', [
                            [ 'name' => 'origin', 'contents' => (string)$originLocationId ],
                            [ 'name' => 'destination', 'contents' => (string)$destinationLocationId ],
                            [ 'name' => 'weight', 'contents' => (string)$weight ],
                            [ 'name' => 'courier', 'contents' => $courier ]
                        ]);

                    Log::info("Cost response for {$courier}", [
                        'status' => $costResponse->status(),
                        'successful' => $costResponse->successful(),
                        'body_preview' => substr($costResponse->body(), 0, 500) // First 500 chars
                    ]);

                    if ($costResponse->successful()) {
                        $costData = $costResponse->json();
                        
                        if (!empty($costData['data'])) {
                            foreach ($costData['data'] as $service) {
                                $shippingOptions[] = [
                                    'courier_code' => $courier,
                                    'courier_name' => strtoupper($courier),
                                    'service' => $service['service'] ?? 'Regular',
                                    'description' => $service['description'] ?? '',
                                    'cost' => $service['cost'] ?? 0,
                                    'etd' => $service['etd'] ?? 'N/A', // Estimasi waktu pengiriman
                                ];
                            }
                            Log::info("Added shipping option for {$courier}", [
                                'count' => count($costData['data'])
                            ]);
                        } else {
                            Log::warning("No data for courier: {$courier}", [
                                'response' => $costData
                            ]);
                        }
                    } else {
                        Log::warning("Failed to get cost for courier: {$courier}", [
                            'status' => $costResponse->status(),
                            'body' => $costResponse->body()
                        ]);
                    }
                } catch (\Exception $courierException) {
                    Log::error("Exception for courier {$courier}", [
                        'error' => $courierException->getMessage()
                    ]);
                    continue;
                }
            }

            Log::info('Total shipping options found', [
                'count' => count($shippingOptions)
            ]);

            if (empty($shippingOptions)) {
                return response()->json([
                    'error' => 'Tidak ada layanan pengiriman yang tersedia untuk rute ini.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'origin' => $originData['data'][0],
                'destination' => $destinationData['data'][0],
                'weight' => $weight,
                'shipping_options' => $shippingOptions
            ]);

        } catch (\Exception $e) {
            Log::error('Calculate Shipping Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Kesalahan sistem saat menghitung ongkir: ' . $e->getMessage()], 500);
        }
    }
}