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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 329);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 1 */,
/* 2 */,
/* 3 */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(14)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */,
/* 12 */,
/* 13 */,
/* 14 */
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),
/* 15 */,
/* 16 */,
/* 17 */,
/* 18 */,
/* 19 */,
/* 20 */,
/* 21 */,
/* 22 */,
/* 23 */,
/* 24 */,
/* 25 */,
/* 26 */,
/* 27 */,
/* 28 */,
/* 29 */,
/* 30 */,
/* 31 */,
/* 32 */,
/* 33 */,
/* 34 */,
/* 35 */,
/* 36 */,
/* 37 */,
/* 38 */,
/* 39 */,
/* 40 */,
/* 41 */,
/* 42 */,
/* 43 */,
/* 44 */,
/* 45 */,
/* 46 */,
/* 47 */,
/* 48 */,
/* 49 */,
/* 50 */,
/* 51 */,
/* 52 */,
/* 53 */,
/* 54 */,
/* 55 */,
/* 56 */,
/* 57 */,
/* 58 */,
/* 59 */,
/* 60 */,
/* 61 */,
/* 62 */,
/* 63 */,
/* 64 */,
/* 65 */,
/* 66 */,
/* 67 */,
/* 68 */,
/* 69 */,
/* 70 */,
/* 71 */,
/* 72 */,
/* 73 */,
/* 74 */,
/* 75 */,
/* 76 */,
/* 77 */,
/* 78 */,
/* 79 */,
/* 80 */,
/* 81 */,
/* 82 */,
/* 83 */,
/* 84 */,
/* 85 */,
/* 86 */,
/* 87 */,
/* 88 */,
/* 89 */,
/* 90 */,
/* 91 */,
/* 92 */,
/* 93 */,
/* 94 */,
/* 95 */,
/* 96 */,
/* 97 */,
/* 98 */,
/* 99 */,
/* 100 */,
/* 101 */,
/* 102 */,
/* 103 */,
/* 104 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Datum; });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Datum = function () {
    function Datum() {
        _classCallCheck(this, Datum);

        this._type = 'Datum';
        this._meta = {};
        this._hidden = false;
    }

    _createClass(Datum, [{
        key: 'toShortString',
        value: function toShortString() {
            return this.toString();
        }
    }, {
        key: 'toString',
        value: function toString() {
            return 'This is a testing';
        }
    }, {
        key: 'meta',
        get: function get() {
            return this._meta;
        },
        set: function set(meta) {
            this._meta = meta;
        }
    }, {
        key: 'hidden',
        get: function get() {
            return this._hidden;
        },
        set: function set(hidden) {
            this._hidden = hidden;
        }
    }, {
        key: 'type',
        get: function get() {
            return this._type;
        },
        set: function set(type) {
            this._type = type;
        }
    }]);

    return Datum;
}();

/***/ }),
/* 105 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ContentItem; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Datum_js__ = __webpack_require__(104);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }



var ContentItem = function () {
    function ContentItem(name, data, meta, box_hidden) {
        _classCallCheck(this, ContentItem);

        // invokes the setter
        if (name !== undefined) this._name = name;else this._name = 'Title should be here ....';

        if (data !== undefined) {
            this._data = data;
        } else this._data = new Array();

        if (meta !== undefined) {
            this._meta = meta;
        } else {
            this._meta = {
                with_header: true
            };
        }

        if (box_hidden !== undefined) this._box_hidden = box_hidden;else this._box_hidden = false;
    }

    _createClass(ContentItem, [{
        key: 'addDatum',
        value: function addDatum(datum) {
            if (datum !== undefined) {
                this.data.push(datum);
            } else this.data.push(new __WEBPACK_IMPORTED_MODULE_0__Datum_js__["a" /* Datum */]());
        }
    }, {
        key: 'removeDatum',
        value: function removeDatum(datum) {
            var index = this._data.indexOf(datum);
            this.removeDatumIndex(index);
        }
    }, {
        key: 'removeDatumIndex',
        value: function removeDatumIndex(index) {
            this._data.splice(index, 1);
        }
    }, {
        key: 'box_hidden',
        get: function get() {
            return this._box_hidden;
        },
        set: function set(box_hidden) {
            this._box_hidden = box_hidden;
        }
    }, {
        key: 'name',
        get: function get() {
            return this._name;
        },
        set: function set(name) {
            this._name = name;
        }
    }, {
        key: 'data',
        get: function get() {
            return this._data;
        },
        set: function set(data) {
            this._data = data;
        }
    }, {
        key: 'meta',
        get: function get() {
            return this._meta;
        },
        set: function set(meta) {
            this._meta = meta;
        }
    }]);

    return ContentItem;
}();

/***/ }),
/* 106 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ContentListHelper; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Cipher__ = __webpack_require__(116);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Roman__ = __webpack_require__(117);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }




var ContentListHelper = function () {
    function ContentListHelper() {
        _classCallCheck(this, ContentListHelper);
    }

    _createClass(ContentListHelper, null, [{
        key: 'list_representation',
        value: function list_representation(ordered_list, parent_label) {
            var count = 0;

            var result = '<table class="ordered-list"><tbody>';
            var list_items = ordered_list.list_items;

            for (var i = 0; i < list_items.length; i++) {
                var list_item = ordered_list.list_items[i];
                result += '<tr>';

                var item_label = this.generate_before_label(ordered_list.meta.style, count + 1);

                // Put a styled list order if it is list
                if (list_items[i].is_list === true) {
                    // Check if there is a parent label and concatenate it to the current label
                    if (ordered_list.has_parent === true && parent_label !== undefined && parent_label !== null) item_label = parent_label + item_label;

                    count++;
                    result += '<td class="ol-label"><strong>' + item_label + '</strong></td>';
                }

                result += '<td class="ol-content">' + list_item.data.toString();
                var temp_list = list_item.ordered_list;

                for (var j = 0; j < temp_list.length; j++) {
                    result += this.list_representation(temp_list[j], item_label);
                }
                result += '</td></tr>';
            }
            result += '</tbody></table>';
            return result;
        }
    }, {
        key: 'content_item_representation',
        value: function content_item_representation(content) {
            var count = 0;

            var result = '<table class="ordered-list"><tbody>';
            var content_items = content.content_items;

            for (var i = 0; i < content_items.length; i++) {
                var content_item = content.content_items[i];
                result += '<tr>';

                var item_label = this.generate_before_label(content.style, count + 1);

                result += '<td class="ol-label" style="padding-bottom: 1em;"><strong>' + item_label + '</strong></td>';
                count++;
                result += '<td class="ol-content" style="padding-bottom: 1em;">';

                if (content_item.meta.with_header === true && content_item.name !== null && content_item.name !== undefined) result += '<strong>' + content_item.name + ':</strong>';

                content_item.data.forEach(function (datum) {

                    switch (datum.type) {
                        case 'list':
                            result += datum.toString(item_label); // Pass the parent label to adapt to the child list
                            break;
                        case 'paragraph':
                            result += '<br>' + datum.toString(item_label); // Pass the parent label to adapt to the child list
                            break;
                        default:
                            result += datum.toString();
                            break;
                    }
                });
                result += '</td></tr>';
            }
            result += '</tbody></table>';
            return result;
        }
    }, {
        key: 'generate_before_label',
        value: function generate_before_label(style, index) {
            // none = ''
            // number
            // lower_alpha
            // upper_alpha
            // circle
            // square
            // upper_roman
            // lower_roman

            var style_str = '';
            switch (style) {
                case 'lower_alpha':
                    style_str = __WEBPACK_IMPORTED_MODULE_0__Cipher__["a" /* Cipher */].decode(index).toLowerCase() + '.';
                    break;
                case 'upper_alpha':
                    style_str = __WEBPACK_IMPORTED_MODULE_0__Cipher__["a" /* Cipher */].decode(index).toUpperCase() + '.';
                    break;
                case 'number':
                    style_str = index + '.';
                    break;
                case 'circle':
                    style_str = '&#9679;';
                    break;
                case 'square':
                    style_str = '&#9632;';
                    break;
                case 'upper_roman':
                    style_str = __WEBPACK_IMPORTED_MODULE_1__Roman__["a" /* Roman */].encode(index).toUpperCase() + '.';
                    break;
                case 'lower_roman':
                    style_str = __WEBPACK_IMPORTED_MODULE_1__Roman__["a" /* Roman */].encode(index).toLowerCase() + '.';
                    break;
            }

            return style_str;
        }
    }]);

    return ContentListHelper;
}();

/***/ }),
/* 107 */,
/* 108 */,
/* 109 */,
/* 110 */,
/* 111 */,
/* 112 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony default export */ __webpack_exports__["a"] = ({
    methods: {
        cast_to_content: function cast_to_content(data) {
            // Cast a content to an instance of Content Class
            if (data instanceof Content === false) {
                var content = new Content();
                content.style = data.style;

                for (var i = 0; i < data.content_items.length; i++) {
                    content.addContentItem(this.cast_to_content_item(data.content_items[i]));
                }

                return content;
            } else return data;
        },
        cast_to_content_item: function cast_to_content_item(data) {
            // Cast a content to an instance of Content Class
            if (data instanceof ContentItem === false) {
                var content_item = new ContentItem();
                content_item.name = data.name;
                content_item.box_hidden = data.box_hidden;
                content_item.meta = data.meta;

                for (var i = 0; i < data.data.length; i++) {
                    content_item.addDatum(this.cast_to_datum(data.data[i]));
                }

                return content_item;
            } else return data;
        },
        cast_to_datum: function cast_to_datum(data) {
            var datum = new Datum();
            switch (data.type) {
                case 'list':
                    datum = this.cast_to_list(data);

                    break;

                case 'image':
                    datum = this.cast_to_image(data);
                    break;

                case 'table':
                    datum = this.cast_to_table(data);
                    break;

                case 'paragraph':
                    datum = this.cast_to_paragraph(data);
                    break;

            }

            datum.type = data.type;
            datum.meta = data.meta;
            datum.hidden = data.hidden;

            return datum;
        },
        cast_to_list: function cast_to_list(data) {
            var vm = this;
            var list = new OrderedList();
            data.list_items.forEach(function (list_item) {

                var temp_list_item = new ListItem();
                switch (list_item.data.type) {
                    case 'table':
                        temp_list_item.data = vm.cast_to_table(list_item.data);
                        break;
                    case 'paragraph':
                        temp_list_item.data = vm.cast_to_paragraph(list_item.data);
                        break;
                    case 'image':
                        temp_list_item.data = vm.cast_to_image(list_item.data);
                        break;
                }

                temp_list_item.is_list = list_item.is_list;

                if (list_item.meta !== undefined) temp_list_item.meta = list_item.meta;

                var temp_list = list_item.ordered_list;

                temp_list.forEach(function (li) {
                    temp_list_item.addOrderedList(vm.cast_to_list(li));
                });

                list.addListItem(temp_list_item);
            });

            return list;
        },
        cast_to_table: function cast_to_table(data) {
            var temp = new Table();
            temp.header = data.header;
            temp.rows = data.rows;
            return temp;
        },
        cast_to_paragraph: function cast_to_paragraph(data) {
            var temp = new Paragraph();
            temp.value = data.value;
            return temp;
        },
        cast_to_image: function cast_to_image(data) {
            var temp = new Image();
            temp.upload_id = data.upload_id;
            return temp;
        }
    }
});

/***/ }),
/* 113 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__src_classes_Content_Import_js__ = __webpack_require__(114);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__global_events_bus__ = __webpack_require__(124);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__mixin_content_list_js__ = __webpack_require__(112);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__content_items_components__ = __webpack_require__(125);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__content_items_components___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3__content_items_components__);


// Global Event

window.EventBus = __WEBPACK_IMPORTED_MODULE_1__global_events_bus__["a" /* EventBus */];
// Global Event


Vue.mixin(common);


// Import draggable component for items
Vue.component('draggable', __webpack_require__(140));

// List Structure Representation Component
Vue.component('list-style', __webpack_require__(142));
Vue.component('list-preview', __webpack_require__(147));
// List Structure Representation Component


// Modal Item Components
Vue.component('phar', __webpack_require__(150));
Vue.component('tbl', __webpack_require__(153));
Vue.component('list', __webpack_require__(156));
Vue.component('com-image', __webpack_require__(161));
// Modal Item Components


Vue.component('content-form', __webpack_require__(164)).mixin(__WEBPACK_IMPORTED_MODULE_2__mixin_content_list_js__["a" /* default */]);
Vue.component('main-item', __webpack_require__(170));

/***/ }),
/* 114 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Content_js__ = __webpack_require__(115);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__ContentItem_js__ = __webpack_require__(105);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Datum_js__ = __webpack_require__(104);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ContentType_Table_js__ = __webpack_require__(118);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ContentType_Paragraph_js__ = __webpack_require__(119);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ContentType_Image_js__ = __webpack_require__(121);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__ContentType_OrderedList_OrderedList_js__ = __webpack_require__(122);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ContentType_OrderedList_ListItem_js__ = __webpack_require__(123);






// Content types import









window.Content = __WEBPACK_IMPORTED_MODULE_0__Content_js__["a" /* Content */];
window.ContentItem = __WEBPACK_IMPORTED_MODULE_1__ContentItem_js__["a" /* ContentItem */];

//Content types Initialization
window.Table = __WEBPACK_IMPORTED_MODULE_3__ContentType_Table_js__["a" /* Table */];
window.Paragraph = __WEBPACK_IMPORTED_MODULE_4__ContentType_Paragraph_js__["a" /* Paragraph */];
window.OrderedList = __WEBPACK_IMPORTED_MODULE_6__ContentType_OrderedList_OrderedList_js__["a" /* OrderedList */];
window.ListItem = __WEBPACK_IMPORTED_MODULE_7__ContentType_OrderedList_ListItem_js__["a" /* ListItem */];
window.Image = __WEBPACK_IMPORTED_MODULE_5__ContentType_Image_js__["a" /* Image */];
window.Datum = __WEBPACK_IMPORTED_MODULE_2__Datum_js__["a" /* Datum */];

/***/ }),
/* 115 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Content; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__ContentItem_js__ = __webpack_require__(105);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__helpers_ContentListHelper_js__ = __webpack_require__(106);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }





var Content = function () {
    function Content(style, content_items) {
        _classCallCheck(this, Content);

        // invokes the setter
        if (style !== undefined) this._style = style;else this._style = null;

        if (content_items !== undefined) {
            this._content_items = content_items;
        } else this._content_items = [];
    }

    _createClass(Content, [{
        key: 'addContentItem',
        value: function addContentItem(content_item) {
            if (content_item !== undefined) {
                this._content_items.push(content_item);
            } else this._content_items.push(new __WEBPACK_IMPORTED_MODULE_0__ContentItem_js__["a" /* ContentItem */]());
        }
    }, {
        key: 'removeContentItem',
        value: function removeContentItem(content_item) {
            var index = this._content_items.indexOf(content_item);
            this.removeContentItemIndex(index);
        }
    }, {
        key: 'removeContentItemIndex',
        value: function removeContentItemIndex(index) {
            this._content_items.splice(index, 1);
        }
    }, {
        key: 'toString',
        value: function toString() {
            return __WEBPACK_IMPORTED_MODULE_1__helpers_ContentListHelper_js__["a" /* ContentListHelper */].content_item_representation(this);
        }
    }, {
        key: 'style',
        get: function get() {
            return this._style;
        },
        set: function set(style) {
            this._style = style;
        }
    }, {
        key: 'content_items',
        get: function get() {
            return this._content_items;
        },
        set: function set(content_items) {
            this._content_items = content_items;
        }
    }]);

    return Content;
}();

/***/ }),
/* 116 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Cipher; });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Cipher = function () {
    function Cipher() {
        _classCallCheck(this, Cipher);
    }

    _createClass(Cipher, null, [{
        key: 'setLetters',
        value: function setLetters() {
            return ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
        }
    }, {
        key: 'decode',
        value: function decode(index) {
            if (index < 1) {
                return '';
            } else if (index <= this.setLetters().length) {
                return this.setLetters()[index - 1];
            } else if (index > this.setLetters().length) {

                var letters = this.setLetters();
                // Count the occurrence of the letter 
                var occurrence = Math.round(index / letters.length);
                // Get the letter index
                var letter_index = index - letters.length * Math.floor(index / letters.length);
                var letter = '';

                for (var i = 0; i < occurrence; i++) {
                    letter += letters[letter_index - 1];
                }

                return letter;
            }
        }
    }]);

    return Cipher;
}();

/***/ }),
/* 117 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Roman; });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Roman = function () {
    function Roman() {
        _classCallCheck(this, Roman);
    }

    _createClass(Roman, null, [{
        key: "romanSet",
        value: function romanSet() {
            return ["", "C", "CC", "CCC", "CD", "D", "DC", "DCC", "DCCC", "CM", "", "X", "XX", "XXX", "XL", "L", "LX", "LXX", "LXXX", "XC", "", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX"];
        }
    }, {
        key: "encode",
        value: function encode(num) {
            if (!+num) return false;
            var digits = String(+num).split(""),
                key = this.romanSet(),
                roman = "",
                i = 3;
            while (i--) {
                roman = (key[+digits.pop() + i * 10] || "") + roman;
            }return Array(+digits.join("") + 1).join("M") + roman;
        }
    }]);

    return Roman;
}();

/***/ }),
/* 118 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Table; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Datum_js__ = __webpack_require__(104);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }



var Table = function (_Datum) {
    _inherits(Table, _Datum);

    function Table(header, rows) {
        _classCallCheck(this, Table);

        var _this = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this));

        _this._type = 'table';

        if (header !== undefined) _this._header = header;else
            // invokes the setter
            _this._header = ['', '', ''];

        if (rows !== undefined) _this._rows = rows;else
            // invokes the setter
            _this._rows = [['', '', '']];
        return _this;
    }

    _createClass(Table, [{
        key: 'addHeader',
        value: function addHeader(header) {
            var par = this;
            if (header === undefined) {
                this._header.push(header);
            } else {
                this._header.push('');
            }
            // Insert new cell in row
            this._rows.forEach(function (row, key) {
                par._rows[key].push('');
            });
        }
    }, {
        key: 'removeHeader',
        value: function removeHeader(element) {
            var index = this._header.indexOf(element);
            this.removeHeaderIndex(index);
        }
    }, {
        key: 'removeHeaderIndex',
        value: function removeHeaderIndex(index) {
            var par = this;
            this._header.splice(index, 1);

            this._rows.forEach(function (row, key) {
                par._rows[key].splice(index, 1);
            });
        }
    }, {
        key: 'addRow',
        value: function addRow(row) {
            if (row === undefined) {
                this._rows.push(row);
            } else {
                var row = [];
                this._header.forEach(function () {
                    row.push('');
                });
                this._rows.push(row);
            }
        }
    }, {
        key: 'removeRow',
        value: function removeRow(element) {
            var index = this._rows.indexOf(element);
            this.removeRowIndex(index);
        }
    }, {
        key: 'removeRowIndex',
        value: function removeRowIndex(index) {
            this._rows.splice(index, 1);
        }
    }, {
        key: 'init',
        value: function init() {
            this._header = ['', '', ''];
            this._rows = [['', '', ''], ['', '', ''], ['', '', '']];
        }
    }, {
        key: 'thead',
        value: function thead() {

            var display = '<thead>';
            display += '<tr>';
            this._header.forEach(function (head) {
                display += '<th>' + head + '</th>';
            });
            display += '</tr>';
            display += '</thead>';
            return display;
        }
    }, {
        key: 'tbody',
        value: function tbody() {
            var display = '<tbody>';
            this._rows.forEach(function (cells) {
                display += '<tr>';
                cells.forEach(function (cell) {
                    display += '<td>' + cell + '</td>';
                });
                display += '</tr>';
            });
            display += '</tbody>';
            return display;
        }
    }, {
        key: 'toString',
        value: function toString() {
            return '<table class="table table-bordered">' + this.thead() + this.tbody() + '</table>';
        }
    }, {
        key: 'toShortString',
        value: function toShortString() {
            return '<table class="table table-bordered">' + this.thead() + '</table>';
        }
    }, {
        key: 'header',
        get: function get() {
            return this._header;
        },
        set: function set(header) {
            this._header = header;
        }
    }, {
        key: 'rows',
        get: function get() {
            return this._rows;
        },
        set: function set(rows) {
            this._rows = rows;
        }
    }]);

    return Table;
}(__WEBPACK_IMPORTED_MODULE_0__Datum_js__["a" /* Datum */]);

/***/ }),
/* 119 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Paragraph; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Datum_js__ = __webpack_require__(104);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__helpers_ParagraphHelper__ = __webpack_require__(120);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }





var Paragraph = function (_Datum) {
    _inherits(Paragraph, _Datum);

    function Paragraph(value) {
        _classCallCheck(this, Paragraph);

        var _this = _possibleConstructorReturn(this, (Paragraph.__proto__ || Object.getPrototypeOf(Paragraph)).call(this));

        _this._type = 'paragraph';
        if (value !== undefined) _this._value = value;else
            // invokes the setter
            _this._value = '';
        _this._meta = {
            html: ''
        };
        return _this;
    }

    _createClass(Paragraph, [{
        key: 'toShortString',
        value: function toShortString() {
            if (this.toString().length > 30) return this.toString().substring(0, 30) + '...';else return this.toString();
        }
    }, {
        key: 'toString',
        value: function toString() {
            return __WEBPACK_IMPORTED_MODULE_1__helpers_ParagraphHelper__["a" /* ParagraphHelper */].format_html(this._value);
        }
    }, {
        key: 'value',
        get: function get() {
            return this._value;
        },
        set: function set(value) {
            this._value = value;
        }
    }]);

    return Paragraph;
}(__WEBPACK_IMPORTED_MODULE_0__Datum_js__["a" /* Datum */]);

/***/ }),
/* 120 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ParagraphHelper; });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ParagraphHelper = function () {
    function ParagraphHelper() {
        _classCallCheck(this, ParagraphHelper);
    }

    _createClass(ParagraphHelper, null, [{
        key: 'nl2br',
        value: function nl2br(str, is_xhtml) {
            if (typeof str === 'undefined' || str === null) {
                return '';
            }
            var breakTag = is_xhtml || typeof is_xhtml === 'undefined' ? '<br />' : '<br>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        }
    }, {
        key: 'format_html',
        value: function format_html(str) {
            var value = this.nl2br(str);

            value = value.replace(/<bold>/g, "<b>");
            value = value.replace(/<\/bold>/g, "</b>");

            value = value.replace(/<italic>/g, "<i>");
            value = value.replace(/<\/italic>/g, "</i>");
            return value;
        }
    }]);

    return ParagraphHelper;
}();

/***/ }),
/* 121 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Image; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Datum_js__ = __webpack_require__(104);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }



var Image = function (_Datum) {
    _inherits(Image, _Datum);

    function Image(upload_id) {
        _classCallCheck(this, Image);

        var _this = _possibleConstructorReturn(this, (Image.__proto__ || Object.getPrototypeOf(Image)).call(this));

        _this._type = 'image';

        if (upload_id !== undefined) _this._upload_id = upload_id;else
            // invokes the setter
            _this._upload_id = null;

        _this._meta = {
            width: '500',
            height: null,
            position: 'left', // center,left,right,
            class: 'img-responsive',
            style: ''
        };
        return _this;
    }

    _createClass(Image, [{
        key: 'reset',
        value: function reset() {
            // invokes the setter
            this._upload_id = '';
            this._meta.width = '500';
            this._meta.height = null;
            this._meta.position = 'left';
            this._meta.class = 'img-responsive';
        }
    }, {
        key: 'generate_style',
        value: function generate_style() {
            var style = '';
            if (this._meta.width !== '' && this._meta.width !== null) style += 'width:' + this._meta.width + 'px;';

            if (this._meta.height !== '' && this._meta.height !== null) {
                style += 'height:' + this._meta.height + 'px;';
            }
            style += 'margin-top:10px';

            return style;
        }
    }, {
        key: 'generateLink',
        value: function generateLink() {
            return '/file/' + this._upload_id;
        }
    }, {
        key: 'toShortString',
        value: function toShortString() {
            return '<img style="width:30px;" src="' + this.generateLink() + '">';
        }
    }, {
        key: 'toString',
        value: function toString() {

            var display = '';
            switch (this._meta.position) {
                case 'center':

                    display += '<center>';
                    display += '<img class="img-responsive" style="' + this.generate_style() + '" src="' + this.generateLink() + '">';
                    display += '</center>';

                    break;
                case 'left':

                    display = '<div class="clearfix"></div>';
                    display += '<img class="img-responsive" style="' + this.generate_style() + ' float:left;" src="' + this.generateLink() + '">';
                    display += '<div class="clearfix"></div>';

                    break;
                case 'right':
                    display = '<div class="clearfix"></div>';
                    display += '<img class="img-responsive" style="' + this.generate_style() + ' float:right;" src="' + this.generateLink() + '">';
                    display += '<div class="clearfix"></div>';
                    break;
            }

            return display;
        }
    }, {
        key: 'upload_id',
        get: function get() {
            return this._upload_id;
        },
        set: function set(upload_id) {
            this._upload_id = upload_id;
        }
    }]);

    return Image;
}(__WEBPACK_IMPORTED_MODULE_0__Datum_js__["a" /* Datum */]);

/***/ }),
/* 122 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return OrderedList; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Datum_js__ = __webpack_require__(104);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__helpers_ContentListHelper_js__ = __webpack_require__(106);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }




var OrderedList = function (_Datum) {
    _inherits(OrderedList, _Datum);

    function OrderedList() {
        _classCallCheck(this, OrderedList);

        var _this = _possibleConstructorReturn(this, (OrderedList.__proto__ || Object.getPrototypeOf(OrderedList)).call(this));
        // invokes the setter


        _this.clear();

        return _this;
    }

    _createClass(OrderedList, [{
        key: 'addListItem',
        value: function addListItem(list_item) {

            if (list_item === undefined) {
                this._list_items.push(new ListItem());
            } else this._list_items.push(list_item);
        }
    }, {
        key: 'removeListItem',
        value: function removeListItem(list_item) {
            var index = this._list_items.indexOf(list_item);
            this.removeListItemIndex(index);
        }
    }, {
        key: 'removeListItemIndex',
        value: function removeListItemIndex(index) {
            this._list_items.splice(index, 1);
        }
    }, {
        key: 'clear',
        value: function clear() {
            this._type = 'list';
            this._has_parent = true;

            this._list_items = new Array();
            this._meta = {
                style: 'number'
                // none = ''
                // number
                // lower_alpha
                // upper_alpha
                // circle
                // square
                // upper_roman
                // lower_roman
            };
        }
    }, {
        key: 'toShortString',
        value: function toShortString() {
            var str = this.toString();
            if (str === null || str === '') return false;else {

                // Remove special chars/html
                str = str.toString();
                str = str.replace(/<[^>]*>/g, '');
                // Shorten the content
                if (str.length > 50) return str.substring(0, 50) + '...';else return str;
            }
        }
    }, {
        key: 'toString',
        value: function toString(parentStyle) {
            return __WEBPACK_IMPORTED_MODULE_1__helpers_ContentListHelper_js__["a" /* ContentListHelper */].list_representation(this, parentStyle);
        }
    }, {
        key: 'list_items',
        get: function get() {
            return this._list_items;
        },
        set: function set(list_items) {
            this._list_items = list_items;
        }
    }, {
        key: 'has_parent',
        get: function get() {
            return this._has_parent;
        },
        set: function set(has_parent) {
            this._has_parent = has_parent;
        }
    }]);

    return OrderedList;
}(__WEBPACK_IMPORTED_MODULE_0__Datum_js__["a" /* Datum */]);

