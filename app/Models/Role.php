<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_name',
    ];

    public function getPermissions(){
        return $this->hasMany('App\Models\RolePermission','role_id','id');
    }
   
    public function permissions() {
        return $this->belongsToMany('App\Models\Permission','role_permissions');     
    }
  
}
