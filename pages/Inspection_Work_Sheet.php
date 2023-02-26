<?php
 require_once 'support_file.php'; 
 $title='Inspection Work Sheet';
unset($_SESSION['Inspection_Work_Sheet_ID']);
$item_info = find_all_field('item_info','','item_id='.$_GET['item_id']);
$QC_IWS_master = find_all_field('QC_Inspection_Work_Sheet_master','','item_id='.$_GET['item_id']);
$pr_master = find_all_field('purchase_receive','','item_id='.$_GET['item_id'].' and id='.$_GET['id'].' and pr_no='.$_GET['pr_no']);

$ins= date('Ymd').date("hisa");
?>


<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.productcode.options[form.productcode.options.selectedIndex].value;
	self.location='purchase.php?productcodeget=' + val ;
}
</script>
<style>
    input[type=text]{
        font-size: 11px;
    }
    input[type=date]{
        font-size: 11px;
    }
</style>
<?php if(isset($_GET[pr_no])):
    require_once 'body_content_without_menu.php'; else :
    require_once 'body_content.php'; endif;  ?>

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />



                      <?php
                      $_POST['entry_at']=date('Y-m-d h:s:i');
                      $_POST['entry_by']=$_SESSION['user']['id'];
                      $warehouseid=$_SESSION['user']['depot'];
                      $inspdate=date('Y-m-d');
                      $m =$_POST[mfg];
                      $mgfdateF=date('Y-m-d' , strtotime($m));

                      $e =$_POST[Exp_date];
                      $Exp_date=date('Y-m-d' , strtotime($e));

                      $rec =$_POST[Receipt_date];
                      $recdateF=date('Y-m-d' , strtotime($rec));

                      $rel =$_POST[Release_Date];
                      $reldateF=date('Y-m-d' , strtotime($rel));

                      $delivary_challan=getSVALUE('MAN_master','delivary_challan','where MAN_ID="'.$_GET[manid].'"');
                      $VAT_challan=getSVALUE('MAN_master','VAT_challan','where MAN_ID="'.$_GET[manid].'"');

                      if(isset($_POST[initite])){

                          $insertdata=mysql_query("INSERT INTO QC_Inspection_Work_Sheet_master (
	warehouse_id,
	MAN_ID,
	item_id,
	inspection_date,
	inspection_lot_no,
	vendor_code,
	delivary_challan,
	VAT_challan,
	Mfg,
	Exp_Date,
	Receipt_Date,
	Release_Date,
	packing_labeling,
	Physical_Properties,
	Total_Pack,
	Sample_Size,
	Received_Qty,
	No_of_pack_physically_checked,
	No_of_sample_scraped,
	entry_by,
	entry_at,
	opinion,
	sample_qty,
	accepted_qty,
	rejected_qty,
	hold_qty,
	po_no,
	status	,
	t_id,inspection_for,ip,analyst,no_of_pack
	) VALUES ('$_POST[warehouseid]','$_GET[manid]','$_GET[item_id]','$inspdate','$_POST[inspection_lot_no]','$_POST[vendor_code]','$delivary_challan','$VAT_challan','$mgfdateF','$Exp_date','$recdateF','$reldateF','$_POST[packing_labeling]','$_POST[Physical_Properties]','$_POST[Total_Pack]','$_POST[Sample_Size]','$_POST[Received_Qty]','$_POST[No_of_pack_physically_checked]','$_POST[No_of_sample_scraped]','".$_POST['entry_by']."','".$_POST['entry_at']."','$_POST[opinion]','$_POST[sample_qty]','$_POST[accepted_qty]','$_POST[rejected_qty]','$_POST[hold_qty]','$_POST[po_no]','UNCHECKED','$_GET[t_id]','Material','$ip','$_POST[analyst]','$_POST[no_of_pack]')");
                          $_SESSION['Inspection_Work_Sheet_ID']=mysql_insert_id();
                          ?>

                     <?php } ?>


                      <SCRIPT language=JavaScript>

                          function doAlert(form)
                          {
                              var val=form.accepted_qty.value;
                              var val2=form.stockbalance.value;

                              if (Number(val)>Number(val2)){
                                  alert('oops!! Exceed Stock Balance!! Thanks');

                                  form.accepted_qty.value='';
                              }
                              form.accepted_qty.focus();
                          }</script>
                    
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">

