<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Vendor Info";




$now=time();
$unique='vendor_id';
$unique_field='vendor_name';
$table="vendor";
$page="vendor_info.php";
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

        $id = $_POST['vendor_id'];
        if($_FILES['vtc']['tmp_name']!=''){
            $file_temp = $_FILES['vtc']['tmp_name'];
            $folder = "../../v_pic/vtc/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['vvc']['tmp_name']!=''){
            $file_temp = $_FILES['vvc']['tmp_name'];
            $folder = "../../v_pic/vvc/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['vtl']['tmp_name']!=''){
            $file_temp = $_FILES['vtl']['tmp_name'];
            $folder = "../../v_pic/vtl/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['qt1']['tmp_name']!=''){
            $file_temp = $_FILES['qt1']['tmp_name'];
            $folder = "../../v_pic/qt1/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['qt2']['tmp_name']!=''){
            $file_temp = $_FILES['qt2']['tmp_name'];
            $folder = "../../v_pic/qt2/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['qt3']['tmp_name']!=''){
            $file_temp = $_FILES['qt3']['tmp_name'];
            $folder = "../../v_pic/qt3/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

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

    $id = $_POST['vendor_id'];

    if($_FILES['vtc']['tmp_name']!=''){
        $file_temp = $_FILES['vtc']['tmp_name'];
        $folder = "../../v_pic/vtc/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['vvc']['tmp_name']!=''){
        $file_temp = $_FILES['vvc']['tmp_name'];
        $folder = "../../v_pic/vvc/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['vtl']['tmp_name']!=''){
        $file_temp = $_FILES['vtl']['tmp_name'];
        $folder = "../../v_pic/vtl/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['qt1']['tmp_name']!=''){
        $file_temp = $_FILES['qt1']['tmp_name'];
        $folder = "../../v_pic/qt1/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['qt2']['tmp_name']!=''){
        $file_temp = $_FILES['qt2']['tmp_name'];
        $folder = "../../v_pic/qt2/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['qt3']['tmp_name']!=''){
        $file_temp = $_FILES['qt3']['tmp_name'];
        $folder = "../../v_pic/qt3/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}
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
                    <div align="center" style="position: center" class="col-md-6 col-sm-12 col-xs-12">
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

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 12px">
                                    <?require_once 'support_html.php';?>

                                    <input type="hidden" id="status" style="width:100%"    name="status" value="Inactive" class="form-control col-md-7 col-xs-12" >
                                    <input type="hidden" id="group_for" style="width:100%"    name="group_for" value="<?=$_SESSION[usergroup];?>" class="form-control col-md-7 col-xs-12" >

                                    <div class="form-group" style="display: none">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Vendor ID<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="vendor_id" style="width:100%"    name="vendor_id" value="<?=$vendor_id;?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Ledger ID:<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="ledger_id" style="width:100%"  required   name="ledger_id" value="<?=$ledger_id;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>

                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Vendor Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="vendor_name" style="width:100%"  required   name="vendor_name" value="<?=$vendor_name;?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Vendor Company<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="vendor_company" style="width:100%"  required   name="vendor_company" value="<?=$vendor_company;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Address<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea id="address" style="width:100%"    name="address"  class="form-control col-md-7 col-xs-12" ><?=$address;?></textarea>
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Contact No<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="contact_no" style="width:100%" name="contact_no" value="<?=$contact_no;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Email ID<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="email" style="width:100%" name="email" value="<?=$email;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Contact Person Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="contact_person_name" style="width:100%" name="contact_person_name" value="<?=$contact_person_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Contact P Designation<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="contact_person_designation" style="width:100%" name="contact_person_designation" value="<?=$contact_person_designation;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Contact Person Mobile<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="contact_person_mobile" style="width:100%" name="contact_person_mobile" value="<?=$contact_person_mobile;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Category<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <select class="select2_single form-control" name="vendor_category" id="vendor_category">
                                                <option value="0"></option>
                                                <? foreign_relation('vendor_category','id','category_name',$vendor_category);
                                                // table name, which field take, which field show, default value
                                                ?>
                                            </select></div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Vendor Type<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" name="vendor_type" id="vendor_type">
                                                <? foreign_relation('vendor_type','id','vendor_type',$vendor_type);?>
                                            </select></div></div>





                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Trade Lisenc<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="file" id="vtl" style="width:100%" name="vtl" value="<?=$vtl;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>



                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">TIN Copy<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="file" id="vtc" style="width:100%" name="vtc" value="<?=$vtc;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>




                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">VAT Copy<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="file" id="vvc" style="width:100%" name="vvc" value="<?=$vvc;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>










                                    
                                    <br><br><br><br>



                                        <?php if($_GET[vendor_id]){  ?>
                                            <? if($_SESSION['userlevel']==5){?>                                            
                                             <div class="form-group" style="margin-left:40%; display: none">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                             <input  name="delete" type="submit" class="btn btn-success" id="delete" value="Delete"/>
                                             </div></div>                                             
                                             <? }?>
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" class="btn btn-success">Modify</button>
                                            </div></div>                                            
                                            <?php } else {?>                                           
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  class="btn btn-success">Requisition Create & Send</button>
                                            </div></div>                                                                                        
                                            <?php } ?> 


                                </form>
                                </div>
                                </div>
                                </div>

<?=$html->footer_content();mysqli_close($conn);?>