<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('IsActive', 1)->get();

        return view('home.index', compact('services'));
    }
}