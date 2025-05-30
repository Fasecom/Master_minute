<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('workshop_id')->constrained('workshops');
            $table->decimal('cash_revenue', 10, 2);
            $table->decimal('cashless_revenue', 10, 2);
            $table->dateTime('date');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('working_shifts');
    }
}; 