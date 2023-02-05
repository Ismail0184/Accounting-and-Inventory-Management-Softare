<?php
require_once 'support_file.php';
$title='Add New Material';
$unique='item_id';
$unique_field='item_name';
$table='item_info';
$page="LC_create_material.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
unset($_SESSION[input_sg]);
unset($_SESSION[input_unit_name]);
unset($_SESSION[input_sales_unit_name]);

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
        $_SESSION[input_sg]=$_POST['sub_group_id'];
        $_SESSION[input_unit_name]=$_POST['unit_name'];
        $_SESSION[input_sales_unit_name]=$_POST['pack_unit'];

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
<?php require_once 'body_content.php'; ?>

<?php if(!isset($_GET[item_id])){ ?>
    <!-------------------list view ------------------------->
    <div class="col-md-7 col-sm-12 col-xs-12" style="margin: 0px">
        <div class="x_panel" >
            <div class="x_title">
                <h2>Material List</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <? 	$res='
                                select 
                                i.'.$unique.',
                                i.'.$unique.' as code,
                                i.'.$unique_field.',
                                g.sub_group_name 
                                
                                from 
                                                               
                                '.$table.' i,
                                item_sub_group g
                                
                                WHERE
                                
                                i.sub_group_id=g.sub_group_id and 
                                g.sub_group_id not in ("500010000")
                                                                 
                                 order by g.sub_group_id,i.'.$unique;
                echo $crud->link_report_popup($res,$link);?>
                <?=paging(10);?>
            </div>

        </div></div>
    <!-------------------End of  List View --------------------->
<?php } ?>


    <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title; ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">





                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                    <? require_once 'support_html.php';?>

                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Custom Code<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="finish_goods_code" id="finish_goods_code" value="<?=$finish_goods_code?>" style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
                        </div></div-->


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Material Name<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="item_name" style="width:100%; font-size: 12px"  required   name="item_name" value="<?=$item_name;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>

                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Material Description<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="item_description" style="width:100%; height: 80px; font-size: 12px" name="item_description" value="<?=$item_name;?>" class="form-control col-md-7 col-xs-12" ></textarea>
                        </div>
                    </div-->

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Sub Group<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php


                            $postsubgroup=find_a_field('item_sub_group','sub_group_name','sub_group_id='.$_SESSION[input_sg].'');
                            $a2="select sub_group_id, sub_group_name from item_sub_group where status in ('1')";
                            //echo $a2;
                            $a1=mysql_query($a2);
                            echo "<select class=\"select2_single form-control\" name=\"sub_group_id\" id=\"sub_group_id\"\" required>";
                            echo "<option value='$_SESSION[input_sg]'>$postsubgroup</option>";
                            while($a=mysql_fetch_row($a1))
                            {



                                if($a[0]==$sub_group_id)
                                    echo "<option value=\"".$a[0]."\" selected>".$a[1]."</option>";
                                else
                                    echo "<option value=\"".$a[0]."\">".$a[1]."</option>"; }
                            echo "</select>";  ?></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Consumable<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" required name="consumable_type" id="consumable_type">
                                <option value="<?=$consumable_type?>"><?=$consumable_type?></option>
                                <option value="Consumable" selected>Consumable</option>
                                <option value="Non-Consumable">Non-Consumable</option>
                                <option value="Service">Service</option>
                            </select></div>
                    </div>


                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Product Nature<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" name="product_nature" id="product_nature">
                                <option value="<?=$product_nature?>"><?=$product_nature?></option>
                                <option value="Salable">Salable</option>
                                <option value="Purchasable">Purchasable</option>
                                <option value="Both">Both</option>
                            </select></div></div-->




                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Type:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" name="exim_status" id="exim_status">
                                <option></option>
                                <option value="Local" <?php if($exim_status=='Local') echo 'selected' ?>>Local</option>
                                <option value="Export" <?php if($exim_status=='Export') echo 'selected' ?>>Export</option>
                                <option value="Import" <?php if($exim_status=='Import') echo 'selected' ?>>Import</option>
                            </select></div>
                    </div-->


                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Product Category<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" name="brand_category" id="brand_category">
                                <option value="<?=$brand_category?>" selected="selected"><?=$brand_category?></option>
                                <?php
                                $cateresult=mysql_query("Select * from brand_category");
                                while($cgoryrow=mysql_fetch_array($cateresult)){ ?>
                                    <option value="<?=$cgoryrow[category_name]?>"><?=$cgoryrow[category_name]?></option>
                                <?php } ?></select></div>
                    </div-->



                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Brand<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" name="item_brand" id="item_brand">
                                <option value="<?=$item_brand?>" selected="selected"><?=$item_brand?></option>
                                <?php
                                $brandresult=mysql_query("Select * from brand");
                                while($brandyrow=mysql_fetch_array($brandresult)){ ?>
                                    <option value="<?=$brandyrow[brand_name]?>"><?=$brandyrow[brand_name]?></option>
                                <?php } ?></select></div>
                    </div-->




                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Product Type<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" name="sales_item_type" id="sales_item_type">
                                <option value="<?=$sales_item_type?>" selected="selected"><?=$sales_item_type?></option>
                                <?php
                                $item_typeresult=mysql_query("Select * from item_type");
                                while($typerow=mysql_fetch_array($item_typeresult)){ ?>
                                    <option value="<?=$typerow[item_type]?>"><?=$typerow[item_type]?></option>
                                <?php } ?></select></div></div-->




                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Unit Name<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            $a2="select unit_name, unit_name from unit_management";
                            //echo $a2;
                            $a1=mysql_query($a2);
                            echo "<select class=\"select2_single form-control\" name=\"unit_name\" id=\"unit_name\"\">";
                            echo "<option value='$_SESSION[input_unit_name]' selected>$_SESSION[input_unit_name]</option>";
                            while($a=mysql_fetch_row($a1))
                            {
                                if($a[0]==$unit_name)
                                    echo "<option value=\"".$a[0]."\" selected>".$a[1]."</option>";
                                echo "<option value=\"".$a[0]."\">".$a[1]."</option>";
                            }echo "</select>";
                            ?></div>
                    </div>



                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Sale Unit Name<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php

                            $a2="select unit_name, unit_name from unit_management";
                            //echo $a2;
                            $a1=mysql_query($a2);
                            echo "<select class=\"select2_single form-control\" name=\"pack_unit\" id=\"pack_unit\"\">";
                            echo "<option value='$_SESSION[input_sales_unit_name]' selected>$_SESSION[input_sales_unit_name]</option>";
                            while($a=mysql_fetch_row($a1))
                            {
                                if($a[0]==$pack_unit)
                                    echo "<option value=\"".$a[0]."\" selected>".$a[1]."</option>";
                                else
                                    echo "<option value=\"".$a[0]."\">".$a[1]."</option>";  }
                            echo "</select>";?>
                        </div></div>



                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Pack Size<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="pack_size" style="width:100%; font-size: 12px"  required   name="pack_size" value="1" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>



                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Gross Weight<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="g_weight" style="width:100%; font-size: 12px"    name="g_weight" value="<?=$g_weight;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div-->



                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Material Cost<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="material_cost" style="width:100%; font-size: 12px"    name="material_cost" value="<?=$material_cost;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>



                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Conversion Cost<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="conversion_cost" style="width:100%; font-size: 12px" name="conversion_cost" value="<?=$conversion_cost;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">COGS (Including CC):<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="production_cost" style="width:100%; font-size: 12px" name="production_cost" value="<?=$production_cost;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Shelf Life:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="shelf_life" style="width:100%; font-size: 12px" name="shelf_life" value="<?=$shelf_life;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Dealer Price:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="d_price" style="width:100%; font-size: 12px" name="d_price" value="<?=$d_price;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Supershop Price:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="s_price" style="width:100%; font-size: 12px" name="s_price" value="<?=$s_price;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Trade Price:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="t_price" style="width:100%; font-size: 12px" name="t_price" value="<?=$t_price;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Market Price:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="m_price" style="width:100%; font-size: 12px" name="m_price" value="<?=$m_price;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Revenue (%) :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="revenue" style="width:100%; font-size: 12px" name="revenue" value="<?=$revenue;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Re Purchase level:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="re_purchase_level" style="width:100%; font-size: 12px" name="re_purchase_level" value="<?=$re_purchase_level;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Quantity Type:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            $a2="select unit_name, unit_name from unit_management";
                            //echo $a2;
                            $a1=mysql_query($a2);
                            echo "<select class=\"select2_single form-control\" name=\"quantity_type\" id=\"quantity_type\"\">";
                            echo "<option value=\"\" selected></option>";
                            while($a=mysql_fetch_row($a1))
                            {
                                if($a[0]==$quantity_type)
                                    echo "<option value=\"".$a[0]."\" selected>".$a[1]."</option>";
                                echo "<option value=\"".$a[0]."\">".$a[1]."</option>";
                            }
                            echo "</select>";
                            ?></div></div-->

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Status:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" name="status" id="status">
                                <option value="Active" <?php if($status=='Active') echo 'selected' ?>>Active</option>
                                <option value="Inactive" <?php if($status=='Inactive') echo 'selected' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Comission Status:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" name="commission_status" id="commission_status">
                                <option></option>
                                <option value="1" <?php if($commission_status=='1') echo 'selected' ?>>Active</option>
                                <option value="0" <?php if($commission_status=='0') echo 'selected' ?>>Inactive</option>
                            </select></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Product Type:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" name="producttype" id="producttype">
                                <option value="<?=$producttype?>">
                                    <?=$producttype?>
                                </option>
                                <option value="Personal Care">Personal Care</option>
                                <option value="Food">Food</option>
                                <option value="Other">Other</option>
                            </select></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Serial:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="serial" style="width:100%; font-size: 12px" name="serial" value="<?=$serial;?>" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div-->




                    <br>


                    <?php if($_GET[item_id]){  ?>
                        <? if($_SESSION['userlevel']==5){?>
                            <div class="form-group" style="margin-left:40%; display: none">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input  name="delete" type="submit" class="btn btn-success" id="delete" value="Delete"/></div></div>
                        <? }?>

                        <div class="form-group" style="margin-left:40%">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button type="submit" name="modify" id="modify" class="btn btn-success">Modify Item Info</button>
                            </div></div>


                    <?php   } else {?>
                        <div class="form-group" style="margin-left:40%">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button type="submit" name="record" id="record"  class="btn btn-primary">Add New Item</button></div></div>


                    <?php } ?>
            </div></div>

        </form>
    </div></div></div>


    </div>
    </div>
    </div>
    <!---page content----->







<?php require_once 'footer_content.php' ?>