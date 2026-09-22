@extends('layouts.app')
@section('content')
    <div class="container my-4">
        <div class="card shadow-sm"><div class="card-body"><canvas id="stations-chart" height="220"></canvas></div></div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/graphs.js'])
@endpush
