<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $guarded  = [];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime'];

    public function getProfilePhotoUrlAttribute() {
        // URL externa
        if ($this->avatar && filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }
        // Imagen local existe
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::url($this->avatar);
        }
        // Imagen por defecto
        return asset('storage/photos/anonymous.png');
    }

    public function getFormattedNameAttribute() {
        if (empty($this->name)) return '';
        $parts = array_filter(explode(' ', $this->name));
        
        switch (count($parts)) {
            case 0:
                return '';
            case 1:
                return $this->name;
            case 2:
            case 3:
                return $this->name;
            default:
                $firstName      = $parts[0];
                $middleInitial  = strlen($parts[1]) > 0 ? substr($parts[1], 0, 1) . '.' : '';
                $lastName       = $parts[count($parts) - 2];
                return trim("{$firstName} {$middleInitial} {$lastName}");
        }
    }

    public function Hospitalizacion(): HasMany {
        return $this->hasMany(Hospitalization::class, 'user_id');
    }
}
