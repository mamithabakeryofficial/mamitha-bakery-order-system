<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'invoice_number', 'total_price',
        'payment_status', 'order_status', 'payment_method',
        'customer_notes', 'customer_name', 'customer_phone',
        'customer_address', 'customer_lat', 'customer_lng',
        'courier_name', 'courier_phone', 'delivery_notes',
        'order_date',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'order_date' => 'date',
        'customer_lat' => 'float',
        'customer_lng' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // ── Helpers ──
    public static function generateInvoice(): string
    {
        $prefix = 'INV-MB';
        $date = now()->format('Ymd');
        $last = self::whereDate('created_at', today())->count() + 1;
        return $prefix . '-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->order_status) {
            'menunggu_pembayaran' => '<span class="badge bg-warning text-dark">Menunggu Pembayaran</span>',
            'dibayar' => '<span class="badge bg-info">Pembayaran Berhasil</span>',
            'diproses' => '<span class="badge bg-primary">Diproses Admin</span>',
            'sedang_dibuat' => '<span class="badge bg-orange text-white" style="background:#fd7e14">Sedang Diproduksi</span>',
            'siap_diambil' => '<span class="badge bg-success">Siap Diambil</span>',
            'sedang_dikirim' => '<span class="badge text-white" style="background:#0d6efd"><i class="fas fa-truck me-1"></i>Sedang Dikirim</span>',
            'selesai' => '<span class="badge bg-success">Selesai</span>',
            'dibatalkan' => '<span class="badge bg-danger">Dibatalkan</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getPaymentBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'settlement' => '<span class="badge bg-success">Lunas</span>',
            'expire' => '<span class="badge bg-secondary">Expired</span>',
            'cancel' => '<span class="badge bg-danger">Cancel</span>',
            'deny' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'dibayar' => 'Pembayaran Berhasil',
            'diproses' => 'Diproses Admin',
            'sedang_dibuat' => 'Sedang Diproduksi',
            'siap_diambil' => 'Siap Diambil',
            'sedang_dikirim' => 'Sedang Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => '-',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'Bayar di Tempat (Cash)',
            'midtrans' => 'Bayar Online (Midtrans)',
            default => 'Midtrans',
        };
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->customer_lat && $this->customer_lng) {
            return "https://www.google.com/maps?q={$this->customer_lat},{$this->customer_lng}";
        }
        return null;
    }

    public function getGoogleMapsEmbedUrlAttribute(): ?string
    {
        if ($this->customer_lat && $this->customer_lng) {
            $key = config('services.google_maps.api_key');
            return "https://www.google.com/maps/embed/v1/place?key={$key}&q={$this->customer_lat},{$this->customer_lng}&zoom=16";
        }
        return null;
    }

    // ── Scopes ──
    public function scopeByStatus($query, $status)
    {
        return $query->where('order_status', $status);
    }

    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('order_date', [$from, $to]);
    }
}
