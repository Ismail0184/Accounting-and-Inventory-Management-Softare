<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Asset Purchase Report';
$now=time();
$unique='po_no';
$unique_field='po_date';
$table="purchase_master";
$table_details="purchase_invoice";
$journal_item="journal_item";
$journal_accounts="journal";
$page='po_receive_asset.php';
$print_page='po_print_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if (isset($_POST['reprocess'])) {

        $_POST['status'] = 'MANUAL';
        $crud->update($table);
        $_SESSION['initiate_hrm_stationary_purchase'] = $_GET[$unique];
        $type = 1;
        echo "<script>self.opener.location = 'hrm_stationary_purchase.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
         
	
		 $results=mysqli_query($conn,"Select srd.*,i.*,ar.*,d.DEPT_SHORT_NAME as department 
						from 
						purchase_invoice_asset srd, item_info i, asset_register ar, department d  where
 srd.item_id=i.item_id and ar.item_id=i.item_id and d.DEPT_ID=ar.DEPT_ID and 
 srd.".$unique."=".$_GET[$unique]." order by srd.id");
                        while($row=mysqli_fetch_array($results)){
							$ids=$row[id];
            $_POST['ji_date'] = $ji_date;
            $_POST['item_id'] = $row[item_id];
			$_POST['asset_id'] = $row[asset_id];
            $_POST['warehouse_id'] = $row[warehouse_id];
            $_POST['item_in'] = $row[total_qty];
            $_POST['item_price'] = $row[unit_price];
            $_POST['total_amt'] = $row[total_amt];
            $_POST['tr_from'] = 'Asset_Purchase';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $row[id];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();
        }

        $jv=next_journal_voucher_id();
        $transaction_date=date('Y-m-d');
        $enat=date('Y-m-d h:s:i');
        $cd =$_POST[c_date];
        $c_date=date('Y-m-d' , strtotime($cd));
        $invoice=$_POST[invoice];
        $date=date('d-m-y' , strtotime($transaction_date));
        $narration='Purchase, PO#'.$$unique;
        $j=0;
        for($i=0;$i<strlen($date);$i++)
        {
            if(is_numeric($date[$i]))
            { $time[$j]=$time[$j].$date[$i];
            } else {
                $j++; } }
        $date=mktime(0,0,0,$time[1],$time[0],$time[2]);
        add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], $_POST[cr_amount_1], Purchase, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_2], $_POST[dr_amount_2], $_POST[cr_amount_2], Purchase, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);

        $up_master="UPDATE ".$table." SET status='COMPLETED' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_deatils." SET status='COMPLETED' where ".$unique."=".$$unique."";
        $update_table_details=mysqli_query($conn, $up_details);
        $type=1;
        unset($_POST);
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$vendor_info=find_all_field("vendor","","vendor_id=".$vendor_id."");
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php require_once 'body_content.php';  ?>



<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Asset Purchased</button></td>
            </tr></table>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                
                <table class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                                    <thead>
                                    <tr style="background-color: bisque">
                                        <th>SL</th>
                                        <th>Po</th>
                                        <th>Date</th>
                                        <th>Vendor / Buyer Name</th>
                                        <th>Entry By</th>
                                        <th>Checked By</th>
                                        <th>Recommended By</th>
                                        <th>Authorised By</th>
                                        <th>Status</th>
                                        <th>Print</th>
                                    </tr>
                                    </thead>


                                    <tbody>
                                <?
                                if(isset($_POST['viewreport'])){                       
                                $res=mysqli_query($conn, 'select  a.po_no,a.po_no, a.po_date, v.vendor_name,a.work_order_for_department as                                Department,c.fname as Entryby,a.status,a.entry_at,a.recommended_date,a.authorized_date,a.checkby_date,
								(select PBI_NAME from personnel_basic_info where PBI_ID=a.checkby) as checkby,
								(select PBI_NAME from personnel_basic_info where PBI_ID=a.recommended) as recommendedby,
								(select PBI_NAME from personnel_basic_info where PBI_ID=a.authorise) as authorizedby
								from 
								purchase_master a,
								warehouse b,
								users c, 
								vendor v 
								where  
								a.warehouse_id=b.warehouse_id and 
								a.entry_by=c.user_id and 
								a.vendor_id=v.vendor_id and 							
				                a.po_type in ("Asset") and a.po_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'"
								order by a.po_no DESC');
                               } else {
                               
                                $res=mysqli_query($conn, 'select  a.po_no,a.po_no, a.po_date, v.vendor_name,a.work_order_for_department as Department,c.fname as Entryby,a.status,a.entry_at,a.recommended_date,a.authorized_date,a.checkby_date,
								(select PBI_NAME from personnel_basic_info where PBI_ID=a.checkby) as checkby,
								(select PBI_NAME from personnel_basic_info where PBI_ID=a.recommended) as recommendedby,
								(select PBI_NAME from personnel_basic_info where PBI_ID=a.authorise) as authorizedby
								from 
								purchase_master a,
								warehouse b,
								users c, 
								vendor v 
								where  
								a.warehouse_id=b.warehouse_id and 
								a.entry_by=c.user_id and 
								a.vendor_id=v.vendor_id and 
								a.status="PROCESSING" and 							
				                a.po_type in ("Asset")
								order by a.po_no DESC');
                                }
                                    while($data=mysqli_fetch_object($res)){
                                         ?>
                                 <tr style="cursor:pointer">
                                     <td><?=$i=$i+1;?></td>
                                     <td><a href="../page/po_documents/qoutationDoc/<?=$data->$unique.'.pdf';?>" target="_blank" style="color:#06F" title="Quotation Attached"><u><strong><?=$data->po_no;?></strong></u></a></td>
                                     <td style="width:8%"><a href="../page/po_documents/mailCommDoc/<?=$data->$unique.'.pdf';?>" target="_blank" style="color:#06F" title="Email Conversation Attached"><u><strong><?=$data->po_date;?></strong></u></a></td>
                                     <td onclick="DoNavPOPUP('<?=$data->po_no;?>', 'TEST!?', 900, 600);"><?=$data->vendor_name;?></td>
                                     <td onclick="DoNavPOPUP('<?=$data->po_no;?>', 'TEST!?', 900, 600);"><?=$data->Entryby;?><br><?=$data->entry_at;?></td>
                                     <td onclick="DoNavPOPUP('<?=$data->po_no;?>', 'TEST!?', 900, 600);"><?=$data->checkby;?><br><?=$data->checkby_date;?></td>
                                     <td onclick="DoNavPOPUP('<?=$data->po_no;?>', 'TEST!?', 900, 600);"><?=$data->recommendedby;?><br><?=$data->recommended_date;?></td>
                                     <td onclick="DoNavPOPUP('<?=$data->po_no;?>', 'TEST!?', 900, 600);"><?=$data->authorizedby;?><br><?=$data->authorized_date;?></td>
                                     <td onclick="DoNavPOPUP('<?=$data->po_no;?>', 'TEST!?', 900, 600);"><?php if($data->status=='PROCESSING'){ echo "Not received"; } else {echo $data->status;}?> </td>
                                     <td style="text-align: center; vertical-align: middle"><a target="_blank" href="<?=$print_page;?>?<?=$unique;?>=<?=$data->po_no;?>"><img src="http://icpbd-erp.com/51816/warehouse_mod/images/print.png" width="20" height="20" /></a></td>
                                 </tr>
                               <?php }  ?>
                                </tbody>
                                </table>
                
              </div></div></div></form>
<?php } ?>

<?=$html->footer_content();mysqli_close($conn);?>