<?php
require_once 'support_file.php';
$title='Report';


$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));


$pfrom_date=date('Y-m-d' , strtotime($_POST[pf_date]));
$pto_date=date('Y-m-d' , strtotime($_POST[pt_date]));

$ledger_id=$_REQUEST["ledger_id"];

list( $day,$month,$year1) = split('[/.-]', $_REQUEST['datefrom']);
$dofdate= '20'.$year1.'-'.$month.'-'.$day;

list($dayt,$montht,$yeart) = split('[/.-]', $_REQUEST['dateto']);
$dotdate= '20'.$yeart.'-'.$montht.'-'.$dayt;

$warehouseid=$_POST[warehouse_id];
$_SESSION['company_name']=getSVALUE('company','company_name','where company_id="'.$_SESSION['companyid'].'"');

$sectionid=$_SESSION['sectionid'];
$companyid=$_SESSION['companyid'];

if($sectionid=='400000'){
    $sec_com_connection=' and 1';
} else {
    $sec_com_connection=" and j.section_id='".$sectionid."' and j.company_id='".$companyid."'";
}

?>


<style>

    td{border: solid 1px #999;padding:2px}
</style>





    <h2 align="center"><?=$_SESSION['company_name'];?></h2>

    <h4 align="center" style="margin-top:-13px">Transaction Statement</h4>
    <h4 align="center" style="margin-top:-13px">Ledger Name: Cost of Goods Sales (COGS)</h4>
<?php if($_POST[cc_code]){ ?>
    <h5 align="center" style="margin-top:-13px; ">Cost Center: <?=getSVALUE('cost_center','center_name','where id="'.$_GET['cost_center_id'].'"');?> (<?=$_REQUEST['cc_code'];?>)</h5>
<?php } ?>
    <h5 align="center" style="margin-top:-13px">Period From <?=$_GET[f_date]?> to <?=$_GET[t_date]?></h5>


    <table align="center"  style="width:80%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:80%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>


        <tbody>
      <tr><td colspan="6" style="font-size:20px; color:red; height:40px; border: solid 1px #999; padding:2px">COGS</td></tr>
      <tr>

      <th width="4%" style="border: solid 1px #999; padding:2px; font-size:13px;">SL</th>
      <th style="border: solid 1px #999; padding:2px">Ladger ID</th>
      <th style="border: solid 1px #999; padding:2px">Accounts Head</th>
      <th style="border: solid 1px #999; padding:2px">Dr. Amount</th>
      
      

      </tr>

	 
 <?php
				   $led=mysql_query("Select * from accounts_ledger WHERE  ledger_group_id='4001' ");
	while($ledrow=mysql_fetch_array($led)){
				   
				    $cogs=getSVALUE('journal','SUM(dr_amt-cr_amt)',"where ledger_id='$ledrow[ledger_id]' and jvdate between '$_GET[f_date]' and '$_GET[t_date]' and  section_id='".$sectionid."' and company_id='".$companyid."'");
        //
        //(find_a_field_sql("select SUM(dr_amt-cr_amt) from journal
 //where ledger_id='$ledrow[ledger_id]' and jv_date between '$_GET[f_date]' and '$tdate' and  section_id='".$sectionid."' and company_id='".$companyid."'"));
 
 //echo $ledrow[ledger_id].'<Br>';
	//}
				   $totalcogs=$totalcogs+$cogs;
				   
				  // echo number_format($totalcogs,2);
				   ?>
 
<tr style="background-color:#FFF;font-size:13px;">
<td align="center" style="width:2%"><?php echo $i; ?></td>
<td align="left"><?php echo  $ledgername = getSVALUE('accounts_ledger','ledger_id','where ledger_id='.$ledrow[ledger_id]); ?></td>
<td align="left" style=""><?php echo  $ledgername = getSVALUE('accounts_ledger','ledger_name','where ledger_id='.$ledrow[ledger_id]); ?></td>

<td align="right"><?php if ($cogs>0) echo number_format($cogs,2); else echo '-' ?></td>

</tr>
<?php 

$cogstotal=$cogstotal+$cogs;
} ?>    
     
  <tr style="background-color:#FFF; font-size:13px; font-weight:bold; text-align:right"><td colspan="3" style=" color:#F00;">Total COGS</td>
  <td align="right" style=" color:#F00;"><?php echo number_format($cogstotal,2); ?></td></tr>   
     
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 <tr><td colspan="6" style="font-size:20px; color:red; height:40px">Cost Center: <?php echo $cname = getSVALUE('cost_center','center_name','where id='.$_GET[cost_center_id]); ?></td></tr>
      <tr>

      <th width="4%">SL</th>
      <th>Ladger ID</th>
      <th>Accounts Head</th>
      <th style="text-align:center">Dr. Amount</th>
      
      

      </tr>

	 
 <?php
$result=mysql_query("Select distinct ledger_id from journal where cc_code='$_GET[cost_center_id]' and jvdate between '$_GET[f_date]' and '$_GET[t_date]' and section_id='".$sectionid."' and company_id='".$companyid."'");
while($row=mysql_fetch_array($result)){
	
	$warehouse_info=mysql_query("select * from accounts_ledger where ledger_id='$row[ledger_id]'");
	$wrow=mysql_fetch_array($warehouse_info);
	
	$ccode=$_GET[cost_center_id];
    $SandDErowCurrentAmount = getSVALUE('journal','SUM(dr_amt-cr_amt)','where cc_code="'.$ccode.'" and jvdate between "'.$_GET[f_date].'" and "'.$_GET[t_date].'" and section_id="'.$sectionid.'" and company_id="'.$companyid.'" and ledger_id='.$row[ledger_id]);

	 $SandDErowCurrentAmounttotal=$SandDErowCurrentAmounttotal+$SandDErowCurrentAmount;
	
	$username=mysql_query("Select * from user_activity_management where user_id='$row[entry_by]'");
	$userrow=mysql_fetch_array($username);
	 $i=$i+1;
 ?>
 
<tr style="background-color:#FFF; font-size: 13px">
<td align="center" style="width:2%"><?php echo $i; ?></td>
<td align="left"><?php echo $wrow[ledger_id]; ?></td>
<td align="left" style=""><?php echo $wrow[ledger_name]; ?></td>

<td align="right"><?php if ($SandDErowCurrentAmount>0) echo number_format($SandDErowCurrentAmount,2)
    ; else echo '-' ?></td>
<!---td align="right"><?php if ($cramount>0) echo number_format($cramount,2); else echo '-' ?></td>
<td align="right" style="width:20%"><?php echo number_format(($receiableamount),2) ; ?></td--->
</tr>
<?php } ?>    
     
  <tr style="background-color:#FFF; font-size:12px; font-weight:bold; text-align:right"><td colspan="3" style=" color:#F00;">Total Factory Expenses</td>
  <td align="right" style=" color:#F00;"><?php echo number_format($SandDErowCurrentAmounttotal,2); ?></td></tr>
  
  <tr style="background-color:#FFF; font-size:14px; font-weight:bold; text-align:right"><td colspan="3" style=" color:#F00;">Cost of Goods Sales (COGS)</td>
  <td align="right" style=" color:#F00;"><?php echo number_format($SandDErowCurrentAmounttotal+$cogstotal,2); ?></td></tr>
 
 
 
 
 
 
 
 
 
  

  </tbody></table>		  

