<?php
	require_once 'support_file.php';
	if(isset($_POST['btn-login']))
	{
		$user_email = trim($_POST['user_email']);
		$user_password = trim($_POST['password']);		
		try
		{	
		
			$stmt = $db_con->prepare("SELECT u.*,c.* FROM user_activity_management u,company c WHERE u.username=:username and u.companyid=c.companyid and u.section_id=c.section_id ");
			$stmt->execute(array(":username"=>$user_email));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$count = $stmt->rowCount();
			
if($row['password']==$user_password){				
$_SESSION['login_email'] = $row['username'];
$_SESSION['company_id']= $row[companyid];
$_SESSION['sectionid']= $row[section_id];	
$_SESSION["userid"] = $row[user_id];
$_SESSION["PBI_ID"] = $row[PBI_ID];
$_SESSION["username"] = $row[fname];
$_SESSION["email"] = $row[email];
$_SESSION["warehouse"] = $row[warehouse_id];
$_SESSION["department"]= $row[department];
$_SESSION["dep_power_level"]= $row[dep_power_level];
$_SESSION["userlevel"]= $row[level];
$_SESSION["ip"] = $row[ip];
$_SESSION["logo_color"]= $row[mac];
$_SESSION["designation"]= $row[designation];
$_SESSION["status"]= $row[status];
$_SESSION['usergroup']=$row[group_for];
$_SESSION['gander']=$row[gander];
$_SESSION['create_date']=date('Y-m-d');

} else{
				echo "email or password does not exist."; // wrong details
			}
				
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

?>