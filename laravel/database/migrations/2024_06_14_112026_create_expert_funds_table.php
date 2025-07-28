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
        Schema::create('expert_funds', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->float('amount');

            $table->integer('expert_level')->default(1);
            $table->float('admin_fee')->default(0.35);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_funds');
    }
};
