<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'login_attempts';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'latitude',
        'longitude',
        'location'
    ];

    public function isSuspicious() {
        return $this->latitude && abs($this->latitude - $previous->latitude) > 5;
    }

    public function usuario() {
        return $this->belongsTo(User::class);
    }
}
