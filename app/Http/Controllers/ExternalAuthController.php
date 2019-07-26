<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\ExternalAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExternalAuthController extends Controller
{
    public function add()
    {
        return response()->view('externalauth/add');
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'key'   => 'required',
            'brand' => 'required',
        ]);
        try {
            $ext_auth         = new ExternalAuth();
            $ext_auth->key    = $validatedData['key'];
            $ext_auth->brand  = $validatedData['brand'];
            $ext_auth->secret = Str::random(64);
            $ext_auth->save();
            return redirect('externalauth/list')->with('status', 'External Auth Created!');
        } catch (Exception $e) {
            return redirect('externalauth/list')->with('status', 'Error Creating External Auth!');
        }
    }

    public function list()
    {
        return response()->view('externalauth/list');
    }

    public function fetch_all()
    {
        $ext_auths = ExternalAuth::all();
        return response()->json(['data' => $ext_auths]);
    }

    public function delete(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:external_auth',
        ]);
        try {
            $ext_auth = ExternalAuth::findOrFail($validatedData['id']);
            $ext_auth->delete();
            return redirect('externalauth/list')->with('status', 'Autênticação externa eliminada!');
        } catch (Exception $e) {
            return redirect('externalauth/list')->with('status', 'Erro ao eliminar autenticação externa!');
        }
    }
}
