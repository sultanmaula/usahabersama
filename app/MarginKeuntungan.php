<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarginKeuntungan extends Model
{
    //
    Use SoftDeletes;
    // protected $table 	= 'margin_keuntungans';
    protected $guarded = [''];
    protected $hidden   = ['created_at','updated_at'];
    protected $date = ['deleted_at'];
    public $incrementing = false;
}
