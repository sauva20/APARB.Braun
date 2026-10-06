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
        Schema::create('apar_cadangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gedung_id')->constrained('gedung')->onDelete('cascade');
            $table->foreignId('jenis_id')->constrained('jenis_apar')->onDelete('cascade');
            $table->foreignId('kapasitas_id')->constrained('kapasitas_apar')->onDelete('cascade');
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apar_cadangans');
    }
};
