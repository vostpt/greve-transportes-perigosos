let places = {};
function updateStats() {
    document.getElementById('global_stats').src = "/graphs/stats";
    document.getElementById('brand_stats').src = "/graphs/brands";

    $.getJSON( "/storage/data/places.json", (data) => {
        places = data;
        Object.keys(data).forEach(district => {
            $("#district_selection").append("<option value=\""+district+"\">"+district+"</option>")
        });
    });
}

$('#district_selection').on('change', function (e) {
    let valueSelected = this.value;
    if(valueSelected == "none") {
        $("#county_selection").prop('disabled', true);
        $("#county_selection").html("<option value=\"none\">Todos</option>");
    }
    else {
        document.getElementById('selected_stats').src = "/graphs/stats?distrito="+encodeURI(valueSelected);
        $("#county_selection").prop('disabled', false);
        $("#county_selection").html("<option value=\"none\">Todos</option>");
        places[valueSelected].forEach(county => {
            $("#county_selection").append("<option value=\""+county+"\">"+county+"</option>");
        });
    }
});

$('#county_selection').on('change', function (e) {
    let valueSelected = this.value;
    let district = $("#district_selection").val();
    if(valueSelected == "none") {
        document.getElementById('selected_stats').src = "/graphs/stats?distrito="+encodeURI();
    }
    else {
        document.getElementById('selected_stats').src = "/graphs/stats?distrito="+encodeURI(district)+'&concelho='+encodeURI(valueSelected);
    }
});


updateStats();
setInterval(updateStats,30000);
