<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theme;

class ThemeController extends Controller {
    public function index() {
        $themes = Theme::all();
            
        return response()->json($themes);
    }
}