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
                'full_name' => 'Василий Иван Сергеевич',
                'phone' => '79123333333',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-02-01',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Петров Александр Николаевич',
                'phone' => '79123456789',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-03-15',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Смирнова Елена Петровна',
                'phone' => '79124567890',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-04-01',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Козлов Дмитрий Иванович',
                'phone' => '79125678901',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-05-10',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Новикова Анна Сергеевна',
                'phone' => '79126789012',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-06-20',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Иванов Сергей Петрович',
                'phone' => '79127890123',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-07-05',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Соколова Мария Александровна',
                'phone' => '79128901234',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-08-15',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Морозов Иван Дмитриевич',
                'phone' => '79129012345',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-09-01',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Волкова Ольга Николаевна',
                'phone' => '79120123456',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-10-10',
                'work_end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Лебедев Андрей Владимирович',
                'phone' => '79121234567',
                'role_id' => '3',
                'password' => bcrypt('12345678'),
                'work_start_date' => '2023-11-20',
                'work_end_date' => '2024-01-15',
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
