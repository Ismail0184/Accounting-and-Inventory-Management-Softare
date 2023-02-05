<?php
require_once 'support_file.php';
//require_once 'dashboard_data.php';
?>
<h1 style="text-align:center; margin-top:200px">Welcome to <?php if($_SESSION[module_id]>0) { ?> <?=getSVALUE("module_department", "modulename", " where id='$_SESSION[module_id]'");?> Module <?php } else { echo 'ERP Software. <br><font style="font-size: 15px">Please See the left menus</font>'; }?></h1>
