<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Item Specifications';
$unique='id';
$unique_field='item_id';
$table='item_SPECIFICATION';
$page="item_specifications.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];


if(prevent_multi_submit()) {
    if(isset($_POST['add']) ) {
        $crud->insert();
        unset($_POST);
    }
}



$res = "select isp.id,i.item_name,i.unit_name,p.PARAMETERS_Name,isp.RESULT,isp.SPECIFICATION from ".$table." isp, item_info i,PARAMETERS p where isp.item_id=i.item_id and isp.TEST_PARAMETERS=p.id";
$query = mysqli_query($conn, $res);
while ($data = mysqli_fetch_object($query)) {
    if(isset($_POST['deletedata'.$data->id]))
    {  $res=mysqli_query($conn, ("DELETE FROM ".$table." WHERE id=".$data->id));
        unset($_POST);
    }
    if(isset($_POST['editdata'.$data->id]))
    {  mysqli_query($conn, ("UPDATE ".$table." SET item_id='".$_POST[item_id]."', TEST_PARAMETERS='".$_POST[TEST_PARAMETERS]."',RESULT='".$_POST[RESULT]."',SPECIFICATION='".$_POST[SPECIFICATION]."' WHERE id=".$data->id));
        unset($_POST);
        header('Location: '.$page.'');
    } // end of editdata
}

$item_master = find_all_field('item_info','','item_id='.$_GET['item_id']);
$COUNT_details_data=find_a_field(''.$table.'','Count(id)','item_id='.$_GET['item_id'].'');
$res = "select isp.id,i.item_name,i.unit_name,p.PARAMETERS_Name,isp.RESULT,isp.SPECIFICATION from ".$table." isp, item_info i,PARAMETERS p where isp.item_id=i.item_id and isp.TEST_PARAMETERS=p.id";
if (isset($_GET[id])) {
    $edit_value=find_all_field(''.$table.'','','id='.$_GET[id].'');
}
?>





<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.item_id.options[form.item_id.options.selectedIndex].value;
	self.location='item_specifications.php?item_id=' + val ;
}</script>
<?php require_once 'body_content.php'; ?>
             <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                          <table align="center" style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                              <thead>
                              <tr style="background-color: #3caae4; color:white">
                                  <th style="text-align: center; vertical-align: middle">Item Name</th>
                                  <th style="text-align: center; vertical-align: middle">Unit</th>
                                  <th style="text-align: center; vertical-align: middle">Parameter</th>
                                  <th style="text-align: center">Result</th>
                                  <th style="text-align: center">Specification</th>
                                  <th style="text-align: center">Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td style="vertical-align: middle">
                                      <select class="select2_single form-control" <?php if ($_GET['id']>0) {} else {?>  onchange="javascript:reload(this.form)" <?php } ?> style="font-size: 11px" tabindex="-1" required="required"  name="item_id" id="item_id" >
                                              <option></option>
                                              <? advance_foreign_relation(find_all_item($product_nature="'Purchasable','Both'"),($_GET[item_id]>0)? $_GET[item_id] : $edit_value->item_id);?>
                                          </select>
                                  </td>
                                  <td style="vertical-align: middle"><?=$item_master->unit_name;?></td>
                                  <td style="vertical-align: middle">
                                      <select class="select2_single form-control"  style="font-size: 11px" tabindex="-1" required="required"  name="TEST_PARAMETERS" id="TEST_PARAMETERS" >
                                          <option></option>
                                          <?=foreign_relation('PARAMETERS', 'id', 'concat(PARAMETERS_CODE," : ", PARAMETERS_Name)',$edit_value->TEST_PARAMETERS, '1'); ?>
                                      </select>
                                  </td>
                                  <td style="vertical-align: middle">
                                      <input type="text" id="RESULT" style="font-size: 11px"   name="RESULT" value="<?=$edit_value->RESULT?>" class="form-control col-md-7 col-xs-12" >
                                  </td>
                                  <td style="vertical-align: middle"><textarea name="SPECIFICATION" id="SPECIFICATION" style="font-size: 11px" class="form-control"><?=$edit_value->SPECIFICATION?></textarea></td>
                                  <td style="vertical-align: middle"><?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                                      <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td></tr>
                              </td>
                              </tr>
                              </tbody>
                          </table>
                      </form>
                  </div>
                </div>
             </div>


<?=added_data_delete_edit($res,$unique,$_SESSION['initiate_debit_note'],$COUNT_details_data);?>

<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>