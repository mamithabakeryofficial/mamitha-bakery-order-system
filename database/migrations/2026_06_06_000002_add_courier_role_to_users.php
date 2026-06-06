<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL, alter the column enum values
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin', 'kitchen', 'courier') DEFAULT 'customer'");
        }
        // SQLite doesn't enforce ENUM, but we still define the schema accurately if needed
    }

    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            // Revert back to the original enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin', 'kitchen') DEFAULT 'customer'");
        }
    }
};
