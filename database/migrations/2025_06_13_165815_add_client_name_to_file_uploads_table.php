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
        Schema::table('file_uploads', function (Blueprint $table) {
            $table->string('client_name')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('file_uploads', function (Blueprint $table) {
            $table->dropColumn('client_name');
        });
    }
};
