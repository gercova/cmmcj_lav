<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Resources\DrugResource;
use App\Models\Drug;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrugsController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
    }

    public function index(): View {
        return view('maintenance.drugs.index');
    }

    public function store(): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => 'Medicamento guardado correctamente',
        ]);
    }

    public function show(Drug $drug) {
        return DrugResource::make($drug);
    }

    public function destroy(Drug $drug): JsonResponse {
        $drug->delete();
        return response()->json([
            'success' => true,
            'message' => 'Medicamento eliminado correctamente',
        ]);
    }
}
