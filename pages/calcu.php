<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title>untitled</title>
<style type="text/css">

input {width: 40px;}

</style>
<script type="text/javascript" language="javascript">

function autocalc(oText)
{
	if (isNaN(oText.value)) //filter input
	{
		alert('Numbers only!');
		oText.value = '';
	}
	var field, val, oForm = oText.form, total = a = 0;
	for (a; a < arguments.length; ++a) //loop through text elements
	{
		field = arguments[a];
		val = parseFloat(field.value); //get value
		if (!isNaN(val)) //number?
		{
			total += val; //accumulate
		}
	}
	oForm.total.value = total; //out
}
		

</script>
</head>
<body onload="document.forms[0].reset()">
<form style="width:260px;margin:100px auto;border:2px black dashed;">
<table cellspacing="8">
<tr>
<!-- pass field reference ('this') and other field references -->
<td>value 1___<input name="t1" type="text" onkeyup="return autocalc(this,t2,t3)" tabindex="1"></td>
<td rowspan="3"><strong>total_____</strong><input name="total" type="text" readonly="readonly" value="0"  tabindex="-1"></td>
</tr><tr>
<td>value 2___<input name="t2" type="text" onkeyup="return autocalc(this,t1,t3)" tabindex="2"></td>
</tr><tr>
<td>value 3___<input name="t3" type="text" onkeyup="return autocalc(this,t1,t2)" tabindex="3"></td>
</tr>
</table>
</form>
</body>
</html>