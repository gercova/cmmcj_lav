<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentsStatus extends Model
{
    use HasFactory;

    protected $table        = 'estados_cita';
    protected $primaryKey   = 'id';
    protected $fillable     = ['descripcion'];
    public $timestamps      = false;

    public function cita(): HasMany {
        return $this->hasMany(Appointment::class, 'estado_cita_id');
    }
}
