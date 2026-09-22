@extends('layouts.app')

@section('title', 'Mapa · ' . config('app.name'))
@section('body-class', 'map-page')

@section('content')
    <div class="map-wrapper">
        <div id="map"></div>

        <a href="#" id="toggle-filters" class="btn btn-light map-overlay map-overlay-toggle" aria-label="Filtros">
            <i class="fas fa-sliders-h"></i>
        </a>

        <div id="map-filters" class="map-overlay map-overlay-filters d-none">
            <h4>Filtros</h4>
            <fieldset>
                @php
                    $filterOptions = [
                        'all' => 'Todos os postos',
                        'gasoline' => 'Com gasolina',
                        'diesel' => 'Com gasóleo',
                        'lpg' => 'Com GPL',
                        'without_gasoline' => 'Sem gasolina',
                        'without_diesel' => 'Sem gasóleo',
                        'without_lpg' => 'Sem GPL',
                        'none' => 'Sem nada',
                    ];
                @endphp
                @foreach ($filterOptions as $value => $label)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="fuel_stations_type" id="ft-{{ $value }}" value="{{ $value }}" @checked($value === 'all')>
                        <label class="form-check-label" for="ft-{{ $value }}">{{ $label }}</label>
                    </div>
                @endforeach
            </fieldset>

            <h4 class="mt-3">Marcas oficiais</h4>
            <fieldset>
                @foreach (['Bxpress', 'Ecobrent', 'OZ Energia', 'Prio', 'Tfuel'] as $brand)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="fuel_stations_brand[]" id="fb-{{ $loop->index }}" value="{{ $brand }}">
                        <label class="form-check-label" for="fb-{{ $loop->index }}">{{ $brand }}</label>
                    </div>
                @endforeach
                <a href="#" id="reset-brand-filter" class="small">Limpar</a>
            </fieldset>
        </div>

        <a href="#" id="toggle-legend" class="btn btn-light map-overlay" style="top: 60px; right: 60px;" aria-label="Legenda">
            <i class="fas fa-question"></i>
        </a>

        <div id="map-legend" class="map-overlay map-overlay-legend d-none">
            <h4>Legenda</h4>
            <div><span class="badge" style="background:#2e7d32">&nbsp;</span> Todos os combustíveis disponíveis</div>
            <div><span class="badge" style="background:#f9a825">&nbsp;</span> Alguns combustíveis indisponíveis</div>
            <div><span class="badge" style="background:#c62828">&nbsp;</span> Nenhum combustível disponível</div>
            <div><span class="badge" style="background:#9e9e9e">&nbsp;</span> Sem dados</div>
        </div>
    </div>
@endsection

@push('scripts')
    @php
        $mapConfig = [
            'token' => config('services.mapbox.token'),
            'style' => config('services.mapbox.style'),
            'center' => [-8.5, 39.5],
            'zoom' => 6.5,
        ];
    @endphp
    <script>
        window.__MAP_CONFIG__ = {!! json_encode($mapConfig) !!};
    </script>
    @vite(['resources/js/map.js'])
@endpush
