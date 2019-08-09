@extends('layouts.app')
@section('styles')
@endsection

@section('content')
<div class="container">
    <div style="text-align: center">
        <img src="/img/VOSTPT_FuelCrisisPT_JNDPA_Logo_With_VOSTPT_Logo_800pxX800px.png" style="width:20em;" />
    </div>
    <p>Disponibilizamos aos media e aos outros utilizadores as seguintes ferramentas para integrarem os nossos dados noutras plataformas.</p>
    <p>Também disponibilizamos ficheiros .json para o processamento de dados, para tal contacte <a href="mailto:hello@vost.pt">hello@vost.pt</a></p>
    <hr/>
    <h2>Mapa de Consulta</h2>
    <div class="accordion" id="map" style="text-align: left;">
        <div class="card">
            <div class="card-header" id="what_it_is">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#what_it_is_collapse" aria-expanded="false" aria-controls="what_it_is_collapse"
                        style="font-weight: bold; color:black;">
                        O que é?
                    </button>
                </h2>
            </div>
            <div id="what_it_is_collapse" class="collapse" aria-labelledby="what_it_is" data-parent="#map">
                <div class="card-body">
                    <h2>Integração do Mapa de Consulta</h2>
                    <p>Permite o uso do mapa de consulta em qualquer outra plataforma.</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="example">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#example_collapse" aria-expanded="false" aria-controls="example_collapse"
                        style="font-weight: bold; color:black;">
                        Exemplo
                    </button>
                </h2>
            </div>
            <div id="example_collapse" class="collapse" aria-labelledby="example" data-parent="#map">
                <div class="card-body">
                    <h2>Exemplo</h2>
                    <iframe style="width:100%;min-height:60vh;border: 0;"
                        src="https://janaodaparaabastecer.vost.pt/"></iframe>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="usage">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#usage_collapse" aria-expanded="false" aria-controls="usage_collapse"
                        style="font-weight: bold; color:black;">
                        Código
                    </button>
                </h2>
            </div>
            <div id="usage_collapse" class="collapse" aria-labelledby="usage" data-parent="#map">
                <div class="card-body">
                    <h2>HTML</h2>
                    <code>
                        &lt;iframe style=&quot;width:100%;min-height:60vh;border: 0;&quot; src=&quot;https://janaodaparaabastecer.vost.pt/&quot;&gt;&lt;/iframe&gt;
                    </code>
                </div>
            </div>
        </div>
    </div>
    <hr />
    <h2>Estatísticas Globais</h2>
    <div class="accordion" id="global_stats" style="text-align: left;">
        <div class="card">
            <div class="card-header" id="global_stats_what_it_is">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#global_stats_what_it_is_collapse" aria-expanded="false" aria-controls="global_stats_what_it_is_collapse"
                        style="font-weight: bold; color:black;">
                        O que é?
                    </button>
                </h2>
            </div>
            <div id="global_stats_what_it_is_collapse" class="collapse" aria-labelledby="global_stats_what_it_is" data-parent="#global_stats">
                <div class="card-body">
                    <h2>Integração de Estatísticas Globais</h2>
                    <p>Permite o uso das estatísticas globais em qualquer outra plataforma.</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="global_stats_example">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#global_stats_example_collapse" aria-expanded="false" aria-controls="global_stats_example_collapse"
                        style="font-weight: bold; color:black;">
                        Exemplo
                    </button>
                </h2>
            </div>
            <div id="global_stats_example_collapse" class="collapse" aria-labelledby="global_stats_example" data-parent="#global_stats">
                <div class="card-body">
                    <h2>Exemplo</h2>
                    <style>
                        .vost-media-iframe {
                            width: 100%;
                        }
                        @media (min-width: 768px) {
                            .vost-media-iframe {
                                height: 400px;
                            }
                        }

                        @media  (max-width: 768px) {
                            .vost-media-iframe {
                                height: 700px;
                            }
                        }
                    </style>
                    <iframe frameborder="0" scrolling="no" class="vost-media-iframe" src="https://janaodaparaabastecer.vost.pt/graphs/stats"></iframe>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="global_stats_usage">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#global_stats_usage_collapse" aria-expanded="false" aria-controls="global_stats_usage_collapse"
                        style="font-weight: bold; color:black;">
                        Código
                    </button>
                </h2>
            </div>
            <div id="global_stats_usage_collapse" class="collapse" aria-labelledby="global_stats_usage" data-parent="#global_stats">
                <div class="card-body">
                    <h2>HTML</h2>
                    <code>                        
                        &lt;style&gt; .vost-media-iframe { width: 100%; } @media (min-width: 768px) { .vost-media-iframe { height: 400px; } } @media (max-width: 768px) { .vost-media-iframe { height: 700px; } } &lt;/style&gt; &lt;iframe frameborder=&quot;0&quot; scrolling=&quot;no&quot; class=&quot;vost-media-iframe&quot; src=&quot;https://janaodaparaabastecer.vost.pt/graphs/stats&quot;&gt;&lt;/iframe&gt;
                    </code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
@endsection