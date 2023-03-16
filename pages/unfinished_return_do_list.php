 <?php
require_once 'support_file.php';
$title="Unfinished & Return DO List";

$now=time();
$unique='do_no';
$unique_field='do_date';
$condition_field='status';
$table_master="sale_do_master";
$table_detail='sale_do_details';
$unique_detail='id';
$page="unfinished_return_do_list.php";
$crud      =new crud($table_master);
$$unique_master = $_GET[$unique];


// for delete data..................................
if($_GET[do_no]>0){
 if(isset($_POST['deleteall']))
 {   $crud   = new crud($table_master);
     $condition=$unique."=".$$unique_master;
     $crud->delete($condition);
     $crud   = new crud($table_detail);
     $crud->delete_all($condition);
     unset($$unique_master);
     unset($_SESSION[$unique_master]);
     $type=1;
     echo "<script>self.opener.location = 'unfinished_return_do_list.php'; self.blur(); </script>";
     echo "<script>window.close(); </script>";
 }


 // for return data..................................
     if(isset($_POST['initiate']))
     {   $_POST['status']='MANUAL';
         $crud->update($unique);
         $_SESSION[dealer_code_GET]=find_a_field('sale_do_master','dealer_code','do_no='.$_GET[do_no]);
         $_SESSION['old_do_find']=$_GET[$unique];
         $type=1;
         echo "<script>self.opener.location = 'do.php'; self.blur(); </script>";
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
         {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 250,top = -1");}
     </script>
     <style>
         input[type=text] {
             width: 100%;
             margin-top: 5px;
             margin-bottom: 5px;
         }
         select {

             margin-top: 5px;
             margin-bottom: 5px;
         }
     </style>
 </head>
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
                                <? 	$res='select '.$unique.','.$unique.' as DO_No,'.$unique_field.',status from '.$table_master.' where '.$condition_field.' in ("MANUAL","RETURNED") order by '.$unique;
                                echo $crud->link_report_popup($res,$link);?>
                                <?=paging(10);?>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->
     <?php } else { ?>

 <!-- input section-->
 <div class="col-md-12 col-sm-12 col-xs-12">
     <div class="x_panel">
         <div class="x_content">
             <form  method="post" name="cz" id="cz">
                 <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 12px">

                     <?php
                     $res="select 
m.*,
concat(d.dealer_custom_code,'- ',d.dealer_name_e) as dealer_name, 
d.dealer_code, 
d.account_code,
d.customer_type,
u.fname as entryby,
d.credit_limit


from 

sale_do_master m,
dealer_info d,
users u

where 
m.do_no='".$_GET[do_no]."' and 
m.dealer_code=d.dealer_code and 
u.user_id=m.entry_by and 
m.status in ('MANUAL','RETURNED')";
                     $query = mysql_query($res);
                     $dataMaster = mysql_fetch_object($query);

                     ?>


                     <tr>
                         <td style="width: 15%"><strong>DO :</strong></td>
                         <td style="text-align: left"><?=$_GET[do_no];?></td>
                         <td style="width: 15%"><strong>DO Date :</strong></td>
                         <td style="text-align: left"><?= $dataMaster->do_date;?></td>
                     </tr>

                     <tr>
                         <td><strong>Dealer Name : </strong></td><td style="text-align: left"><?=$dataMaster->dealer_name;?></td>
                         <td><strong>Dealer Type : </strong></td><td style="text-align: left"><?=$dataMaster->customer_type;?></td>
                     </tr>

                     <tr>
                         <td><strong>Account Balance : </strong></td><td style="text-align: left"><?=number_format($accountbalance=find_a_field('journal','SUM(cr_amt-dr_amt)','ledger_id='.$dataMaster->account_code),2);?> BDT</td>
                         <td><strong>Credit Limit : </strong></td><td style="text-align: left"><?=number_format($dataMaster->credit_limit,2);?> BDT</td>
                     </tr>

                     <tr>
                         <td><strong>Entry By : </strong></td><td style="text-align: left"><?=$dataMaster->entryby;?></td>
                         <td><strong>Entry At : </strong></td><td style="text-align: left"><?=$dataMaster->entry_at;?></td>
                     </tr>

                 </table>







                 <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 12px">
                     <tbody>
                     <tr style="height:30px; text-align: center">
                         <th>SL</th>
                         <th>Item Code</th>
                         <th>Item Description</th>
                         <th>Qty</th>
                         <th>Rate</th>
                         <th>Amount</th>

                     </tr>





                     <?
                     $enat=date('Y-m-d h:s:i');
                     $enby=$_SESSION['userid'];
                     $res="select 
m.do_no,
m.do_section,
m.do_date,
m.depot_id,
m.commission,
m.commission_amount,
concat(d.dealer_custom_code,'- ',d.dealer_name_e) as dealer_name, 
d.credit_limit_time as limittime,
d.dealer_code, 
d.account_code, 
dt.item_id,
dt.total_unit as QTY,
dt.unit_price as PriceGET,
dt.total_amt as total_amt,
dt.id as tr_no,
m.received_amt as RCV_AMT,
i.item_name,
i.finish_goods_code as fgcode

from 

sale_do_master m,
dealer_info d ,
sale_do_details dt,
item_info i



where 
m.do_no='".$_GET[do_no]."' and 
m.dealer_code=d.dealer_code and  
m.do_no=dt.do_no and 
dt.item_id=i.item_id and
m.status in ('MANUAL','RETURNED')
 order by dt.id";
                     $query = mysql_query($res);
                     while($data = mysql_fetch_object($query))

                     { ?>

                         <tr><td style="text-align:center; width: 1%">&nbsp;<?=$i=$i+1;?></td>
                             <td style="text-align:left" width="0">&nbsp;<?=$data->fgcode;?></td>
                             <td style="text-align:left" width="0">&nbsp;<?=$data->item_name; if($data->total_amt==0){ echo '<font style="color: red; margin-left: 5px">[Free]</font>';} ?></td>
                             <td style="text-align:right" width="0"><?=$data->QTY?> </td>
                             <td style="text-align:right" width="0"><?=$data->PriceGET?> </td>
                             <td style="text-align:right" width="0"><?=number_format($data->total_amt,2)?> </td> </tr>

                         <?
                         $warehouseid=$data->depot_id;
                         $totalamount=$totalamount+$data->total_amt;
                         $commissionamount=$data->commission_amount;
                     }




                     if(isset($_POST['confirm']))

                     {


                         echo "<script>self.opener.location = 'unchecked_do_list.php'; self.blur(); </script>";
                         echo "<script>window.close(); </script>";
                     }  ?>

                     <tr style="font-weight: bold">
                         <td colspan="5" style="text-align: right">Total</td>
                         <td style="text-align:right"><?=number_format($totalamount,2);?></td>
                     </tr>

                     <?php
                     $commission=find_a_field('sale_do_master','commission','do_no='.$_GET[do_no]);
                     if($commission>0) { ?>
                         <tr style="font-weight: bold">
                         <td colspan="5" style="text-align: right">Commission</td>
                         <td style="text-align:right"><?=number_format($commissionamount,2);?></td>
                         </tr><?php } ?>
                     <tr style="font-weight: bold">
                         <td colspan="5" style="text-align: right">Total Receivable Amount</td>
                         <td style="text-align:right"><?=number_format($totalamount-$commissionamount,2);?></td>
                     </tr>
                     </tbody>
                 </table>


                <?php
                $data_status=find_a_field('sale_do_master','status','do_no="'.$_GET[do_no].'"');
                if($data_status=='MANUAL' ||  $data_status=='RETURNED'){

                ?>

                 <table align="center">
                     <tr style="background-color: transparent">

                         <td><button type="submit" name="initiate" id="initiate" class="btn btn-primary" onclick='return window.confirm("You want to delete the DO?");'>Re-processing & Modify</button></td></td>
                         <td><button type="submit" name="deleteall" id="deleteall" class="btn btn-danger" onclick='return window.confirm("You want to delete the DO?");'>Deleted</button></td></td>
                     </tr>

                 </table>
                <?php } else { echo '<h4 align="center" style="color: red">Sorry! This Demand Order Has Been Finished!!</h4>';} ?>

             </form>
         </div></div></div>
<?php } ?>


 <?php require_once 'footer_content.php' ?>