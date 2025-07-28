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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('expert_id');
            $table->unsignedBigInteger('assignment_id');

            $table->string('type'); // Custom, Prepaid
            $table->integer('hours')->default(10);
            $table->float('rate')->default(100.00);
            $table->string('status')->default('send'); // send, accepted, declined

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
