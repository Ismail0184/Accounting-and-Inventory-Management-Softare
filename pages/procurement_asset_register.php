 <?php
require_once 'support_file.php';
$title="Asset Register";




$now=time();
$unique='id';
$unique_field='item_id';
$table="asset_register";
$page="procurement_asset_register.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();

        $id = $_POST['vendor_id'];
        if($_FILES['vtc']['tmp_name']!=''){
            $file_temp = $_FILES['vtc']['tmp_name'];
            $folder = "../../v_pic/vtc/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['vvc']['tmp_name']!=''){
            $file_temp = $_FILES['vvc']['tmp_name'];
            $folder = "../../v_pic/vvc/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['vtl']['tmp_name']!=''){
            $file_temp = $_FILES['vtl']['tmp_name'];
            $folder = "../../v_pic/vtl/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['qt1']['tmp_name']!=''){
            $file_temp = $_FILES['qt1']['tmp_name'];
            $folder = "../../v_pic/qt1/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['qt2']['tmp_name']!=''){
            $file_temp = $_FILES['qt2']['tmp_name'];
            $folder = "../../v_pic/qt2/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        if($_FILES['qt3']['tmp_name']!=''){
            $file_temp = $_FILES['qt3']['tmp_name'];
            $folder = "../../v_pic/qt3/";
            move_uploaded_file($file_temp, $folder.$id.'.jpg');}

        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);

    $id = $_POST['vendor_id'];

    if($_FILES['vtc']['tmp_name']!=''){
        $file_temp = $_FILES['vtc']['tmp_name'];
        $folder = "../../v_pic/vtc/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['vvc']['tmp_name']!=''){
        $file_temp = $_FILES['vvc']['tmp_name'];
        $folder = "../../v_pic/vvc/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['vtl']['tmp_name']!=''){
        $file_temp = $_FILES['vtl']['tmp_name'];
        $folder = "../../v_pic/vtl/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['qt1']['tmp_name']!=''){
        $file_temp = $_FILES['qt1']['tmp_name'];
        $folder = "../../v_pic/qt1/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['qt2']['tmp_name']!=''){
        $file_temp = $_FILES['qt2']['tmp_name'];
        $folder = "../../v_pic/qt2/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}

    if($_FILES['qt3']['tmp_name']!=''){
        $file_temp = $_FILES['qt3']['tmp_name'];
        $folder = "../../v_pic/qt3/";
        move_uploaded_file($file_temp, $folder.$id.'.jpg');}
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
}}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
	
	
	$sql_recommended_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME";	
							  



$chalan_date=date('Y-m-d');
$query = "Select distinct asset_id from  asset_register where create_date='".$chalan_date."' and section_id='".$_SESSION[sectionid]."' and  company_id='".$_SESSION[companyid]."' ORDER BY asset_id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
if ($result)
{
    if (mysqli_num_rows($result) == 0){
        $idates=date('Y-m-d');
        list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $idatess);
        $tdatevalye=substr($year1,2,3).$month.$day;        
        $vnos="".$tdatevalye."001";
        $_SESSION[asset_register_id]= $vnos;
        //echo $_SESSION[challan_auto_number];
    } else {
        while($row = mysqli_fetch_array($result)) {
            $sl= $row['asset_id'];
            $sl=$sl+1;
            if (strlen($sl)==1) {
                $sl="000".$sl;
            } else if (strlen($sl)==2){
                $sl="0".$sl;
            }
            $idatess=date('Y-m-d');
            list( $year1, $month, $day) = preg_split ('[/.-]', $idatess);
            $tdatevalye=substr($year1,2,3).$month.$day;
            $_SESSION[asset_register_id]= $sl;
            //echo $_SESSION[challan_auto_number];
        }}
    mysqli_free_result($result);
}		

$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g 
							
							WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id  in ('1100000000')			 
							  order by i.item_name";
$res='select a.'.$unique.',a.asset_id as Code,a.asset_code,a.specification,d.DEPT_SHORT_NAME as Department,a.where_kept,a.status from '.$table.' a,item_info i,department d
				  where i.item_id=a.item_id and d.DEPT_ID=a.DEPT_ID order by a.'.$unique;							  				  
