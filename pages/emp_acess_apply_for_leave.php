 <?php
require_once 'support_file.php';
$title="Apply for Leave";

$now=time();
$unique='id';
$unique_field='type';
$table="hrm_leave_info";
$page="emp_acess_apply_for_leave.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$current_status=find_a_field("".$table."","dept_head_status","".$unique."=".$_GET[$unique]."");
$required_status="Pending";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
		$sd=$_POST['s_date'];
		$ed=$_POST['e_date'];
		$_POST['s_date']=date('Y-m-d' , strtotime($sd));
        $_POST['e_date']=date('Y-m-d' , strtotime($ed));
		$date1=date_create($_POST['s_date']);
        $date2=date_create($_POST['e_date']);
        $diff=date_diff($date1,$date2);
		$_POST['total_days']=		$diff->format("%R%a")+1;
		$_POST['PBI_ID']=$_SESSION[PBI_ID];
		$_POST['leave_status'] = "Waiting";
        $_POST['entry_at'] = date('Y-m-d H:i:s');
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';


$PBI_DEPT_HEADemail=$_POST['PBI_DEPT_HEAD'];
$PBI_DEPT_RES=$_POST['leave_responsibility_name'];
$myid=$_SESSION['PBI_ID'];
$name=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$myid);
$emailId=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$PBI_DEPT_HEADemail);
$email_CC=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$PBI_DEPT_RES);


		//if($emailId!=''){
				$to = $emailId;
				$subject = "Leave Application";
				$txt1 = "<p>Dear Sir,</p>
				<p>A Leave Application is pending for your Authorization. Please enter employee access module to authorise the requisition. </p>
				<p>Leave Requested By- ".$name."</p>
				<p><b><i>This EMAIL is automatically generated by ERP Software.</i></b></p>";
				$txt=$txt1.$txt2.$tr;
				$from = 'erp@icpbd.com';
				$headers = "";
        $headers .= "From: ERP Software<erp@".$_SERVER['SERVER_NAME']."> \r\n";
$headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Cc:' .$email_CC. "\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($to,$subject,$txt,$headers);
        unset($_POST);
        unset($$unique);
    }


//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $sd=$_POST['s_date'];
    $ed=$_POST['e_date'];
    $_POST['s_date']=date('Y-m-d' , strtotime($sd));
    $_POST['e_date']=date('Y-m-d' , strtotime($ed));
    $date1=date_create($_POST['s_date']);
    $date2=date_create($_POST['e_date']);
    $diff=date_diff($date1,$date2);
    $_POST['total_days']=		$diff->format("%R%a")+1;
    $_POST['PBI_ID']=$_SESSION[PBI_ID];
    $_POST['leave_status'] = "Waiting";
    $_POST['entry_at'] = date('Y-m-d H:i:s');
    $crud->update($unique);
    $type=1;
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


	$year=date('Y');
	$s_date_s="".$year."-01-01";
	$s_date_e="".$year."-12-31";
	$policy=find_a_field('hrm_leave_type','yearly_leave_days','id='.$_GET['type'].'');
	$alrady_taken=find_a_field('hrm_leave_info','SUM(total_days)','half_or_full="Full" and e_date between "'.$s_date_s.'" and "'.$s_date_e.'"
	and s_date between "'.$s_date_s.'" and "'.$s_date_e.'"  and PBI_ID='.$_SESSION['PBI_ID'].' and type='.$_GET['type'].'');
	$sql_recommended_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM

							personnel_basic_info p,
							department d,
							essential_info e
							 where
							 p.PBI_JOB_STATUS in ('In Service') and
							 p.PBI_DEPARTMENT=d.DEPT_ID	and
							 p.PBI_ID=e.PBI_ID and
							 e.ESS_JOB_LOCATION=1 group by p.PBI_ID
							 order by p.PBI_NAME";
							  $sql_leave_PBI_IN_CHARGE="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM

							personnel_basic_info p,
							department d,
							essential_info e
							 where
							 p.PBI_JOB_STATUS in ('In Service') and
							 p.PBI_DEPARTMENT=d.DEPT_ID	and
							 p.PBI_ID=e.PBI_ID and
							 e.ESS_JOB_LOCATION=1 group by p.PBI_ID
							  order by p.PBI_NAME";
							  $sql_leave_responsibility_name="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM
							personnel_basic_info p,
							department d,
							essential_info e
							 where
							 p.PBI_JOB_STATUS in ('In Service') and
							 p.PBI_DEPARTMENT=d.DEPT_ID	and
							 p.PBI_ID=e.PBI_ID and
							 e.ESS_JOB_LOCATION=1 group by p.PBI_ID
							  order by p.PBI_NAME";
if($_GET['type']){
	$type=$_GET['type'];
} else
{$type=$type;
	}

$sql2="select a.id,a.s_date as date,a.reason,a.leave_status from ".$table." a where a.PBI_ID=".$_SESSION['PBI_ID']." order by a.".$unique." desc limit 7";
?>



<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.type.options[form.type.options.selectedIndex].value;
	self.location='<?=$page;?>?type=' + val ;
}        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 250,top = -1");}
     function GetDays(){
         var dropdt = new Date(document.getElementById("s_date").value);
         var pickdt = new Date(document.getElementById("e_date").value);
         return parseInt((pickdt - dropdt) / (24 * 3600 * 1000))+1;
     }
     function cal(){
         if(document.getElementById("e_date")){
             document.getElementById("applied").value=GetDays();
         }
     }
 </script>
