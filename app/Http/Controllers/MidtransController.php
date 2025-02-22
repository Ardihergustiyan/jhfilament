<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handleMidtransNotification(Request $request)
    {
        try {
            // Set konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;
    
            // Ambil payload dari Midtrans
            $notif = new \Midtrans\Notification();
    
            $transactionStatus = $notif->transaction_status;
            $orderId = str_replace('ORDER-', '', $notif->order_id);
    
            // Ambil data pembayaran berdasarkan order_id
            $payment = Payment::where('order_id', $orderId)->first();
    
            if (!$payment) {
                return response()->json(['success' => false, 'message' => 'Order tidak ditemukan'], 404);
            }
    
            // Cek status transaksi dari Midtrans
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                // Pembayaran sukses
                $payment->update(['payment_status' => 'dibayar']);
    
                // Perbarui status pesanan menjadi "Selesai" atau "Dikirim"
                $order = Order::find($orderId);
                if ($order) {
                    $order->update(['status_id' => OrderStatus::where('slug', 'dikirim')->first()->id]);
                }
            } elseif ($transactionStatus == 'pending') {
                // Pembayaran masih pending
                $payment->update(['payment_status' => 'pending']);
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                // Pembayaran gagal atau dibatalkan
                $payment->update(['payment_status' => 'gagal']);
    
                // Kembalikan stok jika pembayaran gagal
                $orderItems = OrderItem::where('order_id', $orderId)->get();
                foreach ($orderItems as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::where('id', $item->product_variant_id)->increment('stock', $item->quantity);
                    } else {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }
            }
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
}
