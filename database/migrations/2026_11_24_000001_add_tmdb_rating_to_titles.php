<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('titles', function (Blueprint $table): void {
            $table->decimal('tmdb_rating', 4, 2)->nullable()->after('source');
        });
    }

    public function down(): void
    {
        Schema::table('titles', function (Blueprint $table): void {
            $table->dropColumn('tmdb_rating');
        });
    }
};
