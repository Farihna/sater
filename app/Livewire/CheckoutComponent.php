<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RajaOngkirController;
use App\Models\Order;      
use App\Models\OrderItem;   
use App\Models\Transaction; 
use App\Models\Payment;
use App\Models\Address;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use Midtrans\Config;
use Midtrans\Snap;


class CheckoutComponent extends Component 
{
    protected $rajaOngkirService;
    public $snapToken;
    public $finalOrderId;

    public $currentStep = null;
    public $cartItems = [];
    public $subtotalPrice = 0;
    public $shippingCost = 0;
    public $grandTotal = 0;

    public $recipient_name;
    public $phone;
    public $shipping_address;
    public $province_id;
    public $city_id;
    public $district_id;
    public $village_id;
    public $zip_code;

    public $provinces = [];
    public $cities = [];
    public $districts = [];
    public $villages = [];

    public $shippingOptions = [];
    public $selectedCourier = null; 
    public $isLoadingShipping = false;
    public $shippingError = null;
    public $totalWeight = 0; 

    public $originPostalCode = '50519';

    protected $rules = [
        'recipient_name' => 'required|string|max:100',
        'phone' => 'required|string|max:15',
        'shipping_address' => 'required|string',
        'province_id' => 'required|exists:provinces,id',
        'city_id' => 'required|exists:cities,id',
        'district_id' => 'required|exists:districts,id',
        'village_id' => 'required|exists:villages,id', 
        'zip_code' => 'nullable|string|max:10',
    ];

    public function boot(RajaOngkirController $rajaOngkir = null)
    {
        // KOMEN: kalau container tidak meng-inject (null), kita coba resolve manual sebagai fallback.
        if ($rajaOngkir instanceof RajaOngkirController) {
            $this->rajaOngkirService = $rajaOngkir;
        } else {
            // KOMEN: fallback - resolve dari container agar tidak null
            try {
                $this->rajaOngkirService = app()->make(RajaOngkirController::class);
                \Log::info('RajaOngkirController resolved from container as fallback.');
            } catch (\Throwable $e) {
                $this->rajaOngkirService = null;
                \Log::error('Gagal resolve RajaOngkirController: ' . $e->getMessage());
            }
        }
    }

    public function mount(Request $request)
    {
        $this->provinces = Province::all();
        $this->cities = collect();
        $this->districts = collect();
        $this->villages = collect(); 

        $midtransStatus = $request->query('status_midtrans');
        $orderRef = $request->query('order_ref');
        
        if ($midtransStatus === 'finished' && $orderRef) {
            $transaction = Transaction::where('transaction_reference', $orderRef)->first();
            
            if ($transaction) {
                $order = Order::find($transaction->order_id);
                
                if ($order) {
                    $this->finalOrderId = $order->id;
                    $this->setStep(4);
                    
                    session()->forget('cart');
                    $this->cartItems = [];
                    
                    \Log::info('Redirect from Midtrans', ['order_id' => $order->id]);
                    return; 
                }
            }
        }
        
        $this->cartItems = Session::get('cart', []);
        
        if (empty($this->cartItems)) {
            $this->currentStep = 1;
        }

        if (Auth::check()) {
            $user = Auth::user();
            $defaultAddress = Address::where('user_id', $user->id)
                                ->where('type', 'shipping')
                                ->where('is_default', true)
                                ->first();
            if ($defaultAddress) {
                $this->recipient_name = $defaultAddress->recipient_name;
                $this->phone = $defaultAddress->phone_number;
                $this->shipping_address = $defaultAddress->address_line;
                $this->zip_code = $defaultAddress->zip_code;
                
                $this->province_id = $defaultAddress->province_id;
                $this->city_id = $defaultAddress->city_id;
                $this->district_id = $defaultAddress->district_id;
                $this->village_id = $defaultAddress->village_id;

                $this->loadInitialRegionData();
            } else {
                $this->recipient_name = $user->username;
                $this->phone = $user->phone;
            }
        }
    
        if ($this->currentStep === null) {
            $this->currentStep = 1; 
        }
        $this->calculateTotals(); 
    }

