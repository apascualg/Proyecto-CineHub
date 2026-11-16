<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('title_id')->constrained('titles')->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('role_type', 30);
            $table->string('character_name')->nullable();
            $table->unsignedInteger('billing_order')->nullable();
            $table->timestamps();

            $table->index(['title_id', 'role_type']);
            $table->unique(['title_id', 'person_id', 'role_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
