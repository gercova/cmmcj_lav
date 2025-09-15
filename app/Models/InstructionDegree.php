<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructionDegree extends Model
{
    use HasFactory;

    protected $table = 'grados_instruccion';
    protected $primaryKey = 'id';
}
