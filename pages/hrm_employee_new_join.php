 <?php
require_once 'support_file.php';
$title="New Join Employee Report";

$now=time();
$unique='PBI_ID';
$unique_field='PBI_ID_UNIQUE';
$table="personnel_basic_info";
$page="hrm_employee_report.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
 $jobinfo="hrm_employee_job_info.php".'?'.$unique.'='.$$unique;
 $targeturlJOBINFO="<meta http-equiv='refresh' content='0;$jobinfo'>";


 $datefrom=date('Y-m-01');
 $dateto=date('Y-m-31');

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['goback']))
    {
        echo "$targeturlJOBINFO";
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
?>



 <?php require_once 'header_content.php'; ?>
 <script type="text/javascript">
     function DoNavPOPUP(lk)
     {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 230,top = 5");}
 </script>
 <?php require_once 'body_content.php'; ?>


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
                                        <th style="">Code</th>
                                        <th style="">Employee ID</th>
                                        <th style="">Name</th>
                                        <th style="">Designation</th>
                                        <th style="text-align:center">Department</th>
                                        <th style="">Joining Date</th>
                                        <th style="">Corp. Mobile</th>
                                        <th style="">Blood</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                <? 	$res=mysql_query('select p.'.$unique.',p.'.$unique.' as Code,p.'.$unique_field.' as Employee_ID,p.PBI_NAME as Name, (select DESG_SHORT_NAME from designation where DESG_ID=p.PBI_DESIGNATION) as designation,
                                 (select DEPT_DESC from department where DEPT_ID=p.PBI_DEPARTMENT) as Department,p.PBI_DOJ,p.PBI_EMAIL,p.ESSENTIAL_BLOOD_GROUP as blood,p.PBI_MOBILE as mobile
                                 from '.$table.' p where p.PBI_JOB_STATUS in ("In Service") and p.PBI_DOJ between "'.$datefrom.'" and "'.$dateto.'" order by p.'.$unique);
                                while($data=mysql_fetch_object($res)){?>
                                    <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$data->$unique;?>', 'TEST!?', 600, 700)">
                                <td><?=$i=$i+1;?></td>
                                <td><?=$data->$unique;?></td>
                                <td><?=$data->Employee_ID;?></td>
                                <td><?=$data->Name;?></td>
                                <td><?=$data->designation;?></td>
                                <td><?=$data->Department;?></td>
                                <td><?=$data->PBI_DOJ;?></td>
                                <td><?=$data->mobile;?></td>
                                <td><?=$data->blood;?></td>
                                </tr>
                                <?php } ?>

                                </tbody>
                                </table>
                            </div>

                        </div></div>

        
<?php require_once 'footer_content.php' ?>