/***/ }),
/* 123 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ListItem; });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ListItem = function () {
    function ListItem(data, ordered_list, is_list) {
        _classCallCheck(this, ListItem);

        // invokes the setter
        if (data !== undefined) this._data = data;else this._data = new Paragraph();

        if (ordered_list !== undefined && ordered_list !== null) this._ordered_list = ordered_list;else this._ordered_list = new Array();

        if (is_list !== undefined) this._is_list = is_list;else this._is_list = true;

        this._meta = {
            html: ''
        };
    }

    _createClass(ListItem, [{
        key: 'addOrderedList',
        value: function addOrderedList(obj) {
            if (obj === undefined) {
                //var ordered_list = new OrderedList();
                //ordered_list.addListItem();
                this.ordered_list.push(new OrderedList());
            } else {
                this.ordered_list.push(obj);
            }
        }
    }, {
        key: 'removeOrderedList',
        value: function removeOrderedList(ordered_list) {
            var index = this.ordered_list.indexOf(ordered_list);
            this.removeOrderedListIndex(index);
        }
    }, {
        key: 'removeOrderedListIndex',
        value: function removeOrderedListIndex(index) {
            this.ordered_list.splice(index, 1);
        }
    }, {
        key: 'is_list',
        get: function get() {
            return this._is_list;
        },
        set: function set(is_list) {
            this._is_list = is_list;
        }
    }, {
        key: 'meta',
        get: function get() {
            return this._meta;
        },
        set: function set(meta) {
            this._meta = meta;
        }
    }, {
        key: 'data',
        get: function get() {
            return this._data;
        },
        set: function set(data) {
            this._data = data;
        }
    }, {
        key: 'ordered_list',
        get: function get() {
            return this._ordered_list;
        },
        set: function set(ordered_list) {
            this._ordered_list = ordered_list;
        }
    }]);

    return ListItem;
}();

/***/ }),
/* 124 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return EventBus; });
var EventBus = new Vue();

/***/ }),
/* 125 */
/***/ (function(module, exports, __webpack_require__) {

// Item Components
Vue.component('c-image', __webpack_require__(126));
Vue.component('c-table', __webpack_require__(129));
Vue.component('c-par', __webpack_require__(132));
Vue.component('c-list', __webpack_require__(135));
// Item Components

/***/ }),
/* 126 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(127)
/* template */
var __vue_template__ = __webpack_require__(128)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/component_items/c_image.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3d3dcf42", Component.options)
  } else {
    hotAPI.reload("data-v-3d3dcf42", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 127 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        value: {
            type: [Object, Array],
            default: function _default() {
                return new Image();
            }
        }
    },
    data: function data() {
        return {

            image: new Image(),
            file: null,
            submitted: false
        };
    },
    methods: {
        done: function done(response) {
            this.image.upload_id = response.data.upload_id;
        },
        upload: function upload() {
            var par = this;
            if (par.submitted === false) {
                par.submitted = true;
                // Show wait dialog
                par.show_wait("Please wait while the system is uploading the files....");

                // Set the form data
                var form = new FormData();

                form.append('file', par.file);

                form.append('_method', 'PUT');
                // Send request to server
                axios.post('/upload/image', form, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(function (response) {
                    //hide wait dialog
                    par.hide_wait();
                    par.submitted = false;
                    par.done(response);
                }).catch(function (error) {
                    //hide wait dialog
                    par.hide_wait();
                    par.submitted = false;
                    par.alert_failed(error);
                });
            }
        },
        reset: function reset() {
            this.file = null;
            this.image = new Image();
            this.$emit('input', this.image);
        }
    },
    mounted: function mounted() {
        this.$nextTick(function () {
            this.image = this.value;
        });
    },

    watch: {
        image: {
            deep: true,
            handler: function handler(image) {
                this.$emit('input', image);
            }
        },
        value: {
            deep: true,
            handler: function handler(value) {
                this.image = value;
            }
        }
    }
});

/***/ }),
/* 128 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("div", { staticClass: "box box-solid" }, [
      _c("div", { staticClass: "box-body" }, [
        _c(
          "form",
          {
            attrs: { method: "post" },
            on: {
              submit: function($event) {
                $event.preventDefault()
                return _vm.upload($event)
              }
            }
          },
          [
            _c(
              "div",
              { staticClass: "form-group" },
              [
                _c("label", { attrs: { for: "" } }, [_vm._v("Browse File")]),
                _vm._v(" "),
                _c("input-file", {
                  attrs: {
                    accept: ".jpeg,  .png, .jpg",
                    multiple: false,
                    required: true
                  },
                  model: {
                    value: _vm.file,
                    callback: function($$v) {
                      _vm.file = $$v
                    },
                    expression: "file"
                  }
                })
              ],
              1
            ),
            _vm._v(" "),
            _vm._m(0)
          ]
        )
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "box box-solid" }, [
      _vm._m(1),
      _vm._v(" "),
      _c("div", { staticClass: "box-body" }, [
        _c("div", { staticClass: "form-inline" }, [
          _c("div", { staticClass: "form-group" }, [
            _c("label", [_vm._v("Width")]),
            _vm._v(" "),
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.image.meta.width,
                  expression: "image.meta.width"
                }
              ],
              staticClass: "form-control input-sm",
              attrs: { type: "number" },
              domProps: { value: _vm.image.meta.width },
              on: {
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.$set(_vm.image.meta, "width", $event.target.value)
                }
              }
            })
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "form-group" }, [
            _c("label", [_vm._v("Height")]),
            _vm._v(" "),
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.image.meta.height,
                  expression: "image.meta.height"
                }
              ],
              staticClass: "form-control input-sm",
              attrs: { type: "number" },
              domProps: { value: _vm.image.meta.height },
              on: {
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.$set(_vm.image.meta, "height", $event.target.value)
                }
              }
            })
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "form-group" }, [
            _c("label", [_vm._v("Position")]),
            _vm._v(" "),
            _c(
              "select",
              {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.image.meta.position,
                    expression: "image.meta.position"
                  }
                ],
                staticClass: "form-control input-sm",
                on: {
                  change: function($event) {
                    var $$selectedVal = Array.prototype.filter
                      .call($event.target.options, function(o) {
                        return o.selected
                      })
                      .map(function(o) {
                        var val = "_value" in o ? o._value : o.value
                        return val
                      })
                    _vm.$set(
                      _vm.image.meta,
                      "position",
                      $event.target.multiple ? $$selectedVal : $$selectedVal[0]
                    )
                  }
                }
              },
              [
                _c("option", { attrs: { value: "" } }, [
                  _vm._v("-- Select --")
                ]),
                _vm._v(" "),
                _c("option", { attrs: { value: "center" } }, [
                  _vm._v("Center")
                ]),
                _vm._v(" "),
                _c("option", { attrs: { value: "left" } }, [_vm._v("Left")]),
                _vm._v(" "),
                _c("option", { attrs: { value: "right" } }, [_vm._v("Right")])
              ]
            )
          ])
        ])
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "box box-solid" }, [
      _vm._m(2),
      _vm._v(" "),
      _c("div", { staticClass: "box-body" }, [
        _vm.image.upload_id !== null
          ? _c("div", { domProps: { innerHTML: _vm._s(_vm.image.toString()) } })
          : _vm._e()
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "clearfix" })
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "button",
      { staticClass: "btn btn-default btn-sm", attrs: { type: "submit" } },
      [
        _c("i", {
          staticClass: "fa fa-upload",
          attrs: { "aria-hidden": "true" }
        }),
        _vm._v("\n                    Upload")
      ]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "box-header" }, [
      _c("h3", { staticClass: "box-title" }, [_vm._v("Image Controls")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "box-header" }, [
      _c("h3", { staticClass: "box-title" }, [_vm._v("Image Preview")])
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-3d3dcf42", module.exports)
  }
}

/***/ }),
/* 129 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(130)
/* template */
var __vue_template__ = __webpack_require__(131)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/component_items/c_table.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-09e21f96", Component.options)
  } else {
    hotAPI.reload("data-v-09e21f96", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 130 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        value: {
            type: [Object, Array],
            default: function _default() {
                return new Table();
            }
        }
    },
    data: function data() {
        return {
            table: new Table()
        };
    },
    methods: {

        add_row: function add_row() {
            var vm = this;
            // initiate empty row
            var row = [];
            // Loop all data on first row of the table and push empty data to new row
            // This is to initiate exact data to be inserted in the new row
            vm.table.rows[0].forEach(function () {
                row.push('');
            });
            // Push new row to table row
            vm.table.rows.push(row);
        },

        remove_row: function remove_row(index) {
            var vm = this;

            // If the table row is 1 then do not remove
            if (vm.table.rows.length <= 1) {
                alert('Could not remove anymore the first row!');
                return;
            }
            vm.table.rows.splice(index, 1);
        },

        remove_header: function remove_header(index) {
            var vm = this;

            // If the table header is 1 then do not remove
            if (vm.table.header.length <= 1) {
                alert('Could not remove anymore the first header!');
                return;
            }
            vm.table.removeHeaderIndex(index);
        },
        reset: function reset() {
            this.table = new Table();
            this.table.init();
            this.$emit('input', this.table);
        }

    },

    mounted: function mounted() {
        //this.reset();
        this.$nextTick(function () {
            this.table = this.value;
        });
    },

    watch: {
        table: {
            deep: true,
            handler: function handler(table) {
                this.$emit('input', table);
            }
        },
        value: {
            deep: true,
            handler: function handler(value) {
                this.table = value;
            }
        }
    }

});

/***/ }),
/* 131 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "table-responsive" }, [
    _c("div", { staticStyle: { "padding-bottom": "5px" } }, [
      _c(
        "a",
        {
          staticClass: "btn btn-xs btn-default",
          attrs: { href: "#", title: "Add Header" },
          on: {
            click: function($event) {
              $event.preventDefault()
              return _vm.table.addHeader()
            }
          }
        },
        [
          _c("i", {
            staticClass: "fa fa-header",
            attrs: { "aria-hidden": "true" }
          })
        ]
      ),
      _vm._v(" "),
      _c(
        "a",
        {
          staticClass: "btn btn-xs btn-default",
          attrs: { href: "#", title: "Add Row" },
          on: {
            click: function($event) {
              $event.preventDefault()
              return _vm.add_row($event)
            }
          }
        },
        [
          _c("i", {
            staticClass: "fa fa-list-alt",
            attrs: { "aria-hidden": "true" }
          })
        ]
      )
    ]),
    _vm._v(" "),
    _c("table", { staticClass: "table table-bordered" }, [
      _c("thead", [
        _c(
          "tr",
          [
            _c("th", { staticClass: "fit" }),
            _vm._v(" "),
            _vm._l(_vm.table.header, function(value, key) {
              return _c("th", { key: key }, [
                _c("div", { staticClass: "input-group" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.table.header[key],
                        expression: "table.header[key]"
                      }
                    ],
                    staticClass: "form-control",
                    attrs: { type: "text", name: "head-input-" + key },
                    domProps: { value: _vm.table.header[key] },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.table.header, key, $event.target.value)
                      }
                    }
                  }),
                  _vm._v(" "),
                  _c("span", { staticClass: "input-group-btn" }, [
                    _vm.table.header.length > 1
                      ? _c(
                          "a",
                          {
                            staticClass: "btn btn-default text-red",
                            attrs: { href: "#", title: "Remove header" },
                            on: {
                              click: function($event) {
                                return _vm.remove_header(key)
                              }
                            }
                          },
                          [
                            _c("i", {
                              staticClass: "fa fa-trash",
                              attrs: { "aria-hidden": "true" }
                            })
                          ]
                        )
                      : _vm._e()
                  ])
                ])
              ])
            })
          ],
          2
        )
      ]),
      _vm._v(" "),
      _c(
        "tbody",
        _vm._l(_vm.table.rows, function(rowvalue, rowkey) {
          return _c(
            "tr",
            { key: rowkey },
            [
              _c("td", { staticClass: "fit" }, [
                _vm.table.rows.length > 1
                  ? _c(
                      "a",
                      {
                        staticClass: "btn btn-default btn-xs text-red",
                        attrs: { href: "#", title: "Remove row" },
                        on: {
                          click: function($event) {
                            $event.preventDefault()
                            return _vm.remove_row(rowkey)
                          }
                        }
                      },
                      [
                        _c("i", {
                          staticClass: "fa fa-trash",
                          attrs: { "aria-hidden": "true" }
                        })
                      ]
                    )
                  : _vm._e()
              ]),
              _vm._v(" "),
              _vm._l(rowvalue, function(value, tdkey) {
                return _c("td", { key: tdkey }, [
                  _c("textarea", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.table.rows[rowkey][tdkey],
                        expression: "table.rows[rowkey][tdkey]"
                      }
                    ],
                    staticClass: "form-control",
                    attrs: { name: "tditem-tarea-" + tdkey },
                    domProps: { value: _vm.table.rows[rowkey][tdkey] },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(
                          _vm.table.rows[rowkey],
                          tdkey,
                          $event.target.value
                        )
                      }
                    }
                  })
                ])
              })
            ],
            2
          )
        }),
        0
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-09e21f96", module.exports)
  }
}

/***/ }),
/* 132 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(133)
/* template */
var __vue_template__ = __webpack_require__(134)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/component_items/c_paragraph.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7347ae55", Component.options)
  } else {
    hotAPI.reload("data-v-7347ae55", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 133 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        rows: {
            type: [String, Number],
            default: function _default() {
                return 10;
            }
        },
        value: {
            type: [Object, Array],
            default: function _default() {
                return new Paragraph();
            }
        }
    },
    data: function data() {
        return {
            isChunk: false,
            paragraph: new Paragraph()
        };
    },
    methods: {
        replace_highlight: function replace_highlight(toReplace, element) {

            // obtain the index of the first selected character
            var start = element.selectionStart;
            // obtain the index of the last selected character
            var finish = element.selectionEnd;
            //obtain all Text
            var allText = element.value;

            // obtain the selected text
            var sel = allText.substring(start, finish);
            //append te text;
            var newText = allText.substring(0, start) + "<" + toReplace + ">" + sel + "</" + toReplace + ">" + allText.substring(finish, allText.length);

            return newText;
        },
        bold: function bold() {
            this.paragraph.value = this.replace_highlight("bold", this.$refs.textarea);
        },
        italic: function italic() {
            this.paragraph.value = this.replace_highlight("italic", this.$refs.textarea);
        },
        reset: function reset() {
            this.paragraph = new Paragraph();
            this.$emit('input', this.paragraph);
            this.isChunk = false;
        },
        increment_height: function increment_height() {
            this.$refs.textarea.rows += this.textAreaAutoHeight();
        },
        decrease_height: function decrease_height() {
            if (this.$refs.textarea.rows > 1) {
                this.$refs.textarea.rows -= this.textAreaAutoHeight() - 1;
            }
        },
        textAreaAutoHeight: function textAreaAutoHeight() {
            return this.paragraph.value.replace(/\s/g, '').split("\n").length + this.$refs.textarea.scrollHeight / this.$refs.textarea.offsetHeight;
        },
        chunk: function chunk() {
            if (this.isChunk === true) {
                var par_array = new Array();
                var split_value = this.paragraph.value.split('\n');

                split_value.forEach(function (val) {
                    par_array.push(new Paragraph(val));
                });
                return par_array;
            }
            return new Array();
        }
    },
    watch: {
        paragraph: {
            deep: true,
            handler: function handler(val) {
                this.$emit('input', val);
            }
        },
        value: {
            deep: true,
            handler: function handler(val) {
                this.paragraph = val;
            }
        }
    }
});

