 <?php
require_once 'support_file.php';
$title="Un-Approved Food Requisition List";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='oi_no';
$unique_field='oi_date';
$table="warehouse_other_issue";
$table_details="warehouse_other_issue_detail";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="PENDING";
$page="hrm_unapproved_requisition_food_beverage.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

if(prevent_multi_submit()){
    if(isset($_POST['Return'])) {
    $_POST['status']='RETURNED';
	$_POST['return_comments']=$_POST['return_comments'];
	$_POST['recommended_date']=$todayss;	
    $crud->update($unique);
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
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
 if(isset($_POST['viewreport'])):
$res = 'select r.'.$unique.',r.'.$unique.' as Req_No,r.'.$unique_field.' as Req_Date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.issued_to and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,r.oi_subject as Remarks,r.Priority,(select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_person) as authorised_person,r.status as status
				  from '.$table.' r
				  WHERE r.recommended_by='.$_SESSION['PBI_ID'].' and
				  r.req_category in ("1500010000") and 	
				  r.oi_date between "'.$_POST['f_date'].'" and "'.$_POST['t_date'].'"		  
				   order by r.'.$unique.' DESC';
 else:
     $res = 'select r.'.$unique.',r.'.$unique.' as Req_No,r.'.$unique_field.' as Req_Date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.issued_to and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,r.oi_subject as Remarks,r.Priority,(select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_person) as authorised_person,r.status as status
				  from '.$table.' r
				  WHERE r.recommended_by='.$_SESSION['PBI_ID'].' and
				  r.req_category in ("1500010000") and 	
				  status="'.$required_status.'"				  
				   order by r.'.$unique.' DESC';
     endif;
     $sql = 'Select td.* from '.$table_details.' td 
				  where 				  
				  td.oi_no='.$_GET[$unique];
     $res = mysqli_query($conn, $sql);
     while ($data=mysqli_fetch_object($res)){
        
     }
?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 200,top = -1");}
    </script>
 <style>
     .btn-font-size{
         font-size: 12px;
     }
 </style>
<?php require_once 'body_content.php'; ?>
<?php if(isset($_GET[$unique])){ ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="width: 2%; vertical-align: middle">#</th>
                            <th style="text-align:center; vertical-align: middle">Serving<br />Date</th>
                            <th style="text-align:center; vertical-align: middle">Serving<br />Time</th>
                            <th style="text-align:center; vertical-align: middle">Serving<br />Place</th>
                            <th style="text-align:center; vertical-align: middle">Purpose of Requisition</th>
                            <th style="text-align:center; vertical-align: middle">Person number to be Served</th>
                            <th style="text-align:center; vertical-align: middle">Preferred Restaurant/Shop</th>
                            <th style="text-align:center; vertical-align: middle">Preferred Item</th>
                            <th style="text-align:center; vertical-align: middle">Price</th>
                            <th style="text-align:center; vertical-align: middle">Number of Item</th>
                            <th style="vertical-align: middle; text-align: center">Already Taken<br /> (Current Year)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? $res=mysqli_query($conn, $sql); while($req_data=mysqli_fetch_object($res)){?>
                            <tr>
                                <td style="vertical-align: middle"><?=$i=$i+1;?></td>
                                <td style="vertical-align: middle"><?=$req_data->serving_date;?></td>
                                <td style="vertical-align: middle"><?=$req_data->serving_time;?></td>
                                <td style="vertical-align: middle"><?=$req_data->serving_place;?></td>
                                <td style="vertical-align: middle"><?=$req_data->requisition_purpose;?></td>
                                <td style="vertical-align: middle"><?=$req_data->served_person;?></td>
                                <td style="vertical-align: middle"><?=$req_data->restaurent;?></td>
                                <td style="vertical-align: middle"><?=$req_data->item_name;?>,<?=$req_data->item_details;?></td>
                                <td style="text-align:center; vertical-align: middle"><?=$req_data->rate;?></td>
                                <td style="vertical-align: middle"><input type="text" name="recemmended_qty_<?=$req_data->id;?>" id="recemmended_qty_<?=$req_data->id;?>" value="<?=$req_data->qty;?>" style="width:80px" /></td>
                                <td style="text-align:center; vertical-align: middle"><?=$taken=find_a_field("".$table_details."", "SUM(qty)", "oi_date between '$dfrom' and '$dto' and  oi_no='".$_GET[$unique]."' and item_id=".$req_data->item_id."")-$req_data->qty;?> <?=$req_data->unit_name;?>'s</td>
                                </tr>
                                <?php } ?>
                                </tbody>
                                </table>
                                     <?php if($current_status!=$required_status){ echo '<h5 style="text-align:center; color:red; font-weight:bold"><i>This requisition has been recommended!!</i></h5>';} else { ?>
                                     <table style="width:100%;font-size:11px">
                                     <tr><td> <div class="form-group">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                           <input type="text" id="return_comments"  name="return_comments" class="form-control col-md-7 col-xs-12"  style="width:166px;font-size: 11px" placeholder="return remarks........" >
                                             </div></div></td><td></td></tr>
                                          <tr>
                                            <td><button type="submit" style="float: left; margin-left: 1%" name="Return" id="Return" class="btn btn-danger btn-font-size" onclick='return window.confirm("Are you confirm to Return?");'>Returned the Requisition</button>
                                            </td>
                                            <td><button type="submit" style="float: right; margin-right: 1%" onclick='return window.confirm("Are you confirm to Recommended the Requisition?");' name="recommend" id="recommend" class="btn btn-success btn-font-size">Recommended & Forward</button
                                            </td>
                                          </tr>
                                     </table>
                                     <?php } ?>
                                </form>
                                </div>
                                </div>
                                </div>
<?php } ?>
 <?php if(!isset($_GET[$unique])): ?>
     <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
         <table align="center" style="width: 50%;">
             <tr><td>
                     <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?=($_POST['f_date']!='')? $_POST['f_date'] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                 <td style="width:10px; text-align:center"> -</td>
                 <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?=($_POST['t_date']!='')? $_POST['t_date'] : date('Y-m-d') ?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                 <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Data</button></td>
             </tr></table>
     </form>
     <?=$crud->report_templates_with_status($res,$title);?>
 <?php endif;  ?>
 <?=$html->footer_content();?>