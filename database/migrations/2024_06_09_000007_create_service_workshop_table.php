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
            ['workshop_id' => 1, 'service_id' => 1],
            ['workshop_id' => 1, 'service_id' => 2],
            ['workshop_id' => 1, 'service_id' => 3],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('service_workshop');
    }
}; 