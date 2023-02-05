 <?php
require_once 'support_file.php';
$title="Daily Attendance";

$now=time();
$unique='id';
$unique_field='name';
$table="hrm_attendance_info";
$page="ims_daily_attendance.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
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
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

 $currentmonth=date('m');
 $currentyear=date('Y');
?>



<?php require_once 'header_content.php'; ?>
 <style>
     #namedd2 { text-align:center; font-size:12px; background-color:#8B2323}
     #namedd1 { text-align:center; font-size:12px; background-color:#458B00}
     #namedd30 {text-align:center; font-size:12px; background-color:#FFF68F}
 </style>
<?php require_once 'body_content.php'; ?>



                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <br />

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px" target="_blank" action="ims_attendance_view.php">
                                    <?require_once 'support_html.php';?>
                                    

                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Attendance Day<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="f_date" style="width:20%; font-size: 11px"  value="<?=$_POST[f_date]?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                                        <input type="text" id="t_date" style="width:20%; font-size: 11px; margin-left: 50px"  value="<?=$_POST[t_date]?>" required   name="t_date" class="form-control col-md-7 col-xs-12" >
                                            <button type="submit" name="Search" id="Search"  style="margin-left: 50px" class="btn btn-primary">Search Attendance</button>
                                        </div></div>


                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">

                                            </div></div>                                                                                        



                                </form>

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">



                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Attendance Day<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="f_date" style="width:20%; font-size: 11px"  value="<?=$_POST[f_date]?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                                            <button type="submit" name="Search" id="Search"  style="margin-left: 50px" class="btn btn-primary">Search Attendance</button>
                                        </div></div>

                                </form>
                                </div>
                                </div>
                                </div>

                    <?php

                        $working_day=find_a_field('ims_date','ims_date','month='.$currentmonth.' and year='.$currentyear.'');

                    ?>
                    <!-------------------list view ------------------------->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">


                            <div class="x_content">

                        <table style="width:100%; font-size: 11px"  class="table table-striped table-bordered">
                        <tr>
                            <th>SL</th>
                            <th>SO Code</th>
                            <th>SO Name</th>
                            <th style="width: 5%">Attendance</th>


                        </tr>

                                <? 	$res=mysql_query('select p.*,a.* 
                                
                                from 
                                personnel_basic_info p ,
                                hrm_attendance_info a
                                where 
                                
                                a.PBI_ID=p.PBI_ID and 
                                 a.working_day="'.$working_day.'" group by a.PBI_ID order by p.sl ');
                                while($attdata=mysql_fetch_object($res)){?>
                                    <tr>
                                    <td><?=$i=$i+1;?></td>
                                    <td><?=$attdata->PBI_ID_UNIQUE;?></td>
                                    <td><?=$attdata->PBI_NAME;?></td>
                                    <?php
                                    if ($attdata->attendance == L){
                                        echo "<td id='namedd1' style=\"width: 5%; text-align: center\"><font color='#FFFFFF'>". $attdata->attendance."</font></td>";
                                    }
                                    else if ($attdata->attendance == A){
                                        echo "<td id='namedd2' style=\"width: 5%; text-align: center\"><font color='#FFFFFF'>". $attdata->attendance ."</font></td>";
                                    }

                                    else if ($attdata->attendance == H){
                                        echo "<td id='namedd30' style=\"width: 5%; text-align: center\"><font color='#'>". $attdata->attendance ."</font></td>";
                                    }
                                    else if ($attdata->attendance == P){
                                        echo "<td id='namedd' style=\"width: 5%; text-align: center\"><font color=''>". $attdata->attendance ."</font></td>";
                                    }
                                    else if ($attdata->attendance == 0){
                                        echo "<td id='namedd' style=\"width: 5%; text-align: center\"><font color=''>". $attdata->attendance ."</font></td>";
                                    }
                                    ?>


                                    </tr><?php } ?></table>

                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->

                    <!---page content----->


                
        
<?php require_once 'footer_content.php' ?>