?>



<?php require_once 'header_content.php'; ?>
<style>
    input[type=text] {
        font-size: 11px;
    }
</style>
<script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>


 <?php if(isset($_GET[$unique])): ?>
<!-- input section-->
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Asset Info</h2>
                               <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                            <?php else: ?>
                            
<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Record</h5>
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <?php endif; ?>
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <?php require_once 'support_html.php';?>

                                    <input type="hidden" id="group_for" style="width:100%"    name="group_for" value="<?=$_SESSION[usergroup];?>" class="form-control col-md-7 col-xs-12" >

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Sub Class<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="select2_single form-control" required name="item_id" id="item_id" style="width:100%;font-size: 11px">
                        <option></option>
                        <?=advance_foreign_relation($sql_item_id,$item_id);?>
                    </select>
                                    </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Asset Code<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="hidden" id="asset_id" style="width:100%"    name="asset_id" value="<?=$_SESSION[asset_register_id];?>" class="form-control col-md-7 col-xs-12" >
                                            <input type="text" id="asset_code" style="width:100%"  required   name="asset_code" value="<?=$asset_code;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Specification</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea type="text" id="specification" style="width:100%; font-size:11px"    name="specification" class="form-control col-md-7 col-xs-12" ><?=$specification;?></textarea>
                                    </div></div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Department</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select name="DEPT_ID" id="DEPT_ID" style="width:100%; height: 30px;margin-top: 5px;font-size: 11px" class="select2_single form-control">
                                            <option></option>
                                <? foreign_relation('department','DEPT_ID','DEPT_DESC',$DEPT_ID,' 1 order by DEPT_ID asc');?>
                            </select>
                                        </div></div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">For Person</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select class="select2_single form-control" style="width: 100%;" tabindex="-1" name="PBI_ID" id="PBI_ID">
                      <option></option>
                      <? advance_foreign_relation($sql_recommended_by,$PBI_ID);?>
                  </select>
                                        </div></div>                                  
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Where Kept	</label>
                             <div class="col-md-6 col-sm-6 col-xs-12">
                               <input type="text" id="where_kept" style="width:100%" name="where_kept" value="<?=$where_kept;?>" class="form-control col-md-7 col-xs-12" ></div></div>

<div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Purchase Date	</label>
                             <div class="col-md-6 col-sm-6 col-xs-12">
                               <input type="date" id="purchase_date" style="width:100%; font-size:11px" name="purchase_date" value="<?=$purchase_date;?>" class="form-control col-md-7 col-xs-12" ></div></div>

<div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Useful Life</label>
                             <div class="col-md-6 col-sm-6 col-xs-12">
                               <input type="date" id="useful_life" style="width:100%;font-size:11px" name="useful_life" value="<?=$useful_life;?>" class="form-control col-md-7 col-xs-12" ></div></div>
                                        <?php if($_GET[$unique]){  ?>
                                            <? if($_SESSION['userlevel']==5){?>                                            
                                             <div class="form-group" style="margin-left:40%; display: none">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                             <input  name="delete" type="submit" class="btn btn-success" id="delete" value="Delete"/>
                                             </div></div>                                             
                                             <? }?>
                                              <?php if($status=='UNUSED'){ ?>
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">                                           
                                            <button type="submit" name="modify" id="modify" class="btn btn-success">Modify</button>
                                            </div></div>
                                            <?php } else { echo '<h6 align="center" style="color:red; margin-top:50px; font-weight:bold">The Asset code used. You cannot modify any info.</h6>';} ?>
                                                                                        
                                            <?php } else {?>                                           
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record" style="font-size:12px"  class="btn btn-primary">New Asset Register</button>
                                            </div></div>                                                                                        
                                            <?php } ?> 


                                </form>
                                </div>
                                </div>
                                </div>
                                <?php if(!isset($_GET[$unique])): ?></div> <?php endif; ?>
<?php if(!isset($_GET[$unique])): ?>
<?=$crud->report_templates_with_add_new($res,$title,12,$action=0,$create=1);?>  
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>