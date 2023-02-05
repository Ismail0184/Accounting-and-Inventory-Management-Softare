<?

session_start();
/////////////////////////////////////////////////////////////////
///////////////////// VOUCHER FUNCTIONS /////////////////////////
/////////////////////////////////////////////////////////////////




function auto_insert_purchase($jv,$pdate,$vendor_ledger,$purchase_ledger,$order_no,$amt,$po_no,$tr_no){

    $pdate=date_2_stamp_add_mon_duration($pdate);
//insert to bdt sales acc credit
    $journal="INSERT INTO `journal` (
`jv_no` ,
`jv_date` ,
`ledger_id` ,
`narration` ,
`dr_amt` ,
`cr_amt` ,
`tr_from` ,
`tr_no`,
group_for,`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`
)
VALUES ('$jv', '$pdate', '$vendor_ledger', 'Pr No# $po_no (Order No# $order_no)', '0', '$amt', 'Purchase',$tr_no,'".$_SESSION['usergroup']."','$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear'')";
    mysql_query($journal);

    $journal="INSERT INTO `journal` (
`jv_no` ,
`jv_date` ,
`ledger_id` ,
`narration` ,
`dr_amt` ,
`cr_amt` ,
`tr_from` ,
`tr_no`,
group_for,
`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`
)
VALUES ( '$jv', '$pdate', '$purchase_ledger', 'Po No# $po_no (Order No# $order_no)', '$amt', '0', 'Purchase','$tr_no','".$_SESSION['usergroup']."','$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear')";
    mysql_query($journal);
}

function auto_insert_purchase_secoundary($jv,$pdate,$vendor_ledger,$purchase_ledger,$pr_no,$amts,$po_no,$tr_id,$tax_amt,$tax_amtcr,$tax_ledger,$tax_ledgercr,$tax_ait,$tax_ait_ledger,$asf_amt,$ip,$create_date,$now,$day,$thisday,$thismonth,$thisyear,$rec_date,$group_for='',$user_id='',$entry_at=''){

    $pdate=date_2_stamp_add_mon_duration($pdate);

    if($group_for==0) $group_for = $_SESSION['usergroup'];
    if($user_id==0) $user_id = $_SESSION['userid'];
    if($entry_at==0) $entry_at = date('Y-m-d H:s:i');

//$amt = $amts + $tax_amt;
    $amt = $amts+$asf_amt;

    if($tax_amt>0){
        $purchaseamt=$amt;
    }
    elseif($tax_amtcr>0){
        $purchaseamt=$amt+$tax_amtcr;
    }
    else
    {
        $purchaseamt=$amt;
    }
    $journal="INSERT INTO `secondary_journal` (
`jv_no` ,
`jv_date` ,
`ledger_id` ,
`narration` ,
`dr_amt` ,
`cr_amt` ,
`tr_from` ,
`tr_no`,
`tr_id`,
group_for,
entry_at,
user_id,
`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`,`jvdate`
)
VALUES ( '$jv', '$pdate', '$purchase_ledger', 'GR#$pr_no/$tr_id(PO#$po_no)', ($purchaseamt), '0', 'Purchase',$pr_no,$tr_id,'$group_for','$entry_at','$user_id','$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear',$rec_date)";
    mysql_query($journal);




////////////////////////////////////////////////////// VAT Current Account
    if($tax_amt>0){
        $journal="INSERT INTO `secondary_journal` (
`jv_no` ,
`jv_date` ,
`ledger_id` ,
`narration` ,
`dr_amt` ,
`cr_amt` ,
`tr_from` ,
`tr_no`,
`tr_id`,
group_for,
entry_at,
user_id,`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`,`jvdate`
)
VALUES ( '$jv', '$pdate', '$tax_ledger', 'GR#$pr_no/$tr_id(PO#$po_no)',  '$tax_amt', '0','Purchase',$pr_no,$tr_id,'$group_for','$entry_at','$user_id','$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear',$rec_date)";
        mysql_query($journal);
////////////////////////////////// end of VAT
    }



////////////////////////////////////////////////////// VAT deducted at source from vendor payment
    if($tax_amtcr>0){
        $journal="INSERT INTO `secondary_journal` (
`jv_no` ,
`jv_date` ,
`ledger_id` ,
`narration` ,
`dr_amt` ,
`cr_amt` ,
`tr_from` ,
`tr_no`,
`tr_id`,
group_for,
entry_at,
user_id,`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`,`jvdate`
)
VALUES ( '$jv', '$pdate', '$tax_ledgercr', 'GR#$pr_no/$tr_id(PO#$po_no)',  '0', '$tax_amtcr','Purchase',$pr_no,$tr_id,'$group_for','$entry_at','$user_id','$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear',$rec_date)";
        mysql_query($journal);
