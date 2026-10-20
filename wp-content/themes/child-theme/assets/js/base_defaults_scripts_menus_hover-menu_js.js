"use strict";
/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunkmarkup_template"] = self["webpackChunkmarkup_template"] || []).push([["base_defaults_scripts_menus_hover-menu_js"],{

/***/ "./base/defaults/scripts/libs/hover-menu.js":
/*!**************************************************!*\
  !*** ./base/defaults/scripts/libs/hover-menu.js ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": function() { return /* binding */ hoverMenu; }\n/* harmony export */ });\n/* harmony import */ var _menu_helpers__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./menu-helpers */ \"./base/defaults/scripts/libs/menu-helpers.js\");\n\n\n/**\n * @param {HTMLUListElement} menuUl\n */\nfunction hoverMenu(menuUl) {\n  menuUl.addEventListener('mouseover', function () {\n    var dialogs = new Map();\n    var hoverItems = Array.from(menuUl.querySelectorAll('div[hidden]')).map(function (dialog) {\n      var hoverItem = dialog.parentElement;\n      dialogs.set(hoverItem, dialog);\n      return hoverItem;\n    });\n    hoverItems.forEach(function (hoverItem) {\n      var dialog = dialogs.get(hoverItem);\n      hoverItem.addEventListener('mouseover', function () {\n        (0,_menu_helpers__WEBPACK_IMPORTED_MODULE_0__.open)(dialog, 'hover');\n      });\n      hoverItem.addEventListener('mouseout', function () {\n        (0,_menu_helpers__WEBPACK_IMPORTED_MODULE_0__.close)(dialog, 'hover');\n      });\n    });\n  }, {\n    once: true\n  });\n}\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/libs/hover-menu.js?");

/***/ }),

/***/ "./base/defaults/scripts/libs/menu-helpers.js":
/*!****************************************************!*\
  !*** ./base/defaults/scripts/libs/menu-helpers.js ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   close: function() { return /* binding */ close; },\n/* harmony export */   open: function() { return /* binding */ open; },\n/* harmony export */   openTypes: function() { return /* binding */ openTypes; },\n/* harmony export */   setCloseEvents: function() { return /* binding */ setCloseEvents; },\n/* harmony export */   toggle: function() { return /* binding */ toggle; }\n/* harmony export */ });\nvar openTypes = new WeakMap();\nfunction getClassName(element, regExp, modifier) {\n  var className = '';\n  element.classList.forEach(function (c) {\n    if (regExp.test(c)) {\n      className = c;\n    }\n  });\n  if (modifier) {\n    className += '_' + modifier;\n  }\n  return className;\n}\nfunction openLinkClassName(element, isOpen) {\n  element.classList.toggle(getClassName(element, /__link$/, 'open'), isOpen);\n}\nfunction openItemClassName(element, isOpen) {\n  element.classList.toggle(getClassName(element, /__item$/, 'open', isOpen));\n}\nfunction openButtonClassName(element, isOpen) {\n  element.classList.toggle(getClassName(element, /__expend-button$/, 'open'), isOpen);\n}\n\n/**\n * @param {HTMLDivElement} dialog\n * @param {string}         type\n */\nfunction toggle(dialog, type) {\n  if (dialog.hidden) {\n    open(dialog, type);\n  } else {\n    close(dialog, type);\n  }\n}\n\n/**\n * @param {HTMLDivElement} dialog\n * @param {string}         type\n */\nfunction open(dialog, type) {\n  openTypes.set(dialog, type);\n  dialog.hidden = false;\n  dialog.previousElementSibling.ariaExpanded = 'true';\n  openButtonClassName(dialog.previousElementSibling, true);\n  openLinkClassName(dialog.previousElementSibling.previousElementSibling, true);\n  openItemClassName(dialog.parentElement, true);\n}\n\n/**\n * @param {HTMLDivElement} dialog\n * @param {string}         type\n */\nfunction close(dialog, type) {\n  if (openTypes.get(dialog) === type) {\n    openTypes.set(dialog, '');\n    dialog.hidden = true;\n    dialog.previousElementSibling.ariaExpanded = 'false';\n    dialog.querySelectorAll('button[aria-expanded]').forEach(function (expendButton) {\n      close(expendButton.nextElementSibling, type);\n    });\n    openButtonClassName(dialog.previousElementSibling, false);\n    openLinkClassName(dialog.previousElementSibling.previousElementSibling, false);\n    openItemClassName(dialog.parentElement, false);\n  }\n}\n\n/**\n * @param {HTMLDivElement} dialog\n * @param {string}         type\n * @param {boolean}        needFocusOutClose\n */\nfunction setCloseEvents(dialog, type, needFocusOutClose) {\n  dialog.addEventListener('keydown', function (event) {\n    if (openTypes.get(dialog) === type && event.key === 'Escape') {\n      event.preventDefault();\n      event.stopPropagation();\n      close(dialog, type);\n      if ('expend' === type) {\n        dialog.previousElementSibling.focus();\n      } else {\n        dialog.previousElementSibling.previousElementSibling.focus();\n      }\n    }\n  });\n  if (needFocusOutClose) {\n    dialog.addEventListener('focusout', function (event) {\n      if (openTypes.get(dialog) === type && !dialog.contains(event.relatedTarget) && dialog.previousElementSibling !== event.relatedTarget) {\n        close(dialog, type);\n      }\n    });\n  }\n}\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/libs/menu-helpers.js?");

/***/ }),

/***/ "./base/defaults/scripts/menus/hover-menu.js":
/*!***************************************************!*\
  !*** ./base/defaults/scripts/menus/hover-menu.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _libs_live_dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../libs/live-dom */ \"./base/defaults/scripts/libs/live-dom.js\");\n/* harmony import */ var _libs_hover_menu__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../libs/hover-menu */ \"./base/defaults/scripts/libs/hover-menu.js\");\n\n\n(0,_libs_live_dom__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('[data-hover-menu]').firstShow(function () {\n  (0,_libs_hover_menu__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this);\n});\n\n//# sourceURL=webpack://markup-template/./base/defaults/scripts/menus/hover-menu.js?");

/***/ })

}]);