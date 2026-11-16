<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titles', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
            $table->string('title');
            $table->string('original_title')->nullable();
            $table->string('slug')->unique();
            $table->text('synopsis')->nullable();
            $table->date('release_date')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('seasons_count')->nullable();
            $table->unsignedInteger('episodes_count')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('trailer_url', 500)->nullable();
            $table->string('status', 30)->default('published');
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('release_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titles');
    }
};
