@extends('layouts.app')
@section('title', 'Reportar erro · ' . config('app.name'))

@section('content')
    <div class="container my-4" style="max-width: 720px;">
        <h1>Reportar erro</h1>
        <p class="text-muted">Preencha o formulário para nos comunicar uma correção sobre um posto específico.</p>

        <form method="POST" action="{{ route('error.push') }}" class="card card-body shadow-sm">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="brand">Marca</label>
                <input type="text" class="form-control" name="brand" id="brand" required>
            </div>
            <div class="row g-2">
                <div class="col">
                    <label class="form-label" for="lat">Latitude</label>
                    <input type="number" step="0.0000001" class="form-control" name="lat" id="lat" required>
                </div>
                <div class="col">
                    <label class="form-label" for="long">Longitude</label>
                    <input type="number" step="0.0000001" class="form-control" name="long" id="long" required>
                </div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col">
                    <label class="form-label" for="sell_gasoline">Vende gasolina?</label>
                    <select class="form-select" name="sell_gasoline" id="sell_gasoline"><option value="1">Sim</option><option value="0">Não</option></select>
                </div>
                <div class="col">
                    <label class="form-label" for="sell_diesel">Vende gasóleo?</label>
                    <select class="form-select" name="sell_diesel" id="sell_diesel"><option value="1">Sim</option><option value="0">Não</option></select>
                </div>
                <div class="col">
                    <label class="form-label" for="sell_lpg">Vende GPL?</label>
                    <select class="form-select" name="sell_lpg" id="sell_lpg"><option value="1">Sim</option><option value="0">Não</option></select>
                </div>
            </div>
            <div class="mb-3 mt-3">
                <label class="form-label" for="email">Email de contacto</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <input type="hidden" name="captcha" value="disabled">
            <button type="submit" class="btn btn-danger">Submeter</button>
        </form>
    </div>
@endsection
