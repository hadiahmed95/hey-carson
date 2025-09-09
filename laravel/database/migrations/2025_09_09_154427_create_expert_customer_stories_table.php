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
        Schema::create('expert_customer_stories', function (Blueprint $table) {
            $table->id();
            $table->uuid('expert_id')->index();
            $table->string('title', 255);
            $table->json('images')->nullable();
            $table->text('problem');
            $table->text('solution');
            $table->text('result');

            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('app.env') == 'local') {
            Schema::dropIfExists('expert_customer_stories');
        }
    }
};
