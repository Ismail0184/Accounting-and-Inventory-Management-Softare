<?php
require_once 'support_file.php';
$title='Material Received';
$now=time();

$unique='pi_no';
$table="production_issue_master";
$table_details="production_issue_detail";
$journal_item="journal_item";
$page='warehouse_material_issued_to_CMU_received.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
$pi_master=find_all_field(''.$table.'','',''.$unique.'='.$$unique.'');
$config_group_class=find_all_field("config_group_class","","1");

if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {

        $rs="Select d.*
from 
".$table_details." d
 where

 d.".$unique."=".$$unique." and 
 d.status not in ('COMPLETED') group by d.id order by d.id";
        $pdetails=mysqli_query($conn, $rs);
        while($uncheckrow=mysqli_fetch_array($pdetails)) {
            $id = $uncheckrow[id];
            $deleted = mysqli_query($conn, "DELETE from ".$journal_item." where  item_id=".$uncheckrow[item_id]." and tr_no=".$$unique." and sr_no=".$uncheckrow[id]." and tr_from='ProductionTransfer'");
        }

        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        //$_POST['verifi_status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        echo "<script>self.opener.location = 'warehouse_STO_received.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for checked ...........................
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
        while($uncheckrow=mysqli_fetch_array($pdetails)){
            $id=$uncheckrow[id];
            $rcvd=$uncheckrow['total_unit_received'];
            $qty=$_POST['received_qty'.$id];
            $cost_price=$_POST['cost_price'.$id];
            $total_Rcvd=$rcvd+$qty;
            $up_details="UPDATE ".$table_details." SET total_unit_received='$total_Rcvd' where id=".$id."";
            $update_table_details=mysqli_query($conn, $up_details);
            $_POST['ji_date'] = date('Y-m-d');
            $_POST['item_id'] = $uncheckrow[item_id];
            $_POST['warehouse_id'] = 25;
            $_POST['relevant_warehouse'] = $uncheckrow[warehouse_to];
            $_POST['item_ex'] = $_POST['received_qty'.$id];
            $_POST['item_price'] = $cost_price;
            $_POST['total_amt'] = $_POST['received_qty'.$id]*$cost_price;
            $_POST['tr_from'] = 'Issued';
            $_POST['custom_no'] = $uncheckrow[custom_pi_no];
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $uncheckrow[id];
            $_POST[ip]=$ip;

            if($qty>0) {
                $crud = new crud($journal_item);
                $crud->insert();
            }
            $_POST['item_ex'] = 0;
            $_POST['ji_date'] = date('Y-m-d');
            $_POST['item_id'] = $uncheckrow[item_id];
            $_POST['warehouse_id'] = $uncheckrow[warehouse_to];
            $_POST['relevant_warehouse'] = 25;
            $_POST['item_in'] = $_POST['received_qty'.$id];
            $_POST['item_price'] = $cost_price;
            $_POST['total_amt'] = $_POST['received_qty'.$id]*$cost_price;
            $_POST['tr_from'] = 'Issued';
            $_POST['custom_no'] = $uncheckrow[custom_pi_no];
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $uncheckrow[id];
            $_POST[ip]=$ip;

            if($qty>0) {
                $crud = new crud($journal_item);
                $crud->insert();
            }
            $toal_amount=$toal_amount+($_POST['received_qty'.$id]*$uncheckrow[unit_price]);
        }

        $jv=next_journal_voucher_id();
        $transaction_date=date('Y-m-d');
        $transitLedger=$config_group_class->raw_material_in_transit;
        $warehouse_ledger=find_a_field('warehouse','ledger_id_RM','warehouse_id='.$pi_master->warehouse_to.'');
        $narration = 'Material Issued to CMU, Remarks#'.$pi_master->remarks.' , VAT Challan#'.$pi_master->VATChallanno;

        add_to_journal_new($transaction_date, $proj_id, $jv, $date, $warehouse_ledger, $narration, $toal_amount, 0, Issued, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        add_to_journal_new($transaction_date, $proj_id, $jv, $date, $transitLedger, $narration, 0, $toal_amount, Issued, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        $rev_Date=date('Y-m-d');
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
if(isset($_POST[viewreport])){
    $resultss="Select 
p.pi_no,
p.pi_no,
p.custom_pi_no as 	Custom_no,
p.pi_date as Date,
w.warehouse_name as 'transferred from',	
wto.warehouse_name as 'transferred to',
v.vendor_name as transporter,
p.track_no,
p.driver_info,	
u.fname as Entry_by,
p.entry_at as Entry_at,
p.verifi_status as status
from 
production_issue_master p,
warehouse w,
warehouse wto,
user_activity_management u,
vendor v
where
p.entry_by=u.user_id and 
p.transporter=v.vendor_id and 
w.warehouse_id=p.warehouse_from and  
p.pi_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' and 
p.warehouse_to=".$_POST[warehouse_to]." and ISSUE_TYPE='ISSUE' and 
p.warehouse_to=wto.warehouse_id
order by p.pi_no DESC ";
}?>

<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=830,height=500,left = 280,top = -1");}
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
                            <th style="vertical-align: middle">SL</th>
                            <th style="vertical-align: middle">Material Description</th>
                            <th style="text-align:center; vertical-align: middle">Unit</th>
                            <th style="text-align:center; vertical-align: middle">Lot</th>
                            <th style="text-align:center; vertical-align: middle">Batch</th>
                            <th style="text-align:center; vertical-align: middle">MFG</th>
                            <th style="text-align:center; vertical-align: middle">Issued Qty</th>
                            <th style="text-align:center; vertical-align: middle">Rcvd. Qty</th>
                            <th style="text-align:center; vertical-align: middle">UnRcvd. Qty</th>
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
                            $id=$uncheckrow[id];
                            $unrec_qty=$uncheckrow[total_unit]-$uncheckrow[total_unit_received];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$js=$js+1;?></td>
                                <td style="text-align:left;vertical-align: middle"><?=$uncheckrow[item_name];?></td>
                                <td style="text-align:left; vertical-align: middle"><?=$uncheckrow[unit_name];?></td>
                                <td style="width:10%; text-align:left; vertical-align: middle"><?=$uncheckrow[lot];?></td>
                                <td style="width:10%; text-align:left; vertical-align: middle"><?=$uncheckrow[batch];?></td>
                                <td style="width:10%; text-align:left; vertical-align: middle"><?=$uncheckrow[mfg];?></td>
                                <td align="center" style="width:15%; text-align:center; vertical-align: middle">
                                    <input type="hidden" name="cost_price<?=$id;?>" id="cost_price<?=$id;?>" value="<?=$cost_price=find_a_field('item_costing','fg_cost','status="ON" and item_id='.$uncheckrow[item_id].'');?>">
                                    <?=$ttotal=$uncheckrow[total_unit];?></td>
                                <td align="center" style="width:15%; text-align:center;vertical-align: middle"><?=number_format($ttotal=$uncheckrow[total_unit_received],2);?></td>

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
                                    <?php } else { echo '<font style="font-weight: bold">Done</font>';

                                        $vars['status']='COMPLETED';
                                        //$table_details='production_issue_detail';
                                        $id=$uncheckrow[id];
                                        db_update($table_details, $id, $vars, 'id');
                                    } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody></table>

                    <?php
                    if($cow<1){
                        $vars['verifi_status']='COMPLETED';
                        $table_master='production_issue_master';
                        $id=$$unique;
                        db_update($table_master, $id, $vars, 'pi_no');
                        echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>Materials have been received !!</i></h6>'; } else {?>
                        <p>
                            <button style="float: left; font-size: 12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right; font-size: 12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Received</button>
                        </p>
                    <? }?>

                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) { echo $_POST[f_date];} else {echo date('Y-m-01'); }?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date];} else {echo date('Y-m-d'); }?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select  class="form-control" style="width: 200px; height:25px; font-size:11px; vertical-align:middle" tabindex="-1" required="required"  name="warehouse_to" id="warehouse_to">
                        <option selected></option>
                        <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
                        advance_foreign_relation($sql_plant,$_POST[warehouse_to]);?>
                    </select></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Issued Report</button></td>

            </tr></table>
        <?=$crud->report_templates_with_status($resultss,"Material Issued View");?>
    </form>
<?php } ?>

<?php require_once 'footer_content.php' ?>