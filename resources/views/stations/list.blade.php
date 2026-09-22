@extends('layouts.app')
@section('title', 'Postos · ' . config('app.name'))

@section('content')
    <div class="container my-4">
        <h1 class="mb-4">Postos</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-sm table-hover" id="stations-table">
                    <thead><tr>
                        <th>ID</th><th>Nome</th><th>Marca</th><th>REPA</th><th>G</th><th>D</th><th>GPL</th>
                    </tr></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    fetch('/panel/stations/fetch/all', { headers: { Accept: 'application/json' } })
        .then((r) => r.json())
        .then(({ data }) => {
            const rows = data.map((s) => `
                <tr>
                    <td>${s.id}</td>
                    <td>${s.name}</td>
                    <td>${s.brand}</td>
                    <td>${s.repa ? '✓' : ''}</td>
                    <td class="${s.sell_gasoline && !s.has_gasoline ? 'table-danger' : ''}">${s.sell_gasoline ? (s.has_gasoline ? '✓' : '✗') : '—'}</td>
                    <td class="${s.sell_diesel && !s.has_diesel ? 'table-danger' : ''}">${s.sell_diesel ? (s.has_diesel ? '✓' : '✗') : '—'}</td>
                    <td class="${s.sell_lpg && !s.has_lpg ? 'table-danger' : ''}">${s.sell_lpg ? (s.has_lpg ? '✓' : '✗') : '—'}</td>
                </tr>`).join('');
            document.querySelector('#stations-table tbody').innerHTML = rows || '<tr><td colspan="7" class="text-muted">Sem dados.</td></tr>';
        });
</script>
@endpush
