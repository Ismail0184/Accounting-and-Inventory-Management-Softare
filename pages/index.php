<?php
session_start();
require_once ('base.php');
if(isset($_SESSION['login_email'])!="")
{header("Location: dashboard.php");}
if($_GET[module]>0){
    $_SESSION['module_id']=$_GET[module];
}
if(isset($_POST['btn-login']))
	{   $user_email = trim($_POST['user_email']);
		$user_password = trim($_POST['password']);
		try
		{	$stmt = $db_con->prepare("SELECT u.*,c.* FROM users u,company c WHERE u.username=:username and u.company_id=c.company_id and u.section_id=c.section_id ");
			$stmt->execute(array(":username"=>$user_email));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$count = $stmt->rowCount();
if($row['password']==$user_password){
$_SESSION['login_email'] = $row['username'];
$_SESSION['companyid']= $row[company_id];
$_SESSION['sectionid']= $row[section_id];	
$_SESSION["userid"] = $row[user_id];
$_SESSION["PBI_ID"] = $row[PBI_ID];
$_SESSION["username"] = $row[fname];
$_SESSION["email"] = $row[email];
$_SESSION["warehouse"] = $row[warehouse_id];
$_SESSION["department"]= $row[department];
$_SESSION["dep_power_level"]= $row[dep_power_level];
$_SESSION["userlevel"]= $row[level];
$_SESSION["language"] = 'English';
$_SESSION["logo_color"]= $row[logo_color];
$_SESSION["designation"]= $row[designation];
$_SESSION["status"]= $row[status];
//$_SESSION["PASSCODE"]= $row[password];
$_SESSION['usergroup']=$row[group_for];
$_SESSION['gander']=$row[gander];
$_SESSION['userpic']=$row[picture_url];
$_SESSION['create_date']=date('Y-m-d');
$res=mysqli_query($conn, "SELECT * FROM company WHERE  section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
 $userRow=mysqli_fetch_array($res);
$_SESSION['company_name']=$userRow[company_name];
$_SESSION['company_address']=$userRow[address];
$_SESSION['com_short_name']=$userRow[com_short_name];
$_SESSION['section_name']=$userRow[section_name];
header("Location: dashboard.php");
			} else{
				echo "email or password does not exist."; // wrong details
			}}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
?>
<!doctype html>
<html lang="en">
<head>
        
        <meta charset="utf-8" />
        <title>Login | ERP Software</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="ERP software using php mysql" name="description" />
        <meta content="Md Ismail Hossain" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="../assets/images/icon/title.png">
        <!-- Bootstrap Css -->
        <link href="../assets/login/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="../assets/login/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="../assets/login/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body>
        <div class="home-btn d-none d-sm-block">
            <a href="index.php" class="text-dark"><i class="fas fa-home h2"></i></a>
        </div>
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-soft-primary">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p>Sign in to continue to ICP ERP.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="../assets/login/images/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0"> 
                                <div>
                                    <a href="index.html">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="../assets/login/images/logo.svg" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">
                                    <form class="form-horizontal" action="" method="POST">
        
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" name="user_email" class="form-control" style="font-size:11px" id="user_email" placeholder="Enter username">
                                        </div>
                
                                        <div class="form-group">
                                            <label for="userpassword">Password</label>
                                            <input type="password" name="password" class="form-control" id="password" style="font-size:11px" placeholder="Enter password">
                                        </div>
                
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customControlInline">
                                            <label class="custom-control-label" for="customControlInline">Remember me</label>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit" name="btn-login">Log In</button>
                                        </div>
            
                                       

                                        <div class="mt-4 text-center">
                                            <h5 class="font-size-14 mb-3">Sign in with</h5>
            
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-primary text-white border-primary">
                                                        <i class="mdi mdi-facebook"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-info text-white border-info">
                                                        <i class="mdi mdi-twitter"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()" class="social-list-item bg-danger text-white border-danger">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock mr-1"></i> Forgot your password?</a>
                                        </div>
                                    </form>
                                </div>
            
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            
                            <div>
                                <p>Don't have an account ? <a href="#" class="font-weight-medium text-primary"> Signup now </a> </p>
                                <p>© <?=date('Y')?> ICP ERP. Crafted with <i class="mdi mdi-heart text-danger"></i> by Raresoft</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="../assets/login/libs/jquery/jquery.min.js"></script>
        <script src="../assets/login/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/login/libs/metismenu/metisMenu.min.js"></script>
        <script src="../assets/login/libs/simplebar/simplebar.min.js"></script>
        <script src="../assets/login/libs/node-waves/waves.min.js"></script>
        
        <!-- App js -->
        <script src="../assets/login/js/app.js"></script>
    </body>
</html>



