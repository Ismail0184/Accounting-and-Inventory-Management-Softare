<?php

require_once 'support_file.php';
$title='Select Dealer for DO';
$table_master='sale_do_master';
$unique_master='do_no';
$table_detail='sale_do_details';
$unique_detail='id';
$table_chalan='sale_do_chalan';
$unique_chalan='id';
$$unique_master=$_POST[$unique_master];
if(isset($_POST['delete']))

{



    $crud   = new crud($table_master);

    $condition=$unique_master."=".$$unique_master;

    $crud->delete($condition);

    $crud   = new crud($table_detail);

    $crud->delete_all($condition);

    $crud   = new crud($table_chalan);

    $crud->delete_all($condition);

    unset($$unique_master);

    unset($_POST[$unique_master]);

    unset($_SESSION['COMWR']);

    unset($_SESSION['dealer_code_GET']);

    unset($_SESSION['old_do_find']);

    $type=1;

    $msg='Successfully Deleted.';







}







if(isset($_POST['confirm']))

{		unset($_POST);

    $_POST[$unique_master]=$$unique_master;

    $_POST['entry_at']=date('Y-m-d H:s:i');

    $_POST['status']='PROCESSING';

    $crud   = new crud($table_master);

    $crud->update($unique_master);

    $crud   = new crud($table_detail);

    $crud->update($unique_master);

    $DOTY = find_a_field('sale_do_master','do_type','do_no="'.$$unique_master.'" ');

    mysql_query("UPDATE sale_do_details SET do_type='".$DOTY."' WHERE do_no='".$$unique_master."'");

    $dooo=$$unique_master;



    $dealerGETDATA = find_all_field('dealer_info','','dealer_code='.$_SESSION['dlrid']);

    $DOTOTA = find_a_field('sale_do_details','SUM(total_amt)','do_no="'.$$unique_master.'"');

    $COMAMOUNT=($DOTOTA/100)*$dealerGETDATA->commission;



    if($COMAMOUNT>0){

        mysql_query("INSERT INTO sale_do_details (do_no,item_id,dealer_code,dealer_type,town,area_code,territory,region,unit_price,pkt_size,pkt_unit,dist_unit,total_unit,total_amt,depot_id,status,do_date,do_type) VALUES ('$dooo','1096000100010313','$_SESSION[dlrid]','$dealerGETDATA->customer_type','$dealerGETDATA->town','$dealerGETDATA->area_code','$dealerGETDATA->territory','$dealerGETDATA->region','','1','','','','-$COMAMOUNT','$_SESSION[DEPID]','PROCESSING','','$DOTY')");



    }



    mysql_query("UPDATE sale_do_master SET commission_amount='$_SESSION[COMWR]' where do_no='".$$unique_master."'");



    unset($$unique_master);

    unset($_POST[$unique_master]);

    unset($_SESSION['dlrid']);

    unset($_SESSION['DEPID']);

    unset($_SESSION['COMWR']);

    unset($_SESSION['dealer_code_GET']);

    unset($_SESSION['old_do_find']);
    $type=1;
    $msg='Successfully Instructed to Depot.';}






?>




<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>





                    <!-- input section-->

                    <div class="col-md-12 col-sm-12 col-xs-12">

                        <div class="x_panel">


                            <div class="x_content">




                    <form  name="addem" id="addem" action="do_MT.php" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
                    <? require_once 'support_html.php';?>
                    <table style="width:70%; font-size: 11px" align="center">
                    <tr>
                    <td style="vertical-align: middle">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"  >Select a Dealer<span class="required">*</span></label>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                             <select class="select2_single form-control" required name="dealer" id="dealer" style="width: 100%; font-size: 12px">
                                    <option value=""></option>
                                    <?
             $sql="Select dealer_code,dealer_custom_code,dealer_name_e from dealer_info where  customer_type in ('SuperShop','Corporate')";
                                    $led=mysql_query($sql);
                                    if(mysql_num_rows($led) > 0)
                                    { while($ledg = mysql_fetch_row($led)){?>
     <option value="<?=$ledg[0]?>" <?php if($data[2]==$ledg[0]) echo " Selected "?>><?=$ledg[1];?>-<?=$ledg[2];?></option>

                                        <? }}?>

                                </select></div></div></td>




                       <td style="float: left; vertical-align: middle">
                        <div align="center" class="form-group">                           
                            <div class="col-md-6 col-sm-6 col-xs-12">
              <button type="submit" name="submitit" id="submitit" class="btn btn-primary" style="font-size: 11px">Create New DO</button></div></div></td>
              </tr></table>





                    </form>



                            </div></div></div>

                    <!-- input section-->





<!-------------------End of  List View --------------------->

                </div>

            </div>

        </div>

<!---page content----->















<?php require_once 'footer_content.php' ?>