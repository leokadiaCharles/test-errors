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
        // Create the trigger for logging updates
        DB::unprepared('

            CREATE TRIGGER log_task_updates
            BEFORE UPDATE ON tasks
            FOR EACH ROW
            BEGIN
                INSERT INTO logs (task_id, user_id, task_title, description, action, action_date, created_at, updated_at)
                VALUES (OLD.id, OLD.user_id, OLD.title, OLD.description, "update", NOW(), NOW(), NOW());
            END;

            
        ');

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS log_task_updates');
    }
};
