@extends('layouts.app')

@section('title', 'Painel · ' . config('app.name'))

@section('content')
    <div class="container my-4">
        <h1 class="mb-4">Painel</h1>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm text-white bg-secondary">
                    <div class="card-body">
                        <div class="small text-uppercase opacity-75">Postos</div>
                        <div class="h2 mb-0" id="stations-total">0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm text-white bg-secondary">
                    <div class="card-body">
                        <div class="small text-uppercase opacity-75">Submissões total</div>
                        <div class="h2 mb-0" id="entries-total">0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm text-white bg-secondary">
                    <div class="card-body">
                        <div class="small text-uppercase opacity-75">Última hora</div>
                        <div class="h2 mb-0" id="entries-last-hour">0</div>
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
                    <div class="card-header">Rupturas por combustível</div>
                    <div class="card-body"><canvas id="shortages-chart" height="220"></canvas></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/graphs.js'])
@endpush
