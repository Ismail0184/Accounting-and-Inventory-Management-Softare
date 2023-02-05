<?php
require_once 'support_file.php';
$title='Stock Transfer (STO)';

$now=time();
$unique='pi_no';
$table="production_issue_master";
$table_details="production_issue_detail";

$page="production_transfer2.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');



if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST[ISSUE_TYPE]='STO';
        $d =$_POST[pi_date];
        $_POST[pi_date]=date('Y-m-d' , strtotime($d));
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION[initiate_production_transfer]=$_POST[custom_pi_no];
        $_SESSION['pi_tr'] =$_POST[$unique];
        $_SESSION['production_warehouse'] =$_POST[warehouse_to];
        $_POST[create_date]=$create_date;
        $_POST[ip]=$ip;
        $crud->insert();
        $type=1;
        unset($_POST);
        unset($$unique);
    }

//for modify PS information ...........................
    if(isset($_POST['modify']))
    {   $d =$_POST[pi_date];
        $_POST[pi_date]=date('Y-m-d' , strtotime($d));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        unset($_POST);
    }


//for single FG Add...........................
    if(isset($_POST['add']))
    {  if($_POST['total_unit']>0) {
        $_POST[status]="UNCHECKED";
        $_POST[ISSUE_TYPE]="STO";
        $_POST[ip]=$ip;
        $_POST[total_unit]=$_POST[total_unit]*$_POST[pack_size];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $crud = new crud($table_details);
        $crud->insert();
    }}



} /// prevent multi submit

if(isset($_POST['confirmsave']))
{   $up="UPDATE ".$table." SET verifi_status='UNCHECKED' where ".$unique."='$_SESSION[pi_tr]'";
    $update_table_master=mysqli_query($conn, $up);
    $up2="UPDATE ".$table_details." SET verifi_status='UNCHECKED' where ".$unique."='$_SESSION[pi_tr]'";
    $update_production_floor_issue_master=mysqli_query($conn, $up2);
    unset($_SESSION['pi_tr']);
    unset($_SESSION['initiate_production_transfer']);
    unset($_POST);
}


//for single FG Delete..................................
$query="Select * from ".$table_details." where ".$unique."='$_SESSION[pi_tr]'";
$res=mysqli_query($conn, $query);
while($row=mysqli_fetch_array($res)){
    $ids=$row[id];
    if(isset($_POST['deletedata'.$ids]))
    {
        $del="DELETE FROM ".$table_details." WHERE id='$ids' and ".$unique."='$_SESSION[pi_tr]'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);
    }}

//for Delete..................................
if(isset($_POST['cancel']))
{   $crud = new crud($table_details);
    $condition =$unique."=".$_SESSION['pi_tr'];
    $crud->delete_all($condition);
    $crud = new crud($table);
    $condition=$unique."=".$_SESSION['pi_tr'];
    $crud->delete($condition);
    unset($_SESSION['pi_tr']);
    unset($_SESSION['initiate_production_transfer']);
    unset($_POST);
}

$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique.'='.$_SESSION['pi_tr'].'');


