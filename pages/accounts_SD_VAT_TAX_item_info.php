<?php
require_once 'support_file.php';
$title='Item List';
$unique='item_id';
$unique_field='item_name';
$table='item_info';
$page="accounts_SD_VAT_TAX_item_info.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(isset($_POST[$unique_field]))

{

    $$unique = $_POST[$unique];

//for Record..................................

    $_POST['item_name'] = str_replace('"',"``",$_POST['item_name']);
    $_POST['item_name'] = str_replace("'","`",$_POST['item_name']);



    $_POST['item_description'] = str_replace(Array("\r\n","\n","\r"), " ", $_POST['item_description']);
    $_POST['item_description'] = str_replace('"',"``",$_POST['item_description']);
    $_POST['item_description'] = str_replace("'","`",$_POST['item_description']);
    if(isset($_POST['record']))
    {
        $_POST['entry_at']=time();
        $_POST['entry_by']=$_SESSION['user']['id'];
        $min=number_format($_POST['sub_group_id'] + 1, 0, '.', '');
        $max=number_format($_POST['sub_group_id'] + 10000, 0, '.', '');
        $_POST[$unique]=number_format(next_value('item_id','item_info','1',$min,$min,$max), 0, '.', '');

        $crud->insert();



        $type=1;

        $msg='New Entry Successfully Inserted.';



        unset($_POST);

        unset($$unique);

    }



//for Modify..................................



    if(isset($_POST['modify']))

    {



        $_POST['item_name'] = str_replace('"',"``",$_POST['item_name']);
        $_POST['item_name'] = str_replace("'","`",$_POST['item_name']);
        $_POST['item_description'] = str_replace(Array("\r\n","\n","\r"), " ", $_POST['item_description']);
        $_POST['item_description'] = str_replace('"',"``",$_POST['item_description']);
        $_POST['item_description'] = str_replace("'","`",$_POST['item_description']);


        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;

        mysql_query("UPDATE sale_do_chalan SET brand_id='$_POST[item_brand]' where item_id='$_GET[item_id]'");


        //echo $targeturl;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";

    }



//for Delete..................................


    if(isset($_POST['delete']))
    {

        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';
        echo $targeturl;
    }}



if(isset($$unique))
{

    $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}


//for Modify..................................

if($_REQUEST['item_group']>0){$_SESSION['item_group'] = $_REQUEST['item_group'];}
if($_REQUEST['src_sub_group_id']>0){$_SESSION['src_sub_group_id'] = $_REQUEST['src_sub_group_id'];$_SESSION['src_item_id'] = $_REQUEST['src_item_id'];}
if($_REQUEST['item_brand_n']!=""){$_SESSION['item_brand_n'] = $_REQUEST['item_brand_n'];}
if($_REQUEST['src_item_id']!=''){$_SESSION['src_sub_group_id'] = $_REQUEST['src_sub_group_id'];$_SESSION['src_item_id'] = $_REQUEST['src_item_id'];}
if($_REQUEST['fg_code']!=''){$_SESSION['fg_code'] = $_REQUEST['fg_code'];$_SESSION['fg_code'] = $_REQUEST['fg_code'];}
if(isset($_REQUEST['cancel'])){unset($_SESSION['item_group']); unset($_SESSION['item_brand_n']); unset($_SESSION['src_sub_group_id']);unset($_SESSION['src_item_id']);unset($_SESSION['fg_code']);}

if($_SESSION['item_group']>0){
    $item_group = $_SESSION['item_group'];
    $con .='and b.group_id=g.group_id and g.group_id="'.$item_group.'" ';}

if($_SESSION['src_sub_group_id']>0){
    $src_sub_group_id = $_SESSION['src_sub_group_id'];
    $con .='and b.group_id=g.group_id and a.sub_group_id="'.$src_sub_group_id.'" ';}

if($_SESSION['item_brand_n'] !=""){
    $item_brand_n = $_SESSION['item_brand_n'];
    $con .='and b.group_id=g.group_id and a.item_brand="'.$item_brand_n.'" ';}

