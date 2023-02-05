 <?php
require_once 'support_file.php';
$title="Warehouse / CMU / Plant Info";
$now=time();
$unique='id';
$unique_field='first_name';
$table="test";
$page="MIS_add_new_plant_cmu_warehouse.php";
$crud      =new crud($table);
$html      =new htmldiv($table);
$$unique = $_GET[$unique]; 

extract($_POST);
$user_id=mysqli_real_escape_string($conn, $user_id);
$status=mysqli_real_escape_string($conn, $status);
$sql=mysqli_query($conn, "UPDATE users SET status='$status' WHERE user_id='$user_id'");
echo $user_id;

echo 'ismail hossain';


?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content_entry_mod.php'; ?>


   
		
		<table style="width:100%">
		    <tr>
		        <td>#</td>
		        <td>Name</td>
		        <td>Email</td>
                 <td>Checkbox</td>
		        <td>Action</td>
		    </tr>
		    <?php $sql=mysqli_query($conn, "Select * from users");
			while($user=mysqli_fetch_array($sql)):
            	?>
		    <tr>
		        <td><?php echo $user['user_id'] ?></td>
		        <td><?php echo $user['fname']; ?></td>
		        <td><?php echo $user['email']; ?></td>
                <td><input type="checkbox" data="<?php echo $user['user_id'];?>" class="status_checks btn <?php echo ($user['status'])? 'btn-success' : 'btn-danger'?>"  <?php echo ($user['status']=='1')? 'checked' : ''?>><?php echo ($user['status'])? 'Active' : 'Inactive'?></td>
		    </tr>
		   <?php endwhile; ?>
		</table>
		
	


<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
$(document).on('click','.status_checks',function(){
var status = ($(this).hasClass("btn-success")) ? '0' : '1';
var msg = (status=='0')? 'Deactivate' : 'Activate';
if(confirm("Are you sure to "+ msg)){
	var current_element = $(this);
	url = "test.php";
	$.ajax({
	type:"POST",
	url: url,
	data: {user_id:$(current_element).attr('data'),status:status},
	success: function(data)
		{   
			location.reload();
		}
	});
	}      
});
</script>
<?=$html->footer_content();?> 