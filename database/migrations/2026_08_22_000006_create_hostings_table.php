<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('students')->cascadeOnDelete();
            $table->string('domain')->nullable()->unique(); // e.g. nim.d4rpl4b.test
            $table->string('path'); // storage path: hostings/{hash}
            $table->string('status')->default('active'); // active, suspended
            $table->bigInteger('quota_mb')->default(500); // 500MB default
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostings');
    }
};
