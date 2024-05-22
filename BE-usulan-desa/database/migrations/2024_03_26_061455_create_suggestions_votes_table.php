<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suggestions_votes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['upvote', 'downvote']);
            $table->foreignId('userID')->constrained('users')->onDelete('cascade');
            $table->foreignId('suggestionID')->constrained('suggestions')->onDelete('cascade');
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
