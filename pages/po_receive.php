<?php
require_once 'support_file.php';
require_once 'mod.php';
$tdates=date("Y-m-d");
$day = date('l', strtotime($idatess));
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$timess=$dateTime->format("d-m-y  h:i A");
$title='PO Receive';
$table_master='purchase_master';
$table_receive_master='purchase_receive_master';
$table_details='purchase_receive';
$unique='po_no';
$page='po_receive.php';
$journal_item="journal_item";
$journal_accounts="journal";
$$unique = $_GET[$unique];
$po_master=find_all_field(''.$table_master.'','',''.$unique.'='.$$unique.'');
$vendor_master=find_all_field('vendor','','vendor_id='.$po_master->vendor_id.'');
$config_group_class=find_all_field("config_group_class","","1");


if(prevent_multi_submit()){
	 if(isset($_POST['confirm'])){ 
	        $_POST['entry_by'] = $_SESSION['userid'];	
			$_POST['entry_at'] = date('Y-m-d H:s:i');
			$_POST[po_no]=$$unique;
			$_POST[rcv_Date]=$_POST[rec_date];		
			$rec_no=$_POST['rec_no'];
            $now = date('Y-m-d H:s:i');		
			$_POST[grn_inventory_type]='';	
			$crud      =new crud($table_receive_master);
            $crud->insert();	// GRN master
////////////////////////////////////////////////////////		
		$results="Select * from purchase_invoice  where ".$unique."=".$$unique."";
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)){
			$id=$row[id];
    		$GRN_Qty=$_POST['chalan_'.$id];
			if($GRN_Qty>0){
			$_POST['entry_by'] = $_SESSION['userid'];	
			$_POST['entry_at'] = date('Y-m-d H:s:i');
			$_POST[item_id]=$row[item_id];	
			$_POST[rate]=$row[rate];
			$_POST[qty]=$GRN_Qty;
			$_POST[amount]=$GRN_Qty*$row[rate];
			$_POST[order_no]=$row[id];
			$_POST[rcv_Date]=$_POST[rec_date];
			$_POST[status]='UNCHECKED';
			$_POST[mfg]=$_POST['mfg'.$id];
			$_POST[lot_number]=$_SESSION['POunique_id']; 
			$_POST[total_cost]=$_POST['transport_bill']+$_POST['labor_bill']+$_POST['others_bill'];			
			$crud      =new crud($table_details);
            $crud->insert();	// GRN received
//////////////////////////////////////////////////////////			
            $_POST['ji_date'] = $_POST['rec_date'];			
            $_POST['item_id'] = $row[item_id];
            $_POST['warehouse_id'] = $row[warehouse_id];
            $_POST['item_in'] = $GRN_Qty;
            $_POST['item_price'] = $row[rate];
            $_POST['total_amt'] = $GRN_Qty*$row[rate];;
            $_POST['tr_from'] = 'Purchase';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $_POST[pr_no];
            $total_amt=$GRN_Qty*$row[rate];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            //$crud->insert();   // inventory received
                $total_amount=$total_amount+$total_amt;
			}}


         // MAN status update
         $mup=mysqli_query($conn, "Update MAN_details SET MAN_RCV_STATUS='Done' where m_id='$_GET[m_id]' and po_no='$_GET[po_no]'");



///////////// accounts journal start from here			
			
        $jv=next_journal_sec_voucher_id(); 
        $get_tax_ait=$_POST[tax_ait];
        $pr_amt  = $total_amount;

//// service charge calculation
        $get_ASFS=$po_master->asf;
        if($get_ASFS>0){
            $get_ASF=$get_ASFS;
        } else {
            $get_ASF=$_POST[asf];
        }
        if($get_ASF>0){
            $asf_amt=($pr_amt*$get_ASF)/100;
        } else {
            $asf_amt=0;
        }
        $sub_tot_amount=$pr_amt+$asf_amt;
/// end of ASF charge here		
		
		

