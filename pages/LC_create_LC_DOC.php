 <?php
require_once 'support_file.php';
$title="LC Documentation Information";
$now=time();
$unique='id';
$unique_field='lc_no';
$table_LC="lc_lc_master";
$table="lc_documentation_create";
$page="LC_new_doc_open.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

 if($_GET['first_lc']>0)
     unset($_SESSION['lc_id']);
 elseif($_REQUEST['unlc_id']>0)
     $lc_id=$_SESSION['lc_id']=$_REQUEST['unlc_id'];
 elseif($_GET['lc_id']>0){
     $lc_id=$_SESSION['lc_id']=$_GET['lc_id'];
 }
 elseif($_POST['lc_id']>0)
     $lc_id=$_SESSION['lc_id']=$_POST['lc_id'];


if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $_POST['prepared_at'] = date('Y-m-d H:s:i');
        $sd=$_POST[shipment_date];
        $_POST[shipment_date]=date('Y-m-d' , strtotime($sd));

        $exd=$_POST[expiry_date];
        $_POST[expiry_date]=date('Y-m-d' , strtotime($exd));
        $lcd=$_POST[lc_create_date];
        $_POST[lc_create_date]=date('Y-m-d' , strtotime($lcd));
        $lids=$_POST[lc_issue_date];
        $_POST[lc_issue_date]=date('Y-m-d' , strtotime($lids));
        $DD=$_POST[documentation_date];
        $_POST[documentation_date]=date('Y-m-d' , strtotime($DD));
        $_POST[status]='OPENED';
        $sql = 'select sum(d.amount) from lc_lc_details ld,  lc_pi_details d where ld.pi_id=d.pi_id and ld.lc_id='.$_GET['lc_id'];
        $_POST[lc_amount]=find_a_field_sql($sql);
        $crud->insert();
        $type=1;
        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $sd=$_POST[shipment_date];
    $_POST[shipment_date]=date('Y-m-d' , strtotime($sd));
    $exd=$_POST[expiry_date];
    $_POST[expiry_date]=date('Y-m-d' , strtotime($exd));
    $lcd=$_POST[lc_create_date];
    $_POST[lc_create_date]=date('Y-m-d' , strtotime($lcd));
    $lids=$_POST[lc_issue_date];
    $_POST[lc_issue_date]=date('Y-m-d' , strtotime($lids));
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];

    $crud->update($unique);
    $type=1;
    echo $targeturl;

}





//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$_SESSION[initiate_create_LC];
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo $targeturl;

}}

//for modify..................................
    if(isset($_POST['add'])) {
        $_POST['edit_at'] = time();
        $_POST['edit_by'] = $_SESSION['userid'];
        $_POST[lc_id]=$_SESSION[initiate_create_LC];
        $_POST[pi_id]=$_POST[under_lc];
        mysql_query("Update lc_pi_master SET under_lc='".$_SESSION[initiate_create_LC]."' where id=".$_POST[under_lc]."");
        $crud = new crud($table_deatils);
        $crud->insert();
        $type = 1;

    }
}

