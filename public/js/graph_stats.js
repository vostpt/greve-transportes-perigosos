/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/graph_stats.js":
/*!*************************************!*\
  !*** ./resources/js/graph_stats.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function findGetParameter(parameterName) {
  var result = null,
      tmp = [];
  location.search.substr(1).split("&").forEach(function (item) {
    tmp = item.split("=");
    if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
  });
  return result;
}

function Get(yourUrl) {
  var Httpreq = new XMLHttpRequest(); // a new request

  Httpreq.open("GET", yourUrl, false);
  Httpreq.send(null);
  return Httpreq.responseText;
}

window.onload = function () {
  var district = findGetParameter("distrito");
  var county = findGetParameter("concelho");
  var url = "";

  if (district) {
    if (county) {
      url = "/storage/data/stats_" + encodeURI(district) + "_" + encodeURI(county) + ".json";
    } else {
      county = null;
      url = "/storage/data/stats_" + encodeURI(district) + ".json";
    }
  } else {
    district = "Portugal";
    county = null;
    url = "/storage/data/stats_global.json";
  }

  charts(url);
};

function charts(dataSourceUri) {
  var data = JSON.parse(Get(dataSourceUri));
  google.charts.load("current", {
    packages: ["corechart"]
  });
  google.charts.setOnLoadCallback(function () {
    var dataTable1 = new google.visualization.DataTable();
    dataTable1.addColumn('string', 'Combustivel');
    dataTable1.addColumn('number', 'Postos');
    dataTable1.addRows([['Todos', data.stations_all], ['Parte', data.stations_partial], ['Nenhum', data.stations_none]]);
    var options = {
      pieHole: 0.2,
      chartArea: {
        top: 50,
        height: "300px"
      },
      height: 300,
      legend: {
        position: "top",
        alignment: "center"
      },
      pieSliceText: 'value-and-percentage',
      tooltip: {
        ignoreBounds: true
      },
      sliceVisibilityThreshold: 0
    };
    var optionsChart1 = Object.assign(options, {
      colors: ['#8BC34A', '#f6bd00', '#f62317'],
      backgroundColor: {
        fill: 'transparent'
      }
    });
    var chart1 = new google.visualization.PieChart(document.getElementById('stations-chart-area'));
    chart1.draw(dataTable1, optionsChart1);
    document.getElementById('stations_total_number').innerHTML = data["stations_total"];
    var hasGasoline = data.stations_sell_gasoline - data.stations_no_gasoline;
    var hasDiesel = data.stations_sell_diesel - data.stations_no_diesel;
    var hasLpg = data.stations_sell_lpg - data.stations_no_lpg; //
    // GASOLINE
    //

    var barOptions = {
      legend: {
        position: "top",
        alignment: "left"
      },
      tooltip: {
        ignoreBounds: true
      },
      sliceVisibilityThreshold: 0,
      bar: {
        groupWidth: '50%'
      },
      isStacked: true,
      top: 0,
      height: 130,
      hAxis: {
        textPosition: 'none'
      }
    };
    var dataTable2 = google.visualization.arrayToDataTable([['Combustivel', 'Esgotado', {
      role: 'annotation'
    }, 'Vende', {
      role: 'annotation'
    }, {
      role: 'style'
    }], ['Gasolina', data.stations_no_gasoline, data.stations_no_gasoline, hasGasoline, hasGasoline, '#AAAE43']]);
    var optionsChart2 = Object.assign(barOptions, {
      colors: ['#f62317', '#AAAE43']
    });
    var chart2 = new google.visualization.BarChart(document.getElementById('gasoline-chart-area'));
    chart2.draw(dataTable2, optionsChart2); //
    // DIESEL
    //

    var dataTable3Diesel = google.visualization.arrayToDataTable([['Combustivel', 'Esgotado', {
      role: 'annotation'
    }, 'Vende', {
      role: 'annotation'
    }, {
      role: 'style'
    }], ['Gasoleo', data.stations_no_diesel, data.stations_no_diesel, hasDiesel, hasDiesel, '#DB6E3E']]);
    var optionsChart3 = Object.assign(barOptions, {
      colors: ['#f62317', '#DB6E3E']
    });
    var chart3lpg = new google.visualization.BarChart(document.getElementById('diesel-chart-area'));
    chart3lpg.draw(dataTable3Diesel, optionsChart3); //
    // LPG
    //

    var dataTable4 = google.visualization.arrayToDataTable([['Combustivel', 'Esgotado', {
      role: 'annotation'
    }, 'Vende', {
      role: 'annotation'
    }, {
      role: 'style'
    }], ['GPL', data.stations_no_lpg, data.stations_no_lpg, hasLpg, hasLpg, '3D8CB1']]);
    var optionsChart4 = Object.assign(barOptions, {
      colors: ['#f62317', '#3D8CB1']
    });
    var chart4lpg = new google.visualization.BarChart(document.getElementById('lpg-chart-area'));
    chart4lpg.draw(dataTable4, optionsChart4);
  });
}

/***/ }),

/***/ 3:
/*!*******************************************!*\
  !*** multi ./resources/js/graph_stats.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/cvarandas/Projects/vostpt/greve-transportes-perigosos/resources/js/graph_stats.js */"./resources/js/graph_stats.js");


/***/ })

/******/ });