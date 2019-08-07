<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function add()
    {
        return response()->view('users/add');
    }

    public function password()
    {
        return response()->view('users/password');
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users|max:254',
            'password' => 'required|min:6',
        ]);
        try {
            $user           = new User();
            $user->name     = $validatedData['name'];
            $user->password = Hash::make($validatedData['password']);
            $user->email    = $validatedData['email'];
            $user->save();
            return redirect('panel/users/list')->with('status', 'User Created!');
        } catch (Exception $e) {
            return redirect('panel/users/list')->with('status', 'Error Creating User!');
        }
    }

    public function list()
    {
        return response()->view('users/list');
    }

    public function fetch_verified()
    {
        $users_final = [];
        $users       = User::where('email_verified_at', '<>', 'NULL')->get();
        foreach ($users as $user) {
            $users_final[] = [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ];
        }
        return response()->json(['data' => $users_final]);
    }

    public function fetch_not_verified()
    {
        $users_final = [];
        $users       = User::whereNull('email_verified_at')->get();
        foreach ($users as $user) {
            $users_final[] = [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ];
        }
        return response()->json(['data' => $users_final]);
    }

    public function verify(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:users',
        ]);
        try {
            $user = User::findOrFail($validatedData['id']);
            $user->markEmailAsVerified();
            return redirect('panel/users/list')->with('status', 'Email de Utilizador validado!');
        } catch (Exception $e) {
            return redirect('panel/users/list')->with('status', 'Erro ao validar Email de Utilizador!');
        }
    }

    public function delete(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:users',
        ]);
        try {
            $user = User::findOrFail($validatedData['id']);
            $user->delete();
            return redirect('users/list')->with('status', 'Utilizador eliminado!');
        } catch (Exception $e) {
            return redirect('users/list')->with('status', 'Erro ao eliminar Utilizador!');
        }
    }

    public function updatePassword(Request $request)
    {
        $validatedData = $request->validate([
            'id'                    => 'required|exists:users',
            'current-password'      => 'required',
            'password'              => 'required|same:password|min:6',
            'password_confirmation' => 'required|same:password|min:6',
        ]);
        try {
            echo('here');
            if (Auth::Check()) {
                $current_password = Auth::User()->password;
                if (Hash::check($validatedData['current-password'], $current_password)) {
                    $user           = User::findOrFail($validatedData['id']);
                    $user->password = Hash::make($validatedData['password']);
                    $user->save();
                    return back()->with('status', 'Password Atualizada!');
                } else {
                    return back()->with('status', 'Password Atual Errada!');
                }
            } else {
                return back()->with('status', 'Erro ao atualizar password!');
            }
            return back()->with('status', 'Password Atualizada!');
        } catch (Exception $e) {
            return back()->with('status', 'Erro ao atualizar password!');
        }
    }
}
