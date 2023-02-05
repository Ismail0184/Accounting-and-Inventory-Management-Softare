 <?php
require_once 'support_file.php';
$title="Travel Expenses Claim Report";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='trvClaim_id';
$unique_field='application_date';
$table="travel_application_claim_master";
$table_details="travel_application_claim_details";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="PENDING";
$page="hrm_requisition_travel_exp_claim_report.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
  
  
    // for re-processing data..................................

    if(isset($_POST['reprocess']))

    {   $_POST['status']='MANUAL';
        $crud->update($table);
        $_SESSION['initiate_travel_exp_claim_requisition']=$_GET[$unique];
        $type=1;
        echo "<script>self.opener.location = 'hrm_requisition_travel_exp_claim.php'; self.blur(); </script>";
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
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 200,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>

 <?php if(!isset($_GET[$unique])){ ?>
     <!-------------------list view ------------------------->
     <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel">
             <div class="x_title">
                 <h2><?=$title;?></h2>
                 <div class="clearfix"></div>
             </div>

             <div class="x_content">
             <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                   <thead>
                   <tr>
                       <th style="width: 2%">#</th>
                       <th style="">Req. No</th>
                       <th style="">Req. Date</th>
                       <th style="">Remarks</th>
                       <th style="">Priority</th>
                       <th style="text-align:center">Recommended <br />By</th>
                       <th style="">Authorised Person</th>
                       <th style="">Approved At</th>
                       <th style="">Status</th>
                   </tr>
                     </thead>
                      <tbody>
                      <? 	$res=mysql_query('select r.'.$unique.',r.'.$unique.' as Req_No,r.'.$unique_field.' as Req_Date,r.approved_date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,r.travel_purpose as Remarks,r.Priority,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.approved_by) as recommended_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_person) as authorised_person,r.status,r.authorised_date
				  from '.$table.' r
				  WHERE r.PBI_ID='.$_SESSION['PBI_ID'].' 	  
				   order by r.'.$unique.' DESC');
                      while($req=mysql_fetch_object($res)){

                          ?>
                   <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                       <td><?=$i=$i+1;?></td>
                       <td><?=$req->$unique;?></td>
                       <td><?=$req->Req_Date;?></td>
                       <td><?=$req->Remarks;?></td>
                       <td><?=$req->Priority;?></td>
                       <td><?=$req->recommended_by;?></td>
                       <td><?=$req->authorised_person;?></td>
                       <td><?=$req->authorised_date;?></td>
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
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />

                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>



                    <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                        <thead>
                        <tr>
                        <tr>
                            <th style="text-align:center">Date</th>
                            <th style="text-align:center">Place/location<br />(from - to)</th>
                            <th style="text-align:center">Mode of Transport <br /> (Details - Cost)</th>
                            <th style="text-align:center">Lodging Expense <br /> (Details - Cost)</th>
                            <th style="text-align:center">Breakfast</th>
                            <th style="text-align:center">Lunch</th>
                            <th style="text-align:center">Dinner</th>
                            <th style="text-align:center">Total</th>
                            <th style="text-align:center">Deleted ?</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if($_GET[deleteid]){

                            mysql_query("Delete From ".$table_details." where ".$unique."=".$$unique." and id='$_GET[id]'"); ?>
                            <meta http-equiv="refresh" content="0;<?=$page;?>?<?=$unique;?>=<?php echo $_GET[$unique]; ?>">
                        <?php } ?>
                        <? 	$res=mysql_query('Select td.* from '.$table_details.' td
				  where 			  
				  td.'.$unique.'='.$_GET[$unique].'');
                        while($req_data=mysql_fetch_object($res)){



                            $transport_fair_rqst=$_POST['transport_fair_rqst_'.$req_data->id];
                            $lodging_fair_rqst=$_POST['lodging_fair_rqst_'.$req_data->id];
                            $breakfast_rqst=$_POST['breakfast_rqst_'.$req_data->id];
                            $lunch_rqst=$_POST['lunch_rqst_'.$req_data->id];
                            $dinner_rqst=$_POST['dinner_rqst_'.$req_data->id];
                            $total_amount=$transport_fair_rqst+$lodging_fair_rqst+$breakfast_rqst+$lunch_rqst+$dinner_rqst;

                            if(isset($_POST[authorised])){

                                mysql_query("Update ".$table_details." SET transport_fair_rqst='".$transport_fair_rqst."',lodging_fair_rqst='".$lodging_fair_rqst."',breakfast_rqst='".$breakfast_rqst."',
 lunch_rqst='".$lunch_rqst."',dinner_rqst='".$dinner_rqst."',total_amount='".$total_amount."'
 where ".$unique."=".$_GET[$unique]." and id=".$req_data->id."");

                            }
                            ?>
                            <tr>

                                <td style="text-align: center"><?=$req_data->travel_date;?></td>
                                <td><?=$req_data->travel_from;?> - <?=$req_data->travel_to;?></td>
                                <td style="text-align: center"><?=$req_data->mode_of_transport;?> - <input type="text" name="transport_fair_rqst_<?=$req_data->id;?>" id="transport_fair_rqst_<?=$req_data->id;?>" value="<?=$req_data->transport_fair_rqst;?>" style="width: 50px"></td>
                                <td style="text-align: center"><?=$req_data->lodging_expense;?> - <input type="text" name="lodging_fair_rqst_<?=$req_data->id;?>" id="lodging_fair_rqst_<?=$req_data->id;?>" value="<?=$req_data->lodging_fair_rqst;?>" style="width: 50px"></td>

                                <td style="text-align: center"><input type="text" name="breakfast_rqst_<?=$req_data->id;?>" id="breakfast_rqst_<?=$req_data->id;?>" value="<?=$req_data->breakfast_rqst;?>" style="width:40px; text-align: center" /></td>
                                <td style="text-align: center"><input type="text" name="lunch_rqst_<?=$req_data->id;?>" id="lunch_rqst_<?=$req_data->id;?>" value="<?=$req_data->lunch_rqst;?>" style="width:40px; text-align: center" /></td>
                                <td style="text-align: center"><input type="text" name="dinner_rqst_<?=$req_data->id;?>" id="dinner_rqst_<?=$req_data->id;?>" value="<?=$req_data->dinner_rqst;?>" style="width:40px; text-align: center" /></td>
                                <td style="text-align: center"><?=$req_data->total_amount;?></td>
                                <td style="text-align:center">
                                    <?php if($current_status!=$required_status){ echo 'Done';} else { ?>
                                        <a onclick='return window.confirm("Mr. <?php echo $_SESSION['userfname']; ?>, Are you sure you want to Delete the Item?");' href="<?=$page?>?<?=$unique?>=<?php echo $_GET[$unique]; ?>&id=<?=$req_data->id;?>&deleteid=confrim" style="text-align:center"><img src="delete.png" style="margin-left:10px" height="20" width="20" /></a>
                                    <?php } ?>
                                </td>

                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>


                    <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h5 style="text-align:center; color:red; font-weight:bold"><i>This requisition has been Authorised!!</i></h5>';} else { ?>
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