// data query..................................
if(isset($_GET[lc_id]))
{   $condition=$unique."=".$_GET[lc_id];
    $data=db_fetch_object($table_LC,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

 if (isset($_POST['confirmsave'])){
     mysql_query("Update ".$table." set status='DONE' where ".$unique."=".$_SESSION['initiate_create_LC']."");
     unset($_SESSION['initiate_create_LC']);
 }
?>



<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>



                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <? require_once 'support_html.php';?>

                                    <?
                                    if($_SESSION['lc_id']>0) $lc_idGET =  $_SESSION['lc_id'];
                                    else
                                    {$lc_idGET =  find_a_field('lc_lc_master','max(id)+1','1');
                                        if($lc_idGET<1) $lc_idGET = 1;
                                    }
                                    ?>

                                    <table style="width:100%; display:none"  cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="width:50%;">

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">LC ID<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  name="lc_id2" type="hidden" id="lc_id2" value="<?=$lc_id?>"/>
                                        <input type="text" id="lc_id" style="width:100%;font-size: 11px" readonly    name="lc_id" value="<?=$lc_id;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>      </td>

                                            <td style="width:50%;">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">LC NO<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="lc_no" style="width:100%;font-size: 11px"    name="lc_no" value="<?=$lc_no;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div></td></tr>
                                        <tr>
                                            <td>
                                                <div class="form-group" style="width: 100%">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" >Shipment Date: <span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="shipment_date"  required="required" name="shipment_date" value="<?php if($_SESSION[initiate_create_LC]>0){ echo date('m/d/y' , strtotime($shipment_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" >      </div>
                                                </div>
                                            </td>
                                            <td><div class="form-group" style="width: 100%">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" >Expiry Date:<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="expiry_date"  name="expiry_date" value="<?php if($_SESSION[initiate_create_LC]>0){ echo date('m/d/y' , strtotime($expiry_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" >      </div>
                                                </div></td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-group" style="width: 100%">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" >LC Create Date: <span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="lc_create_date"  required="required" name="lc_create_date" value="<?php if($_SESSION[initiate_create_LC]>0){ echo date('m/d/y' , strtotime($lc_create_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" >      </div>
                                                </div>
                                            </td>
                                            <td> <div class="form-group" style="width: 100%">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" >LC Issue Date: <span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="lc_issue_date"  required="required" name="lc_issue_date" value="<?php if($_SESSION[initiate_create_LC]>0){ echo date('m/d/y' , strtotime($lc_issue_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" >      </div>
                                                </div>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Party Name<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select style="width: 100%" class="select2_single form-control" name="party_id" id="party_id">
                                                            <option></option>
                                                            <?php
                                                            $result=mysql_query("SELECT  * FROM lc_buyer where   1 order by buyer_name");
                                                            while($row=mysql_fetch_array($result)){  ?>
                                                                <option  value="<?=$row[party_id]; ?>" <?php if($party_id==$row[party_id]) echo 'selected' ?>><?=$row[buyer_name]; ?></option>
                                                            <?php } ?></select>
                                                    </div></div>
                                            </td>



                                            <td>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Buyer Name<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select style="width: 100%;" class="select2_single form-control" name="buyer_id" id="buyer_id">
                                                            <option></option>
                                                            <?php
                                                            $result=mysql_query("SELECT  * from lc_brand_buyer
							 where 
							 1				 
							  order by brand_buyer_name");
                                                            while($row=mysql_fetch_array($result)){  ?>
                                                                <option  value="<?=$row[id]; ?>" <?php if($buyer_id==$row[id]) echo 'selected' ?>><?=$row[brand_buyer_name]; ?></option>
                                                            <?php } ?></select>
                                                    </div></div>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Local Bank<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select style="width: 100%" class="select2_single form-control" name="branch_id" id="branch_id">
                                                            <option></option>
                                                            <? foreign_relation('lc_branch','id','concat(bank_name," - ",branch_name)',$branch_id);?></select>
                                                    </div></div>
                                            </td>



                                            <td>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Buyer Bank<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select style="width: 100%;" class="select2_single form-control" name="buyer_branch" id="buyer_branch">
                                                            <option></option>
                                                            <? foreign_relation('lc_foreigner_branch','id','concat(bank_name," - ",branch_name)',$buyer_branch);?></select>
                                                    </div></div>
                                            </td>
                                        </tr>


                                        <table style="width:100%;"  cellpadding="0" cellspacing="0">

                                            <tr>
                                                <td style="width:50%;">

                                                    <div class="form-group">
                                                        <input type="hidden" name="<?=$unique?>" id="<?=$unique?>" value="" >
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">DOC NO:<span class="required">*</span></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                                            <input type="text" id="documentation_id" style="width:100%;font-size: 11px"     name="documentation_id" value="<?=$documentation_id;?>" class="form-control col-md-7 col-xs-12" >
                                                        </div></div>      </td>

                                                <td style="width:50%;">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">DOC Amount<span class="required">*</span></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <input type="text" id="documentation_amount" style="width:100%;font-size: 11px"    name="documentation_amount" value="<?=$documentation_amount;?>" class="form-control col-md-7 col-xs-12" >
                                                        </div></div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="form-group" style="width: 100%">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">DOC Open Date:<span class="required">*</span></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <input type="text" id="documentation_date"  required="required" name="documentation_date" value="<?php if($documentation_date>0){ echo date('m/d/y' , strtotime($documentation_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" >      </div>
                                                    </div>
                                                </td>
                                            </tr>





<tr><td colspan="2">
        <?php if($documentation_id>0){  ?>
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" class="btn btn-primary">Update LC DOC</button>
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
                                            <button type="submit" name="record" id="record"  class="btn btn-primary">OPEN NEW LC DOC</button>
                                            </div></div>                                                                                        
                                            <?php } ?>
    </td></tr></table>


                                </form>
                                </div>
                                </div>
                                </div>




 <br><br>

                
        
<?php require_once 'footer_content.php' ?>
 <script>
     $(document).ready(function() {
         $('#shipment_date').daterangepicker({

             singleDatePicker: true,
             calender_style: "picker_4",

         }, function(start, end, label) {
             console.log(start.toISOString(), end.toISOString(), label);
         });
     });
 </script>


 <script>
     $(document).ready(function() {
         $('#expiry_date').daterangepicker({

             singleDatePicker: true,
             calender_style: "picker_4",

         }, function(start, end, label) {
             console.log(start.toISOString(), end.toISOString(), label);
         });
     });
 </script>
 <script>
     $(document).ready(function() {
         $('#lc_create_date').daterangepicker({

             singleDatePicker: true,
             calender_style: "picker_4",

         }, function(start, end, label) {
             console.log(start.toISOString(), end.toISOString(), label);
         });
     });
 </script>
 <script>
     $(document).ready(function() {
         $('#lc_issue_date').daterangepicker({

             singleDatePicker: true,
             calender_style: "picker_4",

         }, function(start, end, label) {
             console.log(start.toISOString(), end.toISOString(), label);
         });
     });
 </script>
 <script>
     $(document).ready(function() {
         $('#documentation_date').daterangepicker({

             singleDatePicker: true,
             calender_style: "picker_4",

         }, function(start, end, label) {
             console.log(start.toISOString(), end.toISOString(), label);
         });
     });
 </script>

