<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan_perangkat', function (Blueprint $table) {
            // Satu baris per hari per jenis perangkat, bukan per kunjungan —
            // sama seperti daily_visits, supaya tabelnya tetap kecil.
            $table->date('tanggal');
            $table->string('perangkat', 20); // desktop | mobile | tablet
            $table->unsignedBigInteger('jumlah')->default(0);

            $table->primary(['tanggal', 'perangkat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan_perangkat');
    }
};
