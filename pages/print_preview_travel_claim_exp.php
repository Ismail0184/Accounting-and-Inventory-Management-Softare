<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once 'support_file.php';

$unique='trvClaim_id';
$unique_details='trvClaim_id';
$table="travel_application_claim_master";
$table_details="travel_application_claim_details";
$$unique 		= $_REQUEST[$unique];





$datas=find_all_field('travel_application_claim_master','','trvClaim_id='.$$unique);
$row=find_all_field('personnel_basic_info','','PBI_ID='.$datas->PBI_ID);
$full_desg = find_a_field('designation','DESG_DESC','DESG_ID='.$row->PBI_DESIGNATION);
$full_dept = find_a_field('department','DEPT_DESC','DEPT_ID='.$row->PBI_DEPARTMENT);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Print Preview :.</title>
<link href="../css/invoice.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">

function hide()

{

    document.getElementById("pr").style.display="none";

}

</script>
<style type="text/css">

<!--

.style1 {color: #000000}

-->

</style>
</head>
<body style="font-family:Tahoma, Geneva, sans-serif">
<br />
<br />
<br />
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><div class="header">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="60%" border="0" align="center" cellpadding="5" cellspacing="0">
                            <tr>
                              <td style="text-align:center; font-size:18px; font-weight:bold;"><?=$_SESSION['company_name']?></td>
                            </tr>
                            <tr>
                              <td style="text-align:center; font-size:16px; font-weight:bold;"><span class="style1">TRAVEL EXPENSES CLAIM</span></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td>
            <table style="margin-top:50px" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <td  align="left" valign="middle"><b>Employee Name :</b> </td>
                        <td ><?php echo $row->PBI_NAME;?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle"><b>Designation :</b></td>
                        <td><?=$full_desg?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="top"><b>Requisition Date :</b></td>
                        <td><?=$datas->application_date?></td>
                      </tr>
                      
                    </table></td>
                  <td width="40%"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <td width="48%" align="right" valign="middle"><div align="left"><b>Requisition No : </b></div></td>
                        <td width="52%"><?=$datas->trvClaim_id?></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle"><div align="left"><b>Department : </b></div></td>
                        <td><?=$full_dept?></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle"><div align="left"><b>Purpose of Visit:</b></div></td>
                        <td><?=$datas->travel_purpose?></td>
                      </tr>
                      
                    </table></td>
                </tr>
              </table></td>
          </tr>
        </table>
      </div></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  
  
  
  
  
  
  
  
  
 <tr>
    <td><div id="pr">
        <div align="left">
          <form id="form1" name="form1" method="post" action="">
            <table width="50%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><input name="button" type="button" onclick="hide();window.print();" value="Print" /></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
      
<?
$g_s_date=date('Y-01-01');
$g_e_date=date('Y-12-31');
$res = 'select d.trvDate_from, d.trvDate_to, d.departure_date,d.return_date,d.travel_purpose from item_info i, travel_application_claim_master d where d.trvClaim_id= '.$$unique;
$query = mysql_query($res);
$data=mysql_fetch_object($query);

?>
      
      
      <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="2" style="font-size:11px; border-collapse:collapse">
        <tr>
          <td align="center" bgcolor="#FFFFFF"><strong>From</strong></td> 
          <td align="center" bgcolor="#FFFFFF"><strong>To</strong></td>          
        </tr>       
        <tr>
          <td align="center" valign="top"><? echo $data->trvDate_from ?></td>
          <td align="center" valign="top"><? echo $data->trvDate_to ?></td>
        </tr>
        
      </table></td>
  </tr> 
  
  <tr><td style="height:20px"></td></tr> 
  
  
  <tr><td>
  <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="2" style="font-size:11px; border-collapse:collapse">
        <tr>
          <td align="center" bgcolor="#FFFFFF"><strong>Departure Date</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Return Date</strong></td>        
        </tr>       
        <tr>
          <td align="center" valign="top"><? echo $data->departure_date?></td>
          <td align="center" valign="top"><? echo $data->return_date ?></td>
        </tr>
        
      </table></td>
  </tr> 
  
  
  
  
  
  
  
  
 <tr><td style="height:40px"></td></tr> 
  
  
  
  
  
  
  
  
  
  <tr>
    <td>
      <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="2" style="font-size:11px; border-collapse:collapse">
        <tr>
          <td rowspan="2" align="center" bgcolor="#FFFFFF"><strong>SL</strong></td>
          <td rowspan="2" align="center" bgcolor="#FFFFFF" style=" width:10%"><strong>Date</strong></td>
          <td rowspan="2" align="center" bgcolor="#FFFFFF" style="15%"><strong>Description</strong></td>
          <td rowspan="2" align="center" bgcolor="#FFFFFF" style="15%"><strong>Transport Mode</strong></td>
          <td rowspan="2" align="center" bgcolor="#FFFFFF" style="15%"><strong>Transport Exp.</strong></td>
          <td rowspan="2" align="center" bgcolor="#FFFFFF" style="15%"><strong>Hotel Fare</strong></td>
          
          
          <td colspan="3" align="center" bgcolor="#FFFFFF"><strong>MEALS</strong></td>
          <td rowspan="2" align="center" bgcolor="#FFFFFF"><strong>DA</strong></td>
          <td rowspan="2" align="center" bgcolor="#FFFFFF"><strong>Total</strong></td>
        </tr>
        
        
        <tr>
         <td align="center" bgcolor="#FFFFFF" style="width:5%"><strong>B</strong></td>
          <td align="center" bgcolor="#FFFFFF" style="width:5%"><strong>L</strong></td>
          <td align="center" bgcolor="#FFFFFF" style="width:5%"><strong>D</strong></td>
        </tr>
        
        <? 
$g_s_date=date('Y-01-01');

$g_e_date=date('Y-12-31');



$res = 'select * from '.$table_details.' where '.$unique.'= '.$$unique;
$query = mysql_query($res);
while($data=mysql_fetch_object($query)){

?>
        <tr>
          <td align="center" valign="top"><?=++$i?></td>
          <td align="center" valign="top"><? echo $data->travel_date ?></td>
          <td align="center" valign="top"><? echo $data->travel_from ?> - <? echo $data->travel_to ?> <?php if($data->lodging_expense!=='') echo $data->lodging_expense ; ?></td>          
          <td align="center" valign="top"><? echo $data->mode_of_transport ?></td>
          <td align="center" valign="top"><? echo $data->transport_fair ?></td>
          <td align="center" valign="top"><? echo $data->lodging_fair ?></td>
          <td align="right" valign="top" style="width:5%"><?php if($data->breakfast>0) echo $data->breakfast; else echo '' ?></td>
		  <td align="right" valign="top" style="width:5%"> <?php if($data->lunch>0) echo $data->lunch; else echo '' ?></td>
		  <td align="right" valign="top" style="width:5%"> <?php if($data->dinner>0) echo $data->dinner; else echo '' ?> </td>
          <td align="center" valign="top"> <?php echo $da; ?> </td>
          <td align="right" valign="top"> <?php echo number_format(($suttotal=$data->transport_fair+$data->lodging_fair+$data->breakfast+$data->lunch+$data->dinner),2) ?> </td>
		   
        </tr>
        <?
		$suttotals=$suttotals+$suttotal;
		 }?>
        <tr style="font-weight:bold; font-size:11px"><td colspan="10" style="text-align:right">Sub Total:</td><td style="text-align:right"><?php echo number_format($suttotals,2); ?></td></tr>
        
        
        <tr style="border:none"><td style="height:15px; border:none"></td></tr>
        
        <tr style="font-weight:bold; font-size:11px"><td colspan="10" style="text-align:right">Travel Advance (In BDT)</td><td style="text-align:right"><?=number_format(($datas->advance_amount),2) ?></td></tr>
        
        <?php
		$ddto=$datas->advance_amount-$suttotals;
		if($ddto<0){
		 ?>
        <tr style="font-weight:bold; font-size:11px"><td colspan="10" style="text-align:right">Total Receivable (In BDT)</td><td style="text-align:right"><?php echo substr(number_format($ddto,2),1); ?></td></tr>
        <?php } else { ?>
        
        <tr style="font-weight:bold; font-size:11px"><td colspan="10" style="text-align:right">Total Payable (In BDT)</td><td style="text-align:right"><?php echo number_format($ddto,2); ?></td></tr>
        <?php } ?>
      </table></td>
  </tr>
  
  <tr><td style="height:5px"></td></tr>
  
  <tr style="font-size:11px"><td><strong>Certification</strong>: I clarify that all expenses incurred are related to Business & Genuin.</td></tr>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        
        
        <tr>
          <td colspan="2" align="center">
            
            
            
            
             <table style="font-size:11px; margin-top:50px" width="100%" border="0" cellspacing="0" cellpadding="0">
              
              
              <tr>
              
                <td align="center" ><span class="oe_form_group_cell oe_form_group_cell_label">
                  <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->PBI_ID)?>
                </span><br /><font style="font-size:11px; padding-bottom::-50px">(<?=$datas->entry_at;?>)</font></td>
                
                <td align="center" ><span class="oe_form_group_cell oe_form_group_cell_label">
                  <?=find_a_field('users','fname','user_id='.$datas->checked_by)?>
                </span><br /> <font style="font-size:11px; padding-bottom::-50px">(<?=$datas->checked_at;?>)</font></td>
                
                <td align="center" ><span class="oe_form_group_cell oe_form_group_cell_label">
                  <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->approved_by)?>
                </span><br /> <font style="font-size:11px; padding-bottom::-50px">(<?=$datas->approved_date;?>)</font></td>
                
                
                <td align="center" ><span class="oe_form_group_cell oe_form_group_cell_label">
                <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->authorised_person)?>
                </span><br /> <font style="font-size:11px; padding-bottom::-50px">(<?=$datas->authorised_date;?>)</font></td>
                
                
                
                <td align="center" ><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$_SESSION[PBI_ID])?>
                </span><br /><font style="font-size:11px; padding-bottom::-50px">(<?=$datas->accounts_viewed_date;?>)</font></td>
              </tr>
              
              
              
              
              
              
              
              
              
              
              
              <tr>
                <td align="center" style="text-decoration:overline"><strong>Claimer's Signature </strong></td>
                <td align="center" style="text-decoration:overline"><strong>HR & Admin</strong></td>
                <td align="center" style="text-decoration:overline"><strong>Recommended By </strong></td>
                <td align="center" style="text-decoration:overline"><strong>Authorised By </strong></td>
                
                
                <td align="center" style="text-decoration:overline"><strong>Accounts</strong></td>
                
              </tr>
            </table>
            
            
            
            <strong><br />
            </strong></td>
        </tr>
        
      </table>
    <div class="footer1"> </div></td>
  </tr>
</table>
</body>
</html>
