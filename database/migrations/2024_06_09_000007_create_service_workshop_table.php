<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_workshop', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_id')->constrained('workshops')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
        });

        DB::table('service_workshop')->insert([
            // ТРЦ Фокус
            ['workshop_id' => 1, 'service_id' => 1], // Ремонт часов
            ['workshop_id' => 1, 'service_id' => 2], // Продажа и замена батареек
            ['workshop_id' => 1, 'service_id' => 3], // Продажа и замена ремешков
            
            // ТРЦ Фиеста
            ['workshop_id' => 2, 'service_id' => 1],
            ['workshop_id' => 2, 'service_id' => 2],
            ['workshop_id' => 2, 'service_id' => 3],
            
            // ТРЦ Горки
            ['workshop_id' => 3, 'service_id' => 1],
            ['workshop_id' => 3, 'service_id' => 2],
            ['workshop_id' => 3, 'service_id' => 3],
            
            // ТРЦ Алое Поле
            ['workshop_id' => 4, 'service_id' => 1],
            ['workshop_id' => 4, 'service_id' => 2],
            ['workshop_id' => 4, 'service_id' => 3],
            
            // ТРЦ Родник
            ['workshop_id' => 5, 'service_id' => 1],
            ['workshop_id' => 5, 'service_id' => 2],
            ['workshop_id' => 5, 'service_id' => 3],
            ['workshop_id' => 5, 'service_id' => 4],
            ['workshop_id' => 5, 'service_id' => 5],
            ['workshop_id' => 5, 'service_id' => 6],
            
            // ТРЦ Кольцо
            ['workshop_id' => 6, 'service_id' => 1],
            ['workshop_id' => 6, 'service_id' => 2],
            ['workshop_id' => 6, 'service_id' => 3],
            ['workshop_id' => 6, 'service_id' => 4],
            ['workshop_id' => 6, 'service_id' => 5],
            ['workshop_id' => 6, 'service_id' => 6],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('service_workshop');
    }
}; 