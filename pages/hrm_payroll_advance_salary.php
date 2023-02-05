<?php

require_once 'support_file.php';
// ::::: Edit This Section :::::

$title='Advanced Payments';			// Page Name and Page Title
$page="hrm_payroll_advance_salary.php";		// PHP File Name
$table='salary_advance';		// Database Table Name Mainly related to this page
$unique='id';			// Primary Key of this Database table
$punique='PBI_ID';
$shown='advance_amt';				// For a New or Edit Data a must have data field

if($_GET[ISMAIL_ID]>0){
    $_SESSION[ISMAIL_ID]=$_GET[ISMAIL_ID];
} else {
    $_SESSION[ISMAIL_ID]=$_SESSION[ISMAIL_ID];
}


// ::::: End Edit Section :::::
// ::::: End Edit Section :::::
$crud      =new crud($table);
$$unique = $_GET[$unique];
if(isset($_POST[$shown]))
{
    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $now				= time();


        $_POST['PBI_ID']=$_SESSION['ISMAIL_ID'];
        for($i=0;$i<$_POST['total_installment'];$i++)
        {
            $_POST['installment_no'] = $i+1;
            $smon=$_POST['start_mon']+$i;
            $syear=$_POST['start_year'];
            $_POST['current_mon'] = date('m',mktime(1,1,1,$smon,1,$syear));
            $_POST['current_year'] = date('Y',mktime(1,1,1,$smon,1,$syear));

            $crud->insert();
        }


        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }




    if(isset($_POST['reset'])){

        unset($_POST);
        unset($$unique);
        unset($_SESSION[ISMAIL_ID]);
    }



