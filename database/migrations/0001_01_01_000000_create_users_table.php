<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique(); // KUNCI UTAMA LOGIN
            $table->string('name'); // Nama Lengkap
            $table->string('email')->nullable(); // Opsional untuk warga desa
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // ROLE & STATUS
            $table->string('role', 10)->default('warga'); // admin, staff, warga
            $table->string('status_akun', 10)->default('pending'); // pending, verified
            
            // BIODATA & BIOMETRIK
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->text('data_biometrik')->nullable(); 
            
            // FILE PATHS (PRIVATE)
            $table->string('file_ktp_path')->nullable();
            $table->string('file_kk_path')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            
            // INDEXING UNTUK PERFORMA
            $table->index(['nik', 'role']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
