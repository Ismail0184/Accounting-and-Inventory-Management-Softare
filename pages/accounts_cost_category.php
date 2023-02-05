<?php
require_once 'support_file.php';
$title='Cost Center Category';

$now=time();
$unique='id';
$unique_field='category_name';
$table='cost_category';
$page="accounts_cost_category.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];





$targeturl="<meta http-equiv='refresh' content='0;$page'>";
if(isset($_POST[$unique_field]))

{

    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }

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
}}


if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>
<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=500,height=500,left = 383,top = -1");}</script>

</head>
<?php require_once 'body_content.php'; ?>



<?php if(!isset($_GET[id])){ ?>
    <!-------------------list view ------------------------->
    <div class="col-md-7 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>List of <?=$title;?></h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <? 	$res='select '.$unique.','.$unique.' as Code,'.$unique_field.' from '.$table.' order by '.$unique;
            echo $crud->link_report_popup($res,$link);?>

        </div>

    </div></div>

<?php } ?>
    <!-------------------End of  List View --------------------->


                    <!-- input section-->
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
                                    <? require_once 'support_html.php';?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Category Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="category_name" style="width:100%"  required   name="category_name" value="<?=$category_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>




                                        <?php if($_GET[id]){  ?>
                                            <? if($_SESSION['userlevel']==5){?>
                                                <div class="form-group" style="margin-left:40%; display: none">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input  name="delete" type="submit" class="btn btn-success" id="delete" value="Delete"/></div></div>
                                            <? }?>

                                            <div class="form-group" style="margin-left:40%">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <button type="submit" name="modify" id="modify" class="btn btn-success">Modify</button>
                                                </div></div>


                                        <?php   } else {?>
                                            <div class="form-group" style="margin-left:40%">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <button type="submit" name="record" id="record"  class="btn btn-primary">Add Cost Category</button></div></div>
                                        <?php } ?>
                                    </div></div>


                            </form>

                            </div></div></div>


        <!---page content----->
<?php require_once 'footer_content.php' ?>