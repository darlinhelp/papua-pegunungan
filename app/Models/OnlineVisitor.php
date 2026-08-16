<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineVisitor extends Model
{
    protected $table = 'online_visitors';

    protected $primaryKey = 'visitor_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['visitor_id', 'halaman', 'terakhir'];

    protected function casts(): array
    {
        return ['terakhir' => 'datetime'];
    }
}
