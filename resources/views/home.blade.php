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
                    Disponibilidade combustível por postos
                </div>
                <div class="card-body" style="overflow: hidden; max-height: 300px">
                    <div id="chart_container_1"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Faltas por tipo combustível
                </div>
                <div class="card-body" style="overflow: hidden; max-height: 300px;">
                    <div id="chart_container_2" ></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        let dataSourceGlobalUri = "/storage/data/stats_global.json";
        let $countyEl = $("#county_selection");
        let $districtEl = $("#district_selection");

        window.onload = function(){
            let district = sessionStorage.getItem('district');
            let county = sessionStorage.getItem('county');
            let dataSourceUri = sessionStorage.getItem('data_source_uri');
            if(dataSourceUri === null && district === null && county === null){
                $districtEl.val('none').trigger('change');
                dataSourceUri = dataSourceGlobalUri;
            }else{
                $districtEl.val(district).trigger('change');
                $countyEl.val(county).trigger('change');
            }
            sessionStorage.setItem('data_source_uri', dataSourceUri);
            getEntries();
            charts(dataSourceUri);
        };

        setInterval(function () {
            getEntries();
            let ds = sessionStorage.getItem('data_source_uri');
            charts(ds);
        }, 30000);

        $.getJSON( "/storage/data/places.json", (data) => {
            places = data;
            Object.keys(data).forEach(district => {
                $districtEl.append("<option value=\""+district+"\">"+district+"</option>")
            });
        });

        $districtEl.on('change', function (e) {
            let valueSelected = this.value;
            if(valueSelected === "none") {
                $countyEl.prop('disabled', true);
                $countyEl.html("<option value=\"none\">Todos</option>");
                sessionStorage.setItem('data_source_uri', dataSourceGlobalUri);
                charts(dataSourceGlobalUri);
            }else {
                let dataSourceUri = "/storage/data/stats_" + encodeURI(valueSelected) + ".json";
                $countyEl.prop('disabled', false);
                $countyEl.html("<option value=\"none\">Todos</option>");
                places[valueSelected].forEach(county => {
                    $("#county_selection").append("<option value=\""+county+"\">"+county+"</option>");
                });
                sessionStorage.setItem('district', valueSelected);
                sessionStorage.setItem('data_source_uri', dataSourceUri);
                charts(dataSourceUri);
            }
        });

        $('#county_selection').on('change', function (e) {
            let valueSelected = this.value;
            let district = $districtEl.val();
            let dataSourceUri = "/storage/data/stats_" + encodeURI(district) + ".json";
            if(valueSelected !== "none") {
                dataSourceUri = "/storage/data/stats_" + encodeURI(district) + "_" + encodeURI(valueSelected) + ".json"
            }
            sessionStorage.setItem('county', valueSelected);
            sessionStorage.setItem('data_source_uri', dataSourceUri);
            charts(dataSourceUri);
        });

        function getEntries(){
            $.getJSON('/storage/data/stats_entries.json').then((data) => {
                $('#entries-card-total').text(data.entries_total);
                $('#entries-card-day').text(data.entries_last_day);
                $('#entries-card-hour').text(data.entries_last_hour);
            });
        }

        function charts(dataSourceUri){
            $.getJSON(dataSourceUri).then((data) => {

                google.charts.load("current", {packages:["corechart"]});
                google.charts.setOnLoadCallback(() => {
                    let dataTable1 = new google.visualization.DataTable();

                    dataTable1.addColumn('string', 'Combustivel');
                    dataTable1.addColumn('number', 'Postos');
                    dataTable1.addRows([
                        ['Todos', data.stations_all ],
                        ['Parte', data.stations_partial ],
                        ['Nenhum', data.stations_none ]
                    ]);

                    let options = {
                        pieHole: 0.2,
                        chartArea: {
                            top: 50,
                            height: "300px"
                        },
                        height: 300,
                        legend : {
                            position: "top",
                            alignment: "center",
                        },
                        pieSliceText: 'value-and-percentage',
                        tooltip: {
                            ignoreBounds:true
                        },
                        sliceVisibilityThreshold: 0
                    };

                    let optionsChart1 = Object.assign(options,{colors:['#8BC34A', '#f6bd00', '#f62317']});
                    let chart1 = new google.visualization.PieChart(document.getElementById('chart_container_1'));
                    chart1.draw(dataTable1, optionsChart1);

                    var dataTable2 = google.visualization.arrayToDataTable([
                        ['Combustivel','Esgotado',{ role: 'annotation'},'Vende',{ role: 'annotation'}],
                        ['Gasolina', data.stations_no_gasoline,data.stations_no_gasoline,data.stations_sell_gasoline,data.stations_sell_gasoline],
                        ['Gasoleo', data.stations_no_diesel,data.stations_no_diesel,data.stations_sell_diesel,data.stations_sell_diesel],
                        ['GPL', data.stations_no_lpg,data.stations_no_lpg,data.stations_sell_lpg,data.stations_sell_lpg]
                    ]);

                    let optionsChart2 = Object.assign(options,{legend: { position: 'top', maxLines: 3 },
                        bar: { groupWidth: '75%' },
                        isStacked: true,colors: ['#f62317','#8BC34A']});
                    let chart2 = new google.visualization.BarChart(document.getElementById('chart_container_2'));
                    chart2.draw(dataTable2, optionsChart2);
                });
            });
        }
    </script>

@endsection
