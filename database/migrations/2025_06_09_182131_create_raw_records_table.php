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
        Schema::create('raw_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('file_upload_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('client_name')->nullable();
            $table->string('provider_name')->nullable();
            $table->date('date_of_service')->nullable();
            $table->string('program_name')->nullable();
            $table->string('target_text')->nullable();
            $table->string('raw_data')->nullable();
            $table->string('symbolic_data')->nullable();
            $table->string('file_type')->nullable();
            $table->string('goal_name')->nullable();
            $table->string('domain')->nullable();
            $table->string('mastery_threshold')->nullable();
            $table->unsignedInteger('session_number')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('accuracy')->nullable();
            $table->string('cpt_code')->nullable();
            $table->boolean('billable')->default(true);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_records');
    }
};
