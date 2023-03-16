<?php
require_once 'support_file.php';
$title='Inventory Return View';
$now=time();
$unique='id';
$unique_field='name';
$table="purchase_return_master";
$table_details="purchase_return_details";
$unique_details="m_id";
$journal_item="journal_item";
$journal_accounts="journal";
$page='accounts_inventory_return_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$masterDATA=find_all_field('purchase_return_master','','id='.$_GET[$unique] );
if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $date=date('Y-m-d');
		$results=mysqli_query($conn, "Select srd.*,i.* from ".$table_details." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique_details."=".$_GET[$unique]." order by srd.id");
            while($row=mysqli_fetch_array($results)){
            $ids=$row[id];
            mysqli_query($conn, "INSERT INTO journal_item (ji_date,item_id,warehouse_id,item_ex,item_price,total_amt,tr_from,tr_no,entry_by,entry_at,Remarks,ip,section_id,company_id) VALUES ('$date','$row[item_id]','$row[warehouse_id]','$row[qty]','$row[rate]','$row[amount]','Purchase_Return','$_GET[id]','$_SESSION[userid]','$enat','','$ip','$_SESSION[sectionid]','$_SESSION[companyid]')");
        }
		
		$jv=next_journal_voucher_id();
        $transaction_date=date('Y-m-d');
        $enat=date('Y-m-d h:s:i');
        $cd =$_POST[c_date];
        $c_date=date('Y-m-d' , strtotime($cd));
        $invoice=$_POST[invoice];
        $date=date('d-m-y' , strtotime($transaction_date));
        $j=0;
        for($i=0;$i<strlen($date);$i++)
        {
            if(is_numeric($date[$i]))
            { $time[$j]=$time[$j].$date[$i];
            } else {
                $j++; } }
        $date=mktime(0,0,0,$time[1],$time[0],$time[2]);
        if($_POST[dr_amount_1]>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], $_POST[cr_amount_1], Purchase_Return, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_2], $_POST[dr_amount_2], $_POST[cr_amount_2], Purchase_Return, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }
        //$up_master="UPDATE ".$table." SET status='COMPLETED' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_details." SET status='COMPLETED' where ".$unique_details."=".$$unique."";
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

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>
<?php                      
$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));
                        if(isset($_POST[viewreport])){
$sql="Select p.id,p.id,p.ref_no as Referance,p.remarks,p.return_date as date,w.warehouse_name,v.vendor_name,concat(u.fname,' - ',p.entry_at) as entry_by,concat((SELECT PBI_NAME from personnel_basic_info where PBI_ID=p.checked_by_qc),' - ',checked_at) as Checked_By,p.status
from 
".$table." p,
warehouse w,
users u,
vendor v
 where
  p.entry_by=u.user_id and 
 w.warehouse_id=p.warehouse_id and  
 v.vendor_id=p.vendor_id and 
 p.return_date between '$from_date' and '$to_date' order by p.".$unique." DESC ";
                            
                        } else {
$sql="Select p.id,p.id,p.ref_no as Referance,p.remarks,p.return_date,w.warehouse_name,v.vendor_name,u.fname,p.entry_at,(SELECT PBI_NAME from personnel_basic_info where PBI_ID=p.checked_by_qc) as Checked_By
from 
".$table." p,
warehouse w,
users u,
vendor v

 where
  p.entry_by=u.user_id and 
 w.warehouse_id=p.warehouse_id and  
 v.vendor_id=p.vendor_id and p.status='ROCOMMENDED' order by p.".$unique." DESC "; }  ?>

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
                            <th>Finish Goods</th>
                            <th style="width:5%; text-align:center">UOM</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Total Qty</th>
                            <th style="text-align:center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $results="Select srd.*,i.* from ".$table_details." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique_details."=".$$unique." order by srd.id";
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                                <td style="vertical-align:middle"><?=$row[finish_goods_code];?></td>
                                <td style="vertical-align:middle;"><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td align="center" style=" text-align:right"><?=$row[rate]; ?></td>
                                <td align="center" style=" text-align:center"><?=number_format($row[qty],2); ?></td>
                                <td align="center" style="text-align:right"><?=number_format($row[amount],2);?></td>

                            </tr>
                            <?php $total_amount=$total_amount+$row[amount];
                        }


                        ?>
                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Total Inventory Return in Value = </td>
                            <td align="center" ></td>
                            <td align="center" ></td>
                            <td align="right" ><?=number_format($total_amount,2);?></td>
                        </tr>
                    </table>



                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="vertical-align: middle">#</th>
                            <th style="width: 12%; vertical-align: middle; text-align: center">For</th>
                            <th>Accounts Description</th>
                            <th style="text-align:center; width: 25%">Narration</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: center">1</td>
                            <td style="text-align: center">Vendor Ledger</td>
                            <td><?
                                $vendor_ledger=find_a_field('vendor','ledger_id','vendor_id='.$masterDATA->vendor_id.'');
                                $warehouse_ledger=find_a_field('warehouse','ledger_id_RM','warehouse_id='.$masterDATA->warehouse_id.'');
                                ?>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_1"  name="ledger_1">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $vendor_ledger, '1'); ?>
                                </select>

                            </td>

                            <td style="text-align: center"><input type="text" name="narration_1" id="narration_1" value="Inventory Return,Ref.No#<?=$masterDATA->ref_no;?>,<?=$masterDATA->remarks;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td align="center"><input type="text" name="dr_amount_1" readonly value="<?=$total_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td align="center"><input type="text" name="cr_amount_1" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center">2</td>
                            <td style="text-align: center">Inventory Ledger</td>
                            <td>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_2"  name="ledger_2">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $warehouse_ledger, 'ledger_group_id in ("1007")'); ?>
                                </select>
                                </td>

                            <td style="text-align: center"><input type="text" name="narration_2" id="narration_2" value="Inventory Return,Ref.No#<?=$masterDATA->ref_no;?>,<?=$masterDATA->remarks;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right"><input type="text" name="dr_amount_2" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_2" readonly value="<?=$total_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        </tbody>
                    </table>



                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status='ROCOMMENDED'){  ?>
                        <p>
                            <button style="float: left; margin-left:1%; font-size:12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right; margin-right:1%; font-size:12px" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Checked & Completed the Inventory Return </button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Inventory Return has not yet been Checked ! Please wait until approval !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Inventory Return</button></td>
            </tr></table> 
</form>
<?=$crud->report_templates_with_data($sql,$title);?>   
<?php }  ?>    
<?=$html->footer_content();?>