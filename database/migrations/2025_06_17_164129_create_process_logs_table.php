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
        Schema::create('process_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_id')->nullable();
            $table->string('target_text')->nullable();
            $table->unsignedInteger('records_processed')->default(0);
            $table->decimal('final_accuracy', 5, 2)->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_logs');
    }
};
