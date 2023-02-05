<?php
require_once 'support_file.php';
$title='Permission :: Plan / Warehouse';
$now=time();
$unique='id';
$table="user_plant_permission";
$page='user_plant_permission.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";






if(prevent_multi_submit()){
    if(count($_POST)>0){
        if($_POST['type']==1){
            $name=$_POST['name'];
            $email=$_POST['email'];
            $phone=$_POST['phone'];
            $city=$_POST['city'];
            $sql = "INSERT INTO `test_query`( `module_id`, `user_id`,`powerby`,`status`) 
		VALUES ('$name','$email','$phone','$city')";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
            }
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }



//for modify..................................
    if(isset($_POST['modify']))
    {   $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        unset($_POST);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }



//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$_GET[$unique];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>
    <SCRIPT language=JavaScript>
        function reload(form)
        {	var val=form.user_id.options[form.user_id.options.selectedIndex].value;
            self.location='<?=$page;?>?user_id=' + val ;
        }</script>



<?php require_once 'body_content.php'; ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?=$title?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a hhref="JavaScript:void(0);" class="btn btn-danger" id="delete_multiple" style="color: white; font-size: 12px"><i class="fa fa-minus-circle"></i>  Delete</a></li>
                    <li><a href="#addEmployeeModal" class="btn btn-primary" data-toggle="modal" style="color: white; font-size: 12px"><i class="fa fa-plus"></i>  Add New</a></li>
                    <!--li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li-->
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-9 col-sm-9 col-xs-12">

                    <table class="table table-striped table-hover" style="width: 100%">
                    <thead>
                    <tr>
                        <th>
							<span class="custom-checkbox">
								<input type="checkbox" id="selectAll">
								<label for="selectAll"></label>
							</span>
                        </th>
                        <th>SL NO</th>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th>CITY</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $result = mysqli_query($conn,"SELECT * FROM test_query");
                    $i=1;
                    while($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr id="<?php echo $row["id"]; ?>">
                            <td>
							<span class="custom-checkbox">
								<input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row["id"]; ?>">
								<label for="checkbox2"></label>
							</span>
                            </td>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row["module_id"]; ?></td>
                            <td><?php echo $row["email"]; ?></td>
                            <td><?php echo $row["phone"]; ?></td>
                            <td><?php echo $row["city"]; ?></td>
                            <td>
                                <a href="#editEmployeeModal" class="edit" data-toggle="modal">
                                    <i class="fa fa-pencil-square-o" data-toggle="tooltip"
                                       data-id="<?php echo $row["id"]; ?>"
                                       data-name="<?php echo $row["name"]; ?>"
                                       data-email="<?php echo $row["email"]; ?>"
                                       data-phone="<?php echo $row["phone"]; ?>"
                                       data-city="<?php echo $row["city"]; ?>"
                                       title="Edit"></i>
                                </a>
                                <a href="#deleteEmployeeModal" class="delete" data-id="<?php echo $row["id"]; ?>" data-toggle="modal"><i class="fa fa-times" data-toggle="tooltip"
                                                                                                                                         title="Delete"></i></a>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                    </tbody>
                    </table>
                </div></div></div></div>

    <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="user_form">
                    <div class="modal-header">
                        <h4 class="modal-title">Add User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>NAME</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>EMAIL</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>PHONE</label>
                            <input type="phone" id="phone" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>CITY</label>
                            <input type="city" id="city" name="city" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="1" name="type">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button type="button" class="btn btn-success" id="btn-add">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="editEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="update_form">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_u" name="id" class="form-control" required>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="name_u" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="email_u" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>PHONE</label>
                            <input type="phone" id="phone_u" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="city" id="city_u" name="city" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="2" name="type">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button type="button" class="btn btn-info" id="update">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="deleteEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>

                    <div class="modal-header">
                        <h4 class="modal-title">Delete User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_d" name="id" class="form-control">
                        <p>Are you sure you want to delete these Records?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button type="button" class="btn btn-danger" id="delete">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require_once 'footer_content.php' ?>