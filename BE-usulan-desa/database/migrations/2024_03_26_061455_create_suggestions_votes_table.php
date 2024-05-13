<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suggestions_votes', function (Blueprint $table) {
            $table->id();
            $table->integer('userID')->references('users.id');
            $table->integer('suggestionID')->references('suggestions.id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggestions_up_votes');
    }
};
