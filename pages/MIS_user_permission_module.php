<?php
require_once 'support_file.php';
$title='Modules Permission';
$now=date("Y-m-d H:i:s");
$unique='id';
$table="user_permissions_module";
$page='MIS_user_permission_module.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
    if(isset($_POST['view_report']))
    {   $_SESSION[MIS_user_permission_reportview]=$_POST[user_id];
        }
//for Delete..................................
    if(isset($_POST['cancel']))
    {unset($_SESSION[MIS_user_permission_reportview]);}
if(prevent_multi_submit()) {
// insert permission..................................
    extract($_POST);
    $module_id = mysqli_real_escape_string($conn, $module_id);
    $status = mysqli_real_escape_string($conn, $status);

    $report_in_database=find_a_field('user_permissions_module','COUNT(module_id)','module_id='.$module_id.' and user_id="'.$_SESSION[MIS_user_permission_reportview].'"');
    if($module_id>0){
    if($report_in_database>0) {
        $sql = mysqli_query($conn, "UPDATE user_permissions_module SET status='$status',powerby='$_SESSION[userid]',power_date='".$now."',ip='".$ip."' WHERE module_id='" . $module_id . "' and user_id='" . $_SESSION[MIS_user_permission_reportview] . "'");
    } else {
        $sql = mysqli_query($conn, "INSERT INTO user_permissions_module (module_id,user_id,powerby,power_date,status,section_id,company_id,ip) 
        VALUES ('$module_id','$_SESSION[MIS_user_permission_reportview]','$_SESSION[userid]','$now','1','$_SESSION[sectionid]','$_SESSION[companyid]','$ip')");
    }}}

?>

<?php require_once 'header_content.php'; ?>
<style>
        #customers {}
        #customers td {}
        #customers tr:ntd-child(even)
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: #f5f5f5;}
        td{}
    </style>
<?php require_once 'body_content.php'; ?>
              <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
              <div class="x_title">
              <h2><?=$_SESSION[module_name]?> | <?=$title?></h2>
              <div class="clearfix"></div>
              </div>
               <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Active User<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 50%; flot:left" tabindex="-1" required="required" name="user_id" id="user_id">
                                <option></option>
                                <? $sql_user_id="SELECT  u.user_id,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' (',d.DEPT_SHORT_NAME,')') FROM 						 
							personnel_basic_info p,
							department d,
							user_activity_management u
							 where p.PBI_JOB_STATUS='In Service' and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID and 
							 u.PBI_ID=p.PBI_ID		 
							  order by p.PBI_NAME";
                                advance_foreign_relation($sql_user_id,$_SESSION[MIS_user_permission_reportview]);?>
                            </select>
                       <?php if(isset($_SESSION[MIS_user_permission_reportview])){ ?>
                        <button type="submit" name="cancel" class="btn btn-danger"  style="font-size: 12px; margin-left:5%">Cancel</button>
                       <?php } else { ?>
						<button type="submit" name="view_report" class="btn btn-primary" style="font-size: 12px; margin-left:5%">Proceed to the next</button>
                       <?php } ?>
                   </div></div></form>
              </div></div>


<?php if(isset($_SESSION[MIS_user_permission_reportview])){ ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <table id="customers" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <tr>
                        <th style="width: 2%">Action</th>
                        <th>Module ID</th>
                        <th>Module Name</th>
                        <th>Module Short Name</th>
                    </tr>
                    <?php $sql=mysqli_query($conn, "SELECT m.module_id,m.modulename,m.module_short_name,
       (select p.status from user_permissions_module p where p.module_id=m.module_id and p.user_id='".$_SESSION[MIS_user_permission_reportview]."') as status
       FROM module_department m  WHERE 1 ORDER BY m.module_id");
                    while($data=mysqli_fetch_object($sql)):?>
                        <tr>
                            <td style="text-align: center"><input type="checkbox" data="<?=$data->module_id?>" class="status_checks btn <?php echo ($data->status)? 'btn-success' : 'btn-danger'?>"  <?php echo ($data->status=='1')? 'checked' : ''?>></td>
                            <td><?=$data->module_id?></td>
                            <td><?=$data->modulename; ?></td>
                            <td><?=$data->module_short_name?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div></div>
<?php } ?>
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
                    data: {module_id:$(current_element).attr('data'),status:status},
                    success: function(data)
                    {
                        console.log("Success!");

                        //location.reload();
                    }
                });
            //}
        });
    </script>
<?=$html->footer_content();?>