<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, SoftDeletes;

    public $status_value = array(
        '1'=>'Active', 
        '2'=>'Inactive', 
    );
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'crm_card',
        'mobile_verified_at',
        'first_name',
        'last_name',
        'email',
        'country_code',
        'mobile_no',
        'gender',
        'password',
        'profile_image',
        'otp_code',
        'device_type',
        'device_uuid',
        'device_token',
        'social_type',
        'facebook_id',
        'google_id',
        'apple_id',
        'latitude',
        'longitude',
        'wallet_balance',
        'status',
        'notification_status',
        'approved_date',
        'current_latitude',
        'current_longitude',
        'user_timezone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'approved_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['user_name', 'status_name', 'mobile_no_country_code'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }

    public function getProfileImageAttribute($value) {
        return !empty($value) ? url('site/image/profile/'.$value) : asset('/admin_assets/images/avatar.jpg');
    }
   
    public function getUserNameAttribute($value) {
        $last_name = !empty($this->last_name)? ' '.$this->last_name : '' ;
        return $this->first_name.$last_name;
    }
      
    public function getMobileNoCountryCodeAttribute($value) {
        return $this->country_code .' '.$this->mobile_no;
    }
    
    public function categoryParent() {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function categoryChild() {
        return $this->hasOne('App\Models\Category', 'id', 'subcategory_id');
    }
    
  
}
