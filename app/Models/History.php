<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class History extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'historias';
    protected $primaryKey   = 'id';
    protected $guarded      = [];

    protected $casts = [
        'tipo_documento_id'         => 'integer',
        'dni'                       => 'string:8',
        'nombres'                   => 'string',
        'sexo'                      => 'string',
        'fecha_nacimiento'          => 'date',
        'telefono'                  => 'string:11',
        'email'                     => 'string',
        'direccion'                 => 'string',
        'grupo_sanguineo_id'        => 'integer',
        'grado_instruccion_id'      => 'integer',
        'ubigeo_nacimiento'         => 'string',
        'ubigeo_residencia'         => 'string',
        'ocupacion_id'              => 'integer',
        'estado_civil_id'           => 'integer',
        'acompanante'               => 'string',
        'acompanante_telefono'      => 'string',
        'acompanante_direccion'     => 'string',
        'vinculo'                   => 'string',
        'seguro_id'                 => 'integer',
        'seguro_descripcion'        => 'string',
        'antecedentes_quirurgicos'  => 'string',
        'antecedentes_patologicos'  => 'string',
        'antecedentes_familiares'   => 'string',
        'antecedentes_medicos'      => 'string',
        'rams'                      => 'string',
        'is_active'                 => 'boolean',
        'created_at'                => 'datetime',
        'updated_at'                => 'datetime',
        'deleted_at'                => 'datetime'
    ];

    public static function getAllHistories($startIndex, $pageSize, $itemSearch) {
        // Construir la consulta base
        $query = DB::table('view_active_stories')
            ->where('dni', 'LIKE', "%{$itemSearch}%")
            ->orWhere('nombres', 'LIKE', "%{$itemSearch}%");
        // Contar solo los registros que coinciden con el filtro
        $count = $query->count();
        // Obtener los resultados paginados
        $results = $query->offset($startIndex)
            ->limit($pageSize)
            ->get();
        return [$results, $count];
    }

    public static function getOccupationByHistoryId($id){
		return History::selectRaw('(YEAR(CURRENT_DATE) - YEAR(historias.fecha_nacimiento)) - (RIGHT(CURRENT_DATE,5) < RIGHT(historias.fecha_nacimiento, 5)) AS age, CONCAT(o.id, " | ", o.descripcion) ocupacion')
			->join('ocupaciones as o', 'historias.ocupacion_id', '=', 'o.id')
			->where('historias.id', $id)
			->get()
			->toArray();
	}

    public static function getUBirthByHistoryId($id){
		return History::selectRaw('CONCAT(historias.ubigeo_nacimiento, " | ", ur.region, " | ", up.provincia, " | ", ud.distrito) as nacimiento')
			->join('ubigeo_distrito as ud', 'historias.ubigeo_nacimiento', '=', 'ud.id')
			->join('ubigeo_region as ur', 'ud.region_id', '=', 'ur.id')
			->join('ubigeo_provincia as up', 'ud.provincia_id', '=', 'up.id')
			->where('historias.id', $id)
			->get()
			->toArray();
	}

	public static function getUResidenceByHistoryId($id){
		return History::selectRaw('CONCAT(historias.ubigeo_residencia, " | ", ur.region, " | ", up.provincia, " | ", ud.distrito) as residencia')
			->join('ubigeo_distrito as ud', 'historias.ubigeo_residencia', '=', 'ud.id')
			->join('ubigeo_region as ur', 'ud.region_id', '=', 'ur.id')
			->join('ubigeo_provincia as up', 'ud.provincia_id', '=', 'up.id')
			->where('historias.id', $id)
			->get()
			->toArray();
	}

    public function typeDocument() {
        return $this->belongsTo(DocumentType::class, 'tipo_documento_id');
    }

    public function groupSanguineo() {
        return $this->belongsTo(BloodGroup::class, 'grupo_sanguineo_id');
    }

    public function degreeInstruccion() {
        return $this->belongsTo(DegreesInstruction::class, 'grado_instruccion_id');
    }

    public function occupation() {
        return $this->belongsTo(Occupation::class, 'ocupacion_id');
    }

    
}
