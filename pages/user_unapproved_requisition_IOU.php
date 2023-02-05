<?php
require_once 'support_file.php';
$title="Unapproved IOU List";

$now=time();
$unique='id';
$unique_field='req_date';
$table="user_IOU";
$page="user_unapproved_requisition_IOU.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if(isset($_POST[$unique_field]))

//for insert..................................
    {    $$unique = $_POST[$unique];
        if(isset($_POST['record']))
        {
            $sd=$_POST[s_date];
            $ed=$_POST[e_date];
            $_POST[s_date]=date('Y-m-d' , strtotime($sd));
            $_POST[e_date]=date('Y-m-d' , strtotime($ed));
            $_POST[total_days]=		"0.5";
            $_POST[PBI_ID]=$_SESSION[PBI_ID];
            $_POST[leave_status] = "Waiting";
            $_POST[entry_at] = date('Y-m-d H:i:s');
            $_POST[half_or_full]='Half';
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
?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=700,height=500,left = 280,top = -1");}
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
                <form action="" method="post">
                <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                    <thead>
                    <tr>
                        <th style="width: 2%">#</th>
                        <th style="">IOU ID</th>
                        <th style="">IOU Date</th>
                        <th style="">Name</th>
                        <th style="">Designation</th>
                        <th style="">Purpose of IOU</th>
                        <th style="">Amount in BD</th>
                        <th style="">Recommended By</th>
                        <th style="">Authorized By</th>
                        <th style="">Option</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? 	$res='select i.*, p.*,d.*                  
                    from user_IOU i,
                    personnel_basic_info p,
                    designation d
                    where 
                    i.PBI_ID=p.PBI_ID and i.PBI_ID='.$_SESSION[PBI_ID].' and 
                    p.PBI_DESIGNATION=d.DESG_ID
                    ';
                    $rquery=mysqli_query($conn, $res);
                    while($leavedata=mysqli_fetch_object($rquery)){

                        $id=$leavedata->recommended




                        ?>
                        <tr>
                            <td style="vertical-align: middle"><?=$i=$i+1;?></td>
                            <td style="vertical-align: middle"><?=$leavedata->id;?></td>
                            <td style="vertical-align: middle"><?=$leavedata->req_date;?></td>
                            <td style="vertical-align: middle"><?=$leavedata->PBI_NAME;?></td>
                            <td style="vertical-align: middle"><?=$leavedata->DESG_DESC;?></td>
                            <td style="vertical-align: middle"><?=$leavedata->purpose;?></td>
                            <td style="text-align: right; vertical-align: middle"><?=$leavedata->amount;?></td>
                            <td style="vertical-align: middle"><?php if($leavedata->recommended_date>0){?>
                                    <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$leavedata->recommended_by.'');?> <br>
                                    <?=$leavedata->recommended_date;?>
                                <?php } else { echo 'PENDING';} ?>
                            </td>
                            <td style="vertical-align: middle"><?php if($leavedata->authorized_date>0){?>
                                    <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$leavedata->authorized_by.'');?> <br>
                                    <?=$leavedata->authorized_date;?>
                                <?php } else { echo 'PENDING';} ?>
                            </td>
                            <td align="center"><input type="submit" onclick='return window.confirm("Are you confirm to Updated?");' class="btn btn-primary" style="font-size: 11px" name="recommended<?=$leavedata->id?>" id="recommended<?=$leavedata->id?>" value="Recommended"></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </form>
            </div>
        </div></div>
    <!-------------------End of  List View --------------------->
<?php } ?>
<!---page content----->




<?php require_once 'footer_content.php' ?>
<script>
    $(document).ready(function() {
        $('#s_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#e_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>