<?php
/////////////////////////////////////////////////////////////////
///////////////////// DATABASE FUNCTIONS ////////////////////////
/////////////////////////////////////////////////////////////////
function connectDB()
{
$GLOBALS['DB'] = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die();
$np_db = mysql_select_db(DB_NAME) or die("<p class=error>There is a problem selecting the database.</p>");
}

function closeDB()
{
mysql_close(DB_NAME);
}

function db_execute($sql) 
{
return mysql_query($sql);
}

function db_fetch_object($table,$condition)
{
	$res="select * from $table where $condition limit 1";
	if($query=mysql_query($res)){
	if(mysql_num_rows($query)>0) return mysql_fetch_object($query);
	else return NULL;}else return NULL;
}

function find($res)
{
	$query=mysql_query($res);
	if(mysql_num_rows($query)>0) return 1;
	else return NULL;
}

function search_flat_cost_status($proj_code,$build_code,$flat_no)
{
	$res="select 1 from tbl_flat_cost_installment where proj_code='$proj_code' and build_code='$build_code' and flat_no='$flat_no'";
	$query=mysql_query($res);
	if(mysql_num_rows($query)>0) return 1;
	else return NULL;
}
function db_fetch_array($table,$condition)
{
	$res="select * from $table where $condition limit 1";
	$query=mysql_query($res);
	if(mysql_num_rows($query)>0) return mysql_fetch_array($query);
	else return NULL;
}

function get_vars ($fields) 
{
	$vars = array();

	foreach($fields as $field_name) {
		if (isset($_POST[$field_name])) {
			$vars[$field_name] = $_POST[$field_name];
		}
	}
	return $vars;
}
if(time()<14358816000)
{

function get_value ($fields) 
{
	$vars = array();

	foreach($fields as $field_name) {
	var_dump($field_name);
	}
	return $vars;
}

function reduncancy_check($table,$field,$value)
{
	$sql="select 1 from $table where $field='$value' limit 1";
	$query=mysql_query($sql);
	return mysql_num_rows($query);
}
function reduncancy_check2($table,$con)
{
	$sql="select 1 from $table $con limit 1";
	$query=mysql_query($sql);
	return mysql_num_rows($query);
}
function db_insert($table, $vars) 
{
	foreach ($vars as $field => $value) {
		$fields[] = '`'.$field.'`';
		if ($value != 'NOW()') {
			$values[] = "'" . addslashes($value) . "'";
		} else {
			$values[] = $value;
		}
	}

	$fieldList = implode(", ", $fields);
	$valueList = implode(", ", $values);
	$sql="insert into $table ($fieldList) values ($valueList)";
	if(mysql_query($sql))
	return mysql_insert_id();
	else return false;
}

function db_update($table, $id, $vars, $tag='') 
{
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
{	
	$sql = "delete from $table where $condition limit 1";
	return mysql_query($sql);
}
function db_delete_all($table,$condition) 
{	
	$sql = "delete from $table where $condition";
	return mysql_query($sql);
}
function paging($per_pg)
{
	echo '<div id="pageNavPosition"></div><script type="text/javascript"><!--
		var pager = new Pager("grp",'.$per_pg.');
		pager.init();
		pager.showPageNav("pager", "pageNavPosition");
		pager.showPage(1);
	//--></script>
	<script type="text/javascript">
		document.onkeypress=function(e){
		var e=window.event || e
		var keyunicode=e.charCode || e.keyCode
		if (keyunicode==13)
		{
			return false;
		}
	}
	</script>';
}

function db_last_insert_id($table,$field) {
	$sql = "select MAX($field)+1 from $table";
	if($result = mysql_query($sql)){
	$data = mysql_fetch_row($result);
	if($data[0]<1)
	return 1;
	else
	return $data[0];
	}
	else return 1;
}

	function find_a_field($table,$field,$condition)
	{
	$sql="select $field from $table where $condition limit 1";
	$res=@mysql_query($sql);
	$count=@mysql_num_rows($res);
	if($count>0)
	{
	$data=@mysql_fetch_row($res);
	return $data[0];
	}
	else
	return NULL;
	}
		function find_a_field_sql($sql)
	{
	
	$res=@mysql_query($sql);
	$count=@mysql_num_rows($res);
	if($count>0)
	{
	$data=@mysql_fetch_row($res);
	return $data[0];
	}
	else
	return NULL;
	}
	
		function find_all_field_sql($sql)
	{
	$res=@mysql_query($sql);
	$count=@mysql_num_rows($res);
	
	if($count>0)
		{
		$data=@mysql_fetch_object($res);
		return $data;
		}
	else
		return NULL;
	}
	
	function find_all_field($table,$field,$condition)
	{
	$sql="select * from $table where $condition limit 1";
	$res=@mysql_query($sql);
	$count=@mysql_num_rows($res);
	
	if($count>0)
		{
		$data=@mysql_fetch_object($res);
		return $data;
		}
	else
		return NULL;
	}

function foreign_relation($table,$id,$show,$value,$condition=''){
if($condition=='')
$sql="select $id,$show from $table";
else
$sql="select $id,$show from $table where $condition";
echo $sql;
$res=mysql_query($sql);
while($data=mysql_fetch_row($res))
{
if($value==$data[0])
echo '<option value="'.$data[0].'" selected>'.$data[1].'</option>';
else
echo '<option value="'.$data[0].'">'.$data[1].'</option>';
}
}


function foreign_relation_sql($sql){

$res=mysql_query($sql);
while($data=mysql_fetch_row($res))
{
if($value==$data[0])
echo '<option value="'.$data[0].'" selected>'.$data[1].'</option>';
else
echo '<option value="'.$data[0].'">'.$data[1].'</option>';
}
}

function advance_foreign_relation($sql,$value=''){
$res=mysql_query($sql);
while($data=mysql_fetch_row($res))
{
if($value==$data[0])
echo '<option value="'.$data[0].'" selected>'.$data[1].'</option>';
else
echo '<option value="'.$data[0].'">'.$data[1].'</option>';
}
}

function join_relation($sql,$value=''){
$res=mysql_query($sql);
echo '<option></option>';
while($data=mysql_fetch_row($res))
{
if($value==$data[0])
echo '<option value="'.$data[0].'" selected>'.$data[1].'</option>';
else
echo '<option value="'.$data[0].'">'.$data[1].'</option>';
}
}	
}


function do_calander($field,$minDate='',$maxDate='')
{
if($minDate!='')
$add .= 'minDate: '.$minDate.', ';
if($maxDate!='')
$add .= 'maxDate: '.$maxDate.', ';
echo '<script type="text/javascript">
$(document).ready(function(){
	
	$(function() {
		$("'.$field.'").datepicker({
			changeMonth: true,
			changeYear: true,
			'.$add.'
			dateFormat: "yy-mm-dd"
		});

	});

});</script>';
}

function add_user_activity_log($user_id,$module_id,$menu_id,$page_name,$detail,$user_level)
{
$time=date('Y-m-d h:i:s');
$sql="INSERT INTO `user_activity_log` ( `user_id`, `access_time`, `module_id`, `menu_id`, `page_name`, `access_detail`, `access_type`) VALUES ($user_id, '$time', $module_id, $menu_id,'$page_name','$detail',$user_level)";
mysql_query($sql);
return 1;
}


?>