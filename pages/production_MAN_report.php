 <?php
require_once 'support_file.php';
$title="MAN List";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='MAN_ID';
$unique_field='man_date';
$table="MAN_master";
$table_details="MAN_details";
$current_status=getSVALUE("MAN_master", "status", " where MAN_ID='".$_GET['MAN_ID']."'");
$required_status="UNCHECKED";
$page="production_MAN_report.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if(isset($_POST['reprocess']))
    {   $_POST['status']='MANUAL';
        $crud->update($table);
        $_SESSION[initiate_man_documents]=$$unique;
        $type=1;
        echo "<script>self.opener.location = 'Incoming_Material_Received.php'; self.blur(); </script>";
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
{
    mysql_query("Delete from ".$table_details." where MAN_ID='".$_GET['MAN_ID']."'");
    mysql_query("Delete from ".$table." where MAN_ID='".$_GET['MAN_ID']."'");
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}


?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 250,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>

 <?php if(!isset($_GET[$unique])){ ?>
     <!-------------------list view ------------------------->
     <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel">
             <div class="x_title">
                 <h2>List of <?=$title;?></h2>
                 <div class="clearfix"></div>
             </div>

             <div class="x_content">
             <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;font-size:12px">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="">MAN ID</th>
                     <th style="">MAN Date</th>
                     <th style="">From CMU</th>
                     <th style="">Remarks</th>
                     <th style="">Delivery<br />Challan</th>
                     <th style="text-align:center">VAT<br />Challan</th>
                     <th style="">Entry By</th>
                      <th style="">Status</th>                                        
                     </tr>
                     </thead>
                      <tbody>
                 <? 	$res=mysql_query('select r.'.$unique.',r.'.$unique.' as MAN_ID,r.'.$unique_field.',w.warehouse_name,r.delivary_challan,r.VAT_challan,
				 (SELECT concat(p2.PBI_NAME) FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Entry_by,r.remarks as Remarks,r.cehck_at,r.status
				  from '.$table.' r,
				  warehouse w
				  WHERE 
				  r.warehouse_id=w.warehouse_id and 
				  r.entry_by='.$_SESSION['PBI_ID'].' 		  
				   order by r.'.$unique.' DESC');
				   while($req=mysql_fetch_object($res)){
				   
				   ?>
                   <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                                <td><?=$i=$i+1;?></td>
                                <td><?=$req->$unique;?></td>
                                <td><?=$req->$unique_field;?></td>
                                <td><?=$req->warehouse_name;?></td>
                                <td><?=$req->Remarks;?></td>
                                <td><?=$req->delivary_challan;?></td>
                                <td><?=$req->VAT_challan;?></td>
                                <td><?=$req->Entry_by;?></td>
                                <td><?=$req->status;?></td>
                                </tr>
                                <?php } ?>
                                
                                </tbody>
                                </table>
                
             </div>

         </div></div>
     <!-------------------End of  List View --------------------->
 <?php } ?>
<?php if(isset($_GET[$unique])){ ?>


                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right">
                                        <!--a target="_new" class="btn btn-sm btn-default"  href="user_permission2.php">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Uer Permission (SUB)</span>
                                        </a-->
                                    </div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    

                                    
                                   <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="">MAN ID</th>
                     <th style="">Item Code</th>
                     <th style="">Item Description</th>
                     <th style="">UOM</th>
                     <th style="">Qty</th>
                     <th style="">PO NO</th>
                     </tr>
                     </thead>
                      <tbody>
                     <? 	$res=mysql_query('Select td.*,i.* from '.$table_details.' td,
				 item_info i
				  where td.item_id=i.item_id and 				  
				  td.'.$unique.'="'.$_GET[$unique].'"');
				   while($req_data=mysql_fetch_object($res)){
				   ?>
                   <tr>
                                <td><?=$i=$i+1;?></td>
                                <td><?=$req_data->$unique;?></td>
                                <td><?=$req_data->item_id;?></td>
                                <td><?=$req_data->item_name;?></td>
                                <td><?=$req_data->unit_name;?></td>
                                <td><?=$req_data->qty;?></td>
                                <td><?=$req_data->po_no;?></td>
                                </tr>
                                <?php } ?>
                                
                                </tbody>
                                </table>
                                
                                <?php
                                if(isset($_POST[update])){
								mysql_query("Update ".$table." SET edit_by='".$_SESSION[PBI_ID]."',edit_at='$todayss' where oi_no=".$_GET[$unique].""); 
								echo "<script>self.opener.location = '$page'; self.blur(); </script>";
                                echo "<script>window.close(); </script>";
								}
								?>

                                    <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h5 style="text-align:center; color:red; font-weight:bold"><i>This requisition has been Authorised!!</i></h5>';} else { ?>
                                        <table style="width:100%;font-size:12px">
                                            <td>
                                                <div class="form-group">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <button type="submit" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-processing the MAN</button>
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