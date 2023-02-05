<?php
require_once 'support_file.php';
$title='Create Credit Limit Request';
$now=time();
$unique='id';
$unique_field='fname';
$table="dealer_credit_limit_request";
$page="dealer_credit_limit_request.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$dealer_master = find_all_field('dealer_info','account_code','dealer_code='.$_GET[dealer_code]);


if(prevent_multi_submit()){
    if(isset($_POST['add']))
    {   
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['section_id'] = $_SESSION['sectionid']; 
        $_POST['company_id'] = $_SESSION['companyid']; 
        $crud->insert();
    }}



$res="Select 
d.dealer_code as dcode,
concat(d.dealer_code,' : ' ,d.account_code) as account_code,
d.dealer_name_e as dealer_name,
r.current_balance as ledger_balance,
d.credit_limit as current_credit_limit,
r.requested_limit
from 
dealer_info d ,
accounts_ledger a,
dealer_credit_limit_request r
 where 
 d.account_code=a.ledger_id and
 d.canceled in ('Yes') and
 r.dealer_code=d.dealer_code
 group by d.account_code
 order by d.account_code";
?>

<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
function reload(form){
var val=form.dealer_code.options[form.dealer_code.options.selectedIndex].value;
self.location='<?=$page;?>?<?php if($_GET[id]>0){?>id=<?=$_GET[id]?>&<?php } ?>dealer_code=' + val ;}</script>
<?php require_once 'body_content_nva_sm.php'; ?>


<div class="col-md-12 col-xs-12">
<div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
                <table align="center" style="width:100%">
                    <tr>
                        <td style="">
                            <select class="select2_single form-control" style="width:90%; font-size: 11px" tabindex="-1" required="required"  name="dealer_code" id="dealer_code" onchange="javascript:reload(this.form)">
                                <option></option>
                                <?=foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $_GET[dealer_code], 'canceled="YES"'); ?>
                            </select>
                        </td>
                        <td style="width:10%"><input type="text" name="current_balance" value="<?=find_a_field('journal','SUM(dr_amt-cr_amt)','ledger_id='.$dealer_master->account_code)?>" class="form-control col-md-7 col-xs-12" readonly style="width: 90%; font-size: 11px;"></td>
                        <td style="width:10%"><input type="number" name="requested_limit" value="" class="form-control col-md-7 col-xs-12" required style="width: 90%; font-size: 11px;" placeholder="request limit"></td>
                        <td style="width:15%"><input type="text" name="remarks" value="" class="form-control col-md-7 col-xs-12" required style="width: 90%; font-size: 11px;" placeholder="remarks"></td>
                        <td style="width:15%">
                            <select class="select2_single form-control" style="width:90%; font-size: 11px">
                                <option></option>
                                <option value="Longtime">Unlimited</option>
                                <option value="For one time DO">Single</option>
                            </select>
                        </td>
                        <td style="width:5%"><?php if($_GET[dealer_code]>0){?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 12px">Add Request</button><?php } else {} ?> </td>
                    </tr>
                </table>
            </form></div></div></div>

<?php if(!isset($_GET[$unique])){ ?>
    <?=$crud->report_templates_with_title_and_class($res,$title='Requested Logs','12');?>
<?php } ?>
<?=$html->footer_content();?>