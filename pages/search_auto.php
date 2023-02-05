<?php
include('db_connection_auto.php');
if(isset($_POST['search_keyword']))
{
	$search_keyword = $dbConnection->real_escape_string($_POST['search_keyword']);
	
	$sqlCountries="SELECT distinct institute_name FROM institute_name WHERE institute_name LIKE '%$search_keyword%'";
    $resCountries=$dbConnection->query($sqlCountries);
    if($resCountries === false) {
        trigger_error('Error: ' . $dbConnection->error, E_USER_ERROR);
    }else{
        $rows_returned = $resCountries->num_rows;
    }
	$bold_search_keyword = '<strong>'.$search_keyword.'</strong>';
	if($rows_returned > 0){
		while($rowCountries = $resCountries->fetch_assoc()) {	 ?>	
			<div class="show" align="left" style="font-size:12px;"><a href="http://batch-mates.com/dashboard/find_friend.php?college=<?php echo  $rowCountries['institute_name']; ?>" style="text-decoration:none"><span class="country_name"><?php echo ' '.str_ireplace($search_keyword,$bold_search_keyword,$rowCountries['institute_name']).' ' ?></span></a></div> 	
	<?php 	}
	}else{
		echo '<div class="show" align="left">No matching records.</div>'; 	
	}
}	
?>
