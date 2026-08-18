<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Transaction;

class Contact extends Model
{
    protected $fillable = [
        'user_id',
        'email', 
        'phone', 
        'type'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }
}
