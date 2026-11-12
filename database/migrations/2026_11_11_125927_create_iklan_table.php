<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('iklan', function (Blueprint $table) {
            $table->bigIncrements('id_iklan');
            $table->string('judul', 150);
            $table->text('deskripsi')->nullable();
            $table->string('file_media');
            $table->enum('tipe_media', ['video', 'gambar']);
            $table->integer('durasi_tampil')->default(10);
            $table->integer('urutan')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('iklan');
    }
};