<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Production Report';
$now=time();
$unique='trvClaim_id';
$unique_field='name';
$table="travel_application_claim_master";
$table_details="travel_application_claim_details";

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$journal_accounts="journal";
if(isset($_POST[viewreport])){
$page='print_preview_travel_claim_exp.php';
} else {
$page='accounts_travel_expenses_claim.php';
}
$ji_date=date('Y-m-d');
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
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], $_POST[cr_amount_1], Payment, $$unique, $$unique, $_POST[cc_code_1], 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_2], $_POST[dr_amount_2], $_POST[cr_amount_2], Payment, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }

        $up_master="UPDATE ".$table." SET accounts_viewed='YES',accounts_viewed_date='$todayss' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
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

$datas=find_all_field(''.$table.'','',''.$unique.'='.$_GET[$unique]);
?>


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
                            <td rowspan="2" align="center" style="vertical-align: middle"><strong>SL</strong></td>
                            <td rowspan="2" align="center" style="width:10%; vertical-align: middle"><strong>Date</strong></td>
                            <td rowspan="2" align="center" style="width: 15%;  vertical-align: middle"><strong>Description</strong></td>
                            <td rowspan="2" align="center" style="width: 15%;  vertical-align: middle"><strong>Transport Mode</strong></td>
                            <td rowspan="2" align="center" style="width: 15%;  vertical-align: middle"><strong>Transport Exp.</strong></td>
                            <td rowspan="2" align="center" style="width: 15%;  vertical-align: middle"><strong>Hotel Fare</strong></td>
                            <td colspan="3" align="center" style="vertical-align: middle"><strong>MEALS</strong></td>
                            <td rowspan="2" align="center" style="vertical-align: middle"><strong>DA</strong></td>
                            <td rowspan="2" align="center" style="vertical-align: middle"><strong>Total</strong></td>
                        </tr>
                        <tr>
                            <td align="center" style="width:5%;vertical-align: middle"><strong>B</strong></td>
                            <td align="center" style="width:5%; vertical-align: middle"><strong>L</strong></td>
                            <td align="center" style="width:5%; vertical-align: middle"><strong>D</strong></td>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $res = mysqli_query($conn, 'select * from '.$table_details.' where '.$unique.'='.$_GET[$unique]);
                        while($data=mysqli_fetch_object($res)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td align="center" valign="top"><?=++$i?></td>
                                <td align="center" valign="top"><?=$data->travel_date;?></td>
                                <td align="center" valign="top"><?=$data->travel_from;?> - <?=$data->travel_to;?> <?php if($data->lodging_expense!=='') echo $data->lodging_expense ; ?></td>
                                <td align="center" valign="top"><?=$data->mode_of_transport;?></td>
                                <td align="center" valign="top"><?=$data->transport_fair;?></td>
                                <td align="center" valign="top"><?=$data->lodging_fair;?></td>
                                <td align="right" valign="top" style="width:5%"><?php if($data->breakfast>0) echo $data->breakfast; else echo '' ?></td>
                                <td align="right" valign="top" style="width:5%"> <?php if($data->lunch>0) echo $data->lunch; else echo '' ?></td>
                                <td align="right" valign="top" style="width:5%"> <?php if($data->dinner>0) echo $data->dinner; else echo '' ?> </td>
                                <td align="center" valign="top"> <?php echo $da; ?> </td>
                                <td align="right" valign="top"> <?php echo number_format(($suttotal=$data->total_amount),2) ?> </td>
                            </tr>
                            <?php
                            $suttotals=$suttotals+$suttotal;
                        } ?>
                        </tbody>
                        <tr style="font-weight:bold; font-size:11px"><td colspan="10" style="text-align:right">Sub Total:</td><td style="text-align:right"><?php echo number_format($suttotals,2); ?></td></tr>
                       <tr style="font-weight:bold; font-size:11px"><td colspan="10" style="text-align:right">Travel Advance (In BDT)</td><td style="text-align:right"><?=number_format(($datas->advance_amount),2) ?></td></tr>
                        <?php
                        $ddto=$datas->advance_amount-$suttotals;
                        if($ddto<0){
                            ?>
                            <tr style="font-weight:bold; font-size:11px"><td colspan="10" style="text-align:right">Total Receivable (In BDT)</td><td style="text-align:right"><?php echo substr(number_format($ddto,2),1); ?></td></tr>
                        <?php } else { ?>
                            <tr style="font-weight:bold; font-size:11px"><td colspan="10" style="text-align:right">Total Payable (In BDT)</td><td style="text-align:right"><?php echo number_format($ddto,2); ?></td></tr>
                        <?php } ?>
                    </table>
                    <?php mysqli_close($conn); ?>

                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>#</th>
                            <th style="width: 12%">For</th>
                            <th>Accounts Description</th>
                            <th style="text-align:center; width: 10%">CC Code</th>
                            <th style="text-align:center; width: 25%">Narration</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: center">1</td>
                            <td style="text-align: center; vertical-align: middle">Expenses Head</td>
                            <td>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_1" id="ledger_1">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $sales_return_ledger, '1'); ?>
                                </select>
                            </td>
                            <td>
                                <select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"   name="cc_code_1" id="cc_code_1">
                                    <option></option>
                                    <?php foreign_relation('cost_center', 'id', 'CONCAT(id,"-", center_name)', $cc_code_1, 'status in ("1")'); ?>
                                </select>
                            </td>

                            <td style="text-align: center"><input type="text" name="narration_1" id="narration_1" value="Travel Expenses Claim, Travel Purpose # <?=$datas->travel_purpose;?> , Req. No#<?=$_GET[$unique];?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td align="center"><input type="text" name="dr_amount_1" readonly value="<?=$suttotals;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td align="center"><input type="text" name="cr_amount_1" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center;vertical-align: middle">2</td>
                            <td style="text-align: center;vertical-align: middle">Cash / Advance Ledger</td>
                            <td>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_2"  name="ledger_2">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $dealer_info->account_code, '1'); ?>
                                </select></td>
                            <td style="text-align: center;vertical-align: middle">0</td>
                            <td style="text-align: center"><input type="text" name="narration_2" id="narration_2" value="Travel Expenses Claim, Travel Purpose # <?=$datas->travel_purpose;?> , Req. No#<?=$_GET[$unique];?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right"><input type="text" name="dr_amount_2" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_2" readonly value="<?=$suttotals;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        </tbody>
                    </table>



                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    $hrm_viewed=find_a_field(''.$table.'','hrm_viewed',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='APPROVED' && $hrm_viewed=='YES'){  ?>
                        <p>
                            <button style="float: right;" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Checked & Completed the Claim </button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Claim has not yet been Checked. Please wait until approval !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(!isset($_GET[$unique])){
if(isset($_POST[viewreport])){	
$res="Select 
t.trvClaim_id,
t.trvClaim_id as ClaimID,
t.application_date,
(SELECT concat(p2.PBI_NAME,\" # \",\"(\",de.DESG_SHORT_NAME,\")\") FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=t.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Claim_By,
							 t.Priority,
							 t.travel_purpose,
							 p.PBI_NAME as approved_by,
							 t.approved_date as approved_at,							 
							 SUM(d.total_amount) as amount					
from 
".$table." t,
personnel_basic_info p,
".$table_details." d
 where
  t.approved_by=p.PBI_ID and
  t.".$unique."=d.".$unique." and 
  t.application_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'  and t.hrm_viewed='YES' and t.accounts_viewed='YES' group by d.".$unique." order by t.".$unique." DESC ";
} else {
$res="Select t.trvClaim_id,
t.trvClaim_id as ClaimID,
t.application_date,
(SELECT concat(p2.PBI_NAME,\" # \",\"(\",de.DESG_SHORT_NAME,\")\") FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=t.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Claim_By,
							 t.Priority,
							 t.travel_purpose,
							 p.PBI_NAME as checked_by,
							 t.checked_at,							 
							 SUM(d.total_amount) as amount						
from 
".$table." t,
personnel_basic_info p,
".$table_details." d
 where
  t.PBI_ID=p.PBI_ID and
  t.".$unique."=d.".$unique." and 
  t.status in ('APPROVED') and t.hrm_viewed='YES' and t.accounts_viewed='' group by d.".$unique." order by t.".$unique." DESC ";	
}
	
	 ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <?php
                    $y=date('Y');
                    $m=date('m');
                    ?>
                    <input type="date" name="f_date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" name="t_date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Travel Expenses Claim</button></td>
            </tr></table>
            
<?=$crud->report_templates_with_data($res,$title);?>           
            
        </form>
<?php } ?>
<?=$html->footer_content();?>