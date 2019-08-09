<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function new()
    {
        return response()->view('error/new');
    }

    public function edit()
    {
        return response()->view('error/edit');
    }

    public function push(Request $request)
    {
        $validatedData = $request->validate([
            'id'            => 'nullable|exists:fuel_stations,id',
            'brand'         => 'required|string',
            'long'          => 'required',
            'lat'           => 'required',
            'sell_gasoline' => 'required',
            'sell_diesel'   => 'required',
            'sell_lpg'      => 'required',
            'captcha'       => 'required|string',
            'vostie'        => 'string',
            'email'         => 'required|email',
        ]);
        try {
            $url  = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret'   => env('GOOGLE_RECAPTCHA_V3_SECRET'),
                'response' => $validatedData['captcha'],
            ];
            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => \http_build_query($data),
                ],
            ];
            $context    = \stream_context_create($options);
            $resultJSON = \file_get_contents($url, false, $context);
            $result     = \json_decode($resultJSON);
            if ($result->success != true) {
                return redirect('/')->with('status', 'Erro ao enviar informação!');
            }
            if (! $validatedData['id']) {
                $validatedData['id'] = 0;
            }
            if (! $validatedData['vostie']) {
                $validatedData['vostie'] = 'No';
            }
            $url = env('ERRORS_SPREADSHEET_LINK').'?id='.$validatedData['id'].'&lat='.$validatedData['lat'].'&long='.$validatedData['long'].'&brand='.\urlencode($validatedData['brand']).'&gasoline='.$validatedData['sell_gasoline'].'&diesel='.$validatedData['sell_diesel'].'&lpg='.$validatedData['sell_lpg'].'&vostie='.\urlencode($validatedData['vostie']).'&email='.\urlencode($validatedData['email']);
            \file_get_contents($url);
            return redirect('/error/push')->with('status', 'Informação Enviada!');
        } catch (Exception $e) {
            return redirect('/error/push')->with('status', 'Erro ao enviar informação!');
        }
    }
}
