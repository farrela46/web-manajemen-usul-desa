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
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')->onDelete('cascade');
            $table->string('suggestion');
            $table->longText('description');
            $table->string('status')->nullable();
            $table->unsignedBigInteger('suggestions_id')->nullable();
            $table->foreign('suggestions_id')->references('id')->on('suggestions')->onDelete('set null');
            $table->date('date');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
