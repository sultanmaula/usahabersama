<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;

    protected $table = 'transaksis';
    protected $guarded = [];
    public $incrementing = false;
    public $keyType = 'uuid';

    public function nasabah()
    {
        return $this->belongsTo('App\Nasabah', 'id_nasabah', 'id');
    }
}
