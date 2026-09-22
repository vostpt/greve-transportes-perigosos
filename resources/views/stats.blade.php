@extends('layouts.app')

@section('title', 'Estatísticas · ' . config('app.name'))

@section('content')
    <div class="container my-4">
        <h1 class="mb-4">Estatísticas</h1>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase">Postos totais</div>
                        <div class="h2 mb-0" id="stations-total">0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase">Submissões total</div>
                        <div class="h2 mb-0" id="entries-total">0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase">Última hora</div>
                        <div class="h2 mb-0" id="entries-last-hour">0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase">Últimas 24h</div>
                        <div class="h2 mb-0" id="entries-last-day">0</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Disponibilidade global</div>
                    <div class="card-body"><canvas id="stations-chart" height="220"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Combustíveis: venda vs. rutura</div>
                    <div class="card-body"><canvas id="shortages-chart" height="220"></canvas></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/graphs.js'])
@endpush
