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
        Schema::create('chat_histories', function (Blueprint $table) {
            $table->id();
            // Ubah jadi nullable agar Tamu bisa masuk
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Tambahkan Session ID untuk melacak Tamu
            $table->string('session_id')->index();
            
            $table->enum('role', ['user', 'model']);
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_histories');
    }
};
