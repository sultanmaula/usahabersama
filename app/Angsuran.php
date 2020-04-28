<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Angsuran extends Model
{
    use SoftDeletes;

    protected $table     = 'angsurans';
    protected $guarded   = [];
    public $incrementing = false;
    public $keyType      = 'uuid';

    public function transaksi()
    {
        return $this->belongsTo('App\Transaksi', 'id_transaksi', 'id');
    }
}
