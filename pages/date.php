<?php require_once 'support_file.php';?>
<!DOCTYPE html>
<html>
<body>
<?php
$sql="select item_id,item_name from item_info where 1";
$res=mysqli_query($conn, $sql);
while($data=mysqli_stmt_fetch($res))
{
    echo $data[0].'<br>';
}
?>
</body>
</html>