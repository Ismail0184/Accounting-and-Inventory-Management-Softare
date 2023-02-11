<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

<?php require_once 'support_file.php';



$result = mysqli_query($conn, "SET NAMES utf8");//the main trick
$cmd = "Select m.id as id,p.module_id,m.fa_icon,m.fa_icon_color,m.modulename_BN as module_details,m.module_short_name as modulename  from
dev_modules m,user_permissions_module p where
m.module_id=p.module_id and m.status>0 and p.user_id='" . $_SESSION[userid] . "' and
p.status>0
order by m.sl";
$result = mysqli_query($conn, $cmd);
while($myrow = mysqli_fetch_row($result))
{
    echo ($myrow[4]);
}
