<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::salonServices();

        return view('home.index', compact('services'));
    }
}
