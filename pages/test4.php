<?php
require_once 'support_file.php';
$title='Accounts Report';
$page='acc_select_report.php';
$page='test3.php';
?>



<?php

$query=mysqli_query($conn, "
SELECT zm.optgroup_label_name,zs.report_name as subzonename,zs.report_id FROM module_reportview_optgroup_label AS zm
RIGHT JOIN module_reportview_report AS zs ON zm.optgroup_label_id = zs.optgroup_label_id where 1 group by zm.optgroup_label_id
ORDER BY zm.sl, zs.sl");

//$post = array();
while($row = mysqli_fetch_assoc($query))
{
    $posts[] = $row;
}
    foreach($posts as $row){
        echo "<strong>$row[optgroup_label_name]</strong>" . "<br><br>";
    foreach ($row as $element) {
        echo $element . "<br><br>";
    }
}
?>