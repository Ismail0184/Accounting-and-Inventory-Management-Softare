<?php
require_once 'support_file.php';
$title="Create Notice / Announcement";

$now=time();
$unique='ADMIN_ANN_DID';
$unique_field='ADMIN_ANN_SUBJECT';
$table="hrm_announcement";
$page="hrm_admin_announcement.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if(isset($_POST[$unique_field]))

//for insert..................................
    {    $$unique = $_POST[$unique];
        if(isset($_POST['record']))
        {


            $_POST[ADMIN_ANN_DATE]=date('Y-m-d');
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

<?php require_once 'body_content.php'; ?>



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

                <div class="form-group" style="display: none">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?=$unique?><span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="<?=$unique?>" style="width:100%"    name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                    </div></div>



                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Notice Subject: <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="ADMIN_ANN_SUBJECT" style="width:100%"  required   name="ADMIN_ANN_SUBJECT" value="<?=$ADMIN_ANN_SUBJECT;?>" class="form-control col-md-7 col-xs-12" >
                    </div></div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Notice Details: <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea style="width: 100%;  height: 150px" name="ADMIN_ANN_DETAILS" id="ADMIN_ANN_DETAILS" class="form-control col-md-7 col-xs-12"><?=$ADMIN_ANN_DETAILS;?></textarea>
                    </div></div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Email To: <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="email" style="width:100%"  required   name="email" value="<?=$email;?>" class="form-control col-md-7 col-xs-12" >
                    </div></div>



                <br>
                <?php if($_GET[$unique]){  ?>
                    <div class="form-group" style="margin-left:40%">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="modify" id="modify" class="btn btn-success">Modify</button>
                        </div></div>
                    <? if($_SESSION['userid']=="10019"){?>
                        <div class="form-group" style="margin-left:40%;">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input  name="delete" type="submit" class="btn btn-danger" id="delete" value="Delete"/>
                            </div></div>
                    <? }?>
                <?php } else {?>
                    <div class="form-group" style="margin-left:40%">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="record" id="record"  class="btn btn-primary">Create Notice / Announcement</button>
                        </div></div>
                <?php } ?>


            </form>
        </div>
    </div>
</div>

<?php if(!isset($_GET[$unique])){ ?>
    <!-------------------list view ------------------------->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>List of <?=$title;?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <? 	$res='select p.'.$unique.',p.'.$unique.' as Code,p.'.$unique_field.' AS "REF. NO",p.ADMIN_ANN_SUBJECT as SUBJECT,p.ADMIN_ANN_DATE AS DATE,p.ADMIN_ANN_DETAILS from '.$table.' p order by p.'.$unique;
                echo $crud->link_report_popup($res,$link);?>
            </div>

        </div></div>
    <!-------------------End of  List View --------------------->
<?php } ?>
<!---page content----->




<?php require_once 'footer_content.php' ?>
<script>
    $(document).ready(function() {
        $('#ADMIN_ACTION_DATE').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
