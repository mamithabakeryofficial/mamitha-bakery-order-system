<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Google Maps coordinates
            $table->decimal('customer_lat', 10, 7)->nullable()->after('customer_address');
            $table->decimal('customer_lng', 10, 7)->nullable()->after('customer_lat');

            // Payment method
            $table->string('payment_method', 20)->default('midtrans')->after('payment_status');

            // Courier / delivery info
            $table->string('courier_name')->nullable()->after('customer_notes');
            $table->string('courier_phone', 20)->nullable()->after('courier_name');
            $table->text('delivery_notes')->nullable()->after('courier_phone');
        });

        // Update the order_status enum to include 'sedang_dikirim'
        // Using raw SQL because Laravel doesn't natively support altering enums
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('menunggu_pembayaran','dibayar','diproses','sedang_dibuat','siap_diambil','sedang_dikirim','selesai','dibatalkan') DEFAULT 'menunggu_pembayaran'");
        }
        // For SQLite (dev), enum is stored as text so no alteration needed
    }

    public function down(): void
    {
        // Revert order_status enum
        if (config('database.default') === 'mysql') {
            // First update any rows that have the new status
            DB::table('orders')->where('order_status', 'sedang_dikirim')->update(['order_status' => 'siap_diambil']);
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('menunggu_pembayaran','dibayar','diproses','sedang_dibuat','siap_diambil','selesai','dibatalkan') DEFAULT 'menunggu_pembayaran'");
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_lat',
                'customer_lng',
                'payment_method',
                'courier_name',
                'courier_phone',
                'delivery_notes',
            ]);
        });
    }
};
