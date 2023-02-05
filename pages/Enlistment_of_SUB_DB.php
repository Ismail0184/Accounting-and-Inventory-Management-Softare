 <?php
require_once 'support_file.php';
$title="Enlistment of SUB DB ";

$now=time();
$unique='sub_db_code';
$unique_field='sub_dealer_name_e';
$table="sub_db_info";
$page="Enlistment_of_SUB_DB.php";
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
    //echo "<script>self.opener.location = '$page'; self.blur(); </script>";
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
 <style>
     input[type=text] {
        font-size: 11px;
     }
     input[type=number] {
         font-size: 11px;
     }
 </style>
<?php require_once 'body_content.php'; ?>



                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>


                            <div class="x_content">
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <?require_once 'support_html.php';?>
                                    <input type="hidden" id="<?=$unique?>" style="width:100%"  required   name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Super DB<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="super_dealer_code" id="super_dealer_code">
                                                <option></option>
                                                <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ",dealer_custom_code," : ", dealer_name_e)', $super_dealer_code, 'canceled not in ("No") and customer_type in ("Distributor","cbsd","superdb","SuperShop")'); ?>
                                            </select>
                                        </div></div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SUB DB Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="<?=$unique_field?>" style="width:100%"  required   name="<?=$unique_field?>" value="<?=$$unique_field?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Address<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea id="address_e" name="address_e" style="width:100%; font-size: 11px"  class="form-control col-md-7 col-xs-12" ><?=$address_e?></textarea>
                                        </div></div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Person<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="contact_person" style="width:100%"  required   name="contact_person" value="<?=$contact_person?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Number<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="number" id="contact_number" style="width:100%"  required   name="contact_number" value="<?=$contact_number?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Active Status<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <select class="form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="canceled" id="canceled">
                                                <option></option>
                                                <option value="Yes" <?php if($canceled=='Yes') echo 'selected'; else echo '';?>>Active</option>
                                                <option value="No" <?php if($canceled=='No') echo 'selected'; else echo '';?>>Inactive</option>
                                            </select>
                                        </div></div>
                                    

<table align="center" style="width: 100%">
                                       <tr>
                                           <td>
                                        <?php if($_GET[$unique]){  ?>
                                            <div class="form-group" style="float: left">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" class="btn btn-primary"  style="font-size: 12px">Modify</button>
                                            </div></div>
                                            <? if($_SESSION['userid']=="10019"){?>

                                             <div class="form-group" style="float: right">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                             <input  name="delete" type="submit" class="btn btn-danger"  style="font-size: 12px" id="delete" value="Delete"/>
                                             </div></div>
                                             <? }?>                                         
                                            <?php } else {?>

                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  style="font-size: 12px" class="btn btn-primary">Add New </button>
                                            </div></div></td>
                                            <?php } ?>
                                       </tr>
</table>
                                </form>
                                </div>
                                </div>
                                </div>


<?php if(!isset($_GET[$unique])){ ?>     
<? 	$res='select sub.'.$unique.',sub.'.$unique.' as Code,(select dealer_name_e from dealer_info where dealer_code=sub.super_dealer_code) as Super_db,sub.'.$unique_field.' as SUB_DB_NAME,sub.address_e as Address,sub.contact_person,sub.contact_number,sub.canceled as status from '.$table.' sub order by sub.'.$unique;
echo $crud->report_templates_with_title_and_class($res,'Sub DB List','');?>
<?php } ?>
       
<?php require_once 'footer_content.php' ?>