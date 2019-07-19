@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin="" />
<style>
    #mapid {
        height: 800px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1 class="text-center">Ainda Dá Para Abastecer?</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div id="mapid"></div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
    integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
    crossorigin=""></script>
<script>
    var mymap = L.map('mapid', {center: [39.557191, -7.8536599], zoom: 7});
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: 'pk.eyJ1IjoiY290ZW1lcm8iLCJhIjoiY2p5NzQyeTdvMDc1MzNlbGNnbzh3NjVuOCJ9.cPrQc61yiHA0kOptuuZsSA'
    }).addTo(mymap);
    $.getJSON( "/storage/data/cache.json", function( data ) {
        console.log(data);
        data.forEach(fuelStation => {
            console.log([fuelStation.lat, fuelStation.long]);
            L.marker([fuelStation.lat, fuelStation.long]).addTo(mymap);
        });
    });

</script>
@endsection