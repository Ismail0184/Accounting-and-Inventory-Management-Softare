<?php

require_once 'support_file.php';

$title='Create EMI';			// Page Name and Page Title
$page="procurement_create_EMI.php";		// PHP File Name
$table_master='purchase_EMI_master';		// Database Table Name Mainly related to this page
$table_details='purchase_EMI_details';
$unique='EMI_id';			// Primary Key of this Database table
$shown='down_payment';				// For a New or Edit Data a must have data field
$crud      =new crud($table_master);


$$unique = $_GET[$unique];
if(isset($_POST[$unique]))
{    $$unique = $_POST[$unique];
        if (isset($_POST['initiate'])) {
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d H:s:i');
            $_POST['status'] = 'UNCHECKED';
            $_SESSION[initiate_EMI_id]=$_POST[$unique];
            $crud->insert();
            unset($_POST); }


    if(isset($_POST['add']))
    {
        $now				= time();
        for($i=0;$i<$_POST['total_EMI'];$i++)
        {
            $_POST[$unique] = $_SESSION['initiate_EMI_id'];
            $_SESSION['IID']=$_POST[$unique];
            $_POST['EMI_no'] = $i+1;
            $smon=$_POST['start_mon']+$i;
            $syear=$_POST['start_year'];
            $_POST['current_mon'] = date('m',mktime(1,1,1,$smon,1,$syear));
            $_POST['current_year'] = date('Y',mktime(1,1,1,$smon,1,$syear));
            $crud      =new crud($table_details);
            $crud->insert();
        }
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);}



//for Modify..................................



    if(isset($_POST['modify']))
    {   $crud->update($unique);
        $type=1;
        $msg='Successfully Updated.';
    }
//for Delete..................................
    if(isset($_POST['delete']))
    {		$condition=$unique."=".$$unique;		$crud->delete($condition);
        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';
    }

//for Delete..................................

    if(isset($_POST['delete_all']))
    {		$conditionS=$punique."=".$_SESSION[ISMAIL_ID];
        $crud->delete_all($conditionS);
        unset($_SESSION[ISMAIL_ID]);
        $type=1;
        $msg='Successfully Deleted.';
    }
}
if(isset($_SESSION[initiate_EMI_id])) {
    $condition = $unique . "=" . $_SESSION[initiate_EMI_id];
    $data = db_fetch_object($table_master, $condition);
    while (list($key, $value) = @each($data)) {
        $$key = $value;
    }
}


$app_master=find_all_field('sales_do_installment_applicant_details','','id='.$_GET[app_id].'');
$res_data=mysqli_query($conn,'select * from '.$table.' where installment_ID='.$_SESSION['IID'].'');
?>



<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>

    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Create EMI</h2>
                <ul class="nav navbar-right panel_toolbox">
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <form action="" method="post" enctype="multipart/form-data">
                    <? require_once 'support_html.php';?>
                    <table  style="width:100%;font-size:11px">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">EMI ID :<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="EMI_id" style="width:100%;font-size: 11px"  readonly   name="EMI_id" value="<?=($_SESSION[initiate_EMI_id]!='')? $_SESSION[initiate_EMI_id] : automatic_number_generate("".$_SESSION[userid],"purchase_EMI_master","EMI_id","create_date='".date('Y-m-d')."' and entry_by=".$_SESSION[userid].""); ?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div></td>
                            <td>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Create Date :<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="date" id="create_date" style="width:100%;font-size: 11px"    name="create_date" value="<?=($create_date!='')? $create_date : date('Y-m-d') ?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div></td>
                            <td>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Vendor :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="vendor_id" id="vendor_id">
                                <option></option>
                                <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $vendor_id,' status="ACTIVE"'); ?>
                            </select>
                        </div></div></td><td>
                    </tr>

                        <tr><td style="height: 5px"></td></tr>

                        <tr><td>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Total Amount :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="total_amount" style="width:100%;font-size: 11px"  required   name="total_amount" value="<?=$total_amount;?>" class="form-control col-md-7 col-xs-12" >
                        </div></div></td>

                            <td>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Down Payment :<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="down_payment" style="width:100%;font-size: 11px"   name="down_payment" value="<?=$down_payment;?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div></td>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Monthly Payable :<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="payable_amt" style="width:100%; font-size: 11px"  required   name="payable_amt" value="<?=$payable_amt;?>" class="form-control col-md-7 col-xs-12" >
                                </div></div></td></tr>

