<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Satu baris per hari, berisi jumlah kunjungan hari itu.
 * Diisi oleh App\Http\Middleware\CatatKunjungan, dibaca oleh
 * widget dasbor RingkasanKunjungan.
 */
class DailyVisit extends Model
{
    protected $table = 'daily_visits';

    protected $primaryKey = 'tanggal';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['tanggal', 'jumlah'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }
}
