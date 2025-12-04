<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            Log::info('Midtrans Notification Received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);

            $transaction = Transaction::where('transaction_reference', $orderId)->first();

            if (!$transaction) {
                Log::error('Transaction not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }

            $order = Order::find($transaction->order_id);
            $payment = Payment::where('transaction_id', $transaction->id)->first();

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->updateStatus($transaction, $order, $payment, 'completed');
                }
            } elseif ($transactionStatus == 'settlement') {
                $this->updateStatus($transaction, $order, $payment, 'completed');
            } elseif ($transactionStatus == 'pending') {
                $this->updateStatus($transaction, $order, $payment, 'pending');
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $this->updateStatus($transaction, $order, $payment, 'failed');
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function updateStatus($transaction, $order, $payment, $status)
    {
        $transaction->update([
            'transaction_status' => $status,
            'transaction_date' => now(),
        ]);

        $orderStatus = match($status) {
            'completed' => 'processing',
            'pending' => 'pending',
            'failed' => 'cancelled',
            default => 'pending'
        };
        
        $order->update(['order_status' => $orderStatus]);

        if ($payment) {
            $payment->update([
                'payment_status' => $status,
                'payment_date' => $status === 'completed' ? now() : null,
            ]);
        }

        Log::info('Payment Status Updated', [
            'transaction_id' => $transaction->id,
            'status' => $status
        ]);
    }
}