!function(e){var t={};function o(n){if(t[n])return t[n].exports;var a=t[n]={i:n,l:!1,exports:{}};return e[n].call(a.exports,a,a.exports,o),a.l=!0,a.exports}o.m=e,o.c=t,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)o.d(n,a,function(t){return e[t]}.bind(null,a));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="/",o(o.s=3)}({3:function(e,t,o){e.exports=o("F6g4")},F6g4:function(e,t){window.findGetParameter=function(e){var t=null,o=[];return location.search.substr(1).split("&").forEach(function(n){(o=n.split("="))[0]===e&&(t=decodeURIComponent(o[1]))}),t},window.querystring=function(e){for(var t,o=[],n=new RegExp("(?:\\?|&)"+e+"=(.*?)(?=&|$)","gi");null!==(t=n.exec(document.location.search));)o.push(t[1]);return o},window.searchParam=function(e){return new URLSearchParams(window.location.search).get(e)},window.Get=function(e){var t=new XMLHttpRequest;return t.open("GET",e,!1),t.send(null),t.responseText},window.renderChartsGlobalStats=function(e){var t=JSON.parse(Get(e));google.charts.load("current",{packages:["corechart"]}),google.charts.setOnLoadCallback(function(){var e=new google.visualization.DataTable;e.addColumn("string","Combustivel"),e.addColumn("number","Postos"),e.addRows([["Todos",t.stations_all],["Parte",t.stations_partial],["Nenhum",t.stations_none]]);var o=Object.assign({pieHole:.2,chartArea:{top:50,height:"300px"},height:300,legend:{position:"top",alignment:"center"},pieSliceText:"value-and-percentage",tooltip:{ignoreBounds:!0},sliceVisibilityThreshold:0},{colors:["#8BC34A","#f6bd00","#f62317"],backgroundColor:{fill:"transparent"}});new google.visualization.PieChart(document.getElementById("stations-chart-area")).draw(e,o),document.getElementById("stations_total_number").innerHTML=t.stations_total}),drawChartsFuelTypes(t,"gasoline-chart-area","diesel-chart-area","lpg-chart-area")},window.renderChartsBrand=function(e){e&&drawChartsFuelTypes(e,"gasoline-chart-area-brand","diesel-chart-area-brand","lpg-chart-area-brand")},window.drawChartsFuelTypes=function(e,t,o,n){google.charts.load("current",{packages:["corechart"]}),google.charts.setOnLoadCallback(function(){var a=e.stations_sell_gasoline-e.stations_no_gasoline,r=e.stations_sell_diesel-e.stations_no_diesel,i=e.stations_sell_lpg-e.stations_no_lpg,l={legend:{position:"top",alignment:"left"},tooltip:{ignoreBounds:!0},bar:{groupWidth:"50%"},isStacked:!0,top:0,height:130,hAxis:{textPosition:"none"}},s=google.visualization.arrayToDataTable([["Combustivel","Esgotado",{role:"annotation"},"Vende",{role:"annotation"},{role:"style"}],["Gasolina",e.stations_no_gasoline,e.stations_no_gasoline,a,a,"#AAAE43"]]),u=Object.assign(l,{colors:["#f62317","#AAAE43"]});new google.visualization.BarChart(document.getElementById(t)).draw(s,u);var c=google.visualization.arrayToDataTable([["Combustivel","Esgotado",{role:"annotation"},"Vende",{role:"annotation"},{role:"style"}],["Gasoleo",e.stations_no_diesel,e.stations_no_diesel,r,r,"#DB6E3E"]]),d=Object.assign(l,{colors:["#f62317","#DB6E3E"]});new google.visualization.BarChart(document.getElementById(o)).draw(c,d);var g=google.visualization.arrayToDataTable([["Combustivel","Esgotado",{role:"annotation"},"Vende",{role:"annotation"},{role:"style"}],["GPL",e.stations_no_lpg,e.stations_no_lpg,i,i,"3D8CB1"]]),p=Object.assign(l,{colors:["#f62317","#3D8CB1"]});new google.visualization.BarChart(document.getElementById(n)).draw(g,p)})}}});