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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status');
            $table->string('target');
            $table->foreignId('userID')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('suggestionID')->nullable()->constrained('suggestions')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
