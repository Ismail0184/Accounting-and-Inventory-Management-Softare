<?php 
$uid=$_SESSION["user_id"];
                $photo=$row['photo']; 
				$gender=$row['user_gender'];
 				$m_id=getSVALUE("register", "m_id", "where user_email='$uid'");
				$nmae=$_SESSION[user_id];
				$nms=getSVALUE("register", "full_name"," where user_email='$nmae'");
				$utype=getSVALUE("register", "user_type"," where user_email='$nmae'");
				
				
				if($photo=='Null') { 
				if(($gender)=='Male'){ 
					 if($_GET[m_id]){ ?>
				<p class="centered"><a href="http://batch-mates.com/dashboard/profile.php?m_id=<?php echo $row[m_id]; ?>" ><img  src="http://batch-mates.com/dashboard/defult_pp.png" style=" height:50px; width:50px" ></a></p>
                <?php } else { ?>
					
					<p class="centered"><a href="http://batch-mates.com/dashboard/profile.php?m_id=<?php echo $row[m_id]; ?>" ><img src="http://batch-mates.com/dashboard/defult_pp.png"   style=" height:50px; width:50px" ></a></p>
				<?php } ?>
 			<?php } 
			if(($gender)=='Female'){
				 if($_GET[m_id]){ 
			?>
			
			<p class="centered"><a href="http://batch-mates.com/dashboard/profile.php?m_id=<?php echo $row[m_id]; ?>" ><img src="http://batch-mates.com/dashboard/defult_pp_female.png"  style=" height:50px; width:50px" ></a></p>
			<?php } else { ?>
			<p class="centered"><a href="http://batch-mates.com/dashboard/profile.php?m_id=<?php echo $row[m_id]; ?>" ><img src="http://batch-mates.com/dashboard/defult_pp_female.png"  style=" height:50px; width:50px" ></a></p>
			<?php }}} else { ?>
            
			<?php if($_GET[m_id]){ ?>
              	  <p class="centered"><img src="<?php echo "$photo"; ?>"  style=" height:50px; width:50px" ></p>
                  
                  <?php } else { ?>
                  <p class="centered"><a href="http://batch-mates.com/dashboard/profile.php?m_id=<?php echo $row[m_id]; ?>" ><img src="<?php echo "$photo"; ?>"  style=" height:50px; width:50px" ></a></p>
                  <?php }} ?>