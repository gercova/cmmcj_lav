<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModulesController extends Controller
{
    public function __construct() {
        
    }

    public function index(): View {
        return view('security.modules.index');
    }

    public function list(): JsonResponse {
        $resultsM   = Module::with('submodules')->get();
        $data       = $resultsM->map(function($item, $key) {
            $submodulesList = '<table class="table table-sm">';
            foreach ($item->submodules as $sm) {
                $submodulesList .= sprintf(
                    '<tr>
                        <td><span class="badge badge-success">%s</span>&nbsp;%s</td>
                        <td>
                            <button class="btn btn-sm btn-warning update-row-submodule btn-sm" value="%s"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-danger delete-submodule btn-sm" value="%s"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>',
                    $sm->icono,
                    $sm->descripcion,
                    $sm->id,
                    $sm->id,
                );
            }
            
            $submodulesList .= '</table>';
            return [
                $key + 1,
                $item->descripcion,
                sprintf('<button type="button" class="btn btn-sm btn-info btn-md">%s</button>', $item->icono),
                $submodulesList,
                $item->created_at->format('Y-m-d H:i:s'),
                sprintf(
                    '<button type="button" class="btn btn-sm btn-warning update-row-module btn-md" value="%s">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete-module btn-md" value="%s">
                        <i class="bi bi-trash"></i>
                    </button>',
                    $item->id,
                    $item->id,
                    $item->id
                )
            ];
        });

        return response()->json([
            "sEcho"					    => 1,
            "iTotalRecords"			    => $data->count(),
            "iTotalDisplayRecords"	    => $data->count(),
            "aaData"				    => $data,
        ]);
    }

    protected function buildActionDropdown($id, $canEdit, $canDelete): string {
        if (!$canEdit && !$canDelete) return '<span class="text-muted">Sin acciones</span>';
        
        $buttons = [];
        if ($canEdit) {
            $buttons[] = '<li><a class="dropdown-item update-row" type="button" value="'.e($id).'"><i class="bi bi-pencil-square"></i> Editar</a></li>';
        }
        if ($canDelete) {
            $buttons[] = '<li><a class="dropdown-item delete-drug" type="button" value="'.e($id).'"><i class="bi bi-trash"></i> Eliminar</a></li>';
        }
        
        return '<div class="btn-group">
            <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">Acciones</button>
            <ul class="dropdown-menu">'.implode('', $buttons).'</ul>
        </div>';
    }

    public function store(): JsonResponse {

    }

    public function show(): JsonResponse {

    }

    public function delete(): JsonResponse {

    }
}
