<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return response()->view('home');
    }
}
