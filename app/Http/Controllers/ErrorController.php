<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\SendErrorReportJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ErrorController extends Controller
{
    public function new(): Response
    {
        return response()->view('error.new');
    }

    public function edit(): Response
    {
        return response()->view('error.edit');
    }

    public function submitted(): Response
    {
        return response()->view('error.submitted');
    }

    public function push(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => 'nullable|exists:fuel_stations,id',
            'brand' => 'required|string',
            'long' => 'required|numeric',
            'lat' => 'required|numeric',
            'sell_gasoline' => 'required',
            'sell_diesel' => 'required',
            'sell_lpg' => 'required',
            'captcha' => 'required|string',
            'vostie' => 'nullable|string',
            'email' => 'required|email',
        ]);

        if (! $this->captchaOk($data['captcha'], $request->ip())) {
            return redirect('/')->with('status', 'Erro ao enviar informação.');
        }

        SendErrorReportJob::dispatch([
            'id' => $data['id'] ?? 0,
            'lat' => $data['lat'],
            'long' => $data['long'],
            'brand' => $data['brand'],
            'gasoline' => $data['sell_gasoline'],
            'diesel' => $data['sell_diesel'],
            'lpg' => $data['sell_lpg'],
            'vostie' => $data['vostie'] ?? 'No',
            'email' => $data['email'],
        ]);

        return redirect('/error/submitted')->with('status', 'Informação enviada.');
    }

    private function captchaOk(string $token, ?string $ip): bool
    {
        $secret = config('services.recaptcha.secret');
        if (! $secret) {
            return true; // dev/local: no captcha configured
        }

        $response = Http::asForm()
            ->timeout(5)
            ->post(config('services.recaptcha.verify_url'), [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $ip,
            ]);

        return $response->ok() && $response->json('success') === true;
    }
}
