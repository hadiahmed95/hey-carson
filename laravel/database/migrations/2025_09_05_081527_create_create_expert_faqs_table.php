<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('expert_faqs', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('expert_id')->index();

            $table->string('question', 255);
            $table->text('answer');

            $table->timestamps();
            $table->softDeletes();

            $table->index('question');
        });
    }

    public function down(): void
    {
        if (config('app.env') == 'local') {
            Schema::dropIfExists('expert_faqs');
        }
    }
};
