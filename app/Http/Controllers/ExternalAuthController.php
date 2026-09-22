<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ExternalAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ExternalAuthController extends Controller
{
    public function add(): Response
    {
        return response()->view('externalauth.add');
    }

    public function list(): Response
    {
        return response()->view('externalauth.list');
    }

    public function create(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key' => 'required|string|max:255|unique:external_auth,key',
            'brand' => 'required|string|max:255',
        ]);

        ExternalAuth::create([
            'key' => $data['key'],
            'brand' => $data['brand'],
            'secret' => Str::random(64),
        ]);

        return redirect('panel/externalauth/list')->with('status', 'Credencial criada.');
    }

    public function fetchAll(): JsonResponse
    {
        return response()->json(['data' => ExternalAuth::query()->withTrashed()->get()]);
    }

    public function delete(Request $request): RedirectResponse
    {
        $data = $request->validate(['id' => 'required|exists:external_auth,id']);
        ExternalAuth::findOrFail($data['id'])->delete();

        return redirect('panel/externalauth/list')->with('status', 'Credencial removida.');
    }
}