    /**
     * Midtrans logic
     */

    public function updatedCurrentStep($value)
    {
        if ($value == 3 && !$this->snapToken) {
            $this->generateMidtransToken();
        }
    }

    public function generateMidtransToken()
    {
        $this->calculateTotals(); 

        if ($this->grandTotal <= 0) {
            session()->flash('error', 'Grand Total tidak valid.');
            return;
        }

        if (empty($this->cartItems)) {
            session()->flash('error', 'Keranjang kosong.');
            return;
        }

        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            // 1. BUAT ORDER DULU
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $this->grandTotal,
                'order_status' => 'pending',
                'shipping_fee' => $this->shippingCost, 
                'discount' => 0, 
                'payment_method' => 'Midtrans Snap', 
                'shipping_address' => $this->shipping_address ?? 'Alamat Tidak Diketahui', 
            ]);

            \Log::info('Order Created', ['order_id' => $order->id]);

            // 2. BUAT ORDER ITEMS
            foreach ($this->cartItems as $itemId => $item) {
                OrderItem::create([ 
                    'order_id' => $order->id,
                    'product_id' => $itemId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'], 
                    'total_price' => $item['price'] * $item['quantity'],
                ]);
            }

            \Log::info('Order Items Created', ['order_id' => $order->id]);

            // 3. BUAT ORDER ID UNTUK MIDTRANS
            $midtransOrderId = 'ORDER-' . $order->id . '-' . time();

            // 4. BUAT TRANSACTION
            $transaction = Transaction::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'transaction_type' => 'Pembayaran Midtrans',
                'amount' => $this->grandTotal,
                'transaction_status' => 'pending',
                'transaction_date' => now(),
                'transaction_reference' => $midtransOrderId,
            ]);

            \Log::info('Transaction Created', ['transaction_id' => $transaction->id]);

            // 5. BUAT PAYMENT
            Payment::create([
                'order_id' => $order->id,
                'amount' => $this->grandTotal,
                'payment_method' => 'Midtrans Snap',
                'transaction_id' => $transaction->id,
                'payment_status' => 'pending',
                'payment_date' => null,
            ]);

            \Log::info('Payment Created', ['order_id' => $order->id]);

            // 6. SIMPAN ORDER ID KE PROPERTY
            $this->finalOrderId = $order->id;

            // 7. KONFIGURASI MIDTRANS
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production'); 
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // 8. SIAPKAN ITEM DETAILS
            $itemDetails = [];
            foreach ($this->cartItems as $id => $item) {
                $itemDetails[] = [
                    'id' => $id,
                    'price' => (int)$item['price'],
                    'quantity' => $item['quantity'],
                    'name' => $item['name'] ?? 'Product',
                ];
            }
            
            if ($this->shippingCost > 0) {
                $itemDetails[] = [
                    'id' => 'SHIPPING', 
                    'price' => (int)$this->shippingCost, 
                    'quantity' => 1, 
                    'name' => 'Biaya Pengiriman'
                ];
            }

            // 9. PARAMETER MIDTRANS
            $params = [
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => (int)$this->grandTotal, 
                ],
                'customer_details' => [
                    'first_name' => $this->recipient_name,
                    'email' => $user->email ?? 'customer@example.com',
                    'phone' => $this->phone,
                ],
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('checkout.index') . '?status_midtrans=finished&order_ref=' . $midtransOrderId,
                ],
            ];

            // 10. GENERATE SNAP TOKEN
            $this->snapToken = Snap::getSnapToken($params);
            
            \Log::info('Midtrans Token Generated', [
                'token' => $this->snapToken,
                'order_id' => $order->id
            ]);

            DB::commit();

            $this->dispatch('midtransTokenGenerated', snapToken: $this->snapToken);
            session()->flash('success', 'Token pembayaran berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Generate Token Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Gagal membuat transaksi: ' . $e->getMessage());
            $this->snapToken = null;
        }
    }

    protected function loadInitialRegionData()
    {
        if ($this->province_id) {
            $this->cities = City::where('province_id', $this->province_id)->get();
        }
        if ($this->city_id) {
            $this->districts = District::where('city_id', $this->city_id)->get();
        }
        if ($this->district_id) {
            $this->villages = District::find($this->district_id)->villages()->get();
        }
    }
    
    public function setStep($stepNumber)
    {
        $this->currentStep = $stepNumber;
    }
    public function updateShippingCost($cost)
    {
        $this->shippingCost = $cost;
        $this->calculateTotals();
    }

    public function updatedProvinceId($value)
    {
        $this->cities = [];
        $this->districts = [];
        $this->city_id = null;
        $this->district_id = null;
        
        if ($value) {
            $this->cities = City::where('province_id', $value)->get();
        }
    }

    public function updatedCityId($value)
    {
        $this->districts = [];
        $this->district_id = null;
        $this->shippingCost = 0; 
        
        if ($value) {
            $this->districts = District::where('city_id', $value)->get();
        }
    }

    public function updatedDistrictId($value)
    {
        $this->villages = [];
        $this->village_id = null;
        $this->shippingCost = 0;
        $this->zip_code = null;
        
        if ($value) {
            $this->villages = District::find($value)->villages()->get(); 
        }
    }

    public function updatedVillageId($value)
    {
        $this->zip_code = null;
        
        if ($value) {
            $village = Village::find($value);
            
            if ($village) {
                $postalCodeObj = $village->postalCodes()->first();
                
                if ($postalCodeObj) {
                    $this->zip_code = $postalCodeObj->code;
                } else {
                }
            }
        }
        $this->calculateShippingCost();
    }

    public function selectCourier($courierIndex)
    {
        if (isset($this->shippingOptions[$courierIndex])) {
            $selectedOption = $this->shippingOptions[$courierIndex];
            $this->selectedCourier = $courierIndex;
            $this->shippingCost = $selectedOption['cost'];
            
            // Update total setelah memilih kurir
            $this->calculateTotals();
            
            // Dispatch event untuk update UI
            $this->dispatch('courierSelected', [
                'courier' => $selectedOption['courier_name'],
                'service' => $selectedOption['service'],
                'cost' => $selectedOption['cost']
            ]);
        }
    }

    public $showDeleteModal = false; 
    public $itemToDeleteId = null; 
    public $itemToDeleteName = '';

    public function confirmItemRemoval($itemId)
    {
        $this->itemToDeleteId = $itemId;

        $cart = $this->cartItems; 
    
        if (isset($cart[$itemId])) {
            $this->itemToDeleteName = $cart[$itemId]['name'] ?? 'Item Tidak Dikenal'; 
        } else {
            $this->itemToDeleteName = 'Item Tidak Dikenal';
        }
        $this->showDeleteModal = true;
    }

    public function removeItem()
    {
        $itemId = $this->itemToDeleteId;
        
        if ($itemId === null) {
            return; 
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$itemId])) {
            $productName = $cart[$itemId]['name'] ?? 'Item';
            
            unset($cart[$itemId]);
            Session::put('cart', $cart);

            $this->cartItems = $cart;
            $this->calculateTotals(); 
            
            $this->dispatch('itemDeleted'); 
        }
        
        $this->itemToDeleteId = null;
        
        if (empty($cart)) {
            $this->setStep(1); 
        }
    }
    protected function calculateTotalWeight()
    {
        $weight = 0; // Berat awal dalam gram
        foreach ($this->cartItems as $item) {
            // ASUMSI: Setiap item memiliki 'weight' di dalam array session atau Model Product
            // Jika tidak ada 'weight' di session, gunakan default 500g per unit
            $itemWeight = $item['weight'] ?? 500; // Default 500g per unit
            $weight += $itemWeight * $item['quantity'];
        }
        // RajaOngkir memiliki berat minimal 100g
        $totalWeight = max(100, $weight);
        
        // KOMEN: Simpan juga ke property untuk keperluan lain
        $this->totalWeight = $totalWeight;
        
        // KOMEN: Return nilai agar bisa digunakan langsung
        return $totalWeight;
    }


    public function calculateShippingCost()
    {
        // Reset state
        $this->shippingOptions = [];
        $this->selectedCourier = null;
        $this->shippingCost = 0;
        $this->shippingError = null;
        $this->isLoadingShipping = true;

        if (empty($this->zip_code)) {
            $this->shippingError = 'Kode pos tujuan belum tersedia.';
            $this->isLoadingShipping = false;
            return;
        }

        try {
            $totalWeight = $this->calculateTotalWeight();
            
            // KOMEN: List kurir yang ingin ditampilkan
            $couriers = ['jne', 'pos', 'tiki', 'jnt', 'sicepat', 'anteraja'];
            
            $rajaOngkirController = app(RajaOngkirController::class);
            
            foreach ($couriers as $courier) {
                // KOMEN: Panggil method getCostByPostalCode untuk setiap kurir
                $result = $rajaOngkirController->getCostByPostalCode(
                    $this->originPostalCode,
                    $this->zip_code,
                    $totalWeight,
                    $courier
                );
                
                // KOMEN: Parsing response sesuai struktur API RajaOngkir
                if ($result && isset($result['data']) && is_array($result['data'])) {
                    foreach ($result['data'] as $service) {
                        $this->shippingOptions[] = [
                            'courier_code' => $service['code'] ?? $courier,
                            'courier_name' => $service['name'] ?? strtoupper($courier),
                            'service' => $service['service'] ?? 'Regular',
                            'description' => $service['description'] ?? '',
                            'cost' => $service['cost'] ?? 0,
                            'etd' => $service['etd'] ?? 'N/A',
                        ];
                    }
                }
            }
            
            \Log::info('Shipping options loaded', [
                'count' => count($this->shippingOptions),
                'options' => $this->shippingOptions
            ]);
            
            if (!empty($this->shippingOptions)) {
                $this->dispatch('shippingOptionsLoaded', count($this->shippingOptions));
            } else {
                $this->shippingError = 'Tidak ada layanan pengiriman tersedia.';
            }
            
        } catch (\Exception $e) {
            $this->shippingError = 'Terjadi kesalahan: ' . $e->getMessage();
            \Log::error('Calculate Shipping Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            $this->isLoadingShipping = false;
        }
    }

    // Logika perhitungan total global
    public function calculateTotals()
    {
        $this->subtotalPrice = 0;
        foreach ($this->cartItems as $item) {
            $this->subtotalPrice += $item['price'] * $item['quantity'];
        }

        $this->grandTotal = $this->subtotalPrice + $this->shippingCost;
    }
    public function validateAndGoToPayment() 
    {
        $validatedData = $this->validate(); 
        
        $user = Auth::user();
        $province = Province::find($this->province_id);
        $city = City::find($this->city_id);
        $district = District::find($this->district_id);
        $village = Village::find($this->village_id);

        try {
            // 2. Definisikan Data yang Akan Disimpan
            $dataToSave = [
                'label' => 'Alamat Pengiriman', 
                'recipient_name' => $this->recipient_name,
                'phone_number' => $this->phone,
                'address_line' => $this->shipping_address,
                'zip_code' => $this->zip_code,
                'is_default' => true, 
                
                'province_id' => $this->province_id,
                'city_id' => $this->city_id,
                'district_id' => $this->district_id,
                'village_id' => $this->village_id,
                
                'province' => $province->name,
                'city' => $city->name,
                'district' => $district->name,
                'village' => $village->name
            ];

            $searchConditions = [
                'user_id' => $user->id,
                'type' => 'shipping',
            ];

            $address = Address::firstOrNew($searchConditions);
            
            $address->fill($dataToSave); 
            
            $address->user_id = $user->id; 
            $address->type = 'shipping';

            $address->save(); 

            $this->generateMidtransToken();
            $this->setStep(3);

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan data pengiriman: ' . $e->getMessage());
            $this->addError('shipping_address', 'Gagal menyimpan data pengiriman. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.checkout-component');
    }
}