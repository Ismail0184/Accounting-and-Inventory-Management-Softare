 <?php
require_once 'support_file.php';
$title="Get IMS Data";
$now=time();
$unique='ims_no';
$unique_field='name';
$table="ims_master";
$page="get_ims_data_manual.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

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
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}

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


 <?php if(isset($_GET[$unique])){ ?>
                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <?require_once 'support_html.php';?>
                                    <table style="width:100%;font-size: 12px"  class="table table-striped table-bordered">
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>IMS Qty</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                    </tr>

                                    <?php
                                    $res=mysql_query("select * from item_info where sub_group_id in ('200010000') and exim_status not in ('Export') and brand_category not in ('Rice') and status in ('Active') order by serial");
                                    while($item=mysql_fetch_array($res)){
                                    $imsdetails=find_all_field('ims_details','','item_id="'.$item[item_id].'" and ims_no='.$_GET[$unique] );
                                    $id=$item[item_id];
                                    ?>
                                    <tr>
                                    <td><?=$i=$i+1;?></td>
                                    <td><?=$item[item_name];?></td>
                                    <td style="text-align: center"><?=$imsdetails->total_unit_ims;?></td>
                                    <td style="text-align: right"><?=$imsdetails->unit_price;?></td>
                                    <td style="text-align: right"><?=$imsdetails->total_amt_ims;?></td>
                                    </tr>
                                    <?php $totalIMS=$totalIMS+$imsdetails->total_amt_ims;} ?>
                                        <tr>
                                        <td colspan="4" align="right"><strong>IMS TOTAL = </strong></td>
                                            <td align="right"><strong><?=number_format($totalIMS,2);?></strong></td>
                                        </tr>
                                    </table>




                                        <?php
                                        $GET_status=find_a_field($table,'status','ims_no='.$_GET[$unique]);
                                        if($GET_status!=='COMPLETED'){  ?>
                                            <p>
                                             <button style="float: left" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>DELETED</button>
                                             <button style="float: right" type="submit" name="modify" id="modify" class="btn btn-success" onclick='return window.confirm("Are you confirm?");'>CHECKED & FINISHED</button>
                                             <? } else {echo '<h5 style="text-align: center; color: black; font-style: italic; background-color: red">This IMS Data has been Verified!!</h5>';}?>
                                           </p>



                                </form>
                                </div>
                                </div>
                                </div>
                            <?php } ?>

                    <?php if(!isset($_GET[$unique])){ ?>
                    <!-------------------list view ------------------------->
 <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">

                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <table style="width:100%; font-size: 12px" class="table table-striped table-bordered">
                                    <thead><tr>
                                        <th>#</th>
                                        <th>IMS No</th>
                                        <th>IMS Date</th>
                                        <th>SO Name</th>
                                        <th>TSM Name</th>
                                        <th style="text-align: center">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

									$ims_date=find_a_field('ims_date','ims_date','1');

                                    $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                                    $to_date=date('Y-m-d' , strtotime($_POST[t_date]));


                                    $res=mysql_query("SELECT m.*,
                                    p.PBI_NAME,
                                    p.PBI_ID,
                                    p.PBI_ID_UNIQUE,
                                    (SELECT concat(PBI_ID_UNIQUE ,' # ', PBI_NAME) from personnel_basic_info where PBI_ID=p.tsm) as tsm
                                    
                                    
                                    FROM 
                                    
                                    ims_master m,
                                    personnel_basic_info p
                                    where 
                                    
                                 
                                    m.PBI_ID=p.PBI_ID and 
                                    m.status in ('MANUAL')
                                    group by m.ims_no order by m.ims_no desc ");
                                    while($data=mysql_fetch_object($res)){
                                    ?>
                                    <tr  onclick="DoNavPOPUP('<?=$data->ims_no?>', 'TEST!?', 900, 600)">
                                      <td><?=$i=$i+1;?></td>
                                      <td><?=$data->ims_no;?></td>
                                      <td><?=$data->order_date;?></td>
                                      <td><?=$data->PBI_ID;?> # <?=$data->PBI_ID_UNIQUE;?> # <?=$data->PBI_NAME;?></td>
                                      <td><?=$data->tsm;?></td>
                                      <td style="text-align: right"><?=$data->status;?></td>

                                    </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div></div></form>
                    <!-------------------End of  List View --------------------->
                    <?php } ?>
                    <!---page content----->


                
        
<?php require_once 'footer_content.php' ?>