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


preg_match('#^(\d{2})-(\d{2})-(\d{4})$#', $orderdate, $results);
$month = $results[1];
$day   = $results[2];
$year  = $results[3];

echo $month;

?>
</body>
</html>