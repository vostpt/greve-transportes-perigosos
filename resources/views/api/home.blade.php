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
    <h1 class="text-center" id="header_text"><img src="/img/VOSTPT_ROUND_FULL-COLOR.png" style="width: 1em;margin-top: -10px;" />VOSTPT - Já Não Dá Para Abastecer  - API v1</h1>
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
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Confirmar Ação - <span id="action_title">Titulo da ação</span></h5>
                </div>
                <div class="modal-body">
                    <p><span id="action_description">Descrição da ação</span></p>
                    @csrf
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
                        <a href="#" onclick="updateFuelStaion()" class="btn btn-primary">Confirmar</a>
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
        function modifyFuelStation(id,has_gasoline,has_diesel,has_lpg) {
            $('#action_title').html("Modificar Estação nº"+id);
            $('#action_description').html("Esta ação irá editar o a estacão nº"+id+".");
            $("#station_id").val(id);
            $("#station_has_gasoline").val(has_gasoline);
            $("#station_has_diesel").val(has_diesel);
            $("#station_has_lpg").val(has_lpg);
            $('.modal').modal('show');
        }
        function updateFuelStaion() {
            let id = $("#station_id").val();
            let has_gasoline = $("#station_has_gasoline").val();
            let has_diesel = $("#station_has_diesel").val();
            let has_lpg = $("#station_has_lpg").val();
            $('.modal').modal('hide');
            $.post( "/api/v1/push", { key: key, secret: secret, id: id, has_gasoline: has_gasoline, has_diesel: has_diesel, has_lpg: has_lpg }, function( data ) {
                if(data["success"] == 1) {
                    if(has_gasoline == 1) {
                        $("#station_"+id+' .gasoline').html('<i class="fas fa-check"></i>');
                    }
                    else {
                        $("#station_"+id+' .gasoline').html('<i class="fas fa-times"></i>');
                    }
                    if(has_diesel == 1) {
                        $("#station_"+id+' .diesel').html('<i class="fas fa-check"></i>');
                    }
                    else {
                        $("#station_"+id+' .diesel').html('<i class="fas fa-times"></i>');
                    }
                    if(has_lpg == 1) {
                        $("#station_"+id+' .lpg').html('<i class="fas fa-check"></i>');
                    }
                    else {
                        $("#station_"+id+' .lpg').html('<i class="fas fa-times"></i>');
                    }
                    $("#station_"+id+' .action').html('<a href="#" onclick="modifyFuelStation('+id+','+has_gasoline+','+has_diesel+','+has_lpg+')"><i class="fas fa-edit"></i></a>');
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
                $("#header_text").html(text);
                $.post( "/api/v1/fetch", { key: key, secret: secret }, function( data ) {
                    if(data != []) {
                        $("#fetcher").hide();
                        data.forEach(station => {
                            station["actions"] = '<a href="#" onclick="modifyFuelStation('+station["id"]+','+station["has_gasoline"]+','+station["has_diesel"]+','+station["has_lpg"]+')"><i class="fas fa-edit"></i></a>';
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
                            $("#fetched_list tbody").append('<tr id="station_'+station["id"]+'"><td>'+station["id"]+'</td><td>'+station["name"]+'</td><td class="gasoline">'+station["has_gasoline"]+'</td><td class="diesel">'+station["has_diesel"]+'</td><td class="lpg">'+station["has_lpg"]+'</td><td><a target="_blank" rel="noopener noreferrer" href="/?lat='+station["lat"]+'&long='+station["long"]+'">Ver no Mapa</a></td><td class="action">'+station["actions"]+'</td></tr>');
                        });
                        $('#fetched_list').DataTable();
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