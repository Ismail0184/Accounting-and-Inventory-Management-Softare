<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Add New Dealer';
$page="dealer_info.php";		// PHP File Name
$table='dealer_info';		// Database Table Name Mainly related to this page
$unique='dealer_code';			// Primary Key of this Database table
$shown='dealer_name_e';
$dealer_custom_codess='dealer_custom_code';				// For a New or Edit Data a must have data field
$crud      =new crud($table);
$$unique = @$_GET[$unique];


if(isset($_POST[$shown])) {
$$unique = @$_POST[$unique];
if(isset($_POST['insert']))
{
$proj_id			= $_SESSION['proj_id'];
$now				= time();
$entry_by = $_SESSION['user'];
$crud->insert();
$id = $_POST['dealer_code'];
$type=1;
$msg='New Entry Successfully Inserted.';
unset($_POST);
unset($$unique);
}

if(isset($_POST['update']))
{       $crud->update($unique);
		$type=1;
		$msg='Successfully Updated.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

if(isset($_POST['delete'])) {
    $condition = $unique . "=" . $$unique;
    $crud->delete($condition);
    unset($$unique);
    $type = 1;
    $msg = 'Successfully Deleted.';
}

}



if(isset($$unique))
{
$condition=$unique."=".$$unique;
$data=db_fetch_object($table,$condition);
while (list($key, $value)=each($data))
{ $$key=$value;}
}
if(!isset($$unique)) $$unique=db_last_insert_id($table,$unique);
$serial = @$serial;
$dealer_custom_code = @$dealer_custom_code;
$area_codeGET = @$area_codeGET;
$dealer_code = @$dealer_code;
$dealer_name_e = @$dealer_name_e;
$propritor_name_e = @$propritor_name_e;
$town_code = @$town_code;
$mobile_no = @$mobile_no;
$contact_person = @$contact_person;
$depot = @$depot;
$contact_number = @$contact_number;
$dealer_type = @$dealer_type;
$contact_person_desig = @$contact_person_desig;
$address_e = @$address_e;
$commission = @$commission;
$national_id = @$national_id;
$canceled = @$canceled;
$TIN_BIN = @$TIN_BIN;
$bank_account = @$bank_account;
$account_code = @$account_code;
$select_dealer_do_regular = @$select_dealer_do_regular;
$region = @$region;
$customer_type = @$customer_type;
$tsm = @$tsm;
?>
<?php if(isset($_POST['update']))
{
    mysqli_query($conn, "Update sale_do_master set region='".$_POST['region']."',territory='".$_POST['territory']."',area_code='".$_POST['area_code']."',town='".$_POST['town_code']."',dealer_type='".$_POST['customer_type']."' where dealer_code='".$_GET['dealer_code']."'");
    mysqli_query($conn, "Update sale_do_details set region='".$_POST['region']."',territory='".$_POST['territory']."',area_code='".$_POST['area_code']."',town='".$_POST['town_code']."',dealer_type='".$_POST['customer_type']."' where dealer_code='".$_GET['dealer_code']."'");
    mysqli_query($conn, "Update sale_do_chalan set region='".$_POST['region']."',territory='".$_POST['territory']."',aria='".$_POST['area_code']."',town='".$_POST['town_code']."',dealer_type='".$_POST['customer_type']."' where dealer_code='".$_GET['dealer_code']);
    mysqli_query($conn, "Update ims_details set region='".$_POST['region']."',territory='".$_POST['territory']."',area_id='".$_POST['area_code']."',town_code='".$_POST['town_code']."',dealer_type='".$_POST['customer_type']."' where dealer_code='".$_GET['dealer_code']);
    mysqli_query($conn, "Update sale_return_master set region='".$_POST['region']."',territory='".$_POST['territory']."',area_code='".$_POST['area_code']."',town='".$_POST['town_code']."',dealer_type='".$_POST['customer_type']."' where dealer_code='".$_GET['dealer_code']);
    mysqli_query($conn, "Update sale_return_details set region='".$_POST['region']."',territory='".$_POST['territory']."',area_code='".$_POST['area_code']."',town='".$_POST['town_code']."',dealer_type='".$_POST['customer_type']."' where dealer_code='".$_GET['dealer_code']);
}

$sql_area = 'select a.AREA_CODE,concat(AREA_CODE," : ",a.AREA_NAME) from area a  where 1 order by a.AREA_NAME';
if(@$_GET['area_codeGET']>0){
	$area_code=@$_GET['area_codeGET'];
} else {
	$area_code=@$area_code;
	}
$res='select d.'.$unique.',d.'.$dealer_custom_codess.' as Code,d.account_code,d.'.$shown.' as dealer_name,d.dealer_category as Category,d.dealer_type as Screm_Type,d.customer_type as DB_Type,d.credit_limit as Credit_Limit,d.commission,(select account_name from bank_account_name where id=d.bank_account) as bank_account,d.canceled as status from '.$table.' d where
 1 order by '.$unique;
$sql_TOWN="Select town_code,concat(town_code,' : ',town_name) from town order by town_name";
$res_daeler_type="Select typeshorname,typedetails from distributor_type order by id";
$dealer_code_GET = @$_GET['dealer_code'];
?>

<?php require_once 'header_content.php'; ?>
        <script type="text/javascript">
            function DoNavPOPUP(lk)
            {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=600,left = 250,top = -1");}
        </script>
        <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.area_code.options[form.area_code.options.selectedIndex].value;
	self.location='dealer_info.php?dealer_code=<?=$dealer_code_GET?>&area_codeGET=' + val ;
}

function reload2(form)
{
	var val=form.dealersearchid.options[form.dealersearchid.options.selectedIndex].value;
	self.location='dealer_info.php?dealer_code=' + val ;
}

</script>
        <style>
            input[type=text] {
                width: 100%;
                margin-top: 5px;
                margin-bottom: 5px;
				font-size:11px
            }
            select {

                margin-top: 5px;
                margin-bottom: 5px;
            }
        </style>
    </head>
<?php require_once 'body_content.php'; ?>
<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title?> <small class="text-danger">First Select Territory from the dropdown list</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right"> </div>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?=$page?>" enctype="multipart/form-data" style="font-size:11px" method="post" name="addem" id="addem" >
                <table style="width:100%; font-size: 11px">
                    <tr>
                        <th style="width:10%;">Territory <span class="required text-danger">*</span></th><th style="width: 2%;">:</th>
                        <input name="<?=$unique?>" id="<?=$unique?>" value="<?=$$unique?>" type="hidden" />
                        <input name="dealer_code" type="hidden" id="dealer_code" tabindex="1" value="<?=$dealer_code?>" readonly>
                        <td style="width: 21.5%">
                            <select class="select2_single form-control" name="area_code" id="area_code" tabindex="11" style="width: 90%" onchange="javascript:reload(this.form)">
                                <option></option>
                                <?=advance_foreign_relation($sql_area,$area_code);?>
                            </select>
                        </td>

                        <th style="width:10%;">Country</th><th style="width: 2%;">:</th>
                        <td style="width: 21.5%">
                            <select class="select2_single form-control" name="country" id="country" style="width: 90%"  tabindex="11">
                                <option value="1" selected>Bangladesh</option>
                                <?
                                //$countryquery =mysql_query('select * from apps_countries order by country_name');
                                //echo '<option></option>';
                                while($Cnrow = @mysqli_fetch_array($countryquery)){
                                    if($country==$Cnrow['BRANCH_ID']){ ?>
                                        <option value="<?=$Cnrow['id'];?>" selected><?=$Cnrow['country_name'];?></option>
                                    <?php } else { ?>
                                        <option value="<?=$Cnrow['id'];?>"><?=$Cnrow['country_name'];?></option>
                                    <?php }}?>
                            </select>
                        </td>

                        <th style="width:10%;">Serial</th><th style="width: 2%;">:</th>
                        <td style="width: 21%">
                            <input name="serial" type="text" required id="serial" tabindex="10" style="width: 30%;" placeholder="serial" value="<?=$serial?>" class="form-control col-md-7 col-xs-12" />
                            <input type="text" id="dealer_custom_code"  value="<?=$dealer_custom_code?>" placeholder="custom code" style="width: 60%; margin-left: 1px" name="dealer_custom_code" class="form-control col-md-7 col-xs-12">
                        </td>
                    </tr>
                    <tr>
                        <th style="">Region</th><th>:</th>
                        <td>
                            <?php if(@$_GET['area_codeGET']){ ?>
                                <input name="region" type="hidden" id="region" tabindex="2" value="<?=$region= find_a_field('area','Region_code','AREA_CODE='.$_GET['area_codeGET']);?>">
                            <?php } else { ?>
                                <input name="region" type="hidden" id="region" tabindex="2" value="<?=$region?>">
                            <?php } ?>
                            <input type="text" id="regionName"  value="<?php
                            if(@$_GET['area_codeGET'])
                                echo $rg = find_a_field('branch','BRANCH_NAME','BRANCH_ID='.$region);
                            else  echo $rg = find_a_field('branch','BRANCH_NAME','BRANCH_ID='.$region);
                            ?>" name="regionName" class="form-control col-md-7 col-xs-12" style="width: 90%" readonly >
                        </td>

                        <th style="">Dealer Name</th><th>:</th>
                        <td>
                            <input type="text" id="dealer_name_e"  value="<?=$dealer_name_e?>" name="dealer_name_e" style="width: 90%" class="form-control col-md-7 col-xs-12">
                        </td>
                        <th style="">Area</th><th>:</th>
                        <td style="vertical-align: middle">
                            <input type="text" id="territory"  value="10" name="territory" style="width: 90%; height: " readonly  class="form-control col-md-7 col-xs-12">
                        </td>
                    </tr>

                    <tr>
                        <th style="">Propritor's Name</th><th>:</th>
                        <td>
                            <input type="text" id="propritor_name_e"  value="<?=$propritor_name_e?>" name="propritor_name_e" style="width: 90%" class="form-control col-md-7 col-xs-12">
                        </td>
                        <th style="">Town</th><th>:</th>
                        <td>
                            <select class="select2_single form-control" name="town_code" required id="town_code"  style="width: 90%" tabindex="3">
                                <option></option>
                                <?=advance_foreign_relation($sql_TOWN,$town_code);?>
                            </select>
                        </td>
                        <th style="">Propritor's Mobile No</th><th>:</th>
                        <td>
                            <input type="text" id="mobile_no"  value="<?=$mobile_no?>" name="mobile_no" style="width: 90%" class="form-control col-md-7 col-xs-12">
                        </td>
                    </tr>
                    <tr>
                        <th style="">In Charge person</th><th>:</th>
                        <td>
                            <?php if(@$_GET['area_codeGET']){ ?>
                                <input name="tsm" type="hidden" id="tsm" class="form-control col-md-7 col-xs-12" tabindex="2" value="<?=$PID= find_a_field('area','PBI_ID','AREA_CODE='.$_GET['area_codeGET']);?>" style="width: 90%" />
                                <?php } else { ?>
                                <input name="tsm" type="hidden" id="tsm" class="form-control col-md-7 col-xs-12" tabindex="2" value="<?=$tsm?>" style="width: 90%" />
                            <?php } ?>
                            <?php if(@$_GET['area_codeGET']){ ?>
                                <input name="tsmNAME" type="text" class="form-control col-md-7 col-xs-12" id="tsmNAME" tabindex="2" value="<?=$PBI_ID_GET = find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$PID);?>" readonly="readonly" style="width: 90%" />
                            <?php } else { ?>
                                <input name="tsmNAME" type="text" class="form-control col-md-7 col-xs-12" id="tsmNAME" tabindex="2" value="<?=$PBI_ID_GET = find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$tsm);?>" readonly="readonly" style="width: 90%" />
                            <?php } ?>
                        </td>
                        <th style="">Contact Person</th><th>:</th>
                        <td>
                            <input type="text" id="contact_person"  value="<?=$contact_person?>" name="contact_person" class="form-control col-md-7 col-xs-12" style="width: 90%" />
                        </td>
                        <th style="">Depot Name</th><th>:</th>
                        <td>
                            <select class="select2_single form-control" name="depot" required id="depot" tabindex="7" style="width: 90%">
                                <?=foreign_relation('warehouse','warehouse_id','warehouse_name',$depot,' warehouse_type != "Purchase"');?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">Contact Person Mobile</th><th>:</th>
                        <td>
                            <input type="text" id="contact_number"  value="<?=$contact_number?>" name="contact_number" class="form-control col-md-7 col-xs-12" style="width: 90%" />
                        </td>
                        <th style="">Trade Scheme Type</th><th>:</th>
                        <td>
                            <select class="select2_single form-control" name="dealer_type" required id="dealer_type" tabindex="3" style="width: 90%">
                                <option></option>
                                <?=advance_foreign_relation($res_daeler_type,$dealer_type);?>
                            </select>
                        </td>
                        <th style="">Designation</th><th>:</th>
                        <td>
                            <input type="text" id="contact_person_desig"  value="<?=$contact_person_desig?>" name="contact_person_desig" class="form-control col-md-7 col-xs-12" style="width: 90%" />
                        </td>
                    </tr>
                    <tr>
                        <th style="">Customer Type</th><th>:</th>
                        <td>
                            <select class="select2_single form-control" name="customer_type" required id="customer_type" tabindex="3" style="width: 90%">
                                <option></option>
                                <?=advance_foreign_relation($res_daeler_type,$customer_type);?>
                            </select>
                        </td>
                        <th style="">Address</th><th>:</th>
                        <td>
                            <textarea id="address_e" name="address_e" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px"><?=$address_e?></textarea>
                        </td>
                        <th style="">Commission</th><th>:</th>
                        <td><?php $userid=$_SESSION['userid']; if($userid=='10019'){?>
                                <input type="text" id="commission"  value="<?=$commission?>" name="commission" class="form-control col-md-7 col-xs-12" style="width: 90%"><?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <th style="">National ID</th><th>:</th>
                        <td>
                            <input type="text" id="national_id"  value="<?=$national_id?>" name="national_id" class="form-control col-md-7 col-xs-12" style="width: 90%">
                        </td>
                        <th style="">Status</th><th>:</th>
                        <td>
                            <select class="select_single form-control" style="font-size: 11px; width: 90%" name="canceled" id="canceled" tabindex="12">
                                <option <?=($canceled=='Yes')?'Selected':'';?>>Yes</option>
                                <option <?=($canceled=='No')?'Selected':'';?> >No</option>
                            </select>
                        </td>
                        <th style="">TIN / BIN</th><th>:</th>
                        <td>
                            <input type="text" id="TIN_BIN"  value="<?=$TIN_BIN?>" name="TIN_BIN" class="form-control col-md-7 col-xs-12" style="width: 90%" />
                        </td>
                    </tr>

                    <tr>
                        <th style="">Bank</th><th>:</th>
                        <td>
                            <select class="select2_single form-control" name="bank_account" id="bank_account" tabindex="3" style="width: 90%">
                                <option></option>
                                <? foreign_relation('bank_account_name','id','concat(account_name)',$bank_account,'1');?>
                            </select>
                        </td>
                        <th style="">Accounts Code</th><th>:</th>
                        <td>
                        <?php if(@$_SESSION['userid']=='10019'): ?>
                            <input type="text" id="account_code"  value="<?=$account_code?>" name="account_code" class="form-control col-md-7 col-xs-12" style="width: 90%" />
                        <?php endif; ?>
                        </td>

                        <th style="">Dealer Category</th><th>:</th>
                        <td>
                            <input type="text" value="<?=$dealer_category?>" name="dealer_category" class="form-control col-md-7 col-xs-12" style="width: 90%" />
                        </td>
                    </tr>
                </table>
                <hr>
                <?php if(@$_GET[$unique]){  ?>
                    <button type="submit" name="update" id="update" style="float: right; font-size: 11px" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");'>Update Dealer Information</button>
                    <?php if(@$_SESSION['userid']=='10019'){ ?>
                    <?php } ?>
                <?php } else {?>
                    <button type="submit" name="insert" id="insert" style="float: right; font-size: 12px" class="btn btn-primary">Create New Dealer</button>
                <?php } ?>
            </form>
        </div>
    </div>
</div>
<?php if(!isset($_GET[$unique])){ ?>
<?=$crud->report_templates_with_title_and_class($res,'Dealer List','12');?>
<?php } ?>
<?=$html->footer_content();mysqli_close($conn);?>