<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('riwayat_pemanggilan', function (Blueprint $table) {
            $table->bigIncrements('id_riwayat');
            $table->unsignedBigInteger('id_tiket');
            $table->unsignedBigInteger('id_petugas');
            $table->unsignedBigInteger('id_loket');
            $table->timestamp('waktu_panggil')->useCurrent();
            $table->string('jenis_aksi', 50);
            $table->timestamps();

            $table->foreign('id_tiket')->references('id_tiket')->on('tiket')->onDelete('cascade');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('cascade');
            $table->foreign('id_loket')->references('id_loket')->on('loket')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('riwayat_pemanggilan');
    }
};