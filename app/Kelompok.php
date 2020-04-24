<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelompok extends Model
{
    Use SoftDeletes;
    // protected $table 	= 'role_menus';
    protected $guarded = [''];
    protected $hidden   = ['created_at','updated_at'];
    protected $date = ['deleted_at'];
    public $incrementing = false;
}
