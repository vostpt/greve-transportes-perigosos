@extends('layouts.app')

@section('viewport')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('styles')
<style>
    @media (min-width: 768px) {
        iframe {
            height: 350px;
        }
    }

    @media  (max-width: 768px) {
        iframe {
            height: 650px;
        }
    }
</style>
@endsection

@section('content')
<div class="container text-center">
    <img src="/img/VOSTPT_FuelCrisisPT_JNDPA_Logo_With_VOSTPT_Logo_800pxX800px.png" style="width:20em" />
    <div class="row">
        <iframe frameborder="0" scrolling="no"id="global_stats" style="width:100%;" src="/graphs/stats"></iframe>
    </div>
    <div class="row" style="text-align: left">
        <div class="col-md-9" style="margin-top: 0.5em;">
            <p class="btn btn-primary">Número de Submissões (Última Hora): <span
                    id="entries_last_hour">Loading...</span></p>
        </div>
        <div class="col-md-9" style="margin-top: 0.5em;">
            <p class="btn btn-primary">Número de Submissões (Último Dia): <span id="entries_last_day">Loading...</span>
            </p>
        </div>
        <div class="col-md-9" style="margin-top: 0.5em;">
            <p class="btn btn-primary">Número de Submissões (Total): <span id="entries_total">Loading...</span></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>Estatisticas Por Localização</h2>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="district_selection">Distrito:</label>
                <select class="form-control" id="district_selection">
                    <option value="none">Todos</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="county_selection">Concelho:</label>
                <select class="form-control" id="county_selection" disabled>
                    <option value="none">Todos</option>
                </select>
            </div>
        </div>
        <iframe frameborder="0" scrolling="no" id="selected_stats" style="width:100%;"
            src="/graphs/stats"></iframe>
    </div>
    @endsection

    @section('javascript')
    <script src="{{ mix('/js/stats.js') }}" charset="utf-8"></script>
    @endsection