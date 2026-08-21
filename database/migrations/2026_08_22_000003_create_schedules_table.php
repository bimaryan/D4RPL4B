<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('day'); // Senin, Selasa, etc
            $table->string('time_start'); // 08:00
            $table->string('time_end'); // 10:30
            $table->string('course');
            $table->string('lecturer')->nullable();
            $table->string('room')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
