<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='id';
$unique_field='MAN_ID';
$table="MAN_master";
$table_details="MAN_details";
$unique_details="m_id";



$page='MAN_report.php';
$re_page='Incoming_Material_Received.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$masterDATA=find_all_field('purchase_return_master','','id='.$_GET[$unique] );
if(prevent_multi_submit()){

    // for re-processing data..................................

    if(isset($_POST['reprocess']))
    {   $_POST['status']='MANUAL';
        $crud->update($table);
        $_SESSION['m_id']=$_GET[$unique];
        $_SESSION['initiate_man_documents']=find_a_field(''.$table.'','MAN_ID',''.$unique.'='.$_GET[$unique].'');
        $type=1;
        echo "<script>self.opener.location = '$re_page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['Deleted']))
    {
        $crud = new crud($table_details);
        $condition =$unique_details."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);

        $dc_delete = 'dc_documents/'."$_GET[$unique]".'_'.'dc'.'.pdf';
        unlink($dc_delete);


        $vc_delete = 'vc_documents/'."$_GET[$unique]".'_'.'vc'.'.pdf';
        unlink($vc_delete);

        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

$results="Select srd.*,i.* from ".$table_details." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique_details."=".$$unique." order by srd.id";
$query=mysqli_query($conn, $results);

$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));



if(isset($_POST[viewreport])){
    if($_POST['vendor_code']>0) 			 $vendor_code=$_POST['vendor_code'];
    if(isset($vendor_code))				{$vendor_code_CON=' and m.vendor_code='.$vendor_code;}

    if($_POST['warehouse_id']>0) 			 $warehouse_id=$_POST['warehouse_id'];
    if(isset($warehouse_id))				{$warehouse_id_CON=' and m.warehouse_id='.$warehouse_id;}

    $resultss="Select m.*,m.status as man_status,w.*,u.*,v.*
from 
".$table." m,
warehouse w,
users u,
vendor v

 where
  m.entry_by=u.user_id and 
 w.warehouse_id=m.warehouse_id and  
 v.vendor_id=m.vendor_code and 
 m.man_date between '$from_date' and '$to_date' ".$vendor_code_CON.$warehouse_id_CON." order by m.".$unique." DESC ";
    $pquery=mysqli_query($conn, $resultss);
}

?>


<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content_without_menu.php'; ?>




    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <table align="center" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>SL</th>
                            <th>Code</th>
                            <th>Finish Goods</th>
                            <th style="width:5%; text-align:center">UOM</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Total Qty</th>
                            <th style="text-align:center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php while($row=mysqli_fetch_array($query)){ ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$i;?></td>
                                <td style="vertical-align:middle"><?=$row[finish_goods_code];?></td>
                                <td style="vertical-align:middle;"><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td align="center" style=" text-align:right"><?=$row[rate]; ?></td>
                                <td align="center" style=" text-align:center"><?=number_format($row[qty],2); ?></td>
                                <td align="center" style="text-align:right"><?=number_format($row[amount],2);?></td>

                            </tr>
                            <?php $total_amount=$total_amount+$row[amount];}?>
                        </tbody>                       
                    </table>
                    <?php mysqli_close($conn); ?></form>
            </div>
        </div>
    </div>
<?php require_once 'footer_content.php' ?>