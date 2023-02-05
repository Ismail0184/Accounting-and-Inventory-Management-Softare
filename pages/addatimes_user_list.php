<?php


/*Fetching JSON file content using php file_get_contents method*/
$str_data = file_get_contents("https://www.addatimes.com/api/sslcommerz-users?active_status=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBZGRhdGltZXMgTWVkaWEgUHJpdmF0ZSBMaW1pdGVkIiwiaWF0IjoxNTkxMDY3NzMyLCJleHAiOjE3NDg4MzQxMzIsImF1ZCI6Ind3dy5zc2xjb21tZXJ6LmNvbSIsInN1YiI6InNzbGNvbW1lcnpAYWRkYXRpbWVzbWVkaWEuY29tIiwiR2l2ZW5OYW1lIjoiU1NMIiwiU3VybmFtZSI6IkNvbW1lcnoiLCJFbWFpbCI6InNzbGNvbW1lcnpAYWRkYXRpbWVzbWVkaWEuY29tIiwiUm9sZSI6IlZpZXdlciJ9.iU1-6RefSa0m6g9vPmQURsBUuAh9Lkp5yWPrP1OpIMA");
$data = json_decode($str_data, true);
 
/*Initializing temp variable to design table dynamically*/
$temp = "<table>";
 
/*Defining table Column headers depending upon JSON records*/
$temp .= "<tr><th>S/L</th>";
$temp .= "<th>ID</th>";
$temp .= "<th>First Name</th>";
$temp .= "<th>Last Name</th>";
$temp .= "<th>email</th>";
$temp .= "<th>Phone</th>";
$temp .= "<th>User Name</th>";
$temp .= "<th>Subscription Start</th>";
$temp .= "<th>Subscription End</th>";
$temp .= "<th>Registration Date</th></tr>";
 
/*Dynamically generating rows & columns*/
for($i = 0; $i < sizeof($data["data"]); $i++)
{
	$is=1;
$temp .= "<tr>";
$temp .= "<td>" . $j=$j+$is. "</td>";
$temp .= "<td>" . $data["data"][$i]["id"] . "</td>";
$temp .= "<td>" . $data["data"][$i]["first_name"] . "</td>";
$temp .= "<td>" . $data["data"][$i]["last_name"] . "</td>";
$temp .= "<td>" . $data["data"][$i]["email"] . "</td>";
$temp .= "<td>" . $data["data"][$i]["phone"] . "</td>";
$temp .= "<td>" . $data["data"][$i]["user_name"] . "</td>";
$temp .= "<td>" . $data["data"][$i]["subscription_start"] . "</td>";
$temp .= "<td>" . $data["data"][$i]["subscription_end"] . "</td>";
$temp .= "<td>" . $data["data"][$i]["registration_date"] . "</td>";
$temp .= "</tr>";
}
 
/*End tag of table*/
$temp .= "</table>";
 
/*Printing temp variable which holds table*/
echo $temp;