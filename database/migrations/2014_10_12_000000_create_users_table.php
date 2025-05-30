<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Id_Пользователя
            $table->string('full_name'); // ФИО
            $table->string('phone')->unique(); // Телефон
            $table->foreignId('role_id')->constrained('roles'); // Внешний ключ на ролях
            $table->string('password');
            $table->date('work_start_date')->nullable(); // Дата_начала_работы
            $table->date('work_end_date')->nullable(); // Дата_окончания_работы
            $table->rememberToken();
            $table->timestamps();
        });

        // Пример заполнения
        DB::table('users')->insert([
            [
                'full_name' => 'Маленьких Максим Александрович',
                'phone' => '79320191612',
                'role_id' => 1,
                'password' => bcrypt('02012005'),
                'work_start_date' => '2022-01-01',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Маленьких Людмила Борисовна',
                'phone' => '79123201751',
                'role_id' => 2,
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-02-01',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Иван Иванов Иванович',
                'phone' => '79123333333',
                'role_id' => 3,
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-02-01',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
