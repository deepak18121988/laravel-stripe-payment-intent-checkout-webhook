<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the homepage view
     */
    public function index()
    {
        try {
            return view('home.index'); // points to resources/views/home.blade.php
        } catch (\Exception $e) {
            \Log::error('Error loading homepage: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }
}
