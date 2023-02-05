<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Add LC Buyer";

$now=time();
$unique='party_id';
$unique_field='buyer_name';
$table="lc_buyer";
$page="lc_buyer.php";
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
?>



<?php require_once 'header_content.php'; ?>
 <style>

     input[type=text] {
         font-size: 11px;
     }
 </style>
<?php require_once 'body_content.php'; ?>





                    <?php if(!isset($_GET[$unique])){ ?>
                    <!-------------------list view ------------------------->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Buyer List</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a hhref="JavaScript:void(0);" class="btn btn-danger" id="delete_multiple" style="color: white; font-size: 12px"><i class="fa fa-minus-circle"></i>  Delete</a></li>
                                    <li><a href="#addEmployeeModal" class="btn btn-primary" data-toggle="modal" style="color: white; font-size: 12px"><i class="fa fa-plus"></i>  Add New</a></li>
                                    <!--li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li-->
                                </ul>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <? 	$res='select '.$unique.','.$unique.' as Code,'.$unique_field.',contact_person,contact_number,email_id,origin, ledger_id as Accounts_code,status from '.$table.' order by '.$unique;
                                echo $crud->link_report_popup($res,$link,$conn);?>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->
                    <?php } ?>
                    <!---page content----->

 <?php if(isset($_GET[$unique])){ ?>
 <div class="col-md-5 col-sm-12 col-xs-12">
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
             <form  name="addem" style="font-size: 11px" id="addem" class="form-horizontal form-label-left" method="post">

                 <input type="hidden" id="<?=$unique;?>" style="width:100%"    name="<?=$unique;?>" value="<?=$$unique;?>" class="form-control col-md-7 col-xs-12" >
                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Buyer Name<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="<?=$unique_field?>" style="width:100%"  required   name="<?=$unique_field?>" value="<?=$$unique_field?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>

                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Person<span class="required"></span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="contact_person" style="width:100%"     name="contact_person" value="<?=$contact_person;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>

                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Number<span class="required"></span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="contact_number" style="width:100%"     name="contact_number" value="<?=$contact_number;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>

                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Email ID<span class="required"></span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="email_id" style="width:100%"     name="email_id" value="<?=$email_id;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>

                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Address<span class="required"></span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <textarea name="address" class="form-control col-md-7 col-xs-12"><?=$address;?></textarea>
                     </div></div>


                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Buyer Origin<span class="required"></span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="origin" style="width:100%"   name="origin" value="<?=$origin;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>

                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">ledger ID<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id" id="ledger_id">
                             <option></option>
                             <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_id, 'ledger_group_id in ("2002")'); ?>
                         </select>




                     </div></div>

                 <div class="form-group" >
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Buyer Category<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="lc_buyer_category" id="lc_buyer_category">
                             <option></option>
                             <?php foreign_relation('lc_buyer_category', 'id', 'CONCAT(id," : ", category_name)', $lc_buyer_category, '1'); ?>
                         </select>
                     </div>
                 </div>


                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Active Status:<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select class="select2_single form-control" name="status" id="status">
                             <option></option>
                             <option value="ACTIVE" <?php if($status=='ACTIVE') echo 'selected' ?>>Active</option>
                             <option value="INACTIVE" <?php if($status=='INACTIVE') echo 'selected' ?>>Inactive</option>
                         </select></div>
                 </div> <br>

                 <?php if($_GET[$unique]){  ?>

                     <div class="form-group" style="margin-left:40%">
                         <div class="col-md-6 col-sm-6 col-xs-12">
                             <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify</button>
                         </div></div>

                     <? if($_SESSION['userid']=='440'){?>
                         <div class="form-group" style="margin-left:40%">
                             <div class="col-md-6 col-sm-6 col-xs-12">
                                 <input  name="delete" type="submit" class="btn btn-danger" id="delete" value="Delete"/>
                             </div></div>
                     <? }?>
                 <?php } else {?>
                     <div class="form-group" style="margin-left:40%">
                         <div class="col-md-6 col-sm-6 col-xs-12">
                             <button type="submit" name="record" id="record"  class="btn btn-primary">Add New Buyer</button>
                         </div></div>
                 <?php } ?>


             </form>
         </div>
     </div>
 </div>
     <?php } ?>

 <div id="addEmployeeModal" class="modal fade">
     <div class="modal-dialog">
         <div class="modal-content">
             <form method="post" id="user_form" style="font-size: 11px">
                 <?require_once 'support_html.php';?>
                 <input type="hidden" id="<?=$unique;?>" style="width:100%"    name="<?=$unique;?>" value="<?=$$unique;?>" class="form-control col-md-7 col-xs-12" >
                 <div class="modal-header">
                     <h4 class="modal-title">Buyer / Party Info</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 </div>
                 <div class="modal-body">
                     <div class="form-group">
                         <label>Party Name *</label>
                         <input type="text" id="<?=$unique_field?>" name="<?=$unique_field?>" value="<?=$$unique_field?>" class="form-control" required>
                     </div>
                     <div class="form-group">
                         <label>Contact Person</label>
                         <input type="text" id="contact_person" name="contact_person" value="<?=$contact_person;?>" class="form-control" required>
                     </div>
                     <div class="form-group">
                         <label>Contact Number</label>
                         <input type="text" id="contact_number" name="contact_number" value="<?=$contact_person;?>" class="form-control" required>
                     </div>
                     <div class="form-group">
                         <label>Email</label>
                         <input type="email" id="email_id" name="email_id" class="form-control" required>
                     </div>
                     <div class="form-group">
                         <label>Address</label>
                         <textarea id="address" name="address" class="form-control" required></textarea>
                     </div>
                     <div class="form-group">
                         <label>Origin</label>
                         <input type="text" id="origin" name="origin" class="form-control" >
                     </div>
                     <div class="form-group">
                         <label>Ledger ID</label>
                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id">
                             <option></option>
                             <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_id, ' ledger_group_id not in  ("1006")'); ?>
                         </select>
                     </div>
                     <div class="form-group">
                         <label>Buyer Category</label>
                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="lc_buyer_category" id="lc_buyer_category">
                             <option></option>
                             <?php foreign_relation('lc_buyer_category', 'id', 'CONCAT(id," : ", category_name)', $lc_buyer_category, '1'); ?>
                         </select>
                     </div>
                     </div>
                 <div class="modal-footer">
                     <input type="hidden" value="1" name="type">
                     <input type="button" class="btn btn-danger" data-dismiss="modal" style="font-size: 11px" value="Cancel">
                     <button type="submit" class="btn btn-success" name="record" id="btn-add" style="font-size: 11px">Add New Buyer</button>
                 </div>
             </form>
         </div>
     </div>
 </div>


 <?=$html->footer_content();?>