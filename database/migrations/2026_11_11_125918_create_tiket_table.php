<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tiket', function (Blueprint $table) {
            $table->bigIncrements('id_tiket');
            $table->unsignedBigInteger('id_loket');
            $table->unsignedBigInteger('id_pengguna')->nullable();
            $table->string('nomor_urut', 10);
            $table->string('kode_booking', 20)->unique()->nullable();
            $table->enum('tipe_antrean', ['offline', 'online']);
            $table->date('tanggal_tiket');
            $table->time('jam_prakiraan')->nullable();
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'batal'])->default('menunggu');
            $table->timestamps();

            $table->foreign('id_loket')->references('id_loket')->on('loket')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('set null');
            
            // Unique constraint agar nomor urut tidak ganda pada loket & tanggal yang sama
            $table->unique(['id_loket', 'tanggal_tiket', 'nomor_urut']);
            $table->index(['tanggal_tiket', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('tiket');
    }
};