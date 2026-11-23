<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('titles', function (Blueprint $table): void {
            $table->unsignedBigInteger('tmdb_id')->nullable()->unique()->after('id');
            $table->string('source', 30)->default('local')->after('tmdb_id');
        });

        Schema::table('genres', function (Blueprint $table): void {
            $table->unsignedBigInteger('tmdb_id')->nullable()->unique()->after('id');
        });

        Schema::table('people', function (Blueprint $table): void {
            $table->unsignedBigInteger('tmdb_id')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('titles', function (Blueprint $table): void {
            $table->dropUnique(['tmdb_id']);
            $table->dropColumn(['tmdb_id', 'source']);
        });

        Schema::table('genres', function (Blueprint $table): void {
            $table->dropUnique(['tmdb_id']);
            $table->dropColumn('tmdb_id');
        });

        Schema::table('people', function (Blueprint $table): void {
            $table->dropUnique(['tmdb_id']);
            $table->dropColumn('tmdb_id');
        });
    }
};
