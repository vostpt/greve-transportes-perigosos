<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class GraphsController extends Controller
{
    public function stats(): Response
    {
        return response()->view('graphs.stats');
    }

    public function brands(): Response
    {
        return response()->view('graphs.brands');
    }

    public function entries(): Response
    {
        return response()->view('graphs.entries');
    }
}
