<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='MAN Checked';
$now = time();
$unique = 'id';
$unique_field = 'MAN_ID';
$table = "MAN_master";
$table_details = "MAN_details";
$unique_details = "m_id";

$page = 'grn_check_and_verify_MAN.php';
$re_page = 'Incoming_Material_Received.php';
$ji_date = date('Y-m-d');
$crud = new crud($table);
$$unique = $_GET[$unique];
$targeturl = "<meta http-equiv='refresh' content='0;$page'>";
$masterDATA = find_all_field('MAN_master', '', 'id=' . $_GET[$unique]);
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
  $resu=mysqli_query($conn, "Select d.*,i.* from
                                             ".$table_details." d,item_info i
                                            where
                                            d.".$unique_details."='$_GET[$unique]' and d.item_id=i.item_id");
    while($MANdetrow=mysqli_fetch_array($resu)){
        $id=$MANdetrow[id];
        $po_no_up=$_POST['po_no'.$id];

	if(isset($_POST['editdata'.$id]))
    {  mysqli_query($conn, ("UPDATE ".$table_details." SET po_no='".$po_no_up."' where  id=".$id));
        unset($_POST);
    }

	if(isset($_POST['checked']))
{
    $up_details="UPDATE ".$table_details." SET status='VERIFIED',po_no='".$po_no_up."' where  m_id=".$_GET[id];
    $update_table_details=mysqli_query($conn, $up_details);}

	if(isset($_POST['checked'])){
    $del1=mysqli_query($conn,"UPDATE  purchase_master set MAN_ID='".$masterDATA->MAN_ID."',m_id='".$_GET[$unique]."' where po_no=".$po_no_up);
    $up_master=mysqli_query($conn, "UPDATE ".$table." SET status='VERIFIED' where id=".$_GET[$unique]);
    unset($_POST);
    unset($$unique);
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
	}}


$results = "Select srd.*,i.* from " . $table_details . " srd, item_info i  where
 srd.item_id=i.item_id and
 srd." . $unique_details . "=" . $$unique . " order by srd.id";
$query = mysqli_query($conn, $results);



if(isset($_POST[viewreport])) {
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
  m.man_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' order by m." . $unique . " DESC ";
    $pquery = mysqli_query($conn, $resultss);
} else {
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
 m.status='CHECKED' order by m." . $unique . " DESC ";
    $pquery = mysqli_query($conn, $resultss);
}
$resu=mysqli_query($conn, "Select d.*,i.* from
                                             ".$table_details." d,item_info i
                                            where
                                            d.".$unique_details."='$_GET[$unique]' and d.item_id=i.item_id");
$GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                                            ?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 280,top = -1");}
    </script>
    <?php if(isset($_GET[$unique])){
        require_once 'body_content_without_menu.php';
    } else {
        require_once 'body_content.php';
    } ?>

<?php if($_GET[$unique]) { ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <tr style="height:30px; background-color: bisque">
                            <th style="text-align:center; width:2%; vertical-align: middle">S/N</th>
                            <th style="text-align:center; vertical-align: middle; width: 10%">Code</th>
                            <th style="text-align:center; vertical-align: middle">Material Description</th>
                            <th style="text-align:center; vertical-align: middle">Unit</th>
                            <th style="text-align:center; vertical-align: middle">Qty</th>
                            <th style="text-align:center; vertical-align: middle">MFG</th>
                            <th style="text-align:center; vertical-align: middle">No of Pack	</th>
                            <th style="text-align:center; vertical-align: middle">PO</th>
                            <th style="text-align:center; vertical-align: middle"> Available Qty in PO</th>
                            <?php if($GET_status=='CHECKED'){  ?>
<th style="text-align:center; vertical-align: middle">Action</th><?php } ?>
                        </tr>
                        <?php while($MANdetrow=mysqli_fetch_array($resu)){?>
                            <tr style="background-color:#FFF">
                                <td style="width:2%; text-align:center; vertical-align:middle"><?=$j=$j+1;?></td>
                                <td style="width:5%; text-align:center; vertical-align:middle"><?=$MANdetrow[finish_goods_code];?></td>
                                <td style="text-align:left; vertical-align:middle"><?=$MANdetrow[item_name];?></td>
                                <td style="width:5%; text-align:center; vertical-align:middle"><?=$MANdetrow[unit_name];?></td>
                                <td style="width:8%; text-align:right; vertical-align:middle"><?=$MANdetrow[qty]; ?></td>
                                <td style="width:15%; text-align:right; vertical-align:middle"><?=$MANdetrow[mfg]; ?></td>
                                <td style="width:10%; text-align:right; vertical-align:middle"><?=$MANdetrow[no_of_pack]; ?></td>
                                <td style="width:10%; text-align:right; vertical-align:middle"><input type="text" required name="po_no<?=$MANdetrow[id];?>" value="<?=$MANdetrow[po_no];?>" style="width: 60px; text-align: center"></td>
                                <td style="width:10%; text-align:right; vertical-align:middle"><?=find_a_field('purchase_invoice','SUM(qty)','po_no='.$MANdetrow[po_no].' and item_id='.$MANdetrow[item_id].'')-find_a_field('purchase_receive','SUM(qty)','po_no='.$MANdetrow[po_no].' and item_id='.$MANdetrow[item_id].'')?></td>
                                <?php if($GET_status=='CHECKED'){  ?>
<td style="vertical-align:middle"><button type="submit" name="editdata<?=$MANdetrow[id];?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. , Are you sure you want to Update?");'><img src="refresh.png" style="width:25px;  height:25px"></button></td><?php } ?>
 </tr>
                            <?php  } ?></table>
                    <?php if($GET_status=='CHECKED'){  ?>
                        <p>
                            <input type="text" id="return_comments"  name="return_comments" class="form-control col-md-7 col-xs-12"  style="width:166px; font-size:11px; height:32px"  placeholder="return comments........" ><button style="float: left; font-size: 12px; " type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Returned the MAN</button>
                            <button style="float: right;font-size: 12px; " type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Forward to GRN</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This MAN is '.$GET_status.'!!</i></h6>';}?>
                </form>
            </div></div></div>



<?php } else { ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
    <table align="center" style="width: 50%;">
        <tr><td>
                <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
            <td style="width:10px; text-align:center"> -</td>
            <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
            <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View MAN</button></td>
        </tr></table>

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <table style="width:100%; font-size: 11px" id="datatable-buttons" class="table table-striped table-bordered">
                    <thead>
                    <tr style="background-color: bisque">
                        <th style="width: 2%; vertical-align: middle">#</th>
                        <th style="vertical-align: middle">MAN ID</th>
                        <!--th style="vertical-align: middle">MAN NO</th-->
                        <th style="width:8%;vertical-align: middle">MAN Date</th>
                        <th style="vertical-align: middle">Warehouse</th>
                        <th style="vertical-align: middle">Vendor Name</th>
                        <th style="vertical-align: middle">Remarks</th>
                        <th style="vertical-align: middle">Delivary<br>Challan</th>
                        <th style="vertical-align: middle">VAT<br>Challan</th>
                        <th style="text-align: center;vertical-align: middle">Entry By</th>
                        <th style="text-align: center;vertical-align: middle">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($rows=mysqli_fetch_array($pquery)){ ?>
                        <tr style="font-size:11px; cursor: pointer">
                            <th style="text-align:center" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$i=$i+1;;?></th>
                            <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[id];?></a></td>
                            <!--td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[MAN_ID];?></a></td-->
                            <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[man_date]; ?></td>
                            <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[warehouse_name];?></td>
                            <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[vendor_name];?></td>
                            <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[remarks];?></td>
                            <td><a href="dc_documents/<?=$rows[$unique].'_'.'dc'.'.pdf';?>" target="_blank" style="color:#06F"><u><strong><?=$rows[delivary_challan];?></strong></u></a></td>
                            <td style="text-align:left"><a href="vc_documents/<?=$rows[$unique].'_'.'vc'.'.pdf';?>" target="_blank" style="color:#06F"><u><strong><?=$rows[VAT_challan];?></strong></u></a></td>
                            <td style="text-align:left" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[fname];?><br>at: <?=$rows[entry_at];?></td>
                            <td onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[man_status];?></a></td>
                        </tr>
                    <?php } ?></tbody></table>
            </div></div></div>
    </form>
<?php } ?>

<?=$html->footer_content();mysqli_close($conn);?>
