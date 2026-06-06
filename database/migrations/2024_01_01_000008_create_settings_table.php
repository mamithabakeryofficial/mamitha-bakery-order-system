<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_limits', function (Blueprint $table) {
            $table->id();
            $table->integer('max_orders_per_day')->default(100);
            $table->integer('max_products_per_day')->default(500);
            $table->time('opening_time')->default('08:00');
            $table->time('closing_time')->default('20:00');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('Mamitha Bakery');
            $table->text('store_address')->nullable();
            $table->string('store_phone')->nullable();
            $table->string('store_email')->nullable();
            $table->string('store_logo')->nullable();
            $table->text('store_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('daily_limits');
    }
};
