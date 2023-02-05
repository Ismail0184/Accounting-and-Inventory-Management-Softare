 <?php
require_once 'support_file.php';
$title="Get IMS Data";
$now=time();
$unique='ims_no';
$unique_field='name';
$table="ims_master";
$table_details="ims_details";
$page="get_ims_data_all.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

 $first_number=find_a_field('ims_details','MIN(id)',' ims_no='.$_GET[ims_no].'');
 $lst_number=$first_number+37;

if(prevent_multi_submit()){

//for modify..................................
    if(isset($_POST['modify']))
    {
        $_POST['status']='COMPLETED';
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
	
	mysql_query("delete from ims_details where ims_no='".$$unique."'");
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>window.close(); </script>";
}

if(isset($_POST['resubmit'])){

    $update="Delete from  ims_details where ims_no=".$_GET[ims_no]." and  id not between '$first_number' and '$lst_number' ";
    $up_query=mysqli_query($conn, $update);
    unset($_POST);
    echo "<script>window.close(); </script>";
}


$res=mysqli_query($conn, "select i.item_id,i.item_name,i.unit_name,d.total_unit_today,d.unit_price,d.total_amt_ims,d.id 
									from 
									item_info i,
									ims_details d 
									where 
									i.item_id=d.item_id and d.ims_no=".$_GET[ims_no]." and d.total_unit_today>0  order by i.serial");
                                    while($item=mysqli_fetch_array($res)){
										$did=$item[id];
										$total_unit_today=$_POST['total_unit_today_'.$did];
										$unit_price=$_POST['unit_price_'.$did];
										$total_amt_ims=$_POST['total_amt_ims_'.$did];
										if(isset($_POST['deletedata'.$did])){
											$del=mysqli_query($conn, "DELETE from ".$table_details." where id=".$did." and ".$unique."=".$$unique."");
										}
										if(isset($_POST['editdata'.$did])){
											$del=mysqli_query($conn, "Update ".$table_details." set total_unit_today='".$total_unit_today."',unit_price='".$unit_price."',total_amt_ims='".$total_amt_ims."' where id=".$did." and ".$unique."=".$$unique."");
										}
										
										
									}


}

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
     {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=600,left = 250,top = -1");}
 </script>
 <style>
     input[type=text]{
         font-size: 11px;
     }
 </style>
  <style>
        #customers tr:ntd-child(even)
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: bisque;}
    </style>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
 <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>




 <?php if(isset($_GET[$unique])){ ?>
                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    <table id="customers" style="width:100%;font-size: 11px"  class="table table-striped table-bordered">
                                    <tr style="background-color:bisque">
                                        <th style="width:1%">#</th>
                                        <th style="width:5%">ID</th>
                                        <th style="width:10%">Item ID</th>
                                        <th>Item Name</th>
                                        <th style="width:10%">IMS Qty</th>
                                        <th style="width:10%">Price</th>
                                        <th style="width:10%">Amount</th>
                                        <th style="width:15%; text-align:center">Edit / Delete</th>
                                    </tr>
                                    <?php
                                    $res=mysqli_query($conn, "select i.item_id,i.item_name,i.unit_name,d.total_unit_today,d.unit_price,d.total_amt_ims,d.id 
									from 
									item_info i,
									ims_details d 
									where 
									i.item_id=d.item_id and d.ims_no=".$_GET[ims_no]." and d.total_unit_today>0  order by i.serial,d.id");
                                    while($item=mysqli_fetch_array($res)){
                                        $id=$item[item_id];
                                        $item_id=$_POST['item_id'.$id];
                                        $total_unit_today=$_POST['total_unit_today_'.$id];
                                        $unit_price=$_POST['unit_price_'.$id];
                                        $total_amt_ims=$_POST['total_amt_ims_'.$id];
										$did=$item[id];
                                        ?>
                                    <tr>
                                    <td><?=$i=$i+1;?></td>
                                    <td><?=$item[id];?></td>
                                        <td><input readonly style="font-size: 11px; width: 100%; text-align: center" type="text" name="item_id<?=$did;?>" id="item_id<?=$did;?>"  value="<?=$item[item_id];?>" ></td>
                                    <td><?=$item[item_name];?></td>
                                    <td style="text-align: center"><input style="font-size: 11px; width: 100%; text-align: center" type="text" name="total_unit_today_<?=$did;?>" id="total_unit_today_<?=$did;?>"  value="<?php if($item[total_unit_today]>0) echo $item[total_unit_today]; else echo '';?>" class="total_unit_today_<?=$did;?>"></td>
                                    <td style="text-align: right"><input style="font-size: 11px; width: 100%; text-align: center" type="text" name="unit_price_<?=$did;?>" id="unit_price_<?=$did;?>"  value="<?=$item[unit_price];?>" class="unit_price<?=$did;?>"></td>
                                    <td style="text-align: right"><input style="font-size: 11px; width: 100%; text-align: center" type="text" name="total_amt_ims_<?=$did;?>" id="total_amt_ims_<?=$did;?>"  value="<?php if($item[total_amt_ims]>0) echo $item[total_amt_ims]; else echo '';?>" class="sum"></td>
                                    <td style=""><button type="submit" name="editdata<?=$did;?>" style="background-color:transparent; border:none; float:left" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="update.jpg" style="width:15px; float:left;height:15px"></button>
                                    
                                    <button type="submit" name="deletedata<?=$did;?>" style="background-color:transparent; border:none; float:right" onclick='return window.confirm("Mr. <?=$_SESSION["username"];?>, Are you sure you want to Delete? (<?=$item["id"];?>)");'><img src="delete.png" style="width:15px;  height:15px;"></button>
                                    </td>
                                    </tr>

                                        <script>
                                            $(function(){
                                                $('#unit_price_<?=$did;?>, #total_unit_today_<?=$did;?>').keyup(function(){
                                                   var unit_price_<?=$did;?> = parseFloat($('#unit_price_<?=$did;?>').val()) || 0;
                                                   var total_unit_today_<?=$did;?> = parseFloat($('#total_unit_today_<?=$did;?>').val()) || 0;
                                                   $('#total_amt_ims_<?=$did;?>').val((total_unit_today_<?=$did;?>*unit_price_<?=$did;?>).toFixed(2));
                                                });
                                            });
                                        </script>


                                    <?php $totalIMS=$totalIMS+$item[total_amt_ims];} ?>
                                        <tr>
                                        <td colspan="5" align="right"><strong>IMS TOTAL = </strong></td>
                                            <td></td>
                                            <td align="right"><strong><?=number_format($totalIMS,2);?></strong></td>
                                            <td></td>
                                        </tr>
                                    </table>




                                        <?php
                                        $GET_status=find_a_field($table,'status','ims_no='.$_GET[$unique]);
                                        if($GET_status!=='COMPLETED'){  ?>
                                            <p>
                                             <button style="float:left; font-size:12px" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>DELETE The IMS</button>

                                                <button style="margin-left: 25%;font-size:12px; float:right" type="submit" name="modify" id="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Check This IMS</button>

                                               
                                             <? } else {echo '<h5 style="text-align: center; color: black; font-style: italic; background-color: red">This IMS Data has been Verified!!</h5>';}?>
                                           </p>



                                </form>
                                </div>
                                </div>
                                </div>
                            <?php } ?>

<?php if(!isset($_GET[$unique])){
if(isset($_POST[viewreport])){	
$res="SELECT m.ims_no,m.ims_no,m.order_date as ims_date,m.create_date as entry_date,p.PBI_NAME as Sales_officer_name,(SELECT concat(PBI_ID_UNIQUE ,' # ', PBI_NAME) from personnel_basic_info where PBI_ID=p.tsm) as TSM_name,
                                    SUM(imsd.total_amt_today) as amount,
                                    COUNT(imsd.id) as noofFG,m.status as IMS_status
									
                                    FROM 
                                    
                                    ims_master m,
                                    ims_details imsd,
                                    personnel_basic_info p
                                    where 
                                    
                                    m.ims_no=imsd.ims_no and 
                                    m.PBI_ID=p.PBI_ID and 
                                    m.ims_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'
                                    group by m.ims_no order by p.tsm desc";	}?>
                    <!-------------------list view ------------------------->
 <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
                                 <table align="center" style="width: 50%; font-size: 11px">
                                    <tr><td>
                                            <input type="date"  style="width:150px; font-size:11px" value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required name="f_date" class="form-control col-md-7 col-xs-12" >
                                        <td style="width:10px; text-align:center"> -</td>
                                        <td><input type="date"  style="width:150px; font-size:11px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                                        <td style="padding:10px"><button type="submit" style="font-size: 12px" name="viewreport"  class="btn btn-primary">GET MIS Report</button></td>
                                        </tr>
                                        </table>

                           
<?=$crud->report_templates_with_data($res,$title);?>   
</form>         
<?php } ?>     
<?php require_once 'footer_content.php' ?>