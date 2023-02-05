 <?php
require_once 'support_file.php';
$title="Vehicle Register";

$now=time();
$unique='id';
$unique_field='registration_no';
$table="vehicle_registration";
$page="print_preview_vehicles.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

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
    //echo $targeturl;
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>



<?php require_once 'header_content.php'; ?>
 <script type="text/javascript">
     function DoNavPOPUP(lk)
     {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 200,top = -1");}
 </script>
<?php require_once 'body_content.php'; ?>





                    <?php if(!isset($_GET[$unique])){ ?>
                    <!-------------------list view ------------------------->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>List of <?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                        <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Vehicle Reg. NO</th>
                            <th>Vehicle Description</th>
                            <th style="text-align:center">Fitness Expiry Date</th>
                            <th style="text-align:center">Tax Token Expiry Date</th>
                            <th style="text-align:center">Insurance Expiry Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? 	$result=mysql_query("Select * from vehicle_registration order by registration_no");
                        while($rows=mysql_fetch_array($result)){
                            $i=$i=1;




                            $fitness_date_from=$rows[fitness_date_from];
                            $fitness_date_to=$rows[fitness_date_to];
                            $now = time(); // or your date as well
                            $your_date = strtotime("$fitness_date_to");
                            $days_betweens = $your_date - $now;
                            $days_between = floor($days_betweens / (60 * 60 * 24));
                            //echo $days_between;

                            $tax_token_date_from=$rows[tax_token_date_from];
                            $tax_token_date_to=$rows[tax_token_date_to];
                            $now = time(); // or your date as well
                            $your_date2 = strtotime("$tax_token_date_to");
                            $days_betweens2 = $your_date2 - $now;
                            $days_between2 = floor($days_betweens2 / (60 * 60 * 24));
                            //echo $days_between2;

                            $insurance_date_from=$rows[insurance_date_from];
                            $insurance_date_to=$rows[insurance_date_to];
                            $now = time(); // or your date as well
                            $your_date3 = strtotime("$insurance_date_to");
                            $days_betweens3 = $your_date3 - $now;
                            $days_between3 = floor($days_betweens3 / (60 * 60 * 24));
                            //echo $days_between3;

                            ?>
                                <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                                    <td style="width:2%"><?=$j=$j+1;?></td>
                                    <td><a href="print_preview_vehicle.php?vid=<?php echo $rows[id]; ?>" target="_new" style="text-decoration:none"><?php echo $rows[registration_no]; ?> 11</a></td>
                                    <td><?php echo $rows[description]; ?><br />Chassis No - <?php echo $rows[chassis]; ?><br />
                                        Engine No - <?php echo $rows[engine_no]; ?><br />
                                    </td>



                                    <td style="text-align:center; vertical-align:middle; <?php if( $days_between<1) { echo 'background-color:red';}?>;">
                                        <?php if( $days_between<1) { echo '<span style="color:#FFF; font-weight:bold">Fitness already Expaired</span>'; ?>                              <?php } else {?>
                                        <?php echo $rows[fitness_date_to]; ?>
                                        <br /><br /><font style="color:#F00; font-weight:bold">
                                            <?php echo $days_between; ?> days remaining
                                            <?php } ?>
                                        </font>
                                    </td>



                                    <td style="text-align:center; vertical-align:middle; <?php if( $days_between2<1) { echo 'background-color:red';}?>;">
                                        <?php if( $days_between2<1) { echo '<span style="color:#FFF; font-weight:bold">Tax Token already Expaired</span>'; ?>                              <?php } else {?>
                                        <?php echo $rows[tax_token_date_to]; ?>
                                        <br /><br /><font style="color:#F00; font-weight:bold">
                                            <?php echo $days_between2; ?> days remaining
                                            <?php } ?>
                                        </font>
                                    </td>





                                    <td style="text-align:center; vertical-align:middle; <?php if( $days_between3<1) { echo 'background-color:red';}?>;">
                                        <?php if( $days_between3<1) { echo '<span style="color:#FFF; font-weight:bold">Insurance already Expaired</span>'; ?>                              <?php } else {?>
                                        <?php echo $rows[insurance_date_to]; ?>
                                        <br /><br /><font style="color:#F00; font-weight:bold">
                                            <?php echo $days_between3; ?> days remaining
                                            <?php } ?>
                                        </font>
                                    </td>



                                </tr>
                        <?php } ?>
                        </tbody>
                        </table>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->
                    <?php } ?>
                    <!---page content----->


                
        
<?php require_once 'footer_content.php' ?>