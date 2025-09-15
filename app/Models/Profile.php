<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table    = 'perfiles';
    protected $fillable = ['descripcion'];
    protected $primaryKey = 'id';

    public function user() {
        return $this->belongsTo(User::class, 'perfil_id');
    }
}
