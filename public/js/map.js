!function(e){var o={};function r(t){if(o[t])return o[t].exports;var s=o[t]={i:t,l:!1,exports:{}};return e[t].call(s.exports,s,s.exports,r),s.l=!0,s.exports}r.m=e,r.c=o,r.d=function(e,o,t){r.o(e,o)||Object.defineProperty(e,o,{enumerable:!0,get:t})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,o){if(1&o&&(e=r(e)),8&o)return e;if(4&o&&"object"==typeof e&&e&&e.__esModule)return e;var t=Object.create(null);if(r.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:e}),2&o&&"string"!=typeof e)for(var s in e)r.d(t,s,function(o){return e[o]}.bind(null,s));return t},r.n=function(e){var o=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(o,"a",o),o},r.o=function(e,o){return Object.prototype.hasOwnProperty.call(e,o)},r.p="/",r(r.s=1)}({1:function(e,o,r){e.exports=r("mUkR")},mUkR:function(e,o){var r=[],t=[],s=['Thanks to <a href="https://www.facebook.com/WazePortugal/">Waze Portugal</a> for providing important data and permission to use their services','This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply'],i={obj:null},a={obj:null},l=["gasoline","diesel","lpg","none","without_gasoline","without_diesel","without_lpg"],n=["normal","sos","none"];mapboxgl.accessToken="pk.eyJ1Ijoidm9zdHB0IiwiYSI6ImNqeXR3aHQxdTAyYjgzY21wbDMwaHJoaDQifQ.ql-IskzjOdAtEFvbltquaw";var p=new mapboxgl.Map({container:"map",style:"mapbox://styles/mapbox/streets-v11",center:[-7.8536599,39.557191],zoom:6,attributionControl:!1});function c(e,o){return new Promise(function(r,t){p.loadImage(o,function(o,s){o?(console.log("ERROR:"+o),t(o)):(console.log("IMAGE LOADED"),p.addImage(e,s),r())})})}function d(){var e=new Date,o=e.getSeconds(),r=e.getMinutes(),t=e.getHours(),i=[].concat(s);return i.push("Última Atualização às: "+("0"+t).slice(-2)+"h"+("0"+r).slice(-2)+"m"+("0"+o).slice(-2)+"s"),i}function u(){var e=arguments.length>0&&void 0!==arguments[0]&&arguments[0];t.push(new Promise(function(e,o){r=[],$.getJSON("/storage/data/cache.json",function(o){o.forEach(function(e){var o=0,t=0,s=0,i=0,a=0,l=0,n=0,p="",c="",d="",u=0,g=e.brand,v="",m=0,f=0;e.sell_gasoline&&(f++,e.has_gasoline?(o=1,m++):a=1),e.sell_diesel&&(f++,e.has_diesel?(t=1,m++):l=1),e.sell_lpg&&(f++,e.has_lpg?(s=1,m++):n=1),"SOS"==e.repa||"Normal"==e.repa?("SOS"==e.repa?(e.repa="sos",u=2,c="0070bb",d="e6e6e6",g+=" (REPA - Veículos Prioritários)",u=2,v="<strong>Posto REPA - Prioritários</strong>"):"Normal"==e.repa&&(e.repa="normal",u=1,c="0070bb",d="e6e6e6",g+=" (REPA - Todos os Veículos)",u=1,v="<strong>Posto REPA - Geral</strong>"),p=m==f?"REPA":0==m?"REPA_NONE":"REPA_PARTIAL"):(v="<strong>Posto Não REPA</strong>",m==f?(p="ALL",c="006837",d="e6e6e6"):0==m?(p="NONE",c="c1272c",d="e6e6e6"):(p="PARTIAL",c="f7921e",d="e6e6e6")),m==f?v+="<p>Disponível</p>":0==m?(v+="<p>Não Disponível</p>",i=1):v+="<p>Parcialmente Disponível</p>","POSTO ES"==e.brand&&(p="SPAIN",v="<strong>Posto Espanhol</strong>"),r.push({type:"Feature",geometry:{type:"Point",coordinates:[e.lat,e.long]},properties:{id:e.id,name:e.name,brand:g,repa:e.repa,with_gasoline:o,with_diesel:t,with_lpg:s,without_gasoline:a,without_diesel:l,without_lpg:n,with_none:i,sell_gasoline:e.sell_gasoline,sell_diesel:e.sell_diesel,sell_lpg:e.sell_lpg,has_gasoline:e.has_gasoline,has_diesel:e.has_diesel,has_lpg:e.has_lpg,icon:p,popup_color:c,background_color:d,priority:u,tooltip:v}})}),e()})})),Promise.all(t).then(function(){t=[],e||(n.forEach(function(e){l.forEach(function(o){var r="poi-"+e+"-"+o;p.removeLayer(r)})}),p.removeSource("points")),p.addSource("points",{type:"geojson",data:{type:"FeatureCollection",features:r}}),n.forEach(function(o){var r=o;"none"==r&&(r=""),l.forEach(function(t){var s="poi-"+o+"-"+t;p.addLayer({id:s,type:"symbol",source:"points",layout:{"icon-image":"{icon}","symbol-sort-key":["get","priority"],"icon-allow-overlap":!0}}),-1==t.indexOf("without")?p.setFilter(s,["all",["==","with_"+t,1],["==","repa",r]]):p.setFilter(s,["all",["==",t,1],["==","repa",r]]),e&&function(e){p.on("click",e,function(e){var o=e.features[0].geometry.coordinates.slice(),r=e.features[0].properties.sell_gasoline&&e.features[0].properties.has_gasoline?'<img src="/img/map/VOSTPT_FUELCRISIS_GASOLINA_500pxX500px.png"/>':'<img class="no-gas"src="/img/map/VOSTPT_FUELCRISIS_GASOLINA_500pxX500px.png"/>',t=e.features[0].properties.sell_diesel&&e.features[0].properties.has_diesel?'<img src="/img/map/VOSTPT_FUELCRISIS_GASOLEO_500pxX500px.png"/>':'<img class="no-gas" src="/img/map/VOSTPT_FUELCRISIS_GASOLEO_500pxX500px.png"/>',s=e.features[0].properties.sell_lpg&&e.features[0].properties.has_lpg?'<img width="75px" src="/img/map/VOSTPT_FUELCRISIS_GPL_500pxX500px.png"/>':'<img class="no-gas" src="/img/map/VOSTPT_FUELCRISIS_GPL_500pxX500px.png"/>',i=e.features[0].properties.name?e.features[0].properties.name.toUpperCase():"",a="",l="";for(isHelping()?(l="",e.features[0].properties.sell_gasoline&&(l+='<div class="col-md v-fuel-info gasoline"><a href="#" onclick="swapIcon(this)">'+r+"</a><h6>GASOLINA</h6></div>"),e.features[0].properties.sell_diesel&&(l+='<div class="col-md v-fuel-info diesel"><a href="#" onclick="swapIcon(this)">'+t+"</a><h6>GASÓLEO</h6></div>"),e.features[0].properties.sell_lpg&&(l+='<div class="col-md v-fuel-info lpg"><a href="#" onclick="swapIcon(this)">'+s+"</a><h6>GPL</h6></div>")):(l="",e.features[0].properties.sell_gasoline&&(l+='<div class="col-md v-fuel-info">'+r+"<h6>GASOLINA</h6></div>"),e.features[0].properties.sell_diesel&&(l+='<div class="col-md v-fuel-info">'+t+"<h6>GASÓLEO</h6></div>"),e.features[0].properties.sell_lpg&&(l+='<div class="col-md v-fuel-info">'+s+"<h6>GPL</h6></div>")),isHelping()?(a='<div class="v-popup-content"><div class="v-popup-header" style="background-color:#85d5f8; text-align: center;"><h5>ADICIONAR INFORMAÇÃO</h5></div><div class="v-popup-body" style="background-color:#b8e1f8"><div class="row">'+l+'</div><img src="/img/map/separation.png" style="width: calc(100% + 1.6em); margin-left:-0.8em;" />',"Prio"==e.features[0].properties.brand?a+='<div class="row"><div class="col-md"><b>AS DISPONIBILIDADES DAS BOMBAS DA PRIO</b></div></div><div class="row"><div class="col-md"><b>LISTADAS NESTE SITE ESTÃO A SER GERIDAS</b></div></div><div class="row"><div class="col-md"><b><a target="_blank" rel="noopener noreferrer" href="https://www.prio.pt/pt/">PELA PRÓPRIA PRIO</a></b></div></div>':"OZ Energia"==e.features[0].properties.brand?a+='<div class="row"><div class="col-md"><b>AS DISPONIBILIDADES DAS BOMBAS DA OZ ENERGIA</b></div></div><div class="row"><div class="col-md"><b>LISTADAS NESTE SITE ESTÃO A SER GERIDAS</b></div></div><div class="row"><div class="col-md"><b><a target="_blank" rel="noopener noreferrer" href="https://www.ozenergia.pt/">PELA PRÓPRIA OZ ENERGIA</a></b></div></div>':"Ecobrent"==e.features[0].properties.brand?a+='<div class="row"><div class="col-md"><b>AS DISPONIBILIDADES DAS BOMBAS DA ECOBRENT</b></div></div><div class="row"><div class="col-md"><b>LISTADAS NESTE SITE ESTÃO A SER GERIDAS</b></div></div><div class="row"><div class="col-md"><b><a target="_blank" rel="noopener noreferrer" href="https://www.ecobrent.pt/">PELA PRÓPRIA ECOBRENT</a></b></div></div>':"Bxpress"==e.features[0].properties.brand?a+='<div class="row"><div class="col-md"><b>AS DISPONIBILIDADES DAS BOMBAS DA BXPRESS</b></div></div><div class="row"><div class="col-md"><b>LISTADAS NESTE SITE ESTÃO A SER GERIDAS</b></div></div><div class="row"><div class="col-md"><b><a target="_blank" rel="noopener noreferrer" href="https://www.bongasenergias.pt/">PELA PRÓPRIA BXPRESS</a></b></div></div>':"TFuel"==e.features[0].properties.brand||972==e.features[0].properties.id||1549==e.features[0].properties.id?a+='<div class="row"><div class="col-md"><b>AS DISPONIBILIDADES DAS BOMBAS DA TFUEL</b></div></div><div class="row"><div class="col-md"><b>LISTADAS NESTE SITE ESTÃO A SER GERIDAS</b></div></div><div class="row"><div class="col-md"><b><a target="_blank" rel="noopener noreferrer" href="https://www.bongasenergias.pt/">PELA PRÓPRIA TFUEL</a></b></div></div>':a+='<div class="row"><div class="col-md"><b>POR FAVOR INDICA QUE COMBUSTÍVEIS NÃO ESTÃO</b></div></div><div class="row"><div class="col-md"><b>DISPONÍVEIS NA '+i+'.</b></div></div><div class="row"><div class="col-md"><b>CARREGA NAS IMAGENS.</b></div></div>',a+='</div><div class="v-popup-header" style="padding:0;background-color:#85d5f8"><div class="row" style="margin:0;"><div class="col-3"><a href="/error/edit?id='+e.features[0].properties.id+'"><img src="/img/map/VOSTPT_FUELCRISIS_REPORT_500pxX500px.png" style="height:2.5em;margin-top: 1.5vh;" /></a></div>',"Prio"==e.features[0].properties.brand?a+='<div class="col-9"><a target="_blank" rel="noopener noreferrer" href="https://www.prio.pt/pt/" style="margin:1.5vh"><h5  style="margin-right: 1.5vh;" class="popup_submit_text">PRIO</h5></a></div>':a+='<div class="col-9"><a href="#" onclick="submitEntry(this,'+e.features[0].properties.id+')"  style="margin:1.5vh"><h5  style="margin-right: 1.5vh;" class="popup_submit_text">VALIDAR</h5></a></div>',a+="</div></div></div>"):a='<div class="v-popup-content"><div class="v-popup-header" style="background-color: #'+e.features[0].properties.popup_color+'"><h5>'+e.features[0].properties.brand.toUpperCase()+"<br><small>"+i+'</small></h5></div><div class="v-popup-body" style="background-color: #'+e.features[0].properties.background_color+'"><div class="row">'+l+'</div></div><div class="v-popup-body directions"><a href="https://www.waze.com/ul?ll='+o[1]+"%2C"+o[0]+'&navigate=yes&zoom=16&download_prompt=false"  target="_blank" rel="noopener noreferrer"><img src="/img/map/map_separation_'+e.features[0].properties.background_color+'.png" style="width: 100%;" /></a></div><div class="v-popup-header" style="padding:0;background-color: #'+e.features[0].properties.popup_color+'"><div class="row" style="margin:0;"><div class="col-3"><a href="/error/edit?id='+e.features[0].properties.id+'"><img src="/img/map/VOSTPT_FUELCRISIS_REPORT_500pxX500px.png" style="height:2.5em;margin-top: 1.5vh;" /></a></div><div class="col-9"><a href="https://www.waze.com/ul?ll='+o[1]+"%2C"+o[0]+'&navigate=yes&zoom=16&download_prompt=false" style="margin:1.5vh"><h5 style="margin-right: 1.5vh;">OBTER DIREÇÕES</h5></a></div></div></div></div>';Math.abs(e.lngLat.lng-o[0])>180;)o[0]+=e.lngLat.lng>o[0]?360:-360;p.flyTo({center:[o[0],o[1]+.008],zoom:13}),null!=popup&&popup.isOpen()&&popup.remove(),popup=new mapboxgl.Popup({className:"mapboxgl-popup-info"}).setLngLat(o).setHTML(a).addTo(p)});var o=new mapboxgl.Popup({closeButton:!1,closeOnClick:!1,className:"mapboxgl-popup-tooltip"});p.on("mouseenter",e,function(e){p.getCanvas().style.cursor="pointer";for(var r=e.features[0].geometry.coordinates.slice(),t=e.features[0].properties.tooltip;Math.abs(e.lngLat.lng-r[0])>180;)r[0]+=e.lngLat.lng>r[0]?360:-360;o.setLngLat(r).setHTML(t).addTo(p)}),p.on("mouseleave",e,function(){p.getCanvas().style.cursor="",o.remove()})}(s)})}),p.removeControl(a.obj),p.removeControl(i.obj),delete i.obj,i.obj=new mapboxgl.AttributionControl({compact:!0,customAttribution:d()}),p.addControl(i.obj),p.addControl(a.obj,"bottom-right"),g()})}function g(){var e=$("input.type[type=radio]:checked").val(),o=[],r=$('input[name="fuel_stations_repa[]"]:checked');Object.values(r).forEach(function(e){var r=e.value;r&&o.push(r)}),n.forEach(function(r){l.forEach(function(t){var s="poi-"+r+"-"+t,i=o.includes(r)&&(t==e||"all"==e);p.setLayoutProperty(s,"visibility",i?"visible":"none")})})}String.prototype.capitalize=function(){return this.replace(/(?:^|\s)\S/g,function(e){return e.toUpperCase()})},p.on("load",function(){$(".mapboxgl-ctrl-logo").css("float","left"),$(".mapboxgl-ctrl-bottom-left .mapboxgl-ctrl").append('<a style="cursor: pointer;" target="_blank" rel="noopener nofollow"  href="https://twitter.com/vostpt"><img src="/img/VOSTPT_LETTERING_COLOR.png" style="height: 42px; margin-top: -15px; margin-left: 10px;"/></a>'),p.addControl(new MapboxGeocoder({accessToken:mapboxgl.accessToken,language:"pt-PT",mapboxgl:mapboxgl,marker:!1,filter:function(e){return e.context.map(function(e){return"country"===e.id.split(".").shift()&&"Portugal"===e.text}).reduce(function(e,o){return e||o})}})),t.push(c("REPA","/img/map/VOSTPT_JNDPA_REPA_ICON_25x25.png")),t.push(c("NONE","/img/map/VOSTPT_JNDPA_NONE_ICON_25x25.png")),t.push(c("PARTIAL","/img/map/VOSTPT_JNDPA_PARTIAL_ICON_25x25.png")),t.push(c("ALL","/img/map/VOSTPT_JNDPA_ALL_ICON_25x25.png")),t.push(c("REPA_PARTIAL","/img/map/VOSTPT_JNDPA_REPA_NORMAL_PARCIAL.png")),t.push(c("REPA_NONE","/img/map/VOSTPT_JNDPA_REPA_NORMAL_SEM.png")),t.push(c("SPAIN","/img/map/VOSTPT_JNDPA_ESPANHA_25px.png")),Promise.all(t).then(function(){t=[],u(!0);var e=findGetParameter("lat"),o=findGetParameter("long");null!=e&&null!=o?p.flyTo({center:[e,o],zoom:13}):"geolocation"in navigator&&navigator.geolocation.getCurrentPosition(function(e){p.flyTo({center:[e.coords.longitude,e.coords.latitude],zoom:13})}),i.obj=new mapboxgl.AttributionControl({compact:!0,customAttribution:d()}),p.addControl(i.obj),a.obj=new mapboxgl.NavigationControl({visualizePitch:!0,showZoom:!0,showCompass:!0}),p.addControl(a.obj,"bottom-right"),setInterval(u,3e4)})}),p.on("error",function(e){console.log("MAP LOAD ERROR"),console.log(e)}),$("input.type[type=radio]").change(function(){g()}),$('input[name="fuel_stations_repa[]"]').change(function(){g()})}});