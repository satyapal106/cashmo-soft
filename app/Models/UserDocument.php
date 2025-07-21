<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    
    protected $table = "user_documents";

    protected $fillable = [
        'user_id',
        'document_id',
        'file',
        'status'
    ];


     public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
