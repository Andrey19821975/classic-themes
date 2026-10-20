"use strict";
/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunkmarkup_template"] = self["webpackChunkmarkup_template"] || []).push([["src_scripts_maps_js"],{

/***/ "./base/defaults/scripts/libs/map.js":
/*!*******************************************!*\
  !*** ./base/defaults/scripts/libs/map.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   libraries: function() { return /* binding */ libraries; },\n/* harmony export */   map: function() { return /* binding */ map; }\n/* harmony export */ });\n/* harmony import */ var _live_dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./live-dom */ \"./base/defaults/scripts/libs/live-dom.js\");\n/* harmony import */ var _dom_ready__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./dom-ready */ \"./base/defaults/scripts/libs/dom-ready.js\");\n\n\nvar googleMapsLoaded = 'not-init';\nvar libraries = [];\nfunction dependency(done, error) {\n  if (theme.googleMapsApiKey) {\n    if ('not-init' === googleMapsLoaded) {\n      googleMapsLoaded = 'progress';\n      var params = [\"key=\".concat(theme.googleMapsApiKey)];\n      if (libraries.length) {\n        params.push(\"libraries=\".concat(libraries.join(',')));\n      }\n      var script = document.createElement('script');\n      var src = 'https://maps.googleapis.com/maps/api/js?' + params.join('&');\n      script.setAttribute('src', src);\n      script.async = true;\n      script.onload = function () {\n        googleMapsLoaded = 'done';\n        done();\n      };\n      script.onerror = function () {\n        googleMapsLoaded = 'error';\n        error();\n      };\n      document.body.appendChild(script);\n    } else if ('progress' === googleMapsLoaded) {\n      setTimeout(function () {\n        return dependency(done, error);\n      }, 500);\n    } else if ('done' === googleMapsLoaded) {\n      done();\n    } else {\n      error();\n    }\n  } else {\n    // eslint-disable-next-line no-console\n    console.error('There is a map on the page with no API key configured.');\n    error();\n  }\n}\nfunction map(selector, callback) {\n  (0,_dom_ready__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(function () {\n    (0,_live_dom__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(selector, {\n      dependency,\n      firstShow: callback\n    });\n  });\n}\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/libs/map.js?");

/***/ }),

/***/ "./src/scripts/maps.js":
/*!*****************************!*\
  !*** ./src/scripts/maps.js ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _base_defaults_scripts_libs_map__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../base/defaults/scripts/libs/map */ \"./base/defaults/scripts/libs/map.js\");\n// noinspection JSCheckFunctionSignatures, JSUnresolvedVariable, JSUnresolvedFunction, JSUnresolvedVariable\n\n\n(0,_base_defaults_scripts_libs_map__WEBPACK_IMPORTED_MODULE_0__.map)('[data-map=\"demo\"]', function () {\n  /**\n   * @type {HTMLDivElement}\n   */\n  var mapCanvas = this;\n  var position = new google.maps.LatLng(mapCanvas.dataset.latitude, mapCanvas.dataset.longitude);\n  var markerIcon = mapCanvas.dataset.icon;\n  var mapZoom = +mapCanvas.dataset.zoom;\n  var mapObject = new google.maps.Map(mapCanvas, {\n    zoom: mapZoom || 17,\n    center: position,\n    disableDefaultUI: true\n  });\n  new google.maps.Marker({\n    position,\n    mapObject,\n    icon: markerIcon || ''\n  });\n});\n\n//# sourceURL=webpack://markup-template/./src/scripts/maps.js?");

/***/ })

}]);