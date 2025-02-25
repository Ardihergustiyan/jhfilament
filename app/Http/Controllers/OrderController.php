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
    public function orderDetail($first_name, $invoice_number)
    {
        $order = Order::with(['user', 'orderItems.product', 'orderItems.productVariant', 'payment', 'status'])
                    ->where('invoice_number', $invoice_number)
                    ->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        // Pastikan pengguna yang mengakses adalah pemilik pesanan
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $subtotal = $order->orderItems->sum(fn($item) => ($item->unit_price ?? 0) * ($item->quantity ?? 0));
        $voucherAmount = $order->discount_amount ?? 0;
        $total = $subtotal - $voucherAmount;

        return view('order-detail', compact('order', 'subtotal', 'voucherAmount', 'total'));
    }


    public function success(Request $request)
    {
        // Ambil order yang baru saja dibuat
        $order = Order::where('user_id', auth()->id())
                    ->latest()
                    ->first();

        // Jika order tidak ditemukan, kembalikan error 404
        if (!$order) {
            abort(404, 'Order not found');
        }

        // Pastikan pengguna yang mengakses adalah pemilik pesanan
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Jika order ditemukan dan memiliki voucher_id
        if ($order->voucher_id) {
            // Update status voucher menjadi 'Used'
            Voucher::where('id', $order->voucher_id)->update(['status' => 'Used']);
        }

        // Hapus session checkout
        session()->forget(['checkout.subtotal', 'checkout.voucher', 'checkout.total']);

        // Tampilkan halaman sukses
        return view('payment.success');
    }
    private function getApplicableDiscounts($productId, $categoryId)
    {
        $now = now();

        return DB::table('discounts')
            ->where(function ($query) use ($productId, $categoryId) {
                $query->where('applicable_to', 'Semua') // Diskon untuk semua produk
                    ->orWhere(function ($query) use ($productId) {
                        $query->where('applicable_to', 'Product')
                            ->whereJsonContains('applicable_ids', (string) $productId); // Pastikan productId dikonversi ke string
                    })
                    ->orWhere(function ($query) use ($categoryId) {
                        $query->where('applicable_to', 'Category')
                            ->whereJsonContains('applicable_ids', (string) $categoryId); // Pastikan categoryId dikonversi ke string
                    });
            })
            ->where('start_date', '<=', $now) // Diskon aktif
            ->where('end_date', '>=', $now) // Diskon belum kadaluarsa
            ->get();
    }

    private function calculateDiscountedPrice($price, $discounts)
    {
        $discountedPrice = $price;

        foreach ($discounts as $discount) {
            $discountedPrice -= $discountedPrice * ($discount->discount_percentage / 100);
        }

        // Pastikan harga diskon tidak kurang dari 0
        return max($discountedPrice, 0);
    }
}