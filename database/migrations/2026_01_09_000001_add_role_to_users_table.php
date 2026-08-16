<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Bawaannya 'administrator', BUKAN 'penulis'. Ini disengaja:
            // akun yang sudah ada sekarang (termasuk Anda) sedang punya
            // akses penuh. Kalau kolom ini bawaannya 'penulis', migrasi
            // ini sendiri yang mengunci Anda keluar dari Pengaturan dan
            // manajemen pengguna begitu dijalankan.
            //
            // Pengguna BARU yang dibuat lewat panel tetap disarankan
            // sebagai Penulis di formulirnya (lihat UserResource) — hanya
            // nilai bawaan di tabel yang aman-secara-default, bukan
            // nilai bawaan di formulirnya.
            $table->string('role')->default('administrator')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
