<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //

    protected $fillable = ['name', 'status'];


    public function userDocument()
    {
        return $this->hasMany(UserDocument::class);
    }
}
