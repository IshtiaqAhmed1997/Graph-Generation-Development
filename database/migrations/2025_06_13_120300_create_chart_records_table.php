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
        Schema::create('chart_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_upload_id')->constrained()->onDelete('cascade');
            $table->string('client_name')->nullable();
            $table->string('target_text')->nullable();
            $table->string( 'goal_name')->nullable();
            $table->string('chart_type')->nullable();
            $table->json('chart_config')->nullable();
            $table->string('chart_image_path')->nullable();
            $table->integer('version_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_records');
    }
};
