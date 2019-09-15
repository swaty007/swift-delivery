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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./frontend/web/js/scripts.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./frontend/web/js/modules/Main.js":
/*!*****************************************!*\
  !*** ./frontend/web/js/modules/Main.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }\n\nvar Main =\n/*#__PURE__*/\nfunction () {\n  function Main() {\n    _classCallCheck(this, Main);\n\n    this.events();\n  }\n\n  _createClass(Main, [{\n    key: \"events\",\n    value: function events() {\n      $(\".fileContainer input[type=file]\").on('change', function (e) {\n        var input = this;\n\n        if (input.files && input.files[0]) {\n          var reader = new FileReader(); // if (input.files[0].size/1024/1024 > 2) {\n          //     toastr['error'](\"Файл превышает размер в 2мб\", '');\n          // }\n\n          reader.onload = function (e) {\n            $(\".fileContainer .fileContainer__img\").attr('src', e.target.result).show();\n            $(\".fileContainer .fileContainer__text--select\").hide();\n            $(\".fileContainer .fileContainer__text--name\").addClass('loaded');\n          };\n\n          reader.readAsDataURL(input.files[0]);\n        }\n      });\n    }\n  }]);\n\n  return Main;\n}();\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Main);\n\n//# sourceURL=webpack:///./frontend/web/js/modules/Main.js?");

/***/ }),

/***/ "./frontend/web/js/scripts.js":
/*!************************************!*\
  !*** ./frontend/web/js/scripts.js ***!
  \************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _modules_Main__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/Main */ \"./frontend/web/js/modules/Main.js\");\n// 3rd party packages from NPM\n// import $ from \"jquery\";\n// import WOW from \"wowjs\";\n// import tocca from \"tocca\";\n// Our modules / classes\n// import Anim from \"./modules/Anim\";\n// import HowWeWorkCar from \"./modules/HowWeWorkCar\";\n// import Popups from \"./modules/Popups\";\n// import Validate from \"./modules/Validate\";\n // import Cursor from \"./modules/Cursor\";\n// Instantiate a new object using our modules/classes\n\nvar main = new _modules_Main__WEBPACK_IMPORTED_MODULE_0__[\"default\"](); // const hwwcar = new HowWeWorkCar();\n// const filter = new Filter();\n// const popups = new Popups();\n// const validate = new Validate();\n// const calculator = new Calculator();\n// const forms = new Forms();\n// const cursor = new Cursor();\n\n//# sourceURL=webpack:///./frontend/web/js/scripts.js?");

/***/ })

/******/ });