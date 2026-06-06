@extends('layouts.app')

@section('title', 'Dashboard Dapur - Mamitha Bakery')

@section('content')
@include('layouts.partials.kitchen-navbar')

<div class="container py-5">
    {{-- Welcome --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h2 class="fw-bold text-success">Dashboard Dapur</h2>
                <p class="text-muted mb-0">Kelola pesanan yang perlu diproduksi hari ini.</p>
            </div>
            <div>
                <button type="button" id="btnConnectPrinter" class="btn btn-outline-dark fw-bold rounded-pill px-4 py-2 shadow-sm" onclick="connectBluetoothPrinter()">
                    <i class="fas fa-bluetooth-b me-2"></i>
                    <span id="printerStatusText">Hubungkan Printer</span>
                </button>
                <div id="printerStatusBadge" class="mt-2 text-end" style="display: none;">
                    <span class="badge bg-success rounded-pill px-3 py-2">
                        <i class="fas fa-check-circle me-1"></i>
                        <span id="printerDeviceName">Printer</span> Terhubung
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="row mb-5 g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-warning">
                <div class="card-body text-dark d-flex align-items-center">
                    <div class="rounded-circle bg-dark bg-opacity-10 p-3 me-3">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Menunggu</h6>
                        <h3 class="fw-bold mb-0">{{ $ordersWaiting->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-danger">
                <div class="card-body text-white d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="fas fa-fire fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Sedang Dibuat</h6>
                        <h3 class="fw-bold mb-0">{{ $ordersToMake->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-success">
                <div class="card-body text-white d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Siap Diambil</h6>
                        <h3 class="fw-bold mb-0">{{ $ordersReady->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-primary">
                <div class="card-body text-white d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="fas fa-trophy fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Selesai Hari Ini</h6>
                        <h3 class="fw-bold mb-0">{{ $completedToday }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders in Production --}}
    <div class="row mb-4">
        <div class="col-12">
            <!-- New Order Alert Banner -->
            <div id="newOrderAlert" class="alert alert-danger shadow-sm rounded-3 d-none align-items-center justify-content-between p-4 mb-4 fade show" role="alert">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3 animate-pulse">
                        <i class="fas fa-bell fa-2x text-danger"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading fw-bold mb-1">ADA PESANAN BARU MASUK!</h5>
                        <p class="mb-0 small">Ada pesanan baru yang harus diproduksi dapur. Alarm sedang berbunyi.</p>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-light btn-lg fw-bold text-danger" onclick="acknowledgeNewOrders()">
                        <i class="fas fa-volume-mute me-2"></i> Matikan Alarm & Segarkan
                    </button>
                </div>
            </div>

            <div class="card border shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="fas fa-fire text-danger me-2"></i>Sedang Diproduksi</h5>
                </div>
                <div class="card-body p-4">
                    @if($ordersToMake->count() > 0)
                        <div class="row g-3">
                            @foreach($ordersToMake as $order)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border rounded-3 h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="fw-bold text-primary-custom">{{ $order->invoice_number }}</span>
                                                {!! $order->status_badge !!}
                                            </div>
                                            <p class="text-muted mb-2 small">
                                                <i class="fas fa-user me-1"></i> {{ $order->user->name ?? $order->customer_name }}
                                            </p>
                                            <hr>
                                            <ul class="list-unstyled small mb-0">
                                                @foreach($order->details as $detail)
                                                <li class="mb-1">
                                                    <i class="fas fa-circle text-warning me-1" style="font-size: 6px; vertical-align: middle;"></i>
                                                    {{ $detail->product->name ?? 'Produk' }} × {{ $detail->quantity }}
                                                </li>
                                                @endforeach
                                            </ul>
                                            @if($order->customer_notes)
                                            <div class="alert alert-light mt-2 mb-0 py-2 small">
                                                <i class="fas fa-sticky-note me-1"></i> {{ $order->customer_notes }}
                                            </div>
                                            @endif
                                        </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-bold mb-2" onclick="printReceiptJS(this)" data-order='{{ json_encode([
                                                "invoice" => $order->invoice_number,
                                                "date" => $order->created_at->setTimezone("Asia/Jakarta")->format("d/m/Y H:i"),
                                                "customer" => $order->user->name ?? $order->customer_name,
                                                "method" => $order->customer_address ? "Delivery" : "Ambil di Toko",
                                                "address" => $order->customer_address,
                                                "items" => $order->details->map(function($d) { return ["qty" => $d->qty, "name" => $d->product->name ?? "Produk", "notes" => $d->notes]; }),
                                                "notes" => $order->customer_notes
                                            ]) }}'>
                                                <i class="fas fa-print me-1"></i> Cetak Struk
                                            </button>
                                            <form action="{{ route('kitchen.orders.updateStatus', $order->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_status" value="siap_diambil">
                                                <button type="submit" class="btn btn-sm btn-success w-100 fw-bold">
                                                    <i class="fas fa-check-circle me-1"></i> Selesai Dibuat (Siap Diambil)
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-50"></i>
                            <p class="text-muted mb-0">Tidak ada pesanan yang sedang diproduksi.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Waiting to be Made --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="fas fa-clock text-warning me-2"></i>Menunggu Diproses</h5>
                </div>
                <div class="card-body p-4">
                    @if($ordersWaiting->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Pelanggan</th>
                                        <th>Item</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordersWaiting as $order)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $order->invoice_number }}</span></td>
                                        <td>{{ $order->user->name ?? $order->customer_name }}</td>
                                        <td>
                                            @foreach($order->details as $detail)
                                                <span class="badge bg-light text-dark border">{{ $detail->product->name ?? 'Produk' }} × {{ $detail->quantity }}</span>
                                            @endforeach
                                        </td>
                                        <td class="small text-muted">{{ $order->customer_notes ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('kitchen.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="order_status" value="sedang_dibuat">
                                                <button type="submit" class="btn btn-sm btn-warning fw-bold text-dark me-1">
                                                    <i class="fas fa-play me-1"></i> Mulai Buat
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-secondary fw-bold" onclick="printReceiptJS(this)" title="Cetak Struk" data-order='{{ json_encode([
                                                "invoice" => $order->invoice_number,
                                                "date" => $order->created_at->setTimezone("Asia/Jakarta")->format("d/m/Y H:i"),
                                                "customer" => $order->user->name ?? $order->customer_name,
                                                "method" => $order->customer_address ? "Delivery" : "Ambil di Toko",
                                                "address" => $order->customer_address,
                                                "items" => $order->details->map(function($d) { return ["qty" => $d->qty, "name" => $d->product->name ?? "Produk", "notes" => $d->notes]; }),
                                                "notes" => $order->customer_notes
                                            ]) }}'>
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted mb-0">Tidak ada pesanan yang menunggu.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Ready for Pickup --}}
    <div class="row">
        <div class="col-12">
            <div class="card border shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="fas fa-box-open text-success me-2"></i>Siap Diambil</h5>
                </div>
                <div class="card-body p-4">
                    @if($ordersReady->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordersReady as $order)
                                    <tr>
                                        <td><span class="fw-semibold text-success">{{ $order->invoice_number }}</span></td>
                                        <td>{{ $order->user->name ?? $order->customer_name }}</td>
                                        <td>{{ $order->formatted_total }}</td>
                                        <td>
                                            <form action="{{ route('kitchen.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="order_status" value="selesai">
                                                <button type="submit" class="btn btn-sm btn-primary fw-bold me-1">
                                                    <i class="fas fa-check me-1"></i> Selesai (Diambil)
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-secondary fw-bold" onclick="printReceiptJS(this)" title="Cetak Struk" data-order='{{ json_encode([
                                                "invoice" => $order->invoice_number,
                                                "date" => $order->created_at->setTimezone("Asia/Jakarta")->format("d/m/Y H:i"),
                                                "customer" => $order->user->name ?? $order->customer_name,
                                                "method" => $order->customer_address ? "Delivery" : "Ambil di Toko",
                                                "address" => $order->customer_address,
                                                "items" => $order->details->map(function($d) { return ["qty" => $d->qty, "name" => $d->product->name ?? "Produk", "notes" => $d->notes]; }),
                                                "notes" => $order->customer_notes
                                            ]) }}'>
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted mb-0">Tidak ada pesanan yang siap diambil.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
.animate-pulse {
    animation: pulse 1s infinite;
}
</style>

