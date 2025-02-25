<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Order #{{ $order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold">Invoice</h1>
                <p class="text-gray-600">Order #{{ $order->invoice_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-600">Invoice Date: {{ $order->created_at->format('d/m/Y') }}</p>
                <p class="text-gray-600">Due Date: {{ $order->created_at->addDays(15)->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Bill To and Ship To -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h2 class="font-bold text-lg">Bill To</h2>
                <p>{{ $order->user->first_name . ' ' . $order->user->last_name }}</p>
                <p>{{ $order->user->email }}</p>
                <p>{{ $order->user->phone_number }}</p>
            </div>
            <div>
                <h2 class="font-bold text-lg">Ship To</h2>
                <p>{{ $order->user->first_name . ' ' . $order->user->last_name }}</p>
                <p>{{ $order->user->email }}</p>
                <p>{{ $order->user->phone_number }}</p>
            </div>
        </div>

        <!-- Order Details -->
        <div class="mb-8">
            <h2 class="font-bold text-lg">Order Details</h2>
            <p><strong>Status:</strong> {{ $order->status->name }}</p>
            <p><strong>Shipping Method:</strong> {{ $order->shipping_method }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment->payment_method }}</p>
            <p><strong>Payment Status:</strong> {{ $order->payment->payment_status }}</p>
            <p><strong>Notes:</strong> {{ $order->notes }}</p>
        </div>

        <!-- Items Table -->
        <table class="w-full mb-8">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">QTY</th>
                    <th class="px-4 py-2 text-left">DESCRIPTION</th>
                    <th class="px-4 py-2 text-left">UNIT PRICE</th>
                    <th class="px-4 py-2 text-left">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                    <td class="px-4 py-2">
                        {{ $item->product->name }}
                        @if ($item->productVariant)
                            <br><span class="text-sm text-gray-600">Variant: {{ $item->productVariant->color }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="text-right">
            <p class="text-gray-600">Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
            @if ($voucherAmount > 0)
                <p class="text-gray-600">Voucher Discount: -Rp {{ number_format($voucherAmount, 0, ',', '.') }}</p>
            @endif
            {{-- <p class="text-gray-600">Biaya Admin: Rp {{ number_format($adminFee, 0, ',', '.') }}</p> --}}
            <p class="font-bold text-lg">Invoice Total: Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>

        <!-- Payment Instructions -->
        @if ($order->payment->payment_method !== 'cash' && $order->payment->payment_status === 'pending')
        <div class="mt-8">
            <h2 class="font-bold text-lg">Instruksi Pembayaran</h2>
            <p class="text-gray-600">Silakan selesaikan pembayaran Anda menggunakan metode berikut:</p>
            <p class="text-gray-600">Bank: {{ $order->payment->bank_name }}</p>
            <p class="text-gray-600">Nomor VA: {{ $order->payment->va_number }}</p>
            <p class="text-gray-600">Batas Waktu Pembayaran: {{ $order->payment->expiry_date }}</p>
            <div class="mt-4 text-center">
                <a href="{{ $order->payment->payment_url }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Lanjutkan Pembayaran</a>
            </div>
        </div>
        @endif

        <!-- Payment Notification -->
        @if ($order->payment->payment_status === 'settlement')
        <div class="mt-8 bg-green-100 p-4 rounded-lg">
            <p class="text-green-700">Pembayaran Anda telah berhasil diproses. Terima kasih!</p>
        </div>
        @elseif ($order->payment->payment_status === 'pending')
        <div class="mt-8 bg-yellow-100 p-4 rounded-lg">
            <p class="text-yellow-700">Pembayaran Anda masih pending. Silakan selesaikan pembayaran sebelum batas waktu.</p>
        </div>
        @elseif ($order->payment->payment_status === 'expire')
        <div class="mt-8 bg-red-100 p-4 rounded-lg">
            <p class="text-red-700">Pembayaran Anda telah kedaluwarsa. Silakan lakukan pemesanan ulang.</p>
        </div>
        @endif

        <!-- Terms & Conditions -->
        <div class="mt-8">
            <h2 class="font-bold text-lg">Terms & Conditions</h2>
            <p class="text-gray-600">Pembayaran harus diselesaikan sebelum batas waktu yang ditentukan.</p>
            <p class="text-gray-600">Pembayaran yang terlambat akan menyebabkan pesanan dibatalkan secara otomatis.</p>
            <p class="text-gray-600">Jika Anda memiliki pertanyaan, silakan hubungi kami di support@example.com.</p>
        </div>
    </div>
</body>
</html>