<?php
$MANquery="SELECT 
m.*,
d.MAN_ID,
d.item_id,
d.qty as Receivedqty,
d.po_no,
d.mfg,
d.no_of_pack,
i.*,
v.*,
w.warehouse_id,
w.warehouse_name

FROM
MAN_master m,
MAN_details d,
item_info i,
vendor v,
warehouse w
where 

m.MAN_ID=d.MAN_ID and 
m.vendor_code=v.vendor_id and 
m.warehouse_id=w.warehouse_id and 
i.item_id='".$_GET[item_id]."' and 
m.MAN_ID='".$_GET[manid]."' and 
d.id='".$_GET[t_id]."'

";
$mysqlquery=mysql_query($MANquery);
$data=mysql_fetch_object($mysqlquery);

?>
<table width="95%"  style="border:none; margin-top:-30px; color:#999; " cellspacing="0" cellpadding="1">

<tr style="border:none"><th style="text-align:left; width:15%">Item Name</th><th style="text-align:center; width:5%">:</th><td colspan="4" style="text-align:left; font-size:18px;"><strong><em><?=$item_info->item_name; ?> (<?=$item_info->unit_name; ?>)</em></strong></td></tr>

<tr style="border:none"><th style="text-align:left; width:15%">Insp. Lot No.</th><th style="text-align:center; width:2%">:</th><td  style="text-align:left; font-size:18px; color:red">
        <input type="hidden" id="warehouseid"  required="required" name="warehouseid" value="<?=$data->warehouse_id?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px" readonly >
<input type="text" id="inspection_lot_no"  required="required" name="inspection_lot_no" value="<?=$ins?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px" readonly >
</td>
    <th style="text-align:left; width:15%">No of Pack</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="no_of_pack" id="no_of_pack" value="<?=$data->no_of_pack;?>"  readonly class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px"  ></td>

</tr>



<tr style="border:none">
<th style="text-align:left; width:15%">Mfg Date</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red">

        <input type="date"  required="required" name="mfg" value="" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" >

    </td>

<th style="text-align:left; width:15%">Total Pack</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="Total_Pack" id="Total_Pack" value="" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px"  ></td>
</tr>



<tr style="border:none">
<th style="text-align:left; width:15%">Exp Date</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="date" name="Exp_date"  class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" /></td>

<th style="text-align:left; width:15%">Sample Size</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="Sample_Size" id="Sample_Size" value="<?=$btch=getSVALUE("production_floor_receive_detail","lot","Where custom_pr_no='".$_GET[custom_pr_no]."' and item_id=".$_GET[item_id]); ?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>
</tr>


<tr style="border:none">
<th style="text-align:left; width:15%">Receipt Date</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="date" name="Receipt_date" value="<?=$lot= getSVALUE('MAN_details','man_date','MAN_ID="'.$_GET[manid].'" and item_id='.$_GET[item_id]); ?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>

<th style="text-align:left; width:15%;">Received Qty</th><th style="text-align:center; width:2%">:</th><td style="color:#000; font-size:12px; vertical-align:bottom">
        <input type="text" name="Received_Qty" id="Received_Qty" value="<?=$data->Receivedqty; ?> (<?=$data->unit_name?>)" readonly class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" >
</td>
</tr>



<tr style="border:none">
<th style="text-align:left; width:15%">Release Date</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="date" name="Release_Date" value="" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>

