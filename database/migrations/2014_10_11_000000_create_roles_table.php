<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
        });

        // Пример заполнения
        DB::table('roles')->insert([
            ['name' => 'Администратор'],
            ['name' => 'Мастер'],
            ['name' => 'Клиент'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
}; 