<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HistoryValidate;
use App\Models\History;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoriesController extends Controller
{
    public function __construct()
    {
        
    }

    public function new(){

        return view('EMR.histories.new', compact());
    }

    public function edit(int $id): View {
        $hc = History::findOrFail($id);
        return view('EMR.histories.edit', compact('hc'));
    }

    public function store(HistoryValidate $request): JsonResponse {
        $validated = $request->validated();

        $proccessFields = [
            'tipo_documento_id' => $validated['tipo_documento_id'],
            'dni'               => $validated['dni'],
            'nombres'           => strtoupper($validated['nombres']),
            'apellidos'         => strtoupper($validated['apellidos']),
            'sexo'              => strtoupper($validated['sexo']),
            'fecha_nacimiento'  => $validated['fecha_nacimiento'],
            'telefono'          => $validated['telefono'],
            'email'             => $validated['email'],
            'direccion'         => strtoupper($validated['direccion']),
        ];

        $data = array_merge($proccessFields);

        DB::beginTransaction();
        try {
            $result = History::updateOrCreate(['id' => $request->input('id')], $data);

            return response()->json([
                'status'    => (bool) $result,
                'type'      => $result ? 'success' : 'error',
                'message'   => $result->wasRecentlyCreated ? 'Historia clínica guardada correctamente' : 'Historia clínica actualizada correctamente',
                'route'     => route('emr.histories.edit', $result->id),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


    }
}
