<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$now=time();
$unique='id';
$unique_field='stl_no';
$table="acc_short_term_loan";
$table_details="acc_short_term_loan_details";
$page="acc_loan_management_short_term_loan.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$title='Create Short Term Loan';
$find_date = date('Y-m-d');
$maturity_date_get = find_a_field('acc_short_term_loan_details','maturity_date','maturity_date="'.$find_date.'"');
$Get_STL_ledger = find_a_field('acc_short_term_loan','STL_ledger','id="19"');

$query=mysqli_query($conn, "SELECT d.maturity_date as jv_date,d.*,m.* from acc_short_term_loan_details d,acc_short_term_loan m where d.journal_status='pending' and m.status='Disbursed' and d.maturity_date between '0000-00-00' and '".$find_date."' and m.id=d.uid");
while($data=mysqli_fetch_object($query)){
    $ledger_balance = find_a_field('journal','SUM(cr_amt-dr_amt)','ledger_id='.$data->STL_ledger);
    $interest_amount = (($ledger_balance/100)*$data->interest_rate)/360*1;
    $jv=next_journal_voucher_id();
    $narration = $data->interest_rate."% Interest on loan of ".$data->bank_name." ".$data->stl_no;
    add_to_journal_new($data->jv_date, $proj_id, $jv, $date, $data->expenses_head, $narration, $interest_amount, 0,'interest_on_Loan','', $unique_GET, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $_SESSION['wpc_DO']);
    add_to_journal_new($data->jv_date, $proj_id, $jv, $date, $data->interest_ledger, $narration, 0, $interest_amount,'interest_on_Loan','', $unique_GET, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $_SESSION['wpc_DO']);
    $up = mysqli_query($conn, "Update acc_short_term_loan_details set journal_status='created' where maturity_date between '0000-00-00' and '".$find_date."'");


}



