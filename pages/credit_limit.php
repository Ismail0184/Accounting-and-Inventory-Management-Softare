<?php
require_once 'support_file.php';
$title='Dealer Credit Limit';

$now=time();
$unique='id';
$unique_field='fname';
$table="dealer_credit_limit_record";
$page="acc_dealer_credit_limit.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

$res="Select 
d.dealer_code as dcode,
concat(d.dealer_code,' : ' ,d.account_code) as account_code,
d.dealer_name_e as dealer_name,
d.dealer_type,
d.credit_limit,
d.credit_limit_time as credit_limit_duration
from 
dealer_info d ,
accounts_ledger a
 where 
 d.account_code=a.ledger_id and
 d.canceled in ('Yes') 
 group by d.account_code
 order by d.account_code";
?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>





<?php if(!isset($_GET[$unique])){ ?>
    <?=$crud->report_templates_with_title_and_class($res,$title,'12');?>
<?php } ?>
<?=$html->footer_content();?>