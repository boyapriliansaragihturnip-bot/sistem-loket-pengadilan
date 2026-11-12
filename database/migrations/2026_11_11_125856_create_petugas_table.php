<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('petugas', function (Blueprint $table) {
            $table->bigIncrements('id_petugas');
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('nip', 18)->unique();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('petugas');
    }
};