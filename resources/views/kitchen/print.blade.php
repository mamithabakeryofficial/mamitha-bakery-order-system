<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pesanan #{{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #000;
        }
        .receipt {
            width: 58mm; /* Sesuaikan dengan ukuran kertas printer thermal Anda (58mm/80mm) */
            max-width: 100%;
            margin: 0 auto;
            padding: 5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mt-1 { margin-top: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mt-2 { margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; }
        table td { vertical-align: top; }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        @media print {
            body { margin: 0; padding: 0; }
            @page {
                margin: 0;
                size: 58mm auto; /* Jika 80mm ganti menjadi 80mm */
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="text-center mb-2">
            <h2 style="margin: 0; font-size: 16px;">Mamitha Bakery</h2>
            <div>Pesanan Dapur</div>
        </div>
        
        <div class="divider"></div>
        
        <table class="mb-1">
            <tr>
                <td style="width: 30%">Tgl</td>
                <td style="width: 70%">: {{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Inv</td>
                <td>: {{ $order->invoice_number }}</td>
            </tr>
            <tr>
                <td>Plg</td>
                <td>: {{ $order->user->name ?? $order->customer_name }}</td>
            </tr>
            <tr>
                <td>Kirim</td>
                <td>: {{ $order->shipping_method == 'delivery' ? 'Delivery' : 'Ambil di Toko' }}</td>
            </tr>
        </table>
        
        <div class="divider"></div>
        
        <div class="font-bold mb-1">Daftar Item:</div>
        <table>
            @foreach($order->details as $detail)
            <tr>
                <td style="width: 15%;">{{ $detail->quantity }}x</td>
                <td style="width: 85%;">{{ $detail->product->name ?? 'Produk' }}</td>
            </tr>
            @if($detail->notes)
            <tr>
                <td></td>
                <td style="font-size: 10px; font-style: italic;">Catatan: {{ $detail->notes }}</td>
            </tr>
            @endif
            @endforeach
        </table>
        
        @if($order->customer_notes)
        <div class="divider"></div>
        <div class="mt-1 font-bold">Catatan Pelanggan:</div>
        <div>{{ $order->customer_notes }}</div>
        @endif
        
        <div class="divider"></div>
        <div class="text-center mt-2 mb-2" style="font-size: 10px;">
            -- Dapur --
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