@push('scripts')
<script>
    // ============================================================
    // 1. ALARM & NEW-ORDER POLLING (unchanged)
    // ============================================================
    let lastOrderId = {{ \App\Models\Order::max('id') ?? 0 }};
    let alarmInterval = null;
    let audioCtx = null;

    function playBeepAlarm() {
        try {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (alarmInterval) return;
            alarmInterval = setInterval(() => {
                let o1 = audioCtx.createOscillator(), g1 = audioCtx.createGain();
                o1.connect(g1); g1.connect(audioCtx.destination);
                o1.type = 'sine'; o1.frequency.setValueAtTime(880, audioCtx.currentTime);
                g1.gain.setValueAtTime(0.3, audioCtx.currentTime);
                g1.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
                o1.start(audioCtx.currentTime); o1.stop(audioCtx.currentTime + 0.25);
                setTimeout(() => {
                    if (!alarmInterval) return;
                    let o2 = audioCtx.createOscillator(), g2 = audioCtx.createGain();
                    o2.connect(g2); g2.connect(audioCtx.destination);
                    o2.type = 'sine'; o2.frequency.setValueAtTime(880, audioCtx.currentTime);
                    g2.gain.setValueAtTime(0.3, audioCtx.currentTime);
                    g2.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
                    o2.start(audioCtx.currentTime); o2.stop(audioCtx.currentTime + 0.25);
                }, 300);
            }, 1500);
        } catch (e) { console.error('Audio failed:', e); }
    }
    function stopBeepAlarm() { if (alarmInterval) { clearInterval(alarmInterval); alarmInterval = null; } }
    function checkNewOrders() {
        fetch(`{{ route('kitchen.orders.checkNew') }}?last_id=${lastOrderId}`)
            .then(r => r.json())
            .then(data => {
                if (data.new_orders_count > 0) {
                    document.getElementById('newOrderAlert').classList.remove('d-none');
                    document.getElementById('newOrderAlert').classList.add('d-flex');
                    playBeepAlarm();
                }
            }).catch(e => console.error('Error checking new orders:', e));
    }
    function acknowledgeNewOrders() { stopBeepAlarm(); location.reload(); }
    setInterval(checkNewOrders, 5000);
    checkNewOrders();

    // ============================================================
    // 2. WEB BLUETOOTH PRINTER CONNECTION
    // ============================================================
    let btDevice = null;
    let btCharacteristic = null;

    // Common Bluetooth Serial / SPP service UUIDs used by thermal printers
    const PRINTER_SERVICE_UUIDS = [
        '000018f0-0000-1000-8000-00805f9b34fb', // Common Chinese thermal (Zjiang, Eppos, etc)
        '00001101-0000-1000-8000-00805f9b34fb', // SPP (Serial Port Profile)
        'e7810a71-73ae-499d-8c15-faa9aef0c3f2', // Some BLE printers
    ];
    const PRINTER_CHAR_UUIDS = [
        '00002af1-0000-1000-8000-00805f9b34fb', // Common write characteristic
        'bef8d6c9-9c21-4c9e-b632-bd58c1009f9f', // Some BLE printers
    ];

    async function connectBluetoothPrinter() {
        if (btCharacteristic) {
            // Already connected – disconnect
            try { btDevice.gatt.disconnect(); } catch(e) {}
            btDevice = null;
            btCharacteristic = null;
            updatePrinterUI(false);
            return;
        }

        if (!navigator.bluetooth) {
            alert('Browser Anda tidak mendukung Web Bluetooth.\nGunakan Google Chrome atau Microsoft Edge versi terbaru.');
            return;
        }

        try {
            document.getElementById('printerStatusText').textContent = 'Mencari...';

            // Request device – accept ANY device the user picks
            btDevice = await navigator.bluetooth.requestDevice({
                acceptAllDevices: true,
                optionalServices: PRINTER_SERVICE_UUIDS
            });

            btDevice.addEventListener('gattserverdisconnected', () => {
                btCharacteristic = null;
                updatePrinterUI(false);
            });

            const server = await btDevice.gatt.connect();

            // Try each known service UUID until one works
            let service = null;
            for (const sUuid of PRINTER_SERVICE_UUIDS) {
                try {
                    service = await server.getPrimaryService(sUuid);
                    break;
                } catch(e) { /* try next */ }
            }

            if (!service) {
                // Fallback: get the first available service
                const services = await server.getPrimaryServices();
                if (services.length > 0) service = services[0];
            }

            if (!service) throw new Error('Tidak ditemukan service yang cocok pada printer ini.');

            // Try each known characteristic UUID
            let characteristic = null;
            for (const cUuid of PRINTER_CHAR_UUIDS) {
                try {
                    characteristic = await service.getCharacteristic(cUuid);
                    break;
                } catch(e) { /* try next */ }
            }

            if (!characteristic) {
                // Fallback: get the first writable characteristic
                const chars = await service.getCharacteristics();
                for (const c of chars) {
                    if (c.properties.write || c.properties.writeWithoutResponse) {
                        characteristic = c;
                        break;
                    }
                }
            }

            if (!characteristic) throw new Error('Tidak ditemukan characteristic yang bisa ditulis pada printer ini.');

            btCharacteristic = characteristic;
            updatePrinterUI(true, btDevice.name || 'Printer BT');

        } catch (err) {
            console.error('Bluetooth connect error:', err);
            if (err.name !== 'NotFoundError') { // user cancelled picker
                alert('Gagal menghubungkan printer: ' + err.message);
            }
            updatePrinterUI(false);
        }
    }

    function updatePrinterUI(connected, deviceName) {
        const btn = document.getElementById('btnConnectPrinter');
        const badge = document.getElementById('printerStatusBadge');
        const statusText = document.getElementById('printerStatusText');

        if (connected) {
            btn.classList.remove('btn-outline-dark');
            btn.classList.add('btn-success', 'text-white');
            statusText.textContent = 'Putuskan Printer';
            badge.style.display = 'block';
            document.getElementById('printerDeviceName').textContent = deviceName;
        } else {
            btn.classList.remove('btn-success', 'text-white');
            btn.classList.add('btn-outline-dark');
            statusText.textContent = 'Hubungkan Printer';
            badge.style.display = 'none';
        }
    }

    // ============================================================
    // 3. ESC/POS ENCODER (Vanilla JS)
    // ============================================================
    const ESC = 0x1B;
    const GS  = 0x1D;
    const LF  = 0x0A;

    class EscPosEncoder {
        constructor(paperWidth) {
            this.cols = paperWidth === 80 ? 48 : 32; // 58mm = 32 cols, 80mm = 48 cols
            this.buffer = [];
        }

        // Convert string to bytes (Latin-1 safe for thermal printers)
        _textToBytes(text) {
            const bytes = [];
            for (let i = 0; i < text.length; i++) {
                const code = text.charCodeAt(i);
                bytes.push(code > 255 ? 0x3F : code); // Replace non-latin with '?'
            }
            return bytes;
        }

        initialize() {
            this.buffer.push(ESC, 0x40); // ESC @ – Initialize printer
            return this;
        }

        alignCenter() {
            this.buffer.push(ESC, 0x61, 0x01); // ESC a 1
            return this;
        }

        alignLeft() {
            this.buffer.push(ESC, 0x61, 0x00); // ESC a 0
            return this;
        }

        bold(on) {
            this.buffer.push(ESC, 0x45, on ? 0x01 : 0x00); // ESC E n
            return this;
        }

        doubleSize(on) {
            // GS ! n – 0x11 = double width+height, 0x00 = normal
            this.buffer.push(GS, 0x21, on ? 0x11 : 0x00);
            return this;
        }

        text(str) {
            this.buffer.push(...this._textToBytes(str));
            return this;
        }

        newline(n) {
            for (let i = 0; i < (n || 1); i++) this.buffer.push(LF);
            return this;
        }

        dashedLine() {
            this.buffer.push(...this._textToBytes('-'.repeat(this.cols)));
            this.buffer.push(LF);
            return this;
        }

        // Print two columns: left-aligned label, right-aligned value
        twoColumns(left, right) {
            const space = this.cols - left.length - right.length;
            const line = left + (space > 0 ? ' '.repeat(space) : ' ') + right;
            this.buffer.push(...this._textToBytes(line), LF);
            return this;
        }

        feed(lines) {
            this.buffer.push(ESC, 0x64, lines || 3); // ESC d n
            return this;
        }

        cut() {
            this.buffer.push(GS, 0x56, 0x41, 0x00); // GS V A 0 – partial cut
            return this;
        }

        encode() {
            return new Uint8Array(this.buffer);
        }
    }

    // ============================================================
    // 4. BUILD RECEIPT BYTES
    // ============================================================
    function buildReceiptBytes(order) {
        const enc = new EscPosEncoder(58);

        enc.initialize()
           .alignCenter()
           .doubleSize(true)
           .bold(true)
           .text('Mamitha Bakery').newline()
           .doubleSize(false)
           .bold(false)
           .text('Pesanan Dapur').newline()
           .alignLeft()
           .dashedLine()
           .text('Tgl   : ' + order.date).newline()
           .text('Inv   : ' + order.invoice).newline()
           .text('Plg   : ' + order.customer).newline()
           .text('Kirim : ' + order.method).newline();
           
        if (order.address) {
            enc.text('Almt  : ' + order.address.substring(0, 30)).newline(); // Truncate to fit if too long
        }

        enc.dashedLine()
           .bold(true)
           .text('Daftar Item:').newline()
           .bold(false);

        order.items.forEach(item => {
            enc.text(item.qty + 'x ' + item.name).newline();
            if (item.notes) {
                enc.text('   > ' + item.notes).newline();
            }
        });

        if (order.notes) {
            enc.dashedLine()
               .bold(true).text('Catatan:').newline()
               .bold(false).text(order.notes).newline();
        }

        enc.dashedLine()
           .alignCenter()
           .text('-- Dapur --').newline()
           .feed(4)
           .cut();

        return enc.encode();
    }

    // ============================================================
    // 5. SEND DATA VIA BLUETOOTH (chunked writes)
    // ============================================================
    async function sendToPrinter(data) {
        if (!btCharacteristic) throw new Error('Printer tidak terhubung');

        const CHUNK = 100; // BLE max ~512 but safer in small chunks
        for (let i = 0; i < data.length; i += CHUNK) {
            const chunk = data.slice(i, i + CHUNK);
            if (btCharacteristic.properties.writeWithoutResponse) {
                await btCharacteristic.writeValueWithoutResponse(chunk);
            } else {
                await btCharacteristic.writeValue(chunk);
            }
            // Small delay between chunks to prevent buffer overflow
            await new Promise(r => setTimeout(r, 30));
        }
    }

    // ============================================================
    // 6. PRINT RECEIPT (main handler for all Cetak Struk buttons)
    // ============================================================
    async function printReceiptJS(btn) {
        const order = JSON.parse(btn.getAttribute('data-order'));

        // If Bluetooth printer is connected → send ESC/POS directly
        if (btCharacteristic) {
            const origHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mencetak...';
            try {
                const bytes = buildReceiptBytes(order);
                await sendToPrinter(bytes);
                btn.innerHTML = '<i class="fas fa-check me-1"></i> Tercetak!';
                setTimeout(() => { btn.innerHTML = origHtml; btn.disabled = false; }, 1500);
            } catch (err) {
                console.error('Print error:', err);
                alert('Gagal mencetak: ' + err.message + '\nMencoba cetak via browser...');
                btn.innerHTML = origHtml;
                btn.disabled = false;
                fallbackBrowserPrint(order);
            }
            return;
        }

        // No Bluetooth → fallback to browser print dialog via hidden iframe
        fallbackBrowserPrint(order);
    }

    // ============================================================
    // 7. FALLBACK: BROWSER PRINT (hidden iframe)
    // ============================================================
    function fallbackBrowserPrint(order) {
        let itemsHtml = '';
        order.items.forEach(item => {
            itemsHtml += '<tr><td style="width:15%">' + item.qty + 'x</td><td style="width:85%">' + item.name + '</td></tr>';
            if (item.notes) {
                itemsHtml += '<tr><td></td><td style="font-size:10px;font-style:italic">Catatan: ' + item.notes + '</td></tr>';
            }
        });
        let notesHtml = '';
        if (order.notes) {
            notesHtml = '<div class="divider"></div><div class="mt-1 font-bold">Catatan Pelanggan:</div><div>' + order.notes + '</div>';
        }
        const html = '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Cetak #' + order.invoice + '</title>'
            + '<style>body{font-family:"Courier New",Courier,monospace;font-size:12px;margin:0;padding:0;color:#000}'
            + '.receipt{width:58mm;max-width:100%;margin:0 auto;padding:5px}.text-center{text-align:center}'
            + '.font-bold{font-weight:bold}.mb-1{margin-bottom:5px}.mt-1{margin-top:5px}.mb-2{margin-bottom:10px}'
            + '.mt-2{margin-top:10px}table{width:100%;border-collapse:collapse}table td{vertical-align:top}'
            + '.divider{border-top:1px dashed #000;margin:5px 0}'
            + '@media print{body{margin:0;padding:0}@page{margin:0;size:58mm auto}}</style></head>'
            + '<body><div class="receipt"><div class="text-center mb-2"><h2 style="margin:0;font-size:16px">Mamitha Bakery</h2>'
            + '<div>Pesanan Dapur</div></div><div class="divider"></div>'
            + '<table class="mb-1"><tr><td style="width:30%">Tgl</td><td style="width:70%">: ' + order.date + '</td></tr>'
            + '<tr><td>Inv</td><td>: ' + order.invoice + '</td></tr>'
            + '<tr><td>Plg</td><td>: ' + order.customer + '</td></tr>'
            + '<tr><td>Kirim</td><td>: ' + order.method + '</td></tr>'
            + (order.address ? '<tr><td>Almt</td><td>: ' + order.address + '</td></tr>' : '')
            + '</table>'
            + '<div class="divider"></div><div class="font-bold mb-1">Daftar Item:</div>'
            + '<table>' + itemsHtml + '</table>' + notesHtml
            + '<div class="divider"></div><div class="text-center mt-2 mb-2" style="font-size:10px">-- Dapur --</div>'
            + '</div></body></html>';

        let f = document.getElementById('printReceiptIframe');
        if (!f) {
            f = document.createElement('iframe');
            f.id = 'printReceiptIframe';
            f.style.display = 'none';
            document.body.appendChild(f);
        }
        const d = f.contentWindow.document;
        d.open(); d.write(html); d.close();
        setTimeout(() => { f.contentWindow.focus(); f.contentWindow.print(); }, 300);
    }
</script>
@endpush
@endsection
