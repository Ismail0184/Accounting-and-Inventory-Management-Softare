<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='MAN Checked';

$now = time();
$unique = 'id';
$unique_field = 'MAN_ID';
$table = "MAN_master";
$table_details = "MAN_details";
$unique_details = "m_id";

$page = 'QC_MAN_checked.php';
$re_page = 'Incoming_Material_Received.php';
$ji_date = date('Y-m-d');
$crud = new crud($table);
$$unique = $_GET[$unique];
$targeturl = "<meta http-equiv='refresh' content='0;$page'>";
$masterDATA = find_all_field('purchase_return_master', '', 'id=' . $_GET[$unique]);


if(isset($_POST['returned']))
{   $up_master="UPDATE ".$table." SET status='RETURNED' where ".$unique."=".$$unique."";
    $update_table_master=mysqli_query($conn, $up_master);
    $up_details="UPDATE ".$table_details." SET status='RETURNED' where ".$unique_details."=".$$unique."";
    $update_table_details=mysqli_query($conn, $up_details);
    unset($_POST);
    unset($$unique);
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

if (prevent_multi_submit()) {



//for Delete..................................
    if (isset($_POST['Deleted'])) {
        $crud = new crud($table_details);
        $condition = $unique_details . "=" . $$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition = $unique . "=" . $$unique;
        $crud->delete($condition);
        $dc_delete = 'dc_documents/' . "$_GET[$unique]" . '_' . 'dc' . '.pdf';
        unlink($dc_delete);
        $vc_delete = 'vc_documents/' . "$_GET[$unique]" . '_' . 'vc' . '.pdf';
        unlink($vc_delete);

        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

//for modify PS information ...........................
if(isset($_POST['checked']))
{   $up_master="UPDATE ".$table." SET status='CHECKED',check_by='".$_SESSION['userid']."'  where ".$unique."=".$$unique."";
    $update_table_master=mysqli_query($conn, $up_master);
    $up_details="UPDATE ".$table_details." SET status='CHECKED' where ".$unique_details."=".$$unique."";
    $update_table_details=mysqli_query($conn, $up_details);
    unset($_POST);
    unset($$unique);
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

$results = "Select srd.*,i.* from " . $table_details . " srd, item_info i  where
 srd.item_id=i.item_id and 
 srd." . $unique_details . "=" . $$unique . " order by srd.id";
$query = mysqli_query($conn, $results);




$resultss = "Select m.*,m.status as man_status,w.*,u.*,v.*
from 
" . $table . " m,
warehouse w,
users u,
vendor v

 where
  m.entry_by=u.user_id and 
 w.warehouse_id=m.warehouse_id and  
 v.vendor_id=m.vendor_code and 
 m.status='UNCHECKED' order by m." . $unique . " DESC ";
    $pquery = mysqli_query($conn, $resultss);

$resu=mysqli_query($conn, "Select d.*,i.* from 
                                             ".$table_details." d,item_info i 
                                            where 
                                            d.".$unique_details."='$_GET[$unique]' and d.item_id=i.item_id");
											

?>


<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 280,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>



<?php if($_GET[$unique]) { ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">

                            <div class="x_content">
                                    <form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                        <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                                            <tr style="height:30px; background-color: bisque">
                                                <th style="text-align:center; width:2%; vertical-align: middle">S/N</th>
                                                <th style="text-align:center; vertical-align: middle; width: 10%">Code</th>
                                                <th style="text-align:center; vertical-align: middle; width: 10%">Custom Code</th>
                                                <th style="text-align:center; vertical-align: middle">Material Description</th>
                                                <th style="text-align:center; vertical-align: middle">Unit</th>
                                                <th style="text-align:center; vertical-align: middle">Qty</th>
                                                <th style="text-align:center; vertical-align: middle">MFG</th>
                                                <th style="text-align:center; vertical-align: middle">No of Pack	</th>
                                                
                                                <th style="text-align:center; vertical-align: middlel; background-color:#F90">Last MAN 
                                                <br>Date	</th>
                                                <th style="text-align:center; vertical-align: middle; background-color:#F90">Qty	</th>
                                                <!--th style="text-align:center; vertical-align: middle">Inspection<br />Add</th-->
                                            </tr>
                                            <?php while($MANdetrow=mysqli_fetch_array($resu)){
											
												$query_for_last_MAN=mysqli_query($conn, "Select * from MAN_details where item_id=".$MANdetrow[item_id]." order by item_id desc limit 1");
												$last_row=mysqli_fetch_object($query_for_last_MAN);
												 ; ?>
                                                <tr style="background-color:#FFF">
                                                    <td style="width:2%; text-align:center"><?=$j=$j+1;?></td>
                                                    <td style=" text-align:center"><?php echo $MANdetrow[item_id]; ?></td>
                                                    <td style="width:5%; text-align:center"><?=$MANdetrow[finish_goods_code];?></td>
                                                    <td style="text-align:left"><?=$MANdetrow[item_name];?></td>
                                                    <td style="width:5%; text-align:center"><?=$MANdetrow[unit_name];?></td>
                                                    <td style="width:8%; text-align:right"><?php echo $MANdetrow[qty]; ?></td>
                                                    <td style="width:15%; text-align:right"><?php echo $MANdetrow[mfg]; ?></td>
                                                    <td style="width:10%; text-align:right"><?php echo $MANdetrow[no_of_pack]; ?></td>                                                    <td style="width:12%; text-align:right"><?=$last_row->man_date; ?></td>
                                                    <td style="width:10%; text-align:right"><?=$last_row->qty; ?></td>

                                                    <!--td align="center" style="width:10%">
                                                        <?php $dones = getSVALUE('QC_Inspection_Work_Sheet_master','COUNT(item_id)','where '.$unique.'="'.$_GET[man_id].'" and item_id='.$MANdetrow[item_id]); if($dones>0){ ?>
                                                            <img src="done.png" style="margin-left:10px" height="25" width="25" />
                                                        <?php } else { ?>
                                                            <a href="Inspection_Work_Sheet.php?manid=<?php echo $MANdetrow[MAN_ID]; ?>&item_id=<?php echo $MANdetrow[item_id]; ?>&t_id=<?php echo $MANdetrow[id]; ?>" style="text-decoration:none"><img src="add.png" style="margin-left:10px" height="25" width="25" /></a>
                                                        <?php }?>
                                                        </a></td-->
                                                </tr>
                                                <?php
                                                $tqty=$tqty+$MANdetrow[qty];
                                                $tamount=$tqty+$MANdetrow[amount];
                                            } ?>

                                            <tr><td colspan="5">Total</td>
                                                <td style="text-align:right"><?php echo $tqty; ?></td>
                                                <td style="text-align:right"></td><td style="text-align:right"></td>
                                                <td></td><td></td>
                                            </tr>
                                        </table>

                                        <?php
                                        $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                                        if($GET_status=='UNCHECKED'){  ?>
                                            <p>
                                                <button style="float: left; font-size: 12px; " type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Returned the MAN</button>
                                                <button style="float: right;font-size: 12px; " type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm?");'>Checked & Forward</button>
                                            </p>
                                        <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This MAN has been checked by QC !!</i></h6>';}?>
                                    </form>
                            </div></div></div>



                                <?php } else { ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title; ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 2%; vertical-align: middle">#</th>
                                            <th style="vertical-align: middle">MAN ID</th>
                                            <th style="vertical-align: middle">MAN NO</th>
                                            <th style="width:8%;vertical-align: middle">MAN Date</th>
                                            <th style="vertical-align: middle">Warehouse</th>
                                            <th style="vertical-align: middle">Vendor Name</th>
                                            <th style="vertical-align: middle">Remarks</th>
                                            <th style="vertical-align: middle">Delivary<br>Challan</th>
                                            <th style="vertical-align: middle">VAT<br>Challan</th>
                                            <th style="text-align: center;vertical-align: middle">Entry By</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($rows=mysqli_fetch_array($pquery)){ ?>
                                            <tr style="font-size:11px; cursor: pointer">
                                                <th style="text-align:center" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$i=$i+1;;?></th>
                                                <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[id];?></a></td>
                                                <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[MAN_ID];?></a></td>
                                                <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[man_date]; ?></td>
                                                <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[warehouse_name];?></td>
                                                <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[vendor_name];?></td>
                                                <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[remarks];?></td>
                                                <td><a href="dc_documents/<?=$rows[$unique].'_'.'dc'.'.pdf';?>" target="_blank" style="color:#06F"><u><strong><?=$rows[delivary_challan];?></strong></u></a></td>
                                                <td style="text-align:left"><a href="vc_documents/<?=$rows[$unique].'_'.'vc'.'.pdf';?>" target="_blank" style="color:#06F"><u><strong><?=$rows[VAT_challan];?></strong></u></a></td>
                                                <td style="text-align:center" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[fname];?></td>
                                            </tr>
                                        <?php } ?></tbody></table>
                            </div></div></div>
<?php } ?>

<?=$html->footer_content();mysqli_close($conn);?>

