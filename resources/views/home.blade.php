@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="text-center">Dashboard</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="fa fa-pencil-alt fa-3x float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 id="entries-card-total">0</h3>
                                <span>Total Submissões</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="fa fa-pencil-alt fa-3x float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 id="entries-card-hour">0</h3>
                                <span>Subsmissões Ultima hora</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="fa fa-pencil-alt fa-3x float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 id="entries-card-day">0</h3>
                                <span>Subsmissões Ultimo Dia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="form-group">
                <label for="district_selection">Distrito:</label>
                <select class="form-control" id="district_selection">
                    <option value="none">Todos</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="county_selection">Concelho:</label>
                <select class="form-control" id="county_selection" disabled>
                    <option value="none">Todos</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Disponibilidade combustível por postos ( <span id="stations_total_number">0</span> )
                </div>
                <div class="card-body" style="overflow: hidden;">
                    <div id="stations-chart-area"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Faltas por tipo combustível
                </div>
                <div class="card-body" style="overflow: hidden;">
                    <div id="gasoline-chart-area" ></div>
                    <div id="diesel-chart-area" ></div>
                    <div id="lpg-chart-area" ></div>
                </div>
            </div>
        </div>
    </div>
    <hr class="mt-5">
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Registo de submissões no JNDPA ( Ultimas 12 horas )
                </div>
                <div class="card-body" style="overflow: hidden;">
                    <div id="entries-last12-hours" ></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Total de Postos Registados no JNDPA ( <span id="brand_stations_total_number">0</span> )
                </div>
                <div class="card-body" style="overflow: hidden;">
                    <div class="form-group">
                        <label for="brand_selection">Marca:</label>
                        <select class="form-control" id="brand_selection">
                            <option value="none">Todas</option>
                        </select>
                    </div>
                    <div id="gasoline-chart-area-brand" ></div>
                    <div id="diesel-chart-area-brand" ></div>
                    <div id="lpg-chart-area-brand" ></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{ mix('/js/helpers.js') }}" charset="utf-8"></script>
    <script src="{{ mix('/js/graphs.js') }}" charset="utf-8"></script>
    <script type="text/javascript">

        let dataSourceGlobalUri = "/storage/data/stats_global.json";
        let brandsDataSourceUri = "/storage/data/stats_brands.json";
        let entriesLast12HoursUri = "/storage/data/stats_entries_last12.json";
        let $countyEl = $("#county_selection");
        let $districtEl = $("#district_selection");
        let $brandEl = $('#brand_selection');
        let places = [];

        $.getJSON( "/storage/data/places.json", (data) => {
            places = data;
            Object.keys(places).forEach(district => {
                $districtEl.append("<option value=\""+district+"\">"+district+"</option>")
            });
        });

        window.onload = function(){
            let district = sessionStorage.getItem('district');
            let county = sessionStorage.getItem('county');
            let dataSourceUri = sessionStorage.getItem('data_source_uri');
            if(dataSourceUri === null){
                $districtEl.val('none').trigger('change');
                dataSourceUri = dataSourceGlobalUri;
                sessionStorage.setItem('data_source_uri', dataSourceUri);
            }else{
                if(
                    (district !== null || district !== 'none')
                    &&
                    (county !== null || county !== 'none')
                ){
                    $districtEl.val(district).trigger('change');
                    $countyEl.val(county).trigger('change');
                }else{
                    $districtEl.val('none').trigger('change');
                }
            }
            let ds = sessionStorage.getItem('data_source_uri');
            renderChartsGlobalStats(ds);

            getEntries();
            let defaultBrand = 'Prio';
            let data = JSON.parse(Get(brandsDataSourceUri));
            // Populate the dropdown list for brands
            Object.keys(data).forEach((brand) => {
                if(brand === defaultBrand){
                    $brandEl.append("<option value=\""+brand+"\" selected>"+brand+" - Dados Oficiais</option>");
                }else{
                    $brandEl.append("<option value=\""+brand+"\">"+brand+"</option>");
                }
            });
            renderChartsBrand(data[defaultBrand]);
            renderEntriesLast12Hours(entriesLast12HoursUri)
        };

        setInterval(function () {
            getEntries();
            let ds = sessionStorage.getItem('data_source_uri');
            renderChartsGlobalStats(ds);

            let data = JSON.parse(Get(brandsDataSourceUri));
            let valueSelected = sessionStorage.getItem('brand');
            renderChartsBrand(data[valueSelected]);
            renderEntriesLast12Hours(entriesLast12HoursUri);
        }, 30000);

        $districtEl.on('change', function (e) {
            let valueSelected = this.value;
            if(valueSelected === "none") {
                $countyEl.prop('disabled', true);
                $countyEl.html("<option value=\"none\">Todos</option>");
                sessionStorage.setItem('district', valueSelected);
                sessionStorage.setItem('county', valueSelected);
                sessionStorage.setItem('data_source_uri', dataSourceGlobalUri);
            }else {
                let dataSourceUri = "/storage/data/stats_" + encodeURI(valueSelected) + ".json";
                $countyEl.prop('disabled', false);
                $countyEl.html("<option value=\"none\">Todos</option>");
                places[valueSelected].forEach(county => {
                    $("#county_selection").append("<option value=\""+county+"\">"+county+"</option>");
                });
                sessionStorage.setItem('district', valueSelected);
                sessionStorage.setItem('data_source_uri', dataSourceUri);
            }
        });

        $('#county_selection').on('change', function (e) {
            let valueSelected = this.value;
            if(valueSelected !== 'none'){
                let district = $districtEl.val();
                let dataSourceUri = "/storage/data/stats_" + encodeURI(district) + ".json";
                if(valueSelected !== "none") {
                    dataSourceUri = "/storage/data/stats_" + encodeURI(district) + "_" + encodeURI(valueSelected) + ".json"
                }
                sessionStorage.setItem('county', valueSelected);
                sessionStorage.setItem('data_source_uri', dataSourceUri);
                renderChartsGlobalStats(dataSourceUri);
            }
        });

        $brandEl.on('change', function (e) {
            let valueSelected = this.value;
            let data = JSON.parse(Get(brandsDataSourceUri));
            sessionStorage.setItem('brand', valueSelected);
            renderChartsBrand(data[valueSelected]);
        });

        function getEntries(){
            $.getJSON('/storage/data/stats_entries.json').then((data) => {
                $('#entries-card-total').text(data.entries_total);
                $('#entries-card-day').text(data.entries_last_day);
                $('#entries-card-hour').text(data.entries_last_hour);
            });
        }
    </script>

@endsection