////////////////////////////////// end of VAT
    }



////////////////////////////////////////////////////// AIT/TAX deducted at source from vendor payment
    if($tax_ait>0){
        $journal="INSERT INTO `secondary_journal` (
`jv_no` ,
`jv_date` ,
`ledger_id` ,
`narration` ,
`dr_amt` ,
`cr_amt` ,
`tr_from` ,
`tr_no`,
`tr_id`,
group_for,
entry_at,
user_id,`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`,`jvdate`
)
VALUES ( '$jv', '$pdate', '$tax_ait_ledger', 'GR#$pr_no/$tr_id(PO#$po_no)',  '$tax_ait', '0','Purchase',$pr_no,$tr_id,'$group_for','$entry_at','$user_id','$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear',$rec_date)";
        mysql_query($journal);
////////////////////////////////// end of VAT
    }

    if($tax_amt>0){
        $vendoramount=$amt+$tax_amt+$tax_ait;
    }
    elseif($tax_amtcr>0){
        $vendoramount=$amt+$tax_ait;
    } else {$vendoramount=$amt;}


    $journal="INSERT INTO `secondary_journal` (
`jv_no` ,
`jv_date` ,
`ledger_id` ,
`narration` ,
`dr_amt` ,
`cr_amt` ,
`tr_from` ,
`tr_no`,
`tr_id`,
group_for,
entry_at,
user_id,`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`,`jvdate`
)
VALUES ('$jv', '$pdate', '$vendor_ledger', 'GR#$pr_no/$tr_id(PO#$po_no)', '0', ($vendoramount), 'Purchase',$pr_no,$tr_id,'$group_for','$entry_at','$user_id','$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear',$rec_date)";
    mysql_query($journal);
}





function next_journal_sec_voucher_id($date='')
{
    if($date==''){
        $min = date("Ymd")."0000";
        $max = $min+10000;
    }
    else
    {
        $min = date("Ymd",strtotime($date))."0000";
        $max = $min+10000;
    }
    $s ="select MAX(jv_no) jv_no from secondary_journal where jv_no between '".$min."' and '".$max."'";
    $jv_no=@mysql_fetch_row(mysql_query($s));

    if($jv_no[0]>$min)
        $jv=$jv_no[0]+1;
    else
        $jv=$min+1;

    return $jv;
}








function journal_item_control($item_id ,$warehouse_id,$ji_date,$item_in,$item_ex,$tr_from,$tr_no,$rate='',$r_warehouse='',$sr_no='',$po_no,$lot_number,$ip)
{
    $pre_stock=find_all_field('journal_item','final_stock','warehouse_id = "'.$warehouse_id.'" and item_id = "'.$item_id .'" order by id desc');
    $final_stock=($pre_stock->final_stock+$item_in)-$item_ex;

    if(($tr_from == 'Purchase')||($tr_from == 'Other Receive')||($tr_from == 'Local Purchase')||($tr_from == 'Sample Receive'))
    {
        $item_price = $rate;
        $final_price = ((($pre_stock->final_price*$pre_stock->final_stock)+($item_price*$item_in))/($pre_stock->final_stock+$item_in));
    }
    else
    {
        $item_price = find_a_field('item_info','cost_price','item_id='.$item_id);
        $final_price = $item_price;
        if($rate!=''){
            $item_price = $final_price = $rate;
        }
    }
    $sql="INSERT INTO `journal_item` 
	(`ji_date`, `item_id`, `warehouse_id`, `pre_stock`, `pre_price`, `item_in`, `item_ex`, `item_price`, `final_stock`, `final_price`,`tr_from`, `tr_no`, `entry_by`, `entry_at`,relevant_warehouse,sr_no,ip,section_id,company_id,po_no,lot_number) 
	VALUES 
	('".$ji_date."', '".$item_id."', '".$warehouse_id."', '".$pre_stock->final_stock."', '".$item_price."', '".$item_in."', '".$item_ex."', '".$item_price."', '".$final_stock."', '".$final_price."', '".$tr_from."', '".$tr_no."', '".$_SESSION['userid']."', '".date('Y-m-d h:i:s')."', '".$r_warehouse."','".$sr_no."','".$ip."','".$_SESSION[sectionid]."','".$_SESSION[companyid]."','".$po_no."','".$lot_number."')";
    mysql_query($sql);
}


function date_2_stamp_add_mon_duration($date,$duration='')
{
    $j=0;
    for($i=0;$i<strlen($date);$i++)
    {
        if(is_numeric($date[$i]))
            $time[$j]=$time[$j].$date[$i];
        else $j++;
    }
    $stamp_time=mktime(0,0,0,($time[1]+$duration),$time[2],$time[0]);
    return $stamp_time;
}

?>