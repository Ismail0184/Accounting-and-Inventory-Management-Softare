<?php
require_once 'support_file.php';
$title="IOU Requisition";

$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$now=time();

$table="user_IOU";
$unique = 'id';   // Primary Key of this Database table
$page="user_IOU_requisition.php";
$crud      =new crud($table);
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$$unique=$_GET[$unique];
if(prevent_multi_submit()){

    if(isset($_POST['initiate']))
    {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['PBI_ID'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $sd=$_POST[req_date];
        $_POST[req_date]=date('Y-m-d' , strtotime($sd));
        $_POST['ip'] = $ip;
        $_POST['status'] = 'UNCHECKED';
        $_POST['create_date'] = $_SESSION['create_date'];
        $_POST['PBI_ID'] = $_SESSION[PBI_ID];
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }


//for modify..................................
    if(isset($_POST['modify']))
    {
        $sd=$_POST[or_date];
        $_POST[or_date]=date('Y-m-d' , strtotime($sd));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        //echo $targeturl;

    }


//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table_deatils);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>
<script>
    var x = 0;
    var y = 0;
    var z = 0;
    function calc(obj) {
        var e = obj.id.toString();
        if (e == 'qtysa') {
            x = Number(obj.value);
            y = Number(document.getElementById('rate').value);
        } else {
            x = Number(document.getElementById('qtysa').value);
            y = Number(obj.value);
        }
        z = x * y;
        document.getElementById('total').value = z;
        document.getElementById('update').innerHTML = z;
    }
</script>
<style>
    input[type=text]{
        font-size: 11px;
        height: 25px;
    }
</style>
<?php require_once 'body_content.php'; ?>




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
            <form action="" enctype="multipart/form-data" method="post" style="font-size: 11px" name="addem" id="addem" >
                <? //require_once 'support_html.php';?>
                <table style="width:100%"  cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:50%;">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">ID No<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="<?=$unique?>"   required="required" name="<?=$unique?>" value="<?
                                    $uids=find_a_field($table,'max('.$unique.')+1','1');
                                    if($$unique>0){
                                        $uid=$$unique; } else {
                                     $uid=$uids;
                                        if($uids<1) $uid = 1;} echo $uid;?>" class="form-control col-md-7 col-xs-12"  readonly style="width:100%">
                                </div>
                            </div></td>


                        <td style="width:50%">
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">When is Needed<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="req_date" readonly  required="required" name="req_date" value="<?php if($$unique>0){ echo date('m/d/Y' , strtotime($req_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width:100%" >      </div>
                            </div>
                        </td></tr>



                    <tr>
                        <td><div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Amount in BDT </label>
                                <div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="amount" id="amount" value="<?=$amount;?>" class="form-control col-md-7 col-xs-12" style="width: 100%;"></div></div></td>
        </div></div></td>



    <td><div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">IOU Purpose </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="purpose" id="purpose" value="<?=$purpose;?>" class="form-control col-md-7 col-xs-12" style="width: 100%;"></div></div></td>
    </tr>

    <tr><td style="height:5px"></td></tr>


    <tr>
        <td>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Recommended By<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select style="width: 100%" class="select2_single form-control" name="recommended_by" id="recommended_by">
                        <option></option>
                        <?php
                        $result=mysqli_query($conn , ("SELECT  p.*,d.* FROM 							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME"));
                        while($row=mysqli_fetch_array($result)){  ?>
                            <option  value="<?=$row[PBI_ID]; ?>" <?php if($recommended_by==$row[PBI_ID]) echo 'selected' ?>><?=$row[PBI_ID_UNIQUE]; ?>#><?=$row[PBI_NAME];?>#> (<?=$row[DEPT_SHORT_NAME];?>)</option>
                        <?php } ?></select>
                </div></div>
        </td>



        <td>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Authorized By<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select style="width: 100%;" class="select2_single form-control" name="authorized_by" id="authorized_by">
                        <option></option>
                        <?php
                        $result=mysqli_query($conn , ("SELECT  p.*,d.* FROM 
							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME"));
                        while($row=mysqli_fetch_array($result)){  ?>
                            <option  value="<?=$row[PBI_ID]; ?>" <?php if($authorized_by==$row[PBI_ID]) echo 'selected' ?>><?=$row[PBI_ID_UNIQUE]; ?>#><?=$row[PBI_NAME];?>#> (<?=$row[DEPT_SHORT_NAME];?>)</option>
                        <?php } ?></select>
                </div></div>
        </td>
    </tr>

    </table>


    <div class="form-group" style="margin-left:40%; margin-top: 15px">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php if($$unique>0){  ?>
                <button type="submit" style="font-size: 11px" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");'>Update IOU Info</button>
            <?php   } else {?>
                <button type="submit" style="font-size: 11px" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">Initiate <?=$title;?></button>
            <?php } ?>
        </div></div>
    </form></div></div></div>












<?php require_once 'footer_content.php' ?>
<script>
    $(document).ready(function() {
        $('#req_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
<script>
    $(function(){
        $('#price, #qty').keyup(function(){
            var price = parseFloat($('#price').val()) || 0;
            var qty = parseFloat($('#qty').val()) || 0;
            $('#sum').val((price * qty).toFixed(2));
        });
    });
</script>
