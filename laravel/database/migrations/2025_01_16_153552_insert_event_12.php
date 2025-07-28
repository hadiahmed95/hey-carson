<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('events')->insert([
            'id' => 12,
            'title' => 'A request is available again!',
            'message' => 'A request is available again to claim.',
            'type' => 'expert-project-available-again',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: Delete the row inserted in the up method
        DB::table('events')->where('id', 12)->delete();
    }
};
