@extends('layouts.app')
@section('styles')
@endsection

@section('content')
<div class="container">
    <div style="text-align: center">
        <img src="/img/VOSTPT_FuelCrisisPT_JNDPA_Logo_With_VOSTPT_Logo_800pxX800px.png" style="width:20em;" />
    </div>
    <p>Disponibilizamos aos media e aos outros utilizadores as seguintes ferramentas para integrarem os nossos dados
        noutras plataformas.</p>
    <p>Também disponibilizamos ficheiros .json para o processamento de dados, para tal contacte <a
            href="mailto:hello@vost.pt">hello@vost.pt</a></p>
    <hr />
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
                        data-target="#global_stats_what_it_is_collapse" aria-expanded="false"
                        aria-controls="global_stats_what_it_is_collapse" style="font-weight: bold; color:black;">
                        O que é?
                    </button>
                </h2>
            </div>
            <div id="global_stats_what_it_is_collapse" class="collapse" aria-labelledby="global_stats_what_it_is"
                data-parent="#global_stats">
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
                        data-target="#global_stats_example_collapse" aria-expanded="false"
                        aria-controls="global_stats_example_collapse" style="font-weight: bold; color:black;">
                        Exemplo
                    </button>
                </h2>
            </div>
            <div id="global_stats_example_collapse" class="collapse" aria-labelledby="global_stats_example"
                data-parent="#global_stats">
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

                        @media (max-width: 768px) {
                            .vost-media-iframe {
                                height: 800px;
                            }
                        }
                    </style>
                    <iframe frameborder="0" scrolling="no" class="vost-media-iframe"
                        src="https://janaodaparaabastecer.vost.pt/graphs/stats"></iframe>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="global_stats_usage">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#global_stats_usage_collapse" aria-expanded="false"
                        aria-controls="global_stats_usage_collapse" style="font-weight: bold; color:black;">
                        Código
                    </button>
                </h2>
            </div>
            <div id="global_stats_usage_collapse" class="collapse" aria-labelledby="global_stats_usage"
                data-parent="#global_stats">
                <div class="card-body">
                    <h2>HTML</h2>
                    <code>
                        &lt;style&gt; .vost-media-iframe { width: 100%; } @media (min-width: 768px) { .vost-media-iframe { height: 400px; } } @media (max-width: 768px) { .vost-media-iframe { height: 800px; } } &lt;/style&gt; &lt;iframe frameborder=&quot;0&quot; scrolling=&quot;no&quot; class=&quot;vost-media-iframe&quot; src=&quot;https://janaodaparaabastecer.vost.pt/graphs/stats&quot;&gt;&lt;/iframe&gt;
                    </code>
                </div>
            </div>
        </div>
    </div>
    <h2>ARCGIS</h2>
    <div class="accordion" id="arcgis" style="text-align: left;">
        <div class="card">
            <div class="card-header" id="arcgis_what_it_is">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#arcgis_what_it_is_collapse" aria-expanded="false"
                        aria-controls="arcgis_what_it_is_collapse" style="font-weight: bold; color:black;">
                        O que é?
                    </button>
                </h2>
            </div>
            <div id="arcgis_what_it_is_collapse" class="collapse" aria-labelledby="arcgis_what_it_is"
                data-parent="#arcgis">
                <div class="card-body">
                    <h2>O que é?</h2>
                    <p>Uma dashboard em tempo real dos dados recolhidos no site "Já Não Dá Para Abastecer", feita em
                        ArcGIS com o apoio da ESRI Portugal.</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="arcgis_conditions">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#arcgis_conditions_collapse" aria-expanded="false"
                        aria-controls="arcgis_conditions_collapse" style="font-weight: bold; color:black;">
                        Termos e Condições de Uso
                    </button>
                </h2>
            </div>
            <div id="arcgis_conditions_collapse" class="collapse" aria-labelledby="arcgis_conditions"
                data-parent="#arcgis">
                <div class="card-body">
                    <h2>Termos e Condições de Uso</h2>
                    <p>Full Credits a VOSTPT / Esri Portugal / ArcGis.</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="arcgis_example">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#arcgis_example_collapse" aria-expanded="false"
                        aria-controls="arcgis_example_collapse" style="font-weight: bold; color:black;">
                        Exemplo
                    </button>
                </h2>
            </div>
            <div id="arcgis_example_collapse" class="collapse" aria-labelledby="arcgis_example" data-parent="#arcgis">
                <div class="card-body">
                    <h2>Exemplo</h2>
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
                    <div class="embed-container"><iframe width="500" height="400" frameborder="0" scrolling="no"
                            marginheight="0" marginwidth="0" title="BASEMAP"
                            src="https://arcgis.com/apps/opsdashboard/index.html#/a4890bfd60df4c07bd67c857b73b452f"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="arcgis_usage">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#arcgis_usage_collapse" aria-expanded="false"
                        aria-controls="arcgis_usage_collapse" style="font-weight: bold; color:black;">
                        Código
                    </button>
                </h2>
            </div>
            <div id="arcgis_usage_collapse" class="collapse" aria-labelledby="arcgis_usage"
                data-parent="#global_stats">
                <div class="card-body">
                    <h2>HTML</h2>
                    <code>
                        &lt;style&gt;.embed-container {position: relative; padding-bottom: 80%; height: 0; max-width: 100%;} .embed-container iframe, .embed-container object, .embed-container iframe{position: absolute; top: 0; left: 0; width: 100%; height: 100%;} small{position: absolute; z-index: 40; bottom: 0; margin-bottom: -15px;}&lt;/style&gt;&lt;div class=&quot;embed-container&quot;&gt;&lt;iframe width=&quot;500&quot; height=&quot;400&quot; frameborder=&quot;0&quot; scrolling=&quot;no&quot; marginheight=&quot;0&quot; marginwidth=&quot;0&quot; title=&quot;BASEMAP&quot; src=&quot;https://arcgis.com/apps/opsdashboard/index.html#/a4890bfd60df4c07bd67c857b73b452f&quot;&gt;&lt;/iframe&gt;&lt;/div&gt;
                    </code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
@endsection