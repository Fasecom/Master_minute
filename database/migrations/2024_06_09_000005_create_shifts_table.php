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
            // Понедельник
            [
                'user_id' => 3,
                'workshop_id' => 1, // ТРЦ Фокус
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => $startDate . ' 09:00:00'
            ],
            // Вторник
            [
                'user_id' => 3,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +1 day')) . ' 09:00:00'
            ],
            // Пятница
            [
                'user_id' => 3,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +4 day')) . ' 09:00:00'
            ],
            // Суббота
            [
                'user_id' => 3,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +5 day')) . ' 09:00:00'
            ],
        ]);

        // Петров Александр Николаевич (ID: 4) - график 5/2
        // Имеет все навыки, может работать в любой мастерской
        DB::table('working_shifts')->insert([
            // Понедельник
            [
                'user_id' => 4,
                'workshop_id' => 2, // ТРЦ Фиеста
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => $startDate . ' 10:00:00'
            ],
            // Вторник
            [
                'user_id' => 4,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +1 day')) . ' 10:00:00'
            ],
            // Среда
            [
                'user_id' => 4,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 10:00:00'
            ],
            // Четверг
            [
                'user_id' => 4,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 10:00:00'
            ],
            // Пятница
            [
                'user_id' => 4,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +4 day')) . ' 10:00:00'
            ],
        ]);

        // Смирнова Елена Петровна (ID: 5) - график 2/2
        // Имеет все навыки, может работать в любой мастерской
        DB::table('working_shifts')->insert([
            // Среда
            [
                'user_id' => 5,
                'workshop_id' => 3, // ТРЦ Горки
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 10:00:00'
            ],
            // Четверг
            [
                'user_id' => 5,
                'workshop_id' => 3,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 10:00:00'
            ],
            // Воскресенье
            [
                'user_id' => 5,
                'workshop_id' => 3,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +6 day')) . ' 10:00:00'
            ],
        ]);

        // Козлов Дмитрий Иванович (ID: 6) - график 2/2
        // Имеет только базовые навыки, работает в мастерских с базовыми услугами
        DB::table('working_shifts')->insert([
            // Понедельник
            [
                'user_id' => 6,
                'workshop_id' => 4, // ТРЦ Алое Поле
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => $startDate . ' 09:00:00'
            ],
            // Вторник
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +1 day')) . ' 09:00:00'
            ],
            // Пятница
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +4 day')) . ' 09:00:00'
            ],
            // Суббота
            [
                'user_id' => 6,
                'workshop_id' => 4,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +5 day')) . ' 09:00:00'
            ],
        ]);

        // Новикова Анна Сергеевна (ID: 7) - график 5/2
        // Имеет только базовые навыки, работает в мастерских с базовыми услугами
        DB::table('working_shifts')->insert([
            // Понедельник
            [
                'user_id' => 7,
                'workshop_id' => 5, // ТРЦ Родник
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => $startDate . ' 10:00:00'
            ],
            // Вторник
            [
                'user_id' => 7,
                'workshop_id' => 5,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +1 day')) . ' 10:00:00'
            ],
            // Среда
            [
                'user_id' => 7,
                'workshop_id' => 5,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 10:00:00'
            ],
            // Четверг
            [
                'user_id' => 7,
                'workshop_id' => 5,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 10:00:00'
            ],
            // Пятница
            [
                'user_id' => 7,
                'workshop_id' => 5,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +4 day')) . ' 10:00:00'
            ],
        ]);

        // Иванов Сергей Петрович (ID: 8) - график 2/2
        // Имеет только базовые навыки, работает в мастерских с базовыми услугами
        DB::table('working_shifts')->insert([
            // Среда
            [
                'user_id' => 8,
                'workshop_id' => 6, // ТРЦ Кольцо
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 10:00:00'
            ],
            // Четверг
            [
                'user_id' => 8,
                'workshop_id' => 6,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 10:00:00'
            ],
            // Воскресенье
            [
                'user_id' => 8,
                'workshop_id' => 6,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +6 day')) . ' 10:00:00'
            ],
        ]);

        // Соколова Мария Александровна (ID: 9) - график 2/2
        // Имеет только базовые навыки, работает в мастерских с базовыми услугами
        DB::table('working_shifts')->insert([
            // Понедельник
            [
                'user_id' => 9,
                'workshop_id' => 1, // ТРЦ Фокус
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => $startDate . ' 09:00:00'
            ],
            // Вторник
            [
                'user_id' => 9,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +1 day')) . ' 09:00:00'
            ],
            // Пятница
            [
                'user_id' => 9,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +4 day')) . ' 09:00:00'
            ],
            // Суббота
            [
                'user_id' => 9,
                'workshop_id' => 1,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +5 day')) . ' 09:00:00'
            ],
        ]);

        // Морозов Иван Дмитриевич (ID: 10) - график 5/2
        // Имеет только базовые навыки, работает в мастерских с базовыми услугами
        DB::table('working_shifts')->insert([
            // Понедельник
            [
                'user_id' => 10,
                'workshop_id' => 2, // ТРЦ Фиеста
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => $startDate . ' 10:00:00'
            ],
            // Вторник
            [
                'user_id' => 10,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +1 day')) . ' 10:00:00'
            ],
            // Среда
            [
                'user_id' => 10,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 10:00:00'
            ],
            // Четверг
            [
                'user_id' => 10,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 10:00:00'
            ],
            // Пятница
            [
                'user_id' => 10,
                'workshop_id' => 2,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +4 day')) . ' 10:00:00'
            ],
        ]);

        // Волкова Ольга Николаевна (ID: 11) - график 2/2
        // Имеет только базовые навыки, работает в мастерских с базовыми услугами
        DB::table('working_shifts')->insert([
            // Среда
            [
                'user_id' => 11,
                'workshop_id' => 3, // ТРЦ Горки
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +2 day')) . ' 10:00:00'
            ],
            // Четверг
            [
                'user_id' => 11,
                'workshop_id' => 3,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +3 day')) . ' 10:00:00'
            ],
            // Воскресенье
            [
                'user_id' => 11,
                'workshop_id' => 3,
                'cash_revenue' => 0,
                'cashless_revenue' => 0,
                'date' => date('Y-m-d', strtotime($startDate . ' +6 day')) . ' 10:00:00'
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('working_shifts');
    }
}; 