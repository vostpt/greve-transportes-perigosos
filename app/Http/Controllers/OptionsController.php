<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function list()
    {
        return response()->view('options/list');
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name'  => 'required|exists:options',
            'value' => 'required',
        ]);
        try {
            $option = Option::findOrFail($validatedData['name']);
            unset($validatedData['name']);
            $option->timestamps = false;
            $option->update($validatedData);
            return redirect('panel/options/list')->with('status', 'Opção Atualizada!');
        } catch (Exception $e) {
            return redirect('panel/options/list')->with('status', 'Erro ao atualizar opção!');
        }
    }

    public function fetch_all()
    {
        $options = Option::all();
        return response()->json(['data' => $options]);
    }
}
