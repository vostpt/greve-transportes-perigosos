@extends('layouts.app')
@section('title', 'Opções · ' . config('app.name'))

@section('content')
    <div class="container my-4" style="max-width: 780px;">
        <h1 class="mb-4">Opções</h1>
        <div id="options-list"></div>
    </div>
@endsection

@push('scripts')
<script>
    fetch('/panel/options/fetch/all', { headers: { Accept: 'application/json' } })
        .then((r) => r.json())
        .then(({ data }) => {
            const html = data.map((opt) => `
                <div class="card mb-2 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">${opt.name}</h5>
                        <p class="text-muted small">${opt.description}</p>
                        <form method="POST" action="{{ route('panel.options.update') }}" class="d-flex gap-2">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="name" value="${opt.name}">
                            <input type="text" name="value" value="${opt.value}" class="form-control">
                            <button class="btn btn-danger">Guardar</button>
                        </form>
                    </div>
                </div>`).join('');
            document.getElementById('options-list').innerHTML = html;
        });
</script>
@endpush
