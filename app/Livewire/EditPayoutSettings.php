<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditPayoutSettings extends Component
{
    public $bank_name;
    public $account_holder_name;
    public $account_number;
    public $npwp;
    public $successMessage = '';
    public $errorMessage = '';

    public function mount()
    {
        // Load data dari partner/user
        $user = Auth::user();
        if ($user->partner) {
            $this->bank_name = $user->partner->bank_name;
            $this->account_holder_name = $user->partner->account_holder_name;
            $this->account_number = $user->partner->account_number;
            $this->npwp = $user->partner->npwp ?? '';
        }
    }

    public function savePayout()
    {
        // Validasi
        $this->validate([
            'bank_name' => 'required|string|max:100',
            'account_holder_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'npwp' => 'nullable|string|max:20',
        ]);

        try {
            $user = Auth::user();
            
            if ($user->partner) {
                // Update existing partner payout data
                $user->partner->update([
                    'bank_name' => $this->bank_name,
                    'account_holder_name' => $this->account_holder_name,
                    'account_number' => $this->account_number,
                    'npwp' => $this->npwp,
                ]);
                
                $this->successMessage = 'Pengaturan payout berhasil disimpan!';
                $this->errorMessage = '';
            } else {
                $this->errorMessage = 'Data partner tidak ditemukan.';
                $this->successMessage = '';
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            $this->successMessage = '';
        }

        // Clear message after 5 seconds
        $this->dispatch('clearMessage');
    }

    public function render()
    {
        return view('livewire.edit-payout-settings');
    }
}
