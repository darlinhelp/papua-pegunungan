<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();

            // Baris tanpa parent_id adalah kolom mega menu (mis. "Seputar DPW").
            // Baris dengan parent_id adalah tautan di dalam kolom itu.
            $table->foreignId('parent_id')->nullable()
                ->constrained('menu_items')->cascadeOnDelete();

            $table->string('label');

            // 'route:nama.rute' | '/halaman/...' | 'https://...' — kosong untuk
            // baris kolom (parent_id null) yang cuma jadi judul dropdown.
            $table->string('url')->nullable();

            $table->boolean('is_external')->default(false);

            // Hanya relevan untuk baris kolom: kolom itu tampil dua baris
            // (mis. "DPD Kabupaten" yang isinya delapan tautan).
            $table->boolean('wide')->default(false);

            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
