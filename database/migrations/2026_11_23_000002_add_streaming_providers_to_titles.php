<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('titles', function (Blueprint $table): void {
            $table->json('streaming_providers')->nullable()->after('trailer_url');
        });
    }

    public function down(): void
    {
        Schema::table('titles', function (Blueprint $table): void {
            $table->dropColumn('streaming_providers');
        });
    }
};
