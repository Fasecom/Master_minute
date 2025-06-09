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

        // Добавляем график смен на неделю
        $startDate = '2025-06-02'; // Понедельник
        $endDate = '2025-06-08';   // Воскресенье

        // Василий Иван Сергеевич (ID: 3) - график 2/2
        // Имеет все навыки, может работать в любой мастерской
        DB::table('working_shifts')->insert([
            [
                'user_id' => 3,
                'workshop_id' => 1, // ТРЦ Фокус
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => $startDate . ' 09:00:00'
            ],
            [
                'user_id' => 3,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +1 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 3,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 3,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 3,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +4 day')) . ' 09:00:00'
            ],
        ]);
        DB::table('working_shifts')->insert([
            [
                'user_id' => 4,
                'workshop_id' => 2, // ТРЦ Фиеста
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => $startDate . ' 09:00:00'
            ],
            [
                'user_id' => 4,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +1 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 4,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 4,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 4,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +4 day')) . ' 09:00:00'
            ],
        ]);
        DB::table('working_shifts')->insert([
            [
                'user_id' => 5,
                'workshop_id' => 3,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 5,
                'workshop_id' => 3,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 5,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +5 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 5,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +6 day')) . ' 09:00:00'
            ],
        ]);

        // Иванов Иван Иванович (ID: 6) - график 2/2
        // Специализируется на ремонте одежды
        DB::table('working_shifts')->insert([
            [
                'user_id' => 6,
                'workshop_id' => 4, // ТРЦ Мега
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => $startDate . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +1 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +4 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +5 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +6 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +7 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +8 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +9 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,     
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +10 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +11 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +12 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +13 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +14 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +15 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +16 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +17 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +18 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +19 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +20 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +21 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +22 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +23 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +24 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +25 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,    
                'date' => date('Y-m-d', strtotime($startDate . ' +26 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +27 day')) . ' 09:00:00'   
            ],
            [
                'user_id' => 6,
                'workshop_id' => 2,
                'cash_revenue' => 1,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +28 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 2,
                'cash_revenue' => 1,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +29 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 2,
                'cash_revenue' => 1,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +30 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 2,
                'cash_revenue' => 1,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +31 day')) . ' 09:00:00'
            ],
            [
                'user_id' => 6,
                'workshop_id' => 2,
                'cash_revenue' => 1,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +32 day')) . ' 09:00:00'
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('working_shifts');
    }
}; 