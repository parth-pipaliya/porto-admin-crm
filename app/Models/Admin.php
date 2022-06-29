<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasPermissionsTrait, SoftDeletes;

    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'password', 'timezone', 'otp', 'timezone', 'is_superadmin', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarAttribute(){
       return asset('admin_assets/images/avatar.jpg');
    }

    public function getRoles(){
        return $this->hasMany('App\Models\AdminRole','admin_id','id');
    }
   
    public function roles() {
        return $this->belongsToMany('App\Models\Role','admin_roles');     
    }

}
