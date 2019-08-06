<?php
declare(strict_types=1);

namespace App\Http\Controllers;

class GraphsController extends Controller
{
    public function stats()
    {
        return response()->view('graphs/stats');
    }
}
