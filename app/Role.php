<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    public $incrementing = false;
    use SoftDeletes;
    public $keyType = 'uuid';
}