if($_SESSION['src_item_id']!=''){
    $src_item_id = $_SESSION['src_item_id'];
    $con .='and b.group_id=g.group_id and a.item_name like "%'.$src_item_id.'%" ';}

if($_SESSION['fg_code']>0){
    $fg_code = $_SESSION['fg_code'];
    $con .='and b.group_id=g.group_id and a.finish_goods_code="'.$fg_code.'" ';}
?>





<?php require_once 'header_content.php'; ?>
    <style>
        input[type=text]{
            font-size: 11px;
        }
    </style>
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content.php'; ?>

<?php if(!isset($_GET[item_id])){ ?>
    <!-------------------list view ------------------------->
    <div class="col-md-12 col-sm-12 col-xs-12" style="margin: 0px">
        <div class="x_panel" >
            <div class="x_title">
                <h2>Item List</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <? 	$res='
                                select 
                                i.'.$unique.',
                                i.finish_goods_code as FG_Code,
                                i.'.$unique_field.',
                                sg.sub_group_name,
                                FORMAT(i.SD,15) AS SD_Rate,
                                i.SD_percentage as "SD(%)",
                                FORMAT(i.VAT,15) AS VAT_Rate,
                                i.VAT_percentage as "VAT(%)"
                                                              
                                from                                                                
                                '.$table.' i,
                                item_sub_group sg,
                                item_group g 
                                                               
                                WHERE                                
                                i.sub_group_id=sg.sub_group_id and
                                sg.group_id=g.group_id and 
							    g.group_id in ("500000000")                                                                 
                                 order by sg.sub_group_id,i.'.$unique;
                echo $crud->link_report_popup($res,$link);?>
            </div>

        </div></div>
    <!-------------------End of  List View --------------------->
<?php } ?>


    <?php if(isset($_GET[item_id])){ ?>
    <div  class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Item Info Update</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <div class="input-group pull-right"></div>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">





                <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
                    <? require_once 'support_html.php';?>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Custom Code<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="finish_goods_code" id="finish_goods_code" readonly value="<?=$finish_goods_code?>" style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
                        </div></div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Item Name<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="item_name" style="width:100%; font-size: 12px"  required readonly   name="item_name" value="<?=$item_name;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>










                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Pack Size<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="pack_size" style="width:100%; font-size: 12px"  required readonly  name="pack_size" value="<?=$pack_size;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">VAT Percentage :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="VAT_percentage" style="width:100%; font-size: 12px" name="VAT_percentage" value="<?=$VAT_percentage;?>" class="form-control col-md-7 col-xs-12" class="VAT_percentage">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">VAT Rate :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="VAT" style="width:100%; font-size: 12px" name="VAT" value="<?=$VAT;?>" class="form-control col-md-7 col-xs-12" class="VAT">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">SD Percentage :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="SD_percentage" style="width:100%; font-size: 12px" name="SD_percentage" value="<?=$SD_percentage;?>" class="form-control col-md-7 col-xs-12" class="SD_percentage" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">SD Rate :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="SD" style="width:100%; font-size: 12px" name="SD" value="<?=$SD;?>" readonly  class="form-control col-md-7 col-xs-12" class="SD" >
                        </div>
                    </div>




                    <script>
                        $(function(){
                            $('#VAT, #VAT_percentage,#SD_percentage').keyup(function(){
                                var VAT = parseFloat($('#VAT').val()) || 0;
                                var VAT_percentage = parseFloat($('#VAT_percentage').val()) || 0;
                                var SD_percentage = parseFloat($('#SD_percentage').val()) || 0;
                                $('#SD').val((VAT /((SD_percentage+100)/100) ).toFixed(10));
                            });
                        });
                    </script>


 <br>



                           <div class="form-group" style="margin-left:35%">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button type="submit" name="modify" id="modify" class="btn btn-primary" style="font-size: 12px">Modify SD & VAT Info</button>
                            </div></div>



            </div></div>

        </form>
    </div></div></div>
    <?php } ?>







<?php require_once 'footer_content.php' ?>