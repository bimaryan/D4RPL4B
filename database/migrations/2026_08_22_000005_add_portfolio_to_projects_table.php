<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('portfolio_path')->nullable()->after('image_url');
            $table->string('portfolio_index')->nullable()->after('portfolio_path'); // e.g. index.html
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['portfolio_path', 'portfolio_index']);
        });
    }
};
