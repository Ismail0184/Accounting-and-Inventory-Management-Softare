<?php
 ob_start();
 session_start();
 require_once 'base.php';
  require_once 'module.php';
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['user_id']) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM register WHERE m_id=".$_SESSION['m_id']);
 $userRow=mysql_fetch_array($res);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     <title>Batch Mates | News Feed </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="dashboard.php" class="site_title"><i class="fa fa-paw"></i> <span>Batch Mates</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <?php include ("pro.php");  ?>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <?php include("sidebar_menu.php"); ?>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
             <?php include("menu_footer.php"); ?>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <?php include("top.php"); ?>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main" >
          <div class="">
            
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="x_panel" align="center" style="height:1000px; background-color:transparent; border:none">
                  
               
                  <?php 
				  $nmae=$_SESSION[m_id];
				  
				  if($_GET[m_id]){ 
                  $photo=getSVALUE("register", "banner", "where m_id='$_GET[m_id]'");
				  if($photo==''){
				  ?>
                  <div data-role="page" align="center" style="height:250px;background:#CCC;background-size: 100% 100%;width:90%; margin-top:10px; margin-left:10px" >
                  <?php } else { ?>
                  <div data-role="page" align="center" style="height:250px;background:url(<?php echo $photo; ?>);background-size: 100% 100%;width:90%; margin-top:10px; margin-left:10px" >
                  
                  
                  <?php
				  
				  }} else {
				  $photo=getSVALUE("register", "banner", "where m_id='$nmae'"); ?>
                  <a href="http://batch-mates.com/dashboard/logo/banner.php?edit=ok&edit_type=profile_picture_change" >
                  <div data-role="page" align="center" style="height:250px;
                 <?php if($photo==''){ ?>
                 background:#CCC;
                 <?php } else { ?>
                  background:url(<?php echo $photo; ?>)<?php } ?>;background-size: 100% 100%;width:90%; margin-top:10px; margin-left:5px" >
				<?php   } ?>
	
	
   
    
         <?php
	
		 if($_GET[m_id]){
			 
		 $result=mysql_query("Select * from register where m_id='$_GET[m_id]'"); } else {
			 $mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
		 $result=mysql_query("Select * from register where m_id='$mid'");
		 }
		 $rowss=mysql_fetch_array($result);
		 
		 		 ?>
                 
         <h1 align="right" style=" font-family:Tahoma, Geneva, sans-serif; color:#FFF; padding-right:20px; text-decoration:none; font-size:25px; padding-top:30px"><i>Class of <?php echo $rowss['b_to']; ?></i></h1>
   <?php if($_GET[m_id]){ 
   if($photo==''){
     ?>
      
</div>
<?php } else { ?> 
</div>
<?php }} else { ?>
</div></a>
<?php } ?>


              <div class="row" style="margin-top:-110px;">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                  <br>
                  <table style="width:100%"><tr>
                  <td style="width:25%">
                  
                  
                  
                  
                  
                   <?php 
			  
			
				$uid=$_SESSION["user_id"];
                $photo=$rowss['photo']; 
				$gender=$rowss['user_gender'];
 				$m_id=getSVALUE("register", "m_id", "where user_email='$uid'");
				$nmae=$_SESSION[user_id];
				$nms=getSVALUE("register", "full_name"," where user_email='$nmae'");
				$utype=getSVALUE("register", "user_type"," where user_email='$nmae'");
				
				?>
                  
                  
                  <?php 
				if($photo=='Null') { 
				if(($gender)=='Male'){ 
					 if($_GET[m_id]){ ?>
				<p class="centered"><img src="http://batch-mates.com/dashboard/defult_pp.png" style=" height:170px; width:150px" ></p>
                <?php } else { ?>
					
					<p class="centered"><a href="http://batch-mates.com/dashboard/logo/index.php?edit=ok&edit_type=profile_picture_change" ><img src="http://batch-mates.com/dashboard/defult_pp.png" style=" height:170px; width:150px" ></a></p>
				<?php } ?>
 			<?php } 
			if(($gender)=='Female'){
				 if($_GET[m_id]){ 
			?>
			
			<p class="centered"><img src="http://batch-mates.com/dashboard/defult_pp_female.png" style=" height:170px; width:150px" ></p>
			<?php } else { ?>
			<p class="centered"><a href="http://batch-mates.com/dashboard/logo/index.php?edit=ok&edit_type=profile_picture_change" ><img src="http://batch-mates.com/dashboard/defult_pp_female.png" style=" height:170px; width:150px" ></a></p>
			<?php }}} else { ?>
            
			<?php if($_GET[m_id]){ ?>
              	  <p class="centered"><img src="<?php echo "$photo"; ?>"  style=" height:170px; width:150px" ></p>
                  
                  <?php } else { ?>
                  <p class="centered"><a href="http://batch-mates.com/dashboard/logo/index.php?edit=ok&edit_type=profile_picture_change" ><img src="<?php echo "$photo"; ?>"  style=" height:170px; width:150px" ></a></p>
                  <?php }} ?> 
  
                  </td>
                  
                  <td style="width:75%" align="left">
      <p style="font-size:20px; text-align:justify; font-weight:bold; font-family:Tahoma, Geneva, sans-serif; color:#333; padding-left:30px;">						          <?php echo $rowss['full_name'];  ?>
      
      <?php if($_GET[m_id]){ ?>
                  <?php } else { ?>
                  <a style="font-size:11px; text-decoration:none; padding-left:10%" href="profile.php?edit=ok&edit_type=personal_details">Edit</a>
                  <?php }?>
                  
      <br />
      
      <table style="margin-left:30px; color:#000">
      <tr><th>BATCH</th><td style="width:3%"></td><td><?php echo $rowss['b_from'];echo "-"; echo $rowss['b_to'];  ?></td></tr>
      <tr><th>INSTITUTE</th><td></td><td><?php echo $rowss['institute_name']; ?></td></tr>
      <tr><th>DEPARTMENT</th><td></td><td><?php echo $rowss['Branch']; ?></td></tr>
      <!--tr><th>COURSE</th><td></td><td><?php echo $rowss['course'];  ?></td></tr--->
      <tr><th>WORKS</th><td></td><td><?php echo $rowss['profession']; ?></td></tr>
      <tr><th>EMAIL</th><td></td><td><?php echo $rowss['user_email']; ?></td></tr>
      <!--tr><th>LIVE</th><td></td><td><?php echo $rowss['address']; ?></td></tr-->
      
      
      
      
      </table>
    
     
     <ul class="nav pull-right top-menu" style="float:right; padding:0px; margin-top:0px;">
                    
                    
                    <a class="btn btn-success" href="inbox.php?message_to=<?php echo $_GET[m_id]; ?>"><i class="fa fa-edit m-right-xs"></i>Message</a>
            	</ul></p></td></tr>
            </table>
                  
                  </div>
                  </div>
                  
              
