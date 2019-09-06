@extends('layouts.app')
@section('styles')
@endsection

@section('content')
<div class="container text-center">
    <img src="/img/VOSTPT_FuelCrisisPT_JNDPA_Logo_With_VOSTPT_Logo_800pxX800px.png" style="width:20em" />
    <div class="accordion" id="about" style="text-align: left;">
        <div class="card">
            <div class="card-header" id="tandc">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#tandc_collapse" aria-expanded="false" aria-controls="tandc_collapse" style="font-weight: bold; color:black;">
                        Como disponibilizar a minha localização em web browsers
                    </button>
                </h2>
            </div>
            <div id="tandc_collapse" class="collapse show" aria-labelledby="tandc" data-parent="#about">
                <div class="card-body">
                    <h3>Chrome</h3>
                    <p>Na parte superior direita, clique em Mais Mais e, em seguida, Definições.<br>
                        Na parte inferior, clique em Avançadas.<br>
                        Em "Privacidade e segurança", clique em Definições de sites.<br>
                        Clique em Localização. <br>
                        Ative ou desative a opção Perguntar antes de aceder.
                    </p>
                    <p>Mais informações : <a href="https://support.google.com/chrome/answer/142065?hl=pt" target="_blank">https://support.google.com/chrome/answer/142065?hl=pt</a></p>
                    <hr>
                    <h3>Firefox</h3>
                    <p>Definições > Menu menu e escolha Preferências <br>
                        Utilize a caixa de pesquisa das preferências para procurar por "localização". <br>
                        Pesquisar por Localização <br>
                        Escolha Localização Definições na secção Permissões.<br>
                        Reveja ou altere a lista de sites em que autorizou as permissões de localização.<br>
                    </p>
                    <p>Mais informações : <a href="https://support.mozilla.org/pt-PT/kb/firefox-partilha-minha-localizacao-com-websites" target="_blank">https://support.mozilla.org/pt-PT/kb/firefox-partilha-minha-localizacao-com-websites</a></p>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
@endsection
