<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$unique='pi_no';
$table="production_issue_master";
$table_details="production_issue_detail";
$journal_item="journal_item";
$page='prodcution_transfer_checked.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$pi_master=find_all_field(''.$table.'','',''.$unique.'='.$$unique.'');
$config_group_class=find_all_field("config_group_class","","1");
$condition="create_date='".date('Y-m-d')."'";
$lc_lc_received_batch_split='lc_lc_received_batch_split';
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


    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $rs="Select d.*,i.*
from 
".$table_details." d,
item_info i
 where
 i.item_id=d.item_id  and 
 d.".$unique."=".$$unique."
 order by d.id";
        $pdetails=mysqli_query($conn, $rs);
        while($row=mysqli_fetch_array($pdetails)){
            $_POST['ji_date'] = date('Y-m-d');
            $_POST['item_id'] = $row[item_id];
            $_POST['warehouse_id'] = $row[warehouse_from];
            $_POST['relevant_warehouse'] = $row[warehouse_to];
            $_POST['item_price'] = find_a_field('item_costing','fg_cost','status="ON" and item_id='.$row[item_id].'');
            $_POST['total_amt'] = $_POST['item_ex']*$_POST['item_price'];
            $_POST['Remarks'] = $row[Remarks];
            $_POST['batch'] = $row[batch];
            $_POST['tr_from'] = 'ProductionTransfer';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $row[id];
            $_POST[ip]=$ip;
            $sent_to_warehouse_at=date('Y-m-d H:s:i');
            $item_id=$row[item_id];
            $_SESSION['bqty_STO']=$row[total_unit];
            $create_date=date('Y-m-d');
            
            $fifocheck=mysqli_query($conn, "select distinct qb.batch, SUM(j.item_in-j.item_ex) as qty, j.item_price as rate,qb.mfg,qb.batch_no  from journal_item j, lc_lc_received_batch_split qb
            where qb.batch=j.batch and qb.status='PROCESSING' and j.item_id=qb.item_id  and j.item_id='".$item_id."' and j.warehouse_id='".$row[warehouse_from]."'
            group by qb.batch order by qb.mfg asc,qb.batch asc");
            while ($fifocheckrow=mysqli_fetch_array($fifocheck)){
                if ( $_SESSION['bqty_STO']<=$fifocheckrow['qty'] && $_SESSION['bqty_STO']>0 && $fifocheckrow['qty']>0){
                    $new_batch = automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
                    mysqli_query($conn, "INSERT INTO journal_item (ji_date,item_id,warehouse_id,relevant_warehouse,item_ex,item_price,total_amt,tr_from,tr_no,entry_by,entry_at,ip,sr_no,section_id,company_id,batch,expiry_date,Remarks,lot_number) VALUES
                    ('" .$_POST['ji_date']. "','$_POST[item_id]','$_POST[warehouse_id]','$_POST[relevant_warehouse]','$_SESSION[bqty_STO]','$fifocheckrow[rate]','".$_SESSION[bqty_STO]*$fifocheckrow[rate]."','$_POST[tr_from]','".$$unique."','$_SESSION[userid]','$sent_to_warehouse_at','$ip','$row[id]','$_SESSION[sectionid]','$_SESSION[companyid]',$fifocheckrow[batch],'$fifocheckrow[mfg]','$item_status','$new_batch')");
                    
                    mysqli_query($conn, "INSERT INTO lc_lc_received_batch_split (po_no,create_date,lc_id,warehouse_id,batch_no,item_id,qty,rate,batch,mfg,entry_by,entry_at,status,source,section_id,company_id,line_id) VALUES
                    ('".$$unique."','$create_date','".$$unique."','$row[warehouse_to]','".$fifocheckrow['batch_no']."','$_POST[item_id]','$_SESSION[bqty_STO]','$fifocheckrow[rate]','$new_batch','$fifocheckrow[mfg]','$_SESSION[userid]','$sent_to_warehouse_at','PROCESSING','STO','$_SESSION[sectionid]','$_SESSION[companyid]','$row[id]')");
                    
                    $_SESSION['bqty_STO']= 0;
                } else if ($_SESSION['bqty_STO']>=$fifocheckrow['qty'] && $_SESSION['bqty_STO']>0 && $fifocheckrow['qty']>0){
                    $new_batch = automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
                    mysqli_query($conn, "INSERT INTO journal_item (ji_date,item_id,warehouse_id,relevant_warehouse,item_ex,item_price,total_amt,tr_from,tr_no,entry_by,entry_at,ip,sr_no,section_id,company_id,batch,expiry_date,Remarks,lot_number) VALUES 
                    ('" .$_POST['ji_date']. "','$_POST[item_id]','$_POST[warehouse_id]','$_POST[relevant_warehouse]','$fifocheckrow[qty]','$fifocheckrow[rate]','".$fifocheckrow[qty]*$fifocheckrow[rate]."','$_POST[tr_from]','".$$unique."','$_SESSION[userid]','$sent_to_warehouse_at','$ip','$row[id]','$_SESSION[sectionid]','$_SESSION[companyid]',$fifocheckrow[batch],'$fifocheckrow[mfg]','$item_status','$new_batch')");

                    mysqli_query($conn, "INSERT INTO lc_lc_received_batch_split (po_no,create_date,lc_id,warehouse_id,batch_no,item_id,qty,rate,batch,mfg,entry_by,entry_at,status,source,section_id,company_id,line_id) VALUES
                    ('".$$unique."','$create_date','".$$unique."','$row[warehouse_to]','".$fifocheckrow['batch_no']."','$_POST[item_id]','".$fifocheckrow['qty']."','$fifocheckrow[rate]','$new_batch','$fifocheckrow[mfg]','$_SESSION[userid]','$sent_to_warehouse_at','PROCESSING','STO','$_SESSION[sectionid]','$_SESSION[companyid]','$row[id]')");
                   
                    $_SESSION['bqty_STO']= intval($_SESSION['bqty_STO'])-$fifocheckrow['qty'];
                }}
        }


        $jv=next_journal_voucher_id();
        $total_transfer_in_amount=find_a_field('journal_item','SUM(total_amt)','tr_from="ProductionTransfer" and tr_no='.$_GET[$unique]);
            $transitLedger=$config_group_class->finished_goods_in_transit;
            $warehouse_ledger=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$pi_master->warehouse_from);
            $warehouse_to_ledger=find_all_field('warehouse','','warehouse_id='.$pi_master->warehouse_to);
            $narration='FG Transfer to '.$warehouse_to_ledger->warehouse_name.', STONO #'.$$unique.', Remarks # '.$pi_master->remarks;
        $transaction_date=$pi_master->pi_date;
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $transitLedger, $narration, $total_transfer_in_amount, 0, ProductionTransfer, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $warehouse_ledger, $narration, 0, $total_transfer_in_amount, ProductionTransfer, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        $up_master="UPDATE ".$table." SET verifi_status='CHECKED',verifi_by='$_SESSION[userid]',verify_at='$todayss',checked_by='".$_SESSION[userid]."',checked_at='$now' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_details." SET verifi_status='CHECKED',status='CHECKED',verifi_by='$_SESSION[userid]',verify_at='$todayss' where ".$unique."=".$unique."";
        $update_table_details=mysqli_query($conn, $up_details);
        $type=1;
        unset($_POST);
        echo "<script>self.opener.location = '".$page."'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

}

// data query..................................
if(isset($_POST[viewreport])){
    $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
    $to_date=date('Y-m-d' , strtotime($_POST[t_date]));
    $resultss="Select m.pi_no,m.pi_no as STO_ID,m.custom_pi_no as STO_No,m.pi_date as STO_date,m.remarks,w.warehouse_name as 'Warehouse / CMU From',w2.warehouse_name as transfer_to,u.fname as entry_by,m.verifi_status as status
from 
".$table." m,
warehouse w,
users u,
warehouse w2

 where
  m.entry_by=u.user_id and 
 w.warehouse_id=m.warehouse_from and  
 w2.warehouse_id=m.warehouse_to and 
 m.pi_date between '$_POST[f_date]' and '$_POST[t_date]' and
 m.verifi_status not in ('MANUAL') 
 
 order by m.".$unique." DESC ";
} else {
    $resultss="Select m.pi_no,m.pi_no as STO_ID,m.custom_pi_no as STO_No,m.pi_date as STO_date,m.remarks,w.warehouse_name as 'Warehouse / CMU From',w2.warehouse_name as transfer_to,u.fname as entry_by,m.verifi_status as status
from 
".$table." m,
warehouse w,
users u,
warehouse w2

 where
  m.entry_by=u.user_id and 
 w.warehouse_id=m.warehouse_from and  
 w2.warehouse_id=m.warehouse_to and 
  m.verifi_status='UNCHECKED' order by m.".$unique." DESC ";

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
                            <th>Custom Code</th>
                            <th>Item Description</th>
                            <th style="text-align: center">Unit</th>
                            <th style="text-align: center">Pack Size</th>
                            <th style="width:5%; text-align:center;vertical-align: middle">Available Stock</th>
                            <th style="text-align:center">Qty in Pcs</th>
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
                        while($data=mysqli_fetch_object($pdetails)){
                          $available_stock=find_a_field('journal_item','SUM(item_in-item_ex)','warehouse_id='.$pi_master->warehouse_from.' and item_id='.$data->item_id);
                          $unrec_qty=$available_stock-$data->total_unit;
                            ?>
                                <tr <?php if($unrec_qty<0){$cow++;?> style="background-color:red; color:white" <?php } ?>>
                                <td style="width:3%; vertical-align:middle"><?=$js=$js+1;?></td>
                                <td><?=$data->finish_goods_code;?></td>
                                <td style="text-align:left"><?=$data->item_name;?></td>
                                <td style="text-align:center"><?=$data->unit_name;?></td>
                                <td style="text-align:center"><?=$data->pack_size;?></td>
                                <td style="vertical-align:middle; text-align:center"><input type="text" name="available_stock" style="width:80px; height:20px; font-size:11px" value="<?=$available_stock?>" readonly class="form-control col-md-7 col-xs-12"></td>
                                <td align="right" style="text-align:center"><?=$data->total_unit;?></td>
                            </tr>
                            <?php  $amountqty=$amountqty+$data->total_unit;} ?>
                        <tr style="font-weight: bold"><td colspan="6" style="text-align: right">Total = </td>
                            <td style="text-align: center"><?=number_format($amountqty)?></td>
                        </tr>
                        </tbody></table>

                    <?php
                    
                    $GET_status=find_a_field(''.$table.'','verifi_status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED'){ 
                        if($cow<1){ ?>
                        <p>
                            <input type="hidden" value="<?=$totalamount;?>" name="total_amount">
                            <button style="float: left; font-size: 12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right;font-size: 12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Forward</button>
                        </p>
                        
                    <? } else {?><h6 style="text-align: center;color: red;  font-weight: bold"><i>Oops! Some of the items have exceeded the stock balance!!</i></h6> <?php }?>
<?php } else {
                        echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Stock Transfer has been '.$GET_status.' !!</i></h6>';}?>


                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
    <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px;" max="<?=date('Y-m-d');?>"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px;"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px;" name="viewreport"  class="btn btn-primary">View STO</button></td>
            </tr></table>
        <?=$crud->report_templates_with_status($resultss);?>
    </form>
<?php } ?>


<?=$html->footer_content();?>