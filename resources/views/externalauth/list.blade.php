@extends('layouts.app')
@section('title', 'External Auth · ' . config('app.name'))

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Credenciais externas</h1>
            <a href="{{ route('panel.externalauth.add') }}" class="btn btn-danger">Adicionar</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-sm" id="ea-table">
                    <thead><tr><th>Key</th><th>Brand</th><th>Criado</th><th>Estado</th><th></th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    fetch('/panel/externalauth/fetch/all').then((r) => r.json()).then(({ data }) => {
        const rows = data.map((ea) => `
            <tr class="${ea.deleted_at ? 'table-secondary' : ''}">
                <td><code>${ea.key}</code></td>
                <td>${ea.brand}</td>
                <td class="text-muted small">${ea.created_at ?? ''}</td>
                <td>${ea.deleted_at ? 'Removida' : 'Ativa'}</td>
                <td>
                    ${ea.deleted_at ? '' : `<form method="POST" action="{{ route('panel.externalauth.delete') }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="id" value="${ea.id}"><button class="btn btn-sm btn-outline-danger">Remover</button></form>`}
                </td>
            </tr>`).join('');
        document.querySelector('#ea-table tbody').innerHTML = rows || '<tr><td colspan="5" class="text-muted">Sem credenciais.</td></tr>';
    });
</script>
@endpush
