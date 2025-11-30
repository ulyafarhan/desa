<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pejabat_desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pejabat');
            $table->string('nip')->nullable();
            $table->string('jabatan');
            $table->string('path_gambar_ttd')->nullable();
            $table->string('path_gambar_stempel')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('surat_templates', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat')->unique();
            $table->string('judul_surat');
            $table->text('deskripsi')->nullable();
            $table->json('form_schema')->nullable(); 
            $table->string('view_template');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('surat_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_id')->constrained('surat_templates')->cascadeOnDelete();
            $table->foreignId('pejabat_id')->nullable()->constrained('pejabat_desa');
            
            $table->string('nomor_surat')->nullable();
            $table->json('data_input');
            
            // STATUS FLOW: pending -> in_queue -> processing -> completed
            $table->string('status', 20)->default('pending'); 
            
            $table->string('file_hasil_path')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            // INDEXING WAJIB (NFR: High Concurrency)
            $table->index(['status', 'created_at']); 
            $table->index('user_id'); 
        });

        Schema::create('chat_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['user', 'model']);
            $table->text('message');
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_histories');
        Schema::dropIfExists('surat_requests');
        Schema::dropIfExists('surat_templates');
        Schema::dropIfExists('pejabat_desa');
    }
};