//for Modify..................................



    if(isset($_POST['update']))

    {
        $crud->update($unique);
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
if(isset($$unique)) {
    $condition = $unique . "=" . $$unique;
    $data = db_fetch_object($table, $condition);
    while (list($key, $value) = @each($data)) {
        $$key = $value;
    }
}
if(!isset($$unique)) $$unique=db_last_insert_id($table,$unique);
$$unique = $_GET[$unique];
?>


<?php require_once 'header_content.php'; ?>

    <SCRIPT language=JavaScript>
        function reload(form)
        {
            var val=form.PBI_ID.options[form.PBI_ID.options.selectedIndex].value;
            self.location='<?=$page;?>?ISMAIL_ID=' + val ;
        }
    </script>
    <script type="text/javascript"> function DoNav(lk){
            document.location.href = '<?=$page?>?<?=$unique?>='+lk;
        }</script>
<?php require_once 'body_content.php'; ?>


    <!-- input section-->
    <div class="col-md-8 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>:: Advance Salary ::</h2>
                <ul class="nav navbar-right panel_toolbox">
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

        <form action="" method="post" enctype="multipart/form-data">
            <? require_once 'support_html.php';?>

            <div class="form-group" style="display: none">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?=$unique?><span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="<?=$unique?>" style="width:100%"     name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                </div></div>
            <br><br>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Employee Name :<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">

                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID" onchange="javascript:reload(this.form)">
                        <option></option>
                        <?php
                        $result=mysql_query("SELECT  p.*,d.* FROM 
							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME");
                        while($row=mysql_fetch_array($result)){  ?>
                            <option  value="<?=$row[PBI_ID]; ?>" <?php if($_SESSION['ISMAIL_ID']==$row[PBI_ID]) echo 'selected' ?>><?=$row[PBI_ID_UNIQUE]; ?>#><?=$row[PBI_NAME];?>#> (<?=$row[DEPT_SHORT_NAME];?>)</option>
                        <?php } ?></select>
                </div></div>
            <br><br>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Advance Amount :<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="advance_amt" style="width:100%"  required   name="advance_amt" value="<?=$advance_amt;?>" class="form-control col-md-7 col-xs-12" >
                </div></div>
<br><br>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Total Install<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="total_installment" style="width:100%"  required   name="total_installment" value="<?=$total_installment;?>" class="form-control col-md-7 col-xs-12" >
                </div></div>
            <br><br>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Start Month :<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="start_mon" style="width:100%" id="start_mon" required class="form-control col-md-7 col-xs-12">
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
                    </select>
                </div></div>
            <br><br>





            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Start Year :<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="start_year"  id="start_year" style="width:100%" required class="form-control col-md-7 col-xs-12">
                    <option <?=($start_year==date('Y'))?'selected':''?>><?=date('Y')?></option>
                                                                        <option <?=($start_year=='2013')?'selected':''?>>2013</option>
                                                                        <option <?=($start_year=='2014')?'selected':''?>>2014</option>
                                                                        <option <?=($start_year=='2015')?'selected':''?>>2015</option>
                                                                        <option <?=($start_year=='2016')?'selected':''?>>2016</option>
                </select></div></div>

            <? if($$unique>0){?>
                <br><br>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Current Month :<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="current_mon" style="width:100%;" id="current_mon" required class="form-control col-md-7 col-xs-12">
                                                                            <option value="1" <?=($current_mon=='1')?'selected':''?>>Jan</option>
                                                                            <option value="2" <?=($current_mon=='2')?'selected':''?>>Feb</option>
                                                                            <option value="3" <?=($current_mon=='3')?'selected':''?>>Mar</option>
                                                                            <option value="4" <?=($current_mon=='4')?'selected':''?>>Apr</option>
                                                                            <option value="5" <?=($current_mon=='5')?'selected':''?>>May</option>
                                                                            <option value="6" <?=($current_mon=='6')?'selected':''?>>Jun</option>
                                                                            <option value="7" <?=($current_mon=='7')?'selected':''?>>Jul</option>
                                                                            <option value="8" <?=($current_mon=='8')?'selected':''?>>Aug</option>
                                                                            <option value="9" <?=($current_mon=='9')?'selected':''?>>Sep</option>
                                                                            <option value="10" <?=($current_mon=='10')?'selected':''?>>Oct</option>
                                                                            <option value="11" <?=($current_mon=='11')?'selected':''?>>Nov</option>
                                                                            <option value="12" <?=($current_mon=='12')?'selected':''?>>Dec</option>
                    </select></div></div>
                                                                <br><br>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Current Year :<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                                                                        <select name="current_year" style="width:160px;" id="current_year" required class="form-control col-md-7 col-xs-12">
                                                                            <option <?=($current_year==date('Y'))?'selected':''?>><?=date('Y')?></option>
                                                                            <option <?=($current_year=='2013')?'selected':''?>>2013</option>
                                                                            <option <?=($current_year=='2014')?'selected':''?>>2014</option>
                                                                            <option <?=($current_year=='2015')?'selected':''?>>2015</option>
                                                                            <option <?=($current_year=='2016')?'selected':''?>>2016</option>
                                                                        </select></div></div>
                                                            <? }?>
            <br><br>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Monthly Payable :<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="payable_amt" style="width:100%"  required   name="payable_amt" value="<?=$payable_amt;?>" class="form-control col-md-7 col-xs-12" >
                </div></div>

            <br><br>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Install Type :<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="advance_type" id="advance_type" required class="form-control col-md-7 col-xs-12">
                                                                        <option></option>
                                                                        <option <?=($advance_type=='Advance Cash')?'selected':'';?>>Advance Cash</option>
                                                                        <option <?=($advance_type=='Other Advance')?'selected':'';?>>Other Advance</option>
                    </select></div></div>

            <br><br>
            <?php if($_GET[$unique]){  ?>
                <div class="form-group" style="float: left">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input  name="delete_all" type="submit" class="btn btn-danger" id="delete_all" value="Delete All"/>
                    </div></div>
                <div class="form-group" style="float: left">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input  name="delete" type="submit" class="btn btn-danger" id="delete" value="Delete"/>
                    </div></div>

                <div class="form-group" style="float: right">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <button type="submit" name="update" id="update" class="btn btn-success">Update Advance Info</button>
                    </div></div>



            <?php } else {?>
                <div class="form-group" >
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <button type="submit" name="record" id="record"  class="btn btn-success">Add New </button>
                    </div></div>
            <?php } ?>


            </div>
        </div></div>




    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>List of <?=$title;?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">                <? 	$res='select id, advance_type,payable_amt, installment_no,concat(current_mon,"-",current_year) as payable_month,total_installment ,	 concat(start_mon,"-",start_year) as start_month,advance_amt as total_advance_amt  from salary_advance where PBI_ID="'.$_SESSION['ISMAIL_ID'].'" order by id desc';

                                                    echo $crud->link_report($res,$link);?>

                                                </div></div>
                                        </div>
                                    </div>


        </form>

    </div>



<?php require_once 'footer_content.php' ?>