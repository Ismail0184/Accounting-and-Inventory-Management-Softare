<!DOCTYPE html>
<html>
<head>
	<title>Vue.js simple step by step CRUD Operation using PHP and MySQLi</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
    <style>.liveModal{
	position:fixed;
	bottom:0;
	background: rgba(0, 0, 0, 0.4);
	top:0;
	left:0;
	left:0;
}
 
.lstmodelctn{
	width: 800px;
	background: #3d3d3d;
	margin:auto;
	margin-top:52px;
}
 
.mdlheader{
	font-size:20px;
	padding-left:14px;
	padding:12px;
	background: #008852;
	color: #FFFFFF;
	height:52px;
}
 
 
.mdlBody{
	padding:38px;
}
 
.mdlFooter{
	height:39px;
}
 
.footerBtn{
	margin-left:11px;
	margin-top:-9px;
}
 
.closeBtn{
	background: #c60000;
	color: #3d3d3d;
	border:none;
}</style>

</head>
<body>

<!-- Vuejs simple Add Modal live24u-->
<div class="liveModal" v-if="displaySimpleModal">
	<div class="lstmodelctn">
		<div class="mdlheader">
			<span class="headerTitle">Add New User</span>
			<button class="closeBtn pull-left" @click="displaySimpleModal = false">×</button>
		</div>
		<div class="mdlBody">
			<div class="form-group live">
				<label>User Firstname:</label>
				<input type="text" class="form-control" v-model="newUser.userfname">
			</div>
			<div class="form-group live">
				<label>User Lastname:</label>
				<input type="text" class="form-control" v-model="newUser.userlname">
			</div>
		</div>
		<hr>
		<div class="mdlFooter">
			<div class="live footerBtn pull-left">
				<button class="live btn btn-default" @click="displaySimpleModal = false"><span class="glyphicon glyphicon-remove"></span> Cancel</button> <button class="btn btn-success live" @click="displaySimpleModal = false; saveUser();"><span class="live glyphicon glyphicon-floppy-disk"></span>USER Save</button>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<h1 class="page-header text-center">simple Vue.js Insert update delete  Operation with PHP and MySQLi</h1>
	<div id="users">
		<div class="col-md-8 col-md-offset-2">
			<div class="row">
				<div class="col-md-12">
					<h2>User List
					<button class="btn btn-success pull-left" @click="displaySimpleModal = true"><span class="live glyphicon glyphicon-plus"></span> User</button>
					</h2>
				</div>
			</div>
 
			<div class="alert alert-danger text-center" v-if="msgError">
				<button type="button" class="close" @click="msgCls();"><span aria-hidden="true">×</span></button>
				<span class="live glyphicon glyphicon-alert"></span> {{ msgError }}
			</div>
 
			<div class="alert alert-success text-center" v-if="msgSuccess">
				<button type="button" class="close" @click="msgCls();"><span aria-hidden="true">×</span></button>
				<span class="live glyphicon glyphicon-ok"></span> {{ msgSuccess }}
			</div>
 
			<table class="table table-bordered table-striped">
				<thead>
					<th>User Firstname</th>
					<th>User Lastname</th>
					<th>User Action</th>
				</thead>
				<tbody>
					<tr v-for="user in users">
						<td>{{ user.userfname }}</td>
						<td>{{ user.userlname }}</td>
						<td>
							<button class="btn btn-success"><span class="live glyphicon glyphicon-edit"></span> Edit</button> <button class="btn btn-danger"><span class="live glyphicon glyphicon-trash"></span> Remove</button>
 
						</td>
					</tr>
				</tbody>
			</table>
		</div>
 
		<?php include('modal.php'); ?>
	</div>
</div>
<script src="vue.js"></script>
<script src="axios.js"></script>
<script src="main.js"></script>
</body>
</html>