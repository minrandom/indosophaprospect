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
    public function scheduleCreators(){
        return $this->hasMany(schedule::class,"created_by");
    }

    public function scheduleFor(){
        return $this->hasMany(schedule::class,"create_for");
    }
    public function prospectRemarks(){
        return $this->hasMany(prospectRemarks::class,"creator");
    }

    public function scheduleValidators(){
        return $this->hasMany(schedule::class,"validation_by");
    }

    public function personInCharge(){
        return $this->hasMany(Prospect::class,"pic_user_id");
    }

    public function draftinput(){
        return $this->hasOne(tmpProspectInput::class);
    }
    public function alerts(){
        return $this->hasMany(Alert::class);
    }

    public function updateapprove(){
        return $this->hasMany(updatelog::class,"approved_by");
    }
    public function attendance(){
        return $this->hasMany(Attendance::class,"user_id");
    }



    protected $fillable = [
        'name', 'email', 'password','role','employee_id'
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
