<!DOCTYPE html>
<html>
<body>
<?php
$orderdate="23/10/2017";
$orderdate = explode('/', $orderdate);


list( $yearismail, $monthismail, $dayismail) = explode("/[\/\.\-]+/", $orderdate);

$day   = $orderdate[1];
$month = $orderdate[0];
$year  = $orderdate[2];
echo $day."<br>";
echo $month."<br>";
echo $year;

echo $yearismail;


?>
</body>
</html>