<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\ResellerLevel;
use App\Models\Role;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    

    public function show($first_name)
    {
        $user = Auth::user(); // Ambil user yang login

        // Ambil level reseller dari user yang login
        $resellerLevel = $user->resellerLevel->name ?? 'Regular User';
        $resellerLevelId = $user->reseller_level_id ?? null;

        // Awal & akhir bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Awal & akhir bulan lalu
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Ambil semua pesanan selesai bulan ini dengan harga diskon
        $ordersThisMonth = Order::where('user_id', $user->id)
            ->where('status_id', 3)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with(['orderItems' => function ($query) use ($resellerLevelId) {
                $query->select('order_items.*', 'products.het_price')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                        $join->on('products.id', '=', 'product_prices.product_id')
                            ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
                    })
                    ->selectRaw('IFNULL(product_prices.price, products.het_price) as final_price');
            }])
            ->get();

        // Hitung total pesanan selesai bulan ini
        $totalOrdersThisMonth = $ordersThisMonth->count();

        // Hitung total belanja bulan ini dengan harga diskon
        $totalSpentThisMonth = $ordersThisMonth->sum(function ($order) {
            return $order->orderItems->sum(function ($item) {
                $discounts = $this->getApplicableDiscounts($item->product_id, $item->product->category_id);
                $discountedPrice = $this->calculateDiscountedPrice($item->final_price, $discounts);
                return $discountedPrice * $item->quantity;
            });
        });

        // Ambil semua pesanan selesai bulan lalu dengan harga diskon
        $ordersLastMonth = Order::where('user_id', $user->id)
            ->where('status_id', 3)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->with(['orderItems' => function ($query) use ($resellerLevelId) {
                $query->select('order_items.*', 'products.het_price')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                        $join->on('products.id', '=', 'product_prices.product_id')
                            ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
                    })
                    ->selectRaw('IFNULL(product_prices.price, products.het_price) as final_price');
            }])
            ->get();

        // Hitung total pesanan selesai bulan lalu
        $totalOrdersLastMonth = $ordersLastMonth->count();

        // Hitung total belanja bulan lalu dengan harga diskon
        $totalSpentLastMonth = $ordersLastMonth->sum(function ($order) {
            return $order->orderItems->sum(function ($item) {
                $discounts = $this->getApplicableDiscounts($item->product_id, $item->product->category_id);
                $discountedPrice = $this->calculateDiscountedPrice($item->final_price, $discounts);
                return $discountedPrice * $item->quantity;
            });
        });

        // Hitung persentase perubahan jumlah pesanan
        $orderPercentageChange = 0;
        if ($totalOrdersLastMonth > 0) {
            $orderPercentageChange = (($totalOrdersThisMonth - $totalOrdersLastMonth) / $totalOrdersLastMonth) * 100;
        } elseif ($totalOrdersThisMonth > 0) {
            $orderPercentageChange = 100; // Jika bulan lalu 0 dan bulan ini ada pesanan
        }

        // Hitung persentase perubahan total belanja
        $spendingPercentageChange = 0;
        if ($totalSpentLastMonth > 0) {
            $spendingPercentageChange = (($totalSpentThisMonth - $totalSpentLastMonth) / $totalSpentLastMonth) * 100;
        } elseif ($totalSpentThisMonth > 0) {
            $spendingPercentageChange = 100; // Jika bulan lalu tidak ada belanja dan sekarang ada
        }

        // Ambil jumlah review bulan ini
        $totalReviewsThisMonth = ProductReview::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Ambil jumlah review bulan lalu
        $totalReviewsLastMonth = ProductReview::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // Hitung persentase perubahan jumlah review
        $reviewPercentageChange = 0;
        if ($totalReviewsLastMonth > 0) {
            $reviewPercentageChange = (($totalReviewsThisMonth - $totalReviewsLastMonth) / $totalReviewsLastMonth) * 100;
        } elseif ($totalReviewsThisMonth > 0) {
            $reviewPercentageChange = 100;
        }

        // Format angka ke rb/jt
        $formattedThisMonth = $this->formatRupiah($totalSpentThisMonth);
        $formattedLastMonth = $this->formatRupiah($totalSpentLastMonth);

        // Ambil semua orders berdasarkan user yang login
        $orders = Order::where('user_id', $user->id)->latest()->get();

        $payments = [];
            foreach ($orders as $order) {
                $payments[$order->id] = Payment::where('order_id', $order->id)->first();
            }
        // Daftar level dan syarat minimal pembelian untuk upgrade
        $levelRequirements = [
            1 => 500000,    // Ruby
            2 => 1000000,   // Bronze
            3 => 10000000,  // Silver
            4 => 50000000,  // Gold
        ];

        $currentLevelId = $user->reseller_level_id;
        $progressPercentage = 0;
        $nextLevelRequirement = 0;
        $currentSpent = $totalSpentThisMonth; // Total belanja bulan ini

        // Cek apakah user masih bisa naik level
        if (isset($levelRequirements[$currentLevelId])) {
            $nextLevelRequirement = $levelRequirements[$currentLevelId];

            // Hitung progress dalam persen, maksimal 100%
            $progressPercentage = min(100, ($currentSpent / $nextLevelRequirement) * 100);
        }

        // Format nilai rupiah
        $formattedSpent = number_format($currentSpent, 0, ',', '.');
        $formattedTarget = number_format($nextLevelRequirement, 0, ',', '.');

        return view('profile', compact(
            'user', 
            'orders', 
            'payments',
            'totalOrdersThisMonth', 
            'totalOrdersLastMonth', 
            'orderPercentageChange', 
            'formattedThisMonth', 
            'formattedLastMonth', 
            'spendingPercentageChange', 
            'totalReviewsThisMonth', 
            'totalReviewsLastMonth', 
            'reviewPercentageChange',
            'resellerLevel',
            'progressPercentage',
            'formattedSpent',
            'formattedTarget'
        ));
    }

    public function getProgressData()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Ambil level reseller dari user yang login
        $resellerLevelId = $user->reseller_level_id ?? null;

        // Awal & akhir bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Ambil semua pesanan selesai bulan ini dengan harga diskon
        $ordersThisMonth = Order::where('user_id', $user->id)
            ->where('status_id', 3)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with(['orderItems' => function ($query) use ($resellerLevelId) {
                $query->select('order_items.*', 'products.het_price')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                        $join->on('products.id', '=', 'product_prices.product_id')
                            ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
                    })
                    ->selectRaw('IFNULL(product_prices.price, products.het_price) as final_price');
            }])
            ->get();

        // Hitung total belanja bulan ini dengan harga diskon
        $totalSpentThisMonth = $ordersThisMonth->sum(function ($order) {
            return $order->orderItems->sum(function ($item) {
                $discounts = $this->getApplicableDiscounts($item->product_id, $item->product->category_id);
                $discountedPrice = $this->calculateDiscountedPrice($item->final_price, $discounts);
                return $discountedPrice * $item->quantity;
            });
        });

        // Daftar level & syarat upgrade
        $levelRequirements = [
            1 => 500000,    // Ruby
            2 => 1000000,   // Bronze
            3 => 10000000,  // Silver
            4 => 50000000,  // Gold
        ];

        // Ambil nama level saat ini dari database
        $currentLevelName = $user->resellerLevel->name ?? 'Regular User';

        // Cari level berikutnya
        $currentLevelId = $user->reseller_level_id;
        $nextLevelRequirement = $levelRequirements[$currentLevelId] ?? null;

        // Ambil nama level berikutnya dari database
        $nextLevel = \App\Models\ResellerLevel::where('id', '>', $currentLevelId)
            ->orderBy('id')
            ->first();
        $nextLevelName = $nextLevel->name ?? 'Gold';

        // Jika user sudah di level tertinggi
        if (!$nextLevelRequirement) {
            return response()->json([
                'error' => 'Anda sudah di level tertinggi',
                'progressPercentage' => number_format(100, 2),
                'currentSpent' => number_format($totalSpentThisMonth, 0, ',', '.'),
                'target' => number_format($totalSpentThisMonth, 0, ',', '.'), // Target sudah tercapai
                'remainingAmount' => '0',
                'currentLevel' => $currentLevelName,
                'nextLevel' => $nextLevelName,
            ], 400);
        }

        // Hitung progress dalam persen, maksimal 100%
        $progressPercentage = min(100, ($totalSpentThisMonth / $nextLevelRequirement) * 100);

        // Hitung sisa yang perlu dibelanjakan untuk naik level berikutnya
        $remainingAmount = max(0, $nextLevelRequirement - $totalSpentThisMonth);
        $readyForUpgrade = $totalSpentThisMonth >= $nextLevelRequirement; // Cek apakah sudah memenuhi syarat
        $daysLeftInMonth = Carbon::now()->diffInDays($endOfMonth) + 1; // +1 agar hari ini juga dihitung

        return response()->json([
            'progressPercentage' => number_format($progressPercentage, 2),
            'currentSpent' => number_format($totalSpentThisMonth, 0, ',', '.'),
            'target' => number_format($nextLevelRequirement, 0, ',', '.'),
            'remainingAmount' => number_format($remainingAmount, 0, ',', '.'),
            'currentLevel' => $currentLevelName,
            'nextLevel' => $nextLevelName,
            'readyForUpgrade' => $readyForUpgrade, // Kirim status siap naik level
            'daysLeftInMonth' => $daysLeftInMonth,
        ]);
    }

    // public function getProgressData()
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json(['error' => 'User not authenticated'], 401);
    //     }

    //     // Ambil total belanja bulan ini
    //     $startOfMonth = Carbon::now()->startOfMonth();
    //     $endOfMonth = Carbon::now()->endOfMonth();
    //     $totalSpentThisMonth = Order::where('user_id', $user->id)
    //         ->where('status_id', 3)
    //         ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    //         ->sum('total_price');

    //     // Daftar level & syarat upgrade
    //     $levelRequirements = [
    //         1 => 500000,    // Ruby
    //         2 => 1000000,   // Bronze
    //         3 => 10000000,  // Silver
    //         4 => 50000000,  // Gold
    //     ];

    //     // Ambil nama level saat ini dari database
    //     $currentLevelName = $user->resellerLevel->name ?? 'Regular User';

    //     // Cari level berikutnya
    //     $currentLevelId = $user->reseller_level_id;
    //     $nextLevelRequirement = $levelRequirements[$currentLevelId] ?? null;
        
    //     // Ambil nama level berikutnya dari database
    //     $nextLevel = \App\Models\ResellerLevel::where('id', '>', $currentLevelId)
    //         ->orderBy('id')
    //         ->first();
    //     $nextLevelName = $nextLevel->name ?? 'Gold';

    //     if (!$nextLevelRequirement) {
    //         return response()->json([
    //             'error' => 'Anda sudah di level tertinggi',
    //             'progressPercentage' => number_format(100, 2),
    //             'currentSpent' => number_format($totalSpentThisMonth, 0, ',', '.'),
    //             'target' => number_format($totalSpentThisMonth, 0, ',', '.'), // Target sudah tercapai
    //             'remainingAmount' => '0',
    //             'currentLevel' => $currentLevelName,
    //             'nextLevel' => $nextLevelName,
    //         ], 400);
    //     }

    //     // Hitung progress dalam persen, maksimal 100%
    //     $progressPercentage = min(100, ($totalSpentThisMonth / $nextLevelRequirement) * 100);
        
    //     // Hitung sisa yang perlu dibelanjakan untuk naik level berikutnya
    //     $remainingAmount = max(0, $nextLevelRequirement - $totalSpentThisMonth);
    //     $readyForUpgrade = $totalSpentThisMonth >= $nextLevelRequirement; // Cek apakah sudah memenuhi syarat
    //     $daysLeftInMonth = Carbon::now()->diffInDays($endOfMonth) + 1; // +1 agar hari ini juga dihitung

    //     return response()->json([
    //         'progressPercentage' => number_format($progressPercentage, 2),
    //         'currentSpent' => number_format($totalSpentThisMonth, 0, ',', '.'),
    //         'target' => number_format($nextLevelRequirement, 0, ',', '.'),
    //         'remainingAmount' => number_format($remainingAmount, 0, ',', '.'),
    //         'currentLevel' => $currentLevelName,
    //         'nextLevel' => $nextLevelName,
    //         'readyForUpgrade' => $readyForUpgrade, // Kirim status siap naik level
    //         'daysLeftInMonth' => $daysLeftInMonth,
    //     ]);
    // }

    

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Cek apakah user mengunggah gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($user->image) {
                Storage::delete('public/avatars/' . $user->image);
            }

            // Simpan gambar baru
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/avatars', $imageName);

            // Simpan nama gambar baru di database
            $user->image = $imageName;
        }

        // Update data user
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'image' => $user->image, // Simpan avatar baru jika ada perubahan
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

   
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4086',
        ]);

        $user = Auth::user();

        // Hapus gambar lama jika ada
        if ($user->image && Storage::exists('public/avatars/' . $user->image)) {
            Storage::delete('public/avatars/' . $user->image);
        }

        // Simpan gambar baru
        $imageName = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/avatars', $imageName);

        // Update nama gambar di database
        $user->update(['image' => $imageName]);

        return response()->json(['success' => true, 'image' => asset('storage/avatars/' . $imageName)]);
    }
    


    public function join(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Ambil role Reseller dari database
        $resellerRole = Role::where('name', 'Reseller')->first();

        if (!$resellerRole) {
            return response()->json(['error' => 'Role Reseller tidak ditemukan'], 404);
        }

        // Sync role agar hanya menjadi Reseller (menghapus role lain jika ada)
        $user->roles()->sync([$resellerRole->id]);

        // Update reseller_level_id ke 1
        $user->update(['reseller_level_id' => 1]);

        return response()->json(['success' => 'Anda sekarang menjadi Reseller']);
    }

    // public function upgradeReseller(Request $request)
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json(['error' => 'User not authenticated'], 401);
    //     }

    //     // Daftar level dan syarat minimal pembelian
    //     $levelRequirements = [
    //         1 => 500000,    // Ruby
    //         2 => 1000000,   // Bronze
    //         3 => 10000000,  // Silver
    //         4 => 50000000,  // Gold
    //     ];

    //     $currentLevelId = $user->reseller_level_id;

    //     // Cek apakah ada level berikutnya
    //     if (!isset($levelRequirements[$currentLevelId])) {
    //         return response()->json(['error' => 'Anda sudah mencapai level tertinggi'], 400);
    //     }

    //     $requiredAmount = $levelRequirements[$currentLevelId];

    //     // Hitung total pembelian bulan ini
    //     $startOfMonth = Carbon::now()->startOfMonth();
    //     $endOfMonth = Carbon::now()->endOfMonth();
    //     $totalSpentThisMonth = Order::where('user_id', $user->id)
    //         ->where('status_id', 3)
    //         ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    //         ->sum('total_price');

    //     if ($totalSpentThisMonth < $requiredAmount) {
    //         return response()->json(['error' => 'Anda perlu belanja minimal ' . number_format($requiredAmount, 0, ',', '.') . ' untuk naik level.'], 400);
    //     }

    //     // Naikkan level reseller
    //     $newLevelId = $currentLevelId + 1;
    //     $user->update(['reseller_level_id' => $newLevelId]);

    //     $newLevelName = ResellerLevel::find($newLevelId)->name;

    //     return response()->json([
    //         'success' => 'Anda telah naik ke level ' . $newLevelName,
    //         'newLevel' => $newLevelName
    //     ]);
    // }

    public function upgradeReseller(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Daftar level dan syarat minimal pembelian
        $levelRequirements = [
            1 => 500000,    // Ruby
            2 => 1000000,   // Bronze
            3 => 10000000,  // Silver
            4 => 50000000,  // Gold
        ];

        $currentLevelId = $user->reseller_level_id;

        // Cek apakah ada level berikutnya
        if (!isset($levelRequirements[$currentLevelId])) {
            return response()->json(['error' => 'Anda sudah mencapai level tertinggi'], 400);
        }

        $requiredAmount = $levelRequirements[$currentLevelId];

        // Hitung total pembelian bulan ini dengan harga diskon
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $ordersThisMonth = Order::where('user_id', $user->id)
            ->where('status_id', 3)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with(['orderItems' => function ($query) use ($user) {
                $query->select('order_items.*', 'products.het_price')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->leftJoin('product_prices', function ($join) use ($user) {
                        $join->on('products.id', '=', 'product_prices.product_id')
                            ->where('product_prices.reseller_level_id', '=', $user->reseller_level_id);
                    })
                    ->selectRaw('IFNULL(product_prices.price, products.het_price) as final_price');
            }])
            ->get();

        $totalSpentThisMonth = $ordersThisMonth->sum(function ($order) {
            return $order->orderItems->sum(function ($item) {
                $discounts = $this->getApplicableDiscounts($item->product_id, $item->product->category_id);
                $discountedPrice = $this->calculateDiscountedPrice($item->final_price, $discounts);
                return $discountedPrice * $item->quantity;
            });
        });

        // Cek apakah total belanja memenuhi syarat untuk naik level
        if ($totalSpentThisMonth < $requiredAmount) {
            return response()->json([
                'error' => 'Anda perlu belanja minimal ' . number_format($requiredAmount, 0, ',', '.') . ' untuk naik level.'
            ], 400);
        }

        // Naikkan level reseller
        $newLevelId = $currentLevelId + 1;

        try {
            $user->update(['reseller_level_id' => $newLevelId]);

            $newLevelName = ResellerLevel::find($newLevelId)->name;

            return response()->json([
                'success' => 'Anda telah naik ke level ' . $newLevelName,
                'newLevel' => $newLevelName
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat memperbarui level reseller. Silakan coba lagi.'
            ], 500);
        }
    }

    public function reorder(Order $order)
    {
        // Pastikan pengguna yang mengakses adalah pemilik pesanan
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Mulai transaction
        DB::beginTransaction();

        try {
            // Ambil item dari pesanan
            $orderItems = $order->orderItems;

            // Loop melalui setiap item dan tambahkan ke keranjang
            foreach ($orderItems as $item) {
                // Cek apakah item sudah ada di keranjang
                $existingCartItem = Cart::where('user_id', auth()->id())
                    ->where('product_id', $item->product_id)
                    ->where('variant_id', $item->product_variant_id)
                    ->first();

                if ($existingCartItem) {
                    $existingCartItem->increment('quantity', $item->quantity);
                } else {
                    Cart::create([
                        'user_id' => auth()->id(),
                        'product_id' => $item->product_id,
                        'variant_id' => $item->product_variant_id,
                        'quantity' => $item->quantity,
                    ]);
                }
            }

            // Commit transaction
            DB::commit();

            // Redirect ke halaman cart dengan pesan sukses
            return redirect()->route('cart')->with('success', 'Items have been added to your cart.');
        } catch (\Exception $e) {
            // Rollback transaction jika ada error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to reorder items: ' . $e->getMessage());
        }
    }

    // public function downgradeReseller()
    // {
    //     $users = User::whereNotNull('reseller_level_id')->get();

    //     // Daftar syarat minimal pembelian per level
    //     $levelRequirements = [
    //         2 => 500000,    // Bronze -> Ruby
    //         3 => 1000000,   // Silver -> Bronze
    //         4 => 10000000,  // Gold -> Silver
    //     ];

    //     foreach ($users as $user) {
    //         $currentLevelId = $user->reseller_level_id;

    //         if (!isset($levelRequirements[$currentLevelId])) {
    //             continue; // Jika tidak ada aturan, lewati user ini
    //         }

    //         $requiredAmount = $levelRequirements[$currentLevelId];

    //         // Hitung total pembelian bulan lalu
    //         $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
    //         $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
    //         $totalSpentLastMonth = Order::where('user_id', $user->id)
    //             ->where('status_id', 3)
    //             ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
    //             ->sum('total_price');

    //         // Jika tidak memenuhi syarat, turunkan level
    //         if ($totalSpentLastMonth < $requiredAmount) {
    //             $user->update(['reseller_level_id' => $currentLevelId - 1]);
    //         }
    //     }
    // }

    public function downgradeReseller()
    {
        // Daftar syarat minimal pembelian per level
        $levelRequirements = [
            2 => 500000,    // Bronze -> Ruby
            3 => 1000000,   // Silver -> Bronze
            4 => 10000000,  // Gold -> Silver
        ];

        // Proses user dalam chunk untuk optimasi memori
        User::whereNotNull('reseller_level_id')->chunk(100, function ($users) use ($levelRequirements) {
            foreach ($users as $user) {
                $currentLevelId = $user->reseller_level_id;

                // Jika tidak ada aturan untuk level ini, lewati user ini
                if (!isset($levelRequirements[$currentLevelId])) {
                    continue;
                }

                $requiredAmount = $levelRequirements[$currentLevelId];

                // Hitung total pembelian bulan lalu dengan harga diskon
                $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
                $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

                $ordersLastMonth = Order::where('user_id', $user->id)
                    ->where('status_id', 3)
                    ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                    ->with(['orderItems' => function ($query) use ($user) {
                        $query->select('order_items.*', 'products.het_price')
                            ->join('products', 'order_items.product_id', '=', 'products.id')
                            ->leftJoin('product_prices', function ($join) use ($user) {
                                $join->on('products.id', '=', 'product_prices.product_id')
                                    ->where('product_prices.reseller_level_id', '=', $user->reseller_level_id);
                            })
                            ->selectRaw('IFNULL(product_prices.price, products.het_price) as final_price');
                    }])
                    ->get();

                $totalSpentLastMonth = $ordersLastMonth->sum(function ($order) {
                    return $order->orderItems->sum(function ($item) {
                        $discounts = $this->getApplicableDiscounts($item->product_id, $item->product->category_id);
                        $discountedPrice = $this->calculateDiscountedPrice($item->final_price, $discounts);
                        return $discountedPrice * $item->quantity;
                    });
                });

                // Jika tidak memenuhi syarat, turunkan level
                if ($totalSpentLastMonth < $requiredAmount) {
                    try {
                        $newLevelId = $currentLevelId - 1;
                        $user->update(['reseller_level_id' => $newLevelId]);

                        // Logging untuk melacak proses downgrade
                        Log::info("User {$user->id} downgraded from level {$currentLevelId} to {$newLevelId}.");
                    } catch (\Exception $e) {
                        // Tangani exception (misalnya, gagal update database)
                        Log::error("Failed to downgrade user {$user->id}: " . $e->getMessage());
                    }
                }
            }
        });
    }

    public function cancel(Order $order)
    {
        // Pastikan order milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Pastikan status order adalah 1 (Diproses)
        if ($order->status_id !== 1) {
            return response()->json(['error' => 'Order tidak dapat dibatalkan karena pesanan sudah dibuat'], 400);
        }

        // Ambil semua item yang terkait dengan order
        $orderItems = OrderItem::where('order_id', $order->id)->get();

        // Kembalikan stok untuk setiap item
        foreach ($orderItems as $item) {
            if ($item->product_variant_id) {
                // Jika produk memiliki varian, tambah stok pada tabel product_variant
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            } else {
                // Jika produk tidak memiliki varian, tambah stok pada tabel product
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }

         // Jika order menggunakan voucher, kembalikan status voucher ke "Unused"
        if ($order->voucher_id) {
            Voucher::where('id', $order->voucher_id)->update([
                'status' => 'Unused', // Kembalikan status voucher ke "Unused"
                'applicable_id' => null, // Hapus applicable_id (jika masih digunakan)
            ]);
        }

        // Update status order menjadi 4 (Cancelled)
        $order->update([
            'status_id' => 4, // Status dibatalkan
            'voucher_id' => null, // Set voucher_id menjadi null
        ]);

        return response()->json(['success' => 'Order berhasil dibatalkan']);
    }


    /**
     * Format angka ke rb/jt.
     */
    private function formatRupiah($value)
    {
        if ($value >= 1000000) {
            return number_format($value / 1000000, 1, ',', '') . 'jt';
        } elseif ($value >= 1000) {
            return number_format($value / 1000, 1, ',', '') . 'rb';
        }
        return number_format($value, 0, ',', '');
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
