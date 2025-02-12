<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function thankyou($order_id)
    {
        // Ambil data order beserta relasinya
        $order = Order::with(['user', 'orderItems.product', 'orderItems.productVariant', 'payment', 'status'])
                    ->find($order_id);

        // Jika order tidak ditemukan, kembalikan error 404
        if (!$order) {
            abort(404, 'Order not found');
        }

        // Hitung ulang subtotal, voucherAmount, dan total
        $subtotal = $order->orderItems->sum(function ($item) {
            return ($item->unit_price ?? 0) * ($item->quantity ?? 0);
        });

        $voucherAmount = $order->discount_amount ?? 0;
        $total = $subtotal - $voucherAmount;

        // Kirim data ke view
        return view('thankyou', compact('order', 'subtotal', 'voucherAmount', 'total'));
    }

    public function success(Request $request)
    {
        // Ambil order yang baru saja dibuat
        $order = Order::where('user_id', auth()->id())
                      ->latest()
                      ->first();

        // Jika order ditemukan dan memiliki voucher_id
        if ($order && $order->voucher_id) {
            // Update status voucher menjadi 'Used'
            Voucher::where('id', $order->voucher_id)->update(['status' => 'Used']);
        }

        // Hapus session checkout
        session()->forget(['checkout.subtotal', 'checkout.voucher', 'checkout.total']);

        // Tampilkan halaman sukses
        return view('payment.success');
    }
}