// data query..................................
if(isset($_SESSION['pi_tr']))
{   $condition=$unique."=".$_SESSION['pi_tr'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$result_details=mysqli_query($conn, "Select t.*,i.* 
from 
".$table_details." t,item_info i 
where   
t.".$unique."=".$_SESSION[pi_tr]." and t.item_id=i.item_id");
?>

<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
    function reloaditem(form)
    {   var val=form.item_id.options[form.item_id.options.selectedIndex].value;
        self.location='<?=$page;?>?transfer_from=' + '<?php echo $_GET[transfer_from]; ?>' + '&item_id=' + val;
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
<?php require_once 'body_content.php'; ?>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>


                    <a target="_new" style="float: right" class="btn btn-sm btn-default"  href="warehouse_STO_view.php">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Stock Transfer View</span>
                    </a>


            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                <table style="width:100%">
                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">STO NO<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?
                                    $pi_nos=find_a_field(''.$table.'','max('.$unique.')','1');
                                    if($_SESSION['pi_tr']>0) {
                                        $pi_noGET = $_SESSION['pi_tr'];
                                    } else {
                                        $pi_noGET=$pi_nos+1;
                                        if($pi_nos<1) $pi_noGET = 1;
                                    }
                                    ?>
                                    <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$pi_noGET;?>">
                                    <input type="text" id="custom_pi_no" style="width:100%" readonly name="custom_pi_no" value="<?php if($_SESSION[initiate_production_transfer]){ echo $custom_pi_no;} else { echo $_SESSION['STO']; } ?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                        </td>


                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">STO Date<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" id="pi_date" style="width:100%"  required="required" name="pi_date" value="<?=$pi_date;?>" class="form-control col-md-7 col-xs-12" >
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Remarks<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="remarks" style="width:100%" name="remarks" value="<?=$remarks;?>" class="form-control col-md-7 col-xs-12" >
                                </div>
                            </div>
                        </td>
                    </tr>



                    <tr>
                        <td style="width:35%; vertical-align: middle">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Transfer From<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="warehouse_from" id="warehouse_from">
                                        <option></option>
                                        <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_from, 'use_type in (\'PL\',\'WH\')'); ?>
                                    </select>
                                </div></div>
                        </td>


                            <td style="width:35%">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Transfer To<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="warehouse_to" id="warehouse_to">
                                            <option></option>
                                            <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_to, 'use_type in (\'PL\',\'WH\')'); ?>
                                        </select>
                                        </div></div>
                            </td>
                            <td style="width:30%">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">VAT Challan<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="VATChallanno" style="width:100%"  name="VATChallanno" value="<?=$VATChallanno;?>" class="form-control col-md-7 col-xs-12"  Placeholder="Challan & Date" >
                                    </div>
                                </div>
                            </td>
                    </tr>

                        <tr>
                            <td>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Transporter<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="transporter" id="transporter">
                                            <option></option>
                                            <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $transporter, 'vendor_category in (\'30\') order by vendor_name'); ?>
                                        </select>
                                    </div></div>
                            </td>


                            <td>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Track No.<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="ps_date" style="width:100%"  name="track_no" value="<?=$track_no?>" class="form-control col-md-7 col-xs-12" >
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="form-group">

                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Driver Info<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="remarkspro" style="width:100%"  required="required" name="driver_info" value="<?=$driver_info;?>" class="form-control col-md-7 col-xs-12" Placeholder="Name & mobile No" >
                                    </div>
                                </div>
                            </td>
                        </tr>


                        <tr>
                        <td colspan="3">
                            <div class="form-group" style="margin-left:40%">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php if($_SESSION[initiate_production_transfer]){  ?>
                                        <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px">Update Transfer Entry</button>
                                    <?php   } else {?>
                                        <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Initiate Transfer Entry</button>
                                    <?php } ?>
                                </div></div>
                        </td>

                        </tr>
                </table></form></div></div></div>



            <?php if($_SESSION[initiate_production_transfer]){?>
            <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$_SESSION[pi_tr];?>" >
                <input type="hidden" name="custom_pi_no" id="custom_pi_no" value="<?=$custom_pi_no;?>" >
                <input type="hidden" name="pi_date" id="pi_date" value="<?=$pi_date;?>">
                <input type="hidden" name="warehouse_from" id="warehouse_from" value="<?=$warehouse_from;?>">
                <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$warehouse_to;?>">
                <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
                    <thead>
                    <tr style="background-color: bisque">
                        <th style="text-align: center">Item Desctiption</th>
                        <th style="text-align: center">Unit</th>
                        <th style="text-align: center">Batch No</th>
                        <th style="text-align: center">Qty</th>
                        <th style="text-align: center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td align="center">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id" onchange="javascript:reloaditem(this.form)">
                                <option></option>
                                <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id in ('200000000','300000000','400000000','500000000') 							 
							  order by i.item_name";
                                advance_foreign_relation($sql_item_id,$_GET[item_id]);?>
                            </select>
                        </td>

                        <td style="width:10%" align="center">
                            <input type="hidden" id="pack_size" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="pack_size" readonly  class="form-control col-md-7 col-xs-12" value="<?=$pack_size=getSVALUE("item_info", "pack_size", " where item_id='$_GET[item_id]'")?>" >
                            <input type="text" id="unit" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="unit" readonly  class="form-control col-md-7 col-xs-12" value="<?=$unitname=getSVALUE("item_info", "unit_name", " where item_id='$_GET[item_id]'")?>" >
                        </td>


                        <td style="width:15%" align="center">
                            <select class="form-control" style="width: 100%; font-size: 11px" tabindex="-1" required="required" name="batch" id="batch">
                                <option></option>
                                <? $sql_item_id="SELECT distinct batch,batch FROM production_floor_receive_detail where item_id='$_GET[item_id]'  order by batch DESC";
                                advance_foreign_relation($sql_item_id,$batch);?>
                            </select>
                            </td>

                        <td style="width:15%" align="center">
                            <input type="text" id="total_unit" name="total_unit" style="width:100%; height:37px;text-align:center"  required="required"  class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                        <td align="center" style="width:5%">
                            <button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 12px">Add</button></td></tr>
                    </tbody>
                </table>
            </form>



            <!-----------------------Data Save Confirm ------------------------------------------------------------------------->
            <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left" style="font-size: 11px">
                <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                    <thead>
                    <tr style="background-color: bisque">
                        <th>SL</th>
                        <th>Code / Barcode</th>
                        <th>Item Desctiption</th>
                        <th style="text-align:center">UOM</th>
                        <th style="text-align:center">Batch</th>
                        <th style=" text-align:center">Qty</th>
                        <th style="text-align:center">#</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php  while($row=mysqli_fetch_array($result_details)){ $ids=$row[id]; ?>
                        <tr>
                            <td style="width:1%; vertical-align:middle"><?=$i=$i+1;?></td>
                            <td style="width:10%; vertical-align:middle"><?=$row[finish_goods_code];?></td>
                            <td style="vertical-align:middle"><?=$row[item_name];?></td>
                            <td style="width:10%; vertical-align:middle; text-align: center"><?=$row[unit_name];?></td>
                            <td align="center" style="width:10%; text-align:center"><?php echo $row[batch]; ?></td>
                            <td align="center" style="width:10%; text-align:center"><?=$total_unit=$row[total_unit]/$row[pack_size]; ?></td>
                            <td align="center" style="width:5%;vertical-align:middle">
                                <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:15px;  height:15px"></button>
                            </td>
                        </tr>



                        <?php

                        if (isset($_POST['confirmsave'])){


                            $datereal=date("Y-m-d");
                            list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $daterea);
                            $date=$day.'-'.$month.'-'.$year1;

                            //voucher date decode
                            $j=0;
                            for($i=0;$i<strlen($date);$i++)
                            {
                                if(is_numeric($date[$i]))
                                {
                                    $time[$j]=$time[$j].$date[$i];
                                }
                                else
                                {
                                    $j++;
                                }
                            }
                            $date=mktime(0,0,0,$time[1],$time[0],$time[2]);
                            //////////////////////
                            //check date decode
                            $j=0;
                            for($i=0;$i<strlen($c_date);$i++)
                            {
                                if(is_numeric($c_date[$i]))
                                    $ptime[$j]=$ptime[$j].$c_date[$i];
                                else $j++;
                            }
                            $c_date=mktime(0,0,0,$ptime[1],$ptime[0],$ptime[2]);
                            //////////////////////////

                            $rowSQLJVLearge = mysql_query( "SELECT MAX( jv_no ) AS jv_noLearge FROM `journal`;" );
                            $rowJVLarge = mysql_fetch_array( $rowSQLJVLearge );
                            $jv=$rowJVLarge['jv_noLearge']+1;



                            $transferfromLedger=$name=getSVALUE("warehouse", "ledger_id", "where warehouse_id='".$row[warehouse_from]."'");



                            $item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse, item_ex, item_price, tr_from,tr_no,sr_no,entry_by,entry_at,custom_no,batch,ip) VALUES 
('$row[pi_date]','$row[item_id]','$row[warehouse_from]','$row[warehouse_to]','$row[total_unit]','$row[unit_price]','ProductionTransfer','$row[id]','$_SESSION[pi_no]','".$_SESSION[userid]."','$enat','$_SESSION[initiate_production_transfer]','$row[batch]','$ip')");

                            $TRItemValue=$row[total_unit]*$row[unit_price];
                            $TRItemValueTotal=$TRItemValueTotal+$TRItemValue;

                            $journal="INSERT INTO `journal` (
									`proj_id` ,
									`jv_no` ,
									`jv_date` ,
									`ledger_id` ,
									`narration` ,
									`dr_amt` ,
									`cr_amt` ,
									`tr_from` ,
									`sub_ledger` ,
									`tr_no`,
									`tr_no_custom`,
									`tr_id`,
									`cc_code` 
									,user_id
									,group_for,jvdate,ip,custom_no
									)
					VALUES ('', '$jv', '$date', '".$transferfromLedger."', 'FG Transfer, STONO#$_SESSION[initiate_production_transfer]', '".$TRItemValueTotal."','', 'ProductionTransfer','', '$_SESSION[pr_no]','$tr_no_customProduction','', '$cc_code','".$_SESSION['userid']."','".$_SESSION['user']['group']."','$tdates','$ip','$_SESSION[initiate_production_transfer]')";
                            $query_journal = mysql_query($journal);



