@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="text-center">{{ __('Entries List') }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <p><a href="#" onclick="selectAll()"><i class="fas fa-check"></i> Check all</a></p>
            <p><a href="#" onclick="deSelectAll()"><i class="fas fa-check"></i> Uncheck all</a></p>
            <p><a href="#" onclick="pushEntries()"><i class="fas fa-check"></i> Aprovar Seleccionados</a></p>
            <table id="entry_list" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Fuel Station') }}</th>
                        <th>{{ __('Has Gasoline') }}</th>
                        <th>{{ __('Has Diesel') }}</th>
                        <th>{{ __('Has LPG') }}</th>
                        <th>{{ __('Count') }}</th>
                        <th>{{ __('Actions') }}</th>
                        <th>{{ __('Select') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Fuel Station') }}</th>
                        <th>{{ __('Has Gasoline') }}</th>
                        <th>{{ __('Has Diesel') }}</th>
                        <th>{{ __('Has LPG') }}</th>
                        <th>{{ __('Count') }}</th>
                        <th>{{ __('Actions') }}</th>
                        <th>{{ __('Select') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Confirmar Ação - <span id="action_title">Titulo da ação</span></h5>
            </div>
            <div class="modal-body">
                <p><span id="action_description">Descrição da ação</span></p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="modal_form" class="ui form" action="{{ route('entries.push') }}">
                    @csrf
                    <input class="entry_id" type="hidden" name="id[]" value="0" />
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    function pushEntry(id) {
        $('#modal_form').attr('action', '{{ route('entries.push') }}');
        $('#action_title').html("Validar Entrada nº"+id);
        $('#action_description').html("Esta ação irá validar o a entrada nº"+id+".");
        $(".entry_id").val(id);
        $('.modal').modal('show');
    }

    function pushEntries() {
        $selected = $('.select-entry-checkbox:checkbox:checked');
        $(".entry_id").remove();

        let ids = [];
        $selected.each(function(){
            $el = $(this);
            ids.push($el.data('id'))

            $('#modal_form').append('<input class="entry_id" type="hidden" name="id[]" value="'+$el.data('id')+'" />');
        });

        $('#modal_form').attr('action', '{{ route('entries.push') }}');
        $('#action_title').html("Validar Entradas nsº"+ ids.join());
        $('#action_description').html("Esta ação irá validar as entradas nº "+ids.join()+".");

        $('.modal').modal('show');
    }

    function selectAll()
    {
        $('.select-entry-checkbox').prop('checked', true);
    }

    function deSelectAll()
    {
        $('.select-entry-checkbox').prop('checked', false);
    }

    $(document).ready(function() {
        $('#entry_list').DataTable( {
            "ajax": { 
                "url": "{{ route('entries.fetch.pending') }}",
                "dataSrc": function (json) {
                    json.data.forEach((element,index) => {
                        if(json.data[index]["has_gasoline"] == 1) {
                            json.data[index]["has_gasoline"] = '<i class="fas fa-check"></i>';
                        }
                        else {
                            json.data[index]["has_gasoline"] = '<i class="fas fa-times"></i>';
                        }
                        if(json.data[index]["has_diesel"] == 1) {
                            json.data[index]["has_diesel"] = '<i class="fas fa-check"></i>';
                        }
                        else {
                            json.data[index]["has_diesel"] = '<i class="fas fa-times"></i>';
                        }
                        if(json.data[index]["has_lpg"] == 1) {
                            json.data[index]["has_lpg"] = '<i class="fas fa-check"></i>';
                        }
                        else {
                            json.data[index]["has_lpg"] = '<i class="fas fa-times"></i>';
                        }
                        json.data[index]["actions"] = '<a href="#" onclick="pushEntry('+json.data[index]["id"]+')"><i class="fas fa-check"></i></a>';
                        json.data[index]["select"] = '<input type="checkbox" class="select-entry-checkbox" name="checked" data-id="'+json.data[index]["id"]+'"/>';
                    });
                    return json.data;
                }
            },   
            "columns": [
                { "data": "id" },
                { "data": "fuel_station" },
                { "data": "has_gasoline" },
                { "data": "has_diesel" },
                { "data": "has_lpg" },
                { "data": "count" },
                { "data": "actions" },
                { "data": "select" }
            ]
        });
    });

</script>
@endsection