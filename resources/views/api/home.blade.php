@extends('layouts.app')
@section('title', 'API v1 · ' . config('app.name'))

@section('content')
    <div class="container my-4" style="max-width: 900px;">
        <h1>API v1</h1>
        <p class="lead">Endpoints REST para parceiros autorizados. Todas as chamadas requerem <code>key</code> e <code>secret</code> emitidos pela VOST Portugal.</p>

        <h2 class="h5 mt-4">Endpoints</h2>
        <table class="table table-sm">
            <thead>
                <tr><th>Método</th><th>Caminho</th><th>Descrição</th></tr>
            </thead>
            <tbody>
                <tr><td>POST</td><td><code>/api/v1/info</code></td><td>Devolve o brand associado às credenciais.</td></tr>
                <tr><td>POST</td><td><code>/api/v1/fetch</code></td><td>Lista postos visíveis para a credencial.</td></tr>
                <tr><td>POST</td><td><code>/api/v1/push</code></td><td>Actualiza disponibilidade de um posto (<code>id</code>, <code>has_*</code>).</td></tr>
                <tr><td>POST</td><td><code>/api/v1/add</code></td><td>Cria um posto novo.</td></tr>
                <tr><td>POST</td><td><code>/api/v1/change</code></td><td>Actualiza metadados de um posto existente.</td></tr>
                <tr><td>GET</td><td><code>/api/v1/fetchcsv</code></td><td>Dump CSV dos postos (público, exclui <code>POSTO ES</code>).</td></tr>
                <tr><td>GET</td><td><code>/api/v1/fetchapicsv?password=...</code></td><td>Dump CSV dos postos criados via API.</td></tr>
            </tbody>
        </table>
    </div>
@endsection
