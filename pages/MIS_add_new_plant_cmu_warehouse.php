 <?php
require_once 'support_file.php';
$title="Warehouse / CMU / Plant Info";
$now=time();
$unique='warehouse_id';
$unique_field='warehouse_name';
$table="warehouse";
$page="MIS_add_new_plant_cmu_warehouse.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

$res='select '.$unique.','.$unique.' as Code,nick_name,'.$unique_field.',address,contact_no,email from '.$table.' order by '.$unique;
$sql=mysqli_query($conn, $res);
while($data=mysqli_fetch_object($sql)){
    $id=$data->$unique;
if(isset($_POST['deletedata'.$id])){  
	   $condition=$unique."=".$id;
       $crud->delete($condition);
       unset($_POST);
    }}


if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }
 
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $type=1;
}



//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';    
}}}
 ?>

 <?php require_once 'header_content.php';?>
<?php require_once 'body_content.php';?>





  <!-- ADD RECORD MODAL -->
  <div class="modal fade" id="addModal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Record</h5>
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
           
           <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Custom Code<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
										<input type="hidden" name="warehouse_id" id="warehouse_id" value="" />
                                            <input type="text" name="custom_code" id="custom_code" style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Warehouse / Plant / CMU Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" name="warehouse_name" id="warehouse_name"  style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Nick Name</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="nick_name" style="width:100%; font-size: 11px" name="nick_name" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                    

                                   <div class="form-group">
                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Address</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <textarea id="address" style="width:100%; height: 80px; font-size: 12px" name="address"  class="form-control col-md-7 col-xs-12" ></textarea>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Contact No</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="contact_no" style="width:100%; font-size: 11px" name="contact_no" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Email</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="email" style="width:100%; font-size: 11px" name="email" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>




                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Type:<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <select style="width: 100%" class="select2_single form-control" name="use_type" id="use_type">
                                                <option></option>
                                                
                                            </select></div></div>
                                            

<div class="form-group">                                        
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Associated Person<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="ap_name" style="width:100%; font-size: 12px"  required   name="ap_name" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">AP Designation<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="ap_designation" style="width:100%; font-size: 12px"    name="ap_designation" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

								   <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="record" id="record" style="font-size:12px"  class="btn btn-primary">Record</button></div></div>
                                                
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- VIEW MODAL -->
  <div class="modal fade" id="viewModal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">View Record Information</h5>
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-5 col-xs-6 tital " >
              <strong>First Name:</strong>
            </div>
            <div class="col-sm-7 col-xs-6 ">
              <div id="viewFirstname"></div>
            </div>
            <div class="col-sm-5 col-xs-6 tital " >
              <strong>Last Name:</strong>
            </div>
            <div class="col-sm-7 col-xs-6 ">
              <div id="viewLastname"></div>
            </div>
            <div class="col-sm-5 col-xs-6 tital " >
              <strong>Address:</strong>
            </div>
            <div class="col-sm-7 col-xs-6 ">
              <div id="viewAddress"></div>
            </div>
            <div class="col-sm-5 col-xs-6 tital " >
              <strong>Skills:</strong>
            </div>
            <div class="col-sm-7 col-xs-6 ">
              <div id="viewSkills"></div>
            </div>
            <div class="col-sm-5 col-xs-6 tital " >
              <strong>Designation:</strong>
            </div>
            <div class="col-sm-7 col-xs-6 ">
              <div id="viewDesignation"></div>
            </div>          
          </div>
          <br>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- UPDATE MODAL -->
  <div class="modal fade" id="updateModal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Edit Record</h5>
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="update.php" method="POST">
            <input type="hidden" name="updateId" id="updateId">
            <div class="form-group">
              <label for="title">First Name</label>
              <input type="text" name="updateFirstname" id="updateFirstname" class="form-control" placeholder="Enter first name" maxlength="50"
                required>
            </div>
            <div class="form-group">
              <label for="title">Last Name</label>
              <input type="text" name="updateLastname" id="updateLastname" class="form-control" placeholder="Enter last name" maxlength="50"
                required>
            </div>
            <div class="form-group">
              <label for="title">Address</label>
              <input type="text" name="updateAddress" id="updateAddress" class="form-control" placeholder="Enter address" maxlength="50"
                required>
            </div>
            <div class="form-group">
              <label for="title">Skills</label>
              <input type="text" name="updateSkills" id="updateSkills" class="form-control" placeholder="Enter skills" maxlength="50" required>
            </div>
            <div class="form-group">
              <label for="title">Designation</label>
              <input type="text" name="updateDesignation" id="updateDesignation" class="form-control" placeholder="Enter designation" maxlength="50"
                required>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="modify">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- DELETE MODAL -->
  <div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="exampleModalLabel">Delete Record</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="delete.php" method="POST">

          <div class="modal-body">

            <input type="hidden" name="deleteId" id="deleteId">

            <h4>Are you sure want to delete?</h4>

          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          <button type="submit" class="btn btn-primary" name="deleteData">Yes</button>
        </div>

        </form>
      </div>
    </div>
  </div>
 <?=$html->footer_content();?>