<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Bill Received Entry';

$table="Bill_Received_Entry";
$unique = 'id';   // Primary Key of this Database table
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$crud      =new crud($table);

if(prevent_multi_submit()){

    if(isset($_POST['initiate']))
    {   $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST[create_date]=date('Y-m-d');
        $Rd=$_POST[rcv_Date];
        $_POST[rcv_Date]=date('Y-m-d' , strtotime($Rd));
        $_POST['status'] = 'UNCHECKED';
        $_POST[ip]=$ip;
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }





//for modify..................................
    if(isset($_POST['modify']))
    {
        $Rd=$_POST[rcv_Date];
        $_POST[rcv_Date]=date('Y-m-d' , strtotime($Rd));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $sd=$_POST[oi_date];
        $_POST[oi_date]=date('Y-m-d' , strtotime($sd));
        $crud->update($unique);
        $type=1;
        //echo $targeturl;

    }

//for Delete..................................
    if(isset($_POST['cancel']))
    {   $crud = new crud($table_deatils);
        $condition =$unique."=".$_SESSION['initiate_hrm_stationary_requisition'];
        $crud->delete_all($condition);
        $crud = new crud($table);
        $condition=$unique."=".$_SESSION['initiate_hrm_stationary_requisition'];
        $crud->delete($condition);
        unset($_SESSION['initiate_hrm_stationary_requisition']);
        unset($_POST);
        unset($$unique);
    }



}

$res = "SELECT b.id,b.billno_auto,b.bill_no,b.rcv_Date,v.vendor_name,b.contact_person,b.cp_contact_number as CP_number,b.po_no from Bill_Received_Entry b, vendor v 
where b.vendor_id=v.vendor_id";

// data query..................................
if(isset($_SESSION[initiate_hrm_stationary_requisition]))
{   $condition=$unique."=".$_SESSION[initiate_hrm_stationary_requisition];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>


<?php require_once 'header_content.php'; ?>
<?php if(isset($_GET[$unique])):
    require_once 'body_content_without_menu.php'; else :
    require_once 'body_content.php'; endif;  ?>
<?php if(isset($_GET[$unique])): ?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right"></div>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php else: ?>

            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-md" style="width: 900px">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Add New Record
                                <button class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </h5>
                        </div>
                        <div class="modal-body">
                            <?php endif; ?>
                            <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post"  style="font-size: 11px">
                                <input type="hidden" id="<?=$unique?>" style="width:100%; font-size:11px" name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                                <? require_once 'support_html.php';?>
                                <table style="width:100%">
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Vendor<span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">

                                                    <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" onchange="javascript:reload(this.form)" name="vendor_id" id="vendor_id">
                                                        <option></option>
                                                        <? $sql_vendor_id="SELECT  v.vendor_id,concat(v.vendor_id,' : ',v.vendor_name) FROM 
							 vendor v 
							 where 1";
                                                        advance_foreign_relation($sql_vendor_id,$_GET[vendor_id]);?>
                                                    </select>
                                                </div></div>
                                        </td>


                                        <td><div class="form-group">

                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">PO<span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" name="po_no" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td style="width:50%">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">BR NO<span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="billno_auto"   required="required" name="billno_auto" style="width:100%; font-size: 11px" value="<?=automatic_number_generate("BR","Bill_Received_Entry","billno_auto","create_date='".date('Y-m-d')."' and billno_auto like '$sekeyword%'");?>" class="form-control col-md-7 col-xs-12"  readonly >
                                                </div></div></td>


                                        <td style="width:50%">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Rcv. Date<span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="date"   required="required" name="rcv_Date" style="width:100%; font-size: 11px" class="form-control col-md-7 col-xs-12" ></div></div></td><tr>





                                    <tr>
                                        <td style="width:50%">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bill NO<span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="bill_no"   required="required" name="bill_no" style="width:100%; font-size: 11px"  class="form-control col-md-7 col-xs-12"   >
                                                </div></div></td>


                                        <td style="width:50%">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Person<span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="contact_person"  required="required" name="contact_person" style="width:100%; font-size: 11px"  class="form-control col-md-7 col-xs-12" ></div></div></td><tr>




                                    <tr>
                                        <td style="width:50%"><div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">VAT Challan No<span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="VAT_challan"  required="required" name="VAT_challan" style="width:100%; font-size: 11px"  class="form-control col-md-7 col-xs-12" >
                                                </div></div>
                                        </td>


                                        <td style="width:50%">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Number<span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="cp_contact_number"   required="required" name="cp_contact_number" style="width:100%; font-size: 11px"  class="form-control col-md-7 col-xs-12"   >

                                                </div></div>
                                        </td><tr>



                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Remarks<span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <textarea name="Remarks" id="Remarks" style="width:100%; font-size: 11px"></textarea></div></div></td>
                                        <td><div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Challan No<span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="challan_no"   required="required" name="challan_no" style="width:100%; font-size: 11px"  class="form-control col-md-7 col-xs-12"   >
                                                </div>
                                            </div>
                                        </td>
                                    <tr>

                                    <tr>
                                        <td colspan="2">
                                            <div class="form-group" style="margin-left:40%">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">ADD NEW BILL</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
    </div>
    </div>
    </div>
<?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
            <?php if(!isset($_GET[$unique])):?>
                <?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
            <?php endif; ?>
            <?=$html->footer_content();mysqli_close($conn);?>
            <?php ob_end_flush();
            ob_flush(); ?>