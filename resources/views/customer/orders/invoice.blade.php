<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 2px solid #8B4513; padding-bottom: 20px; }
        .header h1 { color: #8B4513; margin: 0; font-size: 28px; }
        .details-container { width: 100%; margin-bottom: 40px; }
        .details-container td { vertical-align: top; }
        .details-container .right { text-align: right; }
        .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .invoice-table th { background: #8B4513; color: white; padding: 10px; text-align: left; }
        .invoice-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .invoice-table .right { text-align: right; }
        .invoice-table .center { text-align: center; }
        .total-row { font-weight: bold; font-size: 16px; background: #f9f9f9; }
        .footer { text-align: center; color: #777; margin-top: 50px; border-top: 1px solid #eee; padding-top: 20px; }
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        .status-paid { background: #28a745; color: white; }
        .status-unpaid { background: #ffc107; color: #333; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
            <tr>
                <td>
                    <h1 style="color: #8B4513; margin: 0;">MAMITHA BAKERY</h1>
                    <p style="margin: 5px 0; color: #777;">Jl. Contoh Alamat Bakery No. 123, Kota Anda<br>WhatsApp: 0812-3456-7890</p>
                </td>
                <td style="text-align: right;">
                    <h2 style="margin: 0; color: #555;">INVOICE</h2>
                    <p style="margin: 5px 0;"><strong>#{{ $order->invoice_number }}</strong><br>
                    Tanggal: {{ $order->order_date->format('d M Y') }}</p>
                    <div>
                        @if($order->payment_status == 'settlement')
                            <span class="status-badge status-paid">LUNAS</span>
                        @else
                            <span class="status-badge status-unpaid">BELUM LUNAS</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" class="details-container">
            <tr>
                <td width="50%">
                    <h4 style="margin-bottom: 5px; color: #8B4513;">Ditagihkan Kepada:</h4>
                    <strong>{{ $order->customer_name }}</strong><br>
                    WhatsApp: {{ $order->customer_phone }}<br>
                    {{ $order->customer_address ?? 'Tidak ada alamat (Ambil di toko)' }}
                </td>
                <td width="50%" class="right">
                    <h4 style="margin-bottom: 5px; color: #8B4513;">Informasi Pembayaran:</h4>
                    Metode: {{ $order->payment_method === 'cash' ? 'BAYAR DI TEMPAT (COD)' : ($order->payment ? str_replace('_', ' ', strtoupper($order->payment->payment_method)) : 'ONLINE') }}<br>
                    Status: {{ ucfirst($order->payment_status) }}
                </td>
            </tr>
        </table>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="center">Harga</th>
                    <th class="center">Qty</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->details as $detail)
                <tr>
                    <td>
                        <strong>{{ $detail->product->name }}</strong>
                        @if($detail->notes)
                            <br><small style="color:#777;">Note: {{ $detail->notes }}</small>
                        @endif
                    </td>
                    <td class="center">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="center">{{ $detail->qty }}</td>
                    <td class="right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" class="right" style="padding-top: 15px; padding-bottom: 15px;">TOTAL PEMBAYARAN:</td>
                    <td class="right" style="padding-top: 15px; padding-bottom: 15px; color: #8B4513; font-size: 18px;">
                        {{ $order->formatted_total }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Terima kasih atas pesanan Anda di Mamitha Bakery.<br>Pesanan yang sudah dibayar tidak dapat dibatalkan atau dikembalikan.</p>
        </div>
    </div>
</body>
</html>
