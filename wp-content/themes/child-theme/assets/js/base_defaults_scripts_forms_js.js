"use strict";
/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunkmarkup_template"] = self["webpackChunkmarkup_template"] || []).push([["base_defaults_scripts_forms_js"],{

/***/ "./base/defaults/scripts/forms.js":
/*!****************************************!*\
  !*** ./base/defaults/scripts/forms.js ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _libs_live_dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./libs/live-dom */ \"./base/defaults/scripts/libs/live-dom.js\");\n\n(0,_libs_live_dom__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('textarea,input').onceInit(function () {\n  return __webpack_require__.e(/*! import() */ \"base_defaults_scripts_libs_toggle-focus_js\").then(__webpack_require__.bind(__webpack_require__, /*! ./libs/toggle-focus */ \"./base/defaults/scripts/libs/toggle-focus.js\")).then(function (_ref) {\n    var toggleFocus = _ref.toggleFocus;\n    return toggleFocus('.form__item');\n  });\n});\n(0,_libs_live_dom__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('[data-optimisation-gf-form-id], .gfield').onceInit(function () {\n  return Promise.all(/*! import() */[__webpack_require__.e(\"vendors-node_modules_css-loader_dist_runtime_api_js-node_modules_css-loader_dist_runtime_noSo-767ea2\"), __webpack_require__.e(\"base_defaults_scripts_forms_gravity-forms_js\")]).then(__webpack_require__.bind(__webpack_require__, /*! ./forms/gravity-forms */ \"./base/defaults/scripts/forms/gravity-forms.js\"));\n});\n(0,_libs_live_dom__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('textarea,input').onceInit(function () {\n  return __webpack_require__.e(/*! import() */ \"base_defaults_scripts_forms_disabled-visual-focus_js\").then(__webpack_require__.bind(__webpack_require__, /*! ./forms/disabled-visual-focus */ \"./base/defaults/scripts/forms/disabled-visual-focus.js\"));\n});\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/forms.js?");

/***/ })

}]);