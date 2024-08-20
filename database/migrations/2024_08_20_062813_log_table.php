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
        Schema::create('logss', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id');
            $table->integer('user_id');
            $table->string('task_title'); 
            $table->text('description')->nullable();
            $table->enum('action', ['update', 'delete']);
            $table->dateTime('action_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logss');
    }
};