if(prevent_multi_submit()){
    if(isset($_POST[$unique_field]))
    {    $$unique = $_POST[$unique];
        if(isset($_POST['record']))
        {
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $name		= @mysqli_real_escape_string($conn, $_POST['bank_name'].' '.$_REQUEST['stl_no']);
            $name		= str_replace("'","",$name);
            $name		= str_replace("&","",$name);
            $name		= str_replace('"','',$name);
            $under		= @mysqli_real_escape_string($conn, $_REQUEST['stl_ledger']);

            $check="select sub_sub_ledger_id from ".$table." where sub_sub_ledger='$name'";
		if(mysqli_num_rows(mysqli_query($conn, $check))>0)
		{
			$aaa=mysqli_num_rows(mysqli_query($conn, $check));
			$ledger_id=$aaa[0];
				$type=0;
				echo "<h4 style='color:red'>Given Name('.$name.') is already exists.</h4>";
		}
		else
		{	$sql_check="select ledger_id,balance_type,budget_enable from accounts_ledger where ledger_id='".$under."' limit 1";
			$sql_query=mysqli_query($conn, $sql_check);
			if(mysqli_num_rows($sql_query)>0){
			$ledger_data=mysqli_fetch_row($sql_query);
				if(!ledger_excess($name))
				{
					$type=0;
					echo "<h4 style='color:red'>Given Name('.$name.') is already exists as Ledger.</h4>";
				}
			else
			{
			 		$sub_ledger_id=next_sub_sub_ledger_id($under);
					sub_sub_ledger_generate($sub_ledger_id,$name, $under, $balance, $now, $proj_id);
                    $sub_sub_type = 'sub-sub-ledger';
					ledger_generate($sub_ledger_id,$name,$ledger_data[0],'',$ledger_data[1],'','', time(),$proj_id,$ledger_data[2],$sub_sub_type);
					$type=1;
                    $_SESSION['sub_sub_name'] = $name;
			}} else {
		$type=0;
		}}

            $SSL_name		= @mysqli_real_escape_string($conn, $_POST['bank_name'].' Provision for Interest on '.$_REQUEST['stl_no']);
            $SSL_name		= str_replace("'","",$SSL_name);
            $SSL_name		= str_replace("&","",$SSL_name);
            $SSL_name		= str_replace('"','',$SSL_name);
            $under		= @mysqli_real_escape_string($conn, $_REQUEST['interest_ledger']);

            $check="select sub_sub_ledger_id from ".$table." where sub_sub_ledger='$SSL_name'";
            if(mysqli_num_rows(mysqli_query($conn, $check))>0)
            {
                $aaa=mysqli_num_rows(mysqli_query($conn, $check));
                $ledger_id=$aaa[0];
                $type=0;
                echo "<h4 style='color:red'>Given Name('.$SSL_name.') is already exists.</h4>";
            }
            else
            {	$sql_check="select ledger_id,balance_type,budget_enable from accounts_ledger where ledger_id='".$under."' limit 1";
                $sql_query=mysqli_query($conn, $sql_check);
                if(mysqli_num_rows($sql_query)>0){
                    $ledger_data=mysqli_fetch_row($sql_query);
                    if(!ledger_excess($SSL_name))
                    {
                        $type=0;
                        echo "<h4 style='color:red'>Given Name('.$SSL_name.') is already exists as Ledger.</h4>";
                    }
                    else
                    {
                        $sub_ledger_id=next_sub_sub_ledger_id($under);
                        sub_sub_ledger_generate($sub_ledger_id,$SSL_name, $under, $balance, $now, $proj_id);
                        $sub_sub_type = 'sub-sub-ledger';
                        ledger_generate($sub_ledger_id,$SSL_name,$ledger_data[0],'',$ledger_data[1],'','', time(),$proj_id,$ledger_data[2],$sub_sub_type);
                        $type=1;
                        $_SESSION['SSL_name'] = $SSL_name;
                    }} else {
                    $type=0;
                }}

            $_POST['status']=1;
            $_POST['maturity_date']=date('Y-m-d', strtotime($_POST['interest_effective_date'] .' '.$_POST['days'].' day'));
            $_POST['STL_ledger']        = find_a_field('accounts_ledger','ledger_id','ledger_name="'.$_SESSION['sub_sub_name'].'"');
            $_POST['interest_ledger']   = find_a_field('accounts_ledger','ledger_id','ledger_name="'.$_SESSION['SSL_name'].'"');
            $crud->insert();
            $jv=next_journal_voucher_id();
            $narration = 'Loan Disbursement against short term loan of '.$_POST['bank_name'].', STL No # '.$_SESSION['sub_sub_name'].', remarks # '.$_POST['remarks'];
            $interest_amounts = ((($_POST['loan_amount']/100)*$_POST['interest_rate'])/360*1);
            $interest_narration = $_POST['interest_rate'].'% Interest on loan of '.$_POST['bank_name'].', STL No # '.$_SESSION['sub_sub_name'];
            if (($_POST['ledger_id'] > 0) && ($_POST['loan_amount'])) {
                add_to_journal_new($_POST['date'], $proj_id, $jv, $date, $_POST['ledger_id'], $narration, $_POST['loan_amount'], 0,'Loan','', $unique_GET, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $_SESSION['wpc_DO']);
                add_to_journal_new($_POST['date'], $proj_id, $jv, $date, $_POST['STL_ledger'], $narration, 0, $_POST['loan_amount'],'Loan','', $unique_GET, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $_SESSION['wpc_DO']);
                //add_to_journal_new($_POST['date'], $proj_id, $jv, $date, $_POST['expenses_head'], $interest_narration, $interest_amounts, 0,'interest_on_Loan','', $unique_GET, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $_SESSION['wpc_DO']);
                //add_to_journal_new($_POST['date'], $proj_id, $jv, $date, $_POST['interest_ledger'], $interest_narration, 0, $interest_amounts,'interest_on_Loan','', $unique_GET, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $_SESSION['wpc_DO']);
                $up = mysqli_query($conn, "Update acc_short_term_loan_details set journal_status='created' where maturity_date='".$_POST['date']."'");

            }

            for($i=0;$i<$_POST['days'];$i++)
            {
                $_POST['installment_no'] = $i+1;
                $_POST['date'] = $_POST['date'];
                $_POST['uid']=find_a_field('acc_short_term_loan','id','stl_no="'.$_POST['stl_no'].'"');
                $_POST['interest_effective_date']=$_POST['interest_effective_date'];
                $_POST['maturity_date']=date('Y-m-d', strtotime($_POST['interest_effective_date'] .' '.$i.' days'));
                $_POST['amount']=((($_POST['loan_amount']/100)*$_POST['interest_rate'])/360)*1;
                $crud      =new crud($table_details);
                $crud->insert();
            }
            unset($_POST);
            unset($_SESSION['sub_sub_name']);
            unset($_SESSION['SSL_name']);
        }
//for modify..................................
        if(isset($_POST['modify']))
        {
            $_POST['edit_at']=time();
            $_POST['edit_by']=$_SESSION['userid'];
            $crud->update($unique);
            $type=1;
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
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}
}
$res="SELECT a.id,a.id as uid,a.stl_no,l.ledger_name as bank_name,(select ledger_name from accounts_ledger where ledger_id=a.STL_ledger) as STL_ledger,(select ledger_name from accounts_ledger where ledger_id=a.interest_ledger) as interest_ledger,a.loan_amount,a.interest_rate,a.interest_on_late_payment,a.date,a.maturity_date,a.remarks,a.status as status from ".$table." a,accounts_ledger l where a.ledger_id=l.ledger_id";
$result=mysqli_query($conn, $res);
while($data=mysqli_fetch_object($result)){
    $id=$data->id;
    if(isset($_POST['deletedata'.$id]))
    { $del=mysqli_query($conn, "Delete from ".$table." where ".$unique."=".$id);}
}?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=600,height=500,left = 350,top = 5");}
</script>
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
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Add New
                                <button class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </h5>
                        </div>
                        <div class="modal-body">
                            <?php endif; ?>
                            <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Bank / Party Name <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id">
                                            <option></option>
                                            <?php foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $ledger_id, 'status=1 and ledger_group_id in ("1002","2002")'); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">STL Ledger Under Head <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="stl_ledger">
                                            <option></option>
                                            <?php foreign_relation('sub_ledger', 'sub_ledger_id', 'sub_ledger', $ledger_id, 'status=1 and ledger_id in ("2003000100000000","2002000600000000")'); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Interest Ledger Under <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="interest_ledger">
                                            <option></option>
                                            <?php foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $ledger_id, 'status=1 and ledger_id in ("2007000400020000","2007000400030000")'); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Expenses Head <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="expenses_head">
                                            <option></option>
                                            <?php foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $expenses_head, 'status=1 and ledger_id in ("4007000200010000","4007000200020000")'); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Bank Name <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" class="form-control" style="font-size: 11px" required name="bank_name" value="<?=$bank_name?>" />
                                        <input type="hidden" class="form-control" style="font-size: 11px" required name="bank_name_" value="_" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">STL No <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" class="form-control" style="font-size: 11px" required name="stl_no" value="<?=$stl_no?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Loan Amount <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" class="form-control" style="font-size: 11px" required name="loan_amount" value="<?=$loan_amount?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Interest Rate (%)<span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="number" class="form-control" style="font-size: 11px" required name="interest_rate" step="any" value="<?=$interest_rate?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Interest on Late Payment Rate (%)<span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="number" class="form-control" style="font-size: 11px" step="any"  required name="interest_on_late_payment" value="<?=$interest_on_late_payment?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Date<span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="date" class="form-control" style="font-size: 11px" required name="date" value="<?=$date?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Interest Effective Date<span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="date" class="form-control" style="font-size: 11px" required name="interest_effective_date" value="<?=$interest_effective_date?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">No. of Days <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" class="form-control" style="font-size: 11px" required name="days" value="<?=$days?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Remarks</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <textarea name="remarks" class="form-control"><?=$remarks?></textarea>
                                    </div>
                                </div>
                                <?php if(isset($_GET[$unique])): ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="status">
                                                <option></option>
                                                <option value="1"<?=($status=='Disbursed')? ' Selected' : '' ?>>Disbursed</option>
                                                <option value="0"<?=($status=='Settled')? ' Selected' : '' ?>>Settled</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <hr>
                                <?php if($_GET[$unique]):  ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php
                                            $status =find_a_field('acc_short_term_loan','status','id='.$_GET['id']) ;
                                            if($status=='Settled'){ echo '<h6 style="color: red; font-style: italic">This STL has been SETTLED</h6>';} else {
                                            ?>
                                            <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-danger" onclick="self.close()">Close</button>
                                            <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Modify</button>
                                        <?php } ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <a name="modify"  style="font-size:12px" class="btn btn-danger" data-dismiss="modal">Close</a>
                                            <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if(!isset($_GET[$unique])): ?>
            </div>
        <?php endif; ?><?php if(!isset($_GET[$unique])):?>
                <?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
            <?php endif; ?>
            <?=$html->footer_content();mysqli_close($conn);?>
            <?php ob_end_flush();ob_flush(); ?>