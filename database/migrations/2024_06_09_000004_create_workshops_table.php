<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('address', 255);
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->date('open_date');
            $table->date('close_date')->nullable();
            $table->time('open_time');
            $table->time('close_time');
        });

        DB::table('workshops')->insert([
            [
                'name' => 'ТРЦ Фокус',
                'address' => 'Молдавская улица, 16',
                'phone' => '+7 900 123-45-67',
                'email' => 'focus@mail.ru',
                'open_date' => '2015-05-12',
                'close_date' => null,
                'open_time' => '09:00:00',
                'close_time' => '22:00:00',
            ],
            [
                'name' => 'ТРЦ Фиеста',
                'address' => 'ул. Ленина, 10',
                'phone' => '+7 900 123-45-67',
                'email' => 'festa@mail.ru',
                'open_date' => '2015-05-12',
                'close_date' => null,
                'open_time' => '10:00:00',
                'close_time' => '22:00:00',
            ],
            [
                'name' => 'ТРЦ Горки',
                'address' => 'ул. Труда, 203',
                'phone' => '+7 900 123-45-68',
                'email' => 'gorki@mail.ru',
                'open_date' => '2016-03-15',
                'close_date' => null,
                'open_time' => '10:00:00',
                'close_time' => '22:00:00',
            ],
            [
                'name' => 'ТРЦ Алое Поле',
                'address' => 'ул. Сони Кривой, 38',
                'phone' => '+7 900 123-45-69',
                'email' => 'aloe@mail.ru',
                'open_date' => '2014-08-20',
                'close_date' => null,
                'open_time' => '09:00:00',
                'close_time' => '21:00:00',
            ],
            [
                'name' => 'ТРЦ Родник',
                'address' => 'ул. Труда, 203',
                'phone' => '+7 900 123-45-70',
                'email' => 'rodnik@mail.ru',
                'open_date' => '2017-11-05',
                'close_date' => null,
                'open_time' => '10:00:00',
                'close_time' => '22:00:00',
            ],
            [
                'name' => 'ТРЦ Кольцо',
                'address' => 'ул. Молодогвардейцев, 61',
                'phone' => '+7 900 123-45-71',
                'email' => 'kolco@mail.ru',
                'open_date' => '2013-06-18',
                'close_date' => null,
                'open_time' => '09:00:00',
                'close_time' => '21:00:00',
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
}; 