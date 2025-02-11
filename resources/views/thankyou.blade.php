@include('layouts.header')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold">Invoice</h1>
                <p class="text-gray-600">Order #{{ $order->id }}</p>
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
                @foreach ($order->items as $item)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                    <td class="px-4 py-2">{{ $item->product->name }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($item->total_price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="text-right">
            <p class="text-gray-600">Subtotal: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p class="text-gray-600">Sales Tax (5%): Rp {{ number_format($order->total_price * 0.05, 0, ',', '.') }}</p>
            <p class="font-bold text-lg">Invoice Total: Rp {{ number_format($order->total_price * 1.05, 0, ',', '.') }}</p>
        </div>

        <!-- Terms & Conditions -->
        <div class="mt-8">
            <h2 class="font-bold text-lg">Terms & Conditions</h2>
            <p class="text-gray-600">Payment is due within 15 days</p>
            <p class="text-gray-600">Name of Bank: Your Bank Name</p>
            <p class="text-gray-600">Account number: 1234567890</p>
            <p class="text-gray-600">Routing: 098765432</p>
        </div>
    </div>
</body>
</html>