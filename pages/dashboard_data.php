<?php
require_once 'support_file.php';
function accounts_session()
{
    $datefrom = date('Y-m-01');
    $dateto = date('Y-m-d');


    $_SESSION[todaycollection_accounts] = find_a_field('journal', 'SUM(cr_amt)', ' tr_from in ("Receipt") and  jvdate between "' . $dateto . '" and "' . $dateto . '" and section_id="' . $_SESSION[sectionid] . '" and company_id="' . $_SESSION[companyid] . '"');
    $_SESSION[collectionMDT_accounts] = find_a_field('journal', 'SUM(cr_amt)', ' tr_from in ("Receipt") and  jvdate between "' . $datefrom . '" and "' . $dateto . '" and section_id="' . $_SESSION[sectionid] . '" and company_id="' . $_SESSION[companyid] . '"');

    $_SESSION[todayshipment_accounts] = find_a_field('sale_do_details', 'SUM(total_amt)', ' do_type in ("sales","") and  do_date between "' . $dateto . '" and "' . $dateto . '"');
    $_SESSION[shipmentMDT_accounts] = find_a_field('sale_do_details', 'SUM(total_amt)', ' do_type in ("sales","") and  do_date between "' . $datefrom . '" and "' . $dateto . '"');

    $_SESSION[todayspurchase_accounts] = find_a_field('purchase_receive', 'SUM(amount)', 'rec_date between "' . $dateto . '" and "' . $dateto . '"');
    $_SESSION[purchaseMDT_accounts] = find_a_field('purchase_receive', 'SUM(amount)', 'rec_date between "' . $datefrom . '" and "' . $dateto . '"');

    $_SESSION[todayspurchaseST_accounts] = find_a_field('warehouse_other_receive_detail', 'SUM(amount)', 'or_date between "' . $dateto . '" and "' . $dateto . '"');
    $_SESSION[purchaseSTMDT_accounts] = find_a_field('warehouse_other_receive_detail', 'SUM(amount)', 'or_date between "' . $datefrom . '" and "' . $dateto . '"');

    //$res = mysql_query("SELECT COUNT(distinct a.ledger_id) as noofvendor, SUM(j.cr_amt-j.dr_amt) as outstanding, (select SUM(dr_amt) from journal where ledger_id=a.ledger_id and jvdate between \"' . $datefrom . '\" and \"' . $dateto . '\") as payment from accounts_ledger a, journal j where a.ledger_group_id in ('2002') and a.ledger_id=j.ledger_id");
    //$vendor = mysql_fetch_object($res);

    //$_SESSION[noofvendor] = $vendor->noofvendor;
    //$_SESSION[outstanding] = $vendor->outstanding;
    //$_SESSION[payment_this_month_account] = $vendor->payment;

    


}
accounts_session();

?>