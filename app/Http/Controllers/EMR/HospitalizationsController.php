<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Hospitalization;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HospitalizationsController extends Controller {
    

    public function __construct(){
        $this->middleware(['auth', 'prevent.back']);
    }

    public function index(): View {
        return view('EMR.hospitalizations.index');
    }

    public function new(History $history): View {
        return view('EMR.hospitalizations.new', compact('history'));
    }

    public  function edit(Hospitalization $hospitalization): View {
        $history = History::find($hospitalization->history_id);
        return view('EMR.hospitalizations.edit', compact('hospitalization', 'history'));

    }

    public function list(): JsonResponse {

    }

    public function store(): JsonResponse {

    }

    public function show(): JsonResponse {

    }

    public function destroy(): JsonResponse {

    }
}
