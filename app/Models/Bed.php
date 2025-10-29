<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bed extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'beds';
    protected $primaryKey   = 'id';
    protected $fillable     = ['description', 'floor', 'detail'];
    protected $dates        = ['created_at', 'updated_at', 'deleted_at'];
}
