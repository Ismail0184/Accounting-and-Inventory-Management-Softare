<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();

$unique='pi_no';
$table="production_issue_master";
$table_details="production_issue_detail";
$page='prodcution_transfer_checked.php';
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
        echo "<script>self.opener.location = 'production_transfer2.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }


//for Delete..................................
    if(isset($_POST['deleted']))
    {

        $crud = new crud($table_details);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($_POST[viewreport])){
    $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
    $to_date=date('Y-m-d' , strtotime($_POST[t_date]));
    $resultss="Select m.*,w.warehouse_name as transfer_from,u.*,w2.warehouse_name as transfer_to
from 
".$table." m,
warehouse w,
users u,
warehouse w2

 where
  m.entry_by=u.user_id and 
 w.warehouse_id=m.warehouse_from and  
 w2.warehouse_id=m.warehouse_to and 
 m.pi_date between '$from_date' and '$to_date' and 
  m.verifi_status='UNCHECKED' order by m.".$unique." DESC ";
    $pquery=mysqli_query($conn, $resultss);
}
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 280,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>



<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th>SL</th>
                            <th>STO NO</th>
                            <th>Item Description</th>
                            <th style="text-align: center">Unit</th>
                            <th style="text-align:center">Batch</th>
                            <th style="text-align:center">Rate</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $rs="Select d.*,i.*
from 
".$table_details." d,
item_info i

 where
 i.item_id=d.item_id  and 
 d.".$unique."=".$$unique."
 order by d.id";
                        $pdetails=mysqli_query($conn, $rs);
                        while($uncheckrow=mysqli_fetch_array($pdetails)){
                            ?>

                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$js=$js+1;?></td>
                                <td><?=$uncheckrow[custom_pi_no];?></td>
                                <td style="text-align:left"><?=$uncheckrow[item_name];?></td>
                                <td style="text-align:left"><?=$uncheckrow[unit_name];?></td>
                                <td style="width:10%; text-align:left"><?=$uncheckrow[batch];?></td>
                                <td style="width:10%; text-align:left"><?=$uncheckrow[rate];?></td>
                                <td align="center" style="width:15%; text-align:center"><?=number_format($ttotal=$uncheckrow[total_unit]/$uncheckrow[pack_size],0);?></td>
                                <td style="width:10%; text-align:left"><?=$uncheckrow[amount];?></td>
                            </tr>
                            <?php  $amountqty=$amountqty+$ttotal;  } ?>
                        <tr style="font-weight: bold"><td colspan="6" style="text-align: right">Total = </td>
                            <td style="text-align: center"><?=number_format($amountqty)?></td>
                            <td style="text-align: center"><?=number_format($amountqty)?></td>
                        </tr>
                        </tbody></table>

                    <?php
                    $GET_status=find_a_field(''.$table.'','verifi_status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED'){  ?>
                        <p>
                            <button style="float: left" type="submit" name="returned" id="returned" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right;" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm?");'>Checked & Forward</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Stock Transfer has been checked !!</i></h6>';}?>

                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="text" id="f_date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[viewreport])) { echo $_POST[f_date]; } else { ?> <?=date('m')?>/01/<?=date('Y'); }?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="text" id="t_date" style="width:150px;font-size: 11px; height: 25px"  value="<?=$_POST[t_date]?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Stock Transfer</button></td>
            </tr></table>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 2%">#</th>
                            <th style="">STO ID</th>
                            <th style="">STO NO</th>
                            <th style="">STO Date</th>
                            <th style="">Warehouse / CMU From</th>
                            <th style="">Warehouse To</th>
                            <th>Remarks</th>
                            <th style="">Entry By</th>
                            <th style="">Entry At</th>
                            <th style="text-align: center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($rows=mysqli_fetch_array($pquery)){
                            $i=$i+1;
                            ?>
                            <tr style="font-size:11px; cursor: pointer" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)">
                                <th style="text-align:center"><?php echo $i; ?></th>
                                <td><?php echo $rows[pi_no]; ?></td>
                                <td><a href="<?php echo $link; ?>" target="_blank"><?php if($rows[custom_pi_no]!=='') echo $rows[custom_pi_no]; else echo$rows[pi_no]; ?></a></td>
                                <td><?php echo $rows[pi_date]; ?></td>
                                <td><?=$rows[transfer_from];?></td>
                                <td><?=$rows[transfer_to];?></td>
                                <td><?=$rows[nooffg];?></td>
                                <td><?=$rows[fname];?></td>
                                <td style="text-align:left"><?=$rows[entry_at];?></td>
                                <td style="text-align:center"><?=$rows[verifi_status];?></td>
                            </tr>
                        <?php } ?></tbody></table>

                </div></div></div></form>
<?php } ?>

<?php require_once 'footer_content.php' ?>