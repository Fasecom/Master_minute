<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
        });

        DB::table('skill_user')->insert([
            // Василий Иван Сергеевич (ID: 3)
            ['user_id' => 3, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 3, 'skill_id' => 2], // Замена батареек
            ['user_id' => 3, 'skill_id' => 3], // Замена ремешков
            ['user_id' => 3, 'skill_id' => 4], // Ремонт обуви
            ['user_id' => 3, 'skill_id' => 5], // Чистка обуви
            
            // Петров Александр Николаевич (ID: 4)
            ['user_id' => 4, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 4, 'skill_id' => 2], // Замена батареек
            ['user_id' => 4, 'skill_id' => 3], // Замена ремешков
            ['user_id' => 4, 'skill_id' => 4], // Ремонт обуви
            ['user_id' => 4, 'skill_id' => 5], // Чистка обуви
            
            // Смирнова Елена Петровна (ID: 5)
            ['user_id' => 5, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 5, 'skill_id' => 2], // Замена батареек
            ['user_id' => 5, 'skill_id' => 3], // Замена ремешков
            ['user_id' => 5, 'skill_id' => 4], // Ремонт обуви
            ['user_id' => 5, 'skill_id' => 5], // Чистка обуви

            // Козлов Дмитрий Иванович (ID: 6)
            ['user_id' => 6, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 6, 'skill_id' => 2], // Замена батареек
            ['user_id' => 6, 'skill_id' => 3], // Замена ремешков

            // Новикова Анна Сергеевна (ID: 7)
            ['user_id' => 7, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 7, 'skill_id' => 2], // Замена батареек
            ['user_id' => 7, 'skill_id' => 3], // Замена ремешков

            // Иванов Сергей Петрович (ID: 8)
            ['user_id' => 8, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 8, 'skill_id' => 2], // Замена батареек
            ['user_id' => 8, 'skill_id' => 3], // Замена ремешков

            // Соколова Мария Александровна (ID: 9)
            ['user_id' => 9, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 9, 'skill_id' => 2], // Замена батареек
            ['user_id' => 9, 'skill_id' => 3], // Замена ремешков

            // Морозов Иван Дмитриевич (ID: 10)
            ['user_id' => 10, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 10, 'skill_id' => 2], // Замена батареек
            ['user_id' => 10, 'skill_id' => 3], // Замена ремешков

            // Волкова Ольга Николаевна (ID: 11)
            ['user_id' => 11, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 11, 'skill_id' => 2], // Замена батареек
            ['user_id' => 11, 'skill_id' => 3], // Замена ремешков

            // Лебедев Андрей Владимирович (ID: 12)
            ['user_id' => 12, 'skill_id' => 1], // Ремонт часов
            ['user_id' => 12, 'skill_id' => 2], // Замена батареек
            ['user_id' => 12, 'skill_id' => 3], // Замена ремешков
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_user');
    }
}; 