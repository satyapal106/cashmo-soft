<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];
    
    public function district(){
        return $this->hasMany(District::class);
    }


    public function retailers()
    {
        return $this->hasMany(User::class);
    }
}
