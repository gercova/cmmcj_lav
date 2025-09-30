<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:examen_acceder')->only('index');
		$this->middleware('permission:examen_ver')->only('see', 'view', 'listExams', 'listDiagnostics', 'listMedications');
		$this->middleware('permission:examen_crear')->only('new');
		$this->middleware('permission:examen_editar')->only('edit');
        $this->middleware('permission:examen_guardar')->only('store');
		$this->middleware('permission:examen_borrar')->only('destroy', 'destroyDiagnostics', 'destroyMedications', 'destroyDocument');
    }

    public function index() {
        return view('emr.reports.index');
    }
}
