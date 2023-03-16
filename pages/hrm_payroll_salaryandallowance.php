<?php
require_once 'support_file.php';
// ::::: Edit This Section :::::
$title='Salary Breakdown';			// Page Name and Page Title
$page="hrm_payroll_salaryandallowance.php";		// PHP File Name
$input_page="employee_essential_information_input.php";
$root='hrm';
$table='salary_info';		// Database Table Name Mainly related to this page
$unique='id';			// Primary Key of this Database table
$PBI_unique='PBI_IID';
$shown='gross_salary';				// For a New or Edit Data a must have data field
//do_calander('#ESSENTIAL_ISSUE_DATE');
$_SESSION['HRM_payroll_employee']=$_GET[$unique];
$datas=find_all_field('personnel_basic_info','','PBI_ID='.$_GET[$unique].'');

// ::::: End Edit Section :::::
$crud      =new crud($table);


$saalry_policy=find_all_field('hrm_salary_policy','','status="1"');
if(isset($_POST['proceed_to_next']))
{$_SESSION[HRM_payroll_employee]=$_POST[PBI_ID];

}

$required_id=find_a_field($table,$unique,'PBI_ID='.$_SESSION['HRM_payroll_employee'],' order by id desc limit 1');
if($required_id>0)
    $$unique = $_GET[$unique] = $required_id;


if(isset($_POST['cancel_proceed_to_next'])){
    unset($_POST);
    unset($$unique);
    unset($_SESSION['HRM_payroll_employee']);
}


if(isset($_POST[$shown]))
{	if(isset($_POST['insert']))
{
    $crud->insert();
    $type=1;
    $msg='New Entry Successfully Inserted.';
    unset($_POST);
    unset($$unique);
    $required_id=find_a_field($table,$unique,'PBI_ID='.$_SESSION['HRM_payroll_employee'],' order by id desc limit 1');
    if($required_id>0)
        $$unique = $_GET[$unique] = $required_id;
}
    if(isset($_POST['reset'])){
        unset($_POST);
        unset($$unique);
        unset($_SESSION['HRM_payroll_employee']);
    }
    //for Modify..................................
    if(isset($_POST['update']))
    {
        $crud->update($unique);
        $type=1;
    }
    //for Delete..................................
    if(isset($_POST['delete']))
    {		$condition=$unique."=".$$unique;		$crud->delete($condition);
        unset($$unique);
        echo '<script type="text/javascript">
parent.parent.document.location.href = "../'.$page.'";
</script>';
        $type=1;
        $msg='Successfully Deleted.';
    }
}

if(isset($$unique))
{
    $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}
}
$sql_user_id="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' (',des.DESG_SHORT_NAME,' - ',d.DEPT_SHORT_NAME,')') FROM 						 
							personnel_basic_info p,
							department d,
							designation des,
							users u
							 where p.PBI_JOB_STATUS='In Service' and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID and 
							 u.PBI_ID=p.PBI_ID and
							 p.PBI_DESIGNATION=des.DESG_ID	 
							  order by p.PBI_NAME";
?>






<?php require_once 'header_content.php'; ?>
    <script type="text/javascript"> function DoNav(lk){
            return GB_show('ggg', '../pages/<?=$root?>/<?=$input_page?>?<?=$unique?>='+lk,600,940)
        }</script>