<th style="text-align:left; width:15%">Approved Qty</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="approved_qty_man" id="approved_qty_man" value="<?=$data->Receivedqty; ?> (<?=$data->unit_name?>)" readonly class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>
</tr>


    <tr style="border:none">
        <th style="text-align:left; width:15%">Vendor Name</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red">
            <input type="hidden" name="vendor_code" id="vendor_code" value="<?=$data->vendor_code;?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:250px; margin-top:5px" readonly >
            <input type="text" name="vendor_name" id="vendor_name" value="<?=$data->vendor_name;?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:250px; margin-top:5px" readonly ></td>

        <th style="text-align:left; width:15%">PO No.</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="po_no" id="po_no" value="<?=$data->po_no; ?>" readonly class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>
    </tr>



    <tr style="border:none">
        <th style="text-align:left; width:15%">P & L</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red">
            <select style="color:#000; font-size:13px; height:25px; width:250px" name="packing_labeling" id="opinion" required>
                <option value="" selected>Select a Packing & Labeling</option>
                <option value="Good">Good</option>
                <option value="Acceptable">Acceptable</option>
                <option value="Poor">Poor</option>
            </select>

        </td>

        <th style="text-align:left; width:15%">No. of pack physically checked</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="No_of_pack_physically_checked" id="No_of_pack_physically_checked" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>
    </tr>


    <tr style="border:none">
        <th style="text-align:left; width:15%">Physical Properties</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red">
            <select style="color:#000; font-size:13px; height:25px; width:250px" name="Physical_Properties" id="Physical_Properties" required>
                <option value="" selected>Physical Properties</option>
                <option value="Good">Good</option>
                <option value="Acceptable">Acceptable</option>
                <option value="Poor">Poor</option>
            </select>
        </td>

        <th style="text-align:left; width:15%">No. of sample scraped</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="No_of_sample_scraped" id="No_of_sample_scraped" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>
    </tr>

<tr style="border:none">
<th style="text-align:left; width:15%">Analyst</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="hidden" name="analyst" value="<?=$_SESSION[userid]?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:170px; margin-top:5px" >
<input type="text" name="" value="<?=$cmuname= getSVALUE('user_activity_management','fname','where user_id='.$_SESSION[userid]);?>" readonly class="form-control col-md-7 col-xs-12" style="height:25px; width:170px; margin-top:5px" >
</td>


</tr>



</table>




                        <table width="95%" border="1"  style=" border-collapse:collapse; height:30px; margin-top:10px; color:#000;" cellspacing="0" cellpadding="1">
                            <tr><th style="text-align:center; width:5%">S. No</th>
                                <th style="text-align:center; width:30%">TEST PARAMETERS</th>
                                <th style="text-align:center">SPECIFICATION</th>
                                <th style="text-align:center; width:15%">RESULT</th>
                            </tr>

                            <?php

                            $speresult=mysql_query("Select * from item_SPECIFICATION where  item_id='$_GET[item_id]'");
                            while($sprow=mysql_fetch_array($speresult)){
                                $i=$i+1;


                                if(isset($_POST[initite])) {

                                    $PARAMETERSid = $_POST['PARAMETERSid_' . $sprow[id]];
                                    $GETresult = $_POST['result_' . $sprow[id]];

                                    mysql_query("INSERT INTO QC_Inspection_Work_Sheet_result (
	Inspection_Work_Sheet_ID,
	MAN_ID,
	item_id,
	PARAMETERS,
	RESULT,po_no,status
	
	) VALUES ('$_SESSION[Inspection_Work_Sheet_ID]','$_GET[manid]','$_GET[item_id]','$PARAMETERSid','$GETresult','" . $data->po_no . "','UNCHECKED')");

                                } ?>


                           <?php if(isset($_POST[initite])) { ?>
                            <meta http-equiv="refresh" content="0;MAN_checked.php?man_id=<?php echo $_GET[manid]?>">
                           <?php  } ?>

                                <tr>
                                    <td align="center" valign="top"><?=$i?></td>
                                    <td align="left" valign="top">
                                        <input type="hidden" name="PARAMETERSid_<?=$sprow[id]?>" id="PARAMETERSid_<?=$sprow[id]?>" value="<?=$sprow[TEST_PARAMETERS]?>" >
                                        <?=$sprow[TEST_PARAMETERS]?>-<?=$PARAMETERS=getSVALUE("PARAMETERS", "PARAMETERS_Name", " where PARAMETERS_CODE='$sprow[TEST_PARAMETERS]'");?></td>
                                    <td align="center" valign="top"><?=$sprow[SPECIFICATION];?></td>
                                    <td align="center" valign="top"><input type="text" name="result_<?=$sprow[id]?>" id="result_<?=$sprow[id]?>" style="width:100px;margin: 2px" value="<?=$sprow[RESULT];?>" autocomplete="off"  ></td>


                                </tr>
                            <?php } ?>

                        </table>




