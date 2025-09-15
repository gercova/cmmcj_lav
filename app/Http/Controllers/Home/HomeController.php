<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
    }
    
    public function index() {
        $hc = History::whereNull('deleted_at')->count();
        $ex = Exam::whereNull('deleted_at')->count();
        $us = User::whereNull('deleted_at')->count();
        return view('home.index', compact('hc', 'ex', 'us'));
    }
}
