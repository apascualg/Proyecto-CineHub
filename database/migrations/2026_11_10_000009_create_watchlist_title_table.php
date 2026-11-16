<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watchlist_title', function (Blueprint $table) {
            $table->foreignId('watchlist_id')->constrained('watchlists')->cascadeOnDelete();
            $table->foreignId('title_id')->constrained('titles')->cascadeOnDelete();
            $table->timestamp('added_at')->useCurrent();
            $table->primary(['watchlist_id', 'title_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watchlist_title');
    }
};