</table><br>
                    <?php if($_SESSION[initiate_EMI_id]){?>
                        <div class="form-group" style="margin-left:40%">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 11px">Update EMI</button>
                            </div></div>
                    <?php   } else {?>
                        <div class="form-group" style="margin-left:40%;">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button type="submit" name="initiate" class="btn btn-primary" style="font-size: 11px">Initiate EMI</button>
                            </div></div>
                    <?php } ?></form>
            </div>
        </div></div>


    <form action="<?=$page;?>" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
        <input type="hidden" name="total_amount" value="<?=$total_amount?>">
        <input type="hidden" name="<?=$unique?>" value="<?=$_SESSION[initiate_EMI_id]?>">
        <input type="hidden" name="payable_amt" value="<?=$payable_amt?>">
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <tbody>
            <tr style="background-color: bisque">
                <th style="text-align: center">No of EMI</th>
                <th style="text-align: center">Start Month</th>
                <th style="text-align: center">Start Year</th>
                <th style="text-align: center">Last date of <b>EMI Deposit</th>
                <th style="text-align: center; display: none">Current Month</th>
                <th style="width:5%; text-align:center; display: none">Current Year</th>
                <th style="text-align:center">Action</th>
            </tr>
            <tbody>
        <tr>
            <td><input type="text" id="total_EMI" style="width:100%;font-size: 11px"  required   name="total_EMI" value="<?=$total_EMI;?>" class="form-control col-md-7 col-xs-12" ></td>
            <td>
                        <select name="start_mon" style="width:100%;font-size: 11px" id="start_mon" required class="form-control col-md-7 col-xs-12">
                            <option value="1" <?=($start_mon=='1')?'selected':''?>>Jan</option>
                            <option value="2" <?=($start_mon=='2')?'selected':''?>>Feb</option>
                            <option value="3" <?=($start_mon=='3')?'selected':''?>>Mar</option>
                            <option value="4" <?=($start_mon=='4')?'selected':''?>>Apr</option>
                            <option value="5" <?=($start_mon=='5')?'selected':''?>>May</option>
                            <option value="6" <?=($start_mon=='6')?'selected':''?>>Jun</option>
                            <option value="7" <?=($start_mon=='7')?'selected':''?>>Jul</option>
                            <option value="8" <?=($start_mon=='8')?'selected':''?>>Aug</option>
                            <option value="9" <?=($start_mon=='9')?'selected':''?>>Sep</option>
                            <option value="10" <?=($start_mon=='10')?'selected':''?>>Oct</option>
                            <option value="11" <?=($start_mon=='11')?'selected':''?>>Nov</option>
                            <option value="12" <?=($start_mon=='12')?'selected':''?>>Dec</option>
                        </select></td>
            <td>
                        <select name="start_year"  id="start_year" style="width:100%;font-size: 11px" required class="form-control col-md-7 col-xs-12">
                            <option <?=($start_year==date('Y'))?'selected':''?>><?=date('Y')?></option>
                            <option <?=($start_year=='2013')?'selected':''?>>2013</option>
                            <option <?=($start_year=='2014')?'selected':''?>>2014</option>
                            <option <?=($start_year=='2015')?'selected':''?>>2015</option>
                            <option <?=($start_year=='2016')?'selected':''?>>2016</option>
                        </select></td>
            <td><input type="text" id="last_day_of_payment" style="width:100%;font-size: 11px"  required   name="last_day_of_payment" class="form-control col-md-7 col-xs-12" ></td>

            <td style="display: none">
                <select name="current_mon" style="width:100%;font-size: 11px" id="current_mon" required class="form-control col-md-7 col-xs-12">
                    <option value="<?=date('m')?>"><?=date('M')?></option>
                </select></td>
        <td style="display: none">
                <select name="current_year" style="width:160px;font-size: 11px" id="current_year" required class="form-control col-md-7 col-xs-12">
                    <option value="<?=date('Y')?>"><?=date('Y')?></option>
                </select></td>
            <td align="center" style="width:5%; vertical-align: middle "><?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td>
        </table>
    </form>




<?=$html->footer_content();mysqli_close($conn);?>