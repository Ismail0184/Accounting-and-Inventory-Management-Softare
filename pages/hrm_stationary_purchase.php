<?php
require_once 'support_file.php';
$title="Stationary Purchase";

$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$now=time();

$table="warehouse_other_receive";
$unique = 'or_no';   // Primary Key of this Database table
$table_deatils = 'warehouse_other_receive_detail';
$details_unique = 'id';
$table_journal_item='journal_item';
$page="hrm_stationary_purchase.php";
$crud      =new crud($table);
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if(isset($_POST['initiate']))
    {


        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];

        $_POST['entry_by'] = $_SESSION['PBI_ID'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $sd=$_POST[or_date];
        $_POST[or_date]=date('Y-m-d' , strtotime($sd));
        $_POST['issue_type'] = 'Office Issue';
        $_POST['status'] = 'MANUAL';
        $_POST['issued_to'] = $_SESSION[PBI_ID];
        $_SESSION['initiate_hrm_stationary_purchase']=$_POST[$unique];
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';

        unset($_POST);
        unset($$unique);
    }


    if(isset($_POST['add']))
    {

        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST[or_date]=$_POST[or_date];
        $_POST['receive_type'] = 'Local Purchase';
        $_POST['status'] = 'MANUAL';
        $_POST['recommend_qty'] = $_POST['qty'];
        $_POST['request_qty'] = $_POST['qty'];
        $_POST['issued_to'] = $_SESSION[PBI_ID];
        $_POST[oi_no]=$_SESSION['initiate_hrm_stationary_purchase'];
        $crud      =new crud($table_deatils);
        $crud->insert();

        $type=1;
        $msg='New Entry Successfully Inserted.';

        unset($_POST);
        unset($$unique);
    }


//for modify..................................
    if(isset($_POST['modify']))
    {
        $sd=$_POST[or_date];
        $_POST[or_date]=date('Y-m-d' , strtotime($sd));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        //echo $targeturl;

    }


//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table_deatils);
        $condition =$unique."=".$_SESSION['initiate_hrm_stationary_purchase'];
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$_SESSION['initiate_hrm_stationary_purchase'];
        $crud->delete($condition);
        unset($_SESSION['initiate_hrm_stationary_purchase']);
        echo $targeturl;
    }}

// data query..................................
if(isset($_SESSION[initiate_hrm_stationary_purchase]))
{   $condition=$unique."=".$_SESSION[initiate_hrm_stationary_purchase];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>
<script>
    var x = 0;
    var y = 0;
    var z = 0;
    function calc(obj) {
        var e = obj.id.toString();
        if (e == 'qtysa') {
            x = Number(obj.value);
            y = Number(document.getElementById('rate').value);
        } else {
            x = Number(document.getElementById('qtysa').value);
            y = Number(obj.value);
        }
        z = x * y;
        document.getElementById('total').value = z;
        document.getElementById('update').innerHTML = z;
    }
</script>
<style>
    input[type=text]{
        height: 25px; font-size: 11px;
    }
    input[type=submit]{
        font-size: 12px;
    }
</style>
<?php require_once 'body_content.php'; ?>




<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right">
                </div>
            </ul>
            <div class="clearfix"></div>
        </div>


        <div class="x_content">
            <form action="" enctype="multipart/form-data" method="post" style="font-size: 11px" name="addem" id="addem" >
                <? //require_once 'support_html.php';?>
                <table style="width:100%"  cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:50%;">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">ID No<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="<?=$unique?>"   required="required" name="<?=$unique?>" value="<? if($_SESSION['initiate_hrm_stationary_purchase']>0) { echo  $_SESSION['initiate_hrm_stationary_purchase'];

                                    } else

                                    { echo find_a_field($table,'max('.$unique.')+1','1');
                                        if($$unique<1) $$unique = 1;}?>" class="form-control col-md-7 col-xs-12"  readonly style="width:100%">
                                </div>
                            </div></td>


                        <td style="width:50%">
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Date<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="or_date" readonly  required="required" name="or_date" value="<?php if($_SESSION[initiate_hrm_stationary_purchase]>0){ echo date('m/d/Y' , strtotime($or_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width:100%" >      </div>
                            </div>
                        </td></tr>


                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Vendor<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" style="width: 100%; height: 25px; font-size: 11px"  name="vendor_id" id="vendor_id">
                                        <option value="<?=$vendor_id_GET=find_a_field('vendor','ledger_id','vendor_name="Local Purchase"');?>"><?=find_a_field('vendor','vendor_name','ledger_id='.$vendor_id_GET.'');?></option>
                                    </select></div></div></td>
                        <td><div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Vendor Name:<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="vendor_name" id="vendor_name" value="<?=$vendor_name;?>" required class="form-control col-md-7 col-xs-12" style="width: 100%;"></div></div></td>
                    </tr>


                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Final Destination<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" style="width: 100%; height: 25px; font-size: 11px"  name="warehouse_id" id="warehouse_id">
                                        <option></option>
                                        <?php
                                        $result=mysqli_query($conn , ("SELECT * from warehouse"));
                                        while($row=mysqli_fetch_array($result)){  ?>
                                            <option  value="<?=$row[warehouse_id]; ?>" <?php if($warehouse_id==$row[warehouse_id]) echo 'selected' ?>><?=$row[warehouse_name]; ?></option>
                                        <?php } ?></select>
                                </div></div></td>




                        <td><div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Remarks:<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="or_subject" id="or_subject" value="<?=$or_subject;?>"  class="form-control col-md-7 col-xs-12" style="width: 100%;"></div></div></td>
                    </tr>


                    <tr>
                        <td><div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Vendor Address </label>
                                <div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="vendor_address" id="vendor_address" value="<?=$vendor_address;?>" class="form-control col-md-7 col-xs-12" style="width: 100%;"></div></div></td>
                        </div></div></td>



                        <td><div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Contact Number </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="vendor_contact_number" id="vendor_contact_number" value="<?=$vendor_contact_number;?>" class="form-control col-md-7 col-xs-12" style="width: 100%;"></div></div></td>
                    </tr>

    <tr><td style="height:5px"></td></tr>

    <tr>
        <td><div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Requisition From:</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" name="requisition_from" id="requisition_from" value="<?=$requisition_from;?>"  class="form-control col-md-7 col-xs-12" style="width: 100%;">
                </div></div>
        </td>




        <td><div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Chalan No:<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" name="chalan_no" id="chalan_no" value="<?=$chalan_no;?>" required class="form-control col-md-7 col-xs-12" style="width: 100%;">
                </div></div>
        </td>
    </tr>


                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Checked By<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="checked_by" id="checked_by">
                                        <option></option>
                                        <? $sql_recommended_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME";
                                        advance_foreign_relation($sql_recommended_by,$checked_by);?>
                                    </select>
                                </div></div>
                        </td>



                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Recommended By<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="recommended_by" id="recommended_by">
                                        <option></option>
                                        <? $sql_recommended_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME";
                                        advance_foreign_relation($sql_recommended_by,$recommended_by);?>
                                    </select>
                                </div></div>
                        </td>
                    </tr>

                </table>











                <div class="form-group" style="margin-left:40%; margin-top: 15px">

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if($_SESSION[initiate_hrm_stationary_purchase]){  ?>
                            <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 12px">Update <?=$title;?></button>

                        <?php   } else {?>
                            <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Initiate <?=$title;?></button>
                        <?php } ?>
                    </div></div>
            </form></div></div></div>











