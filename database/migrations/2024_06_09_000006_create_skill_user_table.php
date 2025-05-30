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
            ['user_id' => 3, 'skill_id' => 1],
            ['user_id' => 3, 'skill_id' => 2],
            ['user_id' => 3, 'skill_id' => 3],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_user');
    }
}; 