<?php
require_once 'support_file.php';
$title="LC Documents List";
$now=time();
$unique='documentation_id';
$unique_field='lc_no';
$table="lc_documentation_create";
$page="LC_proforma_view.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

//for modify..................................
    if(isset($_POST['modify']))
    {
        $_POST['status']='COMPLETED';
        $crud->update($unique);
        $type=1;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['delete']))
    {   $condition=$unique."=".$$unique;
        $crud->delete($condition);

        mysql_query("delete from ims_details where ims_no='".$$unique."'");
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
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=730,height=600,left = 383,top = -1");}
    </script>
</head>
<?php require_once 'body_content.php'; ?>


<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                    <table style="width:100%;font-size: 12px"  class="table table-striped table-bordered">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>IMS Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                        </tr>

                        <?php
                        $res=mysql_query("select * from item_info where sub_group_id in ('200010000') and exim_status not in ('Export') and brand_category not in ('Rice') and status in ('Active') order by serial");
                        while($item=mysql_fetch_array($res)){
                            $imsdetails=find_all_field('ims_details','','item_id="'.$item[item_id].'" and ims_no='.$_GET[$unique] );
                            $id=$item[item_id];
                            ?>
                            <tr>
                                <td><?=$i=$i+1;?></td>
                                <td><?=$item[item_name];?></td>
                                <td style="text-align: center"><?=$imsdetails->total_unit_ims;?></td>
                                <td style="text-align: right"><?=$imsdetails->unit_price;?></td>
                                <td style="text-align: right"><?=$imsdetails->total_amt_ims;?></td>
                            </tr>
                            <?php $totalIMS=$totalIMS+$imsdetails->total_amt_ims;} ?>
                        <tr>
                            <td colspan="4" align="right"><strong>IMS TOTAL = </strong></td>
                            <td align="right"><strong><?=number_format($totalIMS,2);?></strong></td>
                        </tr>
                    </table>




                    <?php
                    $GET_status=find_a_field($table,'status','ims_no='.$_GET[$unique]);
                    if($GET_status!=='COMPLETED'){  ?>
                    <p>
                        <button style="float: left" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>DELETED</button>
                        <button style="float: right" type="submit" name="modify" id="modify" class="btn btn-success" onclick='return window.confirm("Are you confirm?");'>CHECKED & FINISHED</button>
                        <? } else {echo '<h5 style="text-align: center; color: black; font-style: italic; background-color: red">This IMS Data has been Verified!!</h5>';}?>
                    </p>



                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>

    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="text" id="f_date" style="width:150px"  value="<?=$_POST[f_date]?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="text" id="t_date" style="width:150px"  value="<?=$_POST[t_date]?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" name="viewreport"  class="btn btn-primary">View Available LC DOC</button></td>


            </tr></table>
        <!-------------------list view ------------------------->

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">


                <div class="x_content">
                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                        <thead><tr>
                            <th>#</th>
                            <!--th>Proforma Invoice</th-->
                            <th>LC NO</th>
                            <th>DOC ID</th>
                            <th>DOC Date</th>
                            <th>Bank Name</th>
                            <th>Maturity Days</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php

                        $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                        $to_date=date('Y-m-d' , strtotime($_POST[t_date]));

                        if(isset($_POST[viewreport])){
                            $con.= ' and a.documentation_date BETWEEN  "'.$from_date.'" and "'.$to_date. '"';
                            $res=mysql_query('SELECT a.id, a.id,a.lc_id,a.lc_no, a.documentation_id,   a.documentation_date, concat( c.bank_name, " ", c.branch_name ) AS Bank_Name, a.maturity_days, a.documentation_amount as amount 
FROM lc_documentation_create a,  lc_branch c

WHERE  a.status="OPENED" and  a.branch_id = c.id '.$con. ' order by a.documentation_id asc');
                            while($data=mysql_fetch_object($res)){
                                ?>
                                <tr  onclick="DoNavPOPUP('<?=$data->id?>', 'TEST!?', 900, 600)">
                                    <td><?=$i=$i+1;?></td>
                                    <td><?=$data->lc_no;?></td>
                                    <td><?=$data->documentation_id;?></td>
                                    <td><?=$data->documentation_date;?></td>
                                    <td><?=$data->Bank_Name;?></td>
                                    <td><?=$data->maturity_days;?></td>
                                    <td><?=$data->amount;?></td>
                                </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
                </div>

            </div></div></form>
    <!-------------------End of  List View --------------------->
<?php } ?>
<!---page content----->




<?php require_once 'footer_content.php' ?>