<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="FG Ingredients Formula ";
$now=time();
$unique='id';
$unique_field='fg_item_id';
$table="production_ingredient_detail";
$page="CMU_fg_ingredients_formula.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$entry_by=date('Y-m-d H:s:i');

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................

    if(isset($_POST['record']))
    {
        $res="SELECT r.*,i.* FROM production_line_fg_raw r,item_info i where 
i.item_id=r.row_item_id and 
r.line_id=".$_GET[line_id]." and r.fg_id=".$_GET[fg_id]." order by id";
        $res_row=mysqli_query($conn, $res);		
        while($dataa=mysqli_fetch_object($res_row)) {	 		
			$id = $dataa->row_item_id;
			$_POST[raw_item_id]=$_POST['raw_item_id'.$id];
			$_POST[row_pack_size]=$_POST['row_pack_size'.$id];
			$_POST[unit_name]=$_POST['unit_name'.$id];
			$alradypost=find_a_field('production_ingredient_detail','COUNT(id)','item_id='.$_GET[fg_id].' and line_id='.$_GET[line_id].' and raw_item_id='.$_POST['raw_item_id'.$id].' and status=1');
			if(($alradypost==0) && ($_POST['qty_'.$id]>0)){            
            $_POST[unit_qty]=$_POST['qty_'.$id];
            $_POST[type]=$_POST['type_'.$id];
			$_POST[status]=1;
            $_POST[unit_batch_qty]=$_POST['qty_'.$id]*$_POST[unit_batch_size];
            $crud->insert();
            $type = 1;

        }}

        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }
	
	
	
	        $sql2=mysqli_query($conn,'SELECT * FROM '.$table.' where item_id='.$_GET[fg_id].' and line_id='.$_GET[line_id].'');
            while($dataa=mysqli_fetch_object($sql2)){	 		
			$id = $dataa->raw_item_id;
			$_POST[unit_qty]=$_POST['qty_'.$id];
            $_POST[type]=$_POST['type_'.$id];
			$_POST[status]=1;
            $_POST[unit_batch_qty]=$_POST['qty_'.$id]*$_POST[unit_batch_size];
			if(isset($_POST['edit_single'.$id])){    
			$insert_to_BOM=mysqli_query($conn, "INSERT INTO production_BOM (item_id,line_id,raw_item_id,unit_qty,unit_batch_qty,unit_batch_size,type,edit_at,edit_by,entry_at,entry_by,ip,section_id,company_id,unit_name) VALUES ('".$_GET[fg_id]."','".$_GET[line_id]."','".$dataa->raw_item_id."','".$dataa->unit_qty."','".$dataa->unit_batch_qty."','".$dataa->unit_batch_size."','".$dataa->type."','".$entry_by."','".$_SESSION[userid]."','".$entry_by."','".$_SESSION[userid]."','".$ip."','".$_SESSION[sectionid]."','".$_SESSION[sectionid]."','".$dataa->unit_name."')");
            $update=mysqli_query($conn, "Update production_ingredient_detail SET unit_qty='".$_POST[unit_qty]."',type='".$_POST[type]."',status='".$_POST[status]."',unit_batch_qty='".$_POST[unit_batch_qty]."',edit_by='".$_SESSION[userid]."',edit_at='".$entry_by."' where raw_item_id=".$id." and item_id=".$_GET[fg_id]." and line_id=".$_GET[line_id]."");  

        }}

        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
   
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $type=1;
    //echo $targeturl;
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
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
}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>



 <?php require_once 'header_content.php'; ?>
 <script type="text/javascript">
     function DoNavPOPUP(lk)
     {myWindow = window.open("<?=$page?>?line_id="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=600,left = 280,top = -1");}
 </script>
 </head>


 <?php if(isset($_GET[fg_id])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>
 <?php if(isset($_GET[fg_id])){ $count=find_a_field('production_line_fg_raw','COUNT(id)','fg_id='.$_GET[fg_id].' and line_id='.$_GET[line_id].'');
 if($count<1) { echo '<h6 style=" font-style:italic;text-align:center; color:red">There is no consumption material added for the FG!! '.'<br>'.' To add a material please go to '.'<a style=" font-weight:bold;text-decoration:underline" href="CMU_Material_for_FG.php">'.'"Material for FG"'.'</a>'.' and add material accordingly. '.'<br><br>'.' Thanks!! 
</h6>';} else {
 ?>
 <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">

  <? require_once 'support_html.php';?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">

                                <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Production Line<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="line_id" style="width:100%; height: 30px" readonly  required   name="line_id" value="<?=find_a_field('warehouse','warehouse_name','warehouse_id='.$_GET[line_id]);?>" >
                                </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Finish Goods Name<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="fg_item_id" style="width:100%; height: 30px" readonly  required   name="fg_item_id" value="<?=find_a_field('item_info','item_name','item_id='.$_GET[fg_id]);?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Batch For Pcs<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="unit_batch_size" style="width:100%; height: 30px"  required   name="unit_batch_size" value="<?=find_a_field('production_ingredient_detail','distinct (unit_batch_size)','item_id='.$_GET['fg_id'].' and line_id='.$_GET['line_id'].'');?>">
                                    </div>
                                </div></div></div></div>
<br><br>




                                <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                                    <thead><tr style="background-color: bisque">
                                        <th style="text-align: center">Item Id</th>
                                        <th>Material Description</th>
                                        <th style="text-align: center">Unit</th>
                                        <th style="text-align: center">Formula Qty</th>
                                        <th style="text-align: center">Unit Batch Qty</th>
                                        <th style="text-align: center">Type</th>
                                        <th style="text-align: center">Option</th>
                                    </tr></thead>

                                    <?
                                    $res="SELECT r.*,i.* FROM production_line_fg_raw r,item_info i where 
i.item_id=r.row_item_id and 
r.line_id=".$_GET[line_id]." and r.fg_id=".$_GET[fg_id]." order by id";
                                    $res_row=mysqli_query($conn, $res);
                                        while($dataa=mysqli_fetch_object($res_row)){
											$count=$count+1;
                                        $id=$dataa->row_item_id;
                                        $idformula=find_all_field('production_ingredient_detail','','item_id='.$_GET['fg_id'].' and line_id='.$_GET['line_id'].' and raw_item_id='.$dataa->row_item_id.'');?>
                                            <tr>
                                                <td style="vertical-align: middle"><?=$dataa->item_id;?></td>
                                                <input type="hidden" id="raw_item_id<?=$id;?>" name="raw_item_id<?=$id;?>" value="<?=$dataa->item_id;?>"  >                               
                                                <input type="hidden" id="unit_name<?=$id;?>" name="unit_name<?=$id;?>" value="<?=$dataa->unit_name;?>"  >                                   <input type="hidden" id="row_pack_size<?=$id;?>" name="row_pack_size<?=$id;?>" value="<?=$dataa->pack_size;?>"><input type="hidden" id="id" name="id" >
                                                <input type="hidden" id="item_id" name="item_id" value="<?=$_GET[fg_id];?>">
                                                <input type="hidden" id="line_id" name="line_id" value="<?=$_GET[line_id];?>">
                                                <td style="vertical-align: middle"><?=$dataa->item_name;?></td>
                                                <td style="text-align: center; vertical-align: middle"><?=$dataa->unit_name;?></td>
                                                <td style="text-align: right"><input type="text" id="qty_<?=$id;?>" style="width:100%; height: 25px" name="qty_<?=$id;?>" value="<?=$idformula->unit_qty;?>"  autocomplete="off"  ></td>
                                                 
                                                 <td style="text-align: right"><input style=" height: 25px" type="text" value="<?=$idformula->unit_batch_qty;?>"  autocomplete="off" readonly ></td>
                                                 <td style="text-align: right"><select name="type_<?=$id;?>" id="type_<?=$id;?>" style="width:80px; height: 25px; text-align: center"> <option></option>
                                                <option <?php if($idformula->type=='+'){ echo "selected";} else { echo "";} ?>>+</option>
                                                <option <?php if($idformula->type=='-'){ echo "selected";} else { echo "";} ?>>-</option>
                                                </select></td>
                                                <td style="text-align: center">
                                                <button onclick='return window.confirm("Are you confirm to Update <?=$id;?>?");' type="submit" name="edit_single<?=$id;?>" id="edit_single<?=$id;?>" style="border: none; background-color: transparent"><img src="update.jpg" height="20" width="20" /></button>
                                                <button onclick='return window.confirm("Are you confirm to Deleted?");' type="submit" name="delete_single<?=$id;?>" id="delete_single<?=$id;?>" style="border: none; background-color: transparent"><img src="627249-delete3-512.png" style="margin-left:15px" height="20" width="20" /></button></td>
                                            </tr> <? } ?>
                                </table>                     
                                 <p align="center"><button onclick='return window.confirm("Are you confirm to Update?");' type="submit" name="record" id="record" class="btn btn-primary" style="font-size:12px">Record Ingredients Formula</button>
                             </p>
          
 </form>



 <?php }} ?>
 <?php if(!isset($_GET[fg_id])){ ?>
 
                    <!-------------------list view ------------------------->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                               <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                                    <thead><tr style="background-color: bisque">
                                        <th>SL</th>
                                        <th>Code</th>
                                        <th>FG Code</th>
                                        <th>Item Description</th>
                                        <th>Unit</th>
                                        <th>Production Line</th>
                                        <th>Batch Size</th>
                                        <th>No. of Raw <br> Materials</th>
                                        <th>Entry By</th>
                                        <th>Last Updated at</th>
                                    </tr></thead>
                                    <tbody>

                                    <?
                                 $sql2='select f.'.$unique.',f.'.$unique.' as Code,f.'.$unique_field.',i.finish_goods_code as custom_ID,i.item_id,i.item_name,i.unit_name,w.warehouse_id,w.warehouse_name as Production_line,
                                (select distinct unit_batch_size from production_ingredient_detail where item_id=f.fg_item_id and line_id=f.line_id) as batch_size,u.fname,f.edit_at,
                                (select COUNT(raw_item_id) from production_ingredient_detail where item_id=f.fg_item_id and line_id=f.line_id) as No_of_Materials
                                
                                 from 
								 production_line_fg f,
								 item_info i, 
								 warehouse w ,
								 users u    
								                              
                                 where 
                                 f.fg_item_id=i.item_id and 
                                 f.line_id=w.warehouse_id and
								 u.user_id=f.entry_by                                
                                 order by w.warehouse_id,i.finish_goods_code';

                                    $data2=mysqli_query($conn, $sql2);
                                    if(mysqli_num_rows($data2)>0){
                                        while($dataa=mysqli_fetch_object($data2)){ ?>
                                            <tr style="cursor: pointer" class="alt" onclick="DoNavPOPUP('<?=$dataa->warehouse_id?>&fg_id=<?=$dataa->item_id;?>', 'TEST!?', 800,600)">
                                                <td><?=$is=$is+1;?></td>
                                                <td><?=$dataa->item_id;?></td>
                                                <td><?=$dataa->custom_ID;?></td>
                                                <td><?=$dataa->item_name;?></td>
                                                <td style="text-align: center"><?=$dataa->unit_name;?></td>
                                                <td style="text-align: left"><?=$dataa->Production_line;?></td>
                                                <td style="text-align: right"><?=$dataa->batch_size;?></td>
                                                <td style="text-align: right"><?=$dataa->No_of_Materials;?></td>
                                                <td style="text-align: left"><?=$dataa->fname;?></td>
                                                <td style="text-align: left"><?=$dataa->edit_at;?></td>

                                            </tr> <? }}?></tbody>
                                </table>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->
                    <?php } ?>
                    <!---page content----->


                
        
                    <?=$html->footer_content();mysqli_close($conn);?>