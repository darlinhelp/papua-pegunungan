<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // Spanduk lebar terpotong parah di layar ponsel. Kolom ini menampung
            // versi potret yang dipakai di bawah 640px.
            $table->string('image_mobile_path')->nullable()->after('image_path');
            // Teks alternatif untuk pembaca layar; kalau kosong dipakai judulnya.
            $table->string('alt_text')->nullable()->after('image_mobile_path');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['image_mobile_path', 'alt_text']);
        });
    }
};
