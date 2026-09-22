@extends('layouts.app')
@section('title', 'Utilizadores · ' . config('app.name'))

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Utilizadores</h1>
            <a href="{{ route('panel.users.add') }}" class="btn btn-danger">Adicionar</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5">Por verificar</h2>
                <table class="table table-sm"><thead><tr><th>Nome</th><th>Email</th><th></th></tr></thead><tbody id="not-verified"></tbody></table>
                <h2 class="h5 mt-4">Verificados</h2>
                <table class="table table-sm"><thead><tr><th>Nome</th><th>Email</th><th></th></tr></thead><tbody id="verified"></tbody></table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function renderRow(u, verified) {
        return `
            <tr>
                <td>${u.name}</td>
                <td>${u.email}</td>
                <td>
                    ${verified ? '' : `<form method="POST" action="{{ route('panel.users.verify') }}" class="d-inline"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="id" value="${u.id}"><button class="btn btn-sm btn-success">Verificar</button></form>`}
                    <form method="POST" action="{{ route('panel.users.delete') }}" class="d-inline"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="id" value="${u.id}"><button class="btn btn-sm btn-outline-danger">Eliminar</button></form>
                </td>
            </tr>`;
    }
    fetch('/panel/users/fetch/not_verified').then((r) => r.json()).then(({ data }) => {
        document.getElementById('not-verified').innerHTML = data.map((u) => renderRow(u, false)).join('') || '<tr><td colspan="3" class="text-muted">Nenhum utilizador por verificar.</td></tr>';
    });
    fetch('/panel/users/fetch/verified').then((r) => r.json()).then(({ data }) => {
        document.getElementById('verified').innerHTML = data.map((u) => renderRow(u, true)).join('') || '<tr><td colspan="3" class="text-muted">Nenhum utilizador verificado.</td></tr>';
    });
</script>
@endpush
