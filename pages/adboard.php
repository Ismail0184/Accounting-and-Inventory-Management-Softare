


<?php require_once 'header_content_addatime.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php  require_once 'body_content_addatimes.php';?>





<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size:11px" method="post" >
        <table align="center" style="width: 60%; font-size:11px">
        
        
            <tr>
            
            <th>Data Type : </th>
<th>
<select class="form-control" style="width: 180px; font-size:11px; height:25px; vertical-align:middle" tabindex="-1" required="required" name="datatype" id="datatype">
<option value="1" <?php if($_POST[datatype]=='1'){ echo 'selected'; } else { '';} ?>>Subscription</option>
<option value="2" <?php if($_POST[datatype]=='2'){ echo 'selected';} else {'';} ?>>Without Subscription</option>
</select></th>
            
            
            <td>
                <input type="date"  style="width:130px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?php if($_POST[first_date]) echo $_POST[first_date]; else echo date('Y-m-01');?>" required   name="first_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:130px;font-size: 11px; height: 25px"  value="<?php if($_POST[last_date]) echo $_POST[last_date]; else echo date('Y-m-d');?>" required  max="<?=date('Y-m-d');?>" name="last_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 25px; vertical-align:middle" name="viewreport"  class="btn btn-primary">View User Data</button></td>
            </tr></table>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table style="width:100%; font-size: 11px" id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 2%">#</th>
                            <th style="">ID</th>
                            <th style="">Name</th>
                            <th style="">Email</th>
                            <th style="">Phone</th>
                            <?php  if($_POST[datatype]=='1'){ ?>
                            <th style="">Subscribed Package</th>
                            <th style="">Will Expire After</th>
							<?php } ?>
                            <th style="text-align: center">Registration Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                       
                        if(isset($_POST[viewreport])){
                           if($_POST[datatype]=='2'){
$str_data = file_get_contents("https://www.addatimes.com/api/sslcommerz-users?active_status=0&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBZGRhdGltZXMgTWVkaWEgUHJpdmF0ZSBMaW1pdGVkIiwiaWF0IjoxNTkxMDY3NzMyLCJleHAiOjE3NDg4MzQxMzIsImF1ZCI6Ind3dy5zc2xjb21tZXJ6LmNvbSIsInN1YiI6InNzbGNvbW1lcnpAYWRkYXRpbWVzbWVkaWEuY29tIiwiR2l2ZW5OYW1lIjoiU1NMIiwiU3VybmFtZSI6IkNvbW1lcnoiLCJFbWFpbCI6InNzbGNvbW1lcnpAYWRkYXRpbWVzbWVkaWEuY29tIiwiUm9sZSI6IlZpZXdlciJ9.iU1-6RefSa0m6g9vPmQURsBUuAh9Lkp5yWPrP1OpIMA");
} else {
$str_data = file_get_contents("https://www.addatimes.com/api/sslcommerz-users?active_status=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBZGRhdGltZXMgTWVkaWEgUHJpdmF0ZSBMaW1pdGVkIiwiaWF0IjoxNTkxMDY3NzMyLCJleHAiOjE3NDg4MzQxMzIsImF1ZCI6Ind3dy5zc2xjb21tZXJ6LmNvbSIsInN1YiI6InNzbGNvbW1lcnpAYWRkYXRpbWVzbWVkaWEuY29tIiwiR2l2ZW5OYW1lIjoiU1NMIiwiU3VybmFtZSI6IkNvbW1lcnoiLCJFbWFpbCI6InNzbGNvbW1lcnpAYWRkYXRpbWVzbWVkaWEuY29tIiwiUm9sZSI6IlZpZXdlciJ9.iU1-6RefSa0m6g9vPmQURsBUuAh9Lkp5yWPrP1OpIMA");	
}
$data = json_decode($str_data, true);  }
for($i = 0; $i < sizeof($data["data"]); $i++){

$report_date = new DateTime($data['data'][$i]['registration_date']);
$first_date = new DateTime($_POST['first_date']); 
$last_date = new DateTime($_POST['last_date']);
$last_date->setTime(23, 59, 59);

//echo $report_date ;



if($report_date >= $first_date && $report_date <= $last_date){ ?>

                            <tr style="font-size:11px; cursor: pointer" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)">
                                <th style="text-align:center"><?=$is=$is+1;?></th>
                                <td><?=$data["data"][$i]["id"];?></td>
                                <td><?=$data["data"][$i]["first_name"];?> <?=$data["data"][$i]["last_name"];?></td>
                                <td><?=$data["data"][$i]["email"];?></td>
                                <td><?=$data["data"][$i]["phone"];?></td>
                                 <?php  if($_POST[datatype]=='1'){ 
								$f=$data["data"][$i]["subscription_start"];
								$e=$data["data"][$i]["subscription_end"];
								$c=date('Y-m-d');
								$datetime1 = date_create($f); 
                                $datetime2 = date_create($e); 
								$datetime3 = date_create($c); 	
								
								
								
								 
                                $interval = date_diff($datetime1, $datetime2);
								$no_of_date=$interval->format('%a');
								
								
							    
								
								 ?>
                                <td style="text-align:left"><?php 
								 if($no_of_date<31) 
								{ echo '30 Days (Montly)'; }
								
								elseif ($no_of_date<61 && $no_of_date>31) { echo 'Quterly';}
								elseif ($no_of_date<180 && $no_of_date>31 && $no_of_date>61) { echo '6 Months';
								
								} elseif ($no_of_date>300) 
								{ echo '365 Days (Yearly)';}?></td>
                                <td style="text-align:center"><?php $interval = date_diff($datetime3,$datetime2);
								$rem=$interval->format('%a');
								echo $rem;?> Days</td>
                                <?php } ?>
                                <td style="text-align:center"><?=$data["data"][$i]["registration_date"];?></td>
                            </tr>
                        <?php }} ?></tbody></table>
                      <?php mysqli_close($conn); ?>

                </div></div></div></form>
<?php } ?>

<?php require_once 'footer_content.php' ?>