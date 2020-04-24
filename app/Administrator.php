<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrator extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'admin';
    protected $guard = 'administrator';
    protected $guarded = [];

    public $incrementing = false;
    public $keyType = 'uuid';

    public function roles()
    {
        return $this->belongsTo('App\Role', 'id_role', 'id');
    }
}
