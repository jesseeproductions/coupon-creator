this.pngx=this.pngx||{},this.pngx.coupons=this.pngx.coupons||{},this.pngx.coupons.blocks=function(t){function e(r){if(n[r])return n[r].exports;var o=n[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,e),o.l=!0,o.exports}var n={};return e.m=t,e.c=n,e.d=function(t,n,r){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:r})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="",e(e.s=107)}([function(t,e){var n=t.exports={version:"2.6.9"};"number"==typeof __e&&(__e=n)},function(t,e){var n=t.exports="undefined"!=typeof window&&window.Math==Math?window:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")();"number"==typeof __g&&(__g=n)},function(t,e,n){t.exports=!n(10)(function(){return 7!=Object.defineProperty({},"a",{get:function(){return 7}}).a})},function(t,e,n){var r=n(11),o=n(34),u=n(18),i=Object.defineProperty;e.f=n(2)?Object.defineProperty:function(t,e,n){if(r(t),e=u(e,!0),r(n),o)try{return i(t,e,n)}catch(t){}if("get"in n||"set"in n)throw TypeError("Accessors not supported!");return"value"in n&&(t[e]=n.value),t}},function(t,e){var n={}.hasOwnProperty;t.exports=function(t,e){return n.call(t,e)}},function(t,e,n){var r=n(1),o=n(0),u=n(33),i=n(6),c=n(4),a=function(t,e,n){var s,l,f,p=t&a.F,y=t&a.G,d=t&a.S,v=t&a.P,h=t&a.B,m=t&a.W,b=y?o:o[e]||(o[e]={}),g=b.prototype,_=y?r:d?r[e]:(r[e]||{}).prototype;y&&(n=e);for(s in n)(l=!p&&_&&void 0!==_[s])&&c(b,s)||(f=l?_[s]:n[s],b[s]=y&&"function"!=typeof _[s]?n[s]:h&&l?u(f,r):m&&_[s]==f?function(t){var e=function(e,n,r){if(this instanceof t){switch(arguments.length){case 0:return new t;case 1:return new t(e);case 2:return new t(e,n)}return new t(e,n,r)}return t.apply(this,arguments)};return e.prototype=t.prototype,e}(f):v&&"function"==typeof f?u(Function.call,f):f,v&&((b.virtual||(b.virtual={}))[s]=f,t&a.R&&g&&!g[s]&&i(g,s,f)))};a.F=1,a.G=2,a.S=4,a.P=8,a.B=16,a.W=32,a.U=64,a.R=128,t.exports=a},function(t,e,n){var r=n(3),o=n(12);t.exports=n(2)?function(t,e,n){return r.f(t,e,o(1,n))}:function(t,e,n){return t[e]=n,t}},function(t,e){t.exports=function(t){return"object"==typeof t?null!==t:"function"==typeof t}},function(t,e,n){var r=n(49),o=n(19);t.exports=function(t){return r(o(t))}},function(t,e,n){var r=n(21)("wks"),o=n(14),u=n(1).Symbol,i="function"==typeof u;(t.exports=function(t){return r[t]||(r[t]=i&&u[t]||(i?u:o)("Symbol."+t))}).store=r},function(t,e){t.exports=function(t){try{return!!t()}catch(t){return!0}}},function(t,e,n){var r=n(7);t.exports=function(t){if(!r(t))throw TypeError(t+" is not an object!");return t}},function(t,e){t.exports=function(t,e){return{enumerable:!(1&t),configurable:!(2&t),writable:!(4&t),value:e}}},function(t,e){t.exports=!0},function(t,e){var n=0,r=Math.random();t.exports=function(t){return"Symbol(".concat(void 0===t?"":t,")_",(++n+r).toString(36))}},function(t,e,n){var r=n(19);t.exports=function(t){return Object(r(t))}},function(t,e,n){var r=n(44),o=n(25);t.exports=Object.keys||function(t){return r(t,o)}},function(t,e){e.f={}.propertyIsEnumerable},function(t,e,n){var r=n(7);t.exports=function(t,e){if(!r(t))return t;var n,o;if(e&&"function"==typeof(n=t.toString)&&!r(o=n.call(t)))return o;if("function"==typeof(n=t.valueOf)&&!r(o=n.call(t)))return o;if(!e&&"function"==typeof(n=t.toString)&&!r(o=n.call(t)))return o;throw TypeError("Can't convert object to primitive value")}},function(t,e){t.exports=function(t){if(void 0==t)throw TypeError("Can't call method on  "+t);return t}},function(t,e,n){var r=n(21)("keys"),o=n(14);t.exports=function(t){return r[t]||(r[t]=o(t))}},function(t,e,n){var r=n(0),o=n(1),u=o["__core-js_shared__"]||(o["__core-js_shared__"]={});(t.exports=function(t,e){return u[t]||(u[t]=void 0!==e?e:{})})("versions",[]).push({version:r.version,mode:n(13)?"pure":"global",copyright:"© 2019 Denis Pushkarev (zloirock.ru)"})},function(t,e){var n=Math.ceil,r=Math.floor;t.exports=function(t){return isNaN(t=+t)?0:(t>0?r:n)(t)}},function(t,e){t.exports={}},function(t,e,n){var r=n(11),o=n(72),u=n(25),i=n(20)("IE_PROTO"),c=function(){},a=function(){var t,e=n(35)("iframe"),r=u.length;for(e.style.display="none",n(76).appendChild(e),e.src="javascript:",t=e.contentWindow.document,t.open(),t.write("<script>document.F=Object<\/script>"),t.close(),a=t.F;r--;)delete a.prototype[u[r]];return a()};t.exports=Object.create||function(t,e){var n;return null!==t?(c.prototype=r(t),n=new c,c.prototype=null,n[i]=t):n=a(),void 0===e?n:o(n,e)}},function(t,e){t.exports="constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf".split(",")},function(t,e,n){var r=n(3).f,o=n(4),u=n(9)("toStringTag");t.exports=function(t,e,n){t&&!o(t=n?t:t.prototype,u)&&r(t,u,{configurable:!0,value:e})}},function(t,e,n){e.f=n(9)},function(t,e,n){var r=n(1),o=n(0),u=n(13),i=n(27),c=n(3).f;t.exports=function(t){var e=o.Symbol||(o.Symbol=u?{}:r.Symbol||{});"_"==t.charAt(0)||t in e||c(e,t,{value:i.f(t)})}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=n(51);n.d(e,"Loading",function(){return r.a});var o=n(59);n.d(e,"RESTSelect",function(){return o.a});var u=n(101);n.d(e,"Upgrade",function(){return u.a})},function(t,e){e.f=Object.getOwnPropertySymbols},function(t,e,n){"use strict";t.exports=n(52)},function(t,e,n){t.exports={default:n(61),__esModule:!0}},function(t,e,n){var r=n(63);t.exports=function(t,e,n){if(r(t),void 0===e)return t;switch(n){case 1:return function(n){return t.call(e,n)};case 2:return function(n,r){return t.call(e,n,r)};case 3:return function(n,r,o){return t.call(e,n,r,o)}}return function(){return t.apply(e,arguments)}}},function(t,e,n){t.exports=!n(2)&&!n(10)(function(){return 7!=Object.defineProperty(n(35)("div"),"a",{get:function(){return 7}}).a})},function(t,e,n){var r=n(7),o=n(1).document,u=r(o)&&r(o.createElement);t.exports=function(t){return u?o.createElement(t):{}}},function(t,e,n){t.exports={default:n(64),__esModule:!0}},function(t,e,n){var r=n(4),o=n(15),u=n(20)("IE_PROTO"),i=Object.prototype;t.exports=Object.getPrototypeOf||function(t){return t=o(t),r(t,u)?t[u]:"function"==typeof t.constructor&&t instanceof t.constructor?t.constructor.prototype:t instanceof Object?i:null}},function(t,e,n){"use strict";e.__esModule=!0,e.default=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},function(t,e,n){"use strict";e.__esModule=!0;var r=n(32),o=function(t){return t&&t.__esModule?t:{default:t}}(r);e.default=function(){function t(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),(0,o.default)(t,r.key,r)}}return function(e,n,r){return n&&t(e.prototype,n),r&&t(e,r),e}}()},function(t,e,n){"use strict";e.__esModule=!0;var r=n(41),o=function(t){return t&&t.__esModule?t:{default:t}}(r);e.default=function(t,e){if(!t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!==(void 0===e?"undefined":(0,o.default)(e))&&"function"!=typeof e?t:e}},function(t,e,n){"use strict";function r(t){return t&&t.__esModule?t:{default:t}}e.__esModule=!0;var o=n(67),u=r(o),i=n(81),c=r(i),a="function"==typeof c.default&&"symbol"==typeof u.default?function(t){return typeof t}:function(t){return t&&"function"==typeof c.default&&t.constructor===c.default&&t!==c.default.prototype?"symbol":typeof t};e.default="function"==typeof c.default&&"symbol"===a(u.default)?function(t){return void 0===t?"undefined":a(t)}:function(t){return t&&"function"==typeof c.default&&t.constructor===c.default&&t!==c.default.prototype?"symbol":void 0===t?"undefined":a(t)}},function(t,e,n){"use strict";var r=n(13),o=n(5),u=n(43),i=n(6),c=n(23),a=n(71),s=n(26),l=n(37),f=n(9)("iterator"),p=!([].keys&&"next"in[].keys()),y=function(){return this};t.exports=function(t,e,n,d,v,h,m){a(n,e,d);var b,g,_,w=function(t){if(!p&&t in j)return j[t];switch(t){case"keys":case"values":return function(){return new n(this,t)}}return function(){return new n(this,t)}},x=e+" Iterator",O="values"==v,S=!1,j=t.prototype,E=j[f]||j["@@iterator"]||v&&j[v],P=E||w(v),k=v?O?w("entries"):P:void 0,M="Array"==e?j.entries||E:E;if(M&&(_=l(M.call(new t)))!==Object.prototype&&_.next&&(s(_,x,!0),r||"function"==typeof _[f]||i(_,f,y)),O&&E&&"values"!==E.name&&(S=!0,P=function(){return E.call(this)}),r&&!m||!p&&!S&&j[f]||i(j,f,P),c[e]=P,c[x]=y,v)if(b={values:O?P:w("values"),keys:h?P:w("keys"),entries:k},m)for(g in b)g in j||u(j,g,b[g]);else o(o.P+o.F*(p||S),e,b);return b}},function(t,e,n){t.exports=n(6)},function(t,e,n){var r=n(4),o=n(8),u=n(73)(!1),i=n(20)("IE_PROTO");t.exports=function(t,e){var n,c=o(t),a=0,s=[];for(n in c)n!=i&&r(c,n)&&s.push(n);for(;e.length>a;)r(c,n=e[a++])&&(~u(s,n)||s.push(n));return s}},function(t,e){var n={}.toString;t.exports=function(t){return n.call(t).slice(8,-1)}},function(t,e,n){var r=n(44),o=n(25).concat("length","prototype");e.f=Object.getOwnPropertyNames||function(t){return r(t,o)}},function(t,e,n){var r=n(17),o=n(12),u=n(8),i=n(18),c=n(4),a=n(34),s=Object.getOwnPropertyDescriptor;e.f=n(2)?s:function(t,e){if(t=u(t),e=i(e,!0),a)try{return s(t,e)}catch(t){}if(c(t,e))return o(!r.f.call(t,e),t[e])}},function(t,e,n){"use strict";function r(t){return t&&t.__esModule?t:{default:t}}e.__esModule=!0;var o=n(91),u=r(o),i=n(95),c=r(i),a=n(41),s=r(a);e.default=function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+(void 0===e?"undefined":(0,s.default)(e)));t.prototype=(0,c.default)(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(u.default?(0,u.default)(t,e):t.__proto__=e)}},function(t,e,n){var r=n(45);t.exports=Object("z").propertyIsEnumerable(0)?Object:function(t){return"String"==r(t)?t.split(""):Object(t)}},function(t,e){!function(){t.exports=this.wp.i18n}()},function(t,e,n){"use strict";var r=n(31),o=(n.n(r),n(56)),u=n.n(o),i=n(57),c=(n.n(i),n(58));n.n(c);e.a=function(t){var e=t.className;return wp.element.createElement("span",{className:u()(["pngx-editor__spinner-container",e])},wp.element.createElement(i.Spinner,null))}},function(t,e,n){"use strict";function r(t){for(var e=arguments.length-1,n="Minified React error #"+t+"; visit http://facebook.github.io/react/docs/error-decoder.html?invariant="+t,r=0;r<e;r++)n+="&args[]="+encodeURIComponent(arguments[r+1]);throw e=Error(n+" for the full message or use the non-minified dev environment for full errors and additional helpful warnings."),e.name="Invariant Violation",e.framesToPop=1,e}function o(t,e,n){this.props=t,this.context=e,this.refs=b,this.updater=n||T}function u(){}function i(t,e,n){this.props=t,this.context=e,this.refs=b,this.updater=n||T}function c(t,e,n){var r=void 0,o={},u=null,i=null;if(null!=e)for(r in void 0!==e.ref&&(i=e.ref),void 0!==e.key&&(u=""+e.key),e)R.call(e,r)&&!I.hasOwnProperty(r)&&(o[r]=e[r]);var c=arguments.length-2;if(1===c)o.children=n;else if(1<c){for(var a=Array(c),s=0;s<c;s++)a[s]=arguments[s+2];o.children=a}if(t&&t.defaultProps)for(r in c=t.defaultProps)void 0===o[r]&&(o[r]=c[r]);return{$$typeof:w,type:t,key:u,ref:i,props:o,_owner:A.current}}function a(t){return"object"==typeof t&&null!==t&&t.$$typeof===w}function s(t){var e={"=":"=0",":":"=2"};return"$"+(""+t).replace(/[=:]/g,function(t){return e[t]})}function l(t,e,n,r){if(L.length){var o=L.pop();return o.result=t,o.keyPrefix=e,o.func=n,o.context=r,o.count=0,o}return{result:t,keyPrefix:e,func:n,context:r,count:0}}function f(t){t.result=null,t.keyPrefix=null,t.func=null,t.context=null,t.count=0,10>L.length&&L.push(t)}function p(t,e,n,o){var u=typeof t;"undefined"!==u&&"boolean"!==u||(t=null);var i=!1;if(null===t)i=!0;else switch(u){case"string":case"number":i=!0;break;case"object":switch(t.$$typeof){case w:case x:i=!0}}if(i)return n(o,t,""===e?"."+y(t,0):e),1;if(i=0,e=""===e?".":e+":",Array.isArray(t))for(var c=0;c<t.length;c++){u=t[c];var a=e+y(u,c);i+=p(u,a,n,o)}else if(null===t||void 0===t?a=null:(a=M&&t[M]||t["@@iterator"],a="function"==typeof a?a:null),"function"==typeof a)for(t=a.call(t),c=0;!(u=t.next()).done;)u=u.value,a=e+y(u,c++),i+=p(u,a,n,o);else"object"===u&&(n=""+t,r("31","[object Object]"===n?"object with keys {"+Object.keys(t).join(", ")+"}":n,""));return i}function y(t,e){return"object"==typeof t&&null!==t&&null!=t.key?s(t.key):e.toString(36)}function d(t,e){t.func.call(t.context,e,t.count++)}function v(t,e,n){var r=t.result,o=t.keyPrefix;t=t.func.call(t.context,e,t.count++),Array.isArray(t)?h(t,r,n,g.thatReturnsArgument):null!=t&&(a(t)&&(e=o+(!t.key||e&&e.key===t.key?"":(""+t.key).replace(N,"$&/")+"/")+n,t={$$typeof:w,type:t.type,key:e,ref:t.ref,props:t.props,_owner:t._owner}),r.push(t))}function h(t,e,n,r,o){var u="";null!=n&&(u=(""+n).replace(N,"$&/")+"/"),e=l(e,u,r,o),null==t||p(t,"",v,e),f(e)}/** @license React v16.3.0
 * react.production.min.js
 *
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */
var m=n(53),b=n(54),g=n(55),_="function"==typeof Symbol&&Symbol.for,w=_?Symbol.for("react.element"):60103,x=_?Symbol.for("react.portal"):60106,O=_?Symbol.for("react.fragment"):60107,S=_?Symbol.for("react.strict_mode"):60108,j=_?Symbol.for("react.provider"):60109,E=_?Symbol.for("react.context"):60110,P=_?Symbol.for("react.async_mode"):60111,k=_?Symbol.for("react.forward_ref"):60112,M="function"==typeof Symbol&&Symbol.iterator,T={isMounted:function(){return!1},enqueueForceUpdate:function(){},enqueueReplaceState:function(){},enqueueSetState:function(){}};o.prototype.isReactComponent={},o.prototype.setState=function(t,e){"object"!=typeof t&&"function"!=typeof t&&null!=t&&r("85"),this.updater.enqueueSetState(this,t,e,"setState")},o.prototype.forceUpdate=function(t){this.updater.enqueueForceUpdate(this,t,"forceUpdate")},u.prototype=o.prototype;var C=i.prototype=new u;C.constructor=i,m(C,o.prototype),C.isPureReactComponent=!0;var A={current:null},R=Object.prototype.hasOwnProperty,I={key:!0,ref:!0,__self:!0,__source:!0},N=/\/+/g,L=[],F={Children:{map:function(t,e,n){if(null==t)return t;var r=[];return h(t,r,null,e,n),r},forEach:function(t,e,n){if(null==t)return t;e=l(null,null,e,n),null==t||p(t,"",d,e),f(e)},count:function(t){return null==t?0:p(t,"",g.thatReturnsNull,null)},toArray:function(t){var e=[];return h(t,e,null,g.thatReturnsArgument),e},only:function(t){return a(t)||r("143"),t}},createRef:function(){return{current:null}},Component:o,PureComponent:i,createContext:function(t,e){return void 0===e&&(e=null),t={$$typeof:E,_calculateChangedBits:e,_defaultValue:t,_currentValue:t,_changedBits:0,Provider:null,Consumer:null},t.Provider={$$typeof:j,context:t},t.Consumer=t},forwardRef:function(t){return{$$typeof:k,render:t}},Fragment:O,StrictMode:S,unstable_AsyncMode:P,createElement:c,cloneElement:function(t,e,n){var r=void 0,o=m({},t.props),u=t.key,i=t.ref,c=t._owner;if(null!=e){void 0!==e.ref&&(i=e.ref,c=A.current),void 0!==e.key&&(u=""+e.key);var a=void 0;t.type&&t.type.defaultProps&&(a=t.type.defaultProps);for(r in e)R.call(e,r)&&!I.hasOwnProperty(r)&&(o[r]=void 0===e[r]&&void 0!==a?a[r]:e[r])}if(1===(r=arguments.length-2))o.children=n;else if(1<r){a=Array(r);for(var s=0;s<r;s++)a[s]=arguments[s+2];o.children=a}return{$$typeof:w,type:t.type,key:u,ref:i,props:o,_owner:c}},createFactory:function(t){var e=c.bind(null,t);return e.type=t,e},isValidElement:a,version:"16.3.0",__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED:{ReactCurrentOwner:A,assign:m}},D=Object.freeze({default:F}),$=D&&F||D;t.exports=$.default?$.default:$},function(t,e,n){"use strict";function r(t){if(null===t||void 0===t)throw new TypeError("Object.assign cannot be called with null or undefined");return Object(t)}/*
object-assign
(c) Sindre Sorhus
@license MIT
*/
var o=Object.getOwnPropertySymbols,u=Object.prototype.hasOwnProperty,i=Object.prototype.propertyIsEnumerable;t.exports=function(){try{if(!Object.assign)return!1;var t=new String("abc");if(t[5]="de","5"===Object.getOwnPropertyNames(t)[0])return!1;for(var e={},n=0;n<10;n++)e["_"+String.fromCharCode(n)]=n;if("0123456789"!==Object.getOwnPropertyNames(e).map(function(t){return e[t]}).join(""))return!1;var r={};return"abcdefghijklmnopqrst".split("").forEach(function(t){r[t]=t}),"abcdefghijklmnopqrst"===Object.keys(Object.assign({},r)).join("")}catch(t){return!1}}()?Object.assign:function(t,e){for(var n,c,a=r(t),s=1;s<arguments.length;s++){n=Object(arguments[s]);for(var l in n)u.call(n,l)&&(a[l]=n[l]);if(o){c=o(n);for(var f=0;f<c.length;f++)i.call(n,c[f])&&(a[c[f]]=n[c[f]])}}return a}},function(t,e,n){"use strict";var r={};t.exports=r},function(t,e,n){"use strict";function r(t){return function(){return t}}var o=function(){};o.thatReturns=r,o.thatReturnsFalse=r(!1),o.thatReturnsTrue=r(!0),o.thatReturnsNull=r(null),o.thatReturnsThis=function(){return this},o.thatReturnsArgument=function(t){return t},t.exports=o},function(t,e,n){var r,o;/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
!function(){"use strict";function n(){for(var t=[],e=0;e<arguments.length;e++){var r=arguments[e];if(r){var o=typeof r;if("string"===o||"number"===o)t.push(r);else if(Array.isArray(r)&&r.length){var i=n.apply(null,r);i&&t.push(i)}else if("object"===o)for(var c in r)u.call(r,c)&&r[c]&&t.push(c)}}return t.join(" ")}var u={}.hasOwnProperty;void 0!==t&&t.exports?(n.default=n,t.exports=n):(r=[],void 0!==(o=function(){return n}.apply(e,r))&&(t.exports=o))}()},function(t,e){!function(){t.exports=this.wp.components}()},function(t,e){},function(t,e,n){"use strict";var r=n(60),o=n.n(r),u=n(36),i=n.n(u),c=n(38),a=n.n(c),s=n(39),l=n.n(s),f=n(40),p=n.n(f),y=n(48),d=n.n(y),v=n(29),h=n(98),m=n.n(h),b=(wp.i18n.__,wp.components.SelectControl),g=wp.element.Component,_=wp,w=_.apiFetch,x=function(t){function e(){a()(this,e);var t=p()(this,(e.__proto__||i()(e)).apply(this,arguments));return t.state={defaultOptions:[],loadedItems:[],loaded:!1,options:[],isMultiple:!1,isTaxonomy:!1},t.componentDidMount=function(){var e=t.props,n=e.defaultOptions,r=e.isMultiple,o=e.isTaxonomy;t.setState({defaultOptions:n,isMultiple:r,isTaxonomy:o}),t.getOptions()},t.getOptions=function(){var e=t.props.fetchPath;return w({path:e}).then(function(e){e&&(t.setState({loadedItems:e,loaded:!0}),t.setOptions())})},t.setOptions=function(){var e=t.state.defaultOptions;void 0===e&&(e=[]),t.state.isTaxonomy?t.state.loadedItems.forEach(function(t){e.push({value:t.slug,label:t.name})}):t.state.loadedItems.forEach(function(t){e.push({value:t.id,label:t.title.rendered})}),t.setState({options:e})},t.onChangeSelect=function(e){var n=t.props,r=n.attributesID,u=n.setAttributes;if(e.includes("none"))return void u(o()({},r,t.state.isMultiple?[]:""));u(o()({},r,e))},t}return d()(e,t),l()(e,[{key:"render",value:function(){var t=this.props,e=t.currentId,n=t.label,r=t.noItems,o=t.slug,u=wp.element.createElement(v.Loading,{key:"pngx-select-loading-{slug}",className:"pngx-editor__spinner--item"}),i="";if(0===this.state.loadedItems.length&&this.state.loaded){u=r}else if(this.state.loadedItems.length>0&&this.state.loaded){u="";var c=this.state.isMultiple&&!Array.isArray(e)?[]:e;i=wp.element.createElement(b,{multiple:this.state.isMultiple,key:o,value:c,label:n,options:this.state.options,onChange:this.onChangeSelect})}return[i,u]}}]),e}(g);x.propTypes={attributesID:m.a.string.isRequired,currentId:m.a.oneOfType([m.a.array,m.a.string]),defaultOptions:m.a.array,fetchPath:m.a.string.isRequired,isMultiple:m.a.bool,isTaxonomy:m.a.bool,label:m.a.string.isRequired,noItems:m.a.string.isRequired,slug:m.a.string.isRequired},e.a=x},function(t,e,n){"use strict";e.__esModule=!0;var r=n(32),o=function(t){return t&&t.__esModule?t:{default:t}}(r);e.default=function(t,e,n){return e in t?(0,o.default)(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}},function(t,e,n){n(62);var r=n(0).Object;t.exports=function(t,e,n){return r.defineProperty(t,e,n)}},function(t,e,n){var r=n(5);r(r.S+r.F*!n(2),"Object",{defineProperty:n(3).f})},function(t,e){t.exports=function(t){if("function"!=typeof t)throw TypeError(t+" is not a function!");return t}},function(t,e,n){n(65),t.exports=n(0).Object.getPrototypeOf},function(t,e,n){var r=n(15),o=n(37);n(66)("getPrototypeOf",function(){return function(t){return o(r(t))}})},function(t,e,n){var r=n(5),o=n(0),u=n(10);t.exports=function(t,e){var n=(o.Object||{})[t]||Object[t],i={};i[t]=e(n),r(r.S+r.F*u(function(){n(1)}),"Object",i)}},function(t,e,n){t.exports={default:n(68),__esModule:!0}},function(t,e,n){n(69),n(77),t.exports=n(27).f("iterator")},function(t,e,n){"use strict";var r=n(70)(!0);n(42)(String,"String",function(t){this._t=String(t),this._i=0},function(){var t,e=this._t,n=this._i;return n>=e.length?{value:void 0,done:!0}:(t=r(e,n),this._i+=t.length,{value:t,done:!1})})},function(t,e,n){var r=n(22),o=n(19);t.exports=function(t){return function(e,n){var u,i,c=String(o(e)),a=r(n),s=c.length;return a<0||a>=s?t?"":void 0:(u=c.charCodeAt(a),u<55296||u>56319||a+1===s||(i=c.charCodeAt(a+1))<56320||i>57343?t?c.charAt(a):u:t?c.slice(a,a+2):i-56320+(u-55296<<10)+65536)}}},function(t,e,n){"use strict";var r=n(24),o=n(12),u=n(26),i={};n(6)(i,n(9)("iterator"),function(){return this}),t.exports=function(t,e,n){t.prototype=r(i,{next:o(1,n)}),u(t,e+" Iterator")}},function(t,e,n){var r=n(3),o=n(11),u=n(16);t.exports=n(2)?Object.defineProperties:function(t,e){o(t);for(var n,i=u(e),c=i.length,a=0;c>a;)r.f(t,n=i[a++],e[n]);return t}},function(t,e,n){var r=n(8),o=n(74),u=n(75);t.exports=function(t){return function(e,n,i){var c,a=r(e),s=o(a.length),l=u(i,s);if(t&&n!=n){for(;s>l;)if((c=a[l++])!=c)return!0}else for(;s>l;l++)if((t||l in a)&&a[l]===n)return t||l||0;return!t&&-1}}},function(t,e,n){var r=n(22),o=Math.min;t.exports=function(t){return t>0?o(r(t),9007199254740991):0}},function(t,e,n){var r=n(22),o=Math.max,u=Math.min;t.exports=function(t,e){return t=r(t),t<0?o(t+e,0):u(t,e)}},function(t,e,n){var r=n(1).document;t.exports=r&&r.documentElement},function(t,e,n){n(78);for(var r=n(1),o=n(6),u=n(23),i=n(9)("toStringTag"),c="CSSRuleList,CSSStyleDeclaration,CSSValueList,ClientRectList,DOMRectList,DOMStringList,DOMTokenList,DataTransferItemList,FileList,HTMLAllCollection,HTMLCollection,HTMLFormElement,HTMLSelectElement,MediaList,MimeTypeArray,NamedNodeMap,NodeList,PaintRequestList,Plugin,PluginArray,SVGLengthList,SVGNumberList,SVGPathSegList,SVGPointList,SVGStringList,SVGTransformList,SourceBufferList,StyleSheetList,TextTrackCueList,TextTrackList,TouchList".split(","),a=0;a<c.length;a++){var s=c[a],l=r[s],f=l&&l.prototype;f&&!f[i]&&o(f,i,s),u[s]=u.Array}},function(t,e,n){"use strict";var r=n(79),o=n(80),u=n(23),i=n(8);t.exports=n(42)(Array,"Array",function(t,e){this._t=i(t),this._i=0,this._k=e},function(){var t=this._t,e=this._k,n=this._i++;return!t||n>=t.length?(this._t=void 0,o(1)):"keys"==e?o(0,n):"values"==e?o(0,t[n]):o(0,[n,t[n]])},"values"),u.Arguments=u.Array,r("keys"),r("values"),r("entries")},function(t,e){t.exports=function(){}},function(t,e){t.exports=function(t,e){return{value:e,done:!!t}}},function(t,e,n){t.exports={default:n(82),__esModule:!0}},function(t,e,n){n(83),n(88),n(89),n(90),t.exports=n(0).Symbol},function(t,e,n){"use strict";var r=n(1),o=n(4),u=n(2),i=n(5),c=n(43),a=n(84).KEY,s=n(10),l=n(21),f=n(26),p=n(14),y=n(9),d=n(27),v=n(28),h=n(85),m=n(86),b=n(11),g=n(7),_=n(15),w=n(8),x=n(18),O=n(12),S=n(24),j=n(87),E=n(47),P=n(30),k=n(3),M=n(16),T=E.f,C=k.f,A=j.f,R=r.Symbol,I=r.JSON,N=I&&I.stringify,L=y("_hidden"),F=y("toPrimitive"),D={}.propertyIsEnumerable,$=l("symbol-registry"),q=l("symbols"),V=l("op-symbols"),U=Object.prototype,B="function"==typeof R&&!!P.f,W=r.QObject,G=!W||!W.prototype||!W.prototype.findChild,H=u&&s(function(){return 7!=S(C({},"a",{get:function(){return C(this,"a",{value:7}).a}})).a})?function(t,e,n){var r=T(U,e);r&&delete U[e],C(t,e,n),r&&t!==U&&C(U,e,r)}:C,z=function(t){var e=q[t]=S(R.prototype);return e._k=t,e},J=B&&"symbol"==typeof R.iterator?function(t){return"symbol"==typeof t}:function(t){return t instanceof R},Y=function(t,e,n){return t===U&&Y(V,e,n),b(t),e=x(e,!0),b(n),o(q,e)?(n.enumerable?(o(t,L)&&t[L][e]&&(t[L][e]=!1),n=S(n,{enumerable:O(0,!1)})):(o(t,L)||C(t,L,O(1,{})),t[L][e]=!0),H(t,e,n)):C(t,e,n)},K=function(t,e){b(t);for(var n,r=h(e=w(e)),o=0,u=r.length;u>o;)Y(t,n=r[o++],e[n]);return t},Q=function(t,e){return void 0===e?S(t):K(S(t),e)},X=function(t){var e=D.call(this,t=x(t,!0));return!(this===U&&o(q,t)&&!o(V,t))&&(!(e||!o(this,t)||!o(q,t)||o(this,L)&&this[L][t])||e)},Z=function(t,e){if(t=w(t),e=x(e,!0),t!==U||!o(q,e)||o(V,e)){var n=T(t,e);return!n||!o(q,e)||o(t,L)&&t[L][e]||(n.enumerable=!0),n}},tt=function(t){for(var e,n=A(w(t)),r=[],u=0;n.length>u;)o(q,e=n[u++])||e==L||e==a||r.push(e);return r},et=function(t){for(var e,n=t===U,r=A(n?V:w(t)),u=[],i=0;r.length>i;)!o(q,e=r[i++])||n&&!o(U,e)||u.push(q[e]);return u};B||(R=function(){if(this instanceof R)throw TypeError("Symbol is not a constructor!");var t=p(arguments.length>0?arguments[0]:void 0),e=function(n){this===U&&e.call(V,n),o(this,L)&&o(this[L],t)&&(this[L][t]=!1),H(this,t,O(1,n))};return u&&G&&H(U,t,{configurable:!0,set:e}),z(t)},c(R.prototype,"toString",function(){return this._k}),E.f=Z,k.f=Y,n(46).f=j.f=tt,n(17).f=X,P.f=et,u&&!n(13)&&c(U,"propertyIsEnumerable",X,!0),d.f=function(t){return z(y(t))}),i(i.G+i.W+i.F*!B,{Symbol:R});for(var nt="hasInstance,isConcatSpreadable,iterator,match,replace,search,species,split,toPrimitive,toStringTag,unscopables".split(","),rt=0;nt.length>rt;)y(nt[rt++]);for(var ot=M(y.store),ut=0;ot.length>ut;)v(ot[ut++]);i(i.S+i.F*!B,"Symbol",{for:function(t){return o($,t+="")?$[t]:$[t]=R(t)},keyFor:function(t){if(!J(t))throw TypeError(t+" is not a symbol!");for(var e in $)if($[e]===t)return e},useSetter:function(){G=!0},useSimple:function(){G=!1}}),i(i.S+i.F*!B,"Object",{create:Q,defineProperty:Y,defineProperties:K,getOwnPropertyDescriptor:Z,getOwnPropertyNames:tt,getOwnPropertySymbols:et});var it=s(function(){P.f(1)});i(i.S+i.F*it,"Object",{getOwnPropertySymbols:function(t){return P.f(_(t))}}),I&&i(i.S+i.F*(!B||s(function(){var t=R();return"[null]"!=N([t])||"{}"!=N({a:t})||"{}"!=N(Object(t))})),"JSON",{stringify:function(t){for(var e,n,r=[t],o=1;arguments.length>o;)r.push(arguments[o++]);if(n=e=r[1],(g(e)||void 0!==t)&&!J(t))return m(e)||(e=function(t,e){if("function"==typeof n&&(e=n.call(this,t,e)),!J(e))return e}),r[1]=e,N.apply(I,r)}}),R.prototype[F]||n(6)(R.prototype,F,R.prototype.valueOf),f(R,"Symbol"),f(Math,"Math",!0),f(r.JSON,"JSON",!0)},function(t,e,n){var r=n(14)("meta"),o=n(7),u=n(4),i=n(3).f,c=0,a=Object.isExtensible||function(){return!0},s=!n(10)(function(){return a(Object.preventExtensions({}))}),l=function(t){i(t,r,{value:{i:"O"+ ++c,w:{}}})},f=function(t,e){if(!o(t))return"symbol"==typeof t?t:("string"==typeof t?"S":"P")+t;if(!u(t,r)){if(!a(t))return"F";if(!e)return"E";l(t)}return t[r].i},p=function(t,e){if(!u(t,r)){if(!a(t))return!0;if(!e)return!1;l(t)}return t[r].w},y=function(t){return s&&d.NEED&&a(t)&&!u(t,r)&&l(t),t},d=t.exports={KEY:r,NEED:!1,fastKey:f,getWeak:p,onFreeze:y}},function(t,e,n){var r=n(16),o=n(30),u=n(17);t.exports=function(t){var e=r(t),n=o.f;if(n)for(var i,c=n(t),a=u.f,s=0;c.length>s;)a.call(t,i=c[s++])&&e.push(i);return e}},function(t,e,n){var r=n(45);t.exports=Array.isArray||function(t){return"Array"==r(t)}},function(t,e,n){var r=n(8),o=n(46).f,u={}.toString,i="object"==typeof window&&window&&Object.getOwnPropertyNames?Object.getOwnPropertyNames(window):[],c=function(t){try{return o(t)}catch(t){return i.slice()}};t.exports.f=function(t){return i&&"[object Window]"==u.call(t)?c(t):o(r(t))}},function(t,e){},function(t,e,n){n(28)("asyncIterator")},function(t,e,n){n(28)("observable")},function(t,e,n){t.exports={default:n(92),__esModule:!0}},function(t,e,n){n(93),t.exports=n(0).Object.setPrototypeOf},function(t,e,n){var r=n(5);r(r.S,"Object",{setPrototypeOf:n(94).set})},function(t,e,n){var r=n(7),o=n(11),u=function(t,e){if(o(t),!r(e)&&null!==e)throw TypeError(e+": can't set as prototype!")};t.exports={set:Object.setPrototypeOf||("__proto__"in{}?function(t,e,r){try{r=n(33)(Function.call,n(47).f(Object.prototype,"__proto__").set,2),r(t,[]),e=!(t instanceof Array)}catch(t){e=!0}return function(t,n){return u(t,n),e?t.__proto__=n:r(t,n),t}}({},!1):void 0),check:u}},function(t,e,n){t.exports={default:n(96),__esModule:!0}},function(t,e,n){n(97);var r=n(0).Object;t.exports=function(t,e){return r.create(t,e)}},function(t,e,n){var r=n(5);r(r.S,"Object",{create:n(24)})},function(t,e,n){t.exports=n(99)()},function(t,e,n){"use strict";function r(){}function o(){}var u=n(100);o.resetWarningCache=r,t.exports=function(){function t(t,e,n,r,o,i){if(i!==u){var c=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw c.name="Invariant Violation",c}}function e(){return t}t.isRequired=t;var n={array:t,bool:t,func:t,number:t,object:t,string:t,symbol:t,any:t,arrayOf:e,element:t,elementType:t,instanceOf:e,node:t,objectOf:e,oneOf:e,oneOfType:e,shape:e,exact:e,checkPropTypes:o,resetWarningCache:r};return n.PropTypes=n,n}},function(t,e,n){"use strict";t.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},function(t,e,n){"use strict";var r=n(31),o=(n.n(r),n(50)),u=(n.n(o),n(102)),i=(n.n(u),function(){return wp.element.createElement("div",{className:"pngx-message pngx-warning"},wp.element.createElement("p",{className:"pngx-message-upgrade-text"},wp.element.createElement("a",{href:"http://cctor.link/Abqoi",className:"pngx-message-upgrade-link",target:"_blank",rel:"noopener noreferrer"},Object(o.__)("Upgrade to Pro","coupon-creator")),Object(o.__)(" for the expanded Coupon Loop with Filter Bar! ","coupon-creator")))});e.a=i},function(t,e){},function(t,e,n){"use strict";n.d(e,"a",function(){return r});var r=function(){return window.pngx_blocks_editor_settings.constants||{}}},function(t,e,n){"use strict";e.__esModule=!0;var r=n(110),o=function(t){return t&&t.__esModule?t:{default:t}}(r);e.default=o.default||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(t[r]=n[r])}return t}},function(t,e,n){"use strict";var r=n(104),o=n.n(r),u=n(36),i=n.n(u),c=n(38),a=n.n(c),s=n(39),l=n.n(s),f=n(40),p=n.n(f),y=n(48),d=n.n(y),v=n(29),h=wp.i18n.__,m=wp.element.Component,b=function(t){function e(){return a()(this,e),p()(this,(e.__proto__||i()(e)).apply(this,arguments))}return d()(e,t),l()(e,[{key:"render",value:function(){var t=this.props,e=t.attributes.couponid,n=(t.className,t.setAttributes);return wp.element.createElement("div",{className:"pngx-message cctor-core-choose-coupon"},wp.element.createElement(v.RESTSelect,o()({setAttributes:n},{attributesID:"couponid",currentId:e,defaultOptions:[{value:"0",label:h("Select a Coupon","coupon-creator")},{value:"loop",label:h("All Coupons","coupon-creator")}],fetchPath:"/wp/v2/cctor_coupon?per_page=100",label:h("Select a Coupon","coupon-creator"),noItems:h("No coupons found. Please create some first.","coupon-creator"),slug:"coupon-item-select"})))}}]),e}(m);e.a=b},,function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=n(108),o=(n.n(r),n(109)),u=n(117),i=(n.n(u),[o.a]);i.forEach(function(t){var e="pngx/"+t.id;Object(r.registerBlockType)(e,t)}),e.default=i},function(t,e){!function(){t.exports=this.wp.blocks}()},function(t,e,n){"use strict";var r=n(104),o=n.n(r),u=n(50),i=(n.n(u),n(114)),c=n(115),a=n(105),s=n(116),l=wp.components.ServerSideRender;e.a={id:"coupon",title:Object(u.__)("Coupon Creator","coupon-creator"),description:Object(u.__)("Display a single or group of coupons.","coupon-creator"),icon:s.a,category:"widgets",keywords:["coupon","coupon creator","deal"],attributes:i.a,edit:function(t){var e=t.attributes.couponid,n=t.attributes,r=t.className,u=t.setAttributes,i=void 0;return i="0"===e?wp.element.createElement(a.a,o()({setAttributes:u},t)):wp.element.createElement(l,{key:"coupon-render",block:"pngx/coupon",attributes:n}),[wp.element.createElement(c.a,o()({key:"coupon-inspector"},o()({setAttributes:u},t))),wp.element.createElement("div",{key:"coupon-base",className:r+" pngx-clearfix"},i)]},save:function(){return null}}},function(t,e,n){t.exports={default:n(111),__esModule:!0}},function(t,e,n){n(112),t.exports=n(0).Object.assign},function(t,e,n){var r=n(5);r(r.S+r.F,"Object",{assign:n(113)})},function(t,e,n){"use strict";var r=n(2),o=n(16),u=n(30),i=n(17),c=n(15),a=n(49),s=Object.assign;t.exports=!s||n(10)(function(){var t={},e={},n=Symbol(),r="abcdefghijklmnopqrst";return t[n]=7,r.split("").forEach(function(t){e[t]=t}),7!=s({},t)[n]||Object.keys(s({},e)).join("")!=r})?function(t,e){for(var n=c(t),s=arguments.length,l=1,f=u.f,p=i.f;s>l;)for(var y,d=a(arguments[l++]),v=f?o(d).concat(f(d)):o(d),h=v.length,m=0;h>m;)y=v[m++],r&&!p.call(d,y)||(n[y]=d[y]);return n}:s},function(t,e,n){"use strict";var r={couponid:{type:"string",default:"0"},category:{type:"array",items:{type:"string"}},coupon_align:{type:"string"},couponorderby:{type:"string"}};e.a=r},function(t,e,n){"use strict";var r=n(104),o=n.n(r),u=n(36),i=n.n(u),c=n(38),a=n.n(c),s=n(39),l=n.n(s),f=n(40),p=n.n(f),y=n(48),d=n.n(y),v=n(103),h=n(29),m=n(105),b=wp.i18n.__,g=wp.element.Component,_=wp.editor.InspectorControls,w=wp.components,x=w.PanelRow,O=w.SelectControl,S=function(t){function e(){return a()(this,e),p()(this,(e.__proto__||i()(e)).apply(this,arguments))}return d()(e,t),l()(e,[{key:"render",value:function(){var t=this.props,e=t.attributes,n=e.couponid,r=e.category,u=e.coupon_align,i=e.couponorderby,c=t.setAttributes,a="true"===Object(v.a)().hide_upgrade,s="";"loop"===n&&(s=wp.element.createElement(x,null,wp.element.createElement(h.RESTSelect,o()({setAttributes:c},{attributesID:"category",currentId:r,defaultOptions:[{value:"none",label:b("No Categories","coupon-creator")}],fetchPath:"/wp/v2/cctor_coupon_category?per_page=100&hide_empty=true",isMultiple:!0,isTaxonomy:!0,label:b("Select a Category","coupon-creator"),noItems:b("No category terms found. Please create some first.","coupon-creator"),slug:"coupon-category-item-select"}))));var l="";return"loop"===n&&(l=wp.element.createElement(x,null,wp.element.createElement(O,{key:"coupon-order-select",label:b("Select how to order the coupons","coupon-creator"),value:i||"",options:[{value:"date",label:b("Date (default)","coupon-creator")},{value:"none",label:b("None","coupon-creator")},{value:"ID",label:b("ID","coupon-creator")},{value:"author",label:b("Author","coupon-creator")},{value:"title",label:b("Coupon Post Title","coupon-creator")},{value:"name",label:b("Slug Name","coupon-creator")},{value:"modified",label:b("Last Modified","coupon-creator")},{value:"rand",label:b("Random","coupon-creator")}],onChange:function(t){return c({couponorderby:t})}}))),wp.element.createElement(_,null,wp.element.createElement(x,{className:"coupon-chooser"},wp.element.createElement(m.a,o()({setAttributes:c},this.props))),s,wp.element.createElement(x,{className:"coupon-align-select"},wp.element.createElement(O,{key:"coupon-align-select",label:b("Select How to Align the Coupon(s)","coupon-creator"),value:u||"",options:[{value:"cctor_alignnone",label:b("None","coupon-creator")},{value:"cctor_alignleft",label:b("Align Left","coupon-creator")},{value:"cctor_alignright",label:b("Align Right","coupon-creator")},{value:"cctor_aligncenter",label:b("Align Center","coupon-creator")}],onChange:function(t){return c({coupon_align:t})}})),l,!a&&wp.element.createElement(h.Upgrade,null))}}]),e}(g);e.a=S},function(t,e,n){"use strict";var r=wp.element.createElement("svg",{version:"1.1",id:"coupon-creator-icon",xmlns:"http://www.w3.org/2000/svg",x:"0px",y:"0px",viewBox:"0 0 24 24",width:"24px",height:"24px"},wp.element.createElement("g",{fill:"black"},wp.element.createElement("g",null,wp.element.createElement("g",null,wp.element.createElement("polygon",{points:"21,21 17,21 17,19 19,19 19,17 21,17 \t\t\t"}),wp.element.createElement("rect",{x:"9.3",y:"19",width:"5.3",height:"2"}),wp.element.createElement("polygon",{points:"7,21 3,21 3,17 5,17 5,19 7,19 \t\t\t"}),wp.element.createElement("rect",{x:"3",y:"9.3",width:"2",height:"5.3"}),wp.element.createElement("polygon",{points:"5,7 3,7 3,3 7,3 7,5 5,5 \t\t\t"}),wp.element.createElement("rect",{x:"9.3",y:"3",width:"5.3",height:"2"}),wp.element.createElement("polygon",{points:"21,7 19,7 19,5 17,5 17,3 21,3 \t\t\t"}),wp.element.createElement("rect",{x:"19",y:"9.3",width:"2",height:"5.3"}))),wp.element.createElement("g",null,wp.element.createElement("path",{fill:"black",d:"M10.1,15.6c-0.4-0.5-0.6-1.2-0.6-2.1v-3c0-0.9,0.2-1.6,0.6-2.1c0.4-0.5,1-0.7,1.9-0.7c0.9,0,1.5,0.2,1.9,0.6 c0.4,0.4,0.5,1,0.5,1.8v0.7h-1.8V10c0-0.3,0-0.6-0.1-0.7C12.5,9.1,12.3,9,12.1,9c-0.3,0-0.4,0.1-0.5,0.3c-0.1,0.2-0.1,0.4-0.1,0.8 V14c0,0.3,0,0.6,0.1,0.8c0.1,0.2,0.3,0.3,0.5,0.3c0.3,0,0.4-0.1,0.5-0.3c0.1-0.2,0.1-0.4,0.1-0.8v-0.9h1.8v0.7 c0,0.8-0.2,1.4-0.5,1.8c-0.4,0.4-1,0.7-1.9,0.7C11.1,16.3,10.5,16.1,10.1,15.6z"}))));e.a=r},function(t,e){}]);