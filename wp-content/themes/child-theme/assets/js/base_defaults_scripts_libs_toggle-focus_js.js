"use strict";
/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunkmarkup_template"] = self["webpackChunkmarkup_template"] || []).push([["base_defaults_scripts_libs_toggle-focus_js"],{

/***/ "./base/defaults/scripts/libs/toggle-focus.js":
/*!****************************************************!*\
  !*** ./base/defaults/scripts/libs/toggle-focus.js ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   toggleFocus: function() { return /* binding */ toggleFocus; }\n/* harmony export */ });\n/* harmony import */ var _live_dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./live-dom */ \"./base/defaults/scripts/libs/live-dom.js\");\n/* harmony import */ var _dom_ready__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./dom-ready */ \"./base/defaults/scripts/libs/dom-ready.js\");\n\n\nfunction toggle(inputElement, wrapperElement, className) {\n  var force = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;\n  setTimeout(function () {\n    wrapperElement.classList.toggle(\"\".concat(className, \"_in-focus-or-has-value\"), force || !!inputElement.value || !!inputElement.getAttribute('placeholder'));\n  });\n}\nfunction toggleForSelect2(select2, wrapperElement, className) {\n  var force = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;\n  setTimeout(function () {\n    wrapperElement.classList.toggle(\"\".concat(className, \"_in-focus-or-has-value\"), force || select2.querySelector('.select2-selection__rendered').hasAttribute('title'));\n  });\n  var observer = new MutationObserver(function (mutations) {\n    mutations.forEach(function (mutation) {\n      wrapperElement.classList.toggle(\"\".concat(className, \"_in-focus-or-has-value\"), force || mutation.target.hasAttribute('title'));\n    });\n  });\n  var config = {\n    attributes: true,\n    childList: false,\n    characterData: false\n  };\n  observer.observe(select2.querySelector('.select2-selection__rendered'), config);\n}\nfunction toggleFocus(wrapper) {\n  var parent = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';\n  (0,_dom_ready__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(function () {\n    (0,_live_dom__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(parent + ' ' + wrapper).init(function () {\n      var className = wrapper;\n      if (wrapper[0] === '.' || wrapper[0] === '#') {\n        className = className.slice(1);\n      }\n      var wrapperElement = this;\n      var inputElement = wrapperElement.querySelector('input, select, textarea');\n      wrapperElement.parentNode.addEventListener('reset', function () {\n        return toggle(inputElement, wrapperElement, className);\n      });\n      var select2Element = wrapperElement.querySelector('.select2');\n      if (inputElement) {\n        toggle(inputElement, wrapperElement, className);\n        inputElement.addEventListener('focus', function () {\n          return toggle(inputElement, wrapperElement, className, true);\n        });\n        inputElement.addEventListener('blur', function () {\n          return toggle(inputElement, wrapperElement, className);\n        });\n      }\n      if (select2Element) {\n        toggleForSelect2(select2Element, wrapperElement, className);\n      }\n    });\n  });\n}\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/libs/toggle-focus.js?");

/***/ })

}]);