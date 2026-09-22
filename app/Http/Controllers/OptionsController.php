<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OptionsController extends Controller
{
    public function list(): Response
    {
        return response()->view('options.list');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|exists:options,name',
            'value' => 'required|string',
        ]);

        Option::set($data['name'], $data['value']);

        return redirect('panel/options/list')->with('status', 'Opção atualizada!');
    }

    public function fetchAll(): JsonResponse
    {
        return response()->json(['data' => Option::all()]);
    }
}