////////////////////// ledger GET//////////////

        if($_POST[legderid]>0){
            $purchase_ledger=$_POST[legderid]; } else {
            $purchase_ledger = $config_group_class->purchase_ledger;
        }
        if($_POST[tax_ait]>0){
            $tax_ait=($sub_tot_amount*$_POST[tax_ait])/100;
            $tax_ait_ledger="2004000500000000";
        }

        if($_POST[tax]<15){
            $tax_ledgercr =   '2004000200000000';
            $tax_amtcr = (($sub_tot_amount*$_POST[tax])/100);
        }else{
            $tax_ledger =   '1005000400000000';
            $tax_amt = (($sub_tot_amount*$_POST[tax])/100);
        }
        if($_POST[other_cost_accounts_head]>0){
            $others_costsss=$_POST['others_costsss'];
            $other_cost_accounts_head=$_POST['other_cost_accounts_head'];
        }
		$vendor_ledger=$vendor_master->ledger_id;		
		$dd = $_POST[rec_date];
        $date = date('d-m-y', strtotime($dd));
        $j = 0;
        for ($i = 0; $i < strlen($date); $i++) {
            if (is_numeric($date[$i])) {
                $time[$j] = $time[$j] . $date[$i];
            } else {
                $j++;
            }
        }
        $date = mktime(0, 0, 0, $time[1], $time[0], $time[2]);
		$nerration='GR#'.$_POST[pr_no].'/(PO#'.$$unique.'), Chalan No # '.$_POST[ch_no].',Remarks # '. $_POST[remarks].', ';
		
	
	/////////////// purchase amount calculations
	$pr_amt = $pr_amt+$asf_amt;

    if($tax_amt>0){
        $purchaseamt=$pr_amt;
    }
    elseif($tax_amtcr>0){
        $purchaseamt=$pr_amt+$tax_amtcr;
    }
    else
    {
        $purchaseamt=$pr_amt;
    }
				

		if($tax_amt>0){
        $vendoramount=$pr_amt+$tax_amt+$tax_ait;
    }
    elseif($tax_amtcr>0){
        $vendoramount=$pr_amt+$tax_ait;
    } else {$vendoramount=$pr_amt;}
		
		if($purchaseamt>0){
		insert_into_secondary_journal($_POST['rec_date'], $proj_id, $jv, $date, $purchase_ledger, $nerration, $purchaseamt, 0, Purchase, $_POST[pr_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Regular);
		if($tax_ait>0){
		$nerration_tax=$nerration.', Tax # '.$get_tax_ait.' %';	
		insert_into_secondary_journal($_POST['rec_date'], $proj_id, $jv, $date, $tax_ait_ledger, $nerration_tax, $tax_ait, 0, Purchase, $_POST[pr_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Regular);			
		}
		if($tax_amt>0){
		$nerration_VAT=$nerration.', VAT # '.$_POST[tax].'%, VAT Date # '.$_POST[VAT_challan_Date];
		insert_into_secondary_journal($_POST['rec_date'], $proj_id, $jv, $date, $tax_ledger, $nerration_VAT, $tax_amt, 0, Purchase, $_POST[pr_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Regular);			
		}
		if($tax_amtcr>0){
			$nerration_VAT=$nerration.', VAT # '.$_POST[tax].'%, VAT Date # '.$_POSR[VAT_challan_Date];
		 insert_into_secondary_journal($_POST['rec_date'], $proj_id, $jv, $date, $tax_ledgercr, $nerration_VAT, 0, $tax_amtcr, Purchase, $_POST[pr_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Regular);	
		}
        insert_into_secondary_journal($_POST['rec_date'], $proj_id, $jv, $date, $vendor_ledger, $nerration, 0, $vendoramount, Purchase, $_POST[pr_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Regular);
		}
		
    header("Location: ".$page."?jv_no=".$jv."");
    }
	
	 if (isset($_POST['final_confirm'])) {
		 $res=mysqli_query($conn, "SELECT sj.* from secondary_journal sj where jv_no=".$_GET[jv_no]."");
						while($data=mysqli_fetch_object($res)){
							$update_ledger_id=$_POST['ledger_id'.$data->id];
							$update_narration=$_POST['narration'.$data->id];
        mysqli_query($conn, "UPDATE secondary_journal SET ledger_id='".$update_ledger_id."',narration='".$update_narration."' where jv_no=".$_GET[jv_no]." and id=".$data->id."");
						}
        unset($_POST);
        $type = 1;
        echo "<script>window.close(); </script>";
    }
	
	} else{  
	$type=0;
	//echo '<script>alert("Data Re-Submit Warning!");<script>';

}



if($$unique>0)
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table_master,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


if($delivery_within>0)
{
    $ex = strtotime($po_date) + (($delivery_within)*24*60*60)+(12*60*60);
}

$sql='select a.id,a.item_id,b.item_name,a.item_details,b.unit_name,a.qty,a.rate from purchase_invoice a,item_info b where b.item_id=a.item_id and a.po_no='.$$unique;
$MAN_master=find_all_field('MAN_details','','status="VERIFIED" and m_id="'.$_GET[m_id].'" and po_no='.$_GET[po_no]);
$MAN=find_all_field('MAN_master','','id="'.$_GET[m_id].'"'); ?>



<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.m_id.options[form.m_id.options.selectedIndex].value;
	self.location='<?=$page;?>?po_no=<?=$_GET[po_no]?>&m_id=' + val ;
}


</script>
<style>
td {
  padding: 3px;
}

</style>
<style>
        #customers {}
        #customers td {}
        #customers tr:ntd-child(even)
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: #f5f5f5;}
        td{}
		th {
  padding: 3px; vertical-align:middle
}
    </style>

<?php if(isset($_GET[$unique]) || ($_GET[jv_no])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>


<form action="" method="post" name="codz" id="codz" onSubmit="if(!confirm('Are You Sure Execute this?')){return false;}" class="form-horizontal form-label-left">
<?php if($_GET[jv_no]) { ?> 

<div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Accounting Journal for the GRN</h2>
                <div class="clearfix"></div>
            </div>

<div class="x_content">
 <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>#</th>
                            <th>Accounts Description</th>
                            <th style="text-align:center; width: 25%">Narration</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						$res=mysqli_query($conn, "SELECT sj.* from secondary_journal sj where jv_no=".$_GET[jv_no]."");
						while($data=mysqli_fetch_object($res)){?>                        
                        <tr>
                            <td style="text-align: center"><?=$i=$i+1;?></td>
                            <td><?$sales_return_ledger=$config_group_class->sales_return;?>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_id<?=$data->id;?>"  name="ledger_id<?=$data->id;?>">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $data->ledger_id, '1'); ?>
                                </select>
                            </td>

                            <td style="text-align: center"><input type="text" name="narration<?=$data->id;?>" id="narration<?=$data->id;?>" value="<?=$data->narration;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td align="center"><input type="text" name="dr_amount<?=$data->id;?>" readonly value="<?=$data->dr_amt;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td align="center"><input type="text" name="cr_amount<?=$data->id;?>" readonly value="<?=$data->cr_amt;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    
                    <p><button type="submit"  name="final_confirm" style="font-size:12px; float:right" class="btn btn-primary">Confirm All Journal and Send to Accounts</button>
</p>
		</div> </div></div>

<?php } else  { ?>


<input type="hidden" name="pr_no" id="pr_no" value="<?=find_a_field('purchase_receive','max(pr_no)','1')+1;?>">
<input type="hidden" name="legderid" value="<?=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$warehouse_id)?>" >

    <?php require_once 'support_html.php';?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?=$title;?></h2>
                <div class="clearfix"></div>
            </div>

<div class="x_content">
 <table  style="font-size: 11px; width:100%">
    <tr>
    <th style="width:12%">PO NO</th>
    <th style="width:1%"> : </th>
    <td style="width:20%"><input style="width:90%" name="po_no" type="text" id="po_no" value="<?=$po_no;?>" readonly /></td>
    
    <th style="width:12%">PO Date</th>
    <th style="width:1%"> : </th>
    <td style="width:20%"><input style="width:90%" name="po_date" type="date"  value="<?=$po_date;?>" readonly /></td>
    
    <th style="width:12%">Delivery Within</th>
    <th style="width:1%"> : </th>
    <td style="width:20%"><input style="width:90%" name="delivery_within" type="date"  value="<?=$delivery_within;?>" readonly /></td>
    </tr>  
    <tr>
    <th>Warehouse</th>
    <th> : </th>
    <td><select style=" height:23px; width:90%" name="warehouse_id" id="warehouse_id">
                <option></option>
                <? foreign_relation('warehouse','warehouse_id','warehouse_name',$warehouse_id);?>
    </select></td>
    
    <th>PO Remarks</th>
    <th> : </th>
    <td><input style="width:90%" name="po_details" type="text" id="po_details" value="<?=$po_details;?>" readonly /></td>
    
    <th>Entry By</th>
    <th> : </th>
    <td><input style="width:90%" name="entry_by" type="text" id="entry_by" value="<?=find_a_field('user_activity_management','fname','user_id='.$entry_by.'')?>" readonly /></td>
    </tr>
    
    
    
    <tr>
    <th>Vendor</th>
    <th> : </th>
    <td><select style=" height:23px; width:90%" name="vendor_id" id="vendor_id">
                <? foreign_relation('vendor','vendor_id','vendor_name',$vendor_id,'vendor_id='.$vendor_id.'');?>
    </select></td>
    
    <th>Vendor CP</th>
    <th> : </th>
    <td><input style="width:90%" name="po_details" type="text" id="po_details" value="<?=find_a_field('vendor','concat(contact_person_name, ": ",contact_person_mobile)','vendor_id='.$vendor_id.'')?>" readonly /></td>
    
    <th>Entry On</th>
    <th> : </th>
    <td><input style="width:90%" name="entry_by" type="text" id="entry_by" value="<?=$entry_at;?>" readonly /></td>
    </tr>
          
    </table>
		</div> </div></div>




<table width="98%"  border="0" align="center"  style="font-size: 11px; background-color: blanchedalmond; padding:20px">
 <tr>
    
    
    <th>MAN</th>
    <th style="width:1%"> : </th>
    <td><select style=" height:23px; width:90%" name="m_id" id="m_id" onchange="javascript:reload(this.form)">
                <option></option>
                <? foreign_relation('MAN_details','distinct m_id','concat(m_id,":", MAN_ID)',''.$_GET[m_id].'','po_no='.$_GET[po_no].' and MAN_RCV_STATUS!="Done" and  status="VERIFIED"');?>
               </select>
        <input type="hidden" name="rec_no" id="rec_no" value="<?=$MAN_master->MAN_ID;?>">
        <input type="hidden" name="MAN_ID" id="MAN_ID" value="<?=$MAN_master->MAN_ID;?>">
    </td>
        
    <th>Rcv. Date</th>
    <th style="width:1%"> : </th>
    <td><input style="width:90%" name="rec_date" MAX="<?=date('Y-m-d')?>" type="date"  value="<?=$MAN->man_received_date;?>"  required="required" /></td>
    <th>VAT Date</th>
    <th style="width:1%"> : </th>
    <td><input style="width:90%"  name="VAT_challan_Date" MAX="<?=date('Y-m-d')?>" type="date"  value="<?=$MAN_master->VAT_challan_Date;?>" /></td>
    
    <th>Challan Date</th>
    <th style="width:1%"> : </th>
    <td><input style="width:90%" name="ch_date" type="date" MAX="<?=date('Y-m-d')?>"  value="<?=$MAN_master->delivary_challan_Date;?>" required="required" /></td>
    </tr>
    </table>
    <table width="98%"  border="0" align="center"  style="font-size: 11px; background-color: blanchedalmond; margin-top:5px">
    <tr>
    <td><input style="width:90%" name="tax_ait" type="text"  value="<? if($tax_ait>0) echo $tax_ait;?>" title="Tax" placeholder="Tax (%)" /></td>
    <td><input style="width:90%" name="tax" type="text"  value="<? if($tax>0) echo $tax;?>" title="VAT" placeholder=" VAT (%)" required="required" /></td>       
    <td><input style="width:90%;"  name="VAT_challan" type="text" id="VAT_challan" value="<?=find_a_field('MAN_details','VAT_challan','status="VERIFIED" and m_id="'.$_GET['m_id'].'" and po_no='.$_GET[po_no]); ?>" title="VAT Challan No" placeholder="VAT Challan No" required="required"/></td>
    
    <td><input style="width:90%" name="asf" type="twxt"  value="<? if($asf>0) echo $asf;?>" title="ASF" placeholder="ASF (%)" /></td>
    <td><input style="width:90%" name="ch_no" type="text" title="Chalan No" placeholder="Chalan No"  value="<?=$MAN_master->delivary_challan;?>" required="required" /></td> </tr>

    <tr>
    <td><input style="width:90%"  name="transport_bill" type="text"  value="<? if($transport_bill>0) echo $transport_bill;?>" placeholder="Transport Bill" /></td>
    <td><input style="width:90%"  name="labor_bill" type="text"  value="<? if($labor_bill>0) echo $labor_bill;?>" placeholder="Labour Bill" /></td>
    <td><input style="width:90%" name="otc" type="text"  placeholder="Other Cost" /></td>
    <td><textarea style="width:90%;padding-left:5px;padding-top:2px; height:22px"  name="remarks" placeholder="remarks"  type="text" ></textarea></td>
    <td><input style="width:90%"  type="text"  value="<?=find_a_field('users','fname','user_id='.$MAN->check_by);?>"  /></td>
    </tr>
    
    </table><br>


       <? if($$unique>0){       
       $res=mysqli_query($conn, $sql);
	   ?>
      <table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
      <thead>
          <tr style="background-color: blanchedalmond">
            <th>SL</th>
            <th>Item Code</th>
            <th>Item Name</th>
            <th>Unit</th>
            <th>Ordered</th>
            <th>Recd </th>
            <th>UnRecd </th>
            <th>RecQty </th>            
            <th>No. of Pack</th>            
            <!--th style="width:10%">MFG/ Warranty</th-->
          </tr>
          </thead>


          <? while($row=mysqli_fetch_object($res)){
			 $MAN_details = find_all_field('MAN_details','','status="VERIFIED" and po_no="'.$_GET[po_no].'" and m_id="'.$_GET[m_id].'" and item_id="'.$row->item_id.'"');
			  $bg++?>
          <tr>
            <td><?=++$ss;?></td>
           <input type="hidden" style="width:70px" name="lot_number<?=$row->id?>" id="lot_number<?=$row->id?>" value="<?=$_SESSION['POunique_id']++;?>" readonly />
            <td><?=$row->item_id?>
              <input type="hidden" name="item_id_<?=$row->id?>" id="item_id_<?=$row->id?>" value="<?=$row->item_id?>" /></td>
              <td><?=$row->item_name?> # <?=$row->item_details; ?>
              <input type="hidden" name="rate_<?=$row->id?>" id="rate_<?=$row->id?>" value="<?=$row->rate?>" /></td>
              
              <td width="7%" align="center" style="text-align:center"><?=$row->unit_name?>
              <input type="hidden" name="unit_name_<?=$row->id?>" id="unit_name_<?=$row->id?>" value="<?=$row->unit_name?>" /></td>
              <td width="7%" align="center" style="text-align:center"><?=$row->qty?></td>
              <td width="6%" align="center" style="text-align:center"><? echo $rec_qty = (find_a_field('purchase_receive','sum(qty)','order_no="'.$row->id.'" and item_id="'.$row->item_id.'"')*(1));?></td>
              <td width="7%" align="center" style="text-align:center"><? echo $unrec_qty=($row->qty-$rec_qty);?>
              <input type="hidden" name="unrec_qty_<?=$row->id?>" id="unrec_qty_<?=$row->id?>" value="<?=$unrec_qty?>" /></td>             
            
              <td width="5%" align="center" bgcolor="#6699FF" style="text-align:center">
			  <? if($unrec_qty>0){$cow++;?>
                <input name="chalan_<?=$row->id?>" onkeyup="doAlert<?=$row->id?>(this.form);" type="text" id="chalan_<?=$row->id?>" style="width:70px; float:none" value="<?php if($MAN_details->qty<=$row->qty) echo $MAN_details->qty; else echo $row->qty;?>"  />
                <? } else echo 'Done';?></td>
                <SCRIPT language=JavaScript>
            function doAlert<?=$row->id?>(form)
            {
                var val=form.chalan_<?=$row->id?>.value;
                var val2=form.unrec_qty_<?=$row->id?>.value;
                if (Number(val)>Number(val2)){
                    alert('Oops !! Exceeded the receive limit !! Thanks');
                    form.chalan_<?=$row->id?>.value='';
                }
                form.chalan_<?=$row->id?>.focus();
            }</script>
            
            <td align="center"><input type="text" style="width:50px; text-align:center"  value="<?=$noofpack = find_a_field('MAN_details','no_of_pack','status="VERIFIED" and po_no="'.$_GET[po_no].'" and MAN_ID="'.$_GET[MAN_ID].'" and item_id="'.$row->item_id.'"');?>" name="of_no_pack<?=$row->id?>" id="of_no_pack<?=$row->id?>"  /></td>                
                <!--td><input type="date" style="" min="<?=date('Y-m-d');?>" name="mfg<?=$row->id?>" id="mfg<?=$row->id?>" value="<?=$MAN_details->mfg;?>" /></td-->
              </tr>
          <? }?>
      </tbody>
      </table>
      </div>
      </td>
    </tr>
  </table>


    <br />

<table width="100%" border="0">
<? if($cow<1){
$vars['status']='COMPLETED';
db_update($table_master, $po_no, $vars, 'po_no');
?>
<tr>
<td colspan="2" align="center" style="color: red; font-size: 10px; font-style: italic"><strong>THIS PURCHASE ORDER IS COMPLETED</strong></td>
</tr>
<? }else{?>

    <? if($ex<time()){?>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FF0000">
            <tr>
                <td align="right" bgcolor="#FF0000"><div align="center" style="text-decoration:blink"><strong>THIS PURCHASE ORDER IS EXPIRED</strong></div></td>
            </tr></table>
    <? } else {?>

<tr>
<td align="center">
<?php
$GET_status=find_a_field(''.$table_master.'','status',''.$unique.'='.$_GET[$unique]);
if($GET_status=='PROCESSING'){  ?>
    <button type="submit" name="delete" style="font-size:12px; float:left" class="btn btn-danger" onclick="window.location = 'select_dealer_chalan111.php?del=1&po_no=<?=$po_no?>';">CANCEL PURCHASE ORDER</button>
<input  name="vendor_id" type="hidden" id="vendor_id" value="<?=$vendor_id;?>"/></td>
<td align="center"> <button type="submit" name="confirm" style="font-size:12px; float:right" class="btn btn-primary">Received Goods</button>
</td>
<? } else {echo '<h6 style="color:red; font-weight:bold"><i>This purchase order has not yet been approved. Wait until approval !!</i></h6>';} ?>


</tr>
<? }}?>
</table>
<? }}?>
</form>
<?=$html->footer_content();?> 