<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_files', function (Blueprint $table) {
            $table->unsignedBigInteger('message_id')->nullable()->after('project_id');
        });

        $messages = \App\Models\Message::query()->whereNotIn('type', ['text', 'banner', 'offer']);

        $messages->each(function ($message) {
            $content = explode('/', $message->content);

            DB::table('project_files')->insert([
                'project_id' => $message->project_id,
                'message_id' => $message->id,
                'name' => end($content),
                'url' => $message->content,
                'created_at' => $message->created_at,
                'updated_at' => $message->updated_at,
            ]);

            $message->update(['content' => end($content)]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('project_files', function (Blueprint $table) {
            $table->dropColumn('message_id');
        });
    }
};
