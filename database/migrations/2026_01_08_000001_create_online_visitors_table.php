<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_visitors', function (Blueprint $table) {
            // ID sesi Laravel, bukan alamat IP — jadi tidak ada data pribadi
            // yang tersimpan. Baris ini ditimpa terus tiap pengunjung yang
            // sama memuat halaman baru, bukan bertambah baris.
            $table->string('visitor_id')->primary();
            $table->string('halaman')->nullable();
            $table->timestamp('terakhir');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_visitors');
    }
};
