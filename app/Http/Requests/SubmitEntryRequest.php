<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;

class SubmitEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fuel_station' => ['required', 'integer', 'exists:fuel_stations,id'],
            'gasoline' => ['required', 'boolean'],
            'diesel' => ['required', 'boolean'],
            'lpg' => ['required', 'boolean'],
            'captcha' => ['required', 'string'],
        ];
    }

    protected function passedValidation(): void
    {
        $secret = config('services.recaptcha.secret');
        if (! $secret) {
            return;
        }

        $response = Http::asForm()
            ->timeout(5)
            ->post(config('services.recaptcha.verify_url'), [
                'secret' => $secret,
                'response' => $this->input('captcha'),
                'remoteip' => $this->ip(),
            ]);

        if (! $response->ok() || ! ($response->json('success') === true)) {
            throw new HttpResponseException(response()->json(['success' => 0, 'reason' => 'captcha_failed'], 422));
        }
    }
}
