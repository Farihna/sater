<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class VerificationPartnerController extends Controller
{
    public function index()
    {
        $partner = Partner::with('user')->get();
        return view('admin.partnerVerif.verification', compact('partner'));
    }

    public function showVerification(Partner $partner)
    {
        return view('admin.partnerVerif.verification', compact('partner'));
    }

    public function processDecision(Request $request, Partner $partner)
    {
        $decision = $request->input('decision');

        if ($decision === 'approve') {
            $partner->status = 'active';
            $partner->joined_at = now();
            $partner->save();
            
            return redirect()->route('admin.partner.verification.index')->with('status', 'Partner approved successfully.');
        } elseif ($decision === 'reject') {
            $partner->status = 'rejected';
            $partner->rejection_reason = $request->input('reason');
            $partner->joined_at = null;
            $partner->save();

            return redirect()->route('admin.partner.verification.index')->with('status', 'Partner rejected successfully.');
        }
        return back()->with('warning', 'Aksi tidak valid.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        
        if (!auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized access');
        }
        
        $filename = $user->partner->identity_document;
        $disk = 'local'; 
        $path = "{$filename}"; 
        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found');
        }
        
        $fullPath = Storage::disk($disk)->path($path);
        
        return response()->file($fullPath, [
            'Content-Type' => Storage::disk($disk)->mimeType($path),
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }
}
