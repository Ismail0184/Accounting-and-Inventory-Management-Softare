<?php

session_start();
ob_start();
require "../support/inc.all.php";
$title='Bank Payment Voucher Checked';
$now=time();
do_calander('#do_date_fr');
do_calander('#do_date_to');



auto_complete_from_db('accounts_ledger','ledger_name','concat(ledger_id)','1','ledgerids','','ledgerids');
auto_complete_from_db('accounts_ledger','ledger_name','concat(ledger_id)','1','ledgerid','','ledgerid');
?>



<SCRIPT language=JavaScript>
function reload(form)
{var val=form.price_type.options[form.price_type.options.selectedIndex].value;
self.location='sample_gift_list.php?oi_no=<?php echo $_GET[oi_no]; ?>&price_type=' + val ;}
</script>






				<form id="form2" name="form2" method="post" action="">	



<table width="100%" border="0" cellspacing="0" cellpadding="0">



  <tr>

    <td>      <table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>

			<td></td>

	    </tr>

		<tr><td>&nbsp;</td></tr>

        <tr>

          <td>


      <table width="98%" align="center" cellspacing="0" class="tabledesign">

      <tbody>

      <tr>


      <th style="text-align:center; width:15%">Ledger ID</th>
      <th>Narration</th>
      <th style="text-align:right; width:10%">DR Amount</th>
      <th style="text-align:right; width:10%">CR Amount</th>
      <th style="width:20%">Single Update?</th>
      

      </tr>

	 
 <?php
 
 

 
 
 $jv=next_journal_voucher_id();
 $receipt_no = next_invoice('payment_no','payment');
 $dotoday=date('Y-m-d');
$result=mysql_query("Select * from secondary_journal_bank where dr_amt>0 and status='' and jv_no='$_GET[vouchernoedit]' and tr_no='$_GET[jvidedit]'");
while($row=mysql_fetch_array($result)){

	
if(isset($_POST[update1])){
	
	mysql_query("update secondary_journal_bank set ledger_id='$_POST[ledgerid]',dr_amt='$_POST[dr_amt]' where status='' and jv_no='$_GET[vouchernoedit]' and tr_no='$_GET[jvidedit]' and id='$row[id]'");
	
	mysql_query("UPDATE secondary_payment set ledger_id='$_POST[ledgerid]',dr_amt='$_POST[dr_amt]' where payment_no='$_GET[jvidedit]' and dr_amt>0"); ?>
    <meta http-equiv="refresh" content="0;bank_voucher_checked_edit.php?jvidedit=<?=$_GET[jvidedit]?>&vouchernoedit=<?=$_GET[vouchernoedit]?>">
<?php } ?>

<tr style="background-color:#FFF">
<td align="center"><input type="text" name="ledgerid" id="ledgerid" value="<?=$row[ledger_id];?>" style="width:150px" /></a></td>
<td align="left" style=""><?=find_a_field('secondary_journal_bank','narration','cr_amt>0 and jv_no='.$row[jv_no]);?></td>
<td style="text-align:right"><input type="text" name="dr_amt" id="dr_amt" value="<?=$row[dr_amt];?>" style="width:80px" /></td>
<td style="text-align:right"></td>


<td style="vertical-align:middle; text-align:center">
<button type="submit" name="update1" id="update1" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION['user']['fname']; ?>, Are you sure you want to Confirm? (<?=$row[id];?>)");'><img src="th.jpg" style="width:25px;  height:25px"></button>
</td>

</tr>
<?php } ?>    
     
     
     
     
     
     
     
     
     
     
     
     
     


<?php
 ////////////////////////////////////////////////////////////////////////// credit entry start from here/////////////////////
 

$results=mysql_query("Select * from secondary_journal_bank where cr_amt>0 and status='' and jv_no='$_GET[vouchernoedit]' and tr_no='$_GET[jvidedit]'");
while($rows=mysql_fetch_array($results)){
	
if(isset($_POST[update2])){
	
	mysql_query("update secondary_journal_bank set ledger_id='$_POST[ledgerids]',cr_amt='$_POST[cr_amts]' where status='' and jv_no='$_GET[vouchernoedit]' and tr_no='$_GET[jvidedit]' and id='$rows[id]'");
	
	mysql_query("UPDATE secondary_payment set ledger_id='$_POST[ledgerids]',cr_amt='$_POST[cr_amts]' where payment_no='$_GET[jvidedit]' and cr_amt>0"); ?>
    <meta http-equiv="refresh" content="0;bank_voucher_checked_edit.php?jvidedit=<?=$_GET[jvidedit]?>&vouchernoedit=<?=$_GET[vouchernoedit]?>">
<?php } ?>
	


<tr style="background-color:#FFF">
<td align="center"><input type="text" name="ledgerids" id="ledgerids" value="<?=$rows[ledger_id];?>" style="width:150px" /></a></td>
<td align="left" style=""><?=find_a_field('secondary_journal_bank','narration','cr_amt>0 and jv_no='.$rows[jv_no]);?></td>
<td style="text-align:right"></td>
<td style="text-align:right"><input type="text" name="cr_amts" id="cr_amts" value="<?=$rows[cr_amt];?>" style="width:80px"  /></td>


<td style="vertical-align:middle; text-align:center">
<button type="submit" name="update2" id="update2" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION['user']['fname']; ?>, Are you sure you want to Confirm? (<?=$rows[id];?>)");'><img src="th.jpg" style="width:25px;  height:25px"></button>
</td>

</tr>
<?php } ?>   
</tbody></table> </td></tr>
<tr><td style="height:30px"></td></tr>
<?php if(isset($_POST[update_varify2])){ ?>
<meta http-equiv="refresh" content="0;bank_voucher_checked.php">
<?php } ?>
		<tr>
        <td colspan="5" style="text-align:center">
        <?php 
		$dramttotal=find_a_field('secondary_journal_bank','SUM(dr_amt)','dr_amt>0 and status="" and jv_no="'.$_GET[vouchernoedit].'" and tr_no='.$_GET[jvidedit]);
		
		$cramttotal=find_a_field('secondary_journal_bank','SUM(cr_amt)','cr_amt>0 and status="" and jv_no="'.$_GET[vouchernoedit].'" and tr_no='.$_GET[jvidedit]);
		if($dramttotal==$cramttotal){
		 ?>
        <input name="update_varify2" type="submit" id="update_varify2" class="btn2" value="Update Verified" style="height:25px" />
        <?php } else { echo '<h2>INVALID TRANSACTION!!</h2>';} ?>
        </td>
        </tr>
        <tr>

		<td>&nbsp;</td>

		</tr>

		<tr>

		<td>

		<div>

                    

		<table width="100%" border="0" cellspacing="0" cellpadding="0">		

		<tr>		

		<td>

		<div style="width:380px;"></div></td>

		</tr>

		</table>

	        </div>

		</td>

		</tr>

      </table></td></tr>

</table>


<!--h3 align="center" style="color:red">(<?php echo $i ?>) DO Created</h3-->

</form>

<?

$main_content=ob_get_contents();

ob_end_clean();

include ("../template/main_layout.php");

?>

