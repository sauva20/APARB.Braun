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
        Schema::create('jadwal_inspeksis', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_jadwal');
            $table->date('tanggal_inspeksi');
            $table->string('tipe_area'); // 'gedung' atau 'lokasi'
            $table->foreignId('gedung_id')->nullable()->constrained('gedung')->onDelete('cascade');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi')->onDelete('cascade');
            $table->string('petugas')->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->string('status')->default('menunggu'); // 'menunggu', 'selesai'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_inspeksis');
    }
};
