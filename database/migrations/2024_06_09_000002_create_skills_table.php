<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
        });

        DB::table('skills')->insert([
            ['name' => 'Ремонт часов'],
            ['name' => 'Замена батареек'],
            ['name' => 'Замена ремешков'],
            ['name' => 'Ремонт обуви'],
            ['name' => 'Чистка обуви'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
}; 