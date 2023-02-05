<script type="text/javascript">

	function calculateTotal() {

		var totalAmt = document.addem.total.value;
		totalR = eval(totalAmt - document.addem.tb1.value);

		document.getElementById('update').innerHTML = totalR;
	}

</script>


<SCRIPT language=JavaScript>

    function doAlert(form)
    {
        var val=form.qtys.value;
        var val2=form.stockbalance.value;

        if (Number(val)>Number(val2)){
            alert('oops!! Exceed Stock Balance!! Thanks');

            form.qtys.value='';
        }
        form.qtys.focus();
    }</script>

<form name="addem" action="" id="addem" >
	<span id="update">100</span>
	<p><input type="text" name="tb1" onkeyup="calculateTotal()"/>first textbox</p>

	<input type="hidden" name="total" value="200" />


    <input type="text" id="stockbalance" style="width:80px; height:37px; color:red; font-weight:bold; text-align:center" readonly required="required" value="100" name="stockbalance" class="form-control col-md-7 col-xs-12" >

    <input type="text" autocomplete="off" id="qtys" style="width:80px; height:37px; font-weight:bold; text-align:center"  required="required" onkeyup="doAlert(this.form);"  name="qtys" placeholder="qtys"   class="qtys" >

</form>



<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }
</script>
</head>
<body>
<button onclick="OpenPopupCenter('http://www.google.com', 'TEST!?', 800, 600);">click on me</button>
</body>
</html>