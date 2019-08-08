@extends('layouts.app')
@section('styles')
@endsection

@section('content')
<div class="container">
    <div class="text-center">
        <img src="/img/VOSTPT_FuelCrisisPT_JNDPA_Logo_With_VOSTPT_Logo_800pxX800px.png" style="width:20em" />
    </div>
    <h2>Reporte adicionado ou editado!</h2>
@endsection

@section('javascript')
<script src="https://www.google.com/recaptcha/api.js?render=6LcD9rAUAAAAAIn4-wNkOpAmr49ItnAZnBtroGCX"></script>
<script>
    grecaptcha.ready(function () {
    grecaptcha.execute('6LcD9rAUAAAAAIn4-wNkOpAmr49ItnAZnBtroGCX', { action: 'contact' }).then(function (token) {
        var recaptchaResponse = document.getElementById('recaptchaResponse');
        recaptchaResponse.value = token;
    });
});
</script>
@endsection
