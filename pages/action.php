<?php

//action.php

$connect = new PDO("mysql:host=localhost;dbname=icp_distribution", "icp_distribution", "Allahis1!!@@##");
$received_data = json_decode(file_get_contents("php://input"));
$data = array();
if($received_data->action == 'fetchall')
{
 $query = 'SELECT b.*,pbi.PBI_name from branch b,personnel_basic_info pbi where b.branch_rsm_name=pbi.PBI_ID order by b.BRANCH_ID';
 $statement = $connect->prepare($query);
 $statement->execute();
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
  $data[] = $row;
 }
 echo json_encode($data);
}
if($received_data->action == 'insert')
{
 $data = array(
  ':Region_code' => $received_data->Region_code,
  ':BRANCH_NAME' => $received_data->BRANCH_NAME
 );

 $query = "
 INSERT INTO branch 
 (Region_code, BRANCH_NAME) 
 VALUES (:Region_code, :BRANCH_NAME)
 ";

 $statement = $connect->prepare($query);
 $statement->execute($data);
 $output = array(
  'message' => 'Data Inserted'
 );

 echo json_encode($output);
}




if($received_data->action == 'fetchSingle') {
 $query = "
 SELECT * FROM branch 
 WHERE BRANCH_ID = '".$received_data->BRANCH_ID."'
 ";

 $statement = $connect->prepare($query);

 $statement->execute();

 $result = $statement->fetchAll();

 foreach($result as $row)
 {
  $data['BRANCH_ID'] = $row['BRANCH_ID'];
  $data['Region_code'] = $row['Region_code'];
  $data['BRANCH_NAME'] = $row['BRANCH_NAME'];
 }

 echo json_encode($data);
}
if($received_data->action == 'update')
{
 $data = array(
  ':Region_code' => $received_data->Region_code,
  ':BRANCH_NAME' => $received_data->BRANCH_NAME,
  ':BRANCH_ID'   => $received_data->hiddenId
 );

 $query = "
 UPDATE branch 
 SET Region_code = :Region_code, 
 BRANCH_NAME = :BRANCH_NAME 
 WHERE BRANCH_ID = :BRANCH_ID
 ";

 $statement = $connect->prepare($query);

 $statement->execute($data);

 $output = array(
  'message' => 'Data Updated'
 );

 echo json_encode($output);
}

if($received_data->action == 'delete')
{
 $query = "
 DELETE FROM branch 
 WHERE BRANCH_ID = '".$received_data->BRANCH_ID."'
 ";

 $statement = $connect->prepare($query);

 $statement->execute();

 $output = array(
  'message' => 'Data Deleted'
 );

 echo json_encode($output);
}

?>