// fg  transfer to code start from here
                            $item_journal =mysql_query("INSERT INTO cycle_journal_item (ji_date, item_id, warehouse_id, relevant_warehouse,item_in, item_ex, item_price, final_stock, tr_from,tr_no,sr_no,entry_by,entry_at,tr_no_custom,batch) VALUES 
('$row[pi_date]','$row[item_id]','$row[warehouse_from]','$row[warehouse_to]','$row[total_unit]','','$row[unit_price]','$final_stock','ProductionTransfer','$row[id]','".$_SESSION[pi_no]."','".$_SESSION[userid]."','$enat','$_SESSION[initiate_production_transfer]','$row[batch]')");


                            $transitLedger='1007003000050000';
                            $journal="INSERT INTO `journal` (
									`proj_id` ,
									`jv_no` ,
									`jv_date` ,
									`ledger_id` ,
									`narration` ,
									`dr_amt` ,
									`cr_amt` ,
									`tr_from` ,
									`sub_ledger` ,
									`tr_no`,
									`tr_no_custom`,
									`tr_id`,
									`cc_code` 
									,user_id
									,group_for,jvdate,ip,custom_no
									)
					VALUES ('', '$jv', '$date', '$transitLedger', 'FG Transfer, STONO#$_SESSION[initiate_production_transfer]', '','".$TRItemValueTotal."', 'ProductionTransfer','', '$_SESSION[pr_no]','$tr_no_customProduction','','$cc_code','".$_SESSION['userid']."','".$_SESSION['user']['group']."','$tdates','$ip','$_SESSION[initiate_production_transfer]')";
                            $query_journal = mysql_query($journal);
                        }

                        $tp=$tp+$total_unit;
                    } ?>



                    </tbody>
                    <tr>
                        <td colspan="5" style="font-weight:bold; font-size:11px" align="right">Total STO QTY = </td>
                        <td style="text-align:center"><strong><?php echo $tp; ?></strong></td>
                        <td align="center" ></td>
                    </tr>
                </table>

                <button type="submit" style="float: left; margin-left: 1%; font-size: 12px" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to the Production Deleted?");' class="btn btn-danger">Delete STO</button>
                <?php if($COUNT_details_data>0) { ?>
                    <button type="submit" style="float: right; margin-right: 1%; font-size: 12px" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" class="btn btn-success">Confirm and Finish Transfer Entry </button>
                <?php } else { echo '';} ?>
            </form><br>
        </div></div></div>


<?php } ?>


<?php require_once 'footer_content.php' ?>
<!-- bootstrap-daterangepicker -->



<script>
    $(document).ready(function() {
        $('#mfg').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
<!-- /bootstrap-daterangepicker -->



<!-- Select2 -->
<script>
    $(document).ready(function() {
        $(".select2_single").select2({
            placeholder: "select your choice",
            allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple").select2({
            maximumSelectionLength: 4,
            placeholder: "With Max Selection limit 4",
            allowClear: true
        });
    });
</script>
<!-- /Select2 -->

