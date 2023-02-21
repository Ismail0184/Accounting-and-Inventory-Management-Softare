<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Add FG";

$now=time();
$unique='id';
$unique_field='name';
$table="production_line_fg";
$page="CMU_FG_Production_Setup.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";



if(prevent_multi_submit()){

    if(isset($_POST['initiate']))
    {
        $_SESSION['line_id_FG']=$_POST['line_id'];
    }



/////////////
    if(isset($_POST['add']))
    {
        $item_ids= $_POST['item_id'];
		foreach ($item_ids as $i) {
        $item_ids = $i;
		$_POST['item_id']=$item_ids;		
        $_POST['fg_item_id']=$_POST[item_id];
        $_POST['unit_name']=find_a_field('item_info','unit_name','item_id="'.$_POST[item_id].'"');
        $_POST['line_id']=$_SESSION['line_id_FG'];
		$_POST[entry_at]=date("Y-m-d h:i:sa");;
        $crud      	=new crud($table);
        $crud->insert();
		}
		unset($_POST);
        unset($$unique);
		}


//for modify..................................
    if(isset($_POST['modify']))
    {   unset($_SESSION['line_id_FG']);
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

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
	
	$results='select a.fg_item_id,a.id,i.finish_goods_code as code,i.item_name as Finish_goods_name,a.hourly_production,a.unit_name,w.warehouse_name,u.fname as Added_by,a.entry_at

from 
production_line_fg a,
warehouse w,
item_info i ,
user_activity_management u
where 

w.warehouse_id=a.line_id and 
i.item_id=a.fg_item_id and 
a.entry_by=u.user_id and 
w.warehouse_id='.$_SESSION['line_id_FG'];

$sql_fg="SELECT i.item_id,concat(i.item_id,' : ',i.item_name) as item_name from 
item_info i
WHERE 
i.product_nature in ('Salable','Both') and 
i.item_id not in (select fg_item_id from production_line_fg where line_id=".$_SESSION['line_id_FG'].")
";
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

                </div>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />

            <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Factory / Warehouse<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="hidden" value="" name="<?=$unique?>" id="<?=$unique?>">                    
                    <select  class="select2_single form-control" style="width:100%; font-size: 11px; height: 30px"  tabindex="-1" required="required"  name="line_id" id="line_id">
                        <option selected></option>
                        <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION['line_id_FG']);?>
                    </select>
                    </div>
                </div>


                <?php if($_SESSION['line_id_FG']>0){ ?>
            <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Finish Goods List<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">                        
                            <select multiple class="select2_single form-control" name="item_id[]" id="item_id" style="width:100%;font-size: 11px">
                                <option></option>
                                <?=advance_foreign_relation($sql_fg,$_POST[item_id]);?>
                            </select>
                        </div></div>
                <?php } ?>



                <div class="form-group" style="margin-left:40%">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if($_SESSION['line_id_FG']>0){ ?>
                            <button type="submit" name="modify" id="modify"  style="font-size:12px" class="btn btn-danger" onclick='return window.confirm("Are you sure you want to cancel?");'>Cancel</button>
                            <button type="submit" name="add" id="add" style="font-size:12px" class="btn btn-primary">Add FG Item</button>

                        <?php } else { ?>
                            <button type="submit" name="initiate" id="initiate" style="font-size:12px"  class="btn btn-primary">Initiate for Add FG</button>
                        <?php } ?>
                    </div></div>
            </form></div></div></div>


<?php if($_SESSION['line_id_FG']>0){ echo $crud->report_templates_with_title_and_class($results,$title='List of FG','12');}?>
<?=$html->footer_content();?>