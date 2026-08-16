<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganPerangkat extends Model
{
    protected $table = 'kunjungan_perangkat';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['tanggal', 'perangkat', 'jumlah'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }
}