<?php if($_SESSION[initiate_hrm_stationary_purchase]){  ?>

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form action="" enctype="multipart/form-data" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <input type="hidden" id="vendor_id" name="vendor_id" value="<?=$vendor_id;?>" >
                    <input type="hidden" id="vendor_name" name="vendor_name" value="<?=$vendor_name;?>" >
                    <input type="hidden" id="warehouse_id" name="warehouse_id" value="<?=$warehouse_id;?>" >
                    <input type="hidden" id="or_date" name="or_date" value="<?=$or_date;?>">
                    <input type="hidden" id="<?=$unique;?>" name="<?=$unique;?>" value="<?=$_SESSION[initiate_hrm_stationary_purchase];?>">

                    <table style="width:100%">
                        <tbody>
                        <tr>
                            <td align="left" >
                                <input type="hidden" name="oi_date" id="oi_date" value="<?=$oi_date;?>"  />


                                <select class="select2_single form-control" style="width:400px"   tabindex="-1" required="required" name="item_id" id="item_id">
                                    <option></option>
                                    <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id  in ('600000000','1500000000') 							 
							  order by i.item_name";
                                    advance_foreign_relation($sql_item_id,$item_id);?>
                                </select>
                                </td>

                            <td align="left" >
                                <input  type="text" style="width:150px; height:37px; font-size: 12px; text-align:center"   name="qty" id="qty" autocomplete="off" class='qty' placeholder="qty">
                            </td>

                            <td  >
                                <input  type="text" style="width:150px; height:37px; font-size: 12px; text-align:center" value="" name="rate" id="price" autocomplete="off" class='price' placeholder="rate">
                            </td>

                            <td >
                                <input style="width:150px; height:37px; font-size: 12px; text-align:center" readonly type='text' id='sum' name='amount' class='sum' placeholder="amount" />
                            </td>

                            <td align="center" style="width:5%"><button type="submit" class="btn btn-primary" name="add" id="add">Add</button></td></tr>
                        </tbody>
                    </table>
                    <input name="count" id="count" type="hidden" value="" />
                </form>
            </div></div></div></div>


    <!-----------------------Data Save Confirm ------------------------------------------------------------------------->


    <form enctype="multipart/form-data" name="addem" id="addem"   method="post"  class="form-horizontal form-label-left">
        <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
            <thead>
            <tr>
                <th>SL</th>
                <th>Item Description</th>
                <th style="text-align:center">Unit Name</th>
                <th style="text-align:center">Qty</th>
                <th style="text-align:center">Rate</th>
                <th style="text-align:center">Amount</th>
                <th style="width:15%; text-align:center">Action</th>

            </tr>
            </thead>
            <tbody>
            <?php

            $rs=mysql_query("Select 
d.*,i.*
from 
".$table_deatils." d,
item_info i
  where 
 d.item_id=i.item_id and 
 d.".$unique."='".$_SESSION['initiate_hrm_stationary_purchase']."'
 ");
            while($uncheckrow=mysql_fetch_array($rs)){

            if (isset($_POST['confirmsave'])){
                mysql_query("INSERT INTO ".$table_journal_item." (ji_date,item_id,warehouse_id,item_in,item_price,total_amt,tr_from,custom_no,tr_no,sr_no,entry_by,entry_at,ip,section_id,company_id) values 
                ('$uncheckrow[or_date]','$uncheckrow[item_id]','$uncheckrow[warehouse_id]','$uncheckrow[qty]','$uncheckrow[rate]','$uncheckrow[amount]','Local Purchase','$uncheckrow[or_no]','$uncheckrow[id]','$uncheckrow[or_no]','$_SESSION[PBI_ID]','".date('Y-m-d H:s:i')."','$ip','$_SESSION[sectionid]','$_SESSION[companyid]') ");
            }

                $js=$js+1;
                $ids=$uncheckrow[id];
                $item_id_update=$_POST['item_id_update'.$ids];
                $upcr_qty=$_POST['upcr_qty'.$ids];
                $upcr_rate=$_POST['upcr_rate'.$ids];
                $upcr_amt=$upcr_qty*$upcr_rate;


                if(isset($_POST['deletedata'.$ids]))
                {
                    mysql_query("DELETE FROM ".$table_deatils." WHERE id='$ids'"); ?>
                    <meta http-equiv="refresh" content="0;<?=$page?>">
                    <?php
                }

                if(isset($_POST['editdata'.$ids]))
                {
                    mysql_query("Update ".$table_deatils." set item_id='$item_id_update',qty='$upcr_qty',rate='$upcr_rate',amount='$upcr_amt' WHERE id='$ids'"); ?>
                    <meta http-equiv="refresh" content="0;<?=$page?>">
                <?php }?>


                <tr>
                    <td style="width:3%; vertical-align:middle"><?php echo $js; ?></td>
                    <td style="vertical-align:middle"><?=$uncheckrow[item_id]; ?>-<?=$uncheckrow[item_name]; ?></td>
                    <td style="text-align:center"><?=$unit=getSVALUE("item_info", "unit_name", " where item_id=".$uncheckrow[item_id]."");?></td>

                    <td align="center" style="width:6%; text-align:center"><?=number_format($uncheckrow[qty]);?></td>
                    <td align="center" style="width:6%; text-align:center"><?=$uncheckrow[rate];?></td>
                    <td align="center" style="width:6%; text-align:center"><?=$uncheckrow[amount];?></td>
                    <td align="center" style="width:10%;vertical-align:middle">
                        <button type="submit" name="editdata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Edit Date?");'><img src="update.jpg" style="width:15px;  height:15px"></button>
                        <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete Credit Voucher?");'><img src="delete.png" style="width:15px;  height:15px"></button>
                    </td>
                </tr>
                <?php  } ?>
            </tbody></table>
        <?php
        //if(prevent_multi_submit()){
        if (isset($_POST['confirmsave'])){
            mysql_query("Update ".$table." set status='UNCHECKED' where ".$unique."=".$_SESSION['initiate_hrm_stationary_purchase']."");


            $name=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$_SESSION[PBI_ID]);
            $emailId=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$recommended_by);
            $emailIds=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$authorised_person);
            $to = $emailId;
            $subject = "Stationary has been Purchased";
            $txt1 = "<p>Dear Sir,</p>				
				<p>A Stationary Purchase is pending for your Recommendation/Authorization. Please enter Employee Access module to approve the requisition. </p>				<p>Requisition By- ".$name."</p>				
				<p><b><i>This EMAIL is automatically generated by ERP Software.</i></b></p>";
            $txt=$txt1.$txt2.$tr;
            $from = 'erp@icpbd.com';
            $headers = "";
            $headers .= "From: ERP Software<erp@".$_SERVER['SERVER_NAME']."> \r\n";
            $headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($to,$subject,$txt,$headers);


            unset($_SESSION['initiate_hrm_stationary_purchase']);
            ?> <meta http-equiv="refresh" content="0;<?=$page;?>"> <?php }
        ?>
                    <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Purchase?");' name="deleted" id="deleted" class="btn btn-danger" style="float: left">Delete the Stationary Purchase </button>
                    <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Purchase?");' name="confirmsave" id="confirmsave" class="btn btn-success" style="float: right">Confirm and Finish Requisition </button>
</form><br>
<?php } ?>
<?php require_once 'footer_content.php' ?>
<script>
    $(document).ready(function() {
        $('#or_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
<script>
    $(function(){
        $('#price, #qty').keyup(function(){
            var price = parseFloat($('#price').val()) || 0;
            var qty = parseFloat($('#qty').val()) || 0;
            $('#sum').val((price * qty).toFixed(2));
        });
    });
</script>
