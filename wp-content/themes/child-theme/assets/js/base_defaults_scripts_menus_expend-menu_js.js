"use strict";
/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunkmarkup_template"] = self["webpackChunkmarkup_template"] || []).push([["base_defaults_scripts_menus_expend-menu_js"],{

/***/ "./base/defaults/scripts/libs/expend-menu.js":
/*!***************************************************!*\
  !*** ./base/defaults/scripts/libs/expend-menu.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": function() { return /* binding */ expendMenu; }\n/* harmony export */ });\n/* harmony import */ var _menu_helpers__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./menu-helpers */ \"./base/defaults/scripts/libs/menu-helpers.js\");\n\n\n/**\n * @param {HTMLUListElement} menuUl\n */\nfunction expendMenu(menuUl) {\n  function init() {\n    var needFocusOutClose = menuUl.dataset.expendMenu === 'auto-close';\n    var dialogs = [];\n    var expendButtons = Array.from(menuUl.querySelectorAll('button[aria-expanded]')).map(function (expendButton) {\n      var dialog = expendButton.nextElementSibling;\n      dialog._expendButton = expendButton;\n      dialogs.push(dialog);\n      return expendButton;\n    });\n    expendButtons.forEach(function (expendButton) {\n      expendButton.addEventListener('click', function () {\n        (0,_menu_helpers__WEBPACK_IMPORTED_MODULE_0__.toggle)(expendButton.nextElementSibling, 'expend');\n        if (expendButton.nextElementSibling.hidden) {\n          expendButton.focus();\n        } else {\n          expendButton.nextElementSibling.querySelector('a').focus();\n        }\n      });\n    });\n    dialogs.forEach(function (dialog) {\n      (0,_menu_helpers__WEBPACK_IMPORTED_MODULE_0__.setCloseEvents)(dialog, 'expend', needFocusOutClose);\n    });\n  }\n  menuUl.addEventListener('keydown', init, {\n    once: true\n  });\n  menuUl.addEventListener('mousedown', init, {\n    once: true\n  });\n}\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/libs/expend-menu.js?");

/***/ }),

/***/ "./base/defaults/scripts/libs/menu-helpers.js":
/*!****************************************************!*\
  !*** ./base/defaults/scripts/libs/menu-helpers.js ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   close: function() { return /* binding */ close; },\n/* harmony export */   open: function() { return /* binding */ open; },\n/* harmony export */   openTypes: function() { return /* binding */ openTypes; },\n/* harmony export */   setCloseEvents: function() { return /* binding */ setCloseEvents; },\n/* harmony export */   toggle: function() { return /* binding */ toggle; }\n/* harmony export */ });\nvar openTypes = new WeakMap();\nfunction getClassName(element, regExp, modifier) {\n  var className = '';\n  element.classList.forEach(function (c) {\n    if (regExp.test(c)) {\n      className = c;\n    }\n  });\n  if (modifier) {\n    className += '_' + modifier;\n  }\n  return className;\n}\nfunction openLinkClassName(element, isOpen) {\n  element.classList.toggle(getClassName(element, /__link$/, 'open'), isOpen);\n}\nfunction openItemClassName(element, isOpen) {\n  element.classList.toggle(getClassName(element, /__item$/, 'open', isOpen));\n}\nfunction openButtonClassName(element, isOpen) {\n  element.classList.toggle(getClassName(element, /__expend-button$/, 'open'), isOpen);\n}\n\n/**\n * @param {HTMLDivElement} dialog\n * @param {string}         type\n */\nfunction toggle(dialog, type) {\n  if (dialog.hidden) {\n    open(dialog, type);\n  } else {\n    close(dialog, type);\n  }\n}\n\n/**\n * @param {HTMLDivElement} dialog\n * @param {string}         type\n */\nfunction open(dialog, type) {\n  openTypes.set(dialog, type);\n  dialog.hidden = false;\n  dialog.previousElementSibling.ariaExpanded = 'true';\n  openButtonClassName(dialog.previousElementSibling, true);\n  openLinkClassName(dialog.previousElementSibling.previousElementSibling, true);\n  openItemClassName(dialog.parentElement, true);\n}\n\n/**\n * @param {HTMLDivElement} dialog\n * @param {string}         type\n */\nfunction close(dialog, type) {\n  if (openTypes.get(dialog) === type) {\n    openTypes.set(dialog, '');\n    dialog.hidden = true;\n    dialog.previousElementSibling.ariaExpanded = 'false';\n    dialog.querySelectorAll('button[aria-expanded]').forEach(function (expendButton) {\n      close(expendButton.nextElementSibling, type);\n    });\n    openButtonClassName(dialog.previousElementSibling, false);\n    openLinkClassName(dialog.previousElementSibling.previousElementSibling, false);\n    openItemClassName(dialog.parentElement, false);\n  }\n}\n\n/**\n * @param {HTMLDivElement} dialog\n * @param {string}         type\n * @param {boolean}        needFocusOutClose\n */\nfunction setCloseEvents(dialog, type, needFocusOutClose) {\n  dialog.addEventListener('keydown', function (event) {\n    if (openTypes.get(dialog) === type && event.key === 'Escape') {\n      event.preventDefault();\n      event.stopPropagation();\n      close(dialog, type);\n      if ('expend' === type) {\n        dialog.previousElementSibling.focus();\n      } else {\n        dialog.previousElementSibling.previousElementSibling.focus();\n      }\n    }\n  });\n  if (needFocusOutClose) {\n    dialog.addEventListener('focusout', function (event) {\n      if (openTypes.get(dialog) === type && !dialog.contains(event.relatedTarget) && dialog.previousElementSibling !== event.relatedTarget) {\n        close(dialog, type);\n      }\n    });\n  }\n}\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/libs/menu-helpers.js?");

/***/ }),

/***/ "./base/defaults/scripts/menus/expend-menu.js":
/*!****************************************************!*\
  !*** ./base/defaults/scripts/menus/expend-menu.js ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _libs_live_dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../libs/live-dom */ \"./base/defaults/scripts/libs/live-dom.js\");\n/* harmony import */ var _libs_expend_menu__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../libs/expend-menu */ \"./base/defaults/scripts/libs/expend-menu.js\");\n\n\n(0,_libs_live_dom__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('[data-expend-menu]').firstShow(function () {\n  (0,_libs_expend_menu__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this);\n});\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/menus/expend-menu.js?");

/***/ })

}]);