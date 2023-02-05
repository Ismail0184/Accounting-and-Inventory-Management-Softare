<?php
require_once 'support_file.php';
$title='Accounts Report';
$page='acc_select_report.php';
$page='test3.php';
?>

<SCRIPT language=JavaScript>
    function reload(form)
    {
        var val=form.report_id.options[form.report_id.options.selectedIndex].value;
        self.location='<?=$page;?>?report_id=' + val ;
    }
    function reload1(form)
    {
        var val=form.report_id.options[form.report_id.options.selectedIndex].value;
        var val2=form.ledgercode.options[form.ledgercode.options.selectedIndex].value;
        self.location='acc_select_report.php?report_id=' + val +'&ledgercode=' + val2 ;
    }

</script>

<?php
function select_a_report()
{
global $conn;
$query=mysqli_query($conn, "
SELECT zm.optgroup_label_name,zs.report_name as subzonename,zs.report_id FROM module_reportview_optgroup_label AS zm
RIGHT JOIN module_reportview_report AS zs ON zm.optgroup_label_id = zs.optgroup_label_id RIGHT JOIN user_permissions_reportview AS p ON p.optgroup_label_id=zm.optgroup_label_id AND p.report_id=zs.report_id WHERE p.status in ('1')
ORDER BY zm.sl, zs.sl");
$result = array();
while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
$cat_name = $row['optgroup_label_name'];
if(!isset($results[$cat_name])){
$results[$cat_name] = array();
}
$results[$cat_name][] = array("subzonename" => $row['subzonename'], "report_id" => $row['report_id']);
}
if(!empty($results)){
    $str .= '<select id="first-name"  required="required" size="27" style="font-size: 11px; border: none;white-space: nowrap;
  overflow: scroll;
  text-overflow: ellipsis;" name="report_id" onchange="javascript:reload(this.form)" class="form-control col-md-7 col-xs-12">';
foreach($results as $category => $subcats){
$str .= '<optgroup label="'. $category.'">';
        foreach($subcats as $subcategory){
            if($_GET[report_id]==$subcategory[report_id]){
                $selected='selected';
            } else {
                $selected='';
            }
        $str .= '<option value="'.$subcategory[report_id].'" '.$selected.'>'. $subcategory[subzonename].'</option>';}
        $str .= '</optgroup>';}}
    $str .= '</select>';
return $str;
} ?>
<form class="form-horizontal form-label-left" method="POST" action="<?=$page;?>" style="font-size: 11px" target="_blank">

<?=select_a_report();?>
</form>






