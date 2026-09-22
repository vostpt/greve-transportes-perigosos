<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function add(): Response
    {
        return response()->view('users.add');
    }

    public function password(): Response
    {
        return response()->view('users.password');
    }

    public function list(): Response
    {
        return response()->view('users.list');
    }

    public function create(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:254|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect('panel/users/list')->with('status', 'Utilizador criado.');
    }

    public function fetchVerified(): JsonResponse
    {
        return response()->json([
            'data' => User::query()
                ->whereNotNull('email_verified_at')
                ->select(['id', 'name', 'email'])
                ->get(),
        ]);
    }

    public function fetchNotVerified(): JsonResponse
    {
        return response()->json([
            'data' => User::query()
                ->whereNull('email_verified_at')
                ->select(['id', 'name', 'email'])
                ->get(),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate(['id' => 'required|exists:users,id']);
        User::findOrFail($data['id'])->markEmailAsVerified();

        return redirect('panel/users/list')->with('status', 'Email validado.');
    }

    public function delete(Request $request): RedirectResponse
    {
        $data = $request->validate(['id' => 'required|exists:users,id']);
        User::findOrFail($data['id'])->delete();

        return redirect('panel/users/list')->with('status', 'Utilizador eliminado.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('status', 'Password atualizada.');
    }
}
