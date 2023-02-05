 <?php
require_once 'support_file.php';
$title="Sales Data Checking";

 $page="sales_data_checking.php";
 $unique="item_id";

 if($_POST['f_date']){
     $f_date =$_POST[f_date];
     $fdate=date('Y-m-d' , strtotime($f_date));}

 if($_POST['t_date']){
     $t_date =$_POST[t_date];
     $tdate=date('Y-m-d' , strtotime($t_date));}

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

 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title><?php echo $userRow[proj_name]; ?> | <?php echo $title; ?></title>
     <!-- Select2 -->
     <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
     <!-- Bootstrap -->
     <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Font Awesome -->
     <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
     <!-- NProgress -->
     <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
     <!-- bootstrap-daterangepicker -->
     <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
     <!-- Custom Theme Style -->
     <link href="../build/css/custom.min.css" rel="stylesheet">
     <!-- jQuery custom content scroller -->
     <link href="../vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>

     <script type="text/javascript">
         function DoNavPOPUP(lk)
         {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=730,height=600,left = 383,top = -1");}
     </script>
 </head>
<?php require_once 'body_content.php'; ?>

 <!-- input section-->
 <div style="margin-left: 25%" class="col-md-6 col-sm-12 col-xs-12">
     <div class="x_panel">
         <div class="x_content">
             <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">From Date<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="f_date" style="width:100%"  required   name="f_date" value="<?=$_POST[f_date]?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>

                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">To Date<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="t_date" style="width:100%"  required   name="t_date" value="<?=$_POST[t_date]?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>


                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Warehouse<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                 <select class="select2_single form-control" name="warehouse_id" id="warehouse_id" style="width:100%;">
                     <option></option>

                     <?php
$res=mysql_query("SELECT * FROM  warehouse WHERE 1");
while($data=mysql_fetch_object($res)){?>

                    <option value="<?=$data->warehouse_id?>" <?php if($_POST[warehouse_id]==$data->warehouse_id){ echo 'selected';} else {} ?>><?=$data->warehouse_name?></option>
                    <?php } ?>
                 </select>
                     </div></div>

                 <div class="form-group" style="margin-left:35%">
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <button type="submit" name="submitit" id="submitit"  class="btn btn-primary">View Report</button>
                     </div></div>
             </form>
         </div>
     </div>
 </div>






                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                            <?php
                            if(isset($_POST[submitit])){
                              ?>
                                <table  class="table table-striped table-bordered" style="width:100%; font-size: 12px">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Code</th>
                                        <th>Item Code</th>
                                        <th>Item Description</th>
                                        <th>Unit</th>
                                        <th>Pack Size</th>
                                        <th style="text-align: center">DO QTY</th>
                                        <th style="text-align: center">Challan QTY</th>
                                        <th style="text-align: center">Stock QTY</th>
                                    </tr>
                                    </thead>
                                    <tbody>
<?php
if($_POST[warehouse_id]) {$depot_con=' and j.warehouse_id="'.$_POST[warehouse_id].'"';}
if($_POST[warehouse_id]) {$depot_conDO=' and sdm.depot_id="'.$_POST[warehouse_id].'"';}


$res="SELECT 
sdm.do_no,
sdm.do_date,
sdm.depot_id,
SUM(sdd.total_unit) as doqty,
i.*,
SUM(sdc.total_unit) as challanqty

FROM 

sale_do_details sdd,
sale_do_chalan sdc,
sale_do_master sdm

WHERE 
sdm.do_no=sdd.do_no and 
sdd.do_no=sdc.do_no and 
sdd.item_id=i.item_id and  
i.item_id not in ('1096000100010312','1096000100010313') and 
i.product_nature in ('Salable','Both') and  
sdm.do_date between '".$fdate."' and '".$tdate."'".$depot_conDO." 
GROUP BY i.item_id
ORDER BY 
i.serial";
$query=mysql_query($res);
while($data=mysql_fetch_object($query)){

//$doqty=find_a_field('sale_do_details','SUM(total_unit)','item_id="'.$data->item_id.'" and do_date between "'.$fdate.'" and "'.$tdate.'"'.$depot_conDO.'');
//$challanqty=find_a_field('sale_do_chalan','SUM(total_unit)','item_id="'.$data->item_id.'" and do_date between "'.$fdate.'" and "'.$tdate.'"'.$depot_conDO.'');
?>

    <tr onclick="DoNavPOPUP(<?=$data->item_id;?>)" style="<?php if($doqty!=$data->Stock_qty){ echo 'Background-color:red; color:white';} ?>"  >
<td><?=$i=$i+1;?></td>
        <td><?=$data->item_id;?></td>
        <td><?=$data->finish_goods_code;?></td>
        <td><?=$data->item_name;?></td>
        <td><?=$data->unit_name;?></td>
        <td><?=$data->pack_size;?></td>
        <td style="text-align: right"><?=$doqty;?></td>
        <td  style="text-align: right;<?php if($doqty!==$challanqty) { echo 'background-color:red; color:white';} else echo ''; ?>"><?=$challanqty?></td>
        <td style="text-align: right"><?=number_format($data->Stock_qty,2);?></td>


    </tr>
<?php } ?>

                                    </tbody>
                                </table>
                                <?php } else {echo '<h6 align="center" style="color: red">** Please Select Date and Click on View Report **</h6>';} ?>
                            </div></div></div>


                
        
<?php require_once 'footer_content.php' ?>