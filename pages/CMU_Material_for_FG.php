 <?php
require_once 'support_file.php';
$title="Material for FG";

$now=time();
$unique='id';
$unique_field='line_id';
$table="production_line_fg_raw";
$page="CMU_Material_for_FG.php";
$reloadpage=$page.'?line_id='.$_GET[line_id].'&fg_id='.$_GET[fg_id];
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $row_item_ids= $_POST['row_item_id'];
        foreach ($row_item_ids as $i) {
        $row_item_ids = $i;
		$_POST['row_item_id']=$row_item_ids;
		$_POST[fg_custom_id]=find_a_field('item_info','finish_goods_code','item_id='.$_POST[fg_id].'');
        $_POST[raw_custom_id]=find_a_field('item_info','finish_goods_code','item_id='.$_POST['row_item_id'].'');
        $_POST[raw_sub_group_id]=find_a_field('item_info','sub_group_id','item_id='.$_POST['row_item_id'].'');
        $_POST[unit_name]=find_a_field('item_info','unit_name','item_id='.$_POST['row_item_id'].'');
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
		}
        unset($_POST);
        unset($$unique);
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $type=1;
    //echo $targeturl;


    echo "<script>self.opener.location = '$reloadpage'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>self.opener.location = '$reloadpage'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
	$sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
$sql_fg="SELECT i.item_id,concat(i.item_id,' : ',i.item_name) as item_name from 
production_line_fg f,
item_info i
WHERE 
f.fg_item_id=i.item_id and 
line_id=".$_GET[line_id]."";


$sql_material="SELECT i.item_id,concat(i.item_id,' : ',i.item_name)as item_name from 
production_line_raw r,
item_info i
WHERE 
r.fg_item_id=i.finish_goods_code and 
line_id=".$_GET[line_id]." and i.item_id not in (select row_item_id from production_line_fg_raw where fg_id=".$_GET[fg_id]." and line_id=".$_GET[line_id].")";
?>


 <?php require_once 'header_content.php'; ?>
     <script type="text/javascript">
         function DoNavPOPUP(lk)
         {myWindow = window.open("<?=$page?>?line_id=<?=$_GET[line_id]?>&fg_id=<?=$_GET[fg_id]?>&<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=500,height=500,left = 450,top = -1");}
     </script>
     <SCRIPT language=JavaScript>
         function reload(form)
         {   var val=form.line_id.options[form.line_id.options.selectedIndex].value;
             self.location='<?=$page;?>?line_id=' + val ;
         }
     </script>
     <SCRIPT language=JavaScript>
         function reload2(form)
         {   var val=form.fg_id.options[form.fg_id.options.selectedIndex].value;
             self.location='<?=$page;?>?line_id=<?=$_GET[line_id];?>&fg_id=' + val ;
         }
     </script>
 </head>

