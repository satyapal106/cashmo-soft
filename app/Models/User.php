<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'profile_image',
        'email',
        'phone_number',
        'aadhar_number',
        'pan_number',
        'address',
        'pincode',
        'locality',
        'state_id',
        'district_id',
        'tehsil',
        'member_type',
        'package_id',
        'shop_name',
        'aadhar_front_image',
        'aadhar_back_image',
        'pan_card_image',
        'email_verified_at',
        'password',
        'status',
        'remember_token'
    ];

    
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    // Retailer belongs to a district
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'retailer_service');
    }


    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
