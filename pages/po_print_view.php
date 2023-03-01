<?php

require_once 'support_file.php';
require_once 'class.numbertoword.php';
$po_no = $_REQUEST['po_no'];
if(isset($_POST['cash_discount']))

{

	$po_no = $_POST['po_no'];
	$cash_discount = $_POST['cash_discount'];
	$ssql='update purchase_master set cash_discount="'.$_POST['cash_discount'].'" where po_no="'.$po_no.'"';
	mysqli_query($conn, $ssql);

}



$sql1="select * from purchase_master where   po_no='$po_no'";
$data=mysqli_fetch_object(mysqli_query($conn, $sql1));
$vendor=find_all_field('vendor','','vendor_id='.$data->vendor_id );
$whouse=find_all_field('warehouse','address','warehouse_id='.$data->warehouse_id);
$sql_proj = "select * from project_info where 1";
$datasks = mysqli_fetch_object(mysqli_query($conn, $sql_proj));

//$proj_info = find_all_field('project_info','proj_name','proj_id='.$data->proj_id);
$bd_style=$data->po_date;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<link href="../assets/build/css/invoice.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">

function hide()

{

    document.getElementById("pr").style.display="none";

}

</script>
</head>
<!--body-->


<!--body style="background-image:url(ltr.png);background-repeat:no-repeat; background-position:center;"-->
<body>
<form action="" method="post">
  <table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left"><p class="style4" style="font-size:20px;text-align:center;"> <strong><ins>Purchase</ins></strong><strong><ins> Order</ins><ins></ins></strong></p>
        <table width="100%" cellpadding="0">
          <tr>
            <td width="61%" valign="top"><span style="font-size:10px"><span style="font-size:20px; font-style:italic"><strong>
              <?=$vendor->vendor_name;?>
              </strong></span><br />
              <?=$vendor->address;?>
              <br />
              Attn:
              <?=$vendor->contact_person_name;?>
              <br />
              <?=$vendor->contact_person_designation;?>
              Contact No:
              <?=$vendor->contact_no;?>
              <!--, Fax No:
              <?=$vendor->
              fax_no;?>-->
              , Email:
              <?=$vendor->email;?>
              <br />
              </span></td>
            <td width="39%" valign="top"><span style="font-size:10px">&nbsp;&nbsp;<strong>&nbsp;P O No.#
              <?=$_GET['po_no']?>
              </strong> <br />
              &nbsp;&nbsp;&nbsp;PO Date:
              <?php
			  list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $bd_style);	
			  echo "$day-$month-$year1";?>
              <br />
              &nbsp;&nbsp;&nbsp;Delivery Within:
              <?=$data->delivery_within?>
              <br />
              &nbsp;&nbsp;&nbsp;Note:
              <?=$data->po_details?>
              </span></td>
          </tr>
        </table>
        <br />
        Dear Sir/Madam,<br />
        In reference to your quotation ref no:
        <? if($data->quotation_no=='') echo 'NIL'; else echo $data->quotation_no;?>
        Dated at :
        <? if($data->quotation_date=='') echo 'NIL'; else echo $data->quotation_date;?>
        , we are pleased to issue this work order for the following demanded items:<br />
        </span> <span class="debit_box">
        </div>
        </span></td>
    </tr>
    <tr>
      <td><div id="pr">
          <div align="left">
            <table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><input name="button" type="button" onclick="hide();window.print();" value="Print" /></td>
                <td><span class="style3">Special Cash Discount: </span></td>
                <td><label>
                  <input name="cash_discount" type="text" id="cash_discount" value="<?=$cash_discount?>" />
                  </label>
                  <input type="hidden" name="po_no" id="po_no" value="<?=$po_no?>" /></td>
                <td><label>
                  <input type="submit" name="Update" value="Update" />
                  </label></td>
                <td><a target="_blank" href="../../po_documents/qoutationDoc/<?=$po_no?>.pdf"> Qoutation Doc</a> </td>
                <td><a target="_blank" href="../../po_documents/mailCommDoc/<?=$po_no?>.pdf">E-Mail Communacation Doc</a></td>
              </tr>
            </table>
          </div>
        </div>
        <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="0">
          <tr style="font-size:10px;">
            <td ><strong>SL/No</strong></td>
            <td ><strong>Code No</strong></td>
            <td nowrap="nowrap" style="min-width:30%;"><div align="center"><strong>Description of the Goods </strong></div></td>
            <td style="min-width:5%;"><strong>UOM</strong></td>
            <td><strong>Qty.</strong></td>
            <td><strong>Rate</strong></td>
            <td><strong>Value,
              <?=$data->currency;?>
            </strong></td>
          </tr>
          <?php

