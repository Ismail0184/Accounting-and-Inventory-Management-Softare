<!DOCTYPE html>
<html>
<body>
<?php
$orderdate=date('Y-m-d');
$orderdate = explode('-', $orderdate);
$day   = $orderdate[2];
$month = $orderdate[1];
$year  = $orderdate[0];
echo 'Day = ' .$day."<br>";
echo 'Month =' .$month."<br>";
echo 'Year =' . $year;
?>
</body>
</html>