<?php require_once 'body_content.php'; ?>



                    <!-- input section-->
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right">
                                    </div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                            <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>

                                    <table style="width:100%; font-size:11px;"  cellpadding="0" cellspacing="0">
                                    <tr>

                                     <th style="width: 15%">Type of Leave</th>
         <th style="width:2%">:</th>
         <td style="width: 35%">
         <select class="select2_single form-control" name="type" id="type" required style="width:91%" onchange="javascript:reload(this.form)">
                                        <option></option>
									   <? foreign_relation('hrm_leave_type','id','leave_type_name',$type)?>
									     </select>
                                        <input type="hidden" id="<?=$unique?>" style="width:100%"     name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                                        </td>

                        <th style="width: 15%">Policy & Taken</th>
              <th style="width:2%">:</th>
              <td style="width: 33%">
              <input type="text" id="policy" name="policy" style="width:45%; font-size:11px" value="<?=$policy;?>, Days" readonly class="form-control col-md-7 col-xs-12" >
              <input type="text" id="alrady_taken" name="alrady_taken" style="width:45%; font-size:11px; float:right" value="<?=$alrady_taken;?>" readonly class="form-control col-md-7 col-xs-12" >
              </td>

                      </tr>
                <tr><td style="height:5px"></td></tr>

                                    <tr>
                                    <th>Leave Address</th>
              <th style="width:2%">:</th>
              <td style="width: 20%"><textarea id="leave_address" style="width:91%; font-size:11px" name="leave_address" class="form-control col-md-7 col-xs-12" ><?=$leave_address;?></textarea></td>
              <th>Reason</th>
              <th style="width:2%">:</th><td style="width: 20%"><textarea  id="reason" style="width:100%;font-size:11px"  required   name="reason" value=""   class="form-control col-md-7 col-xs-12" ><?=$reason?></textarea></td>
              </tr>
               <tr><td style="height:5px"></td></tr>
               <tr>
              <th>R.P During Leave</th>
              <th style="width:2%">:</th>
              <td><select class="select2_single form-control" style="width:91%;" tabindex="-1" required="required" name="leave_responsibility_name" id="leave_responsibility_name">
                                                <option></option>
                                                <?=advance_foreign_relation($sql_leave_responsibility_name,$leave_responsibility_name);?>
                                            </select></td>
               <th>Mobile</th>
              <th style="width:2%">:</th><td style="width: 20%"><input name="leave_mobile_number" type="text" id="leave_mobile_number" value="<?=$leave_mobile_number?>" style="font-size:11px; width:100%" placeholder="Mobile no. During Leave" class="form-control col-md-7 col-xs-12" required/></td>
                                            </tr>


              <tr><td style="height:5px"></td></tr>
              <tr>
              <th>Checke By</th>
              <th style="width:2%">:</th><td><select class="select2_single form-control" style="width:91%;" tabindex="-1" required="required" name="PBI_IN_CHARGE" id="PBI_IN_CHARGE">
                                                <option></option>
                                                <?=advance_foreign_relation($sql_leave_PBI_IN_CHARGE,$PBI_IN_CHARGE);?>
                                            </select></td>
              <th>Authorized By</th>
              <th style="width:2%">:</th>
              <td>
              <select class="select2_single form-control" style="width:100%;" tabindex="-1" required="required" name="PBI_DEPT_HEAD" id="PBI_DEPT_HEAD">
                                                <option></option>
                                                <?=advance_foreign_relation($sql_recommended_by,$PBI_DEPT_HEAD);?>
                                            </select>
              </td>
              </tr>
              <tr><td style="height:5px"></td></tr>
              <tr><th>Duration <br>(from & to)</th>
          <th style="width:2%">:</th>
          <td colspan="4">
              <input type="date" id="s_date" style="width:38.5%; font-size:11px" required="required" name="s_date" value="<?=$s_date?>" onchange="cal()" class="form-control col-md-7 col-xs-12" >
              <input type="date" id="e_date" style="width:36.5%; margin-left: 4%; font-size:11px"  required   name="e_date" value="<?=$e_date;?>" onchange="cal()" class="form-control col-md-7 col-xs-12" >
              <input type="text" id="applied" readonly name="applied" style="width:20%; font-size:11px;float:right" placeholder="days" class="form-control col-md-7 col-xs-12" >
          </td></tr>
                                    </table>



                                        <br>

                              <?php
							  if($_GET['type']>0){
							  if($alrady_taken >= $policy){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>You cannot apply for leave more than the company policy !!!!</i></h6>';}


	if (($_GET['type']==5) && ($_SESSION['gander']==1)) {
							  echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>Maternity leave is available for women only
!!</i></h6>';
                    } else { ?>


                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record" style="font-size:12px" onclick='return window.confirm("Are you confirm?");'  class="btn btn-primary">Submit the Application</button>
                                            </div></div>
                                            <?php }} else { if(!isset($_GET[$unique])): echo "<h6 style='text-align:center; color:red; font-weight:bold'>First you need to select a type of leave.</h6>"; endif; } ?>
                                            <?php if(isset($_GET[$unique])){ ?>
                                            <?php if($current_status!=$required_status){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This leave has been approved!!</i></h6>';} else { ?>
                                            <input  name="delete" type="submit" style="font-size:12px; float:left; margin-left:10%" class="btn btn-danger" id="delete" value="Delete"/>
                                                                                         <button type="submit" name="modify" id="modify" style="font-size:12px; float:right; margin-right:10%" class="btn btn-primary">Modify</button>

                                           <?php }} ?>


                                </form>
                                </div>
                                </div>
                                </div>

 <?php if(!isset($_GET[$unique])):?>
<?=recentdataview($sql2,'voucher_view_popup_ismail.php','hrm_leave_info','282px','Recent Leave Applications','hrm_requisition_leave_report.php','4');?>
<?php endif; ?>
 <?=$html->footer_content();?>
