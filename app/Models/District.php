<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    
    protected $fillable = ['id', 'state_id','name', 'code', 'status'];


    public function state(){
        return $this->belongsTo(State::class);
    }

    public function retailers()
    {
        return $this->hasMany(User::class);
    }
}