$final_amt=0;
$pi=0;
$total=0;
$sql2="select p.*,i.*,i.unit_name from purchase_invoice p, item_info i where p.item_id not in ('1096000100010313') and p.po_no='$po_no' and 
p.item_id=i.item_id
";
$data2=mysqli_query($conn, $sql2);
//echo $sql2;
while($info=mysqli_fetch_object($data2)){
$pi++;
$amount=$info->qty*$info->rate;
$total=$total+($info->amount);
$total_item_value=$total_item_value+($info->qty*$info->rate);

$sl=$pi;

$item=find_a_field('item_info','concat(item_name,item_description)','item_id='.$info->item_id);
$item_details=find_a_field('purchase_invoice','item_details','id='.$info->id);
$fg_code = find_a_field('item_info','finish_goods_code','item_id='.$info->item_id);
$qty=$info->qty;

$unit_name=$info->unit_name;

$rate=$info->rate;
$item_del_date=$info->item_del_date;
$disc=$info->disc;

?>
          <tr>
            <td valign="top"><?=$sl?></td>
            <td align="left" valign="top"><?=$fg_code?></td>
            <td align="left" valign="top"><?=$item?>#<?=$item_details?></td>
            <td align="center" valign="top"><?=$unit_name?></td>
            <td valign="top"><?=$qty?></td>
            <td align="right" valign="top"><?=number_format($rate,2)?></td>
            <td align="right" valign="top"><?=number_format($amount,2)?></td>
          </tr>
          <? }?>
          <tr>
            <td colspan="6" align="right">Total:</td>
            <td align="right"><strong><?php echo number_format($total,2);?></strong></td>
          </tr>
        </table>
        <table width="100%" border="0" bordercolor="#000000" cellspacing="3" cellpadding="3" class="tabledesign1" style="width:700px">
		<? if($data->cash_discount>0){?>
          <tr>
            <td colspan="6" align="right">Discount:</td>
            <td align="right"><strong>
              <? if($data->cash_discount>0) echo number_format($data->cash_discount,2); else echo '0.00';?>
              </strong></td>
          </tr>
		  <? }$total=$total-$data->cash_discount;?>
           <? if($data->tax_ait>0){?>
          <tr>
            <td colspan="6" align="right">AIT (<?=$data->tax_ait?>%): </td>
            <td align="right"><strong> <? echo number_format((($AIT=$total/(100-$data->tax_ait))*$data->tax_ait),2);?> </strong></td>
          </tr>
          <? }
		  $total=$total+($total/(100-$data->tax_ait))*$data->tax_ait;
		  ?>
          
          <? if($data->tax>0){?>
          <tr>
            <td colspan="6" align="right">VAT(<?=$data->tax?> %):</td>
            <td align="right"><strong>
              <?=number_format((($total*$data->tax)/100),2);?>
              </strong></td>
          </tr>
          <? }?>
         
          <? if($data->transport_bill>0){?>
          <tr>
            <td colspan="6" align="right">Transport Bill: </td>
            <td align="right"><strong> <? echo number_format(($data->transport_bill),2);?> </strong></td>
          </tr>
          <? }?>
          <? if($data->labor_bill>0){?>
          <tr>
            <td colspan="6" align="right">Labor Bill: </td>
            <td align="right"><strong> <? echo number_format(($data->labor_bill),2);?> </strong></td>
          </tr>
          <? }?>


            <tr>
            <td align="left" colspan="4">In Word: Taka
              <?

		
		$tax_ait_total=(($total_item_value-$data->cash_discount)/(100-$data->tax_ait))*$data->tax_ait;
		$tax_total=(((($total_item_value-$data->cash_discount)+$tax_ait_total)*$data->tax)/100);
		$scs =  $aatotal = ($total_item_value-$data->cash_discount+$tax_total+$data->transport_bill+$data->labor_bill+$tax_ait_total);
		$credit_amt = explode('.',$scs);
	    if($credit_amt[0]>0){

	 echo convertNumberToWordsForIndia($credit_amt[0]);}
	 if($credit_amt[1]>0){
	 if($credit_amt[1]<10) $credit_amt[1] = $credit_amt[1]*10;
	 //echo  ' & '.convertNumberToWordsForIndia($credit_amt[1]).' paisa ';
	 }
	 echo ' Only';

		?></td>
              
              <td width="150" colspan="2" align="right">Grand Total (Rounded):</td>
            <td align="right"><strong> <? echo number_format(($total_item_value-$data->cash_discount+$data->transport_bill+$tax_total+$data->labor_bill+$tax_ait_total),0);?> </strong></td>
          </tr>



            
          <tr>
            <td colspan="6" align="left"><p><strong>Terms &amp; Conditions: </strong></p>
              <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:9px">
                <tr>
                  <td width="20%" align="left" valign="top"><li>Buyer</li></td>
                  <td width="3%" align="right" valign="top">:</td>
                  <td align="left" valign="top"><strong>
                    <?=$_SESSION['company_name']?> (BIN:<?=find_a_field('company','BIN','company_id="'.$_SESSION[companyid].'" and section_id='.$_SESSION[sectionid])?>) <br> <?=$_SESSION['company_address'];?>
                    </strong></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><li>Final Destination </li></td>
                  <td align="right" valign="top">:</td>
                  <td align="left" valign="top"><?=$whouse->warehouse_name.', '.$whouse->address?></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><li>Contact Person </li></td>
                  <td align="right" valign="top">:</td>
                  <td align="left" valign="top"><b><?=$whouse->warehouse_company?>. Mobile No: <?=$whouse->contact_no?></b></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><li>Delievery  Instruction</li></td>
                  <td align="right" valign="top">:</td>
                  <td align="left" valign="top">Delivery Should Reached at Destination Point within 5PM. </td>
                </tr>
                <tr>
                  <td align="left" valign="top"><li>Payment</li></td>
                  <td align="right" valign="top">:</td>
                  <td align="left" valign="top"><?=$cehckstatus=find_a_field('purchase_master','payment_terms','po_no="'.$_GET[po_no].'"');?></td>
                </tr>
              </table></td>
          </tr>
          
          <?php
		  $cehckstatus=find_a_field('purchase_master','status','po_no="'.$_GET[po_no].'"');
		  if($cehckstatus=='PROCESSING' || $cehckstatus=='COMPLETED'){
		   ?>
          <tr>
            <td colspan="4" align="left" style="font-size:10px" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="25%" valign="top"><p>Thanking You,<br />
                    </p>
                    <p style="text-align:center"><br />
                      <br />
                      <?
				$calculate = $total+$data->transport_bill+$tax_total+$data->labor_bill-$data->cash_discount ;?></p></td><br /><br />
                   <td width='25%' valign='top'><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$data->checkby);?><br />(<em style="font-size:8px; font-weight:bold"><?php
				   
				   $PBI_DESIGNATION_checked=find_a_field('personnel_basic_info','PBI_DESIGNATION','PBI_ID='.$data->checkby);
				  if($PBI_DESIGNATION_checked=='65' || $PBI_DESIGNATION_checked=='66') {echo  $checkdes=find_a_field('designation','DESG_SHORT_NAME','DESG_ID='.$PBI_DESIGNATION_checked);} else { echo $checkdes=find_a_field('designation','DESG_DESC','DESG_ID='.$PBI_DESIGNATION_checked);}?>, <?=$DEP=find_a_field('designation','DEP_NAME','DESG_ID='.$PBI_DESIGNATION_checked);?>)</em><br />
                    -----------------------------<br />
                    <strong>Checked By</strong> </td>
                    
                    
                    
                  <td width='25%' valign='top'><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$data->recommended);?><br />(<em style="font-size:8px; font-weight:bold"><?php
				   
				   $PBI_DESIGNATION_recommended=find_a_field('personnel_basic_info','PBI_DESIGNATION','PBI_ID='.$data->recommended);
				  echo  $recommended=find_a_field('designation','DESG_DESC','DESG_ID='.$PBI_DESIGNATION_recommended);?>)</em><br />
                   
                    ----------------------------<br />                   
                    
                    <strong>Recommended By</strong> </td>
                    
                    
                    
                  <td width='25%' valign='top'><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$data->authorise);?><br />(<em style="font-size:8px; font-weight:bold"><?php
				   
				   $PBI_DESIGNATION_authorise=find_a_field('personnel_basic_info','PBI_DESIGNATION','PBI_ID='.$data->authorise);
				  echo  $recommended=find_a_field('designation','DESG_DESC','DESG_ID='.$PBI_DESIGNATION_authorise);?>)</em><br />
                      ---------------------------------<br />
                      <strong>Authorised By</strong>
                      </td>
                </tr>
                <?php } else { echo '<h2 style="color:red">You are trying to print an unauthorized work order. Please wait until approval!!
</h2>'; }  ?>
              </table></td>
          </tr>
          <tr>
            <td colspan="4" align="left" valign="top" style="font-size:10px"><ul>
                <li>Supplied goods will be same as approved sample, otherwise the goods must be replaced &amp; you will bare all expenses.</li>
                <li>Supply Should be as per Company Specification.</li>
                <li>The Copy of Work Order must be shown at the factory premises during the delivery.</li>
                <li>Company protects the right to reconsider or cancel the Work-Order every nowby any administrational dictation.</li>
                <li>Any inefficiency in maintanence must be informed(Officially) before the execution to avoid the compensation. <br />
                  <br />
                  -This Purchase order prepared by <em>
                  <strong><?=find_a_field('user_activity_management','fname','user_id='.$data->entry_by);?>, Designation: 
                  <?=find_a_field('user_activity_management','designation','user_id='.$data->entry_by);?>, Mobile:  <?=find_a_field('user_activity_management','mobile','user_id='.$data->entry_by);?>
                  </strong>
                  </em>
                  <br />
                  <i>This Purchase Order is Software Generated and Do Not Require Any Signature.</i></li>
              </ul></td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>
