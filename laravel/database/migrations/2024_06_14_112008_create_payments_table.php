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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('expert_id')->nullable();

            $table->string('stripe_payment_id')->nullable();

            $table->integer('amount');
            $table->float('price');
            $table->float('total');

            $table->string('status')->default('created');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
