<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\History;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamsController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
    }

    public function index(): View {
        return view('emr.exams.index');
    }

    public function new(History $history): View {
        $te = ExamType::all();
        return view('emr.exams.new', compact('history', 'te'));
    }

    public function edit(Exam $exam): View {
        $te = ExamType::all();
        return view('emr.exams.edit', compact('exam', 'te'));
    }

    public function see(Exam $exam): View {
        return view('emr.exams.see', compact('exam'));
    }

    public function store (): JsonResponse {
        return response()->json([],200);
    }

    public function destroy (Exam $exam): JsonResponse {
        $exam->delete();
        return response()->json([
            'status' => (bool) $exam,
            'type'      => $exam ? 'success' : 'error',
            'message' => $exam ? 'Examen eliminado' : 'Error al eliminar el examen'
        ],200);
    }


    
}