<table width="95%"  style="border:none; margin-top:10px; " cellspacing="0" cellpadding="1">
<tr style="border:none">
<th style="text-align:left; width:15%">Opinion</th><th style="text-align:center; width:2%">:</th><td colspan="4" style="text-align:left; font-size:18px; color:red">
        <select style="color:#000; font-size:13px; height:25px; width:130px" name="opinion" id="opinion" required>
<option value="" selected></option>
<option value="Sample conforms to I.H.S">Sample conforms to I.H.S</option>
<option value="Sample not conform to I.H.S">Sample not conform to I.H.S</option>

</select>
</td></tr>

<tr style="border:none">
<th style="text-align:left; width:15%">Remarks</th><th style="text-align:center; width:2%">:</th><td colspan="4" style="text-align:left; font-size:18px; color:red">
        <textarea name="Remarks" style="margin-top:5px; width: 400px; height: 80px" id="Remarks" class="form-control col-md-7 col-xs-12"></textarea>

</td></tr>

    <script type="text/javascript">

        function calculateTotal() {

            var totalAmt = document.addem.accepted_qty_cal.value;
            totalR = eval(totalAmt - document.addem.sample_qty.value-document.addem.rejected_qty.value-document.addem.hold_qty.value);

            document.getElementById('accepted_qty').value = totalR;
        }

    </script>






    <tr style="border:none">
        <th style="text-align:left; width:15%">Qty</th><th style="text-align:center; width:2%">:</th>
        <td style="text-align:left; font-size:18px; color:red"><input type="text" name="sample_qty" class="form-control col-md-7 col-xs-12" onkeyup="calculateTotal()" style="height:25px; width:130px; margin-top:5px" placeholder="Sample Qty" >
        </td>




        <td style="text-align:left; font-size:18px; color:red"><input type="text" name="rejected_qty" onkeyup="calculateTotal()" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" placeholder="Rejected Qty" >
        </td>

        <td style="text-align:left; font-size:18px; color:red"><input type="text" name="hold_qty" onkeyup="calculateTotal()" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" placeholder="Hold Qty" >
        </td>



        <td style="text-align:left; font-size:18px; color:red">
            <input type="hidden" id="stockbalance"  name="stockbalance"    value="5" >

            <input type="text" id="accepted_qty"  name="accepted_qty"  class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px"  value="<?=$data->Receivedqty; ?>" >


            <input type="hidden"  name="accepted_qty_cal"  id="accepted_qty_cal" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" placeholder="Accepted Qty" readonly value="<?=$data->Receivedqty; ?>" >
        </td>



    </tr>

<tr style="height: 10px"></tr>
<tr style="border:none">





<th colspan="4" align="center">
<div class="form-group" style="margin-left:40%">               
<div class="col-md-6 col-sm-6 col-xs-12">              
<button type="submit" name="Reprocessing" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Send for Reprocessing </button></div></div> 


</th>

<th colspan="4" align="center">

<div class="form-group" style="margin-left:40%">               
<div class="col-md-6 col-sm-6 col-xs-12">              
<button type="submit" name="initite"  class="btn btn-success">Confirm & Forword Inspection </button></div></div> </th>
</tr>
</table> 
                   
</form>
                  </div>

                </div>

              </div>
<?=$html->footer_content();mysqli_close($conn);?>