/***/ }),
/* 134 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("div", { staticClass: "form-group" }, [
      _c("textarea", {
        directives: [
          {
            name: "model",
            rawName: "v-model",
            value: _vm.paragraph.value,
            expression: "paragraph.value"
          }
        ],
        ref: "textarea",
        staticClass: "form-control",
        attrs: { rows: _vm.rows },
        domProps: { value: _vm.paragraph.value },
        on: {
          keypress: function($event) {
            if (
              !$event.type.indexOf("key") &&
              _vm._k($event.keyCode, "enter", 13, $event.key, "Enter")
            ) {
              return null
            }
            return _vm.increment_height($event)
          },
          keydown: function($event) {
            if (!$event.type.indexOf("key") && $event.keyCode !== 8) {
              return null
            }
            return _vm.decrease_height($event)
          },
          input: function($event) {
            if ($event.target.composing) {
              return
            }
            _vm.$set(_vm.paragraph, "value", $event.target.value)
          }
        }
      }),
      _vm._v(" "),
      _c("div", { staticStyle: { "padding-top": "5px" } }, [
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default text-primary",
            attrs: { href: "#", title: "Make it bold" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.bold($event)
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-bold",
              attrs: { "aria-hidden": "true" }
            })
          ]
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default text-primary",
            attrs: { href: "#", title: "Make it italic" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.italic($event)
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-italic",
              attrs: { "aria-hidden": "true" }
            })
          ]
        ),
        _vm._v(" "),
        _c("label", { staticClass: "btn btn-default btn-xs" }, [
          _c("input", {
            directives: [
              {
                name: "model",
                rawName: "v-model",
                value: _vm.isChunk,
                expression: "isChunk"
              }
            ],
            attrs: { type: "checkbox" },
            domProps: {
              checked: Array.isArray(_vm.isChunk)
                ? _vm._i(_vm.isChunk, null) > -1
                : _vm.isChunk
            },
            on: {
              change: function($event) {
                var $$a = _vm.isChunk,
                  $$el = $event.target,
                  $$c = $$el.checked ? true : false
                if (Array.isArray($$a)) {
                  var $$v = null,
                    $$i = _vm._i($$a, $$v)
                  if ($$el.checked) {
                    $$i < 0 && (_vm.isChunk = $$a.concat([$$v]))
                  } else {
                    $$i > -1 &&
                      (_vm.isChunk = $$a
                        .slice(0, $$i)
                        .concat($$a.slice($$i + 1)))
                  }
                } else {
                  _vm.isChunk = $$c
                }
              }
            }
          }),
          _vm._v(" Chunk by break lines\n            ")
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-7347ae55", module.exports)
  }
}

/***/ }),
/* 135 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(136)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(138)
/* template */
var __vue_template__ = __webpack_require__(139)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/component_items/c_list.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-502114f2", Component.options)
  } else {
    hotAPI.reload("data-v-502114f2", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 136 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(137);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(4)("0b4c3418", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-502114f2\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./c_list.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-502114f2\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./c_list.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 137 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)(false);
// imports


// module
exports.push([module.i, "\n.tbl-style {\n    width: 100%;\n    margin-top: 10px;\n    text-align: left;\n}\n.tbl-style tr {\n    border-bottom: 1px solid #44444446;\n}\n.tbl-style td,\n.tbl-style th {\n    padding-top: 10px;\n    padding-bottom: 10px;\n    padding-right: 10px;\n    vertical-align: baseline;\n}\n.tbl-style td.fit,\n.tbl-style th.fit {\n\n    white-space: nowrap;\n    width: 1%;\n}\n\n", ""]);

// exports


/***/ }),
/* 138 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        value: {
            type: [Object, Array],
            default: function _default() {
                return new OrderedList();
            }
        }
    },
    data: function data() {
        return {
            selected_item_form: '',
            list: new OrderedList()
        };
    },
    mounted: function mounted() {
        this.list = this.value;
    },

    watch: {
        list: {
            deep: true,
            handler: function handler(val) {
                this.$emit('input', val);
            }
        },
        value: {
            deep: true,
            handler: function handler(val) {
                this.list = val;
            }
        }

    },
    methods: {
        remove_order_list: function remove_order_list(item_index, ordered_list_index) {
            if (confirm('Are you sure you want to remove the list?') === true) this.list.list_items[item_index].removeOrderedListIndex(ordered_list_index);
        },
        add_item: function add_item(item) {
            this.list.addListItem(new ListItem(item));
        },
        remove_item: function remove_item(index) {
            if (confirm('Are you sure you want to remove the item from list?') === true) this.list.removeListItemIndex(index);
        },
        updated_item: function updated_item(item) {
            this.list.list_items[item.id].data = item.updated_data;
            this.$forceUpdate();
        }
    }
});

/***/ }),
/* 139 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "box box-solid" }, [
    _c(
      "div",
      { staticClass: "box-body with-border" },
      [
        _c(
          "list-style",
          {
            model: {
              value: _vm.list.meta.style,
              callback: function($$v) {
                _vm.$set(_vm.list.meta, "style", $$v)
              },
              expression: "list.meta.style"
            }
          },
          [_vm._t("default")],
          2
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default",
            attrs: { href: "#", title: "Add table" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.$refs.table.show()
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-table",
              attrs: { "aria-hidden": "true" }
            })
          ]
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default",
            attrs: { href: "#", title: "Add paragraph" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.$refs.phar.show()
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-file-o",
              attrs: { "aria-hidden": "true" }
            })
          ]
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default",
            attrs: { href: "#", title: "Add image" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.$refs.image.show()
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-picture-o",
              attrs: { "aria-hidden": "true" }
            })
          ]
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default",
            attrs: { href: "#", title: "Preview list" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.$refs.listPreview.show(_vm.list)
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-eye",
              attrs: { "aria-hidden": "true" }
            })
          ]
        ),
        _vm._v(" "),
        _c(
          "label",
          {
            staticClass: "btn btn-xs btn-default",
            attrs: { title: "Inheret the label of parent" }
          },
          [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.list.has_parent,
                  expression: "list.has_parent"
                }
              ],
              staticStyle: { margin: "0px" },
              attrs: { type: "checkbox" },
              domProps: {
                checked: Array.isArray(_vm.list.has_parent)
                  ? _vm._i(_vm.list.has_parent, null) > -1
                  : _vm.list.has_parent
              },
              on: {
                change: function($event) {
                  var $$a = _vm.list.has_parent,
                    $$el = $event.target,
                    $$c = $$el.checked ? true : false
                  if (Array.isArray($$a)) {
                    var $$v = null,
                      $$i = _vm._i($$a, $$v)
                    if ($$el.checked) {
                      $$i < 0 &&
                        _vm.$set(_vm.list, "has_parent", $$a.concat([$$v]))
                    } else {
                      $$i > -1 &&
                        _vm.$set(
                          _vm.list,
                          "has_parent",
                          $$a.slice(0, $$i).concat($$a.slice($$i + 1))
                        )
                    }
                  } else {
                    _vm.$set(_vm.list, "has_parent", $$c)
                  }
                }
              }
            })
          ]
        )
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "div",
      { staticClass: "box-body" },
      [
        _c("table", { staticClass: "tbl-style" }, [
          _c(
            "tbody",
            [
              _c(
                "draggable",
                {
                  attrs: {
                    group: "od_list_item" + _vm._uid,
                    handle: ".control-odi"
                  },
                  on: {
                    start: function($event) {
                      _vm.drag = true
                    },
                    end: function($event) {
                      _vm.drag = false
                    }
                  },
                  model: {
                    value: _vm.list.list_items,
                    callback: function($$v) {
                      _vm.$set(_vm.list, "list_items", $$v)
                    },
                    expression: "list.list_items"
                  }
                },
                _vm._l(_vm.list.list_items, function(value, key) {
                  return _c("tr", { key: key }, [
                    _c("td", { staticClass: "fit" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.list.list_items[key].is_list,
                            expression: "list.list_items[key].is_list"
                          }
                        ],
                        attrs: { type: "checkbox" },
                        domProps: {
                          checked: Array.isArray(
                            _vm.list.list_items[key].is_list
                          )
                            ? _vm._i(_vm.list.list_items[key].is_list, null) >
                              -1
                            : _vm.list.list_items[key].is_list
                        },
                        on: {
                          change: function($event) {
                            var $$a = _vm.list.list_items[key].is_list,
                              $$el = $event.target,
                              $$c = $$el.checked ? true : false
                            if (Array.isArray($$a)) {
                              var $$v = null,
                                $$i = _vm._i($$a, $$v)
                              if ($$el.checked) {
                                $$i < 0 &&
                                  _vm.$set(
                                    _vm.list.list_items[key],
                                    "is_list",
                                    $$a.concat([$$v])
                                  )
                              } else {
                                $$i > -1 &&
                                  _vm.$set(
                                    _vm.list.list_items[key],
                                    "is_list",
                                    $$a.slice(0, $$i).concat($$a.slice($$i + 1))
                                  )
                              }
                            } else {
                              _vm.$set(_vm.list.list_items[key], "is_list", $$c)
                            }
                          }
                        }
                      })
                    ]),
                    _vm._v(" "),
                    _c(
                      "td",
                      [
                        _c("span", {
                          domProps: { innerHTML: _vm._s(value.data.toString()) }
                        }),
                        _vm._v(" "),
                        _c("div", { staticClass: "item-btn-action" }, [
                          value.data.type === "table"
                            ? _c(
                                "a",
                                {
                                  staticClass:
                                    "btn btn-xs btn-default text-yellow",
                                  attrs: { href: "#", title: "Edit item" },
                                  on: {
                                    click: function($event) {
                                      $event.preventDefault()
                                      return _vm.$refs.table.edit(
                                        value.data,
                                        key
                                      )
                                    }
                                  }
                                },
                                [
                                  _c("i", {
                                    staticClass: "fa fa-pencil",
                                    attrs: { "aria-hidden": "true" }
                                  })
                                ]
                              )
                            : value.data.type === "paragraph"
                            ? _c(
                                "a",
                                {
                                  staticClass:
                                    "btn btn-xs btn-default text-yellow",
                                  attrs: { href: "#", title: "Edit item" },
                                  on: {
                                    click: function($event) {
                                      $event.preventDefault()
                                      return _vm.$refs.phar.edit(
                                        value.data,
                                        key
                                      )
                                    }
                                  }
                                },
                                [
                                  _c("i", {
                                    staticClass: "fa fa-pencil",
                                    attrs: { "aria-hidden": "true" }
                                  })
                                ]
                              )
                            : value.data.type === "image"
                            ? _c(
                                "a",
                                {
                                  staticClass:
                                    "btn btn-xs btn-default text-yellow",
                                  attrs: { href: "#", title: "Edit item" },
                                  on: {
                                    click: function($event) {
                                      $event.preventDefault()
                                      return _vm.$refs.image.edit(
                                        value.data,
                                        key
                                      )
                                    }
                                  }
                                },
                                [
                                  _c("i", {
                                    staticClass: "fa fa-pencil",
                                    attrs: { "aria-hidden": "true" }
                                  })
                                ]
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _c(
                            "a",
                            {
                              staticClass: "btn btn-xs btn-default text-red",
                              attrs: { href: "#", title: "Remove list" },
                              on: {
                                click: function($event) {
                                  $event.preventDefault()
                                  return _vm.remove_item(key)
                                }
                              }
                            },
                            [
                              _c("i", {
                                staticClass: "fa fa-trash",
                                attrs: { "aria-hidden": "true" }
                              })
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "a",
                            {
                              staticClass: "btn btn-xs btn-default",
                              attrs: { href: "#", title: "Add list" },
                              on: {
                                click: function($event) {
                                  $event.preventDefault()
                                  return _vm.list.list_items[
                                    key
                                  ].addOrderedList()
                                }
                              }
                            },
                            [
                              _c("i", {
                                staticClass: "fa fa-list",
                                attrs: { "aria-hidden": "true" }
                              })
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "a",
                            {
                              staticClass:
                                "btn btn-xs btn-default text-blue control-odi",
                              attrs: { href: "#", title: "Move Order" },
                              on: {
                                click: function($event) {
                                  $event.preventDefault()
                                }
                              }
                            },
                            [
                              _c("i", {
                                staticClass: "fa fa-arrows",
                                attrs: { "aria-hidden": "true" }
                              })
                            ]
                          )
                        ]),
                        _vm._v(" "),
                        _vm._l(value.ordered_list, function(orValue, orKey) {
                          return _c(
                            "div",
                            {
                              key: orKey,
                              staticStyle: { "padding-top": "10px" }
                            },
                            [
                              _c(
                                "c-list",
                                {
                                  model: {
                                    value: value.ordered_list[orKey],
                                    callback: function($$v) {
                                      _vm.$set(value.ordered_list, orKey, $$v)
                                    },
                                    expression: "value.ordered_list[orKey]"
                                  }
                                },
                                [
                                  _c(
                                    "a",
                                    {
                                      staticClass:
                                        "btn btn-xs btn-default text-red pull-right",
                                      attrs: {
                                        href: "#",
                                        title: "Remove list"
                                      },
                                      on: {
                                        click: function($event) {
                                          $event.preventDefault()
                                          return _vm.remove_order_list(
                                            key,
                                            orKey
                                          )
                                        }
                                      }
                                    },
                                    [
                                      _c("i", {
                                        staticClass: "fa fa-trash",
                                        attrs: { "aria-hidden": "true" }
                                      })
                                    ]
                                  )
                                ]
                              )
                            ],
                            1
                          )
                        })
                      ],
                      2
                    )
                  ])
                }),
                0
              )
            ],
            1
          )
        ]),
        _vm._v(" "),
        _c("phar", {
          ref: "phar",
          on: { saved: _vm.add_item, updated: _vm.updated_item }
        }),
        _vm._v(" "),
        _c("tbl", {
          ref: "table",
          on: { saved: _vm.add_item, updated: _vm.updated_item }
        }),
        _vm._v(" "),
        _c("com-image", {
          ref: "image",
          on: { saved: _vm.add_item, updated: _vm.updated_item }
        }),
        _vm._v(" "),
        _c("list-preview", { ref: "listPreview" })
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-502114f2", module.exports)
  }
}

/***/ }),
/* 140 */
/***/ (function(module, exports, __webpack_require__) {

(function(t,n){ true?module.exports=n(__webpack_require__(141)):"function"===typeof define&&define.amd?define(["sortablejs"],n):"object"===typeof exports?exports["vuedraggable"]=n(require("sortablejs")):t["vuedraggable"]=n(t["Sortable"])})("undefined"!==typeof self?self:this,function(t){return function(t){var n={};function e(r){if(n[r])return n[r].exports;var o=n[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,e),o.l=!0,o.exports}return e.m=t,e.c=n,e.d=function(t,n,r){e.o(t,n)||Object.defineProperty(t,n,{enumerable:!0,get:r})},e.r=function(t){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},e.t=function(t,n){if(1&n&&(t=e(t)),8&n)return t;if(4&n&&"object"===typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(e.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&n&&"string"!=typeof t)for(var o in t)e.d(r,o,function(n){return t[n]}.bind(null,o));return r},e.n=function(t){var n=t&&t.__esModule?function(){return t["default"]}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},e.p="",e(e.s="fb15")}({"02f4":function(t,n,e){var r=e("4588"),o=e("be13");t.exports=function(t){return function(n,e){var i,u,c=String(o(n)),a=r(e),f=c.length;return a<0||a>=f?t?"":void 0:(i=c.charCodeAt(a),i<55296||i>56319||a+1===f||(u=c.charCodeAt(a+1))<56320||u>57343?t?c.charAt(a):i:t?c.slice(a,a+2):u-56320+(i-55296<<10)+65536)}}},"0390":function(t,n,e){"use strict";var r=e("02f4")(!0);t.exports=function(t,n,e){return n+(e?r(t,n).length:1)}},"07e3":function(t,n){var e={}.hasOwnProperty;t.exports=function(t,n){return e.call(t,n)}},"0bfb":function(t,n,e){"use strict";var r=e("cb7c");t.exports=function(){var t=r(this),n="";return t.global&&(n+="g"),t.ignoreCase&&(n+="i"),t.multiline&&(n+="m"),t.unicode&&(n+="u"),t.sticky&&(n+="y"),n}},"0fc9":function(t,n,e){var r=e("3a38"),o=Math.max,i=Math.min;t.exports=function(t,n){return t=r(t),t<0?o(t+n,0):i(t,n)}},1654:function(t,n,e){"use strict";var r=e("71c1")(!0);e("30f1")(String,"String",function(t){this._t=String(t),this._i=0},function(){var t,n=this._t,e=this._i;return e>=n.length?{value:void 0,done:!0}:(t=r(n,e),this._i+=t.length,{value:t,done:!1})})},1691:function(t,n){t.exports="constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf".split(",")},"1af6":function(t,n,e){var r=e("63b6");r(r.S,"Array",{isArray:e("9003")})},"1bc3":function(t,n,e){var r=e("f772");t.exports=function(t,n){if(!r(t))return t;var e,o;if(n&&"function"==typeof(e=t.toString)&&!r(o=e.call(t)))return o;if("function"==typeof(e=t.valueOf)&&!r(o=e.call(t)))return o;if(!n&&"function"==typeof(e=t.toString)&&!r(o=e.call(t)))return o;throw TypeError("Can't convert object to primitive value")}},"1ec9":function(t,n,e){var r=e("f772"),o=e("e53d").document,i=r(o)&&r(o.createElement);t.exports=function(t){return i?o.createElement(t):{}}},"20fd":function(t,n,e){"use strict";var r=e("d9f6"),o=e("aebd");t.exports=function(t,n,e){n in t?r.f(t,n,o(0,e)):t[n]=e}},"214f":function(t,n,e){"use strict";e("b0c5");var r=e("2aba"),o=e("32e9"),i=e("79e5"),u=e("be13"),c=e("2b4c"),a=e("520a"),f=c("species"),s=!i(function(){var t=/./;return t.exec=function(){var t=[];return t.groups={a:"7"},t},"7"!=="".replace(t,"$<a>")}),l=function(){var t=/(?:)/,n=t.exec;t.exec=function(){return n.apply(this,arguments)};var e="ab".split(t);return 2===e.length&&"a"===e[0]&&"b"===e[1]}();t.exports=function(t,n,e){var p=c(t),d=!i(function(){var n={};return n[p]=function(){return 7},7!=""[t](n)}),v=d?!i(function(){var n=!1,e=/a/;return e.exec=function(){return n=!0,null},"split"===t&&(e.constructor={},e.constructor[f]=function(){return e}),e[p](""),!n}):void 0;if(!d||!v||"replace"===t&&!s||"split"===t&&!l){var h=/./[p],b=e(u,p,""[t],function(t,n,e,r,o){return n.exec===a?d&&!o?{done:!0,value:h.call(n,e,r)}:{done:!0,value:t.call(e,n,r)}:{done:!1}}),g=b[0],y=b[1];r(String.prototype,t,g),o(RegExp.prototype,p,2==n?function(t,n){return y.call(t,this,n)}:function(t){return y.call(t,this)})}}},"230e":function(t,n,e){var r=e("d3f4"),o=e("7726").document,i=r(o)&&r(o.createElement);t.exports=function(t){return i?o.createElement(t):{}}},"23c6":function(t,n,e){var r=e("2d95"),o=e("2b4c")("toStringTag"),i="Arguments"==r(function(){return arguments}()),u=function(t,n){try{return t[n]}catch(e){}};t.exports=function(t){var n,e,c;return void 0===t?"Undefined":null===t?"Null":"string"==typeof(e=u(n=Object(t),o))?e:i?r(n):"Object"==(c=r(n))&&"function"==typeof n.callee?"Arguments":c}},"241e":function(t,n,e){var r=e("25eb");t.exports=function(t){return Object(r(t))}},"25eb":function(t,n){t.exports=function(t){if(void 0==t)throw TypeError("Can't call method on  "+t);return t}},"294c":function(t,n){t.exports=function(t){try{return!!t()}catch(n){return!0}}},"2aba":function(t,n,e){var r=e("7726"),o=e("32e9"),i=e("69a8"),u=e("ca5a")("src"),c=e("fa5b"),a="toString",f=(""+c).split(a);e("8378").inspectSource=function(t){return c.call(t)},(t.exports=function(t,n,e,c){var a="function"==typeof e;a&&(i(e,"name")||o(e,"name",n)),t[n]!==e&&(a&&(i(e,u)||o(e,u,t[n]?""+t[n]:f.join(String(n)))),t===r?t[n]=e:c?t[n]?t[n]=e:o(t,n,e):(delete t[n],o(t,n,e)))})(Function.prototype,a,function(){return"function"==typeof this&&this[u]||c.call(this)})},"2b4c":function(t,n,e){var r=e("5537")("wks"),o=e("ca5a"),i=e("7726").Symbol,u="function"==typeof i,c=t.exports=function(t){return r[t]||(r[t]=u&&i[t]||(u?i:o)("Symbol."+t))};c.store=r},"2d00":function(t,n){t.exports=!1},"2d95":function(t,n){var e={}.toString;t.exports=function(t){return e.call(t).slice(8,-1)}},"2fdb":function(t,n,e){"use strict";var r=e("5ca1"),o=e("d2c8"),i="includes";r(r.P+r.F*e("5147")(i),"String",{includes:function(t){return!!~o(this,t,i).indexOf(t,arguments.length>1?arguments[1]:void 0)}})},"30f1":function(t,n,e){"use strict";var r=e("b8e3"),o=e("63b6"),i=e("9138"),u=e("35e8"),c=e("481b"),a=e("8f60"),f=e("45f2"),s=e("53e2"),l=e("5168")("iterator"),p=!([].keys&&"next"in[].keys()),d="@@iterator",v="keys",h="values",b=function(){return this};t.exports=function(t,n,e,g,y,x,m){a(e,n,g);var w,O,S,j=function(t){if(!p&&t in C)return C[t];switch(t){case v:return function(){return new e(this,t)};case h:return function(){return new e(this,t)}}return function(){return new e(this,t)}},_=n+" Iterator",M=y==h,T=!1,C=t.prototype,E=C[l]||C[d]||y&&C[y],A=E||j(y),P=y?M?j("entries"):A:void 0,I="Array"==n&&C.entries||E;if(I&&(S=s(I.call(new t)),S!==Object.prototype&&S.next&&(f(S,_,!0),r||"function"==typeof S[l]||u(S,l,b))),M&&E&&E.name!==h&&(T=!0,A=function(){return E.call(this)}),r&&!m||!p&&!T&&C[l]||u(C,l,A),c[n]=A,c[_]=b,y)if(w={values:M?A:j(h),keys:x?A:j(v),entries:P},m)for(O in w)O in C||i(C,O,w[O]);else o(o.P+o.F*(p||T),n,w);return w}},"32a6":function(t,n,e){var r=e("241e"),o=e("c3a1");e("ce7e")("keys",function(){return function(t){return o(r(t))}})},"32e9":function(t,n,e){var r=e("86cc"),o=e("4630");t.exports=e("9e1e")?function(t,n,e){return r.f(t,n,o(1,e))}:function(t,n,e){return t[n]=e,t}},"32fc":function(t,n,e){var r=e("e53d").document;t.exports=r&&r.documentElement},"335c":function(t,n,e){var r=e("6b4c");t.exports=Object("z").propertyIsEnumerable(0)?Object:function(t){return"String"==r(t)?t.split(""):Object(t)}},"355d":function(t,n){n.f={}.propertyIsEnumerable},"35e8":function(t,n,e){var r=e("d9f6"),o=e("aebd");t.exports=e("8e60")?function(t,n,e){return r.f(t,n,o(1,e))}:function(t,n,e){return t[n]=e,t}},"36c3":function(t,n,e){var r=e("335c"),o=e("25eb");t.exports=function(t){return r(o(t))}},3702:function(t,n,e){var r=e("481b"),o=e("5168")("iterator"),i=Array.prototype;t.exports=function(t){return void 0!==t&&(r.Array===t||i[o]===t)}},"3a38":function(t,n){var e=Math.ceil,r=Math.floor;t.exports=function(t){return isNaN(t=+t)?0:(t>0?r:e)(t)}},"40c3":function(t,n,e){var r=e("6b4c"),o=e("5168")("toStringTag"),i="Arguments"==r(function(){return arguments}()),u=function(t,n){try{return t[n]}catch(e){}};t.exports=function(t){var n,e,c;return void 0===t?"Undefined":null===t?"Null":"string"==typeof(e=u(n=Object(t),o))?e:i?r(n):"Object"==(c=r(n))&&"function"==typeof n.callee?"Arguments":c}},4588:function(t,n){var e=Math.ceil,r=Math.floor;t.exports=function(t){return isNaN(t=+t)?0:(t>0?r:e)(t)}},"45f2":function(t,n,e){var r=e("d9f6").f,o=e("07e3"),i=e("5168")("toStringTag");t.exports=function(t,n,e){t&&!o(t=e?t:t.prototype,i)&&r(t,i,{configurable:!0,value:n})}},4630:function(t,n){t.exports=function(t,n){return{enumerable:!(1&t),configurable:!(2&t),writable:!(4&t),value:n}}},"469f":function(t,n,e){e("6c1c"),e("1654"),t.exports=e("7d7b")},"481b":function(t,n){t.exports={}},"4aa6":function(t,n,e){t.exports=e("dc62")},"4bf8":function(t,n,e){var r=e("be13");t.exports=function(t){return Object(r(t))}},"4ee1":function(t,n,e){var r=e("5168")("iterator"),o=!1;try{var i=[7][r]();i["return"]=function(){o=!0},Array.from(i,function(){throw 2})}catch(u){}t.exports=function(t,n){if(!n&&!o)return!1;var e=!1;try{var i=[7],c=i[r]();c.next=function(){return{done:e=!0}},i[r]=function(){return c},t(i)}catch(u){}return e}},"50ed":function(t,n){t.exports=function(t,n){return{value:n,done:!!t}}},5147:function(t,n,e){var r=e("2b4c")("match");t.exports=function(t){var n=/./;try{"/./"[t](n)}catch(e){try{return n[r]=!1,!"/./"[t](n)}catch(o){}}return!0}},5168:function(t,n,e){var r=e("dbdb")("wks"),o=e("62a0"),i=e("e53d").Symbol,u="function"==typeof i,c=t.exports=function(t){return r[t]||(r[t]=u&&i[t]||(u?i:o)("Symbol."+t))};c.store=r},5176:function(t,n,e){t.exports=e("51b6")},"51b6":function(t,n,e){e("a3c3"),t.exports=e("584a").Object.assign},"520a":function(t,n,e){"use strict";var r=e("0bfb"),o=RegExp.prototype.exec,i=String.prototype.replace,u=o,c="lastIndex",a=function(){var t=/a/,n=/b*/g;return o.call(t,"a"),o.call(n,"a"),0!==t[c]||0!==n[c]}(),f=void 0!==/()??/.exec("")[1],s=a||f;s&&(u=function(t){var n,e,u,s,l=this;return f&&(e=new RegExp("^"+l.source+"$(?!\\s)",r.call(l))),a&&(n=l[c]),u=o.call(l,t),a&&u&&(l[c]=l.global?u.index+u[0].length:n),f&&u&&u.length>1&&i.call(u[0],e,function(){for(s=1;s<arguments.length-2;s++)void 0===arguments[s]&&(u[s]=void 0)}),u}),t.exports=u},"53e2":function(t,n,e){var r=e("07e3"),o=e("241e"),i=e("5559")("IE_PROTO"),u=Object.prototype;t.exports=Object.getPrototypeOf||function(t){return t=o(t),r(t,i)?t[i]:"function"==typeof t.constructor&&t instanceof t.constructor?t.constructor.prototype:t instanceof Object?u:null}},"549b":function(t,n,e){"use strict";var r=e("d864"),o=e("63b6"),i=e("241e"),u=e("b0dc"),c=e("3702"),a=e("b447"),f=e("20fd"),s=e("7cd6");o(o.S+o.F*!e("4ee1")(function(t){Array.from(t)}),"Array",{from:function(t){var n,e,o,l,p=i(t),d="function"==typeof this?this:Array,v=arguments.length,h=v>1?arguments[1]:void 0,b=void 0!==h,g=0,y=s(p);if(b&&(h=r(h,v>2?arguments[2]:void 0,2)),void 0==y||d==Array&&c(y))for(n=a(p.length),e=new d(n);n>g;g++)f(e,g,b?h(p[g],g):p[g]);else for(l=y.call(p),e=new d;!(o=l.next()).done;g++)f(e,g,b?u(l,h,[o.value,g],!0):o.value);return e.length=g,e}})},"54a1":function(t,n,e){e("6c1c"),e("1654"),t.exports=e("95d5")},5537:function(t,n,e){var r=e("8378"),o=e("7726"),i="__core-js_shared__",u=o[i]||(o[i]={});(t.exports=function(t,n){return u[t]||(u[t]=void 0!==n?n:{})})("versions",[]).push({version:r.version,mode:e("2d00")?"pure":"global",copyright:"© 2019 Denis Pushkarev (zloirock.ru)"})},5559:function(t,n,e){var r=e("dbdb")("keys"),o=e("62a0");t.exports=function(t){return r[t]||(r[t]=o(t))}},"584a":function(t,n){var e=t.exports={version:"2.6.5"};"number"==typeof __e&&(__e=e)},"5b4e":function(t,n,e){var r=e("36c3"),o=e("b447"),i=e("0fc9");t.exports=function(t){return function(n,e,u){var c,a=r(n),f=o(a.length),s=i(u,f);if(t&&e!=e){while(f>s)if(c=a[s++],c!=c)return!0}else for(;f>s;s++)if((t||s in a)&&a[s]===e)return t||s||0;return!t&&-1}}},"5ca1":function(t,n,e){var r=e("7726"),o=e("8378"),i=e("32e9"),u=e("2aba"),c=e("9b43"),a="prototype",f=function(t,n,e){var s,l,p,d,v=t&f.F,h=t&f.G,b=t&f.S,g=t&f.P,y=t&f.B,x=h?r:b?r[n]||(r[n]={}):(r[n]||{})[a],m=h?o:o[n]||(o[n]={}),w=m[a]||(m[a]={});for(s in h&&(e=n),e)l=!v&&x&&void 0!==x[s],p=(l?x:e)[s],d=y&&l?c(p,r):g&&"function"==typeof p?c(Function.call,p):p,x&&u(x,s,p,t&f.U),m[s]!=p&&i(m,s,d),g&&w[s]!=p&&(w[s]=p)};r.core=o,f.F=1,f.G=2,f.S=4,f.P=8,f.B=16,f.W=32,f.U=64,f.R=128,t.exports=f},"5d73":function(t,n,e){t.exports=e("469f")},"5f1b":function(t,n,e){"use strict";var r=e("23c6"),o=RegExp.prototype.exec;t.exports=function(t,n){var e=t.exec;if("function"===typeof e){var i=e.call(t,n);if("object"!==typeof i)throw new TypeError("RegExp exec method returned something other than an Object or null");return i}if("RegExp"!==r(t))throw new TypeError("RegExp#exec called on incompatible receiver");return o.call(t,n)}},"626a":function(t,n,e){var r=e("2d95");t.exports=Object("z").propertyIsEnumerable(0)?Object:function(t){return"String"==r(t)?t.split(""):Object(t)}},"62a0":function(t,n){var e=0,r=Math.random();t.exports=function(t){return"Symbol(".concat(void 0===t?"":t,")_",(++e+r).toString(36))}},"63b6":function(t,n,e){var r=e("e53d"),o=e("584a"),i=e("d864"),u=e("35e8"),c=e("07e3"),a="prototype",f=function(t,n,e){var s,l,p,d=t&f.F,v=t&f.G,h=t&f.S,b=t&f.P,g=t&f.B,y=t&f.W,x=v?o:o[n]||(o[n]={}),m=x[a],w=v?r:h?r[n]:(r[n]||{})[a];for(s in v&&(e=n),e)l=!d&&w&&void 0!==w[s],l&&c(x,s)||(p=l?w[s]:e[s],x[s]=v&&"function"!=typeof w[s]?e[s]:g&&l?i(p,r):y&&w[s]==p?function(t){var n=function(n,e,r){if(this instanceof t){switch(arguments.length){case 0:return new t;case 1:return new t(n);case 2:return new t(n,e)}return new t(n,e,r)}return t.apply(this,arguments)};return n[a]=t[a],n}(p):b&&"function"==typeof p?i(Function.call,p):p,b&&((x.virtual||(x.virtual={}))[s]=p,t&f.R&&m&&!m[s]&&u(m,s,p)))};f.F=1,f.G=2,f.S=4,f.P=8,f.B=16,f.W=32,f.U=64,f.R=128,t.exports=f},6762:function(t,n,e){"use strict";var r=e("5ca1"),o=e("c366")(!0);r(r.P,"Array",{includes:function(t){return o(this,t,arguments.length>1?arguments[1]:void 0)}}),e("9c6c")("includes")},6821:function(t,n,e){var r=e("626a"),o=e("be13");t.exports=function(t){return r(o(t))}},"69a8":function(t,n){var e={}.hasOwnProperty;t.exports=function(t,n){return e.call(t,n)}},"6a99":function(t,n,e){var r=e("d3f4");t.exports=function(t,n){if(!r(t))return t;var e,o;if(n&&"function"==typeof(e=t.toString)&&!r(o=e.call(t)))return o;if("function"==typeof(e=t.valueOf)&&!r(o=e.call(t)))return o;if(!n&&"function"==typeof(e=t.toString)&&!r(o=e.call(t)))return o;throw TypeError("Can't convert object to primitive value")}},"6b4c":function(t,n){var e={}.toString;t.exports=function(t){return e.call(t).slice(8,-1)}},"6c1c":function(t,n,e){e("c367");for(var r=e("e53d"),o=e("35e8"),i=e("481b"),u=e("5168")("toStringTag"),c="CSSRuleList,CSSStyleDeclaration,CSSValueList,ClientRectList,DOMRectList,DOMStringList,DOMTokenList,DataTransferItemList,FileList,HTMLAllCollection,HTMLCollection,HTMLFormElement,HTMLSelectElement,MediaList,MimeTypeArray,NamedNodeMap,NodeList,PaintRequestList,Plugin,PluginArray,SVGLengthList,SVGNumberList,SVGPathSegList,SVGPointList,SVGStringList,SVGTransformList,SourceBufferList,StyleSheetList,TextTrackCueList,TextTrackList,TouchList".split(","),a=0;a<c.length;a++){var f=c[a],s=r[f],l=s&&s.prototype;l&&!l[u]&&o(l,u,f),i[f]=i.Array}},"71c1":function(t,n,e){var r=e("3a38"),o=e("25eb");t.exports=function(t){return function(n,e){var i,u,c=String(o(n)),a=r(e),f=c.length;return a<0||a>=f?t?"":void 0:(i=c.charCodeAt(a),i<55296||i>56319||a+1===f||(u=c.charCodeAt(a+1))<56320||u>57343?t?c.charAt(a):i:t?c.slice(a,a+2):u-56320+(i-55296<<10)+65536)}}},7726:function(t,n){var e=t.exports="undefined"!=typeof window&&window.Math==Math?window:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")();"number"==typeof __g&&(__g=e)},"774e":function(t,n,e){t.exports=e("d2d5")},"77f1":function(t,n,e){var r=e("4588"),o=Math.max,i=Math.min;t.exports=function(t,n){return t=r(t),t<0?o(t+n,0):i(t,n)}},"794b":function(t,n,e){t.exports=!e("8e60")&&!e("294c")(function(){return 7!=Object.defineProperty(e("1ec9")("div"),"a",{get:function(){return 7}}).a})},"79aa":function(t,n){t.exports=function(t){if("function"!=typeof t)throw TypeError(t+" is not a function!");return t}},"79e5":function(t,n){t.exports=function(t){try{return!!t()}catch(n){return!0}}},"7cd6":function(t,n,e){var r=e("40c3"),o=e("5168")("iterator"),i=e("481b");t.exports=e("584a").getIteratorMethod=function(t){if(void 0!=t)return t[o]||t["@@iterator"]||i[r(t)]}},"7d7b":function(t,n,e){var r=e("e4ae"),o=e("7cd6");t.exports=e("584a").getIterator=function(t){var n=o(t);if("function"!=typeof n)throw TypeError(t+" is not iterable!");return r(n.call(t))}},"7e90":function(t,n,e){var r=e("d9f6"),o=e("e4ae"),i=e("c3a1");t.exports=e("8e60")?Object.defineProperties:function(t,n){o(t);var e,u=i(n),c=u.length,a=0;while(c>a)r.f(t,e=u[a++],n[e]);return t}},8378:function(t,n){var e=t.exports={version:"2.6.5"};"number"==typeof __e&&(__e=e)},8436:function(t,n){t.exports=function(){}},"86cc":function(t,n,e){var r=e("cb7c"),o=e("c69a"),i=e("6a99"),u=Object.defineProperty;n.f=e("9e1e")?Object.defineProperty:function(t,n,e){if(r(t),n=i(n,!0),r(e),o)try{return u(t,n,e)}catch(c){}if("get"in e||"set"in e)throw TypeError("Accessors not supported!");return"value"in e&&(t[n]=e.value),t}},"8aae":function(t,n,e){e("32a6"),t.exports=e("584a").Object.keys},"8e60":function(t,n,e){t.exports=!e("294c")(function(){return 7!=Object.defineProperty({},"a",{get:function(){return 7}}).a})},"8f60":function(t,n,e){"use strict";var r=e("a159"),o=e("aebd"),i=e("45f2"),u={};e("35e8")(u,e("5168")("iterator"),function(){return this}),t.exports=function(t,n,e){t.prototype=r(u,{next:o(1,e)}),i(t,n+" Iterator")}},9003:function(t,n,e){var r=e("6b4c");t.exports=Array.isArray||function(t){return"Array"==r(t)}},9138:function(t,n,e){t.exports=e("35e8")},9306:function(t,n,e){"use strict";var r=e("c3a1"),o=e("9aa9"),i=e("355d"),u=e("241e"),c=e("335c"),a=Object.assign;t.exports=!a||e("294c")(function(){var t={},n={},e=Symbol(),r="abcdefghijklmnopqrst";return t[e]=7,r.split("").forEach(function(t){n[t]=t}),7!=a({},t)[e]||Object.keys(a({},n)).join("")!=r})?function(t,n){var e=u(t),a=arguments.length,f=1,s=o.f,l=i.f;while(a>f){var p,d=c(arguments[f++]),v=s?r(d).concat(s(d)):r(d),h=v.length,b=0;while(h>b)l.call(d,p=v[b++])&&(e[p]=d[p])}return e}:a},9427:function(t,n,e){var r=e("63b6");r(r.S,"Object",{create:e("a159")})},"95d5":function(t,n,e){var r=e("40c3"),o=e("5168")("iterator"),i=e("481b");t.exports=e("584a").isIterable=function(t){var n=Object(t);return void 0!==n[o]||"@@iterator"in n||i.hasOwnProperty(r(n))}},"9aa9":function(t,n){n.f=Object.getOwnPropertySymbols},"9b43":function(t,n,e){var r=e("d8e8");t.exports=function(t,n,e){if(r(t),void 0===n)return t;switch(e){case 1:return function(e){return t.call(n,e)};case 2:return function(e,r){return t.call(n,e,r)};case 3:return function(e,r,o){return t.call(n,e,r,o)}}return function(){return t.apply(n,arguments)}}},"9c6c":function(t,n,e){var r=e("2b4c")("unscopables"),o=Array.prototype;void 0==o[r]&&e("32e9")(o,r,{}),t.exports=function(t){o[r][t]=!0}},"9def":function(t,n,e){var r=e("4588"),o=Math.min;t.exports=function(t){return t>0?o(r(t),9007199254740991):0}},"9e1e":function(t,n,e){t.exports=!e("79e5")(function(){return 7!=Object.defineProperty({},"a",{get:function(){return 7}}).a})},a159:function(t,n,e){var r=e("e4ae"),o=e("7e90"),i=e("1691"),u=e("5559")("IE_PROTO"),c=function(){},a="prototype",f=function(){var t,n=e("1ec9")("iframe"),r=i.length,o="<",u=">";n.style.display="none",e("32fc").appendChild(n),n.src="javascript:",t=n.contentWindow.document,t.open(),t.write(o+"script"+u+"document.F=Object"+o+"/script"+u),t.close(),f=t.F;while(r--)delete f[a][i[r]];return f()};t.exports=Object.create||function(t,n){var e;return null!==t?(c[a]=r(t),e=new c,c[a]=null,e[u]=t):e=f(),void 0===n?e:o(e,n)}},a352:function(n,e){n.exports=t},a3c3:function(t,n,e){var r=e("63b6");r(r.S+r.F,"Object",{assign:e("9306")})},a481:function(t,n,e){"use strict";var r=e("cb7c"),o=e("4bf8"),i=e("9def"),u=e("4588"),c=e("0390"),a=e("5f1b"),f=Math.max,s=Math.min,l=Math.floor,p=/\$([$&`']|\d\d?|<[^>]*>)/g,d=/\$([$&`']|\d\d?)/g,v=function(t){return void 0===t?t:String(t)};e("214f")("replace",2,function(t,n,e,h){return[function(r,o){var i=t(this),u=void 0==r?void 0:r[n];return void 0!==u?u.call(r,i,o):e.call(String(i),r,o)},function(t,n){var o=h(e,t,this,n);if(o.done)return o.value;var l=r(t),p=String(this),d="function"===typeof n;d||(n=String(n));var g=l.global;if(g){var y=l.unicode;l.lastIndex=0}var x=[];while(1){var m=a(l,p);if(null===m)break;if(x.push(m),!g)break;var w=String(m[0]);""===w&&(l.lastIndex=c(p,i(l.lastIndex),y))}for(var O="",S=0,j=0;j<x.length;j++){m=x[j];for(var _=String(m[0]),M=f(s(u(m.index),p.length),0),T=[],C=1;C<m.length;C++)T.push(v(m[C]));var E=m.groups;if(d){var A=[_].concat(T,M,p);void 0!==E&&A.push(E);var P=String(n.apply(void 0,A))}else P=b(_,p,M,T,E,n);M>=S&&(O+=p.slice(S,M)+P,S=M+_.length)}return O+p.slice(S)}];function b(t,n,r,i,u,c){var a=r+t.length,f=i.length,s=d;return void 0!==u&&(u=o(u),s=p),e.call(c,s,function(e,o){var c;switch(o.charAt(0)){case"$":return"$";case"&":return t;case"`":return n.slice(0,r);case"'":return n.slice(a);case"<":c=u[o.slice(1,-1)];break;default:var s=+o;if(0===s)return e;if(s>f){var p=l(s/10);return 0===p?e:p<=f?void 0===i[p-1]?o.charAt(1):i[p-1]+o.charAt(1):e}c=i[s-1]}return void 0===c?"":c})}})},a4bb:function(t,n,e){t.exports=e("8aae")},a745:function(t,n,e){t.exports=e("f410")},aae3:function(t,n,e){var r=e("d3f4"),o=e("2d95"),i=e("2b4c")("match");t.exports=function(t){var n;return r(t)&&(void 0!==(n=t[i])?!!n:"RegExp"==o(t))}},aebd:function(t,n){t.exports=function(t,n){return{enumerable:!(1&t),configurable:!(2&t),writable:!(4&t),value:n}}},b0c5:function(t,n,e){"use strict";var r=e("520a");e("5ca1")({target:"RegExp",proto:!0,forced:r!==/./.exec},{exec:r})},b0dc:function(t,n,e){var r=e("e4ae");t.exports=function(t,n,e,o){try{return o?n(r(e)[0],e[1]):n(e)}catch(u){var i=t["return"];throw void 0!==i&&r(i.call(t)),u}}},b447:function(t,n,e){var r=e("3a38"),o=Math.min;t.exports=function(t){return t>0?o(r(t),9007199254740991):0}},b8e3:function(t,n){t.exports=!0},be13:function(t,n){t.exports=function(t){if(void 0==t)throw TypeError("Can't call method on  "+t);return t}},c366:function(t,n,e){var r=e("6821"),o=e("9def"),i=e("77f1");t.exports=function(t){return function(n,e,u){var c,a=r(n),f=o(a.length),s=i(u,f);if(t&&e!=e){while(f>s)if(c=a[s++],c!=c)return!0}else for(;f>s;s++)if((t||s in a)&&a[s]===e)return t||s||0;return!t&&-1}}},c367:function(t,n,e){"use strict";var r=e("8436"),o=e("50ed"),i=e("481b"),u=e("36c3");t.exports=e("30f1")(Array,"Array",function(t,n){this._t=u(t),this._i=0,this._k=n},function(){var t=this._t,n=this._k,e=this._i++;return!t||e>=t.length?(this._t=void 0,o(1)):o(0,"keys"==n?e:"values"==n?t[e]:[e,t[e]])},"values"),i.Arguments=i.Array,r("keys"),r("values"),r("entries")},c3a1:function(t,n,e){var r=e("e6f3"),o=e("1691");t.exports=Object.keys||function(t){return r(t,o)}},c649:function(t,n,e){"use strict";(function(t){e.d(n,"c",function(){return l}),e.d(n,"a",function(){return f}),e.d(n,"b",function(){return u}),e.d(n,"d",function(){return s});e("a481");var r=e("4aa6"),o=e.n(r);function i(){return"undefined"!==typeof window?window.console:t.console}var u=i();function c(t){var n=o()(null);return function(e){var r=n[e];return r||(n[e]=t(e))}}var a=/-(\w)/g,f=c(function(t){return t.replace(a,function(t,n){return n?n.toUpperCase():""})});function s(t){null!==t.parentElement&&t.parentElement.removeChild(t)}function l(t,n,e){var r=0===e?t.children[0]:t.children[e-1].nextSibling;t.insertBefore(n,r)}}).call(this,e("c8ba"))},c69a:function(t,n,e){t.exports=!e("9e1e")&&!e("79e5")(function(){return 7!=Object.defineProperty(e("230e")("div"),"a",{get:function(){return 7}}).a})},c8ba:function(t,n){var e;e=function(){return this}();try{e=e||new Function("return this")()}catch(r){"object"===typeof window&&(e=window)}t.exports=e},c8bb:function(t,n,e){t.exports=e("54a1")},ca5a:function(t,n){var e=0,r=Math.random();t.exports=function(t){return"Symbol(".concat(void 0===t?"":t,")_",(++e+r).toString(36))}},cb7c:function(t,n,e){var r=e("d3f4");t.exports=function(t){if(!r(t))throw TypeError(t+" is not an object!");return t}},ce7e:function(t,n,e){var r=e("63b6"),o=e("584a"),i=e("294c");t.exports=function(t,n){var e=(o.Object||{})[t]||Object[t],u={};u[t]=n(e),r(r.S+r.F*i(function(){e(1)}),"Object",u)}},d2c8:function(t,n,e){var r=e("aae3"),o=e("be13");t.exports=function(t,n,e){if(r(n))throw TypeError("String#"+e+" doesn't accept regex!");return String(o(t))}},d2d5:function(t,n,e){e("1654"),e("549b"),t.exports=e("584a").Array.from},d3f4:function(t,n){t.exports=function(t){return"object"===typeof t?null!==t:"function"===typeof t}},d864:function(t,n,e){var r=e("79aa");t.exports=function(t,n,e){if(r(t),void 0===n)return t;switch(e){case 1:return function(e){return t.call(n,e)};case 2:return function(e,r){return t.call(n,e,r)};case 3:return function(e,r,o){return t.call(n,e,r,o)}}return function(){return t.apply(n,arguments)}}},d8e8:function(t,n){t.exports=function(t){if("function"!=typeof t)throw TypeError(t+" is not a function!");return t}},d9f6:function(t,n,e){var r=e("e4ae"),o=e("794b"),i=e("1bc3"),u=Object.defineProperty;n.f=e("8e60")?Object.defineProperty:function(t,n,e){if(r(t),n=i(n,!0),r(e),o)try{return u(t,n,e)}catch(c){}if("get"in e||"set"in e)throw TypeError("Accessors not supported!");return"value"in e&&(t[n]=e.value),t}},dbdb:function(t,n,e){var r=e("584a"),o=e("e53d"),i="__core-js_shared__",u=o[i]||(o[i]={});(t.exports=function(t,n){return u[t]||(u[t]=void 0!==n?n:{})})("versions",[]).push({version:r.version,mode:e("b8e3")?"pure":"global",copyright:"© 2019 Denis Pushkarev (zloirock.ru)"})},dc62:function(t,n,e){e("9427");var r=e("584a").Object;t.exports=function(t,n){return r.create(t,n)}},e4ae:function(t,n,e){var r=e("f772");t.exports=function(t){if(!r(t))throw TypeError(t+" is not an object!");return t}},e53d:function(t,n){var e=t.exports="undefined"!=typeof window&&window.Math==Math?window:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")();"number"==typeof __g&&(__g=e)},e6f3:function(t,n,e){var r=e("07e3"),o=e("36c3"),i=e("5b4e")(!1),u=e("5559")("IE_PROTO");t.exports=function(t,n){var e,c=o(t),a=0,f=[];for(e in c)e!=u&&r(c,e)&&f.push(e);while(n.length>a)r(c,e=n[a++])&&(~i(f,e)||f.push(e));return f}},f410:function(t,n,e){e("1af6"),t.exports=e("584a").Array.isArray},f559:function(t,n,e){"use strict";var r=e("5ca1"),o=e("9def"),i=e("d2c8"),u="startsWith",c=""[u];r(r.P+r.F*e("5147")(u),"String",{startsWith:function(t){var n=i(this,t,u),e=o(Math.min(arguments.length>1?arguments[1]:void 0,n.length)),r=String(t);return c?c.call(n,r,e):n.slice(e,e+r.length)===r}})},f772:function(t,n){t.exports=function(t){return"object"===typeof t?null!==t:"function"===typeof t}},fa5b:function(t,n,e){t.exports=e("5537")("native-function-to-string",Function.toString)},fb15:function(t,n,e){"use strict";var r;(e.r(n),"undefined"!==typeof window)&&((r=window.document.currentScript)&&(r=r.src.match(/(.+\/)[^\/]+\.js(\?.*)?$/))&&(e.p=r[1]));var o=e("5176"),i=e.n(o),u=(e("f559"),e("a4bb")),c=e.n(u),a=(e("6762"),e("2fdb"),e("a745")),f=e.n(a);function s(t){if(f()(t))return t}var l=e("5d73"),p=e.n(l);function d(t,n){var e=[],r=!0,o=!1,i=void 0;try{for(var u,c=p()(t);!(r=(u=c.next()).done);r=!0)if(e.push(u.value),n&&e.length===n)break}catch(a){o=!0,i=a}finally{try{r||null==c["return"]||c["return"]()}finally{if(o)throw i}}return e}function v(){throw new TypeError("Invalid attempt to destructure non-iterable instance")}function h(t,n){return s(t)||d(t,n)||v()}function b(t){if(f()(t)){for(var n=0,e=new Array(t.length);n<t.length;n++)e[n]=t[n];return e}}var g=e("774e"),y=e.n(g),x=e("c8bb"),m=e.n(x);function w(t){if(m()(Object(t))||"[object Arguments]"===Object.prototype.toString.call(t))return y()(t)}function O(){throw new TypeError("Invalid attempt to spread non-iterable instance")}function S(t){return b(t)||w(t)||O()}var j=e("a352"),_=e.n(j),M=e("c649");function T(t,n,e){return void 0===e?t:(t=t||{},t[n]=e,t)}function C(t,n){return t.map(function(t){return t.elm}).indexOf(n)}function E(t,n,e,r){if(!t)return[];var o=t.map(function(t){return t.elm}),i=n.length-r,u=S(n).map(function(t,n){return n>=i?o.length:o.indexOf(t)});return e?u.filter(function(t){return-1!==t}):u}function A(t,n){var e=this;this.$nextTick(function(){return e.$emit(t.toLowerCase(),n)})}function P(t){var n=this;return function(e){null!==n.realList&&n["onDrag"+t](e),A.call(n,t,e)}}function I(t){if(!t||1!==t.length)return!1;var n=h(t,1),e=n[0].componentOptions;return!!e&&["transition-group","TransitionGroup"].includes(e.tag)}function L(t,n){var e=n.header,r=n.footer,o=0,i=0;return e&&(o=e.length,t=t?[].concat(S(e),S(t)):S(e)),r&&(i=r.length,t=t?[].concat(S(t),S(r)):S(r)),{children:t,headerOffset:o,footerOffset:i}}function F(t,n){var e=null,r=function(t,n){e=T(e,t,n)},o=c()(t).filter(function(t){return"id"===t||t.startsWith("data-")}).reduce(function(n,e){return n[e]=t[e],n},{});if(r("attrs",o),!n)return e;var u=n.on,a=n.props,f=n.attrs;return r("on",u),r("props",a),i()(e.attrs,f),e}var $=["Start","Add","Remove","Update","End"],k=["Choose","Sort","Filter","Clone"],D=["Move"].concat($,k).map(function(t){return"on"+t}),R=null,V={options:Object,list:{type:Array,required:!1,default:null},value:{type:Array,required:!1,default:null},noTransitionOnDrag:{type:Boolean,default:!1},clone:{type:Function,default:function(t){return t}},element:{type:String,default:"div"},tag:{type:String,default:null},move:{type:Function,default:null},componentData:{type:Object,required:!1,default:null}},N={name:"draggable",inheritAttrs:!1,props:V,data:function(){return{transitionMode:!1,noneFunctionalComponentMode:!1,init:!1}},render:function(t){var n=this.$slots.default;this.transitionMode=I(n);var e=L(n,this.$slots),r=e.children,o=e.headerOffset,i=e.footerOffset;this.headerOffset=o,this.footerOffset=i;var u=F(this.$attrs,this.componentData);return t(this.getTag(),u,r)},created:function(){null!==this.list&&null!==this.value&&M["b"].error("Value and list props are mutually exclusive! Please set one or another."),"div"!==this.element&&M["b"].warn("Element props is deprecated please use tag props instead. See https://github.com/SortableJS/Vue.Draggable/blob/master/documentation/migrate.md#element-props"),void 0!==this.options&&M["b"].warn("Options props is deprecated, add sortable options directly as vue.draggable item, or use v-bind. See https://github.com/SortableJS/Vue.Draggable/blob/master/documentation/migrate.md#options-props")},mounted:function(){var t=this;if(this.noneFunctionalComponentMode=this.getTag().toLowerCase()!==this.$el.nodeName.toLowerCase(),this.noneFunctionalComponentMode&&this.transitionMode)throw new Error("Transition-group inside component is not supported. Please alter tag value or remove transition-group. Current tag value: ".concat(this.getTag()));var n={};$.forEach(function(e){n["on"+e]=P.call(t,e)}),k.forEach(function(e){n["on"+e]=A.bind(t,e)});var e=c()(this.$attrs).reduce(function(n,e){return n[Object(M["a"])(e)]=t.$attrs[e],n},{}),r=i()({},this.options,e,n,{onMove:function(n,e){return t.onDragMove(n,e)}});!("draggable"in r)&&(r.draggable=">*"),this._sortable=new _.a(this.rootContainer,r),this.computeIndexes()},beforeDestroy:function(){void 0!==this._sortable&&this._sortable.destroy()},computed:{rootContainer:function(){return this.transitionMode?this.$el.children[0]:this.$el},realList:function(){return this.list?this.list:this.value}},watch:{options:{handler:function(t){this.updateOptions(t)},deep:!0},$attrs:{handler:function(t){this.updateOptions(t)},deep:!0},realList:function(){this.computeIndexes()}},methods:{getTag:function(){return this.tag||this.element},updateOptions:function(t){for(var n in t){var e=Object(M["a"])(n);-1===D.indexOf(e)&&this._sortable.option(e,t[n])}},getChildrenNodes:function(){if(this.init||(this.noneFunctionalComponentMode=this.noneFunctionalComponentMode&&1===this.$children.length,this.init=!0),this.noneFunctionalComponentMode)return this.$children[0].$slots.default;var t=this.$slots.default;return this.transitionMode?t[0].child.$slots.default:t},computeIndexes:function(){var t=this;this.$nextTick(function(){t.visibleIndexes=E(t.getChildrenNodes(),t.rootContainer.children,t.transitionMode,t.footerOffset)})},getUnderlyingVm:function(t){var n=C(this.getChildrenNodes()||[],t);if(-1===n)return null;var e=this.realList[n];return{index:n,element:e}},getUnderlyingPotencialDraggableComponent:function(t){var n=t.__vue__;return n&&n.$options&&"transition-group"===n.$options._componentTag?n.$parent:n},emitChanges:function(t){var n=this;this.$nextTick(function(){n.$emit("change",t)})},alterList:function(t){if(this.list)t(this.list);else{var n=S(this.value);t(n),this.$emit("input",n)}},spliceList:function(){var t=arguments,n=function(n){return n.splice.apply(n,S(t))};this.alterList(n)},updatePosition:function(t,n){var e=function(e){return e.splice(n,0,e.splice(t,1)[0])};this.alterList(e)},getRelatedContextFromMoveEvent:function(t){var n=t.to,e=t.related,r=this.getUnderlyingPotencialDraggableComponent(n);if(!r)return{component:r};var o=r.realList,u={list:o,component:r};if(n!==e&&o&&r.getUnderlyingVm){var c=r.getUnderlyingVm(e);if(c)return i()(c,u)}return u},getVmIndex:function(t){var n=this.visibleIndexes,e=n.length;return t>e-1?e:n[t]},getComponent:function(){return this.$slots.default[0].componentInstance},resetTransitionData:function(t){if(this.noTransitionOnDrag&&this.transitionMode){var n=this.getChildrenNodes();n[t].data=null;var e=this.getComponent();e.children=[],e.kept=void 0}},onDragStart:function(t){this.context=this.getUnderlyingVm(t.item),t.item._underlying_vm_=this.clone(this.context.element),R=t.item},onDragAdd:function(t){var n=t.item._underlying_vm_;if(void 0!==n){Object(M["d"])(t.item);var e=this.getVmIndex(t.newIndex);this.spliceList(e,0,n),this.computeIndexes();var r={element:n,newIndex:e};this.emitChanges({added:r})}},onDragRemove:function(t){if(Object(M["c"])(this.rootContainer,t.item,t.oldIndex),"clone"!==t.pullMode){var n=this.context.index;this.spliceList(n,1);var e={element:this.context.element,oldIndex:n};this.resetTransitionData(n),this.emitChanges({removed:e})}else Object(M["d"])(t.clone)},onDragUpdate:function(t){Object(M["d"])(t.item),Object(M["c"])(t.from,t.item,t.oldIndex);var n=this.context.index,e=this.getVmIndex(t.newIndex);this.updatePosition(n,e);var r={element:this.context.element,oldIndex:n,newIndex:e};this.emitChanges({moved:r})},updateProperty:function(t,n){t.hasOwnProperty(n)&&(t[n]+=this.headerOffset)},computeFutureIndex:function(t,n){if(!t.element)return 0;var e=S(n.to.children).filter(function(t){return"none"!==t.style["display"]}),r=e.indexOf(n.related),o=t.component.getVmIndex(r),i=-1!==e.indexOf(R);return i||!n.willInsertAfter?o:o+1},onDragMove:function(t,n){var e=this.move;if(!e||!this.realList)return!0;var r=this.getRelatedContextFromMoveEvent(t),o=this.context,u=this.computeFutureIndex(r,t);i()(o,{futureIndex:u});var c=i()({},t,{relatedContext:r,draggedContext:o});return e(c,n)},onDragEnd:function(){this.computeIndexes(),R=null}}};"undefined"!==typeof window&&"Vue"in window&&window.Vue.component("draggable",N);var U=N;n["default"]=U}})["default"]});
//# sourceMappingURL=vuedraggable.umd.min.js.map

/***/ }),
/* 141 */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/**!
 * Sortable
 * @author	RubaXa   <trash@rubaxa.org>
 * @author	owenm    <owen23355@gmail.com>
 * @license MIT
 */

(function sortableModule(factory) {
	"use strict";

	if (true) {
		!(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	}
	else if (typeof module != "undefined" && typeof module.exports != "undefined") {
		module.exports = factory();
	}
	else {
		/* jshint sub:true */
		window["Sortable"] = factory();
	}
})(function sortableFactory() {
	"use strict";

	if (typeof window === "undefined" || !window.document) {
		return function sortableError() {
			throw new Error("Sortable.js requires a window with a document");
		};
	}

	var dragEl,
		parentEl,
		ghostEl,
		cloneEl,
		rootEl,
		nextEl,
		lastDownEl,

		scrollEl,
		scrollParentEl,
		scrollCustomFn,

		oldIndex,
		newIndex,

		activeGroup,
		putSortable,

		autoScrolls = [],
		scrolling = false,

		awaitingDragStarted = false,
		ignoreNextClick = false,
		sortables = [],

		pointerElemChangedInterval,
		lastPointerElemX,
		lastPointerElemY,

		tapEvt,
		touchEvt,

		moved,


		lastTarget,
		lastDirection,
		pastFirstInvertThresh = false,
		isCircumstantialInvert = false,
		lastMode, // 'swap' or 'insert'

		targetMoveDistance,

		// For positioning ghost absolutely
		ghostRelativeParent,
		ghostRelativeParentInitialScroll = [], // (left, top)


		forRepaintDummy,
		realDragElRect, // dragEl rect after current animation

		/** @const */
		R_SPACE = /\s+/g,

		expando = 'Sortable' + (new Date).getTime(),

		win = window,
		document = win.document,
		parseInt = win.parseInt,
		setTimeout = win.setTimeout,

		$ = win.jQuery || win.Zepto,
		Polymer = win.Polymer,

		captureMode = {
			capture: false,
			passive: false
		},

		IE11OrLess = !!navigator.userAgent.match(/(?:Trident.*rv[ :]?11\.|msie|iemobile)/i),
		Edge = !!navigator.userAgent.match(/Edge/i),
		FireFox = !!navigator.userAgent.match(/firefox/i),
		Safari = !!(navigator.userAgent.match(/safari/i) && !navigator.userAgent.match(/chrome/i) && !navigator.userAgent.match(/android/i)),
		IOS = !!(navigator.userAgent.match(/iP(ad|od|hone)/i)),

		PositionGhostAbsolutely = IOS,

		CSSFloatProperty = Edge || IE11OrLess ? 'cssFloat' : 'float',

		// This will not pass for IE9, because IE9 DnD only works on anchors
		supportDraggable = ('draggable' in document.createElement('div')),

		supportCssPointerEvents = (function() {
			// false when <= IE11
			if (IE11OrLess) {
				return false;
			}
			var el = document.createElement('x');
			el.style.cssText = 'pointer-events:auto';
			return el.style.pointerEvents === 'auto';
		})(),

		_silent = false,
		_alignedSilent = false,

		abs = Math.abs,
		min = Math.min,
		max = Math.max,

		savedInputChecked = [],

		_detectDirection = function(el, options) {
			var elCSS = _css(el),
				elWidth = parseInt(elCSS.width)
					- parseInt(elCSS.paddingLeft)
					- parseInt(elCSS.paddingRight)
					- parseInt(elCSS.borderLeftWidth)
					- parseInt(elCSS.borderRightWidth),
				child1 = _getChild(el, 0, options),
				child2 = _getChild(el, 1, options),
				firstChildCSS = child1 && _css(child1),
				secondChildCSS = child2 && _css(child2),
				firstChildWidth = firstChildCSS && parseInt(firstChildCSS.marginLeft) + parseInt(firstChildCSS.marginRight) + _getRect(child1).width,
				secondChildWidth = secondChildCSS && parseInt(secondChildCSS.marginLeft) + parseInt(secondChildCSS.marginRight) + _getRect(child2).width;

			if (elCSS.display === 'flex') {
				return elCSS.flexDirection === 'column' || elCSS.flexDirection === 'column-reverse'
				? 'vertical' : 'horizontal';
			}

			if (elCSS.display === 'grid') {
				return elCSS.gridTemplateColumns.split(' ').length <= 1 ? 'vertical' : 'horizontal';
			}

			if (child1 && firstChildCSS.float !== 'none') {
				var touchingSideChild2 = firstChildCSS.float === 'left' ? 'left' : 'right';

				return child2 && (secondChildCSS.clear === 'both' || secondChildCSS.clear === touchingSideChild2) ?
					'vertical' : 'horizontal';
			}

			return (child1 &&
				(
					firstChildCSS.display === 'block' ||
					firstChildCSS.display === 'flex' ||
					firstChildCSS.display === 'table' ||
					firstChildCSS.display === 'grid' ||
					firstChildWidth >= elWidth &&
					elCSS[CSSFloatProperty] === 'none' ||
					child2 &&
					elCSS[CSSFloatProperty] === 'none' &&
					firstChildWidth + secondChildWidth > elWidth
				) ?
				'vertical' : 'horizontal'
			);
		},

		/**
		 * Detects first nearest empty sortable to X and Y position using emptyInsertThreshold.
		 * @param  {Number} x      X position
		 * @param  {Number} y      Y position
		 * @return {HTMLElement}   Element of the first found nearest Sortable
		 */
		_detectNearestEmptySortable = function(x, y) {
			for (var i = 0; i < sortables.length; i++) {
				if (_lastChild(sortables[i])) continue;

				var rect = _getRect(sortables[i]),
					threshold = sortables[i][expando].options.emptyInsertThreshold,
					insideHorizontally = x >= (rect.left - threshold) && x <= (rect.right + threshold),
					insideVertically = y >= (rect.top - threshold) && y <= (rect.bottom + threshold);

				if (insideHorizontally && insideVertically) {
					return sortables[i];
				}
			}
		},

		_isClientInRowColumn = function(x, y, el, axis, options) {
			var targetRect = _getRect(el),
				targetS1Opp = axis === 'vertical' ? targetRect.left : targetRect.top,
				targetS2Opp = axis === 'vertical' ? targetRect.right : targetRect.bottom,
				mouseOnOppAxis = axis === 'vertical' ? x : y;

			return targetS1Opp < mouseOnOppAxis && mouseOnOppAxis < targetS2Opp;
		},

		_isElInRowColumn = function(el1, el2, axis) {
			var el1Rect = el1 === dragEl && realDragElRect || _getRect(el1),
				el2Rect = el2 === dragEl && realDragElRect || _getRect(el2),
				el1S1Opp = axis === 'vertical' ? el1Rect.left : el1Rect.top,
				el1S2Opp = axis === 'vertical' ? el1Rect.right : el1Rect.bottom,
				el1OppLength = axis === 'vertical' ? el1Rect.width : el1Rect.height,
				el2S1Opp = axis === 'vertical' ? el2Rect.left : el2Rect.top,
				el2S2Opp = axis === 'vertical' ? el2Rect.right : el2Rect.bottom,
				el2OppLength = axis === 'vertical' ? el2Rect.width : el2Rect.height;

			return (
				el1S1Opp === el2S1Opp ||
				el1S2Opp === el2S2Opp ||
				(el1S1Opp + el1OppLength / 2) === (el2S1Opp + el2OppLength / 2)
			);
		},

		_getParentAutoScrollElement = function(el, includeSelf) {
			// skip to window
			if (!el || !el.getBoundingClientRect) return _getWindowScrollingElement();

			var elem = el;
			var gotSelf = false;
			do {
				// we don't need to get elem css if it isn't even overflowing in the first place (performance)
				if (elem.clientWidth < elem.scrollWidth || elem.clientHeight < elem.scrollHeight) {
					var elemCSS = _css(elem);
					if (
						elem.clientWidth < elem.scrollWidth && (elemCSS.overflowX == 'auto' || elemCSS.overflowX == 'scroll') ||
						elem.clientHeight < elem.scrollHeight && (elemCSS.overflowY == 'auto' || elemCSS.overflowY == 'scroll')
					) {
						if (!elem || !elem.getBoundingClientRect || elem === document.body) return _getWindowScrollingElement();

						if (gotSelf || includeSelf) return elem;
						gotSelf = true;
					}
				}
			/* jshint boss:true */
			} while (elem = elem.parentNode);

			return _getWindowScrollingElement();
		},

		_getWindowScrollingElement = function() {
			if (IE11OrLess) {
				return document.documentElement;
			} else {
				return document.scrollingElement;
			}
		},

		_scrollBy = function(el, x, y) {
			el.scrollLeft += x;
			el.scrollTop += y;
		},

		_autoScroll = _throttle(function (/**Event*/evt, /**Object*/options, /**HTMLElement*/rootEl, /**Boolean*/isFallback) {
			// Bug: https://bugzilla.mozilla.org/show_bug.cgi?id=505521
			if (options.scroll) {
				var _this = rootEl ? rootEl[expando] : window,
					sens = options.scrollSensitivity,
					speed = options.scrollSpeed,

					x = evt.clientX,
					y = evt.clientY,

					winScroller = _getWindowScrollingElement(),

					scrollThisInstance = false;

				// Detect scrollEl
				if (scrollParentEl !== rootEl) {
					_clearAutoScrolls();

					scrollEl = options.scroll;
					scrollCustomFn = options.scrollFn;

					if (scrollEl === true) {
						scrollEl = _getParentAutoScrollElement(rootEl, true);
						scrollParentEl = scrollEl;
					}
				}


				var layersOut = 0;
				var currentParent = scrollEl;
				do {
					var	el = currentParent,
						rect = _getRect(el),

						top = rect.top,
						bottom = rect.bottom,
						left = rect.left,
						right = rect.right,

						width = rect.width,
						height = rect.height,

						scrollWidth,
						scrollHeight,

						css,

						vx,
						vy,

						canScrollX,
						canScrollY,

						scrollPosX,
						scrollPosY;


					scrollWidth = el.scrollWidth;
					scrollHeight = el.scrollHeight;

					css = _css(el);

					scrollPosX = el.scrollLeft;
					scrollPosY = el.scrollTop;

					if (el === winScroller) {
						canScrollX = width < scrollWidth && (css.overflowX === 'auto' || css.overflowX === 'scroll' || css.overflowX === 'visible');
						canScrollY = height < scrollHeight && (css.overflowY === 'auto' || css.overflowY === 'scroll' || css.overflowY === 'visible');
					} else {
						canScrollX = width < scrollWidth && (css.overflowX === 'auto' || css.overflowX === 'scroll');
						canScrollY = height < scrollHeight && (css.overflowY === 'auto' || css.overflowY === 'scroll');
					}

					vx = canScrollX && (abs(right - x) <= sens && (scrollPosX + width) < scrollWidth) - (abs(left - x) <= sens && !!scrollPosX);

					vy = canScrollY && (abs(bottom - y) <= sens && (scrollPosY + height) < scrollHeight) - (abs(top - y) <= sens && !!scrollPosY);


					if (!autoScrolls[layersOut]) {
						for (var i = 0; i <= layersOut; i++) {
							if (!autoScrolls[i]) {
								autoScrolls[i] = {};
							}
						}
					}

					if (autoScrolls[layersOut].vx != vx || autoScrolls[layersOut].vy != vy || autoScrolls[layersOut].el !== el) {
						autoScrolls[layersOut].el = el;
						autoScrolls[layersOut].vx = vx;
						autoScrolls[layersOut].vy = vy;

						clearInterval(autoScrolls[layersOut].pid);

						if (el && (vx != 0 || vy != 0)) {
							scrollThisInstance = true;
							/* jshint loopfunc:true */
							autoScrolls[layersOut].pid = setInterval((function () {
								// emulate drag over during autoscroll (fallback), emulating native DnD behaviour
								if (isFallback && this.layer === 0) {
									Sortable.active._emulateDragOver(true);
									Sortable.active._onTouchMove(touchEvt, true);
								}
								var scrollOffsetY = autoScrolls[this.layer].vy ? autoScrolls[this.layer].vy * speed : 0;
								var scrollOffsetX = autoScrolls[this.layer].vx ? autoScrolls[this.layer].vx * speed : 0;

								if ('function' === typeof(scrollCustomFn)) {
									if (scrollCustomFn.call(_this, scrollOffsetX, scrollOffsetY, evt, touchEvt, autoScrolls[this.layer].el) !== 'continue') {
										return;
									}
								}

								_scrollBy(autoScrolls[this.layer].el, scrollOffsetX, scrollOffsetY);
							}).bind({layer: layersOut}), 24);
						}
					}
					layersOut++;
				} while (options.bubbleScroll && currentParent !== winScroller && (currentParent = _getParentAutoScrollElement(currentParent, false)));
				scrolling = scrollThisInstance; // in case another function catches scrolling as false in between when it is not
			}
		}, 30),

		_clearAutoScrolls = function () {
			autoScrolls.forEach(function(autoScroll) {
				clearInterval(autoScroll.pid);
			});
			autoScrolls = [];
		},

		_prepareGroup = function (options) {
			function toFn(value, pull) {
				return function(to, from, dragEl, evt) {
					var sameGroup = to.options.group.name &&
									from.options.group.name &&
									to.options.group.name === from.options.group.name;

					if (value == null && (pull || sameGroup)) {
						// Default pull value
						// Default pull and put value if same group
						return true;
					} else if (value == null || value === false) {
						return false;
					} else if (pull && value === 'clone') {
						return value;
					} else if (typeof value === 'function') {
						return toFn(value(to, from, dragEl, evt), pull)(to, from, dragEl, evt);
					} else {
						var otherGroup = (pull ? to : from).options.group.name;

						return (value === true ||
						(typeof value === 'string' && value === otherGroup) ||
						(value.join && value.indexOf(otherGroup) > -1));
					}
				};
			}

			var group = {};
			var originalGroup = options.group;

			if (!originalGroup || typeof originalGroup != 'object') {
				originalGroup = {name: originalGroup};
			}

			group.name = originalGroup.name;
			group.checkPull = toFn(originalGroup.pull, true);
			group.checkPut = toFn(originalGroup.put);
			group.revertClone = originalGroup.revertClone;

			options.group = group;
		},

		_checkAlignment = function(evt) {
			if (!dragEl || !dragEl.parentNode) return;
			dragEl.parentNode[expando] && dragEl.parentNode[expando]._computeIsAligned(evt);
		},

		_isTrueParentSortable = function(el, target) {
			var trueParent = target;
			while (!trueParent[expando]) {
				trueParent = trueParent.parentNode;
			}

			return el === trueParent;
		},

		_artificalBubble = function(sortable, originalEvt, method) {
			// Artificial IE bubbling
			var nextParent = sortable.parentNode;
			while (nextParent && !nextParent[expando]) {
				nextParent = nextParent.parentNode;
			}

			if (nextParent) {
				nextParent[expando][method](_extend(originalEvt, {
					artificialBubble: true
				}));
			}
		},

		_hideGhostForTarget = function() {
			if (!supportCssPointerEvents && ghostEl) {
				_css(ghostEl, 'display', 'none');
			}
		},

		_unhideGhostForTarget = function() {
			if (!supportCssPointerEvents && ghostEl) {
				_css(ghostEl, 'display', '');
			}
		};


	// #1184 fix - Prevent click event on fallback if dragged but item not changed position
	document.addEventListener('click', function(evt) {
		if (ignoreNextClick) {
			evt.preventDefault();
			evt.stopPropagation && evt.stopPropagation();
			evt.stopImmediatePropagation && evt.stopImmediatePropagation();
			ignoreNextClick = false;
			return false;
		}
	}, true);

	var nearestEmptyInsertDetectEvent = function(evt) {
		evt = evt.touches ? evt.touches[0] : evt;
		if (dragEl) {
			var nearest = _detectNearestEmptySortable(evt.clientX, evt.clientY);

			if (nearest) {
				nearest[expando]._onDragOver({
					clientX: evt.clientX,
					clientY: evt.clientY,
					target: nearest,
					rootEl: nearest
				});
			}
		}
	};
	// We do not want this to be triggered if completed (bubbling canceled), so only define it here
	_on(document, 'dragover', nearestEmptyInsertDetectEvent);
	_on(document, 'mousemove', nearestEmptyInsertDetectEvent);
	_on(document, 'touchmove', nearestEmptyInsertDetectEvent);

	/**
	 * @class  Sortable
	 * @param  {HTMLElement}  el
	 * @param  {Object}       [options]
	 */
	function Sortable(el, options) {
		if (!(el && el.nodeType && el.nodeType === 1)) {
			throw 'Sortable: `el` must be HTMLElement, not ' + {}.toString.call(el);
		}

		this.el = el; // root element
		this.options = options = _extend({}, options);


		// Export instance
		el[expando] = this;

		// Default options
		var defaults = {
			group: null,
			sort: true,
			disabled: false,
			store: null,
			handle: null,
			scroll: true,
			scrollSensitivity: 30,
			scrollSpeed: 10,
			bubbleScroll: true,
			draggable: /[uo]l/i.test(el.nodeName) ? '>li' : '>*',
			swapThreshold: 1, // percentage; 0 <= x <= 1
			invertSwap: false, // invert always
			invertedSwapThreshold: null, // will be set to same as swapThreshold if default
			removeCloneOnHide: true,
			direction: function() {
				return _detectDirection(el, this.options);
			},
			ghostClass: 'sortable-ghost',
			chosenClass: 'sortable-chosen',
			dragClass: 'sortable-drag',
			ignore: 'a, img',
			filter: null,
			preventOnFilter: true,
			animation: 0,
			easing: null,
			setData: function (dataTransfer, dragEl) {
				dataTransfer.setData('Text', dragEl.textContent);
			},
			dropBubble: false,
			dragoverBubble: false,
			dataIdAttr: 'data-id',
			delay: 0,
			touchStartThreshold: parseInt(window.devicePixelRatio, 10) || 1,
			forceFallback: false,
			fallbackClass: 'sortable-fallback',
			fallbackOnBody: false,
			fallbackTolerance: 0,
			fallbackOffset: {x: 0, y: 0},
			supportPointer: Sortable.supportPointer !== false && (
				('PointerEvent' in window) ||
				window.navigator && ('msPointerEnabled' in window.navigator) // microsoft
			),
			emptyInsertThreshold: 5
		};


		// Set default options
		for (var name in defaults) {
			!(name in options) && (options[name] = defaults[name]);
		}

		_prepareGroup(options);

		// Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

		// Setup drag mode
		this.nativeDraggable = options.forceFallback ? false : supportDraggable;

		if (this.nativeDraggable) {
			// Touch start threshold cannot be greater than the native dragstart threshold
			this.options.touchStartThreshold = 1;
		}

		// Bind events
		if (options.supportPointer) {
			_on(el, 'pointerdown', this._onTapStart);
		} else {
			_on(el, 'mousedown', this._onTapStart);
			_on(el, 'touchstart', this._onTapStart);
		}

		if (this.nativeDraggable) {
			_on(el, 'dragover', this);
			_on(el, 'dragenter', this);
		}

		sortables.push(this.el);

		// Restore sorting
		options.store && options.store.get && this.sort(options.store.get(this) || []);
	}

	Sortable.prototype = /** @lends Sortable.prototype */ {
		constructor: Sortable,

		_computeIsAligned: function(evt) {
			var target;

			if (ghostEl && !supportCssPointerEvents) {
				_hideGhostForTarget();
				target = document.elementFromPoint(evt.clientX, evt.clientY);
				_unhideGhostForTarget();
			} else {
				target = evt.target;
			}

			target = _closest(target, this.options.draggable, this.el, false);
			if (_alignedSilent) return;
			if (!dragEl || dragEl.parentNode !== this.el) return;

			var children = this.el.children;
			for (var i = 0; i < children.length; i++) {
				// Don't change for target in case it is changed to aligned before onDragOver is fired
				if (_closest(children[i], this.options.draggable, this.el, false) && children[i] !== target) {
					children[i].sortableMouseAligned = _isClientInRowColumn(evt.clientX, evt.clientY, children[i], this._getDirection(evt, null), this.options);
				}
			}
			// Used for nulling last target when not in element, nothing to do with checking if aligned
			if (!_closest(target, this.options.draggable, this.el, true)) {
				lastTarget = null;
			}

			_alignedSilent = true;
			setTimeout(function() {
				_alignedSilent = false;
			}, 30);

		},

		_getDirection: function(evt, target) {
			return (typeof this.options.direction === 'function') ? this.options.direction.call(this, evt, target, dragEl) : this.options.direction;
		},

		_onTapStart: function (/** Event|TouchEvent */evt) {
			if (!evt.cancelable) return;
			var _this = this,
				el = this.el,
				options = this.options,
				preventOnFilter = options.preventOnFilter,
				type = evt.type,
				touch = evt.touches && evt.touches[0],
				target = (touch || evt).target,
				originalTarget = evt.target.shadowRoot && ((evt.path && evt.path[0]) || (evt.composedPath && evt.composedPath()[0])) || target,
				filter = options.filter,
				startIndex;

			_saveInputCheckedState(el);


			// IE: Calls events in capture mode if event element is nested. This ensures only correct element's _onTapStart goes through.
			// This process is also done in _onDragOver
			if (IE11OrLess && !evt.artificialBubble && !_isTrueParentSortable(el, target)) {
				return;
			}

			// Don't trigger start event when an element is been dragged, otherwise the evt.oldindex always wrong when set option.group.
			if (dragEl) {
				return;
			}

			if (/mousedown|pointerdown/.test(type) && evt.button !== 0 || options.disabled) {
				return; // only left button and enabled
			}

			// cancel dnd if original target is content editable
			if (originalTarget.isContentEditable) {
				return;
			}

			target = _closest(target, options.draggable, el, false);

			if (!target) {
				if (IE11OrLess) {
					_artificalBubble(el, evt, '_onTapStart');
				}
				return;
			}

			if (lastDownEl === target) {
				// Ignoring duplicate `down`
				return;
			}

			// Get the index of the dragged element within its parent
			startIndex = _index(target, options.draggable);

			// Check filter
			if (typeof filter === 'function') {
				if (filter.call(this, evt, target, this)) {
					_dispatchEvent(_this, originalTarget, 'filter', target, el, el, startIndex);
					preventOnFilter && evt.cancelable && evt.preventDefault();
					return; // cancel dnd
				}
			}
			else if (filter) {
				filter = filter.split(',').some(function (criteria) {
					criteria = _closest(originalTarget, criteria.trim(), el, false);

					if (criteria) {
						_dispatchEvent(_this, criteria, 'filter', target, el, el, startIndex);
						return true;
					}
				});

				if (filter) {
					preventOnFilter && evt.cancelable && evt.preventDefault();
					return; // cancel dnd
				}
			}

			if (options.handle && !_closest(originalTarget, options.handle, el, false)) {
				return;
			}

			// Prepare `dragstart`
			this._prepareDragStart(evt, touch, target, startIndex);
		},


		_handleAutoScroll: function(evt, fallback) {
			if (!dragEl || !this.options.scroll) return;
			var x = evt.clientX,
				y = evt.clientY,

				elem = document.elementFromPoint(x, y),
				_this = this;

			// IE does not seem to have native autoscroll,
			// Edge's autoscroll seems too conditional,
			// MACOS Safari does not have autoscroll,
			// Firefox and Chrome are good
			if (fallback || Edge || IE11OrLess || Safari) {
				_autoScroll(evt, _this.options, elem, fallback);

				// Listener for pointer element change
				var ogElemScroller = _getParentAutoScrollElement(elem, true);
				if (
					scrolling &&
					(
						!pointerElemChangedInterval ||
						x !== lastPointerElemX ||
						y !== lastPointerElemY
					)
				) {

					pointerElemChangedInterval && clearInterval(pointerElemChangedInterval);
					// Detect for pointer elem change, emulating native DnD behaviour
					pointerElemChangedInterval = setInterval(function() {
						if (!dragEl) return;
						// could also check if scroll direction on newElem changes due to parent autoscrolling
						var newElem = _getParentAutoScrollElement(document.elementFromPoint(x, y), true);
						if (newElem !== ogElemScroller) {
							ogElemScroller = newElem;
							_clearAutoScrolls();
							_autoScroll(evt, _this.options, ogElemScroller, fallback);
						}
					}, 10);
					lastPointerElemX = x;
					lastPointerElemY = y;
				}

			} else {
				// if DnD is enabled (and browser has good autoscrolling), first autoscroll will already scroll, so get parent autoscroll of first autoscroll
				if (!_this.options.bubbleScroll || _getParentAutoScrollElement(elem, true) === _getWindowScrollingElement()) {
					_clearAutoScrolls();
					return;
				}
				_autoScroll(evt, _this.options, _getParentAutoScrollElement(elem, false), false);
			}
		},

		_prepareDragStart: function (/** Event */evt, /** Touch */touch, /** HTMLElement */target, /** Number */startIndex) {
			var _this = this,
				el = _this.el,
				options = _this.options,
				ownerDocument = el.ownerDocument,
				dragStartFn;

			if (target && !dragEl && (target.parentNode === el)) {
				rootEl = el;
				dragEl = target;
				parentEl = dragEl.parentNode;
				nextEl = dragEl.nextSibling;
				lastDownEl = target;
				activeGroup = options.group;
				oldIndex = startIndex;

				tapEvt = {
					target: dragEl,
					clientX: (touch || evt).clientX,
					clientY: (touch || evt).clientY
				};

				this._lastX = (touch || evt).clientX;
				this._lastY = (touch || evt).clientY;

				dragEl.style['will-change'] = 'all';
				// undo animation if needed
				dragEl.style.transition = '';
				dragEl.style.transform = '';

				dragStartFn = function () {
					// Delayed drag has been triggered
					// we can re-enable the events: touchmove/mousemove
					_this._disableDelayedDragEvents();

					if (!FireFox && _this.nativeDraggable) {
						dragEl.draggable = true;
					}

					// Bind the events: dragstart/dragend
					_this._triggerDragStart(evt, touch);

					// Drag start event
					_dispatchEvent(_this, rootEl, 'choose', dragEl, rootEl, rootEl, oldIndex);

					// Chosen item
					_toggleClass(dragEl, options.chosenClass, true);
				};

				// Disable "draggable"
				options.ignore.split(',').forEach(function (criteria) {
					_find(dragEl, criteria.trim(), _disableDraggable);
				});

				if (options.supportPointer) {
					_on(ownerDocument, 'pointerup', _this._onDrop);
				} else {
					_on(ownerDocument, 'mouseup', _this._onDrop);
					_on(ownerDocument, 'touchend', _this._onDrop);
					_on(ownerDocument, 'touchcancel', _this._onDrop);
				}

				// Make dragEl draggable (must be before delay for FireFox)
				if (FireFox && this.nativeDraggable) {
					this.options.touchStartThreshold = 4;
					dragEl.draggable = true;
				}

				// Delay is impossible for native DnD in Edge or IE
				if (options.delay && (!this.nativeDraggable || !(Edge || IE11OrLess))) {
					// If the user moves the pointer or let go the click or touch
					// before the delay has been reached:
					// disable the delayed drag
					_on(ownerDocument, 'mouseup', _this._disableDelayedDrag);
					_on(ownerDocument, 'touchend', _this._disableDelayedDrag);
					_on(ownerDocument, 'touchcancel', _this._disableDelayedDrag);
					_on(ownerDocument, 'mousemove', _this._delayedDragTouchMoveHandler);
					_on(ownerDocument, 'touchmove', _this._delayedDragTouchMoveHandler);
					options.supportPointer && _on(ownerDocument, 'pointermove', _this._delayedDragTouchMoveHandler);

					_this._dragStartTimer = setTimeout(dragStartFn, options.delay);
				} else {
					dragStartFn();
				}
			}
		},

		_delayedDragTouchMoveHandler: function (/** TouchEvent|PointerEvent **/e) {
			var touch = e.touches ? e.touches[0] : e;
			if (max(abs(touch.clientX - this._lastX), abs(touch.clientY - this._lastY))
					>= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1))
			) {
				this._disableDelayedDrag();
			}
		},

		_disableDelayedDrag: function () {
			dragEl && _disableDraggable(dragEl);
			clearTimeout(this._dragStartTimer);

			this._disableDelayedDragEvents();
		},

		_disableDelayedDragEvents: function () {
			var ownerDocument = this.el.ownerDocument;
			_off(ownerDocument, 'mouseup', this._disableDelayedDrag);
			_off(ownerDocument, 'touchend', this._disableDelayedDrag);
			_off(ownerDocument, 'touchcancel', this._disableDelayedDrag);
			_off(ownerDocument, 'mousemove', this._delayedDragTouchMoveHandler);
			_off(ownerDocument, 'touchmove', this._delayedDragTouchMoveHandler);
			_off(ownerDocument, 'pointermove', this._delayedDragTouchMoveHandler);
		},

		_triggerDragStart: function (/** Event */evt, /** Touch */touch) {
			touch = touch || (evt.pointerType == 'touch' ? evt : null);

			if (!this.nativeDraggable || touch) {
				if (this.options.supportPointer) {
					_on(document, 'pointermove', this._onTouchMove);
				} else if (touch) {
					_on(document, 'touchmove', this._onTouchMove);
				} else {
					_on(document, 'mousemove', this._onTouchMove);
				}
			} else {
				_on(dragEl, 'dragend', this);
				_on(rootEl, 'dragstart', this._onDragStart);
			}

			try {
				if (document.selection) {
					// Timeout neccessary for IE9
					_nextTick(function () {
						document.selection.empty();
					});
				} else {
					window.getSelection().removeAllRanges();
				}
			} catch (err) {
			}
		},

		_dragStarted: function (fallback, evt) {
			awaitingDragStarted = false;
			if (rootEl && dragEl) {
				if (this.nativeDraggable) {
					_on(document, 'dragover', this._handleAutoScroll);
					_on(document, 'dragover', _checkAlignment);
				}
				var options = this.options;

				// Apply effect
				!fallback && _toggleClass(dragEl, options.dragClass, false);
				_toggleClass(dragEl, options.ghostClass, true);

				// In case dragging an animated element
				_css(dragEl, 'transform', '');

				Sortable.active = this;

				fallback && this._appendGhost();

				// Drag start event
				_dispatchEvent(this, rootEl, 'start', dragEl, rootEl, rootEl, oldIndex, undefined, evt);
			} else {
				this._nulling();
			}
		},

		_emulateDragOver: function (forAutoScroll) {
			if (touchEvt) {
				if (this._lastX === touchEvt.clientX && this._lastY === touchEvt.clientY && !forAutoScroll) {
					return;
				}
				this._lastX = touchEvt.clientX;
				this._lastY = touchEvt.clientY;

				_hideGhostForTarget();

				var target = document.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
				var parent = target;

				while (target && target.shadowRoot) {
					target = target.shadowRoot.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
					parent = target;
				}

				if (parent) {
					do {
						if (parent[expando]) {
							var inserted;

							inserted = parent[expando]._onDragOver({
								clientX: touchEvt.clientX,
								clientY: touchEvt.clientY,
								target: target,
								rootEl: parent
							});

							if (inserted && !this.options.dragoverBubble) {
								break;
							}
						}

						target = parent; // store last element
					}
					/* jshint boss:true */
					while (parent = parent.parentNode);
				}
				dragEl.parentNode[expando]._computeIsAligned(touchEvt);

				_unhideGhostForTarget();
			}
		},


		_onTouchMove: function (/**TouchEvent*/evt, forAutoScroll) {
			if (tapEvt) {
				var	options = this.options,
					fallbackTolerance = options.fallbackTolerance,
					fallbackOffset = options.fallbackOffset,
					touch = evt.touches ? evt.touches[0] : evt,
					matrix = ghostEl && _matrix(ghostEl),
					scaleX = ghostEl && matrix && matrix.a,
					scaleY = ghostEl && matrix && matrix.d,
					relativeScrollOffset = PositionGhostAbsolutely && ghostRelativeParent && _getRelativeScrollOffset(ghostRelativeParent),
					dx = ((touch.clientX - tapEvt.clientX)
							+ fallbackOffset.x) / (scaleX || 1)
							+ (relativeScrollOffset ? (relativeScrollOffset[0] - ghostRelativeParentInitialScroll[0]) : 0) / (scaleX || 1),
					dy = ((touch.clientY - tapEvt.clientY)
							+ fallbackOffset.y) / (scaleY || 1)
							+ (relativeScrollOffset ? (relativeScrollOffset[1] - ghostRelativeParentInitialScroll[1]) : 0) / (scaleY || 1),
					translate3d = evt.touches ? 'translate3d(' + dx + 'px,' + dy + 'px,0)' : 'translate(' + dx + 'px,' + dy + 'px)';

				// only set the status to dragging, when we are actually dragging
				if (!Sortable.active && !awaitingDragStarted) {
					if (fallbackTolerance &&
						min(abs(touch.clientX - this._lastX), abs(touch.clientY - this._lastY)) < fallbackTolerance
					) {
						return;
					}
					this._onDragStart(evt, true);
				}

				!forAutoScroll && this._handleAutoScroll(touch, true);

				moved = true;
				touchEvt = touch;

				_css(ghostEl, 'webkitTransform', translate3d);
				_css(ghostEl, 'mozTransform', translate3d);
				_css(ghostEl, 'msTransform', translate3d);
				_css(ghostEl, 'transform', translate3d);

				evt.cancelable && evt.preventDefault();
			}
		},

		_appendGhost: function () {
			// Bug if using scale(): https://stackoverflow.com/questions/2637058
			// Not being adjusted for
			if (!ghostEl) {
				var container = this.options.fallbackOnBody ? document.body : rootEl,
					rect = _getRect(dragEl, true, container, !PositionGhostAbsolutely),
					css = _css(dragEl),
					options = this.options;

				// Position absolutely
				if (PositionGhostAbsolutely) {
					// Get relatively positioned parent
					ghostRelativeParent = container;

					while (
						_css(ghostRelativeParent, 'position') === 'static' &&
						_css(ghostRelativeParent, 'transform') === 'none' &&
						ghostRelativeParent !== document
					) {
						ghostRelativeParent = ghostRelativeParent.parentNode;
					}

					if (ghostRelativeParent !== document) {
						var ghostRelativeParentRect = _getRect(ghostRelativeParent, true);

						rect.top -= ghostRelativeParentRect.top;
						rect.left -= ghostRelativeParentRect.left;
					}

					if (ghostRelativeParent !== document.body && ghostRelativeParent !== document.documentElement) {
						if (ghostRelativeParent === document) ghostRelativeParent = _getWindowScrollingElement();

						rect.top += ghostRelativeParent.scrollTop;
						rect.left += ghostRelativeParent.scrollLeft;
					} else {
						ghostRelativeParent = _getWindowScrollingElement();
					}
					ghostRelativeParentInitialScroll = _getRelativeScrollOffset(ghostRelativeParent);
				}


				ghostEl = dragEl.cloneNode(true);

				_toggleClass(ghostEl, options.ghostClass, false);
				_toggleClass(ghostEl, options.fallbackClass, true);
				_toggleClass(ghostEl, options.dragClass, true);

				_css(ghostEl, 'box-sizing', 'border-box');
				_css(ghostEl, 'margin', 0);
				_css(ghostEl, 'top', rect.top);
				_css(ghostEl, 'left', rect.left);
				_css(ghostEl, 'width', rect.width);
				_css(ghostEl, 'height', rect.height);
				_css(ghostEl, 'opacity', '0.8');
				_css(ghostEl, 'position', (PositionGhostAbsolutely ? 'absolute' : 'fixed'));
				_css(ghostEl, 'zIndex', '100000');
				_css(ghostEl, 'pointerEvents', 'none');

				container.appendChild(ghostEl);
			}
		},

		_onDragStart: function (/**Event*/evt, /**boolean*/fallback) {
			var _this = this;
			var dataTransfer = evt.dataTransfer;
			var options = _this.options;

			// Setup clone
			cloneEl = _clone(dragEl);

			cloneEl.draggable = false;
			cloneEl.style['will-change'] = '';

			this._hideClone();

			_toggleClass(cloneEl, _this.options.chosenClass, false);


			// #1143: IFrame support workaround
			_this._cloneId = _nextTick(function () {
				if (!_this.options.removeCloneOnHide) {
					rootEl.insertBefore(cloneEl, dragEl);
				}
				_dispatchEvent(_this, rootEl, 'clone', dragEl);
			});


			!fallback && _toggleClass(dragEl, options.dragClass, true);

			// Set proper drop events
			if (fallback) {
				ignoreNextClick = true;
				_this._loopId = setInterval(_this._emulateDragOver, 50);
			} else {
				// Undo what was set in _prepareDragStart before drag started
				_off(document, 'mouseup', _this._onDrop);
				_off(document, 'touchend', _this._onDrop);
				_off(document, 'touchcancel', _this._onDrop);

				if (dataTransfer) {
					dataTransfer.effectAllowed = 'move';
					options.setData && options.setData.call(_this, dataTransfer, dragEl);
				}

				_on(document, 'drop', _this);

				// #1276 fix:
				_css(dragEl, 'transform', 'translateZ(0)');
			}

			awaitingDragStarted = true;

			_this._dragStartId = _nextTick(_this._dragStarted.bind(_this, fallback, evt));
			_on(document, 'selectstart', _this);
			if (Safari) {
				_css(document.body, 'user-select', 'none');
			}
		},


		// Returns true - if no further action is needed (either inserted or another condition)
		_onDragOver: function (/**Event*/evt) {
			var el = this.el,
				target = evt.target,
				dragRect,
				targetRect,
				revert,
				options = this.options,
				group = options.group,
				activeSortable = Sortable.active,
				isOwner = (activeGroup === group),
				canSort = options.sort,
				_this = this;

			if (_silent) return;

			// IE event order fix
			if (IE11OrLess && !evt.rootEl && !evt.artificialBubble && !_isTrueParentSortable(el, target)) {
				return;
			}

			// Return invocation when dragEl is inserted (or completed)
			function completed(insertion) {
				if (insertion) {
					if (isOwner) {
						activeSortable._hideClone();
					} else {
						activeSortable._showClone(_this);
					}

					if (activeSortable) {
						// Set ghost class to new sortable's ghost class
						_toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : activeSortable.options.ghostClass, false);
						_toggleClass(dragEl, options.ghostClass, true);
					}

					if (putSortable !== _this && _this !== Sortable.active) {
						putSortable = _this;
					} else if (_this === Sortable.active) {
						putSortable = null;
					}

					// Animation
					dragRect && _this._animate(dragRect, dragEl);
					target && targetRect && _this._animate(targetRect, target);
				}


				// Null lastTarget if it is not inside a previously swapped element
				if ((target === dragEl && !dragEl.animated) || (target === el && !target.animated)) {
					lastTarget = null;
				}
				// no bubbling and not fallback
				if (!options.dragoverBubble && !evt.rootEl && target !== document) {
					_this._handleAutoScroll(evt);
					dragEl.parentNode[expando]._computeIsAligned(evt);
				}

				!options.dragoverBubble && evt.stopPropagation && evt.stopPropagation();

				return true;
			}

			// Call when dragEl has been inserted
			function changed() {
				_dispatchEvent(_this, rootEl, 'change', target, el, rootEl, oldIndex, _index(dragEl, options.draggable), evt);
			}


			if (evt.preventDefault !== void 0) {
				evt.cancelable && evt.preventDefault();
			}


			moved = true;

			target = _closest(target, options.draggable, el, true);

			// target is dragEl or target is animated
			if (!!_closest(evt.target, null, dragEl, true) || target.animated) {
				return completed(false);
			}

			if (target !== dragEl) {
				ignoreNextClick = false;
			}

			if (activeSortable && !options.disabled &&
				(isOwner
					? canSort || (revert = !rootEl.contains(dragEl)) // Reverting item into the original list
					: (
						putSortable === this ||
						(
							(this.lastPutMode = activeGroup.checkPull(this, activeSortable, dragEl, evt)) &&
							group.checkPut(this, activeSortable, dragEl, evt)
						)
					)
				)
			) {
				var axis = this._getDirection(evt, target);

				dragRect = _getRect(dragEl);

				if (revert) {
					this._hideClone();
					parentEl = rootEl; // actualization

					if (nextEl) {
						rootEl.insertBefore(dragEl, nextEl);
					} else {
						rootEl.appendChild(dragEl);
					}

					return completed(true);
				}

				var elLastChild = _lastChild(el);

				if (!elLastChild || _ghostIsLast(evt, axis, el) && !elLastChild.animated) {
					// assign target only if condition is true
					if (elLastChild && el === evt.target) {
						target = elLastChild;
					}

					if (target) {
						targetRect = _getRect(target);
					}

					if (isOwner) {
						activeSortable._hideClone();
					} else {
						activeSortable._showClone(this);
					}

					if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, !!target) !== false) {
						el.appendChild(dragEl);
						parentEl = el; // actualization
						realDragElRect = null;

						changed();
						return completed(true);
					}
				}
				else if (target && target !== dragEl && target.parentNode === el) {
					var direction = 0,
						targetBeforeFirstSwap,
						aligned = target.sortableMouseAligned,
						differentLevel = dragEl.parentNode !== el,
						side1 = axis === 'vertical' ? 'top' : 'left',
						scrolledPastTop = _isScrolledPast(target, 'top') || _isScrolledPast(dragEl, 'top'),
						scrollBefore = scrolledPastTop ? scrolledPastTop.scrollTop : void 0;


					if (lastTarget !== target) {
						lastMode = null;
						targetBeforeFirstSwap = _getRect(target)[side1];
						pastFirstInvertThresh = false;
					}

					// Reference: https://www.lucidchart.com/documents/view/10fa0e93-e362-4126-aca2-b709ee56bd8b/0
					if (
						_isElInRowColumn(dragEl, target, axis) && aligned ||
						differentLevel ||
						scrolledPastTop ||
						options.invertSwap ||
						lastMode === 'insert' ||
						// Needed, in the case that we are inside target and inserted because not aligned... aligned will stay false while inside
						// and lastMode will change to 'insert', but we must swap
						lastMode === 'swap'
					) {
						// New target that we will be inside
						if (lastMode !== 'swap') {
							isCircumstantialInvert = options.invertSwap || differentLevel;
						}

						direction = _getSwapDirection(evt, target, axis,
							options.swapThreshold, options.invertedSwapThreshold == null ? options.swapThreshold : options.invertedSwapThreshold,
							isCircumstantialInvert,
							lastTarget === target);
						lastMode = 'swap';
					} else {
						// Insert at position
						direction = _getInsertDirection(target);
						lastMode = 'insert';
					}
					if (direction === 0) return completed(false);

					realDragElRect = null;
					lastTarget = target;

					lastDirection = direction;

					targetRect = _getRect(target);

					var nextSibling = target.nextElementSibling,
						after = false;

					after = direction === 1;

					var moveVector = _onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, after);

					if (moveVector !== false) {
						if (moveVector === 1 || moveVector === -1) {
							after = (moveVector === 1);
						}

						_silent = true;
						setTimeout(_unsilent, 30);

						if (isOwner) {
							activeSortable._hideClone();
						} else {
							activeSortable._showClone(this);
						}

						if (after && !nextSibling) {
							el.appendChild(dragEl);
						} else {
							target.parentNode.insertBefore(dragEl, after ? nextSibling : target);
						}

						// Undo chrome's scroll adjustment
						if (scrolledPastTop) {
							_scrollBy(scrolledPastTop, 0, scrollBefore - scrolledPastTop.scrollTop);
						}

						parentEl = dragEl.parentNode; // actualization

						// must be done before animation
						if (targetBeforeFirstSwap !== undefined && !isCircumstantialInvert) {
							targetMoveDistance = abs(targetBeforeFirstSwap - _getRect(target)[side1]);
						}
						changed();

						return completed(true);
					}
				}

				if (el.contains(dragEl)) {
					return completed(false);
				}
			}

			if (IE11OrLess && !evt.rootEl) {
				_artificalBubble(el, evt, '_onDragOver');
			}

			return false;
		},

		_animate: function (prevRect, target) {
			var ms = this.options.animation;

			if (ms) {
				var currentRect = _getRect(target);

				if (target === dragEl) {
					realDragElRect = currentRect;
				}

				if (prevRect.nodeType === 1) {
					prevRect = _getRect(prevRect);
				}

				// Check if actually moving position
				if ((prevRect.left + prevRect.width / 2) !== (currentRect.left + currentRect.width / 2)
					|| (prevRect.top + prevRect.height / 2) !== (currentRect.top + currentRect.height / 2)
				) {
					var matrix = _matrix(this.el),
						scaleX = matrix && matrix.a,
						scaleY = matrix && matrix.d;

					_css(target, 'transition', 'none');
					_css(target, 'transform', 'translate3d('
						+ (prevRect.left - currentRect.left) / (scaleX ? scaleX : 1) + 'px,'
						+ (prevRect.top - currentRect.top) / (scaleY ? scaleY : 1) + 'px,0)'
					);

					forRepaintDummy = target.offsetWidth; // repaint
					_css(target, 'transition', 'transform ' + ms + 'ms' + (this.options.easing ? ' ' + this.options.easing : ''));
					_css(target, 'transform', 'translate3d(0,0,0)');
				}

				(typeof target.animated === 'number') && clearTimeout(target.animated);
				target.animated = setTimeout(function () {
					_css(target, 'transition', '');
					_css(target, 'transform', '');
					target.animated = false;
				}, ms);
			}
		},

		_offUpEvents: function () {
			var ownerDocument = this.el.ownerDocument;

			_off(document, 'touchmove', this._onTouchMove);
			_off(document, 'pointermove', this._onTouchMove);
			_off(ownerDocument, 'mouseup', this._onDrop);
			_off(ownerDocument, 'touchend', this._onDrop);
			_off(ownerDocument, 'pointerup', this._onDrop);
			_off(ownerDocument, 'touchcancel', this._onDrop);
			_off(document, 'selectstart', this);
		},

		_onDrop: function (/**Event*/evt) {
			var el = this.el,
				options = this.options;
			awaitingDragStarted = false;
			scrolling = false;
			isCircumstantialInvert = false;
			pastFirstInvertThresh = false;

			clearInterval(this._loopId);

			clearInterval(pointerElemChangedInterval);
			_clearAutoScrolls();
			_cancelThrottle();

			clearTimeout(this._dragStartTimer);

			_cancelNextTick(this._cloneId);
			_cancelNextTick(this._dragStartId);

			// Unbind events
			_off(document, 'mousemove', this._onTouchMove);


			if (this.nativeDraggable) {
				_off(document, 'drop', this);
				_off(el, 'dragstart', this._onDragStart);
				_off(document, 'dragover', this._handleAutoScroll);
				_off(document, 'dragover', _checkAlignment);
			}

			if (Safari) {
				_css(document.body, 'user-select', '');
			}

			this._offUpEvents();

			if (evt) {
				if (moved) {
					evt.cancelable && evt.preventDefault();
					!options.dropBubble && evt.stopPropagation();
				}

				ghostEl && ghostEl.parentNode && ghostEl.parentNode.removeChild(ghostEl);

				if (rootEl === parentEl || (putSortable && putSortable.lastPutMode !== 'clone')) {
					// Remove clone
					cloneEl && cloneEl.parentNode && cloneEl.parentNode.removeChild(cloneEl);
				}

				if (dragEl) {
					if (this.nativeDraggable) {
						_off(dragEl, 'dragend', this);
					}

					_disableDraggable(dragEl);
					dragEl.style['will-change'] = '';

					// Remove class's
					_toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : this.options.ghostClass, false);
					_toggleClass(dragEl, this.options.chosenClass, false);

					// Drag stop event
					_dispatchEvent(this, rootEl, 'unchoose', dragEl, parentEl, rootEl, oldIndex, null, evt);

					if (rootEl !== parentEl) {
						newIndex = _index(dragEl, options.draggable);

						if (newIndex >= 0) {
							// Add event
							_dispatchEvent(null, parentEl, 'add', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);

							// Remove event
							_dispatchEvent(this, rootEl, 'remove', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);

							// drag from one list and drop into another
							_dispatchEvent(null, parentEl, 'sort', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);
							_dispatchEvent(this, rootEl, 'sort', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);
						}

						putSortable && putSortable.save();
					}
					else {
						if (dragEl.nextSibling !== nextEl) {
							// Get the index of the dragged element within its parent
							newIndex = _index(dragEl, options.draggable);

							if (newIndex >= 0) {
								// drag & drop within the same list
								_dispatchEvent(this, rootEl, 'update', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);
								_dispatchEvent(this, rootEl, 'sort', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);
							}
						}
					}

					if (Sortable.active) {
						/* jshint eqnull:true */
						if (newIndex == null || newIndex === -1) {
							newIndex = oldIndex;
						}
						_dispatchEvent(this, rootEl, 'end', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);

						// Save sorting
						this.save();
					}
				}

			}
			this._nulling();
		},

		_nulling: function() {
			rootEl =
			dragEl =
			parentEl =
			ghostEl =
			nextEl =
			cloneEl =
			lastDownEl =

			scrollEl =
			scrollParentEl =
			autoScrolls.length =

			pointerElemChangedInterval =
			lastPointerElemX =
			lastPointerElemY =

			tapEvt =
			touchEvt =

			moved =
			newIndex =
			oldIndex =

			lastTarget =
			lastDirection =

			forRepaintDummy =
			realDragElRect =

			putSortable =
			activeGroup =
			Sortable.active = null;

			savedInputChecked.forEach(function (el) {
				el.checked = true;
			});

			savedInputChecked.length = 0;
		},

		handleEvent: function (/**Event*/evt) {
			switch (evt.type) {
				case 'drop':
				case 'dragend':
					this._onDrop(evt);
					break;

				case 'dragenter':
				case 'dragover':
					if (dragEl) {
						this._onDragOver(evt);
						_globalDragOver(evt);
					}
					break;

				case 'selectstart':
					evt.preventDefault();
					break;
			}
		},


		/**
		 * Serializes the item into an array of string.
		 * @returns {String[]}
		 */
		toArray: function () {
			var order = [],
				el,
				children = this.el.children,
				i = 0,
				n = children.length,
				options = this.options;

			for (; i < n; i++) {
				el = children[i];
				if (_closest(el, options.draggable, this.el, false)) {
					order.push(el.getAttribute(options.dataIdAttr) || _generateId(el));
				}
			}

			return order;
		},


		/**
		 * Sorts the elements according to the array.
		 * @param  {String[]}  order  order of the items
		 */
		sort: function (order) {
			var items = {}, rootEl = this.el;

			this.toArray().forEach(function (id, i) {
				var el = rootEl.children[i];

				if (_closest(el, this.options.draggable, rootEl, false)) {
					items[id] = el;
				}
			}, this);

			order.forEach(function (id) {
				if (items[id]) {
					rootEl.removeChild(items[id]);
					rootEl.appendChild(items[id]);
				}
			});
		},


		/**
		 * Save the current sorting
		 */
		save: function () {
			var store = this.options.store;
			store && store.set && store.set(this);
		},


		/**
		 * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
		 * @param   {HTMLElement}  el
		 * @param   {String}       [selector]  default: `options.draggable`
		 * @returns {HTMLElement|null}
		 */
		closest: function (el, selector) {
			return _closest(el, selector || this.options.draggable, this.el, false);
		},


		/**
		 * Set/get option
		 * @param   {string} name
		 * @param   {*}      [value]
		 * @returns {*}
		 */
		option: function (name, value) {
			var options = this.options;

			if (value === void 0) {
				return options[name];
			} else {
				options[name] = value;

				if (name === 'group') {
					_prepareGroup(options);
				}
			}
		},


		/**
		 * Destroy
		 */
		destroy: function () {
			var el = this.el;

			el[expando] = null;

			_off(el, 'mousedown', this._onTapStart);
			_off(el, 'touchstart', this._onTapStart);
			_off(el, 'pointerdown', this._onTapStart);

			if (this.nativeDraggable) {
				_off(el, 'dragover', this);
				_off(el, 'dragenter', this);
			}
			// Remove draggable attributes
			Array.prototype.forEach.call(el.querySelectorAll('[draggable]'), function (el) {
				el.removeAttribute('draggable');
			});

			this._onDrop();

			sortables.splice(sortables.indexOf(this.el), 1);

			this.el = el = null;
		},

		_hideClone: function() {
			if (!cloneEl.cloneHidden) {
				_css(cloneEl, 'display', 'none');
				cloneEl.cloneHidden = true;
				if (cloneEl.parentNode && this.options.removeCloneOnHide) {
					cloneEl.parentNode.removeChild(cloneEl);
				}
			}
		},

		_showClone: function(putSortable) {
			if (putSortable.lastPutMode !== 'clone') {
				this._hideClone();
				return;
			}

			if (cloneEl.cloneHidden) {
				// show clone at dragEl or original position
				if (rootEl.contains(dragEl) && !this.options.group.revertClone) {
					rootEl.insertBefore(cloneEl, dragEl);
				} else if (nextEl) {
					rootEl.insertBefore(cloneEl, nextEl);
				} else {
					rootEl.appendChild(cloneEl);
				}

				if (this.options.group.revertClone) {
					this._animate(dragEl, cloneEl);
				}
				_css(cloneEl, 'display', '');
				cloneEl.cloneHidden = false;
			}
		}
	};

	function _closest(/**HTMLElement*/el, /**String*/selector, /**HTMLElement*/ctx, includeCTX) {
		if (el) {
			ctx = ctx || document;

			do {
				if (
					selector != null &&
					(
						selector[0] === '>' && el.parentNode === ctx && _matches(el, selector.substring(1)) ||
						_matches(el, selector)
					) ||
					includeCTX && el === ctx
				) {
					return el;
				}

				if (el === ctx) break;
				/* jshint boss:true */
			} while (el = _getParentOrHost(el));
		}

		return null;
	}


	function _getParentOrHost(el) {
		return (el.host && el !== document && el.host.nodeType)
			? el.host
			: el.parentNode;
	}


	function _globalDragOver(/**Event*/evt) {
		if (evt.dataTransfer) {
			evt.dataTransfer.dropEffect = 'move';
		}
		evt.cancelable && evt.preventDefault();
	}


	function _on(el, event, fn) {
		el.addEventListener(event, fn, captureMode);
	}


	function _off(el, event, fn) {
		el.removeEventListener(event, fn, captureMode);
	}


	function _toggleClass(el, name, state) {
		if (el && name) {
			if (el.classList) {
				el.classList[state ? 'add' : 'remove'](name);
			}
			else {
				var className = (' ' + el.className + ' ').replace(R_SPACE, ' ').replace(' ' + name + ' ', ' ');
				el.className = (className + (state ? ' ' + name : '')).replace(R_SPACE, ' ');
			}
		}
	}


	function _css(el, prop, val) {
		var style = el && el.style;

		if (style) {
			if (val === void 0) {
				if (document.defaultView && document.defaultView.getComputedStyle) {
					val = document.defaultView.getComputedStyle(el, '');
				}
				else if (el.currentStyle) {
					val = el.currentStyle;
				}

				return prop === void 0 ? val : val[prop];
			}
			else {
				if (!(prop in style) && prop.indexOf('webkit') === -1) {
					prop = '-webkit-' + prop;
				}

				style[prop] = val + (typeof val === 'string' ? '' : 'px');
			}
		}
	}

	function _matrix(el) {
		var appliedTransforms = '';
		do {
			var transform = _css(el, 'transform');

			if (transform && transform !== 'none') {
				appliedTransforms = transform + ' ' + appliedTransforms;
			}
			/* jshint boss:true */
		} while (el = el.parentNode);

		if (window.DOMMatrix) {
			return new DOMMatrix(appliedTransforms);
		} else if (window.WebKitCSSMatrix) {
			return new WebKitCSSMatrix(appliedTransforms);
		} else if (window.CSSMatrix) {
			return new CSSMatrix(appliedTransforms);
		}
	}


	function _find(ctx, tagName, iterator) {
		if (ctx) {
			var list = ctx.getElementsByTagName(tagName), i = 0, n = list.length;

			if (iterator) {
				for (; i < n; i++) {
					iterator(list[i], i);
				}
			}

			return list;
		}

		return [];
	}



	function _dispatchEvent(sortable, rootEl, name, targetEl, toEl, fromEl, startIndex, newIndex, originalEvt) {
		sortable = (sortable || rootEl[expando]);
		var evt,
			options = sortable.options,
			onName = 'on' + name.charAt(0).toUpperCase() + name.substr(1);
		// Support for new CustomEvent feature
		if (window.CustomEvent && !IE11OrLess && !Edge) {
			evt = new CustomEvent(name, {
				bubbles: true,
				cancelable: true
			});
		} else {
			evt = document.createEvent('Event');
			evt.initEvent(name, true, true);
		}

		evt.to = toEl || rootEl;
		evt.from = fromEl || rootEl;
		evt.item = targetEl || rootEl;
		evt.clone = cloneEl;

		evt.oldIndex = startIndex;
		evt.newIndex = newIndex;

		evt.originalEvent = originalEvt;
		evt.pullMode = putSortable ? putSortable.lastPutMode : undefined;

		if (rootEl) {
			rootEl.dispatchEvent(evt);
		}

		if (options[onName]) {
			options[onName].call(sortable, evt);
		}
	}


	function _onMove(fromEl, toEl, dragEl, dragRect, targetEl, targetRect, originalEvt, willInsertAfter) {
		var evt,
			sortable = fromEl[expando],
			onMoveFn = sortable.options.onMove,
			retVal;
		// Support for new CustomEvent feature
		if (window.CustomEvent && !IE11OrLess && !Edge) {
			evt = new CustomEvent('move', {
				bubbles: true,
				cancelable: true
			});
		} else {
			evt = document.createEvent('Event');
			evt.initEvent('move', true, true);
		}

		evt.to = toEl;
		evt.from = fromEl;
		evt.dragged = dragEl;
		evt.draggedRect = dragRect;
		evt.related = targetEl || toEl;
		evt.relatedRect = targetRect || _getRect(toEl);
		evt.willInsertAfter = willInsertAfter;

		evt.originalEvent = originalEvt;

		fromEl.dispatchEvent(evt);

		if (onMoveFn) {
			retVal = onMoveFn.call(sortable, evt, originalEvt);
		}

		return retVal;
	}

	function _disableDraggable(el) {
		el.draggable = false;
	}

	function _unsilent() {
		_silent = false;
	}

	/**
	 * Gets nth child of el, ignoring hidden children, sortable's elements (does not ignore clone if it's visible)
	 * and non-draggable elements
	 * @param  {HTMLElement} el       The parent element
	 * @param  {Number} childNum      The index of the child
	 * @param  {Object} options       Parent Sortable's options
	 * @return {HTMLElement}          The child at index childNum, or null if not found
	 */
	function _getChild(el, childNum, options) {
		var currentChild = 0,
			i = 0,
			children = el.children;

		while (i < children.length) {
			if (
				children[i].style.display !== 'none' &&
				children[i] !== ghostEl &&
				children[i] !== dragEl &&
				_closest(children[i], options.draggable, el, false)
			) {
				if (currentChild === childNum) {
					return children[i];
				}
				currentChild++;
			}

			i++;
		}
		return null;
	}

	/**
	 * Gets the last child in the el, ignoring ghostEl or invisible elements (clones)
	 * @param  {HTMLElement} el       Parent element
	 * @return {HTMLElement}          The last child, ignoring ghostEl
	 */
	function _lastChild(el) {
		var last = el.lastElementChild;

		while (last && (last === ghostEl || last.style.display === 'none')) {
			last = last.previousElementSibling;
		}

		return last || null;
	}

	function _ghostIsLast(evt, axis, el) {
		var elRect = _getRect(_lastChild(el)),
			mouseOnAxis = axis === 'vertical' ? evt.clientY : evt.clientX,
			mouseOnOppAxis = axis === 'vertical' ? evt.clientX : evt.clientY,
			targetS2 = axis === 'vertical' ? elRect.bottom : elRect.right,
			targetS1Opp = axis === 'vertical' ? elRect.left : elRect.top,
			targetS2Opp = axis === 'vertical' ? elRect.right : elRect.bottom,
			spacer = 10;

		return (
			axis === 'vertical' ?
				(mouseOnOppAxis > targetS2Opp + spacer || mouseOnOppAxis <= targetS2Opp && mouseOnAxis > targetS2 && mouseOnOppAxis >= targetS1Opp) :
				(mouseOnAxis > targetS2 && mouseOnOppAxis > targetS1Opp || mouseOnAxis <= targetS2 && mouseOnOppAxis > targetS2Opp + spacer)
		);
	}

	function _getSwapDirection(evt, target, axis, swapThreshold, invertedSwapThreshold, invertSwap, isLastTarget) {
		var targetRect = _getRect(target),
			mouseOnAxis = axis === 'vertical' ? evt.clientY : evt.clientX,
			targetLength = axis === 'vertical' ? targetRect.height : targetRect.width,
			targetS1 = axis === 'vertical' ? targetRect.top : targetRect.left,
			targetS2 = axis === 'vertical' ? targetRect.bottom : targetRect.right,
			dragRect = _getRect(dragEl),
			invert = false;


		if (!invertSwap) {
			// Never invert or create dragEl shadow when target movemenet causes mouse to move past the end of regular swapThreshold
			if (isLastTarget && targetMoveDistance < targetLength * swapThreshold) { // multiplied only by swapThreshold because mouse will already be inside target by (1 - threshold) * targetLength / 2
				// check if past first invert threshold on side opposite of lastDirection
				if (!pastFirstInvertThresh &&
					(lastDirection === 1 ?
						(
							mouseOnAxis > targetS1 + targetLength * invertedSwapThreshold / 2
						) :
						(
							mouseOnAxis < targetS2 - targetLength * invertedSwapThreshold / 2
						)
					)
				)
				{
					// past first invert threshold, do not restrict inverted threshold to dragEl shadow
					pastFirstInvertThresh = true;
				}

				if (!pastFirstInvertThresh) {
					var dragS1 = axis === 'vertical' ? dragRect.top : dragRect.left,
						dragS2 = axis === 'vertical' ? dragRect.bottom : dragRect.right;
					// dragEl shadow (target move distance shadow)
					if (
						lastDirection === 1 ?
						(
							mouseOnAxis < targetS1 + targetMoveDistance // over dragEl shadow
						) :
						(
							mouseOnAxis > targetS2 - targetMoveDistance
						)
					)
					{
						return lastDirection * -1;
					}
				} else {
					invert = true;
				}
			} else {
				// Regular
				if (
					mouseOnAxis > targetS1 + (targetLength * (1 - swapThreshold) / 2) &&
					mouseOnAxis < targetS2 - (targetLength * (1 - swapThreshold) / 2)
				) {
					return _getInsertDirection(target);
				}
			}
		}

		invert = invert || invertSwap;

		if (invert) {
			// Invert of regular
			if (
				mouseOnAxis < targetS1 + (targetLength * invertedSwapThreshold / 2) ||
				mouseOnAxis > targetS2 - (targetLength * invertedSwapThreshold / 2)
			)
			{
				return ((mouseOnAxis > targetS1 + targetLength / 2) ? 1 : -1);
			}
		}

		return 0;
	}

	/**
	 * Gets the direction dragEl must be swapped relative to target in order to make it
	 * seem that dragEl has been "inserted" into that element's position
	 * @param  {HTMLElement} target       The target whose position dragEl is being inserted at
	 * @return {Number}                   Direction dragEl must be swapped
	 */
	function _getInsertDirection(target) {
		var dragElIndex = _index(dragEl),
			targetIndex = _index(target);

		if (dragElIndex < targetIndex) {
			return 1;
		} else {
			return -1;
		}
	}


	/**
	 * Generate id
	 * @param   {HTMLElement} el
	 * @returns {String}
	 * @private
	 */
	function _generateId(el) {
		var str = el.tagName + el.className + el.src + el.href + el.textContent,
			i = str.length,
			sum = 0;

		while (i--) {
			sum += str.charCodeAt(i);
		}

		return sum.toString(36);
	}

	/**
	 * Returns the index of an element within its parent for a selected set of
	 * elements
	 * @param  {HTMLElement} el
	 * @param  {selector} selector
	 * @return {number}
	 */
	function _index(el, selector) {
		var index = 0;

		if (!el || !el.parentNode) {
			return -1;
		}

		while (el && (el = el.previousElementSibling)) {
			if ((el.nodeName.toUpperCase() !== 'TEMPLATE') && el !== cloneEl) {
				index++;
			}
		}

		return index;
	}

	function _matches(/**HTMLElement*/el, /**String*/selector) {
		if (el) {
			try {
				if (el.matches) {
					return el.matches(selector);
				} else if (el.msMatchesSelector) {
					return el.msMatchesSelector(selector);
				} else if (el.webkitMatchesSelector) {
					return el.webkitMatchesSelector(selector);
				}
			} catch(_) {
				return false;
			}
		}

		return false;
	}

	var _throttleTimeout;
	function _throttle(callback, ms) {
		return function () {
			if (!_throttleTimeout) {
				var args = arguments,
					_this = this;

				_throttleTimeout = setTimeout(function () {
					if (args.length === 1) {
						callback.call(_this, args[0]);
					} else {
						callback.apply(_this, args);
					}

					_throttleTimeout = void 0;
				}, ms);
			}
		};
	}

	function _cancelThrottle() {
		clearTimeout(_throttleTimeout);
		_throttleTimeout = void 0;
	}

	function _extend(dst, src) {
		if (dst && src) {
			for (var key in src) {
				if (src.hasOwnProperty(key)) {
					dst[key] = src[key];
				}
			}
		}

		return dst;
	}

	function _clone(el) {
		if (Polymer && Polymer.dom) {
			return Polymer.dom(el).cloneNode(true);
		}
		else if ($) {
			return $(el).clone(true)[0];
		}
		else {
			return el.cloneNode(true);
		}
	}

	function _saveInputCheckedState(root) {
		savedInputChecked.length = 0;

		var inputs = root.getElementsByTagName('input');
		var idx = inputs.length;

		while (idx--) {
			var el = inputs[idx];
			el.checked && savedInputChecked.push(el);
		}
	}

	function _nextTick(fn) {
		return setTimeout(fn, 0);
	}

	function _cancelNextTick(id) {
		return clearTimeout(id);
	}


	/**
	 * Returns the "bounding client rect" of given element
	 * @param  {HTMLElement} el                The element whose boundingClientRect is wanted
	 * @param  {[HTMLElement]} container       the parent the element will be placed in
	 * @param  {[Boolean]} adjustForTransform  Whether the rect should compensate for parent's transform
	 * @return {Object}                        The boundingClientRect of el
	 */
	function _getRect(el, adjustForTransform, container, adjustForFixed) {
		if (!el.getBoundingClientRect && el !== win) return;

		var elRect,
			top,
			left,
			bottom,
			right,
			height,
			width;

		if (el !== win && el !== _getWindowScrollingElement()) {
			elRect = el.getBoundingClientRect();
			top = elRect.top;
			left = elRect.left;
			bottom = elRect.bottom;
			right = elRect.right;
			height = elRect.height;
			width = elRect.width;
		} else {
			top = 0;
			left = 0;
			bottom = window.innerHeight;
			right = window.innerWidth;
			height = window.innerHeight;
			width = window.innerWidth;
		}

		if (adjustForFixed && el !== win) {
			// Adjust for translate()
			container = container || el.parentNode;

			// solves #1123 (see: https://stackoverflow.com/a/37953806/6088312)
			// Not needed on <= IE11
			if (!IE11OrLess) {
				do {
					if (container && container.getBoundingClientRect && _css(container, 'transform') !== 'none') {
						var containerRect = container.getBoundingClientRect();

						// Set relative to edges of padding box of container
						top -= containerRect.top + parseInt(_css(container, 'border-top-width'));
						left -= containerRect.left + parseInt(_css(container, 'border-left-width'));
						bottom = top + elRect.height;
						right = left + elRect.width;

						break;
					}
					/* jshint boss:true */
				} while (container = container.parentNode);
			}
		}

		if (adjustForTransform && el !== win) {
			// Adjust for scale()
			var matrix = _matrix(container || el),
				scaleX = matrix && matrix.a,
				scaleY = matrix && matrix.d;

			if (matrix) {
				top /= scaleY;
				left /= scaleX;

				width /= scaleX;
				height /= scaleY;

				bottom = top + height;
				right = left + width;
			}
		}

		return {
			top: top,
			left: left,
			bottom: bottom,
			right: right,
			width: width,
			height: height
		};
	}


	/**
	 * Checks if a side of an element is scrolled past a side of it's parents
	 * @param  {HTMLElement}  el       The element who's side being scrolled out of view is in question
	 * @param  {String}       side     Side of the element in question ('top', 'left', 'right', 'bottom')
	 * @return {HTMLElement}           The parent scroll element that the el's side is scrolled past, or null if there is no such element
	 */
	function _isScrolledPast(el, side) {
		var parent = _getParentAutoScrollElement(el, true),
			elSide = _getRect(el)[side];

		/* jshint boss:true */
		while (parent) {
			var parentSide = _getRect(parent)[side],
				visible;

			if (side === 'top' || side === 'left') {
				visible = elSide >= parentSide;
			} else {
				visible = elSide <= parentSide;
			}

			if (!visible) return parent;

			if (parent === _getWindowScrollingElement()) break;

			parent = _getParentAutoScrollElement(parent, false);
		}

		return false;
	}

	/**
	 * Returns the scroll offset of the given element, added with all the scroll offsets of parent elements.
	 * The value is returned in real pixels.
	 * @param  {HTMLElement} el
	 * @return {Array}             Offsets in the format of [left, top]
	 */
	function _getRelativeScrollOffset(el) {
		var offsetLeft = 0,
			offsetTop = 0,
			winScroller = _getWindowScrollingElement();

		if (el) {
			do {
				var matrix = _matrix(el),
					scaleX = matrix.a,
					scaleY = matrix.d;

				offsetLeft += el.scrollLeft * scaleX;
				offsetTop += el.scrollTop * scaleY;
			} while (el !== winScroller && (el = el.parentNode));
		}

		return [offsetLeft, offsetTop];
	}

	// Fixed #973:
	_on(document, 'touchmove', function(evt) {
		if ((Sortable.active || awaitingDragStarted) && evt.cancelable) {
			evt.preventDefault();
		}
	});


	// Export utils
	Sortable.utils = {
		on: _on,
		off: _off,
		css: _css,
		find: _find,
		is: function (el, selector) {
			return !!_closest(el, selector, el, false);
		},
		extend: _extend,
		throttle: _throttle,
		closest: _closest,
		toggleClass: _toggleClass,
		clone: _clone,
		index: _index,
		nextTick: _nextTick,
		cancelNextTick: _cancelNextTick,
		detectDirection: _detectDirection,
		getChild: _getChild
	};


	/**
	 * Create sortable instance
	 * @param {HTMLElement}  el
	 * @param {Object}      [options]
	 */
	Sortable.create = function (el, options) {
		return new Sortable(el, options);
	};


	// Export
	Sortable.version = '1.8.4';
	return Sortable;
});


/***/ }),
/* 142 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(143)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(145)
/* template */
var __vue_template__ = __webpack_require__(146)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/component_items/list/style.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1d6e760d", Component.options)
  } else {
    hotAPI.reload("data-v-1d6e760d", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 143 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(144);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(4)("59ce1f0b", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../../node_modules/css-loader/index.js!../../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-1d6e760d\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./style.vue", function() {
     var newContent = require("!!../../../../../../node_modules/css-loader/index.js!../../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-1d6e760d\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./style.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 144 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)(false);
// imports


// module
exports.push([module.i, "\n.list-style>input[type=radio] {\n    position: absolute;\n    visibility: hidden;\n    display: none;\n}\n.list-style>label {\n    display: inline-block;\n    cursor: pointer;\n    padding: 0px 5px 0px 5px;\n    font-weight: 500;\n    color: #202020;\n}\n.list-style>input[type=radio]:checked+label {\n    border-bottom: 2px solid #e6b4b4c5;\n}\n.list-style {\n    display: inline-block;\n    overflow: hidden;\n}\n\n", ""]);

// exports


/***/ }),
/* 145 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        class_name: {
            type: String,
            default: function _default() {
                return '';
            }
        },
        value: {
            type: String,
            default: function _default() {
                return null;
            }
        }
    },
    data: function data() {
        return {
            list_style: 'number'
        };
    },
    mounted: function mounted() {
        if (this.value === null || this.value === '') this.list_style = null;else this.list_style = this.value;
    },

    watch: {
        list_style: function list_style(val) {
            this.$emit('input', val);
        },
        value: function value(val) {
            this.list_style = val;
        }
    }
});

/***/ }),
/* 146 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { class: _vm.class_name },
    [
      _c("div", { staticClass: "list-style" }, [
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.list_style,
              expression: "list_style"
            }
          ],
          attrs: {
            type: "radio",
            name: "list_style_" + _vm._uid,
            id: "none_" + _vm._uid
          },
          domProps: { checked: _vm._q(_vm.list_style, null) },
          on: {
            change: function($event) {
              _vm.list_style = null
            }
          }
        }),
        _vm._v(" "),
        _c("label", { attrs: { for: "none_" + _vm._uid } }, [_vm._v("None")]),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.list_style,
              expression: "list_style"
            }
          ],
          attrs: {
            type: "radio",
            name: "list_style_" + _vm._uid,
            id: "_number" + _vm._uid,
            value: "number"
          },
          domProps: { checked: _vm._q(_vm.list_style, "number") },
          on: {
            change: function($event) {
              _vm.list_style = "number"
            }
          }
        }),
        _vm._v(" "),
        _c("label", { attrs: { for: "_number" + _vm._uid } }, [
          _vm._v("Number")
        ]),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.list_style,
              expression: "list_style"
            }
          ],
          attrs: {
            type: "radio",
            name: "list_style_" + _vm._uid,
            id: "lower_alpha_" + _vm._uid,
            value: "lower_alpha"
          },
          domProps: { checked: _vm._q(_vm.list_style, "lower_alpha") },
          on: {
            change: function($event) {
              _vm.list_style = "lower_alpha"
            }
          }
        }),
        _vm._v(" "),
        _c("label", { attrs: { for: "lower_alpha_" + _vm._uid } }, [
          _vm._v("Lower Alpha")
        ]),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.list_style,
              expression: "list_style"
            }
          ],
          attrs: {
            type: "radio",
            name: "list_style_" + _vm._uid,
            id: "upper_alpha_" + _vm._uid,
            value: "upper_alpha"
          },
          domProps: { checked: _vm._q(_vm.list_style, "upper_alpha") },
          on: {
            change: function($event) {
              _vm.list_style = "upper_alpha"
            }
          }
        }),
        _vm._v(" "),
        _c("label", { attrs: { for: "upper_alpha_" + _vm._uid } }, [
          _vm._v("Upper Alpha")
        ]),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.list_style,
              expression: "list_style"
            }
          ],
          attrs: {
            type: "radio",
            name: "list_style_" + _vm._uid,
            id: "circle_" + _vm._uid,
            value: "circle"
          },
          domProps: { checked: _vm._q(_vm.list_style, "circle") },
          on: {
            change: function($event) {
              _vm.list_style = "circle"
            }
          }
        }),
        _vm._v(" "),
        _c("label", { attrs: { for: "circle_" + _vm._uid } }, [
          _vm._v("Circle")
        ]),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.list_style,
              expression: "list_style"
            }
          ],
          attrs: {
            type: "radio",
            name: "list_style_" + _vm._uid,
            id: "square_" + _vm._uid,
            value: "square"
          },
          domProps: { checked: _vm._q(_vm.list_style, "square") },
          on: {
            change: function($event) {
              _vm.list_style = "square"
            }
          }
        }),
        _vm._v(" "),
        _c("label", { attrs: { for: "square_" + _vm._uid } }, [
          _vm._v("Square")
        ]),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.list_style,
              expression: "list_style"
            }
          ],
          attrs: {
            type: "radio",
            name: "list_style_" + _vm._uid,
            id: "upper_roman_" + _vm._uid,
            value: "upper_roman"
          },
          domProps: { checked: _vm._q(_vm.list_style, "upper_roman") },
          on: {
            change: function($event) {
              _vm.list_style = "upper_roman"
            }
          }
        }),
        _vm._v(" "),
        _c("label", { attrs: { for: "upper_roman_" + _vm._uid } }, [
          _vm._v("Upper Roman")
        ]),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.list_style,
              expression: "list_style"
            }
          ],
          attrs: {
            type: "radio",
            name: "list_style_" + _vm._uid,
            id: "lower_roman_" + _vm._uid,
            value: "lower_roman"
          },
          domProps: { checked: _vm._q(_vm.list_style, "lower_roman") },
          on: {
            change: function($event) {
              _vm.list_style = "lower_roman"
            }
          }
        }),
        _vm._v(" "),
        _c("label", { attrs: { for: "lower_roman_" + _vm._uid } }, [
          _vm._v("Lower Roman")
        ])
      ]),
      _vm._v(" "),
      _vm._t("default")
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-1d6e760d", module.exports)
  }
}

/***/ }),
/* 147 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(148)
/* template */
var __vue_template__ = __webpack_require__(149)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/component_items/list/preview.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7f3cd7a4", Component.options)
  } else {
    hotAPI.reload("data-v-7f3cd7a4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 148 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            id: '',
            list: new OrderedList()

        };
    },
    methods: {
        show: function show(list) {
            this.list = list;
            this.$refs.modal.show();
        }

    }
});

/***/ }),
/* 149 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "modal",
    { ref: "modal", attrs: { extended_width: true } },
    [
      _c("template", { slot: "header" }, [_vm._v("List Preview")]),
      _vm._v(" "),
      _c("template", { slot: "body" }, [
        _c("div", { domProps: { innerHTML: _vm._s(_vm.list.toString()) } })
      ]),
      _vm._v(" "),
      _c("template", { slot: "footer" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-default",
            attrs: { type: "button", "data-dismiss": "modal" }
          },
          [_vm._v("Close")]
        )
      ])
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-7f3cd7a4", module.exports)
  }
}

/***/ }),
/* 150 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(151)
/* template */
var __vue_template__ = __webpack_require__(152)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/sub-items/paragraph.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-187a17b4", Component.options)
  } else {
    hotAPI.reload("data-v-187a17b4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 151 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            id: '',
            paragraph: new Paragraph()
        };
    },
    methods: {
        show: function show() {
            this.$refs.cParagraph.reset();
            this.$refs.modal.show();
        },
        edit: function edit(paragraph, id) {

            this.$refs.cParagraph.reset();
            this.id = id;
            this.paragraph.value = paragraph.value;
            if (paragraph.meta !== undefined) this.paragraph.meta = paragraph.meta;

            this.$refs.modal.show();
        },

        emit_save: function emit_save(varpar) {
            this.$emit('saved', varpar);
        },
        emit_update: function emit_update(varpar) {
            this.$emit('updated', {
                id: this.id,
                updated_data: varpar
            });
            this.id = '';
        },

        save: function save() {
            var vm = this;

            // Check if the paragraph is chunk by break line
            if (vm.$refs.cParagraph.isChunk === true) {
                var chunk_value = vm.$refs.cParagraph.chunk();

                // Tell the parent that there are multiple values to be saved
                chunk_value.forEach(function (parChunked) {
                    if (vm.id === '') {
                        vm.emit_save(parChunked);
                    } else {
                        vm.emit_update(parChunked);
                    }
                });
            } else {
                if (vm.id === '') {
                    vm.emit_save(vm.paragraph);
                } else {
                    vm.emit_update(vm.paragraph);
                }
            }
            vm.$refs.modal.dismiss();
        }
    }
});

/***/ }),
/* 152 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "modal",
    { ref: "modal", attrs: { extended_width: true } },
    [
      _c("template", { slot: "header" }, [_vm._v("Add Paragraph")]),
      _vm._v(" "),
      _c(
        "template",
        { slot: "body" },
        [
          _c("c-par", {
            ref: "cParagraph",
            model: {
              value: _vm.paragraph,
              callback: function($$v) {
                _vm.paragraph = $$v
              },
              expression: "paragraph"
            }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c("template", { slot: "footer" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-default",
            attrs: { type: "button", "data-dismiss": "modal" }
          },
          [_vm._v("Close")]
        ),
        _vm._v(" "),
        _c(
          "button",
          {
            staticClass: "btn btn-default",
            attrs: { type: "button" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.save($event)
              }
            }
          },
          [_vm._v("Save")]
        )
      ])
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-187a17b4", module.exports)
  }
}

/***/ }),
/* 153 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(154)
/* template */
var __vue_template__ = __webpack_require__(155)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/sub-items/table.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-412072f4", Component.options)
  } else {
    hotAPI.reload("data-v-412072f4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 154 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            id: '',
            table: new Table()
        };
    },
    methods: {
        show: function show() {
            this.$refs.cTable.reset();
            this.$refs.modal.show();
        },

        edit: function edit(table, id) {
            this.$refs.cTable.reset();
            this.id = id;
            this.table.header = table.header;
            this.table.rows = table.rows;
            this.$refs.modal.show();
        },
        save: function save() {
            if (this.id === '') {
                // Save
                this.$emit('saved', this.table);
            } else {
                // update
                this.$emit('updated', {
                    id: this.id,
                    updated_data: this.table
                });
            }

            this.$refs.modal.dismiss();
        }
    }

});

/***/ }),
/* 155 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "modal",
    { ref: "modal", attrs: { extended_width: true } },
    [
      _c("template", { slot: "header" }, [_vm._v("Create Table")]),
      _vm._v(" "),
      _c(
        "template",
        { slot: "body" },
        [
          _c("c-table", {
            ref: "cTable",
            model: {
              value: _vm.table,
              callback: function($$v) {
                _vm.table = $$v
              },
              expression: "table"
            }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c("template", { slot: "footer" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-default",
            attrs: { type: "button", "data-dismiss": "modal" }
          },
          [_vm._v("Close")]
        ),
        _vm._v(" "),
        _c(
          "button",
          {
            staticClass: "btn btn-default",
            attrs: { type: "button" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.save($event)
              }
            }
          },
          [_vm._v("Save")]
        )
      ])
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-412072f4", module.exports)
  }
}

/***/ }),
/* 156 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(157)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(159)
/* template */
var __vue_template__ = __webpack_require__(160)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/sub-items/list.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-31e21116", Component.options)
  } else {
    hotAPI.reload("data-v-31e21116", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 157 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(158);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(4)("04b64c80", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-31e21116\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./list.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-31e21116\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./list.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 158 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)(false);
// imports


// module
exports.push([module.i, "\n.list-tab>.tab-content {\n    min-height: 0px;\n}\n\n", ""]);

// exports


/***/ }),
/* 159 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        value: {
            type: [Object, Array],
            default: function _default() {
                return new OrderedList();
            }
        }
    },
    data: function data() {
        return {
            list: new OrderedList()
        };
    },
    mounted: function mounted() {
        this.$nextTick(function () {
            // Value must be loaded after the whole component is loaded
            this.list = this.value;
        });
    },

    watch: {
        list: {
            deep: true,
            handler: function handler(val) {
                this.$emit('input', val);
            }
        },
        value: {
            deep: true,
            handler: function handler(val) {
                this.list = val;
            }
        }

    }

});

/***/ }),
/* 160 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "nav-tabs-custom list-tab" }, [
    _c("ul", { staticClass: "nav nav-tabs" }, [
      _c("li", { staticClass: "active" }, [
        _c(
          "a",
          { attrs: { href: "#tab_rep" + _vm._uid, "data-toggle": "tab" } },
          [_vm._v("Display")]
        )
      ]),
      _vm._v(" "),
      _c("li", [
        _c(
          "a",
          { attrs: { href: "#tab_struct" + _vm._uid, "data-toggle": "tab" } },
          [_vm._v("Structure")]
        )
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "tab-content" }, [
      _c(
        "div",
        { staticClass: "tab-pane active", attrs: { id: "tab_rep" + _vm._uid } },
        [_c("div", { domProps: { innerHTML: _vm._s(_vm.list.toString()) } })]
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "tab-pane", attrs: { id: "tab_struct" + _vm._uid } },
        [
          _c("c-list", {
            model: {
              value: _vm.list,
              callback: function($$v) {
                _vm.list = $$v
              },
              expression: "list"
            }
          })
        ],
        1
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-31e21116", module.exports)
  }
}

/***/ }),
/* 161 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(162)
/* template */
var __vue_template__ = __webpack_require__(163)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/sub-items/image.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-219ea593", Component.options)
  } else {
    hotAPI.reload("data-v-219ea593", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 162 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            id: '',
            image: new Image()

        };
    },
    methods: {
        show: function show() {
            this.$refs.cImage.reset();
            this.$refs.modal.show();
        },
        edit: function edit(image, id) {
            this.$refs.cImage.reset();
            this.id = id;
            this.image.upload_id = image.upload_id;
            this.image.meta.width = image.meta.width;
            this.image.meta.height = image.meta.height;
            this.image.meta.position = image.meta.position;
            this.image.meta.class = image.meta.class;
            this.$refs.modal.show();
        },
        save: function save() {
            this.image.meta.class = this.img_class;
            this.image.meta.style = this.style;

            if (this.id === '') {
                // Save
                this.$emit('saved', this.image);
            } else {
                // update
                this.$emit('updated', {
                    id: this.id,
                    updated_data: this.image
                });
                // Reset id
                this.id = '';
            }
            this.$refs.modal.dismiss();
        }
    }
});

/***/ }),
/* 163 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "modal",
    { ref: "modal", attrs: { extended_width: true } },
    [
      _c("template", { slot: "header" }, [_vm._v("Add Image")]),
      _vm._v(" "),
      _c(
        "template",
        { slot: "body" },
        [
          _c("c-image", {
            ref: "cImage",
            model: {
              value: _vm.image,
              callback: function($$v) {
                _vm.image = $$v
              },
              expression: "image"
            }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c("template", { slot: "footer" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-default",
            attrs: { type: "button", "data-dismiss": "modal" }
          },
          [_vm._v("Close")]
        ),
        _vm._v(" "),
        _c(
          "button",
          {
            staticClass: "btn btn-default",
            attrs: { type: "button" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.save($event)
              }
            }
          },
          [_vm._v("Save")]
        )
      ])
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-219ea593", module.exports)
  }
}

/***/ }),
/* 164 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(165)
/* template */
var __vue_template__ = __webpack_require__(169)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/sub-items/form.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-042e2b88", Component.options)
  } else {
    hotAPI.reload("data-v-042e2b88", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 165 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__form_src_datum_vue__ = __webpack_require__(166);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__form_src_datum_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__form_src_datum_vue__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['value'],
    components: {
        'datum': __WEBPACK_IMPORTED_MODULE_0__form_src_datum_vue___default.a
    },
    methods: {
        add_item_list: function add_item_list() {
            this.data.push(new OrderedList());
        },
        add_item: function add_item(item) {
            this.data.push(item);
        },
        remove_item: function remove_item(index) {
            this.data.splice(index, 1);
        },
        updated_item: function updated_item(item) {
            this.data[item.id] = item.updated_data;
            this.$forceUpdate();
        },
        edit_item: function edit_item(key, value) {
            switch (value.type) {
                case 'table':
                    this.$refs.table.edit(value, key);
                    break;
                case 'paragraph':
                    this.$refs.phar.edit(value, key);
                    break;
                case 'image':
                    this.$refs.image.edit(value, key);
                    break;
            }
        }
    },
    data: function data() {
        return {
            data: []
        };
    },
    mounted: function mounted() {
        this.data = this.value;
    },

    watch: {
        data: {
            deep: true,
            handler: function handler(data) {
                this.$emit('input', data);
            }
        },
        value: {
            deep: true,
            handler: function handler(value) {
                this.data = value;
            }
        }
    }
});

/***/ }),
/* 166 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(167)
/* template */
var __vue_template__ = __webpack_require__(168)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/sub-items/form_src/datum.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7ae7a47e", Component.options)
  } else {
    hotAPI.reload("data-v-7ae7a47e", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 167 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        id: [Number, String],
        value: {
            type: [Object, Array],
            required: true,
            default: function _default() {
                return new Datum();
            }
        }
    },
    data: function data() {
        return {
            datum: new Datum()
        };
    },
    mounted: function mounted() {
        this.$nextTick(function () {
            this.datum = this.value;
        });
    },

    methods: {
        item_remove: function item_remove() {
            if (confirm('Are you sure you want to remove the ' + this.datum.type + '?') === true) this.$emit('removed', this.id);
        },
        edit_item: function edit_item() {
            this.$emit('edit', this.id, this.datum);
        },
        hide: function hide() {
            if (this.datum.hidden === false) this.datum.hidden = true;else this.datum.hidden = false;
        }
    },
    watch: {
        datum: {
            deep: true,
            handler: function handler(datum) {
                this.$emit('input', datum);
            }
        },
        value: {
            deep: true,
            handler: function handler(value) {
                this.datum = value;
            }
        }
    }
});

/***/ }),
/* 168 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "box box-solid" }, [
    _c("div", { staticClass: "box-header" }, [
      _c(
        "div",
        { staticClass: "pull-right item-btn-action" },
        [
          _vm.datum.hidden === false && _vm.datum.type !== "list"
            ? [
                _c(
                  "a",
                  {
                    staticClass: "btn btn-xs btn-default text-yellow",
                    attrs: { href: "#", title: "Edit table" },
                    on: {
                      click: function($event) {
                        $event.preventDefault()
                        return _vm.edit_item($event)
                      }
                    }
                  },
                  [
                    _c("i", {
                      staticClass: "fa fa-pencil",
                      attrs: { "aria-hidden": "true" }
                    })
                  ]
                )
              ]
            : _vm._e(),
          _vm._v(" "),
          _c(
            "a",
            {
              staticClass: "btn btn-xs btn-default text-red",
              attrs: { href: "#", title: "Remove table" },
              on: {
                click: function($event) {
                  $event.preventDefault()
                  return _vm.item_remove($event)
                }
              }
            },
            [
              _c("i", {
                staticClass: "fa fa-trash",
                attrs: { "aria-hidden": "true" }
              })
            ]
          ),
          _vm._v(" "),
          _c(
            "a",
            {
              staticClass: "btn btn-xs btn-default text-blue citem-drag",
              attrs: { href: "#", title: "Move Order" },
              on: {
                click: function($event) {
                  $event.preventDefault()
                }
              }
            },
            [
              _c("i", {
                staticClass: "fa fa-arrows",
                attrs: { "aria-hidden": "true" }
              })
            ]
          ),
          _vm._v(" "),
          _c(
            "a",
            {
              staticClass: "btn btn-xs btn-default",
              attrs: { href: "#", title: "Hid/Show item" },
              on: {
                click: function($event) {
                  $event.preventDefault()
                  return _vm.hide($event)
                }
              }
            },
            [_c("i", { staticClass: "fa fa-minus" })]
          )
        ],
        2
      ),
      _vm._v(" "),
      _vm.datum.hidden === true
        ? _c("div", {
            staticClass: "pull-left",
            domProps: { innerHTML: _vm._s(_vm.datum.toShortString()) }
          })
        : _vm._e(),
      _vm._v(" "),
      _vm.datum.hidden === false
        ? _c("div", { staticClass: "pull-left" }, [
            _c("strong", [_vm._v(_vm._s(_vm.datum.type.toUpperCase()))])
          ])
        : _vm._e()
    ]),
    _vm._v(" "),
    _vm.datum.hidden === false
      ? _c(
          "div",
          { staticClass: "box-body" },
          [
            _vm.datum.type === "list"
              ? [
                  _c("list", {
                    model: {
                      value: _vm.datum,
                      callback: function($$v) {
                        _vm.datum = $$v
                      },
                      expression: "datum"
                    }
                  })
                ]
              : _c("div", {
                  domProps: { innerHTML: _vm._s(_vm.datum.toString()) }
                })
          ],
          2
        )
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-7ae7a47e", module.exports)
  }
}

/***/ }),
/* 169 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "content-items" },
    [
      _c("div", { staticClass: "form-group" }, [
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default",
            attrs: { href: "#", title: "Add table" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.$refs.table.show()
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-table",
              attrs: { "aria-hidden": "true" }
            })
          ]
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default",
            attrs: { href: "#", title: "Add list" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.add_item_list($event)
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-list",
              attrs: { "aria-hidden": "true" }
            })
          ]
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default",
            attrs: { href: "#", title: "Add paragraph" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.$refs.phar.show()
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-file-o",
              attrs: { "aria-hidden": "true" }
            })
          ]
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            staticClass: "btn btn-xs btn-default",
            attrs: { href: "#", title: "Add image" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.$refs.image.show()
              }
            }
          },
          [
            _c("i", {
              staticClass: "fa fa-picture-o",
              attrs: { "aria-hidden": "true" }
            })
          ]
        )
      ]),
      _vm._v(" "),
      _c(
        "draggable",
        {
          attrs: { group: "content-items" + _vm._uid, handle: ".citem-drag" },
          on: {
            start: function($event) {
              _vm.drag = true
            },
            end: function($event) {
              _vm.drag = false
            }
          },
          model: {
            value: _vm.data,
            callback: function($$v) {
              _vm.data = $$v
            },
            expression: "data"
          }
        },
        _vm._l(_vm.data, function(value, key) {
          return _c(
            "div",
            { key: key, staticClass: "item" },
            [
              _c("datum", {
                attrs: { id: key },
                on: { edit: _vm.edit_item, removed: _vm.remove_item },
                model: {
                  value: _vm.data[key],
                  callback: function($$v) {
                    _vm.$set(_vm.data, key, $$v)
                  },
                  expression: "data[key]"
                }
              })
            ],
            1
          )
        }),
        0
      ),
      _vm._v(" "),
      _c("phar", {
        ref: "phar",
        on: { saved: _vm.add_item, updated: _vm.updated_item }
      }),
      _vm._v(" "),
      _c("tbl", {
        ref: "table",
        on: { saved: _vm.add_item, updated: _vm.updated_item }
      }),
      _vm._v(" "),
      _c("com-image", {
        ref: "image",
        on: { saved: _vm.add_item, updated: _vm.updated_item }
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-042e2b88", module.exports)
  }
}

/***/ }),
/* 170 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(171)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(173)
/* template */
var __vue_template__ = __webpack_require__(177)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/main-item.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4c515afb", Component.options)
  } else {
    hotAPI.reload("data-v-4c515afb", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 171 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(172);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(4)("9d050f76", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-4c515afb\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./main-item.vue", function() {
     var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-4c515afb\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./main-item.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 172 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)(false);
// imports


// module
exports.push([module.i, "\n.main-item-input {\n    margin: 10px 0px 10px 0px;\n}\n.item-holder {\n    margin: 10px 0px 10px 0px;\n}\n\n", ""]);

// exports


/***/ }),
/* 173 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__content_preview_vue__ = __webpack_require__(174);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__content_preview_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__content_preview_vue__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
    components: {
        'content-preview': __WEBPACK_IMPORTED_MODULE_0__content_preview_vue___default.a
    },
    props: {
        value: {
            type: [Array, Object],
            default: function _default() {
                return new Content();
            }
        }
    },
    data: function data() {
        return {
            content: new Content(),
            change_content_title: false
        };
    },
    methods: {
        head_change: function head_change(value, index) {
            if (value === false) this.content.content_items[index].name = '';
        },
        remove_content_item: function remove_content_item(index) {
            if (confirm('Are you sure you want to remove the ' + this.content.content_items[index].name + '?')) this.content.removeContentItemIndex(index);
        }
    },
    mounted: function mounted() {
        this.$nextTick(function () {
            if (this.value !== null && this.value !== undefined) {
                this.content = this.value;
            }
        });
    },
    watch: {
        content: {
            deep: true,
            handler: function handler(val) {
                this.$emit('input', val);
            }
        },
        value: {
            deep: true,
            handler: function handler(val) {
                this.content = val;
            }
        }
    }
});

/***/ }),
/* 174 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(175)
/* template */
var __vue_template__ = __webpack_require__(176)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/content-form/content-preview.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-ee760170", Component.options)
  } else {
    hotAPI.reload("data-v-ee760170", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 175 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            id: '',
            content: new Content()
        };
    },
    methods: {
        show: function show(content) {
            this.content = new Content();
            this.content = content;
            this.$refs.modal.show();
        }

    }
});

/***/ }),
/* 176 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "modal",
    { ref: "modal", attrs: { extended_width: true } },
    [
      _c("template", { slot: "header" }, [_vm._v("Content Preview")]),
      _vm._v(" "),
      _c("template", { slot: "body" }, [
        _c("div", { domProps: { innerHTML: _vm._s(_vm.content.toString()) } })
      ]),
      _vm._v(" "),
      _c("template", { slot: "footer" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-default",
            attrs: { type: "button", "data-dismiss": "modal" }
          },
          [_vm._v("Close")]
        )
      ])
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-ee760170", module.exports)
  }
}

/***/ }),
/* 177 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "box box-solid" }, [
    _c(
      "div",
      { staticClass: "box-header with-border" },
      [
        _c("list-style", {
          attrs: { class_name: "pull-left" },
          model: {
            value: _vm.content.style,
            callback: function($$v) {
              _vm.$set(_vm.content, "style", $$v)
            },
            expression: "content.style"
          }
        }),
        _vm._v(" "),
        _c("div", { staticClass: " pull-right" }, [
          _c(
            "a",
            {
              staticClass: "btn btn-xs btn-default",
              attrs: { href: "#" },
              on: {
                click: function($event) {
                  $event.preventDefault()
                  return _vm.content.addContentItem()
                }
              }
            },
            [
              _c("i", {
                staticClass: "fa fa-plus-circle",
                attrs: { "aria-hidden": "true" }
              })
            ]
          ),
          _vm._v(" "),
          _c(
            "a",
            {
              staticClass: "btn btn-xs btn-default",
              attrs: { href: "#" },
              on: {
                click: function($event) {
                  $event.preventDefault()
                  return _vm.$refs.contentPreview.show(_vm.content)
                }
              }
            },
            [
              _c("i", {
                staticClass: "fa fa-eye",
                attrs: { "aria-hidden": "true" }
              })
            ]
          )
        ])
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "div",
      { staticClass: "box-body" },
      [
        _c(
          "draggable",
          {
            attrs: {
              group: "content" + _vm._uid,
              handle: ".control-drag-mover"
            },
            on: {
              start: function($event) {
                _vm.drag = true
              },
              end: function($event) {
                _vm.drag = false
              }
            },
            model: {
              value: _vm.content.content_items,
              callback: function($$v) {
                _vm.$set(_vm.content, "content_items", $$v)
              },
              expression: "content.content_items"
            }
          },
          _vm._l(_vm.content.content_items, function(value, key) {
            return _c("div", { key: key, staticClass: "box box-solid" }, [
              _c("div", { staticClass: "box-header with-border" }, [
                _c(
                  "a",
                  {
                    staticClass: "btn btn-xs btn-default text-yellow",
                    attrs: { href: "#", title: "Edit title" },
                    on: {
                      click: function($event) {
                        $event.preventDefault()
                        _vm.change_content_title == true
                          ? (_vm.change_content_title = false)
                          : (_vm.change_content_title = true)
                      }
                    }
                  },
                  [
                    _c("i", {
                      staticClass: "fa fa-pencil",
                      attrs: { "aria-hidden": "true" }
                    })
                  ]
                ),
                _vm._v(" "),
                _c("strong", {
                  domProps: {
                    textContent: _vm._s(_vm.content.content_items[key].name)
                  }
                }),
                _vm._v(" "),
                _c("div", { staticClass: "pull-right" }, [
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-xs btn-default",
                      attrs: { type: "button" },
                      on: {
                        click: function($event) {
                          _vm.content.content_items[key].box_hidden === true
                            ? (_vm.content.content_items[
                                key
                              ].box_hidden = false)
                            : (_vm.content.content_items[key].box_hidden = true)
                        }
                      }
                    },
                    [_c("i", { staticClass: "fa fa-minus" })]
                  ),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "btn btn-xs btn-default text-red",
                      attrs: { href: "#", title: "Remove item" },
                      on: {
                        click: function($event) {
                          $event.preventDefault()
                          return _vm.remove_content_item(key)
                        }
                      }
                    },
                    [
                      _c("i", {
                        staticClass: "fa fa-trash",
                        attrs: { "aria-hidden": "true" }
                      })
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass:
                        "btn btn-xs btn-default text-blue control-drag-mover",
                      attrs: { href: "#", title: "Move Order" },
                      on: {
                        click: function($event) {
                          $event.preventDefault()
                        }
                      }
                    },
                    [
                      _c("i", {
                        staticClass: "fa fa-arrows",
                        attrs: { "aria-hidden": "true" }
                      })
                    ]
                  )
                ])
              ]),
              _vm._v(" "),
              _vm.content.content_items[key].box_hidden === false
                ? _c(
                    "div",
                    { staticClass: "box-body" },
                    [
                      _vm.change_content_title === true
                        ? [
                            _c("div", { staticClass: "form-group" }, [
                              _c(
                                "div",
                                { staticClass: "input-group  main-item-input" },
                                [
                                  _c(
                                    "span",
                                    { staticClass: "input-group-addon" },
                                    [
                                      _c("input", {
                                        directives: [
                                          {
                                            name: "model",
                                            rawName: "v-model",
                                            value:
                                              _vm.content.content_items[key]
                                                .meta.with_header,
                                            expression:
                                              "content.content_items[key].meta.with_header"
                                          }
                                        ],
                                        attrs: {
                                          type: "checkbox",
                                          title: "With header"
                                        },
                                        domProps: {
                                          checked: Array.isArray(
                                            _vm.content.content_items[key].meta
                                              .with_header
                                          )
                                            ? _vm._i(
                                                _vm.content.content_items[key]
                                                  .meta.with_header,
                                                null
                                              ) > -1
                                            : _vm.content.content_items[key]
                                                .meta.with_header
                                        },
                                        on: {
                                          change: [
                                            function($event) {
                                              var $$a =
                                                  _vm.content.content_items[key]
                                                    .meta.with_header,
                                                $$el = $event.target,
                                                $$c = $$el.checked
                                                  ? true
                                                  : false
                                              if (Array.isArray($$a)) {
                                                var $$v = null,
                                                  $$i = _vm._i($$a, $$v)
                                                if ($$el.checked) {
                                                  $$i < 0 &&
                                                    _vm.$set(
                                                      _vm.content.content_items[
                                                        key
                                                      ].meta,
                                                      "with_header",
                                                      $$a.concat([$$v])
                                                    )
                                                } else {
                                                  $$i > -1 &&
                                                    _vm.$set(
                                                      _vm.content.content_items[
                                                        key
                                                      ].meta,
                                                      "with_header",
                                                      $$a
                                                        .slice(0, $$i)
                                                        .concat(
                                                          $$a.slice($$i + 1)
                                                        )
                                                    )
                                                }
                                              } else {
                                                _vm.$set(
                                                  _vm.content.content_items[key]
                                                    .meta,
                                                  "with_header",
                                                  $$c
                                                )
                                              }
                                            },
                                            function($event) {
                                              return _vm.head_change(
                                                _vm.content.content_items[key]
                                                  .meta.with_header,
                                                key
                                              )
                                            }
                                          ]
                                        }
                                      })
                                    ]
                                  ),
                                  _vm._v(" "),
                                  _c("input", {
                                    directives: [
                                      {
                                        name: "model",
                                        rawName: "v-model",
                                        value:
                                          _vm.content.content_items[key].name,
                                        expression:
                                          "content.content_items[key].name"
                                      }
                                    ],
                                    staticClass: "form-control",
                                    attrs: {
                                      type: "text",
                                      disabled:
                                        _vm.content.content_items[key].meta
                                          .with_header === false
                                    },
                                    domProps: {
                                      value: _vm.content.content_items[key].name
                                    },
                                    on: {
                                      input: function($event) {
                                        if ($event.target.composing) {
                                          return
                                        }
                                        _vm.$set(
                                          _vm.content.content_items[key],
                                          "name",
                                          $event.target.value
                                        )
                                      }
                                    }
                                  }),
                                  _vm._v(" "),
                                  _c(
                                    "span",
                                    { staticClass: "input-group-addon" },
                                    [
                                      _c(
                                        "a",
                                        {
                                          staticClass: "text-green",
                                          attrs: { href: "#" },
                                          on: {
                                            click: function($event) {
                                              $event.preventDefault()
                                              _vm.change_content_title = false
                                            }
                                          }
                                        },
                                        [
                                          _c("i", {
                                            staticClass: "fa fa-check",
                                            attrs: { "aria-hidden": "true" }
                                          })
                                        ]
                                      )
                                    ]
                                  )
                                ]
                              )
                            ])
                          ]
                        : _vm._e(),
                      _vm._v(" "),
                      _c("content-form", {
                        model: {
                          value: _vm.content.content_items[key].data,
                          callback: function($$v) {
                            _vm.$set(
                              _vm.content.content_items[key],
                              "data",
                              $$v
                            )
                          },
                          expression: "content.content_items[key].data"
                        }
                      })
                    ],
                    2
                  )
                : _vm._e()
            ])
          }),
          0
        ),
        _vm._v(" "),
        _c("content-preview", { ref: "contentPreview" })
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-4c515afb", module.exports)
  }
}

/***/ }),
/* 178 */,
/* 179 */,
/* 180 */,
/* 181 */,
/* 182 */,
/* 183 */,
/* 184 */,
/* 185 */,
/* 186 */,
/* 187 */,
/* 188 */,
/* 189 */,
/* 190 */,
/* 191 */,
/* 192 */,
/* 193 */,
/* 194 */,
/* 195 */,
/* 196 */,
/* 197 */,
/* 198 */,
/* 199 */,
/* 200 */,
/* 201 */,
/* 202 */,
/* 203 */,
/* 204 */,
/* 205 */,
/* 206 */,
/* 207 */,
/* 208 */,
/* 209 */,
/* 210 */,
/* 211 */,
/* 212 */,
/* 213 */,
/* 214 */,
/* 215 */,
/* 216 */,
/* 217 */,
/* 218 */,
/* 219 */,
/* 220 */,
/* 221 */,
/* 222 */,
/* 223 */,
/* 224 */,
/* 225 */,
/* 226 */,
/* 227 */,
/* 228 */,
/* 229 */,
/* 230 */,
/* 231 */,
/* 232 */,
/* 233 */,
/* 234 */,
/* 235 */,
/* 236 */,
/* 237 */,
/* 238 */,
/* 239 */,
/* 240 */,
/* 241 */,
/* 242 */,
/* 243 */,
/* 244 */,
/* 245 */,
/* 246 */,
/* 247 */,
/* 248 */,
/* 249 */,
/* 250 */,
/* 251 */,
/* 252 */,
/* 253 */,
/* 254 */,
/* 255 */,
/* 256 */,
/* 257 */,
/* 258 */,
/* 259 */,
/* 260 */,
/* 261 */,
/* 262 */,
/* 263 */,
/* 264 */,
/* 265 */,
/* 266 */,
/* 267 */,
/* 268 */,
/* 269 */,
/* 270 */,
/* 271 */,
/* 272 */,
/* 273 */,
/* 274 */,
/* 275 */,
/* 276 */,
/* 277 */,
/* 278 */,
/* 279 */,
/* 280 */,
/* 281 */,
/* 282 */,
/* 283 */,
/* 284 */,
/* 285 */,
/* 286 */,
/* 287 */,
/* 288 */,
/* 289 */,
/* 290 */,
/* 291 */,
/* 292 */,
/* 293 */,
/* 294 */,
/* 295 */,
/* 296 */,
/* 297 */,
/* 298 */,
/* 299 */,
/* 300 */,
/* 301 */,
/* 302 */,
/* 303 */,
/* 304 */,
/* 305 */,
/* 306 */,
/* 307 */,
/* 308 */,
/* 309 */,
/* 310 */,
/* 311 */,
/* 312 */,
/* 313 */,
/* 314 */,
/* 315 */,
/* 316 */,
/* 317 */,
/* 318 */,
/* 319 */,
/* 320 */,
/* 321 */,
/* 322 */,
/* 323 */,
/* 324 */,
/* 325 */,
/* 326 */,
/* 327 */,
/* 328 */,
/* 329 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(113);


/***/ })
/******/ ]);