 <?php
require_once 'support_file.php';
$title="Get IMS Data";
$now=time();
$unique='ims_no';
$unique_field='name';
$table="ims_master";
$page="get_ims_data.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$currentmonth=date('m');
$currentyear=date('Y');

if(prevent_multi_submit()){

//for modify..................................
    if(isset($_POST['modify']))
    {
        $_POST['status']='COMPLETED';
        $crud->update($unique);
        $type=1;
        //echo $targeturl;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
	$del="delete from ims_details where ims_no='".$$unique."'";
	$del_query=mysqli_query($conn, $del);
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
         {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=730,height=600,left = 383,top = -1");}
     </script>
     <style>
         input[type=text]{
             font-size: 11px;
             height: 25px;
         }
     </style>
 </head>
<?php require_once 'body_content.php'; ?>


 <?php if(isset($_GET[$unique])){ ?>
                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    <table style="width:100%; font-size: 11px"  class="table table-striped table-bordered">
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>IMS Qty</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                    </tr>

                                    <?php
                                    $res="select * from item_info where sub_group_id in ('200010000') and exim_status not in ('Export') and brand_category not in ('Rice') and status in ('Active') order by serial";
$iquery=mysqli_query($conn, $res);
while($item=mysqli_fetch_array($iquery)){
                                    $imsdetails=find_all_field('ims_details','','item_id="'.$item[item_id].'" and ims_no='.$_GET[$unique] );
                                    $id=$item[item_id];
                                    ?>
                                    <tr>
                                    <td><?=$i=$i+1;?></td>
                                    <td><?=$item[item_name];?></td>
                                    <td style="text-align: center"><?=$imsdetails->total_unit_ims;?></td>
                                    <td style="text-align: right"><?=$imsdetails->unit_price;?></td>
                                    <td style="text-align: right"><?=$imsdetails->total_amt_ims;?></td>
                                    </tr>
                                    <?php $totalIMS=$totalIMS+$imsdetails->total_amt_ims;} ?>
                                        <tr>
                                        <td colspan="4" align="right"><strong>IMS TOTAL = </strong></td>
                                            <td align="right"><strong><?=number_format($totalIMS,2);?></strong></td>
                                        </tr>
                                    </table>
                                        <?php
                                        $GET_status=find_a_field($table,'status','ims_no='.$_GET[$unique]);
                                        if($GET_status!=='COMPLETED'){  ?>
                                            <p>
                                             <button style="float: left" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>DELETED</button>
                                             <button style="float: right" type="submit" name="modify" id="modify" class="btn btn-success" onclick='return window.confirm("Are you confirm?");'>CHECKED & FINISHED</button>
                                             <? } else {echo '<h5 style="text-align: center; color: black; font-style: italic; background-color: red">This IMS Data has been Verified!!</h5>';}?>
                                           </p>
                                </form>
                                </div>
                                </div>
                                </div>
                            <?php } ?>

                    <?php if(!isset($_GET[$unique])){ ?>
                    <!-------------------list view ------------------------->
                   <!--form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" target="_new" action="ims_report_view.php">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                                <table align="center" style="width: 50%; font-size: 11px">
                                    <tr><td>
                                            <input type="text" id="f_date" style="width:150px"  value="<?=$_POST[f_date]?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                                        <td style="width:10px; text-align:center"> -</td>
                                        <td><input type="text" id="t_date" style="width:150px"  value="<?=$_POST[t_date]?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                                        <td style="padding:10px"><button type="submit" name="viewreport" style="height: 30px; font-size: 11px" class="btn btn-primary">GET MIS Report</button></td>

                                        <td align="center" colspan="2" style="padding:10px"><a href="get_ims_data_all.php" target="_blank" style="height: 30px; font-size: 11px" class="btn btn-success">View All Data</a></td>
                                        <td align="center" colspan="2" style="padding:10px"><a href="get_ims_data_manual.php" target="_blank"  style="height: 30px; font-size: 11px" class="btn btn-danger">View Incomplete Data</a></td>
                                    </tr>
                                  </table></div></div></form-->


 <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post"  >
     <?php
     if(isset($_POST[showtoday])) {
         $ims_date = $from_date=date('Y-m-d' , strtotime($_POST[nam_date]));
         $today=$ims_date;
     } else {
         $ims_date=find_a_field('ims_date','ims_date','month='.$currentmonth.' and year='.$currentyear.'');
         $today=date('Y-m-d');
     }
     ?>

     <table align="center" style="width: 50%; font-size: 11px">
         <tr><td>
                 <input type="text" id="nam_date" style="width:150px"  value="<?=$_POST[nam_date]?>" required   name="nam_date" class="form-control col-md-7 col-xs-12" >
             <td style="width:10px; text-align:center"> -</td>
             <td style="padding:10px"><button type="submit" name="showtoday" style="height: 30px; font-size: 11px" class="btn btn-primary">View Status</button></td>
             <td style="padding:10px; float: right"><a href="get_ims_data_all.php"  class="btn btn-primary" target="_blank">View All Data</a></td>
         </tr></table>
     <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel">
              <div class="x_content">
                                <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                                    <thead><tr style="background-color: bisque">
                                        <th style="vertical-align: middle">#</th>
                                        <th style="vertical-align: middle">In-charge person</th>
                                        <th style="text-align: center;vertical-align: middle">No of SO</th>
                                        <th style="text-align: center;vertical-align: middle">Comission<br>DB</th>
                                        <th style="text-align: center;vertical-align: middle">IMS Entry</th>
                                        <th style="text-align: center;vertical-align: middle">Attendance Entry</th>
                                        <th style="text-align: center;vertical-align: middle">Lifting Entry <br>(MTD)</th>
                                        <th style="text-align: center;vertical-align: middle">IMS Monthly <br>Target</r></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $tfrom=date('Y').'-'.date('m').'-'.'01';
                                    $tto=date('Y').'-'.date('m').'-'.'31';
                                    $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                                    $to_date=date('Y-m-d' , strtotime($_POST[t_date]));
                                    $res="SELECT p.*,des.*,
(SELECT COUNT(PBI_ID) from personnel_basic_info where so_type='SO' and PBI_JOB_STATUS in ('In Service') and tsm=p.PBI_ID)  as COUNT_SO,
(SELECT COUNT(PBI_ID) from personnel_basic_info where so_type='CD' and PBI_JOB_STATUS in ('In Service') and tsm=p.PBI_ID)  as COUNT_CD

FROM 
                                    personnel_basic_info p,
                                    designation des
                                    where 
                                    p.PBI_JOB_STATUS in ('In Service') and 
                                    p.PBI_DESIGNATION=des.DESG_ID and 
                                    p.PBI_DESIGNATION in ('56','57','102')";
                                        $query1=mysqli_query($conn, $res);
                                        while($data=mysqli_fetch_object($query1)){
                                            $ims_SO=find_a_field('ims_master','COUNT(PBI_ID)','TSM_PBI_ID='.$data->PBI_ID.' and order_date="'.$ims_date.'"');
                                            $total_ims_SO=find_a_field('ims_master','COUNT(PBI_ID)','order_date="'.$ims_date.'"');
                                            $COUNT_SO=$data->COUNT_SO;
                                            $COUNT_CD=$data->COUNT_CD;
                                            $SO=$COUNT_SO+$COUNT_CD;

                                            $attendance=find_a_field('hrm_attendance_info','COUNT(PBI_ID)','TSM_PBI_ID='.$data->PBI_ID.' and working_day="'.$today.'"');
                                            $Totalattendance=find_a_field('hrm_attendance_info','COUNT(PBI_ID)','working_day="'.$today.'"');
                                            $ims_monthly_target=find_a_field('ims_monthly_target_master','COUNT(PBI_ID)','TSM_PBI_ID='.$data->PBI_ID.' and status not in ("CHECKED")');
                                            ?>
                                            <tr  onclick="DoNavPOPUPS('<?=$data->ims_no?>', 'TEST!?', 900, 600)">
                                                <td><?=$i=$i+1;?></td>
                                                <td><?=$data->PBI_ID;?> # <?=$data->PBI_ID_UNIQUE;?> # <?=$data->PBI_NAME;?></td>
                                                <td style="text-align: center"><?=$COUNT_SO;?></td>
                                                <td style="text-align: center"><?=$COUNT_CD;?></td>
                                                <?php
                                                $imstoday=(($ims_SO/$SO)*100);
                                                $attendanceGET=(($attendance/$SO)*100);
                                                $TargetGET=(($ims_monthly_target/$SO)*100);
                                                ?>
                                                <td style="text-align: center; background-color: <?php if($imstoday>'99') { echo 'green; color:white'; } else { echo 'red; color:white';} ?>"><? if ($imstoday>0) echo number_format($imstoday,2).' %'; else echo ''; ?></td>
                                                <td style="text-align: center; background-color: <?php if($attendanceGET>'99') { echo 'green; color:white'; } else { echo 'red; color:white';} ?>"><? if ($attendanceGET>0) echo number_format($attendanceGET,2).' %'; else echo ''; ?></td>
                                                <td style="text-align: right"><?=$lefting=find_a_field('ims_stock_master','COUNT(ims_no)','entry_by='.$data->PBI_ID.' and ims_date between "'.$tfrom.'" and "'.$tto.'"');?></td>
                                                <td style="text-align: center; background-color: <?php if($TargetGET>'99') { echo 'green; color:white'; } else { echo 'red; color:white';} ?>"><? if ($TargetGET>0) echo number_format($TargetGET,2).' %'; else echo ''; ?></td>
                                            </tr>
                                        <?php
                                        $totalSO=$totalSO+$COUNT_SO;
                                        $totalCD=$totalCD+$COUNT_CD;
                                        $tlefting=$tlefting+$lefting;
                                        }
                                        $TIMS=($total_ims_SO/$totalSO)*100;
                                        $tattendance=($Totalattendance/$totalSO)*100;
                                         $ttarget=($TargetGET/$totalSO)*100;
                                        ?>
                                    <tr><th colspan="2" style="text-align: right">Total</th>
                                        <th style="text-align: center"><?=$totalSO;?></th>
                                        <th style="text-align: center"><?=$totalCD;?></th>
                                        <th style="text-align: center; background-color: <?php if($TIMS>'99') { echo 'green; color:white'; } else { echo 'red; color:white';} ?>"><?=number_format($TIMS,2);?>%</th>
                                        <th style="text-align: center; background-color: <?php if($tattendance>'99') { echo 'green; color:white'; } else { echo 'red; color:white';} ?>"><?=number_format($tattendance,2);?>%</th>
                                        <td style="text-align: right"><?=$tlefting;?></td>
                                        <td style="text-align: right"><?=number_format($ttarget,2);?></td>
                                    </tr>
                                    </tbody>
                                </table>
                    <?php } ?>
                  <!---page content-----></div></div></div></form>


                
        
<?php require_once 'footer_content.php' ?>

 <script>
     $(document).ready(function() {
         $('#nam_date').daterangepicker({
             singleDatePicker: true,
             calender_style: "picker_4",
         }, function(start, end, label) {
             console.log(start.toISOString(), end.toISOString(), label);
         });
     });

 </script>
