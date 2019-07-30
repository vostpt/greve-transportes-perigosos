@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="text-center">{{ __('Fuel Stations List') }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table id="fuel_stations_list" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Brand') }}</th>
                        <th>{{ __('Sells Gasoline') }}</th>
                        <th>{{ __('Sells Diesel') }}</th>
                        <th>{{ __('Sells LPG') }}</th>
                        <th>{{ __('REPA') }}</th>
                        <th>{{ __('Map') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Brand') }}</th>
                        <th>{{ __('Sells Gasoline') }}</th>
                        <th>{{ __('Sells Diesel') }}</th>
                        <th>{{ __('Sells LPG') }}</th>
                        <th>{{ __('REPA') }}</th>
                        <th>{{ __('Map') }}</th>
                        <th>{{ __('Actions') }}</th>
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
            <form method="POST" id="modal_form" class="ui form" action="{{ route('stations.update') }}">
                @csrf
                <div class="modal-body">
                    <p><span id="action_description">Descrição da ação</span></p>
                    @csrf
                    <input id="station_id" type="hidden" name="id" value="0" />
                    <div class="form-group">
                        <label for="station_sell_gasoline">{{ __('Sells Gasoline') }}</label>
                        <select class="form-control" id="station_sell_gasoline" name="sell_gasoline">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="station_sell_diesel">{{ __('Sells Diesel') }}</label>
                        <select class="form-control" id="station_sell_diesel" name="sell_diesel">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="station_sell_lpg">{{ __('Sells LPG') }}</label>
                        <select class="form-control" id="station_sell_lpg" name="sell_lpg">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="station_repa">{{ __('REPA') }}</label>
                        <select class="form-control" id="station_repa" name="repa">
                            <option value="">Não</option>
                            <option value="SOS">SOS</option>
                            <option value="Normal">Normal</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    function modifyFuelStation(id,sell_gasoline,sell_diesel,sell_lpg,repa) {
        $('#modal_form').attr('action', '{{ route('stations.update') }}');
        $('#action_title').html("Modificar Estação nº"+id);
        $('#action_description').html("Esta ação irá editar o a estacão nº"+id+".");
        $("#station_id").val(id);
        $("#station_sell_gasoline").val(sell_gasoline);
        $("#station_sell_diesel").val(sell_diesel);
        $("#station_sell_lpg").val(sell_lpg);
        $("#station_repa").val(repa);
        $('.modal').modal('show');
    }
    $(document).ready(function() {
        $('#fuel_stations_list').DataTable( {
            "ajax": { 
                "url": "{{ route('stations.fetch.all') }}",
                "dataSrc": function (json) {
                    json.data.forEach((element,index) => {
                        json.data[index]["actions"] = '<a href="#" onclick="modifyFuelStation('+json.data[index]["id"]+','+json.data[index]["sell_gasoline"]+','+json.data[index]["sell_diesel"]+','+json.data[index]["sell_lpg"]+','+json.data[index]["repa"]+')"><i class="fas fa-edit"></i></a>';
                        if(json.data[index]["sell_gasoline"] == 1) {
                            json.data[index]["sell_gasoline"] = '<i class="fas fa-check"></i>';
                        }
                        else {
                            json.data[index]["sell_gasoline"] = '<i class="fas fa-times"></i>';
                        }
                        if(json.data[index]["sell_diesel"] == 1) {
                            json.data[index]["sell_diesel"] = '<i class="fas fa-check"></i>';
                        }
                        else {
                            json.data[index]["sell_diesel"] = '<i class="fas fa-times"></i>';
                        }
                        if(json.data[index]["sell_lpg"] == 1) {
                            json.data[index]["sell_lpg"] = '<i class="fas fa-check"></i>';
                        }
                        else {
                            json.data[index]["sell_lpg"] = '<i class="fas fa-times"></i>';
                        }
                        if(json.data[index]["repa"] == 1) {
                            json.data[index]["repa"] = '<i class="fas fa-check"></i>';
                        }
                        else {
                            json.data[index]["repa"] = '<i class="fas fa-times"></i>';
                        }
                        json.data[index]["map"] = '<a href="https://www.waze.com/ul?ll='+json.data[index]["lat"]+'%2C'+json.data[index]["long"]+'&navigate=yes&zoom=16&download_prompt=false"  target="_blank" rel="noopener noreferrer">Ver no Mapa</a>';
                    });
                    return json.data;
                }
            },   
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "brand" },
                { "data": "sell_gasoline" },
                { "data": "sell_diesel" },
                { "data": "sell_lpg" },
                { "data": "repa" },
                { "data": "map" },
                { "data": "actions" }
            ]   
        });
    });

</script>
@endsection