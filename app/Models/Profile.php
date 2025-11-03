<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perfiles';
    protected $primaryKey = 'id';
    protected $fillable = ['descrpcion'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
