<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('loket', function (Blueprint $table) {
            $table->bigIncrements('id_loket');
            $table->unsignedBigInteger('id_layanan');
            $table->string('nama_loket', 50);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->foreign('id_layanan')->references('id_layanan')->on('layanan')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('loket');
    }
};  