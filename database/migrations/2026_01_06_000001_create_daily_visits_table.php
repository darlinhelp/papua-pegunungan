<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_visits', function (Blueprint $table) {
            // Satu baris per hari, bukan satu baris per kunjungan — jadi
            // tabelnya tetap kecil meski situs diakses ribuan kali sehari.
            $table->date('tanggal')->primary();
            $table->unsignedBigInteger('jumlah')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_visits');
    }
};
