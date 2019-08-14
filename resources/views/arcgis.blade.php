@extends('layouts.app')

@section('viewport')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('styles')
<style>
    .embed-container {
        position: relative;
        padding-bottom: 80%;
        height: 0;
        max-width: 100%;
    }

    .embed-container iframe,
    .embed-container object,
    .embed-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    small {
        position: absolute;
        z-index: 40;
        bottom: 0;
        margin-bottom: -15px;
    }
</style>
@endsection

@section('content')
<div class="container text-center">
    <img src="/img/VOSTPT_FuelCrisisPT_JNDPA_Logo_With_VOSTPT_Logo_800pxX800px.png" style="width:20em" />
    <div class="embed-container">
        <iframe width="500" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" title="BASEMAP"
            src="https://arcgis.com/apps/opsdashboard/index.html#/a4890bfd60df4c07bd67c857b73b452f"></iframe>
    </div>
</div>
@endsection

@section('javascript')
@endsection