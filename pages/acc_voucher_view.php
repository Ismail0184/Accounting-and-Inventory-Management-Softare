<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Voucher View';
$page='acc_voucher_view_popup.php';
$unique='v_no';
?>

<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
     function DoNavPOPUP(lk)
     {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=600,left = 230,top = 5");}
 </script>
<?php require_once 'body_content.php'; ?>


<div class="col-md-12 col-sm-12 col-xs-12">
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



<?php
$proj_id 	= $_SESSION['proj_id'];
$vtype 		= $_REQUEST['v_type'];


if(isset($_REQUEST['show']))
{  
   	$fdate=$_REQUEST["fdate"];
	$tdate=$_REQUEST["tdate"];
	$vou_no=$_REQUEST['vou_no'];
	$user_id=$_REQUEST['user_id'];
	if($user_id!='')
	$user_id = getSVALUE('user_activity_management','user_id',"where username='".$user_id."'");




if (!empty($_POST[vou_no])){
    $sql = "SELECT DISTINCT 
				  j.jv_no,
				  j.jv_no as Transaction_no,
                  j.tr_no as voucher_no,                
				  j.jvdate as date,
				  j.dr_amt,
				  j.cr_amt,				  
				  l.ledger_name,
				  j.tr_from as Voucher_type,
                  u.fname as entry_by,
                  j.entry_at,j.status
				FROM
				  user_activity_management u,
				  journal j,
				  accounts_ledger l
				WHERE
				  j.tr_no='".$_POST[vou_no]."' and 
				  j.user_id=u.user_id 
				  AND j.ledger_id = l.ledger_id group BY j.tr_no ";
} else {

    if($_POST[tr_from]!=''){$tr_from .= " AND j.tr_from = '".$_POST[tr_from]."'";}
    $sql = "SELECT DISTINCT 
				  j.jv_no,
				  j.jv_no as Transaction_no,
                  j.tr_no as voucher_no,                
				  j.jvdate as date,
				  j.dr_amt,
				  j.cr_amt,				  
				  l.ledger_name,
				  j.tr_from as Voucher_type,
                  u.fname as entry_by,
                  j.entry_at,j.status


				FROM
				  user_activity_management u,
				  journal j,
				  accounts_ledger l
				WHERE
				  j.jvdate BETWEEN '" . $_POST[fdate] . "' AND '" . $_POST[tdate] . "' and  
				  j.user_id=u.user_id ".$tr_from." 
				  AND j.ledger_id = l.ledger_id group BY j.tr_no ";}}
?>


<form id="form1" name="form1" method="post" action="" style="font-size: 11px">
                                                <table align="center" style="width: 60%">
                                                    <tr style="height: 45px"><td>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required">*</span>
                                                                </label>
                                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                                    <input type="date" id="fdate" style="font-size: 11px; width:46%; float:left"  name="fdate" value="<?=($_POST[fdate]!='')? $_POST[fdate] : date('Y-m-01') ?>" max="<?=date('Y-m-d');?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off">
                                                                    <input type="date" id="tdate" style="font-size: 11px; width:46%; float:right" value="<?=($_POST[tdate]!='')? $_POST[tdate] : date('Y-m-d') ?>" name="tdate"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>


                            </div>
                        </div></td></tr>
                                                    <tr style="height: 45px"><td>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Voucher Type</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  id="tr_from" name="tr_from" >
                    <option></option>
                    <?php foreign_relation('journal', 'distinct tr_from', 'tr_from',  $_POST[tr_from], '1','1'); ?>                  </select></select></div></div>

                                                            </select>
                                                    </div>
                                                </div></td></tr>
                                                    <tr valign="middle" style="height: 45px"><td>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Voucher No</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="vou_no" style="font-size: 12px"  value="<?=$vou_no?>" name="vou_no"  class="form-control col-md-7 col-xs-12"></td>


                            </div>
                        </div></td></tr>


                <tr valign="middle" style="height: 45px"><td>
                
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" style="font-size:12px" class="btn btn-primary" name="show">Search</button>
                    </div>
                </div></td></tr>
                                    </table>
								    </form>
							</div></div></div>



<?php if(isset($_REQUEST['view'])||isset($_REQUEST['show'])) : echo $crud->report_templates_with_status($sql,$title);
endif; mysqli_close($conn); ?> 
<?=$html->footer_content();?> 