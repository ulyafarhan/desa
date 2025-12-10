<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_histories', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->change(); // Buat user_id jadi nullable
        });
    }

    public function down(): void
    {
        Schema::table('chat_histories', function (Blueprint $table) {
            $table->dropColumn('session_id');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};