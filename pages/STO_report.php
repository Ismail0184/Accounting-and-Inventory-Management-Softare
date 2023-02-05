<?php
 require_once 'support_file.php';
 $title='STO Report';
$now=time();
$entry_at=date('Y-m-d H:s:i');
$unique='pi_no';
$unique_field='name';
$table="production_issue_master";
$table_details="production_issue_detail";

$page='page.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        //echo "<script>self.opener.location = 'QC_sales_return_view.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }



    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $rs="Select d.*,i.*
from 
production_floor_receive_detail d,
item_info i

 where
 i.item_id=d.item_id  and 
 d.".$unique."=".$$unique."
 order by d.id";
        $pdetails=mysqli_query($conn, $rs);
        while($uncheckrow=mysqli_fetch_array($pdetails)){
            $_POST['ji_date'] = date('Y-m-d');
            $_POST['item_id'] = $uncheckrow[item_id];
            $_POST['warehouse_id'] = $uncheckrow[warehouse_from];
            $_POST['item_in'] = $uncheckrow[total_unit];
            $_POST['item_price'] = $uncheckrow[unit_price];
            $_POST['total_amt'] = $uncheckrow[total_amt];
            $_POST['lot_number'] = $uncheckrow[lot];
            $_POST['batch'] = $uncheckrow[batch];
            $_POST['tr_from'] = 'Production';
            $_POST['custom_no'] = $uncheckrow[custom_pr_no];
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['entry_at'] = $entry_at;
            $_POST['sr_no'] = $uncheckrow[id];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();
        }
        $up_master="UPDATE ".$table." SET status='CHECKED',qc_by=".$_SESSION[userid].",qc_at='$entry_at' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_deatils." SET status='CHECKED' where ".$unique."=".$unique."";
        $update_table_details=mysqli_query($conn, $up_details);
        $type=1;
        unset($_POST);
        echo "<script>window.close(); </script>";
    }


//for Delete..................................
    if(isset($_POST['deleted']))
    {

        $crud = new crud($table_deatils);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);

        $crud = new crud($production_table_issue_master);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);

        $crud = new crud($production_table_issue_detail);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $deleted_journal_item="Delete from ".$journal_item." where sr_no=".$$unique." and tr_from='Consumption'";
        $query=mysqli_query($conn, $deleted_journal_item);

        $deleted_journal="Delete from ".$journal_accounts." where tr_no=".$$unique." and tr_from in ('Consumption','Production')";
        $queryj=mysqli_query($conn, $deleted_journal);



        unset($_SESSION['ps_id']);
        unset($_SESSION['pi_id']);
        unset($_SESSION['initiate_daily_production']);
        unset($_POST);
        unset($$unique);

        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}


?>

<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 280,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
                    <table align="center" style="width: 50%;">
                        <tr><td>
                                <input type="text" id="f_date" style="width:150px; font-size: 11px; height: 25px"  value="<?=date('m')?>/01/<?=date('Y')?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                            <td style="width:10px; text-align:center"> -</td>
                            <td><input type="text" id="t_date" style="width:150px;font-size: 11px; height: 25px"  value="<?=$_POST[t_date]?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                            <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View STO</button></td>
                        </tr></table>

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                  <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                   <thead>
                     <tr>
                     <th style="width: 2%">#</th>
                     <th>STO NO</th>
                     <th>Date</th>
                     <th>Transfer From</th>
                     <th>Transfer To</th>
                     <th>Remarks</th>
                     <th>Transporter</th>
                     <th>Track</th>
                     <th>Driver Info</th>
                     </tr>
                     </thead>
                      <tbody>

<?php
$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));
if(isset($_POST[viewreport])){
$resultss=mysqli_query($conn,"Select * from ".$table." where pi_date between '$from_date' and '$to_date' order by ".$unique." DESC ");
}
while ($rows=mysqli_fetch_array($resultss)){
	$i=$i+1;

$link='STO_print_view.php?custom_pi_no='.$rows[custom_pi_no].'&pino='.$rows[pi_no];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><a href="<?php echo $link; ?>" target="_blank"><?php echo $rows[custom_pi_no]; ?></a></td>
                        <td style="width:10%"><?php echo $rows[pi_date]; ?></td>
                        <td><a href="<?php echo $link; ?>" target="_blank"><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_from']."'");?></a></td>
                        <td><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_to']."'");?></td>
                        <td><?=$rows[remarks];?></td>
                        <td style="text-align:left"><?=$companyname=getSVALUE("vendor", "vendor_name", "where vendor_id='".$rows['transporter']."'");?></td>
                        <td style="text-align:left"><?=$rows[track_no];?></td>
                        <td style="text-align:left"><?=$rows[driver_info];?></td>
                        </tr>
<?php } ?></tbody></table>

       </div></div></div>

<?php require_once 'footer_content.php' ?>
