<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UbigeoDistrict extends Model
{
    use HasFactory;

    protected $table        = 'ubigeo_districto';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    protected $t_up, $t_ur = '';
    public function __construct() {
        parent::__construct();
		$this->t_up 	= 'ubigeo_provincia';
		$this->t_ur 	= 'ubigeo_region';
    }

    public function getLocation($value) {
        return UbigeoDistrict::selectRaw('ubigeo_distrito.id as id, CONCAT(ubigeo_distrito.id, " | ", ur.region, " | ", up.provincia, " | ", ubigeo_distrito.distrito) as ubigeo')
            ->join($this->t_up.' as up', 'ubigeo_distrito.provincia_id', '=', 'up.id')
            ->join($this->t_ur.' as ur', 'ubigeo_distrito.region_id', '=', 'ur.id')
            ->where('ur.region', 'LIKE', "%$value%")
            ->orWhere('ubigeo_distrito.id', 'LIKE', "%$value%")
            ->orWhere('up.provincia', 'LIKE', "%$value%")
            ->orWhere('ud.distrito', 'LIKE', "%$value%")
            ->get()
            ->toArray();
    }

    
}
