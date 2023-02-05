<?php
require_once 'support_file.php';
$title="Item Info Upload";
$now=date("Y-m-d h:i:sa");
$table="item_info";
$table_helper="data_upload_helper";
$unique = 'id';   // Primary Key of this Database table
$page="MIS_upload_item_info.php";
$crud      =new crud($table);
$source="item_info";
$source_unique="source_id";
$source_id=3;


if(prevent_multi_submit()) {
    if (isset($_POST['initiate'])) {
        $_SESSION['mis_iiu_group_id'] = $_POST[group_id];
        $_SESSION['mis_iiu_product_nature'] = $_POST[product_nature];
        $_SESSION['mis_iiu_consumable_type'] = $_POST[consumable_type];
        unset($_POST);
        unset($$unique);
    }} // prevent multi submit

if(isset($_POST['clearAll']))
    {   $crud   = new crud($table_helper);
        $condition=$source_unique."=".$source_id;
        $crud->delete_all($condition);
    }

    if(isset($_POST['confirm']))
    { $query = "INSERT INTO ".$table." (item_id,finish_goods_code,item_name,unit_name,pack_size,d_price,consumable_type,product_nature,sub_group_id,entry_by,entry_at,H_S_code,section_id,company_id)
          SELECT item_id, finish_goods_code,item_name,email,parent,rate,mobile_no,outlet_category,ledger_group_id,entry_by,entry_at,ledger_id,section_id,company_id FROM data_upload_helper
          WHERE source in ('".$source."')";
        $insert=mysqli_query($conn, $query);
        unset($_POST);
        $del=mysqli_query($conn, "DELETE FROM ".$table_helper." WHERE  entry_by=".$_SESSION[userid]." and ".$source_unique."=".$source_id."");
    }


    if(isset($_POST["Import"])){
            echo $filename=$_FILES["file"]["tmp_name"];
            if($_FILES["file"]["size"] > 0)
            {
                $file = fopen($filename, "r");
                while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
                {   //It wiil insert a row to our subject table from our csv file`
						if(is_numeric($emapData[0])) {
						$sql = "INSERT INTO `data_upload_helper` (`item_id`,`finish_goods_code`,`item_name`,`email`,`parent`,`rate`,`ledger_id`,`entry_by`,`entry_at`,`ledger_group_id`,`section_id`,`company_id`,`status`,`source`,`source_id`,`outlet_category`,`mobile_no`)
            VALUES('$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$_SESSION[userid]','$now','$_SESSION[mis_iiu_group_id]','$_SESSION[sectionid]','$_SESSION[companyid]','UNMOVED','$source','$source_id','$_SESSION[mis_iiu_product_nature]','$_SESSION[mis_iiu_consumable_type]')";
                    }
                    $result = mysqli_query( $conn, $sql);
                    if(! $result )
                    {
                        echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
							window.location = ".$page."
						</script>";
                    }}
                fclose($file);
                echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location = ".$page."
					</script>";
            }header("Location: ".$page."");}
if(isset($_POST['deleted']))
{	unset($_POST);
    unset($_SESSION['mis_iiu_group_id']);
    unset($_SESSION['mis_iiu_product_nature']);
    unset($_SESSION['mis_iiu_consumable_type']);

}


    $results="Select code,item_id,finish_goods_code,item_name,mobile_no as consumable_type,email as unit,rate as DP_price,ledger_id as HS_code_id,parent as Pack_size  from data_upload_helper  where
 source in ('".$source."') order by code";
   $ismail = mysqli_query($conn, $results);
?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>