<script type="text/javascript"> function DoNav(lk){document.location.href = '<?=$page?>?<?=$unique?>='+lk;}
    function popUp(URL)
    {   day = new Date();
        id = day.getTime();
        eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=800,left = 383,top = -16');"); }
</script>
<?php require_once 'body_content.php'; ?>





<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?=$title;?></h2>
                    <div class="clearfix"></div>
                </div>

                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Select Employee<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 70%; flot:left" tabindex="-1" required="required" name="PBI_ID" id="PBI_ID">
                                <option></option>
                                <?=advance_foreign_relation($sql_user_id,$_SESSION[HRM_payroll_employee]);?>
                            </select>
                            <?php if(isset($_SESSION[HRM_payroll_employee])): ?>
                                <button type="submit" name="cancel_proceed_to_next" class="btn btn-danger" style="font-size: 12px; margin-left:5%">Cancel the Employee</button>
                            <?php  else: ?>
                                <button type="submit" name="proceed_to_next" class="btn btn-primary" style="font-size: 12px; margin-left:5%">Proceed to the next</button>
                            <?php endif; ?>
                        </div></div></form>
            </div></div>

<?php if(isset($_SESSION[HRM_payroll_employee])): ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <form action="" method="post" enctype="multipart/form-data" style="font-size: 11px">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="oe_form_group ">
                                                                    <tr>
                                                                        <th width="15%">Gross Salary</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td width="33%" ><input name="<?=$unique?>" id="<?=$unique?>" value="<?=$$unique?>" type="hidden" />
                                                                            <input name="PBI_ID" id="PBI_ID" value="<?=$_SESSION['HRM_payroll_employee']?>" type="hidden" />
                                                                        <input name="gross_salary" type="text" id="gross_salary" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle" value="<?=$gross_salary?>" onkeyup="salaryCal()"/></td>


                                                                        <th width="15%"><span >Bonus Applicable? :</span></th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td width="33%">
                                                                            <select class="form-control" name="if_bonus_applicable" id="if_bonus_applicable" style="float: left; width: 20%; font-size: 11px" onchange="bonusAppl(), salaryCal()">
                                                                                <option selected="selected"><?=$if_overtime_applicable?></option>
                                                                                <option>YES</option>
                                                                                <option>NO</option>
                                                                            </select>
                                                                            <input name="bonus_applicable" type="text" id="bonus_applicable" value="<?=$bonus_applicable?>" class="form-control col-md-7 col-xs-12" style="width: 68%; font-size: 11px;vertical-align:middle; margin-left: 2%" onkeyup="salaryCal()" />
                                                                        </td>
                                                                    </tr>



                                                                    <tr>
                                                                        <th>Basic Salary</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td><input placeholder="<?=$saalry_policy->basic?>% Of Gross Amount" name="basic_salary" type="text" id="basic_salary" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle; margin-top: 5px" value="<?=$basic_salary?>" readonly="readonly" /></td>
                                                                        <th>Overtime Applicable?</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td><select class="form-control" style="float: left; width: 20%; font-size: 11px;margin-top: 5px" name="if_overtime_applicable" id="if_overtime_applicable" onchange="overtimeAllwAppl()">
                                                                                <option selected="selected"><?=$if_overtime_applicable?></option>
                                                                                <option>YES</option>
                                                                                <option>NO</option>
                                                                            </select>
                                                                            <input name="overtime_applicable" type="text" id="overtime_applicable" class="form-control col-md-7 col-xs-12" style="margin-left:2%; width: 68%; font-size: 11px;vertical-align:middle; margin-top: 5px" value="<?=$overtime_applicable?>"  onkeyup="salaryCal()" />
                                                                        </td>
                                                                    </tr>





                                                                    <tr>
                                                                        <th>HRA</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td><input placeholder="<?=$saalry_policy->house_rent_allowance?>% Of Gross Amount" name="house_rent" type="text" id="house_rent" value="<?=$house_rent?>" readonly="readonly" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle; margin-top: 5px" /></td>
                                                                        <th>PF Applicable?</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td><select class="form-control" style="float: left; width: 20%; font-size: 11px;margin-top: 5px" name="pf_applicable" id="pf_applicable" onchange="pfAllwAppl(),salaryCal()">
                                                                                <option selected="selected"><?=$if_overtime_applicable?></option>
                                                                                <option>YES</option>
                                                                                <option>NO</option>
                                                                            </select>
                                                                            <input name="pf_percentage" type="text" id="pf_percentage" value="<?=$pf_percentage?>" class="form-control col-md-7 col-xs-12" style="width: 20%; font-size: 11px;vertical-align:middle; margin-top: 5px; margin-left: 2%" style="width:30px" onkeyup="salaryCal()"/>
                                                                            <input name="pf" type="text" id="pf" value="<?=$pf?>" class="form-control col-md-7 col-xs-12" style="width: 47%; font-size: 11px;vertical-align:middle; margin-top: 5px; margin-left: 1%" />
                                                                        </td>
                                                                    </tr>




                        <tr>
                            <th>Medical Allowance</th>
                            <th style="width: 2%;">:</th>
                            <td><input placeholder="<?=$saalry_policy->medical_allowance?>% Of Gross Amount" name="medical_allowance" type="text" id="medical_allowance" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle; margin-top: 5px" value="<?=$medical_allowance?>" readonly="readonly" /></td>
                            <th>Medical Insurance Applicable?</th>
                            <th style="width: 2%;">:</th>
                            <td><select class="form-control" style="float: left; width: 20%; font-size: 11px;margin-top: 5px" name="mi_applicable" id="mi_applicable" onchange="miAllwAppl()">
                                    <option selected="selected"><?=$mi_applicable?></option>
                                    <option>YES</option>
                                    <option>NO</option>
                                </select>
                                <input onkeyup="salaryCal()" name="mi_percentage" type="text" id="mi_percentage" value="<?=$mi_percentage?>" class="form-control col-md-7 col-xs-12" style="width: 20%; font-size: 11px;vertical-align:middle; margin-top: 5px; margin-left: 2%" />
                                <input name="medical_insurance" type="text" id="medical_insurance" value="<?=$medical_insurance?>" class="form-control col-md-7 col-xs-12" style="width: 47%; font-size: 11px;vertical-align:middle; margin-top: 5px; margin-left: 1%" />
                            </td>
                        </tr>



                        <tr>
                            <th>Convenience</th>
                            <th style="width: 2%;">:</th>
                            <td><input placeholder="<?=$saalry_policy->convenience?>% Of Gross Amount" name="convenience" type="text" id="convenience" value="<?=$convenience?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle; margin-top: 5px" readonly="readonly" /></td>
                            <th>Food Allowance?</th>
                            <th style="width: 2%;">:</th>
                            <td><select class="form-control" style="float: left; width: 20%; font-size: 11px;margin-top: 5px" name="food_alw_applicable" id="food_alw_applicable" onchange="foodAppl()">
                                    <option selected="selected">
                                        <?=$food_alw_applicable?>
                                    </option>
                                    <option>YES</option>
                                    <option>NO</option>
                                </select>
                                <input onkeyup="salaryCal()" name="food_allowance" type="text" id="food_allowance" value="<?=$food_allowance?>" class="form-control col-md-7 col-xs-12" style="width: 68%; font-size: 11px;vertical-align:middle; margin-top: 5px; margin-left: 2%" />
                            </td>
                        </tr>



                        <tr>
                            <th>Special Allowance</th>
                            <th style="width: 2%;">:</th>
                            <td><input placeholder="<?=$saalry_policy->special_allowance?>% Of Gross Amount" name="special_allowance" type="text" id="special_allowance" value="<?=$special_allowance?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle; margin-top: 5px" readonly="readonly" /></td>
                            <th>Mobile Allowance?</th>
                            <th style="width: 2%;">:</th>
                            <td><select class="form-control" style="float: left; width: 20%; font-size: 11px;margin-top: 5px" name="mobile_alw_applicable" id="mobile_alw_applicable" onchange="mobileAppl()">
                                    <option selected="selected"><?=$mobile_alw_applicable?></option>
                                    <option>YES</option>
                                    <option>NO</option>
                                </select>
                                <input onkeyup="salaryCal()" name="mobile_allowance" type="text" id="mobile_allowance" value="<?=$mobile_allowance?>" class="form-control col-md-7 col-xs-12" style="width: 68%; font-size: 11px;vertical-align:middle; margin-top: 5px; margin-left: 2%" />
                            </td>
                        </tr>
                        

                                                                    <tr>
                                                                        <th>Salary Pay Through</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td><select class="form-control" name="cash_bank" required style="width: 90%; font-size: 11px;margin-top: 5px">
                                                                                <option selected="selected">
                                                                                    <?=$cash_bank?>
                                                                                </option>
                                                                                <option value="cash">Cash</option>
                                                                                <option value="bank"> Bank</option>
                                                                            </select></td>
                                                                        <th>Transport Allowance</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td><select class="form-control" style="float: left; width: 20%; font-size: 11px;margin-top: 5px" name="transportAllwAppl" id="transportAllwAppl" onchange="trnsAppl()">
                                                                                <option selected="selected"><?=$transportAllwAppl?></option>
                                                                                <option>YES</option>
                                                                                <option>NO</option>
                                                                            </select>
                                                                            <input name="transport_allowance" type="text" id="transport_allowance" value="<?=$transport_allowance?>" class="form-control col-md-7 col-xs-12" style="width: 68%; font-size: 11px;vertical-align:middle; margin-left: 2%; margin-top: 5px" />
                                                                        </td>
                                                                    </tr></table><br>






                        <strong><h5>INCOME TAX CALCULATION</h5></strong><hr>




                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="oe_form_group ">
                            <tr>
                                <th width="15%">Total Taxable Amount</th>
                                <th style="width: 2%;">:</th>
                                <td width="33%">
                                    <input class="form-control col-md-7 col-xs-12" style="width: 45%; font-size: 11px;vertical-align:middle; margin-top: 5px;" onkeyup="" name="" type="text" id="" value=""  readonly />
                                    <input class="form-control col-md-7 col-xs-12" style="width: 45%; font-size: 11px;vertical-align:middle; margin-top: 5px;" onkeyup="salaryCal()" name="total_taxable_amt" type="text" id="total_taxable_amt" value="<?=$total_taxable_amt?>" style="width:100px" /></td>

                                <th width="15%"><span >Car Facilities? :</span></th>
                                <th style="width: 2%;">:</th>
                                <td width="33%">
						        <select class="form-control" style="float: left; width: 20%; font-size: 11px;margin-top: 5px" name="carFacilitiesAppl" id="carFacilitiesAppl" onchange="carFacilitiesAppll(), salaryCal()">
                                <option selected="selected"><?=$carFacilitiesAppl?></option>
                                <option>YES</option>
                                <option>NO</option>
                                </select>
                                    <input class="form-control col-md-7 col-xs-12" style="width: 68%; font-size: 11px;vertical-align:middle; margin-top: 5px; margin-left: 2%" name="carFacilitiesAmt" type="text" id="carFacilitiesAmt" value="<?=$carFacilitiesAmt?>" style="width:100px"/>
                          </span></td>
                            </tr>
                                                                    <tr>
                                                                        <th>Investment Amount : </th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td>
                                                                            <input onkeyup="salaryCal()" name="max_invested_amt" type="text" id="max_invested_amt" value="" class="form-control col-md-7 col-xs-12" style="width: 45%; font-size: 11px;vertical-align:middle; margin-top: 5px;" readonly />
                                                                            <input onkeyup="salaryCal()" name="total_invested_amt" type="text" id="total_invested_amt" value="<?=$total_invested_amt?>" class="form-control col-md-7 col-xs-12" style="width: 45%; font-size: 11px;vertical-align:middle; margin-top: 5px;" />
                                                                        </td>


                                                                        <th>Income Tax Yearly:</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td><input onkeyup="salaryCal()" name="income_tax_yearly" type="text" id="income_tax_yearly" value="<?=$income_tax_yearly?>" readonly class="form-control col-md-7 col-xs-12" style="width: 45%; font-size: 11px;vertical-align:middle; margin-top: 5px;" /></td>
                                                                    </tr>



                                                                    <tr>
                                                                        <th>AIT</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td>
                                                                            <input onkeyup="" name="" type="text" id="" value="" class="form-control col-md-7 col-xs-12" style="width: 45%; font-size: 11px;vertical-align:middle; margin-top: 5px;" readonly />
                                                                            <input onkeyup="salaryCal()" name="advance_IT" type="text" id="advance_IT" value="<?=$advance_IT?>" class="form-control col-md-7 col-xs-12" style="width: 45%; font-size: 11px;vertical-align:middle; margin-top: 5px;" /></td>
                                                                        <th>Income Tax Monthly</th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td><input onkeyup="salaryCal()" name="income_tax" type="text" id="income_tax" value="<?=$income_tax?>" readonly class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle; margin-top: 5px;" /></td>
                                                                    </tr>

                                                                    <tr>

                                                                        <td colspan="4" ><span id="taxDetails"></span></td>
                                                                    </tr>


                                                                    <tr>
                                                                        <th>Total Payable Amt : </th>
                                                                        <th style="width: 2%;">:</th>
                                                                        <td><input name="total_payable_amount" type="text" id="total_payable_amount" value="<?=$total_payable_amount?>" class="form-control col-md-7 col-xs-12" style="width: 45%; font-size: 11px;vertical-align:middle; margin-top: 5px;" readonly /></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr></table>
                        <br>

                        <?php if($required_id>0){  ?>
                            <div class="form-group" style="float: right">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button type="submit" name="modify" id="modify" class="btn btn-primary">Update Salary Info</button>
                                </div></div>
                            <? if($_SESSION['userid']=="10019"){?>
                                <div class="form-group" style="float: left">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  name="delete" type="submit" class="btn btn-danger" id="delete" value="Delete Salary Info"/>
                                    </div></div>
                            <? }?>
                        <?php } else {?>
                            <div class="form-group" style="float: right">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button type="submit" name="insert" id="insert"  class="btn btn-primary">Add Salary Info </button>
                                </div></div>



                                <div class="form-group" style="float: left">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  name="reset" type="submit" class="btn btn-danger" id="reset" value="Reset Salary Info"/>
                                    </div></div>

                        <?php } ?>

    </form></div></div></div>


<?php endif; ?>
<?=$html->footer_content();?>

<script>

    function foodAppl()
    {
        var status = document.getElementById('food_alw_applicable').value;
        if(status!="YES"){
            document.getElementById('food_allowance').setAttribute("readonly", "readonly");
            document.getElementById('food_allowance').value='0.00';}
        if(status=="YES"){
            document.getElementById('food_allowance').removeAttribute("readonly", "readonly");}
    }


    function trnsAppl()
    {
        var status = document.getElementById('transportAllwAppl').value;
        if(status!="YES"){
            document.getElementById('transport_allowance').setAttribute("readonly", "readonly");
            document.getElementById('transport_allowance').value='0.00';}
        if(status=="YES"){
            document.getElementById('transport_allowance').removeAttribute("readonly", "readonly");}
    }

    function mobileAppl()
    {
        var status = document.getElementById('mobile_alw_applicable').value;
        if(status!="YES"){
            document.getElementById('mobile_allowance').setAttribute("readonly", "readonly");
            document.getElementById('mobile_allowance').value='0.00';}
        if(status=="YES"){
            document.getElementById('mobile_allowance').removeAttribute("readonly", "readonly");}
    }



    function bonusAppl()
    {
        var status = document.getElementById('if_bonus_applicable').value;
        if(status!="YES"){
            document.getElementById('bonus_applicable').setAttribute("readonly", "readonly");
            document.getElementById('bonus_applicable').value='0.00';}
        if(status=="YES"){
            //document.getElementById('bonus_applicable').removeAttribute("readonly", "readonly");
            var gross_salary = document.getElementById('gross_salary').value*1;
            document.getElementById('bonus_applicable').value= ((gross_salary*<?=$saalry_policy->bonus?>)/100);;
        }
    }

    function overtimeAllwAppl()
    {
        var status = document.getElementById('if_overtime_applicable').value;
        if(status!="YES"){
            document.getElementById('overtime_applicable').setAttribute("readonly", "readonly");
            document.getElementById('overtime_applicable').value='0.00';}
        if(status=="YES"){
            document.getElementById('overtime_applicable').removeAttribute("readonly", "readonly");}
    }

    function pfAllwAppl()
    {
        var status = document.getElementById('pf_applicable').value;
        if(status!="YES"){
            document.getElementById('pf_percentage').setAttribute("readonly", "readonly");
            document.getElementById('pf').setAttribute("readonly", "readonly");
            document.getElementById('pf_percentage').value='0.00';
            document.getElementById('pf').value='0.00';}
        if(status=="YES"){
            document.getElementById('pf_percentage').removeAttribute("readonly", "readonly");
            document.getElementById('pf').removeAttribute("readonly", "readonly");}
    }

    function miAllwAppl()
    {
        var status = document.getElementById('mi_applicable').value;
        if(status!="YES"){
            document.getElementById('mi_percentage').setAttribute("readonly", "readonly");
            document.getElementById('medical_insurance').setAttribute("readonly", "readonly");
            document.getElementById('mi_percentage').value='0.00';
            document.getElementById('medical_insurance').value='0.00';}
        if(status=="YES"){
            document.getElementById('mi_percentage').removeAttribute("readonly", "readonly");
            document.getElementById('medical_insurance').removeAttribute("readonly", "readonly");}
    }

    function carFacilitiesAppll()
    {
        var status = document.getElementById('carFacilitiesAppl').value;
        if(status!="YES"){
            document.getElementById('carFacilitiesAmt').setAttribute("readonly", "readonly");
            document.getElementById('carFacilitiesAmt').value='0.00';}
        if(status=="YES"){
            //document.getElementById('carFacilitiesAmt').removeAttribute("readonly", "readonly");
            var basic_salaryy = document.getElementById('basic_salary').value;
            var carFacilitiesTaxAct = ((basic_salaryy*12)*5)/100;
            if(carFacilitiesTaxAct<60000){
                var carFacilitiesTaxabl = 60000;
            }else{
                var carFacilitiesTaxabl = carFacilitiesTaxAct;
            }
            document.getElementById('carFacilitiesAmt').value=carFacilitiesTaxabl.toFixed(2);
        }
    }



    window.onload = function(){
        foodAppl(); mobileAppl(); bonusAppl(); overtimeAllwAppl(); pfAllwAppl(); trnsAppl(); miAllwAppl(), carFacilitiesAppll();

    }
    //window.onload= foodAppl, mobileAppl, bonusAppl, overtimeAllwAppl, pfAllwAppl;
    //window.onload= trnsAppl;
</script>


<script>
    function salaryCal(){
        var gross_salary = document.getElementById('gross_salary').value*1;
        var basic_salary = document.getElementById('basic_salary').value= ((gross_salary*<?=$saalry_policy->basic?>)/100);
        var pf_percentage = document.getElementById('pf_percentage').value*1;
        var pf = document.getElementById('pf').value= ((gross_salary*pf_percentage)/100);
        var mi_percentage = document.getElementById('mi_percentage').value*1;
        var medical_insurance = document.getElementById('medical_insurance').value= ((gross_salary*mi_percentage)/100);
        var house_rent = document.getElementById('house_rent').value= ((gross_salary*<?=$saalry_policy->house_rent_allowance?>)/100);
        var medical_allowance =document.getElementById('medical_allowance').value= ((gross_salary*<?=$saalry_policy->medical_allowance?>)/100);
        var convenience = document.getElementById('convenience').value= ((gross_salary*<?=$saalry_policy->convenience?>)/100);
        var special_allowance= document.getElementById('special_allowance').value= ((gross_salary*<?=$saalry_policy->special_allowance?>)/100);
        var food_allowance = document.getElementById('food_allowance').value*1;
        var mobile_allowance = document.getElementById('mobile_allowance').value*1;
//var medical_insurance = document.getElementById('medical_insurance').value*1;
        var bonus_applicable = document.getElementById('bonus_applicable').value*1;
        var overtime_applicable = document.getElementById('overtime_applicable').value *1;
        var carFacilitiesAmt = document.getElementById('carFacilitiesAmt').value *1;
        var advance_IT = document.getElementById('advance_IT').value *1;
//var total_taxable_amt2 = document.getElementById('total_taxable_amt').value *1;

        var total_invested_amt = document.getElementById('total_invested_amt').value *1;




        var yearly_basic = basic_salary*12;

        var yearly_houseRent = house_rent*12;
        if(yearly_houseRent>300000){
            var taxable_houseRent = yearly_houseRent-300000;
        }else{
            var taxable_houseRent = 0;
        }

        var yearly_convenience = convenience*12;
        if(yearly_convenience>30000){
            var taxable_convenience = yearly_convenience-30000;
        }else{
            var taxable_convenience = 0;
        }

        var yearly_medicalAlw = medical_allowance*12;
        if(yearly_medicalAlw>120000){
            var taxable_medicalAlw = yearly_medicalAlw-120000;
        }else{
            var medicalAlwAct = (yearly_basic*10)/100;
            var taxable_medicalAlw = yearly_medicalAlw-medicalAlwAct;
        }

        var yearly_specialAlw= special_allowance*12;

        if(bonus_applicable>0){
            var yearly_eidBonus = <?=$saalry_policy->bonus?>;
        }else{
            var yearly_eidBonus = 0;
        }

        if(pf>0){
            var yearly_pf = (yearly_basic*pf_percentage)/100;}
        else{
            var yearly_pf = 0;
        }


        var invest_amt_tax1=0, invest_amt_tax2=0, invest_amt_tax3=0;

        if(total_invested_amt>250000){
            var invest_amt_tax1 = 37500;
        }else{
            var invest_amt_tax1 = (total_invested_amt*15)/100;
        }

        if(total_invested_amt>750000){
            var invest_amt_tax2 = 60000;
        }else if(total_invested_amt>250000 && total_invested_amt<750000){
            var invest_amt_tax2 = ((total_invested_amt-250000)*12)/100;
        }

        if(total_invested_amt>750000){
            var invest_amt_tax3 = ((total_invested_amt-750000)*10)/100;
        }
        var invest_amt_tax = invest_amt_tax1+ invest_amt_tax2+ invest_amt_tax3;



        var total_taxable = yearly_basic+taxable_houseRent+taxable_convenience+taxable_medicalAlw+yearly_specialAlw+yearly_eidBonus+yearly_pf+carFacilitiesAmt;

        var actualTax1=0, actualTax2=0, actualTax3=0, actualTax4=0, actualTax5=0;



        var pbi_gender = '<?=$pbi_info->PBI_SEX?>';
        if(pbi_gender=='Female'){
            var examptAmtGender = 300000;
        }else{
            var examptAmtGender = 250000;
        }


        var firstTaxAmt = total_taxable-examptAmtGender;

        if(firstTaxAmt>0){
            if(firstTaxAmt>400000){
                actualTax1 = 40000;
            }else{
                actualTax1 = (firstTaxAmt*10)/100;
            }

            if(firstTaxAmt>900000){
                actualTax2 = 75000;
            }else if(firstTaxAmt>400000 && firstTaxAmt<900000){
                actualTax2 = ((firstTaxAmt-400000)*15)/100;
            }

            if(firstTaxAmt>1500000){
                actualTax3 = 120000;
            }else if(firstTaxAmt>900000 && firstTaxAmt<1500000){
                actualTax3 = ((firstTaxAmt-900000)*20)/100;
            }

            if(firstTaxAmt>4500000){
                actualTax4 = 750000;
            }else if(firstTaxAmt>1500000 && firstTaxAmt<4500000){
                actualTax4 = ((firstTaxAmt-1500000)*25)/100;
            }

            if((firstTaxAmt-4500000)>0){
                actualTax5 = ((firstTaxAmt-4500000)*30)/100;
            }



            var totalTax = actualTax1+ actualTax2+ actualTax3+ actualTax4+ actualTax5- (invest_amt_tax + advance_IT);
            if(totalTax<5000){
                totalTax=5000;
            }
            var monthlyTax = totalTax/12;
        }

        if(monthlyTax>0){
            document.getElementById('income_tax').value = monthlyTax.toFixed(2);
            document.getElementById('income_tax_yearly').value = totalTax.toFixed(2);
            document.getElementById('total_taxable_amt').value = total_taxable.toFixed(2);
        }else{
            document.getElementById('income_tax').value = "";
        }
        var income_tax = document.getElementById('income_tax').value*1;
        document.getElementById('total_payable_amount').value = ((gross_salary)-(income_tax+pf+medical_insurance)).toFixed(2);


        var total_invested_amt_appl = (total_taxable*25)/100;
        document.getElementById('max_invested_amt').value = 'Max: '+total_invested_amt_appl;
        if(total_invested_amt>total_invested_amt_appl){
            alert('You Crossed the Limit 25% of Total Taxable Income');
            document.getElementById('total_invested_amt').value = 0;
        }else{
            document.getElementById('total_invested_amt').value = total_invested_amt;
        }


//document.getElementById('taxDetails').innerHTML = 'YB-'+yearly_basic + ' TH-'+taxable_houseRent+ ' TC-'+taxable_convenience+ ' TM-'+taxable_medicalAlw+ ' YS-'+yearly_specialAlw+ ' YE-'+yearly_eidBonus+ ' TT-'+total_taxable+ ' T1-'+actualTax1+ ' T2-'+actualTax2+ ' T3-'+actualTax3+ ' T4-'+actualTax4+ ' T5-'+actualTax5+ ' YTT-'+totalTax+ ' IAT-'+invest_amt_tax+ ' AIT-'+advance_IT;
    }
</script>
