<div class="payout-settings-container">
    <h4 class="mb-4">Pengaturan Pencairan Dana (Payout)</h4>
    <p class="text-muted">Lengkapi data rekening bank Anda untuk menerima hasil penjualan.</p>

    @if ($successMessage)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $successMessage }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errorMessage)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ $errorMessage }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form wire:submit.prevent="savePayout">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="bank_name" class="form-label">Nama Bank <span class="text-danger">*</span></label>
                <input type="text" wire:model="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" 
                    placeholder="Contoh: BCA, Mandiri, BNI" required>
                @error('bank_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="account_holder_name" class="form-label">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                <input type="text" wire:model="account_holder_name" id="account_holder_name" class="form-control @error('account_holder_name') is-invalid @enderror" 
                    placeholder="Nama sesuai rekening" required>
                @error('account_holder_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="account_number" class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                <input type="text" wire:model="account_number" id="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                    placeholder="Nomor rekening" required>
                @error('account_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="npwp" class="form-label">NPWP <span class="text-muted">(Opsional)</span></label>
                <input type="text" wire:model="npwp" id="npwp" class="form-control @error('npwp') is-invalid @enderror" 
                    placeholder="Nomor NPWP (15 digit)">
                @error('npwp') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3" wire:loading.attr="disabled">
            <span wire:loading.remove>
                <i class="bi bi-check-circle"></i> Simpan Pengaturan Payout
            </span>
            <span wire:loading>
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Menyimpan...
            </span>
        </button>
    </form>
</div>

