<?php
/////////////////////////////////////////////////////////////////
///////////////////// DATABASE FUNCTIONS //////////////
/////////////////////////////////////////////////////////////////
function connectDB()
	{
		$GLOBALS['DB'] = mysqli_connect(DB_SERVER, DB_USER, DB_PASS) or die();
		$np_db = mysqli_select_db(DB_NAME) or die("<p class=error>There is a problem selecting the database.</p>");
	}

	function closeDB()
	{
		mysqli_close(DB_NAME);
	}

require_once 'base.php';

function execute_query($sql)
{   global $conn;
    $query=mysqli_query($conn, $sql);
    if(mysqli_num_rows($query)>0)
    {
        $data=mysqli_fetch_row($query);
        return $data;
    }
    else NULL;
}
function db_execute($sql)
{   global $conn;
    return mysqli_query($conn, $sql);
}
function db_fetch_object($table,$condition)
{   global $conn;
	$res="select * from $table where $condition limit 1";
	if($query=mysqli_query($conn, $res)){
	if(mysqli_num_rows($query)>0) return mysqli_fetch_object($query);
	else return NULL;}else return NULL;
}



function find($res)
{   global $conn;
	$query=mysqli_query($conn, $res);
	if(mysqli_num_rows($query)>0) return 1;
	else return NULL;
}
function db_fetch_array($table,$condition)
{   global $conn;
	$res="select * from $table where $condition limit 1";
	$query=mysqli_query($conn, $res);
	if(mysqli_num_rows($query)>0) return mysqli_fetch_array($query);
	else return NULL;
}

function get_vars ($fields)
{   global $conn;
	$vars = array();
	foreach($fields as $field_name) {
		if (isset($_POST[$field_name])) {
			$vars[$field_name] = $_POST[$field_name];
		}
	}
	return $vars;
}

function get_value ($fields)
{   global $conn;
	$vars = array();
	foreach($fields as $field_name) {
	var_dump($field_name);
	}
	return $vars;
}

function reduncancy_check($table,$field,$value)
{   global $conn;
	$sql="select 1 from $table where $field='$value' limit 1";
	$query=mysqli_query($conn, $sql);
	return mysqli_num_rows($query);
}
function reduncancy_check2($table,$con)
{   global $conn;
	$sql="select 1 from $table where $con limit 1";
	$query=mysqli_query($conn, $sql);
	return mysqli_num_rows($query);
}
function db_insert($table, $vars)
{   global $conn;
	foreach ($vars as $field => $value) {
		$fields[] = $field;
		if ($value != 'NOW()') {
			$values[] = "'" . addslashes($value) . "'";
		} else {
			$values[] = $value;
		}
	}

	$fieldList = implode(", ", $fields);
	$valueList = implode(", ", $values);
	$sql="insert into $table ($fieldList) values ($valueList)";
	$id=mysqli_query($conn, $sql);
	return $id;
}

function db_update($table, $id, $vars, $tag='')
{   global $conn;
	foreach ($vars as $field => $value) {
		$sets[] = "$field = '" . addslashes($value) . "'";
	}

	$setList = implode(", ", $sets);

	if($tag=='')
		$sql = "update $table set $setList where id= $id";
	else
		$sql = "update $table set $setList where $tag= $id";
//echo $sql;
	db_execute($sql);
}

function db_delete($table,$condition)
{   global $conn;
	echo $sql = "delete from $table where $condition limit 1";
	return mysqli_query($conn, $sql);
}


function db_last_insert_id($table,$field) {
	global $conn;
    $sql = "select MAX($field)+1 from $table";
    if($result = mysqli_query($conn, $sql)){
        $data = mysqli_fetch_row($result);
        if($data[0]<1)
            return 1;
        else
            return $data[0];
    }
    else return 1;
}




?>