<!------------ start from personal details update--------------------------->
                  <?php 
 if ($_GET[edit]=='ok'){
	if($_GET[edit_type]=='personal_details')
	{ 
	//$delete_work=$_POST[delete_work];
	$edit_personal_details=$_POST[edit_personal_details];
	$full_name=$_POST[full_name];
	$contact_number=$_POST[contact_number];
	$user_email=$_POST[user_email];
	$institute_name=$_POST[institute_name];
	$user_gender=$_POST[user_gender];
	$m_type=$_POST[m_type];
	$course=$_POST[course];
	$Branch=$_POST[Branch];
	$b_from=$_POST[b_from];
	$b_to=$_POST[b_to];
	$profession=$_POST[profession];
	
	
	
	$mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
	$add_date=date("Y-m-d");
	if(isset($edit_personal_details)){
		
		
		
		$insert=mysql_query("Update register SET 
		
		full_name='$full_name',
		contact_number='$contact_number',
		user_email='$user_email',
		institute_name='$institute_name',
		user_gender='$user_gender',
		m_type='$m_type',
		course='$course',
		Branch='$Branch',
		b_from='$b_from',
		b_to='$b_to',
		profession='$profession'
		WHERE m_id='$mid'
		"); ?>
		<meta http-equiv="refresh" content="0;profile.php">
	<?php } ?>
	
	


	
  <div class="row" style="margin-top:30px;" align="center">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
 
 
   
 
                 <h3>Update Personal Details</h3>
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                  <table>
                  
                  <?php
				  $mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
				  $result=mysql_query("SELECT * FROM register WHERE m_id='$mid'");
				  $row=mysql_fetch_array($result);
				  
				   ?>
         
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="full_name" id="full_name" value="<?php echo $row[full_name]; ?>" placeholder="Full Name"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="contact_number" id="contact_number" value="<?php echo $row[contact_number]; ?>" placeholder="Contact Number"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="user_email" id="user_email" value="<?php echo $row[user_email]; ?>" placeholder="User Email"  style="margin-top:10px; height:30px; width:250px" readonly="readonly"  />
        </td></tr>
        
        
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="institute_name" id="institute_name" value="<?php echo $row[institute_name]; ?>" placeholder="institute_name"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="user_gender" id="user_gender" value="<?php echo $row[user_gender]; ?>" placeholder="User Gender"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="m_type" id="m_type" value="<?php echo $row[m_type]; ?>" placeholder="Member Type"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="course" id="course" value="<?php echo $row[course]; ?>" placeholder="Course Name"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="Branch" id="Branch" value="<?php echo $row[Branch]; ?>" placeholder="Branch Name"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="b_from" id="b_from" value="<?php echo $row[b_from]; ?>" placeholder="batch_from"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="b_to" id="b_to" value="<?php echo $row[b_to]; ?>" placeholder="batch_to"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
        
        <tr><td>
        <input type="text" class="form-control col-md-7 col-xs-12" name="profession" id="profession" value="<?php echo $row[profession]; ?>" placeholder="Profession"  style="margin-top:10px; height:30px; width:250px"  />
        </td></tr>
         <tr><td align="center">
         
         <input class="btn btn-success" type="submit" name="edit_personal_details" id="edit_personal_details" value="Update" style="margin-top:20px; width:250px"/></td></tr>

         </table>
         </form>
                  <br><br><br>
                  
                  <a href="#">
                  Do you want to permanently delete your account?
                  </a>
                  </div>
                  </div>	
	
	
                  <?php }} ?>
                  
                  
<!------------ end of pnersonal details update------------------------------>                  
                  
                  <?php 
if($_GET[add]=='ok'){
	if($_GET[edit_type]=='work')
	{ 
	$work_add=$_POST[work_add];
	$company=$_POST[company];
	$Designation=$_POST[Designation];
	$start_from=$_POST[start_from];
	$end_of=$_POST[end_of];
	$mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
	$add_date=date("Y-m-d");
	if(isset($work_add)){
		
		$insert=mysql_query("INSERT INTO work_profile (m_id,company,designation,start_from,end_to,add_date) VALUES ($mid,'$company','$Designation','$start_from','$end_of','$add_date')"); ?>
		<meta http-equiv="refresh" content="0;profile.php">
	<?php }
	
	?>


	
  <div class="row" style="margin-top:30px;" align="center">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                 <h3>Add your Work</h3>
<form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                  <table>
                  <tr>
        <td><input type="text" name="company" class="form-control col-md-7 col-xs-12" id="company" placeholder="Where are your working?" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" class="form-control col-md-7 col-xs-12" name="Designation" id="Designation" placeholder="Designation" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" class="form-control col-md-7 col-xs-12" name="start_from" id="start_from" placeholder="Start From" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" class="form-control col-md-7 col-xs-12" name="end_of" id="end_of" placeholder="End Date or Present" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td align="center"><input type="submit" class="btn btn-success" name="work_add" id="work_add" value="Add Work" style="margin-top:10px; width:250px"/></td></tr>
         </table>
         </form>
                  <br>
                  </div>
                  </div>	
	
	
<?php } ?>
	
<?php 
} elseif ($_GET[edit]=='ok'){
	if($_GET[edit_type]=='work')
	{ 
	$delete_work=$_POST[delete_work];
	$edit_work=$_POST[edit_work];
	$company=$_POST[company];
	$Designation=$_POST[Designation];
	$start_from=$_POST[start_from];
	$sl=$_POST[sl];
	$end_of=$_POST[end_of];
	$mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
	$add_date=date("Y-m-d");
	if(isset($edit_work)){
		
		$delete=mysql_query("DELETE FROM work_profile WHERE id=$_GET[id]");
		
		$insert=mysql_query("INSERT INTO work_profile (m_id,company,designation,start_from,end_to,add_date,sl) VALUES ($mid,'$company','$Designation','$start_from','$end_of','$add_date','$sl')"); ?>
		<meta http-equiv="refresh" content="0;profile.php">
	<?php }
	
	if(isset($delete_work)){
		$delete=mysql_query("DELETE FROM work_profile WHERE id=$_GET[id]"); ?>
		
		<meta http-equiv="refresh" content="0;profile.php">
	<?php }	?>


	
  <div class="row" style="margin-top:30px;" align="center">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                 <h3>Edit your Work</h3>
<form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                  <table>
                  
                  <?php
				  $mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
				  $result=mysql_query("SELECT * FROM work_profile WHERE id='$_GET[id]' and m_id='$mid'");
				  $row=mysql_fetch_array($result);
				  
				   ?>
         <tr><td><input type="text" class="form-control col-md-7 col-xs-12" name="sl" id="sl" value="<?php echo $row[sl]; ?>"  style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" class="form-control col-md-7 col-xs-12" name="company" id="company" value="<?php echo $row[company]; ?>"  style="margin-top:10px; width:250px"  /></td></tr>
 <tr><td><input type="text" class="form-control col-md-7 col-xs-12" name="Designation" id="Designation" value="<?php echo $row[designation]; ?>" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" class="form-control col-md-7 col-xs-12" name="start_from" id="start_from" value="<?php echo $row[start_from]; ?>"  style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" class="form-control col-md-7 col-xs-12" name="end_of" id="end_of" value="<?php echo $row[end_to]; ?>"  style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td align="center"><input type="submit" class="btn btn-success" name="edit_work" id="edit_work" value="Edit Work" style="margin-top:10px; width:250px"/></td></tr>
        
        <tr><td align="center"><input type="submit" class="btn btn-success" name="delete_work" id="delete_work" value="Delete Work" style="margin-top:20px; width:250px"/></td></tr>
         </table>
         </form>
                  <br>
                  </div>
                  </div>	
	
	
<?php } ?>	
	
<?php } else {

?>
                  
                  
                  <div class="row" style="margin-top:30px;">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                  <table style="width:100%">
                  <tr>
                  <td width="90%">Work Profile</td><td align="right">
                  
				  <?php if($_GET[m_id]){ ?>
                  <?php } else { ?>
                  <a href="profile.php?add=ok&edit_type=work">Add</a>
                  <?php }?>
                  
               </td></tr>
              
			   <?php
		 if($_GET[m_id]){
			 
		 $result=mysql_query("SELECT * FROM work_profile WHERE m_id='$_GET[m_id]' order by sl"); } else {
		 $mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
		 $result=mysql_query("SELECT * FROM work_profile WHERE m_id='$mid' order by sl");
		 }
		 while($work_row=mysql_fetch_array($result)){
		 
		 		 ?>
     		<tr>
            
            <td width="90%"><font style="font-weight:bold; color:#006; font-size:17px; font-family:Tahoma, Geneva, sans-serif"><?php echo $work_row['company']; ?></font><br /><font style="font-size:15px;"><?php echo $work_row['designation']; ?></font><br /><?php echo $work_row['start_from']; ?> to <?php echo $work_row['end_to']; ?></p></td>
            <td align="right">
            
            
            <?php if($_GET[m_id]){ ?>
                  <?php } else { ?>
                  <a href="profile.php?edit=ok&edit_type=work&id=<?php echo $work_row['id']; ?>">Edit</a>
                  <?php }?>
            
            </td>
            </tr>
			<?php } ?>
            </table>
                  
                  </div>
                  </div>
                  <?php } ?>
                  
                  
  
  
<!-------------------------- end of work---------------------------------------------->  
  
                  


                  <?php 
if($_GET[add]=='ok'){
	if($_GET[add_type]=='education')
	{ 
	$education_add=$_POST[education_add];
	$university=$_POST[university];
	$course=$_POST[course];
	$Board=$_POST[Board];
	$start_from=$_POST[start_from];
	$end_of=$_POST[end_of];
	$passing_year=$_POST[passing_year];
	$mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
	$add_date=date("Y-m-d");
	if(isset($education_add)){
		
		$insert=mysql_query("INSERT INTO education_profile (m_id,university,course,Board,start_from,end_to,passing_year,add_date) VALUES ($m_id,'$university','$course','$Board','$start_from','$end_of','$passing_year','$add_date')"); ?>
		<meta http-equiv="refresh" content="0;profile.php">
	<?php }
	
	?>


	
  <div class="row" style="margin-top:30px;" align="center">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                 <h3>Add Education</h3>
                  <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                  <table>
                  <tr>
        <td>
        
        
        <!--input type="text" name="university" id="university" placeholder="University" style="margin-top:10px; width:250px"  /--->
        <select name="university" class="form-control col-md-7 col-xs-12" id="university" style="margin-top:10px; height:30px; width:250px">
        <option>Select a University</option>
		<?php
		$select=mysql_query("SELECT * FROM institute_name");
		while($row=mysql_fetch_array($select)){ ?>
		
		
		<option><?php echo $row['institute_name']; ?></option>
		
        <?php } ?>
		</select>
        
        </td></tr>
        <tr><td>
        
        <select name="course" id="course" class="form-control col-md-7 col-xs-12" style="margin-top:10px; height:30px; width:250px">
        <option>Select a course</option>
        <?php
		$select=mysql_query("SELECT * FROM course_type");
		while($row=mysql_fetch_array($select)){ ?>
		
		
		<option><?php echo $row['course_type']; ?></option>
		
        <?php } ?>
		</select>
        
        
        </td></tr>
        
        
        
        <tr><td>
        <select name="Board" id="Board" class="form-control col-md-7 col-xs-12" style="margin-top:10px; height:30px; width:250px">
        <option>Select a Board</option>
        <?php
		$select=mysql_query("SELECT * FROM board_name order by board");
		while($row=mysql_fetch_array($select)){ ?>
		
		
		<option><?php echo $row['board']; ?></option>
		
        <?php } ?>
		</select>
        </td></tr>
       <tr><td>
       <select name="start_from" id="start_from" class="form-control col-md-7 col-xs-12" style="margin-top:10px; height:30px; width:250px">
        <option>Start From</option>
        <?php
		$select=mysql_query("SELECT * FROM create_batch order by create_batch");
		while($row=mysql_fetch_array($select)){ ?>
		
		
		<option><?php echo $row['create_batch']; ?></option>
		
        <?php } ?>
		</select>
       </td></tr>
       <tr><td>
       
       <select name="end_of" id="end_of" class="form-control col-md-7 col-xs-12" style="margin-top:10px; height:30px; width:250px">
        <option>End Date</option>
        <option>Present</option>
        
        <?php
		$select=mysql_query("SELECT * FROM create_batch order by create_batch");
		while($row=mysql_fetch_array($select)){ ?>
		
		
		<option><?php echo $row['create_batch']; ?></option>
		
        <?php } ?>
		</select>
       </td></tr>
       <tr><td>
       
       
       <select name="passing_year" id="passing_year" class="form-control col-md-7 col-xs-12" style="margin-top:10px; height:30px; width:250px">
        <option>Passing Year</option>
        
        
        <?php
		$select=mysql_query("SELECT * FROM create_batch order by create_batch");
		while($row=mysql_fetch_array($select)){ ?>
		
		
		<option><?php echo $row['create_batch']; ?></option>
		
        <?php } ?>
		</select>
   </td></tr>
        <tr><td align="center"><input type="submit" class="btn btn-success" name="education_add" id="education_add" value="Add Education" style="margin-top:10px; width:250px"/></td></tr>
        
        <tr><td align="center"><a class="btn btn-success" href="profile.php" style="margin-top:10px; width:250px">Cancel</a></td></tr>
         </table>
         </form>
                  <br>
                  </div>
                  </div>	
	
	
<?php } ?>
<!-------------------------------------------------------end of add education--------------------------->	
<?php 
} elseif ($_GET[edit]=='ok'){
	if($_GET[edit_type]=='education')
	{ 
	$delete_education=$_POST[delete_education];
	$edit_education=$_POST[edit_education];
	$education_add=$_POST[education_add];
	$university=$_POST[university];
	$course=$_POST[course];
	$Board=$_POST[Board];
	$start_from=$_POST[start_from];
	$sl=$_POST[sl];
	$end_of=$_POST[end_of];
	$passing_year=$_POST[passing_year];
	$mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
	$add_date=date("Y-m-d");
	
	
	
	if(isset($edit_education)){
		
		$delete=mysql_query("DELETE FROM education_profile WHERE id=$_GET[id]");
		
		$insert=mysql_query("INSERT INTO education_profile (sl,m_id,university,course,Board,start_from,end_to,passing_year,add_date) VALUES ($sl,'$m_id','$university','$course','$Board','$start_from','$end_of','$passing_year','$add_date')"); ?>
		<meta http-equiv="refresh" content="0;profile.php">
	<?php }
	
	if(isset($delete_education)){
		$delete=mysql_query("DELETE FROM education_profile WHERE id=$_GET[id]"); ?>
		
		<meta http-equiv="refresh" content="0;profile.php">
	<?php }	?>


	
  <div class="row" style="margin-top:30px;" align="center">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                 <h3>Edit Education</h3>
                  <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                  <table>
                  
                  <?php
				  $mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
				  $result=mysql_query("SELECT * FROM education_profile WHERE id='$_GET[id]' and m_id='$mid'");
				  $row=mysql_fetch_array($result);
				  
				   ?>
         <tr><td><input type="text" name="sl" class="form-control col-md-7 col-xs-12" id="sl" value="<?php echo $row[sl]; ?>" placeholder="Serial Number"  style="margin-top:10px; width:250px"  /></td></tr>
        <td><input type="text" name="university" class="form-control col-md-7 col-xs-12" id="university" value="<?php echo $row[university]; ?>" placeholder="University" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" name="course" class="form-control col-md-7 col-xs-12" id="course" value="<?php echo $row[course]; ?>" placeholder="Course" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" name="Board" class="form-control col-md-7 col-xs-12" id="Board" value="<?php echo $row[Board]; ?>" placeholder="Board" style="margin-top:10px; width:250px"  /></td></tr>
       <tr><td><input type="text" name="start_from" class="form-control col-md-7 col-xs-12" id="start_from" value="<?php echo $row[start_from]; ?>" placeholder="Start From" style="margin-top:10px; width:250px"  /></td></tr>
       <tr><td><input type="text" name="end_of" class="form-control col-md-7 col-xs-12" id="end_of" value="<?php echo $row[end_to]; ?>" placeholder="End Date or Present" style="margin-top:10px; width:250px"  /></td></tr>
       <tr><td><input type="text" name="passing_year" class="form-control col-md-7 col-xs-12" id="passing_year" value="<?php echo $row[passing_year]; ?>" placeholder="passing_year" style="margin-top:10px; width:250px"  /></td></tr>
        
        
        <tr><td align="center"><input type="submit" class="btn btn-success" name="edit_education" id="edit_education" value="Edit Education" style="margin-top:10px; width:250px"/></td></tr>
        <tr><td align="center"><input type="submit" class="btn btn-success" name="delete_education" id="delete_education" value="Delete Education" style="margin-top:20px; width:250px"/></td></tr>
         </table>
         </form>
                  <br>
                  </div>
                  </div>	
	
	
<?php } ?>	
<!----------------------------------------end of education edit------------------------>	
<?php } else {

?>
                  
                  
                  <div class="row" style="margin-top:30px;">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                  <table style="width:100%">
                  <tr>
                  <td width="90%">Education</td><td align="right">
                  
                  
                  <?php if($_GET[m_id]){ ?>
                  <?php } else { ?>
                  <a href="profile.php?add=ok&add_type=education">Add</a>
                  <?php }?>
                  
                  
                  </td></tr>
              
			   <?php
		 if($_GET[m_id]){
			 
		 $result=mysql_query("SELECT * FROM education_profile WHERE m_id='$_GET[m_id]' order by sl"); } else {
		 $mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
		 $result=mysql_query("SELECT * FROM education_profile WHERE m_id='$mid' order by sl");
		 }
		 while($work_row=mysql_fetch_array($result)){
		 
		 		 ?>
     		<tr>
            
            <td width="90%"><font style="font-weight:bold; color:#006; font-size:17px; font-family:Tahoma, Geneva, sans-serif"><?php echo $work_row['university']; ?></font><br /><font style="font-size:15px;"><?php echo $work_row['course']; ?></font><br /><?php echo $work_row['start_from']; ?> to <?php echo $work_row['end_to']; ?></p></td>
            <td align="right">
            
            
           <?php if($_GET[m_id]){ ?>
                  <?php } else { ?>
                  <a href="profile.php?edit=ok&edit_type=education&id=<?php echo $work_row['id']; ?>">Edit</a>
                  <?php }?> 
            
            </td>
            </tr>
			<?php } ?>
            </table>
                  
                  </div>
                  </div>
                  <?php } ?>
                       
                  
                  
 
 
 
 <!-------------------------- end of education---------------------------------------------->  
  
                  


                  <?php 
if($_GET[add]=='ok'){
	if($_GET[add_type]=='experience')
	{ 
	$experience_add=$_POST[experience_add];
	$company=$_POST[company];
	$Designation=$_POST[Designation];
	$start_from=$_POST[start_from];
	$end_of=$_POST[end_of];
	$mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
	$add_date=date("Y-m-d");
	if(isset($experience_add)){
		
		$insert=mysql_query("INSERT INTO experience (m_id,company,designation,start_from,end_to,add_date) VALUES ($mid,'$company','$Designation','$start_from','$end_of','$add_date')"); ?>
		<meta http-equiv="refresh" content="0;profile.php">
	<?php }
	
	?>


	
  <div class="row" style="margin-top:30px;" align="center">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                 <h3>Add your experience</h3>
                  <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                  <table>
                  <tr>
        <td><input type="text" name="company" class="form-control col-md-7 col-xs-12" id="company" placeholder="Where did you worked?" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" name="Designation" class="form-control col-md-7 col-xs-12" id="Designation" placeholder="Designation" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" name="start_from" class="form-control col-md-7 col-xs-12" id="start_from" placeholder="Start From" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" name="end_of" class="form-control col-md-7 col-xs-12" id="end_of" placeholder="End Date or Present" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td align="center"><input type="submit" class="btn btn-success" name="experience_add" id="experience_add" value="Add Experience" style="margin-top:10px; width:250px"/></td></tr>
         </table>
         </form>
                  <br>
                  </div>
                  </div>	
	
	
<?php } ?>
	
<?php 
} elseif ($_GET[edit]=='ok'){
	if($_GET[edit_type]=='experience')
	{ 
	$delete_experience=$_POST[delete_experience];
	$edit_experience=$_POST[edit_experience];
	$company=$_POST[company];
	$Designation=$_POST[Designation];
	$start_from=$_POST[start_from];
	$sl=$_POST[sl];
	$end_of=$_POST[end_of];
	$mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
	$add_date=date("Y-m-d");
	if(isset($edit_experience)){
		
		$delete=mysql_query("DELETE FROM experience WHERE id=$_GET[id]");
		
		$insert=mysql_query("INSERT INTO experience (m_id,company,designation,start_from,end_to,add_date,sl) VALUES ($mid,'$company','$Designation','$start_from','$end_of','$add_date','$sl')"); ?>
		<meta http-equiv="refresh" content="0;profile.php">
	<?php }
	
	if(isset($delete_experience)){
		$delete=mysql_query("DELETE FROM experience WHERE id=$_GET[id]"); ?>
		
		<meta http-equiv="refresh" content="0;profile.php">
	<?php }	?>


	
  <div class="row" style="margin-top:30px;" align="center">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                 <h3>Edit your experience</h3>
                  <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                  <table>
                  
                  <?php
				  $mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
				  $result=mysql_query("SELECT * FROM experience WHERE id='$_GET[id]' and m_id='$mid'");
				  $row=mysql_fetch_array($result);
				  
				   ?>
         <tr><td><input type="text" name="sl" class="form-control col-md-7 col-xs-12" id="sl" value="<?php echo $row[sl]; ?>"  style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" name="company" class="form-control col-md-7 col-xs-12" id="company" value="<?php echo $row[company]; ?>"  style="margin-top:10px; width:250px"  /></td></tr>
 <tr><td><input type="text" name="Designation" class="form-control col-md-7 col-xs-12" id="Designation" value="<?php echo $row[designation]; ?>" style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" name="start_from" class="form-control col-md-7 col-xs-12" id="start_from" value="<?php echo $row[start_from]; ?>"  style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td><input type="text" name="end_of" class="form-control col-md-7 col-xs-12" id="end_of" value="<?php echo $row[end_to]; ?>"  style="margin-top:10px; width:250px"  /></td></tr>
        <tr><td align="center"><input type="submit" class="btn btn-success" name="edit_experience" id="edit_experience" value="Edit Experience" style="margin-top:10px; width:250px"/></td></tr>
        
        <tr><td align="center"><input type="submit" class="btn btn-success" name="delete_experience" id="delete_experience" value="Delete Experience" style="margin-top:20px; width:250px"/></td></tr>
         </table>
         </form>
                  <br>
                  </div>
                  </div>	
	
	
<?php } ?>	
	
<?php } else {

?>
                  
                  
                  <div class="row" style="margin-top:30px;">
                  <div class="col-lg-9 main-chart" style="background-color:#FFF; width:80%; height:auto; margin-left:10%">
                  <table style="width:100%">
                  <tr>
                  <td width="90%">Experience</td><td align="right">
                  
                  <?php if($_GET[m_id]){ ?>
                  <?php } else { ?>
                  <a href="profile.php?add=ok&add_type=experience">Add</a>
                  <?php }?>
                  
                  </td></tr>
              
			   <?php
		 if($_GET[m_id]){
			 
		 $result=mysql_query("SELECT * FROM experience WHERE m_id='$_GET[m_id]' order by sl"); } else {
		 $mid=getSVALUE("register", "m_id"," where user_email='$_SESSION[user_id]'");
		 $result=mysql_query("SELECT * FROM experience WHERE m_id='$mid' order by sl");
		 }
		 while($work_row=mysql_fetch_array($result)){
		 
		 		 ?>
     		<tr>
            
            <td width="90%"><font style="font-weight:bold; color:#006; font-size:17px; font-family:Tahoma, Geneva, sans-serif"><?php echo $work_row['company']; ?></font><br /><font style="font-size:15px;"><?php echo $work_row['designation']; ?></font><br /><?php echo $work_row['start_from']; ?> to <?php echo $work_row['end_to']; ?></p></td>
            <td align="right">
            
            
            
            <?php if($_GET[m_id]){ ?>
                  <?php } else { ?>
                  <a href="profile.php?edit=ok&edit_type=experience&id=<?php echo $work_row['id']; ?>">Edit</a>
                  <?php }?>
            
            </td>
            </tr>
			<?php } ?>
            </table>
                  
                  </div>
                  </div>
                  <?php } ?>                
            </div><!-- /col-lg-9 END SECTION MIDDLE -->
                  
               
    
              </div>

              
                    
                    
                   <?php include("batch_matess.php"); ?>
                   <?php include("advertisment.php"); ?>
				   <?php include ("event.php"); ?>
                 <?php //include ("help.php"); ?>
                     
                    
                    


             </div>
</div>
    </div>

    <script src="../build/js/custom.min.js"></script>

    
  </body>
</html>
<?php ob_end_flush(); ?>