<?php require_once 'body_content.php'; ?>
 <?php if(!isset($_GET[$unique])){ ?>
                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>

                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form style="font-size: 11px"  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    <input type="hidden" id="line_ids" style="width:100%"  required   name="line_ids" value="<?=$_GET[warehouse_id]?>" class="form-control col-md-7 col-xs-12" >


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Warehouse<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="select2_single form-control" onchange="javascript:reload(this.form)" style="width: 100%;" tabindex="-1" required="required"  name="line_id" id="line_id">
                        <option selected></option>
                        <?=advance_foreign_relation($sql_plant,$_GET[line_id]);?>
                                   </select>
                    
                                            
                                    </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Select FG<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select id="fg_id" required="required" style="" onchange="javascript:reload2(this.form)" name="fg_id" class="select2_single form-control">
                                                <option value="">Choose ......</option>                                               												
                                            <?=advance_foreign_relation($sql_fg,$_GET[fg_id]);?></select>
                                    </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Select Material<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select multiple id="row_item_id" required="required" style=""  name="row_item_id[]" class="select2_single form-control">                                   <option value="">Choose ......</option>
                                                 <?=advance_foreign_relation($sql_material,$_POST[row_item_id]);?></select>
                                                </select>
                                        </div></div>
                                    <?php if($_GET[$unique]){  ?>
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify</button>
                                            </div></div>
                                            <? if($_SESSION['userid']=="10019"){?>                                            
                                             <div class="form-group" style="margin-left:40%;">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                             <input  name="delete" type="submit" class="btn btn-danger" id="delete" value="Delete"/>
                                             </div></div>                                             
                                             <? }?>                                         
                                            <?php } else {?>                                           
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  class="btn btn-primary">Add Material </button>
                                            </div></div>                                                                                        
                                            <?php } ?> 


                                </form>
                                </div>
                                </div>
                                </div>


<?php if($_GET[fg_id]) {
$res='select 
                                t.'.$unique.',
                                t.'.$unique.' as Code,
                                w.warehouse_name as line_Name,
                                i.item_id,
                                i.item_name,
                                i.unit_name
								from 
                                '.$table.' t,
                                item_info i,
                                warehouse w
								WHERE 
                                 w.warehouse_id=t.line_id and
                                 i.item_id=t.row_item_id and
                                 t.line_id='.$_GET[line_id].' and t.fg_id='.$_GET[fg_id].' order by '.$unique;
								  echo $crud->report_templates_with_title_and_class($res,$title='List of Material','12'); } ?>
                    <!-------------------End of  List View --------------------->
                    <?php } else {  ?>
                    <!---page content----->
     <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel">
             <div class="x_content">
               <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                     <?require_once 'support_html.php';?>
                     <input type="hidden" id="line_ids" style="width:100%"  required   name="line_ids" value="<?=$_GET[warehouse_id]?>" class="form-control col-md-7 col-xs-12" >


                     <div class="form-group">
                         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">CMU / Depot<span class="required">*</span>
                         </label>
                         <div class="col-md-6 col-sm-6 col-xs-12">
                             <select id="line_id" required="required" style="" onchange="javascript:reload(this.form)"   name="line_id" class="select2_single form-control">
                                 <option value="">Choose ......</option>
                                 <?php $resultVENDOR=mysql_query("Select * from warehouse where use_type in ('PL','WH')  order by warehouse_id");
                                 while($rowVENDOR=mysql_fetch_array($resultVENDOR)){ ?>
                                     <option value="<?php echo $rowVENDOR[warehouse_id]; ?>"  <?php if($line_id==$rowVENDOR[warehouse_id]) echo 'selected' ?>><?php echo $rowVENDOR[warehouse_name]; ?></option>
                                 <?php } ?></select>
                         </div></div>


                     <div class="form-group">
                         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Select FG<span class="required">*</span>
                         </label>
                         <div class="col-md-6 col-sm-6 col-xs-12">
                             <select id="fg_id" required="required" name="fg_id" class="select2_single form-control">
                                 <option value="">Choose ......</option>
                                 <?php
                                 $result=mysql_query("SELECT f.fg_item_id,i.item_id,i.item_name from 

production_line_fg f,
item_info i
WHERE 
f.fg_item_id=i.item_id and 
line_id=".$line_id."
");
                                 while($row=mysql_fetch_array($result)){ ?>
                                     <option value="<?=$row[item_id];?>" <?php if($fg_id==$row[item_id]) echo 'selected' ?>><?=$row[item_name]; ?></option>
                                 <?php } ?></select>
                         </div></div>


                     <div class="form-group">
                         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Select Material<span class="required">*</span>
                         </label>
                         <div class="col-md-6 col-sm-6 col-xs-12">
                             <select id="row_item_id" required="required" style=""  name="row_item_id" class="select2_single form-control">
                                 <option value="">Choose ......</option>
                                 <?php
                                 $result=mysql_query("SELECT r.fg_item_id,i.item_id,i.item_name from 

production_line_raw r,
item_info i
WHERE 
r.fg_item_id=i.finish_goods_code and 
line_id=".$line_id."
");
                                 while($row=mysql_fetch_array($result)){ ?>
                                     <option value="<?=$row[item_id];?>" <?php if($row_item_id==$row[item_id]) echo 'selected' ?>><?=$row[item_name]; ?></option>
                                 <?php } ?></select>
                         </div></div>
                     <?php if($_GET[$unique]){  ?>
                         <div class="form-group" style="margin-left:40%">
                             <div class="col-md-6 col-sm-6 col-xs-12">
                                 <button type="submit" name="modify" id="modify" class="btn btn-success">Modify</button>
                             </div></div>
                         <? if($_SESSION['userid']=="10019"){?>
                             <div class="form-group" style="margin-left:40%;">
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                     <input  name="delete" type="submit" class="btn btn-danger" id="delete" value="Delete"/>
                                 </div></div>
                         <? }?>
                     <?php } else {?>
                         <div class="form-group" style="margin-left:40%">
                             <div class="col-md-6 col-sm-6 col-xs-12">
                                 <button type="submit" name="record" id="record"  class="btn btn-success">Add New </button>
                             </div></div>
                     <?php } ?>


                 </form>
             </div>
         </div>
     </div>
     <?php } ?>

                
        
<?=$html->footer_content();?>