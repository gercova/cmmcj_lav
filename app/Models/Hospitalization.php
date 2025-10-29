<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospitalization extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'hospitalizacions';
    protected $primaryKey   = 'id';
    protected $fillable     = ['history_id', 'bed_id', 'fc', 't', 'so2', 'vital_functions', 'observations', 'others'];
    protected $dates        = ['created_at', 'updated_at', 'deleted_at'];
}
