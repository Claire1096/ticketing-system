<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->boolean('is_viewed_by_tech')->default(false)->after('status');
            $table->timestamp('tech_viewed_at')->nullable()->after('is_viewed_by_tech');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['is_viewed_by_tech', 'tech_viewed_at']);
        });
    }
};