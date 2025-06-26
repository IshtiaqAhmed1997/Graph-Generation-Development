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
        Schema::create('goal_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_upload_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('client_name')->nullable();
            $table->string('target_text');
            $table->date('first_date')->nullable();
            $table->date('last_date')->nullable();
            $table->unsignedInteger('total_trials')->default(0);
            $table->unsignedInteger('total_correct')->default(0);
            $table->unsignedTinyInteger('average_accuracy')->nullable();
            $table->boolean('mastered')->default(false);
            $table->date('mastered_on')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_results');
    }
};
