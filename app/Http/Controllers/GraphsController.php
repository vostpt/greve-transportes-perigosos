<?php
declare(strict_types=1);

namespace App\Http\Controllers;

class GraphsController extends Controller
{
    public function stats()
    {
        return response()->view('graphs/stats');
    }

    public function brands()
    {
        return response()->view('graphs/brands');
    }

    public function entries()
    {
        return response()->view('graphs/entries');
    }
}
