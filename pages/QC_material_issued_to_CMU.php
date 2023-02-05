<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='pi_no';
$unique_field='name';
$table="production_issue_master";
$table_deatils="production_issue_detail";
$journal_item="journal_item";
$journal_accounts="journal";
$page='QC_material_issued_to_CMU.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$master=find_all_field('production_issue_master','','pi_no='.$_GET[$unique].'');


if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['verifi_status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $results="Select srd.*,i.* from ".$table_deatils." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique."=".$$unique." group by srd.id order by srd.id";
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)){
            $_POST['ji_date'] = $ji_date;
            $_POST['item_id'] = $row[item_id];
            $_POST['warehouse_id'] = $row[warehouse_from];
            $_POST['relevant_warehouse'] = $row[warehouse_to];
            $_POST['item_ex'] = $row[total_unit];
            $_POST['item_price'] = $row[unit_price];
            $_POST['total_amt'] = $row[total_amt];
            $_POST['lot_number'] = $row[lot];
            $_POST['batch'] = $row[batch];
            $_POST['custom_no'] = $row[custom_pi_no];
            $_POST['tr_from'] = 'Issued';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $row[id];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();

            $_POST['item_ex'] = 0;
            $_POST['ji_date'] = $ji_date;
            $_POST['item_id'] = $row[item_id];
            $_POST['warehouse_id'] = 25;
            $_POST['relevant_warehouse'] = $row[warehouse_from];
            $_POST['item_in'] = $row[total_unit];
            $_POST['item_price'] = $row[unit_price];
            $_POST['total_amt'] = $row[total_amt];
            $_POST['lot_number'] = $row[lot];
            $_POST['batch'] = $row[batch];
            $_POST['custom_no'] = $row[custom_pi_no];
            $_POST['tr_from'] = 'Issued';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $row[id];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();
            $total_amount=$total_amount+$row[total_amt];
        }
        $jv=next_journal_voucher_id();
        $transaction_date=date('Y-m-d');
        $date=date('d-m-y' , strtotime($transaction_date));
        $_POST[narration] = 'Material Issued to CMU, Remarks#'.$master->remarks.' , VAT Challan#'.$master->VATChallanno;
        $_POST[ledger_1] = '1007002900070000';
        $_POST[ledger_2] = find_a_field('warehouse','ledger_id_RM','warehouse_id='.$master->warehouse_from.'');

            if($total_amount>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration], $total_amount, 0, Issued, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration], 0, $total_amount, Issued, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            }

        $up_master="UPDATE ".$table." SET verifi_status='CHECKED' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_deatils." SET verifi_status='CHECKED' where ".$unique."=".$unique."";
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
        unset($_SESSION['ps_id']);
        unset($_SESSION['pi_id']);
        unset($_SESSION['initiate_daily_production']);
        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

if(isset($_POST[viewreport])){
    $resultss="Select 
p.pi_no,
p.pi_no,
p.custom_pi_no as 	Custom_no,
p.pi_date as Date,
w.warehouse_name as 'transferred from',	
wto.warehouse_name as 'transferred to',	
u.fname as Entry_by,
p.entry_at as Entry_at,
p.verifi_status as status
from 
production_issue_master p,
warehouse w,
warehouse wto,
user_activity_management u
where
p.entry_by=u.user_id and 
w.warehouse_id=p.warehouse_from and  
p.pi_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' and 
p.warehouse_from=".$_POST[warehouse_from]." and ISSUE_TYPE='ISSUE' and 
p.warehouse_to=wto.warehouse_id
order by p.pi_no DESC ";
} else {
    $resultss="Select 
p.pi_no,
p.pi_no,
p.custom_pi_no as 	Custom_no,
p.pi_date as Date,
w.warehouse_name as 'transferred from',	
wto.warehouse_name as 'transferred to',	
u.fname as Entry_by,
p.entry_at as Entry_at,
p.verifi_status as status
from 
production_issue_master p,
warehouse w,
warehouse wto,
user_activity_management u
where
p.entry_by=u.user_id and 
w.warehouse_id=p.warehouse_from and  
p.verifi_status in ('UNCHECKED') and ISSUE_TYPE='ISSUE' and  
p.warehouse_to=wto.warehouse_id
order by p.pi_no DESC ";
}?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php if(isset($_GET[$unique])){
    require_once 'body_content_without_menu.php'; } else {
    require_once 'body_content.php'; } ?>

<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>SL</th>
                            <th>Code</th>
                            <th>Material Description</th>
                            <th style="width:5%; text-align:center">UOM</th>
                            <th style="text-align:center">Lot</th>
                            <th style="text-align:center">Batch</th>
                            <th style="text-align:center">MFG</th>
                            <th style="text-align:center">Total Unit</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php
                        $results="Select srd.*,i.* from ".$table_deatils." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique."=".$$unique." order by srd.id";
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                                <td style="vertical-align:middle"><?=$row[finish_goods_code];?></td>
                                <td style="vertical-align:middle; width: 25%"><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td align="center" style=" text-align:center"><?=$row[lot];?></td>
                                <td align="center" style=" text-align:center"><?=$row[batch];?></td>
                                <td align="center" style=" text-align:right"><?=$row[mfg];?></td>
                                <td align="center" style=" text-align:right"><?=$row[total_unit]; ?></td>
                                <td align="center" style=" text-align:center"><?=$row[unit_price]; ?></td>
                                <td align="center" style="text-align:right"><?=number_format($row[total_amt],2);?></td>

                            </tr>
                            <?php  $ttotal_unit=$ttotal_unit+$row[total_unit];
                            $tfree_qty=$tfree_qty+$row[free_qty];
                            $ttotal_qty=$ttotal_qty+$row[total_qty];
                            $tdiscount=$tdiscount+$row[discount];
                            $ttotal_amt=$ttotal_amt+$row[total_amt];  } ?>
                        </tbody>
                    </table>

                    <?php
                    $GET_status=find_a_field(''.$table.'','verifi_status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED'){  ?>
                        <p>
                            <button style="float: left; font-size: 12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right; font-size: 12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Forward to CMU</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Data has been checked !!</i></h6>';}?>
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
                <td><select  class="form-control" style="width: 200px; height:25px; font-size:11px; vertical-align:middle" tabindex="-1" required="required"  name="warehouse_from" id="warehouse_from">
                        <option selected></option>
                        <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
                        advance_foreign_relation($sql_plant,$_POST[warehouse_from]);?>
                    </select></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Issued Report</button></td>

            </tr></table>
        <?=$crud->report_templates_with_status($resultss,"Material Issued View");?>
    </form>
<?php } ?>

<?php require_once 'footer_content.php' ?>