<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use  Database\Factories\HistoryFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class History extends Model {
    use HasFactory, SoftDeletes;

    protected $table        = 'historias';
    protected $primaryKey   = 'id';
    //protected $guarded      = [];
    protected $fillable     = [
        'tipo_documento_id',
        'dni',
        'nombres',
        'sexo',
        'fecha_nacimiento',
        'telefono',
        'email',
        'direccion',
        'grupo_sanguineo_id',
        'grado_instruccion_id',
        'ubigeo_nacimiento',
        'ubigeo_residencia',
        'ocupacion_id',
        'estado_civil_id',
        'acompanante',
        'acompanante_telefono',
        'acompanante_direccion',
        'vinculo',
        'seguro_id',
        'seguro_descripcion',
        'ant_quirurgicos',
        'ant_patologicos',
        'ant_familiares',
        'ant_medicos',
        'rams',
    ];

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
        'ant_quirurgicos'           => 'string',
        'ant_patologicos'           => 'string',
        'ant_familiares'            => 'string',
        'ant_medicos'               => 'string',
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
		return History::selectRaw('CONCAT(historias.ubigeo_nacimiento, " | ", ur.nombre, " | ", up.nombre, " | ", ud.nombre) as nacimiento')
			->join('ubigeo_distrito as ud', 'historias.ubigeo_nacimiento', '=', 'ud.id')
			->join('ubigeo_region as ur', 'ud.region_id', '=', 'ur.id')
			->join('ubigeo_provincia as up', 'ud.provincia_id', '=', 'up.id')
			->where('historias.id', $id)
			->get()
			->toArray();
	}

	public static function getUResidenceByHistoryId($id){
		return History::selectRaw('CONCAT(historias.ubigeo_residencia, " | ", ur.nombre, " | ", up.nombre, " | ", ud.nombre) as residencia')
			->join('ubigeo_distrito as ud', 'historias.ubigeo_residencia', '=', 'ud.id')
			->join('ubigeo_region as ur', 'ud.region_id', '=', 'ur.id')
			->join('ubigeo_provincia as up', 'ud.provincia_id', '=', 'up.id')
			->where('historias.id', $id)
			->get()
			->toArray();
	}

    public function typeDocument(): BelongsTo {
        return $this->belongsTo(DocumentType::class, 'tipo_documento_id');
    }

    public function groupSanguineo(): BelongsTo {
        return $this->belongsTo(BloodGroup::class, 'grupo_sanguineo_id');
    }

    public function degreeInstruccion(): BelongsTo {
        return $this->belongsTo(DegreesInstruction::class, 'grado_instruccion_id');
    }

    public function occupation(): BelongsTo {
        return $this->belongsTo(Occupation::class, 'ocupacion_id');
    }

    public function hospitalization(): HasMany {
        return $this->hasMany(Hospitalization::class);
    } 

    // Especificar el factory personalizado
    protected static function newFactory() {
        return HistoryFactory::new();
    }
}
