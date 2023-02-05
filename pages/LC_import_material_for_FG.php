 <?php
require_once 'support_file.php';
$title="Import Material for FG";
$now=time();
$unique='id';
$unique_field='fg_id';
$table="LC_import_material_for_FG";
$page="LC_import_material_for_FG.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $material_ids= $_POST['material_id'];
        foreach ($material_ids as $i) {
            $material_ids = $i;
            $_POST['material_id'] = $material_ids;
            $crud->insert();
            $type = 1;
            $msg = 'New Entry Successfully Inserted.';
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
    $tlink=$page.'?fg_id='.$_GET[fg_id];
    echo "<script>self.opener.location = '$tlink'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

 $res_details=mysqli_query($conn, 'select i.*, l.id,l.material_id,l.fg_id,l.unit_qty,(select concat(item_id, " : ", item_name) from item_info where item_id=l.fg_id) as fg_name
from item_info i, LC_import_material_for_FG l 
where i.item_id=l.material_id and l.fg_id='.$_GET[fg_id].'
');
?>



<?php require_once 'header_content.php'; ?>
 <SCRIPT language=JavaScript>
     function reload(form)
     {
         var val=form.fg_id.options[form.fg_id.options.selectedIndex].value;
         self.location='<?=$page;?>?fg_id=' + val ;
     }
 </script>
 <script type="text/javascript">
     function DoNavPOPUP(lk)
     {myWindow = window.open("<?=$page?>?fg_id=<?=$_GET[fg_id];?>&<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=500,height=500,left = 383,top = -1");}
 </script>
<?php require_once 'body_content.php'; ?>



                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Add Material for FG</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <? require_once 'support_html.php';?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Finish Goods<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="hidden" name="<?=$unique?>" id="<?=$unique?>" value="<?=$$unique;?>" >
                                            <select style="width: 100%;margin-top: 2px;" class="select2_single form-control" name="fg_id" id="fg_id" onchange="javascript:reload(this.form)">
                                                <option></option>
                                                <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id in ('500000000') 							 
							  order by i.item_name";
                                                advance_foreign_relation($sql_item_id,$_GET[fg_id]);?></select>
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Material<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select multiple style="width: 100%;margin-top: 2px;" class="select2_single form-control" name="material_id[]" id="material_id">

                                                <? $sql_material="SELECT i.item_id,concat(i.item_id,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id not in ('500000000') and i.item_id not in (select material_id from LC_import_material_for_FG where fg_id=".$_GET[fg_id].") 							 
							  order by i.item_name";
                                                advance_foreign_relation($sql_material,$material_id);?></select>
                                        </div></div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Unit Qty<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="unit_qty" style="width:100%"  required   name="unit_qty" value="<?=$unit_qty;?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>


                                            <div class="form-group" style="margin-left: 25%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php if($_GET[id]){  ?>
                                            <a class="btn btn-danger" href="<?=$page;?>" style="font-size: 12px">Cancel</a>
                                            <button type="submit" name="modify" id="modify" class="btn btn-success" style="font-size: 12px">Modify Data</button>


                                            <?php } else { ?>
                                            <a class="btn btn-danger" href="<?=$page;?>" style="font-size: 12px">Cancel</a>
                                            <button type="submit" name="record" id="record"  class="btn btn-primary" style="font-size: 12px">Add Material </button>
                                                <?php } ?>
                                            </div></div>

                                </form>
                                </div></div></div>


 <?php if(!isset($_GET[id])){ ?>
 <?php if(isset($_GET[fg_id])){ ?>
                        <!-------------------list view ------------------------->
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Material list for the FG</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <table  class="table table-striped table-bordered" style="width:100%;font-size:11px">
                                        <thead>
                                        <tr>
                                            <th style="width: 2%">#</th>
                                            <th style="">FG Name</th>
                                            <th style="">Material Name</th>
                                            <th style="text-align: center">Unit Name</th>
                                            <th style="text-align: center">Unit Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?  while($item=mysqli_fetch_object($res_details)){ ?>
                                            <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$item->id;?>', 'TEST!?', 600, 700)">
                                                <td><?=$i=$i+1;?></td>
                                                <td><?=$item->fg_name;?></td>
                                                <td><?=$item->item_id." : ".$item->item_name;?></td>
                                                <td style="text-align: center"><?=$item->unit_name;?></td>
                                                <td style="text-align: center"><?=$item->unit_qty;?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div></div></div>
                    <?php }} mysqli_close($conn); ?>
<?php require_once 'footer_content.php' ?>