<?php if(isset($_GET[$unique])){ ?>

<?php } else { ?>
<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right">
                </div>
            </ul>
            <div class="clearfix"></div>
        </div>


        <div class="x_content">
            <form action="" enctype="multipart/form-data" style="font-size: 11px" method="post" name="addem" id="addem" >
                <? //require_once 'support_html.php';?>
                <table style="width:100%"  cellpadding="0" cellspacing="0">


                    <tr>
                        <td>
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Customer Type<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="select2_single form-control"  required style="width: 99%;" name="group_id" id="group_id">
                                    <option></option>
                                    <?php foreign_relation('item_group', 'group_id', 'CONCAT(group_id," : ", group_name)', $_SESSION['mis_iiu_group_id'], '1'); ?>
                                    </select>
                                </div></div></td>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Consumable Type:<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="select2_single form-control"  required style="width: 99%;" name="consumable_type" id="consumable_type"><option></option>
                                    <option value="Consumable" <?=($_SESSION['mis_iiu_consumable_type']=='Consumable')? 'Selected' : '';?>>Consumable</option>
                                    <option value="Non-Comsumable" <?=($_SESSION['mis_iiu_consumable_type']=='Non-Comsumable')? 'Selected' : '';?>>Non-Comsumable</option>
                                    <option value="Service" <?=($_SESSION['mis_iiu_consumable_type']=='Service')? 'Selected' : '';?>>Service</option>
                                    </select>
                                    </div></div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Product Nature:<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="select2_single form-control"  required style="width: 99%;" name="product_nature" id="product_nature"><option></option>
                                    <option value="Salable" <?=($_SESSION['mis_iiu_product_nature']=='Salable')? 'Selected' : '';?>>Salable</option>
                                    <option value="Purchasable" <?=($_SESSION['mis_iiu_product_nature']=='Purchasable')? 'Selected' : '';?>>Purchasable</option>
                                    <option value="Both" <?=($_SESSION['mis_iiu_product_nature']=='Both')? 'Selected' : '';?>>Both</option>

                                    </select>
                                    </div></div>
                        </td>
                    </tr> </table>

                <div class="form-group" style="margin-left:40%; margin-top: 15px">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if($_SESSION[mis_iiu_group_id]){  ?>
                        <?php   } else {?>
                            <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 11px">Initiate with Primary Info</button>
                        <?php } ?>
                    </div></div>
            </form></div></div></div>



<?php if($_SESSION[mis_iiu_group_id]){  ?>
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <form class="form-horizontal form-label-left"  method="post"  enctype="multipart/form-data">
                        <? require_once 'support_html.php';?>
                   <table align="center" style="width:65%">
                            <tr>
                                <td>CSV File Only</td>
                                <td style="width: 2%"> : </td>
                                <td><input type="file" name="file" id="file"  /></td>
                                <td><button type="submit" name="Import" onclick='return window.confirm("Are you confirm to Upload?");' class="btn btn-primary" style="font-size: 11px">Upload the File</button></td>
                                <td><a href="<?=$page;?>" type="submit" style="float: left; margin-left: 1%; font-size: 12px" name="deleted" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Cancel Upload file?");' class="btn btn-danger">Cancel</a></td>
                                <?php if (mysqli_num_rows($ismail) > 0) {  ?>
                                <td><button type="submit" style="float: left; margin-left: 1%; font-size: 12px" name="clearAll" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Clear All Data?");' class="btn btn-danger">Clear All uploaded Data</button></td>
                                <?php } ?>
                            </tr>
                   </table>
                    </form>
                </div></div></div>

    <form id="ismail" name="ismail"  method="post" style="font-size: 11px"  class="form-horizontal form-label-left">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
        <div class="x_content">
            <?=$crud->link_report_voucher($results,$link);?>
        </div></div></div>
        <button type="submit" style="float: left; margin-left: 1%; font-size: 12px" name="deleted" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to Deleted?");' class="btn btn-danger">Cancel Upload </button>
        <?php if (mysqli_num_rows($ismail) > 0) {  ?>
            <button type="submit" style="float: right; margin-right: 1%; font-size: 12px" onclick='return window.confirm("Are you want to Finished?");' name="confirm" class="btn btn-success">Confirm and Data move to Master Table </button>
        <?php } else { echo '';} ?>
    </form>


<?php }} mysqli_close($conn);?>
<?=$html->footer_content();?>
