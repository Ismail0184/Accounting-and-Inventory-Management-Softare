<?php
require_once 'support_file.php';
$title='Accounts Report';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ATCl: Chat of Account</title>
<style>
	$off { color:#FF0;}
	a { text-decoration:none;}
</style>
</head>

<body>
<?php


if (isset($_GET[id])){
	echo "<label align='center'><a href='coaprint.php?id=$_GET[id]&off=$_GET[off]&flag=ok'>Please confirm off/on</a></label>";
	if ($_GET[flag]=='ok'){
		if ($_GET[off]==0)
		{$update=mysql_query("update coa_accounthead set off=1 where ID=".$_GET[id]);
			header("location: coaprint.php");}
		else if ($_GET[off]==1){$update=mysql_query("update coa_accounthead set off=0 where ID=".$_GET[id]); 
			header("location: coaprint.php");}
		}
	
	}

echo "<h2 align='center'>$_SESSION[company]</h2>";
echo "<h5 align='center'>Chart of Account</h5>";
?>
<table  width="95%"   align="center"   style=" border-collapse:collapse;  ">
        <tr style="border-bottom:1px solid red; border-top:1px solid red">
        	<th width="10%" style="text-align:center">Code</th><th width="50%" style="text-align:left">Account head</th><th width="20%" style="text-align:left">Main Group</th><th width="20%" style="text-align:left">Report Level</th>
            
            <?php 
        $sSQL=mysql_query("select * from ledger_group where 1 order by group_id ");
		while ($srows=mysql_fetch_array($sSQL)){

		echo "<tr style=''><td style='text-align:left; margin-top:10px; padding-left:5px' colspan='3'><strong><u>$srows[group_name]</u></strong></td>";

        $ssSQL=mysql_query("select * from accounts_ledger where  ledger_group_id='$srows[group_id]' order by ledger_id ");
		while ($ssrows=mysql_fetch_array($ssSQL)){

		echo "<tr ><td style='text-align:left; padding-left:30px; font-size:13px;' colspan='3'><strong>$ssrows[ledger_name]</strong></td>";
		$strSQL = "SELECT * FROM coa_accounthead where Company='$_SESSION[company]' 
		and Subsubsidiary='$ssrows[Subsubsidiary]' ORDER BY Subsubsidiary,AccountHead ";
        
		 $result = mysql_query($strSQL);
         $row = mysql_num_rows($result);
         for ($i=1; $i<$row+1; $i++) {
         $r = mysql_fetch_array($result);
		 if ($r[off]==0) {$id="<!--a href='coaprint.php?id=$r[ID]&off=$r[off]'>on</a-->";$fld=$r[AccountHead] ;} 
		 else {$id="<a href='coaprint.php?id=$r[ID]&off=$r[off]'>off</a>"; $fld="<span style='color:red'>$r[AccountHead]</span>";}
		  
        echo "<tr ><td style='text-align:center; padding-left:50px; font-size:13px;'>$i</td><td style='font-size:13px;'>$fld</td><td style='font-size:13px;'>$r[MainGroup]</td><td style='font-size:13px;'>$r[ReportLevel]</td><td>$id</td>";
        	}
		}
	}
	
	
			?>
        </tr>
        
        <tr style="border-top:1px solid red"><td colspan="2" style="margin-top:20px; font-size:13px; font-weight:bold; padding-left:50px;">Printed By:<?php echo $_SESSION[username]; ?></td><td colspan=2" style="margin-top:20px; font-size:13px; font-weight:bold">Printed Date:<?php echo $_SESSION[datet]; ?></td></tr>
	</table>

</body>
</html>