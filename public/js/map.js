!function(e){var o={};function t(r){if(o[r])return o[r].exports;var i=o[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,t),i.l=!0,i.exports}t.m=e,t.c=o,t.d=function(e,o,r){t.o(e,o)||Object.defineProperty(e,o,{enumerable:!0,get:r})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,o){if(1&o&&(e=t(e)),8&o)return e;if(4&o&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(t.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&o&&"string"!=typeof e)for(var i in e)t.d(r,i,function(o){return e[o]}.bind(null,i));return r},t.n=function(e){var o=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(o,"a",o),o},t.o=function(e,o){return Object.prototype.hasOwnProperty.call(e,o)},t.p="/",t(t.s=1)}({1:function(e,o,t){e.exports=t("mUkR")},mUkR:function(e,o){var t=[],r=[],i=['Thanks to <a href="https://www.facebook.com/WazePortugal/">Waze Portugal</a> for providing important data and permission to use their services','This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply'],a={obj:null},s={obj:null},n=["gasoline","diesel","lpg","none","without_gasoline","without_diesel","without_lpg"],l=["normal","sos","none"];mapboxgl.accessToken="pk.eyJ1Ijoidm9zdHB0IiwiYSI6ImNqeXR3aHQxdTAyYjgzY21wbDMwaHJoaDQifQ.ql-IskzjOdAtEFvbltquaw";var p=new mapboxgl.Map({container:"map",style:"mapbox://styles/mapbox/streets-v11",center:[-7.8536599,39.557191],zoom:6,attributionControl:!1});function c(e,o){return new Promise(function(t,r){p.loadImage(o,function(o,i){o?(console.log("ERROR:"+o),r(o)):(console.log("IMAGE LOADED"),p.addImage(e,i),t())})})}function u(){var e=new Date,o=e.getSeconds(),t=e.getMinutes(),r=e.getHours(),a=[].concat(i);return a.push("Última Atualização às: "+("0"+r).slice(-2)+"h"+("0"+t).slice(-2)+"m"+("0"+o).slice(-2)+"s"),a}function d(){var e=arguments.length>0&&void 0!==arguments[0]&&arguments[0];r.push(new Promise(function(e,o){t=[],$.getJSON("/storage/data/cache.json",function(o){o.forEach(function(e){var o=0,r=0,i=0,a=0,s=0,n=0,l=0,p="",c="",u="",d=0,g=e.brand,m="",f=0,v=0;e.sell_gasoline&&(v++,e.has_gasoline?(o=1,f++):s=1),e.sell_diesel&&(v++,e.has_diesel?(r=1,f++):n=1),e.sell_lpg&&(v++,e.has_lpg?(i=1,f++):l=1),"SOS"==e.repa?(e.repa="sos",p="REPA",d=2,c="0070bb",u="e6e6e6",g+=" (REPA - Veículos Prioritários)",d=2,m="<strong>Posto REPA - Prioritários</strong>"):"Normal"==e.repa?(e.repa="normal",p="REPA",d=1,c="0070bb",u="e6e6e6",g+=" (REPA - Todos os Veículos)",d=1,m="<strong>Posto REPA - Geral</strong>"):(m="<strong>Posto Não REPA</strong>",f==v?(p="ALL",c="006837",u="e6e6e6"):0==f?(p="NONE",c="c1272c",u="e6e6e6"):(p="PARTIAL",c="f7921e",u="e6e6e6")),f==v?m+="<p>Disponível</p>":0==f?(m+="<p>Não Disponível</p>",a=1):m+="<p>Parcialmente Disponível</p>",t.push({type:"Feature",geometry:{type:"Point",coordinates:[e.long,e.lat]},properties:{id:e.id,name:e.name,brand:g,repa:e.repa,with_gasoline:o,with_diesel:r,with_lpg:i,without_gasoline:s,without_diesel:n,without_lpg:l,with_none:a,sell_gasoline:e.sell_gasoline,sell_diesel:e.sell_diesel,sell_lpg:e.sell_lpg,has_gasoline:e.has_gasoline,has_diesel:e.has_diesel,has_lpg:e.has_lpg,icon:p,popup_color:c,background_color:u,priority:d,tooltip:m}})}),e()})})),Promise.all(r).then(function(){r=[],e||(l.forEach(function(e){n.forEach(function(o){var t="poi-"+e+"-"+o;p.removeLayer(t)})}),p.removeSource("points")),p.addSource("points",{type:"geojson",data:{type:"FeatureCollection",features:t}}),l.forEach(function(o){var t=o;"none"==t&&(t=""),n.forEach(function(r){var i="poi-"+o+"-"+r;p.addLayer({id:i,type:"symbol",source:"points",layout:{"icon-image":"{icon}","symbol-sort-key":["get","priority"]}}),-1==r.indexOf("without")?p.setFilter(i,["all",["==","with_"+r,1],["==","repa",t]]):p.setFilter(i,["all",["==",r,1],["==","repa",t]]),e&&function(e){p.on("click",e,function(e){var o=e.features[0].geometry.coordinates.slice(),t=e.features[0].properties.sell_gasoline&&e.features[0].properties.has_gasoline?'<img src="/img/map/VOSTPT_FUELCRISIS_GASOLINA_500pxX500px.png"/>':'<img class="no-gas"src="/img/map/VOSTPT_FUELCRISIS_GASOLINA_500pxX500px.png"/>',r=e.features[0].properties.sell_diesel&&e.features[0].properties.has_diesel?'<img src="/img/map/VOSTPT_FUELCRISIS_GASOLEO_500pxX500px.png"/>':'<img class="no-gas" src="/img/map/VOSTPT_FUELCRISIS_GASOLEO_500pxX500px.png"/>',i=e.features[0].properties.sell_lpg&&e.features[0].properties.has_lpg?'<img width="75px" src="/img/map/VOSTPT_FUELCRISIS_GPL_500pxX500px.png"/>':'<img class="no-gas" src="/img/map/VOSTPT_FUELCRISIS_GPL_500pxX500px.png"/>',a=e.features[0].properties.name?e.features[0].properties.name.toUpperCase():"",s="",n="";for(isHelping()?(n="",e.features[0].properties.sell_gasoline&&(n+='<div class="col-md v-fuel-info gasoline"><a href="#" onclick="swapIcon(this)">'+t+"</a><h6>GASOLINA</h6></div>"),e.features[0].properties.sell_diesel&&(n+='<div class="col-md v-fuel-info diesel"><a href="#" onclick="swapIcon(this)">'+r+"</a><h6>GASÓLEO</h6></div>"),e.features[0].properties.sell_lpg&&(n+='<div class="col-md v-fuel-info lpg"><a href="#" onclick="swapIcon(this)">'+i+"</a><h6>GPL</h6></div>")):(n="",e.features[0].properties.sell_gasoline&&(n+='<div class="col-md v-fuel-info">'+t+"<h6>GASOLINA</h6></div>"),e.features[0].properties.sell_diesel&&(n+='<div class="col-md v-fuel-info">'+r+"<h6>GASÓLEO</h6></div>"),e.features[0].properties.sell_lpg&&(n+='<div class="col-md v-fuel-info">'+i+"<h6>GPL</h6></div>")),s=isHelping()?'<div class="v-popup-content"><div class="v-popup-header" style="background-color:#85d5f8; text-align: center;"><h5>ADICIONAR INFORMAÇÃO</h5></div><div class="v-popup-body" style="background-color:#b8e1f8"><div class="row">'+n+'</div><img src="/img/map/separation.png" style="width: calc(100% + 1.6em); margin-left:-0.8em;" /><div class="row"><div class="col-md"><b>POR FAVOR INDICA QUE COMBUSTÍVEIS NÃO ESTÃO</b></div></div><div class="row"><div class="col-md"><b>DISPONÍVEIS NA '+a+'.</b></div></div><div class="row"><div class="col-md"><b>CARREGA NAS IMAGENS.</b></div></div></div><div class="v-popup-header" style="padding:0;background-color:#85d5f8"><div class="row" style="margin:0;"><div class="col-3"><a href="/error/edit?id='+e.features[0].properties.id+'"><img src="/img/map/VOSTPT_FUELCRISIS_REPORT_500pxX500px.png" style="height:2.5em;margin-top: 1.5vh;" /></a></div><div class="col-9"><a href="#" onclick="submitEntry(this,'+e.features[0].properties.id+')"  style="margin:1.5vh"><h5  style="margin-right: 1.5vh;" class="popup_submit_text">VALIDAR</h5></a></div></div></div></div>':'<div class="v-popup-content"><div class="v-popup-header" style="background-color: #'+e.features[0].properties.popup_color+'"><h5>'+e.features[0].properties.brand.toUpperCase()+"<br><small>"+a+'</small></h5></div><div class="v-popup-body" style="background-color: #'+e.features[0].properties.background_color+'"><div class="row">'+n+'</div></div><div class="v-popup-body directions"><a href="https://www.waze.com/ul?ll='+o[1]+"%2C"+o[0]+'&navigate=yes&zoom=16&download_prompt=false"  target="_blank" rel="noopener noreferrer"><img src="/img/map/map_separation_'+e.features[0].properties.background_color+'.png" style="width: 100%;" /></a></div><div class="v-popup-header" style="padding:0;background-color: #'+e.features[0].properties.popup_color+'"><div class="row" style="margin:0;"><div class="col-3"><a href="/error/edit?id='+e.features[0].properties.id+'"><img src="/img/map/VOSTPT_FUELCRISIS_REPORT_500pxX500px.png" style="height:2.5em;margin-top: 1.5vh;" /></a></div><div class="col-9"><a href="https://www.waze.com/ul?ll='+o[1]+"%2C"+o[0]+'&navigate=yes&zoom=16&download_prompt=false" style="margin:1.5vh"><h5 style="margin-right: 1.5vh;">OBTER DIREÇÕES</h5></a></div></div></div></div>';Math.abs(e.lngLat.lng-o[0])>180;)o[0]+=e.lngLat.lng>o[0]?360:-360;p.flyTo({center:[o[0],o[1]+.008],zoom:13}),popup=new mapboxgl.Popup({className:"mapboxgl-popup-info"}).setLngLat(o).setHTML(s).addTo(p)});var o=new mapboxgl.Popup({closeButton:!1,closeOnClick:!1,className:"mapboxgl-popup-tooltip"});p.on("mouseenter",e,function(e){p.getCanvas().style.cursor="pointer";for(var t=e.features[0].geometry.coordinates.slice(),r=e.features[0].properties.tooltip;Math.abs(e.lngLat.lng-t[0])>180;)t[0]+=e.lngLat.lng>t[0]?360:-360;o.setLngLat(t).setHTML(r).addTo(p)}),p.on("mouseleave",e,function(){p.getCanvas().style.cursor="",o.remove()})}(i)})}),p.removeControl(s.obj),p.removeControl(a.obj),delete a.obj,a.obj=new mapboxgl.AttributionControl({compact:!0,customAttribution:u()}),p.addControl(a.obj),p.addControl(s.obj,"bottom-right"),g()})}function g(){var e=$("input.type[type=radio]:checked").val(),o=[],t=$('input[name="fuel_stations_repa[]"]:checked');Object.values(t).forEach(function(e){var t=e.value;t&&o.push(t)}),l.forEach(function(t){n.forEach(function(r){var i="poi-"+t+"-"+r,a=o.includes(t)&&(r==e||"all"==e);p.setLayoutProperty(i,"visibility",a?"visible":"none")})})}String.prototype.capitalize=function(){return this.replace(/(?:^|\s)\S/g,function(e){return e.toUpperCase()})},p.on("load",function(){$(".mapboxgl-ctrl-logo").css("float","left"),$(".mapboxgl-ctrl-bottom-left .mapboxgl-ctrl").append('<a style="cursor: pointer;" target="_blank" rel="noopener nofollow"  href="https://twitter.com/vostpt"><img src="/img/VOSTPT_LETTERING_COLOR.png" style="height: 42px; margin-top: -15px; margin-left: 10px;"/></a>'),p.addControl(new MapboxGeocoder({accessToken:mapboxgl.accessToken,language:"pt-PT",mapboxgl:mapboxgl,marker:!1,filter:function(e){return e.context.map(function(e){return"country"===e.id.split(".").shift()&&"Portugal"===e.text}).reduce(function(e,o){return e||o})}})),r.push(c("REPA","/img/map/VOSTPT_JNDPA_REPA_ICON_25x25.png")),r.push(c("NONE","/img/map/VOSTPT_JNDPA_NONE_ICON_25x25.png")),r.push(c("PARTIAL","/img/map/VOSTPT_JNDPA_PARTIAL_ICON_25x25.png")),r.push(c("ALL","/img/map/VOSTPT_JNDPA_ALL_ICON_25x25.png")),Promise.all(r).then(function(){r=[],d(!0),"geolocation"in navigator&&navigator.geolocation.getCurrentPosition(function(e){p.flyTo({center:[e.coords.longitude,e.coords.latitude],zoom:13})}),a.obj=new mapboxgl.AttributionControl({compact:!0,customAttribution:u()}),p.addControl(a.obj),s.obj=new mapboxgl.NavigationControl({visualizePitch:!0,showZoom:!0,showCompass:!0}),p.addControl(s.obj,"bottom-right"),setInterval(d,3e4)})}),p.on("error",function(e){console.log("MAP LOAD ERROR"),console.log(e)}),$("input.type[type=radio]").change(function(){g()}),$("input.repa[type=checkbox]").change(function(){g()})}});