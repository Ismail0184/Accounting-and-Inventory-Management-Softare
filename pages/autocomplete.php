<?php
require_once 'support_file.php';
auto_complete_from_db('dealer_info','concat(dealer_name_e," - ",team_name," [",dealer_type,"]")','dealer_code','1  and canceled="Yes"','dealer');
?>
<?
$main_content=ob_get_contents();
ob_end_clean();
?>

<link href="http://icpbd-erp.com/51816/sales_mod/css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="http://icpbd-erp.com/51816/sales_mod/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="http://icpbd-erp.com/51816/sales_mod/js/jquery.autocomplete.js"></script>

<input name="dealer" type="text" id="dealer" />
<?=$main_content?>
