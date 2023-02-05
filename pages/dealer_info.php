<?php

require_once 'support_file.php';
$title='Add New Dealer';


// ::::: Edit This Section :::::
			// Page Name and Page Title
$page="dealer_info.php";		// PHP File Name
$table='dealer_info';		// Database Table Name Mainly related to this page
$unique='dealer_code';			// Primary Key of this Database table
$shown='dealer_name_e';
$dealer_custom_codess='dealer_custom_code';				// For a New or Edit Data a must have data field
// ::::: End Edit Section :::::
//if(isset($_GET['proj_code'])) $proj_code=$_GET[$proj_code];
$crud      =new crud($table);
$$unique = $_GET[$unique];




if(isset($_POST[$shown]))
{
$$unique = $_POST[$unique];
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





//for Modify..................................



if(isset($_POST['update']))
{       $crud->update($unique);
		$type=1;
		$msg='Successfully Updated.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}


//for Delete..................................



if(isset($_POST['delete']))
{		$condition=$unique."=".$$unique;		$crud->delete($condition);
		unset($$unique);
		$type=1;
		$msg='Successfully Deleted.';?>
    <meta http-equiv="refresh" content="0;dealer_info.php">

<?php }

}



if(isset($$unique))
{
$condition=$unique."=".$$unique;
$data=db_fetch_object($table,$condition);
while (list($key, $value)=each($data))
{ $$key=$value;}
}
if(!isset($$unique)) $$unique=db_last_insert_id($table,$unique);

?>


<?php if(isset($_POST['update']))
{
    mysqli_query($conn, "Update sale_do_master set region='$_POST[region]',territory='$_POST[territory]',area_code='$_POST[area_code]',town='$_POST[town_code]',dealer_type='$_POST[customer_type]' where dealer_code='$_GET[dealer_code]'");
    mysqli_query($conn, "Update sale_do_details set region='$_POST[region]',territory='$_POST[territory]',area_code='$_POST[area_code]',town='$_POST[town_code]',dealer_type='$_POST[customer_type]' where dealer_code='$_GET[dealer_code]'");
    mysqli_query($conn, "Update sale_do_chalan set region='$_POST[region]',territory='$_POST[territory]',aria='$_POST[area_code]',town='$_POST[town_code]',dealer_type='$_POST[customer_type]' where dealer_code='$_GET[dealer_code]'");
    mysqli_query($conn, "Update ims_details set region='$_POST[region]',territory='$_POST[territory]',area_id='$_POST[area_code]',town_code='$_POST[town_code]',dealer_type='$_POST[customer_type]' where dealer_code='$_GET[dealer_code]'");
    mysqli_query($conn, "Update sale_return_master set region='$_POST[region]',territory='$_POST[territory]',area_code='$_POST[area_code]',town='$_POST[town_code]',dealer_type='$_POST[customer_type]' where dealer_code='$_GET[dealer_code]'");
    mysqli_query($conn, "Update sale_return_details set region='$_POST[region]',territory='$_POST[territory]',area_code='$_POST[area_code]',town='$_POST[town_code]',dealer_type='$_POST[customer_type]' where dealer_code='$_GET[dealer_code]'");


}

$sql_area = 'select a.AREA_CODE,concat(AREA_CODE," : ",a.AREA_NAME) from area a  where Territory_CODE>0 order by a.AREA_NAME';
if($_GET[area_codeGET]>0){
	$area_code=$_GET[area_codeGET];
} else {
	$area_code=$area_code;
	}
$res='select d.'.$unique.',d.'.$dealer_custom_codess.' as Code,d.account_code,d.'.$shown.' as dealer_name,d.dealer_category as Category,d.dealer_type as Screm_Type,d.customer_type as DB_Type,d.credit_limit as Credit_Limit,d.commission,(select account_name from bank_account_name where id=d.bank_account) as bank_account,d.canceled as status from '.$table.' d where
 1 order by '.$unique;
$sql_TOWN="Select town_code,concat(town_code,' : ',town_name) from town order by town_name";
$res_daeler_type="Select typeshorname,typedetails from distributor_type order by id";

?>

<?php require_once 'header_content.php'; ?>
        <script type="text/javascript">
            function DoNavPOPUP(lk)
            {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=730,height=600,left = 383,top = -1");}
        </script>
        <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.area_code.options[form.area_code.options.selectedIndex].value;
	self.location='dealer_info.php?dealer_code=<?=$_GET[dealer_code]?>&area_codeGET=' + val ;
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


                        <!-- input section-->
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2><?=$title?></h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <div class="input-group pull-right"> </div>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <form action="" enctype="multipart/form-data" style="font-size:11px" method="post" name="addem" id="addem" >
                                        <table style="width:100%; font-size: 11px">
                                            <tr>


                      <div style="display:none">
                        <label> Dealer Code:</label>
                        <input name="<?=$unique?>" id="<?=$unique?>" value="<?=$$unique?>" type="hidden" />
                        <input name="dealer_code" type="text" id="dealer_code" tabindex="1" value="<?=$dealer_code?>" readonly>
                      </div>
                                                <td style="width: 50%">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Territory<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" name="area_code" id="area_code" tabindex="11" style="width: 100%" onchange="javascript:reload(this.form)">
                                                <option></option>
                                                 <?=advance_foreign_relation($sql_area,$area_code);?>
                                                </select>
                                            </div></div></td>

                                                <td>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Country<span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="select2_single form-control" name="country" id="country" style="width: 100%"  tabindex="11">
                                                                <option value="1" selected>Bangladesh</option>

                                                                <?
                                                                //$countryquery =mysql_query('select * from apps_countries order by country_name');

                                                                //echo '<option></option>';
                                                                while($Cnrow = mysql_fetch_array($countryquery)){

                                                                    if($country==$Cnrow[BRANCH_ID]){ ?>
                                                                        <option value="<?=$Cnrow[id];?>" selected><?=$Cnrow[country_name];?></option>
                                                                    <?php } else { ?>
                                                                        <option value="<?=$Cnrow[id];?>"><?=$Cnrow[country_name];?></option>
                                                                    <?php }}?>
                                                            </select></div></div></td> </tr>


                                                <tr><td>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Custom Code<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input name="serial" type="text" required id="serial" tabindex="10" style="width: 30%; float:left;" placeholder="serial" value="<?=$serial?>" class="form-control col-md-7 col-xs-12" />
                                                <input type="text" id="dealer_custom_code"  value="<?=$dealer_custom_code?>" placeholder="custom code" style="width: 68%; float:right; margin-left: 1px" name="dealer_custom_code" class="form-control col-md-7 col-xs-12">
                                            </div></div></td>



                                                    <td>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Region<span class="required">*</span>
                                                            </label>
                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                <?php if($_GET[area_codeGET]){ ?>
                                                                    <input name="region" type="hidden" id="region" tabindex="2" value="<?=$region= find_a_field('area','Region_code','AREA_CODE='.$_GET[area_codeGET]);?>">
                                                                <?php } else { ?>
                                                                    <input name="region" type="hidden" id="region" tabindex="2" value="<?=$region?>">
                                                                <?php } ?>
               <input type="text" id="regionName"  value="<?php
               if($_GET[area_codeGET])
                   echo $rg = find_a_field('branch','BRANCH_NAME','BRANCH_ID='.$region);
               else  echo $rg = find_a_field('branch','BRANCH_NAME','BRANCH_ID='.$region);
               ?>" name="regionName" class="form-control col-md-7 col-xs-12" readonly ></div></div></td>
                                                </tr>


                                        <tr><td>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Dealer Name<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="dealer_name_e"  value="<?=$dealer_name_e?>" name="dealer_name_e" class="form-control col-md-7 col-xs-12">
                                            </div></div></td>

                                            <td style="vertical-align: middle">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Area<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12" style="vertical-align: middle">
                                                        <input type="text" id="territory"  value="10" name="territory" style="width: 100%; height: " readonly  class="form-control col-md-7 col-xs-12">
                                                        <!--a href="area.php" target="_blank"><img src="../page/images/add.png" style="height: 32px; width: 30px; margin-top:5px; margin-bottom: 5px; margin-left: 5px  "></a-->
                                                    </div></div></td></tr>



                                            <tr>
                                                <td>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Propritor's Name<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="propritor_name_e"  value="<?=$propritor_name_e?>" name="propritor_name_e" class="form-control col-md-7 col-xs-12">
                                            </div></div></td>

                                                <td>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Town<span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="select2_single form-control" name="town_code" required id="town_code"  tabindex="3">
                                                                <option></option>
                                                                <?=advance_foreign_relation($sql_TOWN,$town_code);?>
                                                                </select></div></div></td> </tr>

                                            <tr><td>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Propritor's Mobile No:<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="mobile_no"  value="<?=$mobile_no?>" name="mobile_no" class="form-control col-md-7 col-xs-12">
                                            </div></div></td>

                                                <td>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">In Charge person:<span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <?php if($_GET[area_codeGET]){ ?>
                                                                <input name="tsm" type="hidden" id="tsm" class="form-control col-md-7 col-xs-12" tabindex="2" value="<?=$PID= find_a_field('area','PBI_ID','AREA_CODE='.$_GET[area_codeGET]);?>" >
                                                            <?php } else { ?>
                                                                <input name="tsm" type="hidden" id="tsm" class="form-control col-md-7 col-xs-12" tabindex="2" value="<?=$tsm?>" >
                                                            <?php } ?>

                                                            <?php if($_GET[area_codeGET]){ ?>
                                                                <input name="tsmNAME" type="text" class="form-control col-md-7 col-xs-12" id="tsmNAME" tabindex="2" value="<?=$PBI_ID_GET = find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$PID);?>" readonly="readonly">
                                                            <?php } else { ?>
                                                                <input name="tsmNAME" type="text" class="form-control col-md-7 col-xs-12" id="tsmNAME" tabindex="2" value="<?=$PBI_ID_GET = find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$tsm);?>" readonly="readonly">
                                                            <?php } ?>  </div></div></td> </tr>

                                               <tr>
                                                   <td>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Contact Person<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="contact_person"  value="<?=$contact_person?>" name="contact_person" class="form-control col-md-7 col-xs-12">
                                            </div></div></td>

                                                   <td>
                                                       <div class="form-group">
                                                           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Depot Name<span class="required">*</span>
                                                           </label>
                                                           <div class="col-md-6 col-sm-6 col-xs-12">
                                                               <select class="select2_single form-control" name="depot" required id="depot" tabindex="7">

                                                                   <? foreign_relation('warehouse','warehouse_id','warehouse_name',$depot,' warehouse_type != "Purchase"');?>
                                                               </select>
                                                           </div></div></td></tr>




                                            <tr><td>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Contact Person Mobile<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="contact_number"  value="<?=$contact_number?>" name="contact_number" class="form-control col-md-7 col-xs-12" >
                                            </div></div></td>

                                                <td>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Trade Scheme Type<span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="select2_single form-control" name="dealer_type" required id="dealer_type" tabindex="3">
                                                                <option></option>
                                                                 <?=advance_foreign_relation($res_daeler_type,$dealer_type);?>
                                                                 </select></div></div></td> </tr>





                                                <tr>
                                                    <td>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Designation<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="contact_person_desig"  value="<?=$contact_person_desig?>" name="contact_person_desig" class="form-control col-md-7 col-xs-12">
                                            </div></div></td>


                                         <td>
                                             <div class="form-group">
                                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Customer Type<span class="required">*</span>
                                               </label>
                                               <div class="col-md-6 col-sm-6 col-xs-12">
                                                   <select class="select2_single form-control" name="customer_type" required id="customer_type" tabindex="3">
                                                       <option></option>
                                                       <?=advance_foreign_relation($res_daeler_type,$customer_type);?>
                                                       </select></div></div></td>
                                                </tr>

                                            <tr>
                                                <td>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Address<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea id="address_e" name="address_e" class="form-control col-md-7 col-xs-12" ><?=$address_e?></textarea>
                                            </div></div></td>



                                                <td>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Commission<span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <?php
                                                            $userid=$_SESSION['userid'];
                                                            if($userid=='10019'){
                                                            ?><input type="text" id="commission"  value="<?=$commission?>" name="commission" class="form-control col-md-7 col-xs-12"><?php } ?>
                                                        </div></div></td> </tr>



                                                <tr>
                                                    <td>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">National ID<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="national_id"  value="<?=$national_id?>" name="national_id" class="form-control col-md-7 col-xs-12" >
                                            </div></div></td>


                                                    <td>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Status<span class="required">*</span>
                                                            </label>
                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                <select class="select_single form-control" style="font-size: 11px" name="canceled" id="canceled" tabindex="12">

                                                                    <option <?=($canceled=='Yes')?'Selected':'';?>>Yes</option>

                                                                    <option <?=($canceled=='No')?'Selected':'';?> >No</option>
                                                                </select></div></div></td>

                                                </tr>






                                            <tr>

                                                <td>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">TIN / BIN<span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <input type="text" id="TIN_BIN"  value="<?=$TIN_BIN?>" name="TIN_BIN" class="form-control col-md-7 col-xs-12" >
                                                        </div></div></td>
                                                <td>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Bank<span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="select2_single form-control" name="bank_account" id="bank_account" tabindex="3">
                                                                <option></option>
                                                                <? foreign_relation('bank_account_name','id','concat(account_name)',$bank_account,'1');?>
                                                            </select></div></div></td>

                                            </tr>

<?php if($_SESSION[userid]=='10019'){ ?>
																						<tr> <td>
				                                                    <div class="form-group">
				                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Accounts Code<span class="required">*</span>
				                                                        </label>
				                                                        <div class="col-md-6 col-sm-6 col-xs-12">
				                                                            <input type="text" id="account_code"  value="<?=$account_code?>" name="account_code" class="form-control col-md-7 col-xs-12" >
				                                                        </div></div></td>

																															</tr>
																															<?php } ?>


                                            <tr>
                                                <td align="center" colspan="2">
                                              <?php if($_GET[$unique]){  ?>
                                              <button type="submit" name="update" id="update" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");'>Update Dealer Inforamtion</button>
                                                  <?php if($_SESSION[userid]=='10019'){ ?>
                                                      <!--input class="btn" name="delete" class="btn btn-success" type="submit" id="delete" value="Delete"/-->
                                                  <?php } ?>
                                              <?php } else {?>
                                                  <button type="submit" name="insert" id="insert" class="btn btn-primary">Save Dealer Information</button>
                                              <?php } ?>
                                                </td>
                                            </tr>
                                        </table></form>

                            </div></div></div>
                    <!-- input section-->

<?php if(!isset($_GET[$unique])){ ?>
<?=$crud->report_templates_with_title_and_class($res,'Dealer List','12');?>
<?php } ?>
<?=$html->footer_content();mysqli_close($conn);?>
