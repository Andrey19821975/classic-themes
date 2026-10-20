"use strict";
/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunkmarkup_template"] = self["webpackChunkmarkup_template"] || []).push([["base_defaults_scripts_accordions_js"],{

/***/ "./base/defaults/scripts/accordions.js":
/*!*********************************************!*\
  !*** ./base/defaults/scripts/accordions.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _libs_live_dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./libs/live-dom */ \"./base/defaults/scripts/libs/live-dom.js\");\n/* harmony import */ var _libs_accessibility_accordion__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./libs/accessibility-accordion */ \"./base/defaults/scripts/libs/accessibility-accordion.js\");\n\n\n(0,_libs_live_dom__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('.accordion').firstShow(function () {\n  (0,_libs_accessibility_accordion__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this);\n});\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/accordions.js?");

/***/ }),

/***/ "./base/defaults/scripts/libs/accessibility-accordion.js":
/*!***************************************************************!*\
  !*** ./base/defaults/scripts/libs/accessibility-accordion.js ***!
  \***************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": function() { return /* binding */ accessibilityAccordion; }\n/* harmony export */ });\nfunction accessibilityAccordion(accordionNode) {\n  var buttonEl = accordionNode.querySelector('button[aria-expanded]');\n  var controlsId = buttonEl.getAttribute('aria-controls');\n  var contentEl = document.getElementById(controlsId);\n  var state = buttonEl.getAttribute('aria-expanded') === 'true';\n  function toggle(newState) {\n    if (newState === state) {\n      return;\n    }\n    state = newState;\n    buttonEl.setAttribute('aria-expanded', \"\".concat(state));\n    if (state) {\n      contentEl.removeAttribute('hidden');\n      accordionNode.classList.add('accordion_active');\n    } else {\n      contentEl.setAttribute('hidden', '');\n      accordionNode.classList.remove('accordion_active');\n    }\n  }\n  function onButtonClick() {\n    toggle(!state);\n  }\n  function init() {\n    buttonEl.addEventListener('click', onButtonClick);\n  }\n  init();\n}\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/libs/accessibility-accordion.js?");

/***/ })

}]);