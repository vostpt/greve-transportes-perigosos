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
                        Termos e Condições
                    </button>
                </h2>
            </div>
            <div id="tandc_collapse" class="collapse" aria-labelledby="tandc" data-parent="#about">
                <div class="card-body">
                    <h2>Termos e Condições</h2>
                    <p>Os dados constantes na plataforma “Já Não Dá Para Abastecer” da VOST Portugal são compilados através de informação recebida através dos utilizadores da mesma.</p>
                    <p>Tratando-se de uma plataforma de crowdsourcing os dados são meramente indicativos e sofrem alterações constantemente. </p>
                    <p>A VOST Portugal efectuará todos os esforços para validar toda a informação que nos vai chegando.</p>
                    <p>Se é o responsável por uma ou mais bombas de gasolina e os dados que encontrar não estiverem corretos, a VOST Portugal disponibiliza o e-mail hello@vost.pt para proceder de imediato à rectificação da informação constante na nossa plataforma. </p>
                    <hr/>
                    <p>A VOST Portugal disponibiliza também aos grandes distribuidores e entidades oficiais o acesso a uma  API de modo a que possam inserir e corrigir a informação.</p>
                    <hr/>
                    <p>Este site usa cookies mas na VOST Portugal não vamos guardar qualquer informação nem vamos usar o teu dispositivo para minar bitcoins. Os cookies que usamos são utilizados para termos estatísticas de utilização do site, só e apenas. </p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="GDPR ">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#GDPR_collapse"
                        aria-expanded="false" aria-controls="GDPR_collapse" style="font-weight: bold; color:black;">
                        Regulamento Geral sobre a Proteção de Dados
                    </button>
                </h2>
            </div>
            <div id="GDPR_collapse" class="collapse" aria-labelledby="GDPR " data-parent="#about">
                <div class="card-body">
                    <h2>Regulamento Geral sobre a Proteção de Dados</h2>
                    <embed src="/pdf/VOST_PT_Política_de_Privacidade.pdf" style="width: 100%; height: 1000px" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="credits">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#credits_collapse"
                        aria-expanded="false" aria-controls="credits_collapse" style="font-weight: bold; color:black;">
                        Créditos
                    </button>
                </h2>
            </div>
            <div id="credits_collapse" class="collapse" aria-labelledby="credits" data-parent="#about">
                <div class="card-body">
                    <h2>Créditos</h2>
                    <p>Este site foi desenvolvido pela equipa da <a href="https://twitter.com/vostpt">VOST Portugal</a>.</p>
                    <p>Lead Developer: <a href="https://github.com/cotemero">Miguel Santos</a></p>
                    <p>Framework: <a href="https://laravel.com">Laravel</a></p>
                    <p>Todo o código usado pode ser encontrado no <a href="https://github.com/vostpt/greve-transportes-perigosos">nosso repositório no Github</a></p>
                    <p>Licença: <a href="https://github.com/vostpt/greve-transportes-perigosos/blob/master/LICENSE">MIT License</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
@endsection