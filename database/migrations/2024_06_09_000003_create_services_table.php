<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
        });

        DB::table('services')->insert([
            ['name' => 'Ремонт часов'],
            ['name' => 'Продажа и замена батареек'],
            ['name' => 'Продажа и замена ремешков'],
            ['name' => 'Ремонт обуви'],
            ['name' => 'Чистка обуви'],
            ['name' => 'Продажа средств для чистки обуви'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
}; 