<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function creator(){
        return $this->hasMany(Prospect::class,"user_creator");
    }

    public function personInCharge(){
        return $this->hasMany(Prospect::class,"user_creator");
    }

    public function draftinput(){
        return $this->hasOne(tmpProspectInput::class);
    }

    public function updateapprove(){
        return $this->hasMany(updatelog::class,"approved_by");
    }



    protected $fillable = [
        'name', 'email', 'password','role'
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
