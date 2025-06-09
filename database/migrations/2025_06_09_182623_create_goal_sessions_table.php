<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('goal_sessions', function (Illuminate\Database\Schema\Blueprint $table): void {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('goal_id')->constrained()->onDelete('cascade');
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->date('date_of_service')->nullable();
            $table->string('program_name')->nullable();
            $table->string('target_text');
            $table->string('raw_data')->nullable();
            $table->string('symbolic_data')->nullable();
            $table->unsignedTinyInteger('accuracy')->nullable();
            $table->string('cpt_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_sessions');
    }
};
