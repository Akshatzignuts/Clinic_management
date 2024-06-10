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
        Schema::create('medical__histories', function (Blueprint $table) {
            $table->id();
            $table->string('disease',64);
            $table->date('date');
            $table->json('medicine')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->string('file');
            $table->foreign('patient_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical__histories');
    }
};