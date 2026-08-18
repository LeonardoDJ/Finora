<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionNotification extends Model
{
    protected $fillable = [
        'transaction_id',
        'type',
        'sent_at',
    ];

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
