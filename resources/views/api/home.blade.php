<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VOST') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

</head>

<body>
    <h1 class="text-center" id="header_text" style="margin-top:1em;"><img src="/img/VOSTPT_ROUND_FULL-COLOR.png"
            style="width: 1em;margin-top: -10px;" />VOSTPT - Já Não Dá Para Abastecer - API v1</h1>
    <div class="container" id="fetcher">
        <h2>Instructions</h2>
        <div class="accordion" id="API_USAGE">
            <div class="card">
                <div class="card-header" id="API_ACCESS">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#API_ACCESS_COLLAPSE" aria-expanded="false"
                            aria-controls="API_ACCESS_COLLAPSE">
                            How to use API v1
                        </button>
                    </h2>
                </div>
                <div id="API_ACCESS_COLLAPSE" class="collapse" aria-labelledby="API_ACCESS" data-parent="#API_USAGE">
                    <div class="card-body">
                        <h2>How to use API v1</h2>
                        <p>First you need to contact hello@vost.pt for credentials.</p>
                        <p>You can use the API through the endpoints or through the manual access.</p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="API_FETCH">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse"
                            data-target="#API_FETCH_COLLAPSE" aria-expanded="false" aria-controls="API_FETCH_COLLAPSE">
                            Endpoint for Data Fetching
                        </button>
                    </h2>
                </div>

                <div id="API_FETCH_COLLAPSE" class="collapse" aria-labelledby="API_FETCH" data-parent="#API_USAGE">
                    <div class="card-body">
                        <h2>Endpoint for Data Fetching</h2>
                        <code>/api/v1/fetch</code>
                        <h3>CURL</h3>
                        <code>
                            curl -d '{"key":"Key Given by VOSTPT","secret":"Secret Given by VOSTPT"' -H "Content-Type: application/json" -X POST http://localhost:8000/api/v1/fetch
                        </code>
                        <h3>Parameters</h3>
                        <p><b>key:</b> Access key given by VOSTPT</p>
                        <p><b>secret:</b> Access secret given by VOSTPT</p>
                        <h3>Response (JSON)</h3>
                        <h4>On Success</h4>
                        <code>
                            [
                                {
                                    id: ...,
                                    name: ...,
                                    has_gasoline: ...,
                                    has_diesel: ...,
                                    has_lpg: ...,
                                    lat: ...,
                                    long: ...
                                },
                            ...
                            ]
                        }
                        </code>
                        <h4>On Fail</h4>
                        <code>
                            []
                        </code>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="API_PUSH">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#API_PUSH_COLLAPSE" aria-expanded="false" aria-controls="API_PUSH_COLLAPSE">
                            Endpoint for Data Pushing
                        </button>
                    </h2>
                </div>
                <div id="API_PUSH_COLLAPSE" class="collapse" aria-labelledby="API_PUSH" data-parent="#API_USAGE">
                    <div class="card-body">
                        <h2>Endpoint for Data Pushing</h2>
                        <code>/api/v1/push</code>
                        <h3>CURL</h3>
                        <code>
                            curl -d '{"key":"Key Given by VOSTPT","secret":"Secret Given by VOSTPT","id":12345, "has_gasoline":0, "has_diesel": 0, "has_lpg": 0}' -H "Content-Type: application/json" -X POST http://localhost:8000/api/v1/push
                        </code>
                        <h3>Parameters</h3>
                        <p><b>key:</b> Access key given by VOSTPT</p>
                        <p><b>secret:</b> Access secret given by VOSTPT</p>
                        <p><b>id:</b> ID of Fuel Station (found by fetching)</p>
                        <p><b>has_gasoline:</b> 0 or below if station has no gasoline, 1 or above if station has
                            gasoline</p>
                        <p><b>has_diesel:</b> 0 or below if station has no diesel, 1 or above if station has diesel</p>
                        <p><b>has_lpg:</b> 0 or below if station has no lpg, 1 or above if station has lpg</p>
                        <h3>Response (JSON)</h3>
                        <h4>On Success</h4>
                        <code>
                            {"success" => 1}
                        </code>
                        <h4>On Fail</h4>
                        <code>
                            {"success" => 0}
                        </code>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <h2>Manual Access</h2>
        <div class="form-group">
            <label for="inputKey">Key</label>
            <input type="text" name="key" class="form-control" id="inputKey">
        </div>
        <div class="form-group">
            <label for="inputSecret">Secret</label>
            <input type="text" name="secret" class="form-control" id="inputSecret">
        </div>
        <a href="#" onclick="fetch()" class="btn btn-primary">Submit</a>
    </div>
    <div class="container" id="table" style="display:none;">
        <hr />
        <a href="#" onclick="addFuelStation()" class="btn btn-primary">Add New Station</a>
        <hr />
        <table id="fetched_list" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Has Gasoline') }}</th>
                    <th>{{ __('Has Diesel') }}</th>
                    <th>{{ __('Has LPG') }}</th>
                    <th>{{ __('Map') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Has Gasoline') }}</th>
                    <th>{{ __('Has Diesel') }}</th>
                    <th>{{ __('Has LPG') }}</th>
                    <th>{{ __('Map') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div id="action_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Confirmar Ação - <span id="action_title">Titulo da ação</span></h5>
                </div>
                <div class="modal-body">
                    <p><span id="action_description">Descrição da ação</span></p>
                    <input id="station_id" type="hidden" name="id" value="0" />
                    <div class="form-group">
                        <label for="station_has_gasoline">Tem Gasolina</label>
                        <select class="form-control" id="station_has_gasoline" name="has_gasoline">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="station_has_diesel">Tem Gasoleo</label>
                        <select class="form-control" id="station_has_diesel" name="has_diesel">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="station_has_lpg">Tem GPL</label>
                        <select class="form-control" id="station_has_lpg" name="has_lpg">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <a href="#" onclick="updatePushFuelStation()" class="btn btn-primary">Confirmar</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="change_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Confirmar Ação - <span id="change_title">Titulo da ação</span></h5>
                </div>
                <div class="modal-body">
                    <p><span id="change_description">Descrição da ação</span></p>
                    <input id="change_id" type="hidden" name="id" value="0" />
                    <div class="form-group">
                        <label for="change_name">Nome</label>
                        <input id="change_name" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="change_brand">Marca</label>
                        <input id="change_brand" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="change_county">Concelho</label>
                        <input id="change_county" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="change_district">Distrito</label>
                        <input id="change_district" type="text" class="form-control" />
                    </div>
                    <h5>Localização Exata</h5>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="change_long">Longitude</label>
                            <input type="number" step="0.0000000001" class="form-control" id="change_long" name="long"
                                value="0">
                        </div>
                        <div class="form-group col-6">
                            <label for="change_lat">Latitude</label>
                            <input type="number" step="0.0000000001" class="form-control" id="change_lat" name="lat"
                                value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="change_sell_gasoline">Vende Gasolina</label>
                        <select class="form-control" id="change_sell_gasoline" name="sell_gasoline">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="change_sell_diesel">Vende Gasoleo</label>
                        <select class="form-control" id="change_sell_diesel" name="sell_diesel">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="change_sell_lpg">Vende GPL</label>
                        <select class="form-control" id="change_sell_lpg" name="sell_lpg">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="change_repa">REPA</label>
                        <select class="form-control" id="change_repa" name="repa">
                            <option value="">Não</option>
                            <option value="SOS">SOS</option>
                            <option value="Normal">Normal</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <a href="#" onclick="updateModifyFuelStation()" class="btn btn-primary">Confirmar</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="add_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Confirmar Ação - <span id="add_title">Titulo da ação</span></h5>
                </div>
                <div class="modal-body">
                    <p><span id="add_description">Descrição da ação</span></p>
                    <div class="form-group">
                        <label for="add_name">Nome</label>
                        <input id="add_name" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="add_brand">Marca</label>
                        <input id="add_brand" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="add_county">Concelho</label>
                        <input id="add_county" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="add_district">Distrito</label>
                        <input id="add_district" type="text" class="form-control" />
                    </div>
                    <h5>Localização Exata</h5>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="add_long">Longitude</label>
                            <input type="number" step="0.0000000001" class="form-control" id="add_long" name="long"
                                value="0">
                        </div>
                        <div class="form-group col-6">
                            <label for="add_lat">Latitude</label>
                            <input type="number" step="0.0000000001" class="form-control" id="add_lat" name="lat"
                                value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_sell_gasoline">Vende Gasolina</label>
                        <select class="form-control" id="add_sell_gasoline" name="sell_gasoline">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_sell_diesel">Vende Gasoleo</label>
                        <select class="form-control" id="add_sell_diesel" name="sell_diesel">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_sell_lpg">Vende GPL</label>
                        <select class="form-control" id="add_sell_lpg" name="sell_lpg">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_repa">REPA</label>
                        <select class="form-control" id="add_repa" name="repa">
                            <option value="">Não</option>
                            <option value="SOS">SOS</option>
                            <option value="Normal">Normal</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <a href="#" onclick="updateAddFuelStation()" class="btn btn-primary">Adicionar</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ mix('/js/app.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        let key = "";
        let secret = "";
        let datatable = null;

        function addFuelStation() {
            $('#add_title').html("Adicionar Estação");
            $('#add_description').html("Esta ação irá adicionar uma estação.");
            $("#add_name").val("");
            $("#add_brand").val("");
            $("#add_lat").val("");
            $("#add_long").val("");
            $("#add_sell_gasoline").val("");
            $("#add_sell_diesel").val("");
            $("#add_sell_lpg").val("");
            $("#add_repa").val("");
            $("#add_county").val("");
            $("#add_district").val("");
            $('#add_modal').modal('show');
        }


        function updateAddFuelStation() {
            let name = $("#add_name").val();
            let brand = $("#add_brand").val();
            let lat = $("#add_lat").val();
            let long = $("#add_long").val();
            let sell_gasoline = $("#add_sell_gasoline").val();
            let sell_diesel = $("#add_sell_diesel").val();
            let sell_lpg = $("#add_sell_lpg").val();
            let repa = $("#add_repa").val();
            let county = $("#add_county").val();
            let district = $("#add_district").val();
            $('#add_modal').modal('hide');
            $.post( "/api/v1/add", { key: key, secret: secret,name:name,brand:brand,lat:lat,long:long, sell_gasoline: sell_gasoline, sell_diesel: sell_diesel, sell_lpg: sell_lpg, repa:repa, county:county,district:district }, function( data ) {
                if(data["success"] == 1) {
                    fetch();
                    alert('Added Fuel Station');
                }
                else {
                    alert('Error Adding Fuel Station');
                }
            }, "json");
        }
        function modifyFuelStation(id,name,brand,lat,long,sell_gasoline,sell_diesel,sell_lpg,repa,county,district) {
            $('#change_title').html("Modificar Informação da Estação nº"+id);
            $('#change_description').html("Esta ação irá editar a informação da estacão nº"+id+".");
            $("#change_id").val(id);
            $("#change_name").val(name);
            $("#change_brand").val(brand);
            $("#change_lat").val(lat);
            $("#change_long").val(long);
            $("#change_sell_gasoline").val(sell_gasoline);
            $("#change_sell_diesel").val(sell_diesel);
            $("#change_sell_lpg").val(sell_lpg);
            $("#change_repa").val(repa);
            $("#change_county").val(county);
            $("#change_district").val(district);
            $('#change_modal').modal('show');
        }

        function updateModifyFuelStation() {
            let id = $("#change_id").val();
            let name = $("#change_name").val();
            let brand = $("#change_brand").val();
            let lat = $("#change_lat").val();
            let long = $("#change_long").val();
            let sell_gasoline = $("#change_sell_gasoline").val();
            let sell_diesel = $("#change_sell_diesel").val();
            let sell_lpg = $("#change_sell_lpg").val();
            let repa = $("#change_repa").val();
            let county = $("#change_county").val();
            let district = $("#change_district").val();
            $('#change_modal').modal('hide');
            $.post( "/api/v1/change", { key: key, secret: secret, id: id,name:name,brand:brand,lat:lat,long:long, sell_gasoline: sell_gasoline, sell_diesel: sell_diesel, sell_lpg: sell_lpg, repa:repa, county:county,district:district }, function( data ) {
                if(data["success"] == 1) {
                    fetch();
                    alert('Modified Fuel Station');
                }
                else {
                    alert('Error Modifying Fuel Station');
                }
            }, "json");
        }
        function pushFuelStation(id,has_gasoline,has_diesel,has_lpg) {
            $('#action_title').html("Modificar Estação nº"+id);
            $('#action_description').html("Esta ação irá editar o a estacão nº"+id+".");
            $("#station_id").val(id);
            $("#station_has_gasoline").val(has_gasoline);
            $("#station_has_diesel").val(has_diesel);
            $("#station_has_lpg").val(has_lpg);
            $('#action_modal').modal('show');
        }
        function updatePushFuelStation() {
            let id = $("#station_id").val();
            let has_gasoline = $("#station_has_gasoline").val();
            let has_diesel = $("#station_has_diesel").val();
            let has_lpg = $("#station_has_lpg").val();
            $('#action_modal').modal('hide');
            $.post( "/api/v1/push", { key: key, secret: secret, id: id, has_gasoline: has_gasoline, has_diesel: has_diesel, has_lpg: has_lpg }, function( data ) {
                if(data["success"] == 1) {
                    fetch();
                    alert('Updated Fuel Station');
                }
                else {
                    alert('Error Updating Fuel Station');
                }
            }, "json");
        }
        function fetch() {
            key = $("#inputKey").val();
            secret = $("#inputSecret").val();
            let text = "<img src=\"/img/VOSTPT_ROUND_FULL-COLOR.png\" style=\"width: 1em;margin-top: -10px;\" />VOSTPT - Já Não Dá Para Abastecer - Área Reservada a ";
            $.post( "/api/v1/info", { key: key, secret: secret }, function( info ) {
                if(info.brand == "WRITEREAD" || info.brand == "READONLY") {
                    text += key;
                }
                else {
                    text += info.brand;
                }
                text += " (<a href='/api/v1/'>Terminar Sessão</a>)"
                $("#header_text").html(text);
                $.post( "/api/v1/fetch", { key: key, secret: secret }, function( data ) {
                    if(data != []) {
                        $("#fetcher").hide();
                        if(datatable != null) {
                            datatable.destroy();
                            datatable = null;
                            $("#fetched_list tbody").html("");
                        }
                        data.forEach(station => {
                            station["actions"] = '<div class="row"><div class="col-md-6"><a href="#" data-toggle="tooltip" title="Validar" onclick="pushFuelStation('+station["id"]+','+station["has_gasoline"]+','+station["has_diesel"]+','+station["has_lpg"]+')"><i class="fas fa-check-circle"></i></a></div><div class="col-md-6"><a href="#" data-toggle="tooltip" title="Modificar" onclick="modifyFuelStation('+station["id"]+',\''+station["name"]+'\',\''+station["brand"]+'\','+station["lat"]+','+station["long"]+','+station["sell_gasoline"]+','+station["sell_diesel"]+','+station["sell_lpg"]+',\''+station["repa"]+'\',\''+station["county"]+'\',\''+station["district"]+'\')"><i class="fas fa-edit"></i></a></div></div>';
                            if(station["has_gasoline"] == 1) {
                                station["has_gasoline"] = '<i class="fas fa-check"></i>';
                            }
                            else {
                                station["has_gasoline"] = '<i class="fas fa-times"></i>';
                            }
                            if(station["has_diesel"] == 1) {
                                station["has_diesel"] = '<i class="fas fa-check"></i>';
                            }
                            else {
                                station["has_diesel"] = '<i class="fas fa-times"></i>';
                            }
                            if(station["has_lpg"] == 1) {
                                station["has_lpg"] = '<i class="fas fa-check"></i>';
                            }
                            else {
                                station["has_lpg"] = '<i class="fas fa-times"></i>';
                            }
                            $("#fetched_list tbody").append('<tr id="station_'+station["id"]+'"><td>'+station["id"]+'</td><td>'+station["name"]+'</td><td class="gasoline">'+station["has_gasoline"]+'</td><td class="diesel">'+station["has_diesel"]+'</td><td class="lpg">'+station["has_lpg"]+'</td><td><a target="_blank" rel="noopener noreferrer" href="/?lat='+station["long"]+'&long='+station["lat"]+'">Ver no Mapa</a></td><td class="action">'+station["actions"]+'</td></tr>');
                        });
                        datatable = $('#fetched_list').DataTable();
                        $('[data-toggle="tooltip"]').tooltip(); 
                        $("#table").show();
                    }
                    else {
                        alert('Wrong Credentials');
                    }
                }, "json");
            }, "json");
        }
    </script>
</body>

</html>