<?php
require_once 'support_file.php';
$title="Stationary Purchase Report";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='or_no';
$unique_field='or_date';
$table="warehouse_other_receive";
$table_details="warehouse_other_receive_detail";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="UNCHECKED";
$page="hrm_stationary_purchase_report.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    // for re-processing data..................................

    if(isset($_POST['reprocess']))

    {   $_POST['status']='MANUAL';
        $crud->update($table);
        $_SESSION['initiate_hrm_stationary_purchase']=$_GET[$unique];
        $type=1;
        echo "<script>self.opener.location = 'hrm_stationary_purchase.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }


//for modify..................................
    if(isset($_POST['modify']))
    {
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        //echo $targeturl;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['Deleted']))
    {   $condition=$unique."=".$$unique;
        $crud->delete($condition);
        $crud = new crud($table_details);
        $condition = $unique . "=" . $$unique;
        $crud->delete_all($condition);
        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 250,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>
<?php if(!isset($_GET[$unique])){
if(isset($_POST[viewreport])){	
$res = 'select r.' . $unique . ',r.' . $unique . ' as PO_NO,r.' . $unique_field . ' as Purchased_Date,r.recommended_date,r.requisition_from,r.vendor_name,r.chalan_no,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Purchased_By,r.or_subject as Remarks,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.checked_by) as checked_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.approved_by) as authorised_person,r.status,r.approved_date
				  from ' . $table . ' r
				  WHERE r.or_date BETWEEN "'.$from_date.'" AND "'.$to_date.'"	  
				   order by r.' . $unique . ' DESC';}?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="text" id="f_date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if ($_POST[f_date]>0) echo $_POST[f_date]; else echo date('m')?>/01/<?=date('Y')?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="text" id="t_date" style="width:150px;font-size: 11px; height: 25px"  value="<?=$_POST[t_date]?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Stationary Purchase</button></td>
            </tr></table>
            
            
<?=$crud->report_templates_with_data($res,$title);?>
            
    <!-------------------list view ------------------------->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                    <thead>
                    <tr>
                        <th style="text-align:center">#</th>
                        <th style="text-align:center">PO No</th>
                        <th style="text-align:center">Purchase Date</th>
                        <th style="text-align:center">Remarks</th>
                        <th style="text-align:center">Vendor Name</th>
                        <th style="text-align:center">Chalan No</th>
                        <th style="text-align:center">Requisition From</th>
                        <th style="text-align:center">Purchased By</th>
                        <!--th style="text-align:center">Checked By</th-->
                        <th style="text-align:center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?
                   $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                   $to_date=date('Y-m-d' , strtotime($_POST[t_date]));
                    if(isset($_POST[viewreport])) {
                        $res = 'select r.' . $unique . ',r.' . $unique . ' as PO_NO,r.' . $unique_field . ' as Purchased_Date,r.recommended_date,r.requisition_from,r.vendor_name,r.chalan_no,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Purchased_By,r.or_subject as Remarks,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.checked_by) as checked_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.approved_by) as authorised_person,r.status,r.approved_date
				  from ' . $table . ' r
				  WHERE r.or_date BETWEEN "'.$from_date.'" AND "'.$to_date.'"	  
				   order by r.' . $unique . ' DESC';
                        $spquery = mysqli_query($conn, $res);
                    }
                    while($req=mysqli_fetch_object($spquery)){

                        ?>
                        <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                            <td><?=$i=$i+1;?></td>
                            <td><?=$req->$unique;?></td>
                            <td><?=$req->Purchased_Date;?></td>
                            <td><?=$req->Remarks;?></td>
                            <td><?=$req->vendor_name;?></td>
                            <td><?=$req->chalan_no;?></td>
                            <td><?=$req->requisition_from;?></td>
                            <td><?=$req->Purchased_By;?></td>
                            <!--td><?=$req->checked_by;?></td-->
                            <td><?=$req->status;?></td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>

            </div>

        </div></div>
    </form>
    <!-------------------End of  List View --------------------->
<?php } ?>
<?php if(isset($_GET[$unique])){ ?>


    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                <br />

                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>



                    <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                        <thead>
                        <tr>
                            <th style="width: 2%">#</th>
                            <th style="text-align: center">Item Code</th>
                            <th style="">Item Description</th>
                            <th style="text-align: center">Unit</th>
                            <th style="text-align: center">Qty</th>
                            <th style="text-align: center">Rate</th>
                            <th style="text-align: center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if($_GET[deleteid]){

                            mysql_query("Delete From ".$table_details." where ".$unique."=".$$unique." and id='$_GET[id]'"); ?>
                            <meta http-equiv="refresh" content="0;<?=$page;?>?<?=$unique;?>=<?php echo $_GET[$unique]; ?>">
                        <?php } ?>
                        <? 	$res=mysql_query('Select td.*,i.* from '.$table_details.' td,
				 item_info i
				  where td.item_id=i.item_id and 				  
				  td.'.$unique.'='.$_GET[$unique].'');
                        while($req_data=mysql_fetch_object($res)){



                            $qty=$_POST['qty_'.$req_data->id];
                            if(isset($_POST[update])){

                                mysql_query("Update ".$table_details." SET request_qty='$qty' where ".$unique."=".$_GET[$unique]." and id=".$req_data->id."");

                            }
                            ?>
                            <tr>
                                <td><?=$i=$i+1;?></td>
                                <td style="text-align: center"><?=$req_data->item_id;?></td>
                                <td><?=$req_data->item_name;?></td>
                                <td style="text-align: center"><?=$req_data->unit_name;?></td>
                                <td style="text-align: center"><?=$req_data->qty;?></td>
                                <td style="text-align: right"><?=$req_data->rate;?></td>
                                <td style="text-align: right"><?=$req_data->amount;?></td>

                            </tr>
                            <?php     $total_amount=$total_amount+$req_data->amount; } ?>

                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="6" style="text-align: right">Total Amount = </td><td style="text-align: right"><?=number_format($total_amount,2)?></td>
                        </tr>

                    </table>

                    <?php
                    if(isset($_POST[update])){
                        mysql_query("Update ".$table." SET edit_by='".$_SESSION[PBI_ID]."',edit_at='$todayss' where oi_no=".$_GET[$unique]."");
                        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
                        echo "<script>window.close(); </script>";
                    }
                    ?>

                    <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This stationary purchased has been checked!!</i></h6>';} else { ?>
                        <table style="width:100%;font-size:12px">
                            <td>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <button type="submit" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-processing the Requisition</button>
                                    </div></div></td>

                            <td style="float: right">
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted All Data</button>
                                    </div></div></td>



                            </tr></table>
                    <?php } ?>




                </form>
            </div>
        </div>
    </div>
<?php } ?>




<?php require_once 'footer_content.php' ?>