<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Contact;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'contact_id',
        'type',
        'amount',
        'due_date',
        'status',
        'settled_at',
    ];


    public function user(){
        
        return $this->belongsTo(User::class);

    }

    public function contact(){

        return $this->belongsTo(Contact::class);

    }
}
