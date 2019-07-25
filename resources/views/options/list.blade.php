@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="text-center">{{ __('Options') }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{ route('options.update') }}" method="POST">
                <h2 id="num_required_entries_desc">Loading...</h2>
                <input type="hidden" name="name" value="num_entries_required">
                <div class="form-group">
                    <input type="number" step="1" min="0" id="num_required_entries_value" name="value">
                </div>
                <button type="submit" class="btn btn-primary">Confirmar</button>
            </form>
            <form action="{{ route('options.update') }}" method="POST">
                <h2 id="stress_lockdown_desc">Loading...</h2>
                <input type="hidden" name="name" value="stress_lockdown">
                <div class="form-group">
                    <select class="form-control" id="stress_lockdown_value" name="value">
                        <option value="0">Desativado</option>
                        <option value="1">Ativado</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Confirmar</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        $.getJSON( "{{ route('options.fetch.all') }}", function( data ) {
            $("#num_required_entries_desc").html(data["data"][0]["description"]);
            $("#num_required_entries_value").val(data["data"][0]["value"]);
            $("#stress_lockdown_desc").html(data["data"][1]["description"]);
            $("#stress_lockdown_value").val(data["data"][1]["value"]);
        });
    });
</script>
@endsection