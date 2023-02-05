<?php
require_once 'support_file.php';
$title='Sub Class';
$now=time();
$unique='id';
$unique_field='sub_class_name';
$table='acc_sub_class';
$page="account_ledger_sub_class.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];



$targeturl="<meta http-equiv='refresh' content='0;$page'>";
if(isset($_REQUEST['sub_class_name'])||isset($_REQUEST['id']))

{

//common part.............

    $sub_class_name			= mysql_real_escape_string($_REQUEST['sub_class_name']);
    $sub_class_type_id		= mysql_real_escape_string($_REQUEST['sub_class_type_id']);
    $sub_class_id			= mysql_real_escape_string($_REQUEST['id']);
//end
    if(isset($_POST['ngroup']) && !empty($sub_class_name))
    {
        $sql="INSERT INTO `acc_sub_class` (
					`sub_class_name`,
					`sub_class_type_id` ,
					`status`,
					`class_id`
					)
					VALUES ('$sub_class_name','$sub_class_type_id', '1','$_POST[class_id]')";
        $query=mysql_query($sql);
        $type=1;
        $msg='New Entry Successfully Inserted.';
    }





//for Modify..................................



    if(isset($_POST['mgroup']))

    {

        $sql="UPDATE `acc_sub_class` SET 

		`sub_class_name` = '$sub_class_name',

		`sub_class_type_id` ='$sub_class_type_id'

		WHERE `id` = $sub_class_id LIMIT 1";

        $qry=mysql_query($sql);

        $type=1;

        echo $targeturl;

    }

//for Delete..................................



    if(isset($_POST['dgroup']))

    {



        $sql="UPDATE `acc_sub_class` SET 

		`status` = '0'

		WHERE `id` = $sub_class_id LIMIT 1";

        $query=mysql_query($sql);

        $type=1;

        $msg='Successfully Deleted.';

    }
}



//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$_GET['id'];
    $crud->delete($condition);
    unset($_GET['id']);
    $type=1;
    $msg='Successfully Deleted.';
    echo $targeturl;

}


if(isset($_GET['id']))
{   $condition=$unique."=".$_GET['id'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$res='select sc.'.$unique.',sc.'.$unique.' as Code,sc.'.$unique_field.',ac.class_name as class from 
                                '.$table.' sc,
                                 acc_class ac
                                 where 
                                 sc.class_id=ac.id
                                 order by sc.'.$unique;	
?>





<?php require_once 'header_content.php'; ?>
        <script type="text/javascript"> function DoNav(lk){document.location.href = '<?=$page?>?<?=$unique?>='+lk;}
            function popUp(URL)
            {
                day = new Date();
                id = day.getTime();
                eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=800,left = 383,top = -16');"); }

        </script>

<?php require_once 'body_content.php'; echo $crud->report_templates_with_add_new($res,$title,12);?>

<div class="modal fade" id="darkModalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog form-dark" role="document">
    <div class="modal-content card card-image">
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <? require_once 'support_html.php';?>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Sub Class  Name :<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="sub_class_name" style="width:100%; font-size: 12px"  required   name="sub_class_name" value="<?=$sub_class_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>





                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Class<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" name="class_id" id="class_id">
                                                <option></option>
                                                <?	$sql="select * from acc_class order by class_name,priority";
                                                $query=mysql_query($sql);
                                                while($datas=mysql_fetch_object($query)){ ?>
                                                    <option <? if($datas->id==$class_id) echo 'selected';?> value="<?=$datas->id?>"><?=$datas->class_name?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    </div>


                                    <?php if($_GET[id]){  ?>                                       
                                        <div class="form-group" style="float: right">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="mgroup" id="mgroup" onclick='return window.confirm("Are you confirm to Update?");' class="btn btn-success">Modify Sub Class</button>
                                            </div></div>
                                    <?php   } else {?>
                                        <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="ngroup" id="ngroup"  class="btn btn-primary">Add Sub Class</button></div></div>


                                    <?php } ?>
                            </div></div>

                        </form>
    </div></div></div></div>
<?php require_once 'footer_content.php' ?>