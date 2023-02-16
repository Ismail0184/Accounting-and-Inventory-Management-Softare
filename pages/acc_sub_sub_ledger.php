<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Sub sub Ledger';
$table='sub_sub_ledger';
$unique='sub_sub_ledger_id';
$page="acc_sub_sub_ledger.php";
$proj_id=$_SESSION['proj_id'];
$now=time();
$separator	= $_SESSION['separator'];

if(isset($_REQUEST['name'])||isset($_REQUEST['id']))
{
	$id=$_REQUEST['id'];
	//echo $ledger_id;
	$name		= mysqli_real_escape_string($conn, $_REQUEST['name']);
	$name			= str_replace("'","",$name);
	$name			= str_replace("&","",$name);
	$name			= str_replace('"','',$name);
	$under		= mysqli_real_escape_string($conn, $_REQUEST['under']);
	$balance	= mysqli_real_escape_string($conn, $_REQUEST['balance']);
	//end
	if(isset($_POST['nledger']))
	{
		$check="select sub_sub_ledger_id from ".$table." where sub_sub_ledger='$name'";
		//echo $check;
		if(mysqli_num_rows(mysqli_query($conn, $check))>0)
		{
			$aaa=mysqli_num_rows(mysqli_query($conn, $check));
			$ledger_id=$aaa[0];
				$type=0;
				$msg='Given Name('.$name.') is already exists.';
		}
		else
		{	$sql_check="select ledger_id,balance_type,budget_enable from accounts_ledger where ledger_id='".$under."' limit 1";
			$sql_query=mysqli_query($conn, $sql_check);
			if(mysqli_num_rows($sql_query)>0){
			$ledger_data=mysqli_fetch_row($sql_query);
				if(!ledger_excess($name))
				{
					$type=0;
					$msg='Given Name('.$name.') is already exists as Ledger.';
				}
			else
			{
			 		$sub_ledger_id=next_sub_sub_ledger_id($under);
					sub_sub_ledger_generate($sub_ledger_id,$name, $under, $balance, $now, $proj_id);

					ledger_generate($sub_ledger_id,$name,$ledger_data[0],'',$ledger_data[1],'','', time(),$proj_id,$ledger_data[2]);
					$type=1;
					echo "<h4 style='color:red'>New Entry Successfully Inserted.</h4>";
			}}		else
		{
		$type=0;
		$msg='Invalid Accounts Ledger!!!';
		}
		}
	}

//for Modify..................................

	if(isset($_POST['mledger']))
	{
$search_sql="select 1 from sub_sub_ledger where `sub_sub_ledger_id`!='$id' and `sub_sub_ledger` = '$name' limit 1";
if(mysqli_num_rows(mysqli_query($conn, $search_sql))==0)
	{
		$sql_check="select ledger_id from accounts_ledger where ledger_id=".$under;
		$sql_query=mysqli_query($conn, $sql_check);
		if(mysqli_num_rows($sql_query)==1){
		$id=$_REQUEST['id'];
		$sql2="UPDATE `accounts_ledger` SET 
		`ledger_name` 		= '$name'	
			WHERE `ledger_id` 		='$id' LIMIT 1";
		$sql="UPDATE `sub_sub_ledger` SET
		`sub_sub_ledger` = '$name'
		WHERE `sub_sub_ledger_id` =$id LIMIT 1";
		$query=mysqli_query($conn, $sql);
		$query=mysqli_query($conn, $sql2);
		$type=1;
		$msg='Successfully Updated.';
		}
		else
		{
		$type=0;
		$msg='Invalid Accounts Ledger!!!';
		}
		//echo $sql;
	}
	else
	{
	$type=0;
	$msg='Given Name('.$name.') is already exists.';
	}
	}

	if(isset($_POST['dledger']))
{
$id=$_REQUEST['id'];
$sql="delete from `sub_sub_ledger` where `sub_sub_ledger_id`='$id' limit 1";
$query=mysqli_query($conn, $sql);
$sql="delete from `accounts_ledger` where `ledger_id`='$id' limit 1";
$query=mysqli_query($conn, $sql);
		$type=1;
		$msg='Successfully Deleted.';
}

	$ddd="select * from sub_sub_ledger where sub_sub_ledger_id='$id'";
	$data=mysqli_fetch_row(mysqli_query($conn, $ddd));
}


$res="select  z.sub_sub_ledger_id,z.sub_sub_ledger_id,z.sub_sub_ledger,z.sub_ledger_id,a.sub_ledger
                              FROM ".$table." z,sub_ledger a, accounts_ledger b,ledger_group c where 
                             a.ledger_id=b.ledger_id and 
                             b.ledger_group_id=c.group_id and 
                             z.sub_ledger_id=a.sub_ledger_id";
$query=mysqli_query($conn, $res);
while($row=mysqli_fetch_object($query)){
    if(isset($_POST['deletedata'.$row->$unique]))
    { if($row->has_transactions == 0){
        mysqli_query($conn, ("DELETE FROM ".$table." WHERE ".$unique."=".$row->$unique.""));
        mysqli_query($conn, ("DELETE FROM accounts_ledger WHERE ledger_id=".$row->$unique.""));
    } else { echo "<h4 style='color:red'>It has transactions (".$row->has_transactions."). Hence you cannot delete the Sub-Ledger ID (".$row->sub_ledger_id.")</h4>";}
        unset($_POST);
    }} // end of deletedata
?>
<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>
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
        <h5 class="modal-title">Add New Record
            <button class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </h5>
    </div>
    <div class="modal-body">
<?php endif; ?>
                                <form id="form2" name="form2" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <? require_once 'support_html.php';?>
                                        <div class="form-group" style="width: 100%">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Sub Sub Ledger<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="name"  required="required" name="name" value="<?php echo $data[1];?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size: 12px" >
                                        </div></div>





                                    <div class="form-group" style="width: 100%">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Under Ledger<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" name="under" id="under" style="width: 100%; font-size: 12px">
                                                <option value=""></option>
                       <?=foreign_relation('sub_ledger', 'sub_ledger_id', 'CONCAT(sub_ledger_id," : ", sub_ledger)', $_POST[under], '1','1'); ?>
                                            </select></div></div>



                                        <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%"></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <button type="submit" name="nledger" id="nledger" onclick="return checkUserName()" class="btn btn-primary">Record</button>
                                                        </div></div>


								 
                          </form></div>
      </div>
    </div>
  </div>
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?=$html->footer_content();?>