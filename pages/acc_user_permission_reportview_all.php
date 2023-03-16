<?php
require_once 'support_file.php';
$title='Report Permission Setup';
$now=date("Y-m-d H:i:s");
$unique='id';
$table="user_permissions_module";
$page='acc_user_permission_reportview_all.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
    if(isset($_POST['view_report']))
    {   $_SESSION[user_permission_reportview_accounts]=$_POST[user_id];
        }
//for Delete..................................
    if(isset($_POST['cancel']))
    {unset($_SESSION[user_permission_reportview_accounts]);}
if(prevent_multi_submit()) {
// insert permission..................................
    extract($_POST);
    $report_id = mysqli_real_escape_string($conn, $report_id);
    $status = mysqli_real_escape_string($conn, $status);

    $report_in_database=find_a_field('user_permissions_reportview','COUNT(report_id)','report_id='.$report_id.' and user_id="'.$_SESSION[user_permission_reportview_accounts].'"');
    if($report_id>0){
    if($report_in_database>0) {
        $sql = mysqli_query($conn, "UPDATE user_permissions_reportview SET status='$status',edit_by='$_SESSION[userid]',edit_at='".$now."' WHERE report_id='" . $report_id . "' and user_id='" . $_SESSION[user_permission_reportview_accounts] . "'");
    } else {
        $get_optgroup_label_id=find_a_field('module_reportview_report','optgroup_label_id','report_id='.$report_id.'');
        $sql = mysqli_query($conn, "INSERT INTO user_permissions_reportview (report_id,optgroup_label_id,module_id,user_id,entry_by,entry_at,status,section_id,company_id) 
        VALUES ('$report_id','$get_optgroup_label_id','$_SESSION[module_id]','$_SESSION[user_permission_reportview_accounts]','$_SESSION[userid]','$now','1','$_SESSION[sectionid]','$_SESSION[companyid]')");
    }}}?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>
              <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
              <div class="x_title">
              <h2><?=$title?></h2>
              <div class="clearfix"></div>
              </div>
               <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Active User<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="user_id" id="user_id">
                                <option></option>
                                <? $sql_user_id="SELECT  u.user_id,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' (',d.DEPT_SHORT_NAME,')') FROM 						 
							personnel_basic_info p,
							department d,
							users u
							 where p.PBI_JOB_STATUS='In Service' and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID and 
							 u.PBI_ID=p.PBI_ID		 
							  order by p.PBI_NAME";
                                advance_foreign_relation($sql_user_id,$_SESSION[user_permission_reportview_accounts]);?>
                            </select>
                        </div>
                      </div>
                   <div class="form-group">
                   <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                       <?php if(isset($_SESSION[user_permission_reportview_accounts])){ ?>
                        <button type="submit" name="cancel" class="btn btn-danger"  style="font-size: 11px">Cancel the User</button>
                       <?php } else { ?>
						<button type="submit" name="view_report" class="btn btn-primary" style="font-size: 11px">View Available Reports</button>
                       <?php } ?>
                   </div></div></form>
              </div></div>


<?php if(isset($_SESSION[user_permission_reportview_accounts])): ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <table class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <tr>
                        <th style="width: 2%">Action</th>
                        <th>Report ID</th>
                        <th>Report Name</th>
                        <th>Report Group</th>
                        <th>Module</th>
                    </tr>
                    <?php $sql=mysqli_query($conn, "SELECT zm.optgroup_label_name,zs.report_name as subzonename,zs.report_id,
       (select status from user_permissions_reportview where report_id=zs.report_id and user_id=".$_SESSION[user_permission_reportview_accounts].") as status       
       FROM module_reportview_optgroup_label AS zm
RIGHT JOIN module_reportview_report AS zs ON zm.optgroup_label_id = zs.optgroup_label_id  WHERE zm.module_id=".$_SESSION[module_id]."
ORDER BY zm.sl, zs.sl");
                    while($user=mysqli_fetch_array($sql)):?>
                        <tr>
                            <td style="text-align: center"><input type="checkbox" data="<?=$user['report_id'];?>" class="status_checks btn <?php echo ($user['status'])? 'btn-success' : 'btn-danger'?>"  <?php echo ($user['status']=='1')? 'checked' : ''?>></td>
                            <td><?=$user['report_id'] ?></td>
                            <td><?=$user['subzonename']; ?></td>
                            <td><?=$user['optgroup_label_name']; ?></td>
                            <td>Accounts</td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div></div>
<?php endif; ?>
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
        $(document).on('click','.status_checks',function(){
            var status = ($(this).hasClass("btn-success")) ? '0' : '1';
            var msg = (status=='0')? 'Deactivate' : 'Activate';
            //if(confirm("Are you sure to "+ msg)){
                var current_element = $(this);
                url = "<?=$page;?>";
                $.ajax({
                    type:"POST",
                    url: url,
                    data: {report_id:$(current_element).attr('data'),status:status},
                    success: function(data)
                    {
                        //location.reload();
                    }
                });
            //}
        });
    </script>
<?=$html->footer_content();?>