<?php
require_once 'support_file.php';
$title='STO Received';
$now=time();

$unique='pi_no';
$table="production_issue_master";
$table_details="production_issue_detail";
$journal_item="journal_item";
$page='warehouse_STO_received.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];

$pi_master=find_all_field(''.$table.'','',''.$unique.'='.$$unique.'');
$config_group_class=find_all_field("config_group_class","","1");
$lc_lc_received_batch_split="lc_lc_received_batch_split";
$condition="create_date='".date('Y-m-d')."'";

if(prevent_multi_submit()){
    if(isset($_POST['returned'])) {
        $rs="Select d.*
from 
".$table_details." d
 where

 d.".$unique."=".$$unique." and 
 d.status not in ('COMPLETED') group by d.id order by d.id";
        $pdetails=mysqli_query($conn, $rs);
        while($uncheckrow=mysqli_fetch_array($pdetails)) {
            $id = $uncheckrow[id];
            $deleted = mysqli_query($conn, "DELETE from ".$journal_item." where  item_id=".$uncheckrow['item_id']." and tr_no=".$$unique." and sr_no=".$uncheckrow['id']." and tr_from='GoodsTransfer'");
            }
        $_POST['checked_by']=$_SESSION['userid'];
        $_POST['checked_at']=time();
        $_POST['verifi_status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        echo "<script>self.opener.location = 'warehouse_STO_received.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for checked ...........................
    if(isset($_POST['checked'])){
        $rs="Select d.*,i.*
from 
production_issue_detail d,
item_info i
 where
 i.item_id=d.item_id  and 
 d.pi_no=".$$unique." order by d.id";
            $pdetails=mysqli_query($conn, $rs);
            while($uncheckrow=mysqli_fetch_array($pdetails)){
            $id=$uncheckrow['id'];
            $qty=$_POST['received_qty'.$id];
            $cost_price=$uncheckrow['unit_price'];
            $_POST['ji_date'] = date('Y-m-d');
            $_POST['item_id'] = $uncheckrow['item_id'];
            $_POST['warehouse_id'] = $uncheckrow['warehouse_to'];
            $_POST['relevant_warehouse'] = $uncheckrow['warehouse_from'];
            $_POST['item_in'] = $_POST['received_qty'.$id];
            $_POST['item_price'] = $cost_price;
            $_POST['total_amt'] = $_POST['received_qty'.$id]*$cost_price;
            $_POST['tr_from'] = 'GoodsReceived';
            $_POST['batch'] = $uncheckrow['lot_number'];
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_POST['expiry_date'] = $uncheckrow['expiry_date'];
            $_POST['custom_no'] = $uncheckrow['custom_pi_no'];
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $uncheckrow['id'];
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d H:s:i');
            $_POST['ip']=$ip;
            if($qty>0) {
                $crud = new crud($journal_item);
                $crud->insert();
            }
            $up_details=mysqli_query($conn, "UPDATE ".$table_details." SET total_unit_received='".$qty."' where item_id=".$uncheckrow['item_id']." and pi_no=".$$unique);
        }
        $jv=next_journal_voucher_id();
        $rev_Date=date('Y-m-d');
        if (($_POST['ledger_1'] > 0) && (($_POST['ledger_2'] && $_POST['dr_amount_1']) > 0) && ($_POST['cr_amount_2'] > 0)) {
            add_to_journal_new($rev_Date, $proj_id, $jv, $date, $_POST['ledger_1'], $_POST['narration_1'], $_POST['dr_amount_1'], 0, 'GoodsReceived', $$unique, $$unique, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,'','','');
            add_to_journal_new($rev_Date, $proj_id, $jv, $date, $_POST['ledger_2'], $_POST['narration_1'], 0, $_POST['dr_amount_1'], 'GoodsReceived', $$unique, $$unique, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,'','','');
        }
        $up_master="UPDATE ".$table." SET receive_date='$rev_Date' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $type=1;
        unset($_POST);
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
if(isset($_POST['viewreport'])){
    $res="Select m.pi_no as STO_ID,concat(m.pi_no,' - ',m.custom_pi_no) as STO_NO,pi_date as STO_date,w.warehouse_name as 'Warehouse / CMU From',w2.warehouse_name as Warehouse_to,m.remarks,u.fname as entry_by,m.entry_at,m.verifi_status as status
from 
".$table." m,
warehouse w,
users u,
warehouse w2
 where
  m.entry_by=u.user_id and 
 w.warehouse_id=m.warehouse_from and  
 w2.warehouse_id=m.warehouse_to and 
 m.verifi_status in ('CHECKED','COMPLETED') and 
  m.pi_date between '".$_POST['f_date']."' and '".$_POST['t_date']."' and 
 m.warehouse_to=".$_POST['warehouse_id']."  
order by m.".$unique." DESC ";
    $warehouse_id_GET=$_POST['warehouse_id'];
} else {
    $res="Select m.pi_no as STO_ID,concat(m.pi_no,' - ',m.custom_pi_no) as STO_NO,pi_date as STO_date,w.warehouse_name as 'Warehouse / CMU From',w2.warehouse_name as Warehouse_to,m.remarks,u.fname as entry_by,m.entry_at,m.verifi_status as status
from 
".$table." m,
warehouse w,
users u,
warehouse w2
 where
  m.entry_by=u.user_id and 
 w.warehouse_id=m.warehouse_from and  
 w2.warehouse_id=m.warehouse_to and 
 m.verifi_status='CHECKED' and 
 m.warehouse_to=".$_SESSION['warehouse']."  
order by m.".$unique." DESC ";
    $warehouse_id_GET=$_SESSION['warehouse'];
}
$received_from_warehouse_ledger=find_a_field('warehouse','ledger_id','warehouse_id='.$pi_master->warehouse_from);
$received_from_warehouse=find_a_field('warehouse','warehouse_name','warehouse_id='.$pi_master->warehouse_from);
$narration='Goods Received from '.$received_from_warehouse.', STONO#'.$$unique.', Remarks # '.$pi_master->remarks;

?>

<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=930,height=500,left = 280,top = -1");}
    </script>
    <style>
        #customers {}
        #customers td {}
        #customers tr:ntd-child(even)
        {background-color: #white;}
        #customers tr:hover {background-color: #F0F0F0;}
        td{}
    </style>
<?php if(isset($_GET[$unique])){
    require_once 'body_content_without_menu.php';
} else {
    require_once 'body_content.php';
} ?>
<?php if(isset($_GET[$unique])){ ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?php $warehouse_ledger=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$pi_master->warehouse_to.''); ?>
                    <table id="customers" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th style="vertical-align: middle">SL</th>
                            <th style="vertical-align: middle">Item Description</th>
                            <th style="text-align:center; vertical-align: middle">Unit</th>
                            <th style="text-align:center; vertical-align: middle">Transferred Qty</th>
                            <th style="text-align:center; vertical-align: middle">Rcvd. Qty</th>
                            <th style="text-align:center; vertical-align: middle">UnRcvd. Qty</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rs="Select d.*,i.*
from 
production_issue_detail d,
item_info i
 where
 i.item_id=d.item_id  and 
 d.pi_no=".$$unique." order by d.id";
                        $pdetails=mysqli_query($conn, $rs);
                        while($data=mysqli_fetch_object($pdetails)){
                            $id=$data->id;
                            $total_unit=$data->total_unit;
                            $total_unit_received=find_a_field('production_issue_detail','SUM(total_unit_received)','item_id='.$data->item_id.' and pi_no='.$$unique);
                            $unrec_qty=$total_unit-$total_unit_received;
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$js=$js+1;?></td>
                                <td style="text-align:left;vertical-align: middle"><?=$data->item_name;?></td>
                                <td style="text-align:left; vertical-align: middle"><?=$data->unit_name;?></td>
                                <td align="center" style="width:15%; text-align:center; vertical-align: middle">
                                <input type="hidden" name="cost_price<?=$id;?>" id="cost_price<?=$id;?>" value="<?=$data->item_price;?>">
                                    
                                <?=find_a_field('production_issue_detail','SUM(total_unit)','item_id="'.$data->item_id.'" and pi_no="'.$_GET['pi_no'].'"')?>
                                </td>
                                <td align="center" style="width:15%; text-align:center;vertical-align: middle"><?=$total_unit_received;?></td>
                                <SCRIPT language=JavaScript>
                                    function doAlert<?=$id;?>(form)
                                    {
                                        var val=form.received_qty<?=$id;?>.value;
                                        var val2=form.Un_del_<?=$id;?>.value;
                                        if (Number(val)>Number(val2)){
                                            alert('oops!! Exceed Received Limit!! Thanks');
                                            form.received_qty<?=$id;?>.value='';
                                        }
                                        form.received_qty<?=$id;?>.focus();
                                    }</script>
                                <input type="hidden" name="Un_del_<?=$id;?>" id="Un_del_<?=$id;?>" style="text-align: center; width: 80px; vertical-align: middle" value="<?=$unrec_qty;?>" >
                                <td style="width:10%; text-align:center; vertical-align: middle">
                                    <?php if($unrec_qty>0){$cow++; ?>
                                    <input type="text" name="received_qty<?=$id;?>" id="received_qty<?=$id;?>" onkeyup="doAlert<?=$id;?>(this.form);" style="text-align: center; width: 80px; vertical-align: middle" value="<?=$unrec_qty;?>" >
                                    <?php } else { echo '<font style="font-weight: bold">Done</font>';} ?>
                                </td>
                            </tr>
                        <?php $amountqtys=$data->total_unit*$data->unit_price;$amountqty=$amountqty+$amountqtys; } ?>
</td></tr>
                        </tbody>
                    </table>

                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px; display:none">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>#</th>
                            <th style="width: 8%; vertical-align: middle; text-align: center">Journal</th>
                            <th style="width: 10%; vertical-align: middle; text-align: center">For</th>
                            <th style="vertical-align: middle">Accounts Description</th>
                            <th style="text-align:center; width: 25%; vertical-align: middle">Narration</th>
                            <th style="text-align:center; width: 12%; vertical-align: middle">Debit</th>
                            <th style="text-align:center; width: 12%; vertical-align: middle">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th rowspan="2" style="text-align: center; vertical-align: middle">1</th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle">Receive from Transit</th>
                            <th style="text-align: center; vertical-align: middle">Warehouse</th>
                            <td style="vertical-align: middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_1"  name="ledger_1">
                                    <option  value="<?=$warehouse_ledger;?>"><?=$warehouse_ledger; ?>-<?=$customer_name=find_a_field('accounts_ledger','ledger_name','ledger_id='.$warehouse_ledger.''); ?></option>
                                </select>
                            </td>
                            <td rowspan="2" style="text-align: center; vertical-align: middle"><textarea name="narration_1" id="narration_1" class="form-control col-md-7 col-xs-12" style="width:100%; height:92px; font-size: 11px; text-align:center"><?=$narration;?></textarea></td>
                            <td align="center" style="vertical-align: middle"><input type="text" name="dr_amount_1" readonly value="<?=$amountqty;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td align="center" style="vertical-align: middle"><input type="text" name="cr_amount_1" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        <tr>
                            <th style="text-align: center; vertical-align: middle">Transit</th>
                            <td style="vertical-align: middle"><?$transit_ledger=$config_group_class->finished_goods_in_transit;?>
                                <select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_2" id="ledger_2">
                                    <?=foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $received_from_warehouse_ledger, 'ledger_id='.$received_from_warehouse_ledger); ?>
                                </select></td>
                            <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_2" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_2" readonly value="<?=$amountqty;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        </tbody>
                    </table>


                    <?php
                    if($cow<1){
                        $vars['verifi_status']='COMPLETED';
                        $table_master='production_issue_master';
                        $id=$$unique;
                        db_update($table_master, $id, $vars, 'pi_no');
                    }
                    $status=find_a_field('production_issue_master','verifi_status','pi_no='.$_GET['pi_no']);
                    if($status=='COMPLETED'){
                        echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Stock Transfer has been Received !!</i></h6>'; } else {?>
                        <p>
                            <button style="float: left; font-size: 12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="remarks" class="form-control col-md-7 col-xs-12" >
                            <button style="float: right; font-size: 12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Received</button>
                        </p>
                    <? }?>

                </form>
            </div>
        </div>
    </div>
<?php } ?>


<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 30px"  value="<?=($_POST['f_date']!='')? $_POST['f_date'] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 30px"  value="<?=($_POST['t_date']!='')? $_POST['t_date'] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select  class="form-control" style="width: 200px;font-size:11px; height: 30px" required="required"  name="warehouse_id" id="warehouse_id">
                        <option selected></option>
                        <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$_POST['warehouse_id']);?>
                    </select></td>
                <td style="padding: 10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Received STO</button></td>
            </tr></table>
    </form>
<?=$crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>