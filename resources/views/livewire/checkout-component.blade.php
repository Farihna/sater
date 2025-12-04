<section id="checkout" class="page-section">
    <div class="container py-5">
        <h2 class="section-title">Checkout</h2>
        <div class="checkout-steps mb-4">
            <div class="checkout-step" wire:click="setStep(1)">
                <div class="step-number @if($currentStep == 1) active @endif" id="step1">1</div>
                <span>Tinjau Keranjang</span>
            </div>
            <div class="checkout-step" wire:click="setStep(2)">
                <div class="step-number @if($currentStep == 2) active @endif" id="step2">2</div>
                <span>Pengiriman</span>
            </div>
            <div class="checkout-step" wire:click="setStep(3)">
                <div class="step-number @if($currentStep == 3) active @endif" id="step3">3</div>
                <span>Pembayaran</span>
            </div>
            <div class="checkout-step @if($currentStep == 4) active @endif" wire:click="setStep(4)">
                <div class="step-number" id="step4">4</div>
                <span>Konfirmasi</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 col-md-8">
                @if ($currentStep == 1)
                    <div class="table-container">
                        <h5 class="mb-3">Tinjauan Keranjang</h5>
                        
                        <div id="cartItemsList">
                            @foreach ($cartItems as $id => $item)
                                @php
                                    $itemTotalPrice = $item['price'] * $item['quantity'];
                                @endphp
                                
                                <div class="cart-item border-bottom py-3" data-product-id="{{ $id }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="{{ asset('storage/' . $item['image_url']) }}" 
                                                alt="{{ $item['name'] }}" 
                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                                        </div>
                                        <div class="col-md-4">
                                            <h6>{{ $item['name'] }}</h6>
                                            <small class="text-muted">Harga Satuan: Rp {{ number_format($item['price'], 0, ',', '.') }}</small> 
                                        </div>
                                        <div class="col-md-2">
                                            <div class="quantity-selector d-flex align-items-center justify-content-center">
                                                <input type="text" readonly class="form-control text-center" value="{{ $item['quantity'] }}" style="width: 60px;">
                                            </div>
                                            <small class="text-danger mt-1 text-center d-block">Stok: {{ $item['stok'] ?? '?' }}</small>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <small class="text-muted d-block mb-1">Subtotal:</small>
                                            <strong class="text-primary" id="item-total-{{ $id }}">
                                                Rp{{ number_format($itemTotalPrice, 0, ',', '.') }}
                                            </strong>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="confirmItemRemoval({{ $id }})">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if (!empty($cartItems))
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="bi bi-chevron-left"></i> Kembali Ke Produk</a>
                                
                                <button type="button" class="btn btn-primary" wire:click="setStep(2)">Lanjut ke Pengiriman <i class="bi bi-chevron-right"></i></button>
                            </div>
                            @else
                            <div class="alert alert-info text-center mt-3">Keranjang belanja Anda kosong.</div>
                            @endif
                        </div>
                    </div>
                @endif
                @if ($currentStep == 2)
                    <div class="table-container" id="shippingInfoStep">
                        <div id="step2Content">
                        <h5 class="mb-3">2. Alamat & Pengiriman</h5>
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <form wire:submit.prevent="validateAndGoToPayment">
                            @csrf
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i> Data alamat ini akan disimpan sebagai alamat pengiriman default Anda.
                            </div>

                            <h6>Penerima & Kontak</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap Penerima</label>
                                    <input type="text" wire:model.defer="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror" required>
                                    @error('recipient_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor HP Aktif</label>
                                    <input type="tel" wire:model.defer="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Contoh: 08123456789" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <h6>Alamat Tujuan</h6>
                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap (Jalan, RT/RW, Patokan)</label>
                                <textarea wire:model.defer="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required></textarea>
                                @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Provinsi</label>
                                    <select wire:model.live="province_id" id="provinsi_id" class="form-select @error('province_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('province_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Kota/Kabupaten</label>
                                    <select wire:model.live="city_id" class="form-select @error('city_id') is-invalid @enderror" required @if(empty($cities)) disabled @endif>
                                        <option value="">-- Pilih Kota/Kabupaten --</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->type }} {{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    <div wire:loading wire:target="province_id" class="text-muted small mt-1">
                                        <span class="spinner-border spinner-border-sm me-1"></span>
                                        Memuat kota...
                                    </div>
                                    @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Kecamatan</label>
                                    <select wire:model.live="district_id" id="district_id" class="form-select @error('district_id') is-invalid @enderror" required @if(empty($districts)) disabled @endif>
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                    <div wire:loading wire:target="city_id" class="text-muted small mt-1">
                                        <span class="spinner-border spinner-border-sm me-1"></span>
                                        Memuat Kecamatan...
                                    </div>
                                    @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Desa/Kelurahan</label>
                                <select wire:model.live="village_id" id="village_id" class="form-select @error('village_id') is-invalid @enderror" required @if(empty($villages)) disabled @endif>
                                    <option value="">-- Pilih Desa/Kelurahan --</option>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->id }}">{{ $village->name }}</option>
                                    @endforeach
                                </select>
                                <div wire:loading wire:target="district_id" class="text-muted small mt-1">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Memuat Desa/Kelurahan...
                                </div>
                                @error('village_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <hr>
                            <h6>Pilihan Kurir</h6>

                            {{-- KOMEN: Loading State - tampilkan saat sedang fetch data dari API --}}
                            @if($isLoadingShipping)
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Menghitung biaya pengiriman...</p>
                                </div>
                            @endif

                            {{-- KOMEN: Error Message - tampilkan jika ada error saat fetch --}}
                            @if($shippingError)
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ $shippingError }}
                                </div>
                            @endif

                            {{-- KOMEN: Shipping Options - tampilkan list kurir dari API RajaOngkir --}}
                            @if(!empty($shippingOptions) && !$isLoadingShipping)
                                <div class="mb-3">
                                    <label class="form-label">Pilih Jasa Pengiriman</label>
                                    
                                    <div class="list-group">
                                        @foreach($shippingOptions as $index => $option)
                                            <label class="list-group-item list-group-item-action cursor-pointer @if($selectedCourier === $index) active @endif">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="form-check">
                                                        <input 
                                                            class="form-check-input" 
                                                            type="radio" 
                                                            name="courier_option" 
                                                            id="courier_{{ $index }}"
                                                            wire:click="selectCourier({{ $index }})"
                                                            @if($selectedCourier === $index) checked @endif
                                                        >
                                                        <label class="form-check-label" for="courier_{{ $index }}">
                                                            <strong>{{ $option['courier_name'] }}</strong> - {{ $option['service'] }}
                                                            <br>
                                                            <small class="text-muted">{{ $option['description'] }}</small>
                                                            <br>
                                                            <small class="text-muted">Estimasi: {{ $option['etd'] }}</small>
                                                        </label>
                                                    </div>
                                                    <div class="text-end">
                                                        <strong class="text-primary">Rp{{ number_format($option['cost'], 0, ',', '.') }}</strong>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    {{-- KOMEN: Tampilkan error validasi jika user belum pilih kurir --}}
                                    @error('selectedCourier')
                                        <div class="text-danger mt-2">
                                            <small>{{ $message }}</small>
                                        </div>
                                    @enderror
                                </div>

                                {{-- KOMEN: Selected Shipping Summary - konfirmasi pilihan user --}}
                                @if($selectedCourier !== null && isset($shippingOptions[$selectedCourier]))
                                    <div class="alert alert-success mt-3">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Anda memilih: <strong>{{ $shippingOptions[$selectedCourier]['courier_name'] }} {{ $shippingOptions[$selectedCourier]['service'] }}</strong>
                                        - Rp{{ number_format($shippingOptions[$selectedCourier]['cost'], 0, ',', '.') }}
                                    </div>
                                @endif
                            @endif

                            {{-- KOMEN: Info jika belum ada opsi - user belum pilih alamat lengkap --}}
                            @if(empty($shippingOptions) && !$isLoadingShipping && !$shippingError)
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Pilih alamat lengkap terlebih dahulu untuk melihat opsi pengiriman.
                                </div>
                            @endif

                            {{-- KOMEN: Kode Pos tetap di bawah --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" wire:model="zip_code" class="form-control" readonly placeholder="Otomatis terisi">
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" wire:click="setStep(1)">
                                    <i class="bi bi-chevron-left"></i> Kembali ke Keranjang
                                </button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="validateAndGoToPayment">
                                    <span wire:loading.remove wire:target="validateAndGoToPayment">
                                        <i class="bi bi-chevron-right"></i> Lanjut ke Pembayaran
                                    </span>
                                    <span wire:loading wire:target="validateAndGoToPayment">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Memvalidasi...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                @if ($currentStep == 3)
                    <div class="table-container">
                        <h5 class="mb-3">3. Metode Pembayaran</h5>
                        
                        <div class="alert alert-warning">
                            Pastikan total tagihan sudah sesuai sebelum melakukan pembayaran.
                        </div>

                        <h6>Midtrans Snap</h6>
                        <div class="list-group">
                            @if ($snapToken)
                                <button type="button" 
                                        class="btn btn-primary btn-lg w-100 midtrans-pay-btn" 
                                        data-snap-token="{{ $snapToken }}"
                                        onclick="payWithMidtrans('{{ $snapToken }}')">
                                    Bayar Sekarang - Rp{{ number_format($grandTotal, 0, ',', '.') }}
                                </button>
                            @else
                                {{-- Loading State --}}
                                <div class="text-center p-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Memuat sistem pembayaran...</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" wire:click="setStep(2)">
                                <i class="bi bi-chevron-left"></i> Kembali ke Pengiriman
                            </button>
                        </div>
                    </div>
                @endif

                @push('scripts')
                <script>
                function payWithMidtrans(token) {
                    if (typeof snap === 'undefined') {
                        alert('⚠️ Midtrans Snap belum siap. Refresh halaman.');
                        return;
                    }
                    
                    snap.pay(token, {
                        onSuccess: function(result) {
                            @this.call('paymentSuccess', result.order_id, result.transaction_status);
                        },
                        onPending: function(result) {
                            @this.call('paymentPending', result.order_id, result.transaction_status);
                        },
                        onError: function(result) {
                            @this.call('paymentFailed', result.order_id, result.transaction_status);
                        },
                        onClose: function() {
                        }
                    });
                }

                document.addEventListener('livewire:initialized', () => {
                    Livewire.on('snapTokenReady', (event) => {
                        setTimeout(() => {
                            payWithMidtrans(event.token);
                        }, 500);
                    });
                });

                </script>
                @endpush
                @if ($currentStep == 4)
                    <div class="table-container py-5">
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">Pesanan Berhasil Dibuat!</h4>
                            <p class="text-muted">
                                ID Pesanan Anda: <strong id="orderIdDisplay">#ORD-{{ $finalOrderId }}</strong>
                            </p>
                            <p class="alert alert-info mt-4">
                                @if (session('success'))
                                    {{ session('success') }}
                                @elseif (session('info'))
                                    {{ session('info') }}
                                @else
                                    Status pembayaran akan diupdate setelah Midtrans menerima pembayaran Anda.
                                @endif
                            </p>
                            
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3">Lanjut Belanja</a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="order-summary card shadow-sm p-3">
                    <h5 class="mb-3">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal Barang:</span>
                        <span id="subtotalDisplay">Rp{{ number_format($subtotalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Kirim:</span>
                        <span id="shippingDisplay">Rp{{ number_format($shippingCost, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total Tagihan:</strong>
                        <strong id="globalTotalDisplay">Rp{{ number_format($grandTotal, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Delete --}}
    <div 
        class="modal fade" 
        id="deleteModal" 
        tabindex="-1" 
        aria-labelledby="deleteModalLabel" 
        aria-hidden="true"
        wire:ignore.self
    >
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--primary-green); color: white;>
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-circle-fill fs-4 me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <p class="mb-0">Apakah Anda yakin ingin menghapus
                        <span class="fw-bold">{{ $itemToDeleteName }}</span> dari keranjang?
                    </p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button 
                        type="button" 
                        class="btn" 
                        wire:click="removeItem"
                        data-bs-dismiss="modal"
                        wire:loading.attr="disabled"
                        style="background-color: var(--primary-green); color: white;
                    >
                        <span wire:loading.remove wire:target="removeItem">Ya, Hapus</span>
                        <span wire:loading wire:target="removeItem">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menghapus...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('itemDeleted', () => {
                window.location.reload();
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
</section>
