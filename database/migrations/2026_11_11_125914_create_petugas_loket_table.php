<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('petugas_loket', function (Blueprint $table) {
            $table->bigIncrements('id_petugas_loket');
            $table->unsignedBigInteger('id_petugas');
            $table->unsignedBigInteger('id_loket');
            $table->timestamps();

            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('cascade');
            $table->foreign('id_loket')->references('id_loket')->on('loket')->onDelete('cascade');
            $table->unique(['id_loket']); // Setiap loket hanya mempunyai satu petugas penanggung jawab aktif
        });
    }

    public function down(): void {
        Schema::dropIfExists('petugas_loket');
    }
};