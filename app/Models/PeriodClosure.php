<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodClosure extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'status',
        'file_path',
        'sent_at',
        'error_message',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
