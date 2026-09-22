@extends('layouts.app')
@section('title', 'Entries · ' . config('app.name'))

@section('content')
    <div class="container my-4">
        <h1 class="mb-4">Entradas pendentes</h1>
        <p class="text-muted">Submissões da última hora ainda não promovidas. A promoção manual força a atualização do posto e marca as submissões coincidentes como usadas.</p>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-hover" id="entries-table">
                    <thead><tr>
                        <th>Posto</th><th>Gasolina</th><th>Gasóleo</th><th>GPL</th><th>Votos</th><th></th>
                    </tr></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    async function reload() {
        const response = await fetch('/panel/entries/fetch/pending', { headers: { Accept: 'application/json' } });
        const { data } = await response.json();
        const rows = data.map((row) => `
            <tr>
                <td>${row.fuel_station}</td>
                <td>${row.has_gasoline ? '✓' : '✗'}</td>
                <td>${row.has_diesel ? '✓' : '✗'}</td>
                <td>${row.has_lpg ? '✓' : '✗'}</td>
                <td>${row.count}</td>
                <td>
                    <form method="POST" action="{{ route('panel.entries.push') }}" style="display:inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="${row.id}">
                        <button class="btn btn-sm btn-danger">Promover</button>
                    </form>
                </td>
            </tr>`).join('');
        document.querySelector('#entries-table tbody').innerHTML = rows || '<tr><td colspan="6" class="text-muted">Nenhuma submissão pendente.</td></tr>';
    }
    reload();
    setInterval(reload, 15_000);
</script>
@endpush
