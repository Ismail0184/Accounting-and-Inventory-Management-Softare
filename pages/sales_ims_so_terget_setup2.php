<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='target_no';
$table="ims_monthly_target_master";
$table_details="ims_monthly_target_details";
$page='sales_ims_so_terget_setup.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$target_master=find_all_field("".$table."","","".$unique."=".$$unique."");

$create_date=find_a_field('ims_monthly_target_master','create_date','target_no='.$_GET[target_no].'');

$current_day=date('d', strtotime($create_date));;
$current_month=date('m');
$current_year=date('Y');
$prevmonth = date('m', strtotime("last month"));
$pdate='2020-01-'.$current_day;
$cdate='2020-02-'.$current_day;




if(prevent_multi_submit()){

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $results="select i.*,tp.*,
SUM(d.total_amt_ims) as achivement


from 
item_info i,
effective_tp tp,
ims_details d
where 
i.sub_group_id in ('200010000') and
i.item_id=tp.item_id and 
i.exim_status not in ('Export') and 
i.brand_category not in ('Rice') and 
i.status in ('Active') and
d.ims_date between '".$pdate."' and '".$cdate."' and
d.item_id=i.item_id and 
d.PBI_ID=".$target_master->PBI_ID." and
d.total_amt_ims>0

group by d.item_id

order by i.serial";
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)) {
            $i = $i + 1;
            $ids = $row[item_id];
            $pre_target_amount=$_POST['pre_target_amount_'.$ids];
            $rev=mysqli_query($conn, "Update ".$table_details." SET pre_target_amount='".$pre_target_amount."' where  item_id='$ids' and ".$unique."=".$$unique." ");
        }
    }

    //for Delete..................................
    if(isset($_POST['add']))
    {
        $add=mysqli_query($conn, "Update ".$table_details." set item_status=1 where item_id=".$_POST[item_id]." and ".$unique."=".$$unique."");
    }


//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table_details);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);

        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}


$GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);

?>


<?php require_once 'header_content.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>



<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque; vertical-align: middle">
                            <th>SL</th>
                            <th>Code</th>
                            <th>Finish Goods</th>
                            <th style="width:5%; text-align:center">UOM</th>
                            <th style="text-align:center">Achievement</th>
                            <th style="text-align:center">Proposed Target</th>
                            <th style="text-align:center">Checked Target</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php

                        //$results="Select d.id,d.target_proposal,d.unit_price,d.amount,i.item_name,i.unit_name,i.item_id,i.finish_goods_code,i.pack_size,
//(SELECT SUM(total_amt_ims) from ims_details where item_id=d.item_id and ims_date between '".$pdate."' and '".$cdate."' and PBI_ID=d.PBI_ID and total_unit_today>0) as pre_target
 //from ".$table_details." d, item_info i  where
 //d.item_id=i.item_id and
 //d.target_proposal>0 and
 //d.".$unique."=".$$unique." group by d.item_id order by i.serial";

                        $results="select i.*,tp.*,
SUM(d.total_amt_ims) as achivement


from 
item_info i,
effective_tp tp,
ims_details d
where 
i.sub_group_id in ('200010000') and
i.item_id=tp.item_id and 
i.exim_status not in ('Export') and 
i.brand_category not in ('Rice') and 
i.status in ('Active') and
d.ims_date between '".$pdate."' and '".$cdate."' and
d.item_id=i.item_id and 
d.PBI_ID=".$target_master->PBI_ID." and
d.total_amt_ims>0

group by d.item_id

order by i.serial";

                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[item_id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                                <td style="vertical-align:middle"><?=$row[item_id]?> - <?=$row[finish_goods_code];?></td>
                                <td style="vertical-align:middle; width: 25%"><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><input type="text" style="width: 100px; text-align: right" name="pre_target_amount_<?=$ids;?>" id="pre_target_amount_<?=$ids;?>" value="<?=$row[achivement];?>"></td>
                                <td align="center" style=" text-align:center"><?=$row[target_proposal]/$row[pack_size];?></td>
                                <input  type="hidden" style="height: 25px;" value="<?=$row[pack_size]?>" name="pack_size_<?=$ids;?>" id="pack_size_<?=$ids;?>" class='pack_size_<?=$ids;?>'>
                                <td align="center" style=" text-align:center"><input type="text" style="width: 100px; text-align: center" name="target_revised_<?=$ids;?>" id="target_revised_<?=$ids;?>" value="<?php if ($row[target_proposal]>0) echo $row[target_proposal]/$row[pack_size]; else echo '';?>" class="target_revised_<?=$ids;?>"></td>
                                <td align="center" style=" text-align:right"><input type="text" style="width: 100px; text-align: right" name="unit_price<?=$ids;?>" id="unit_price<?=$ids;?>" value="<?=$row[unit_price];?>" readonly class="unit_price<?=$ids;?>"></td>
                                <td align="center" style=" text-align:right"><input type="text" style="width: 100px; text-align: right" name="amount_<?=$ids;?>" id="amount_<?=$ids;?>" readonly value="<?php if($row[amount]>0) echo $row[amount]; else echo '';?>" class="sum"></td>

                            </tr>
                            <script>
                                $(function(){
                                    $('#unit_price<?=$ids;?>, #target_revised_<?=$ids;?>').keyup(function(){
                                        var unit_price<?=$ids;?> = parseFloat($('#unit_price<?=$ids;?>').val()) || 0;
                                        var target_revised_<?=$ids;?> = parseFloat($('#target_revised_<?=$ids;?>').val()) || 0;
                                        var pack_size_<?=$ids;?> = parseFloat($('#pack_size_<?=$ids;?>').val()) || 0;
                                        $('#amount_<?=$ids;?>').val(((target_revised_<?=$ids;?> * pack_size_<?=$ids;?>)*unit_price<?=$ids;?>).toFixed(2));
                                    });
                                });
                            </script>
                            <?php
                            $ttotalamount=$ttotalamount+$row[amount];
                            $tpre_target=$tpre_target+$row[achivement];

                        }


                        ?>

                        </tbody>
                        <script>
                            // we used jQuery 'keyup' to trigger the computation as the user type
                            $('.sum').blur(function () {
                                // initialize the sum (total price) to zero
                                var sum = 0;
                                // we use jQuery each() to loop through all the textbox with 'price' class
                                // and compute the sum for each loop
                                $('.sum').each(function() {
                                    sum += Number($(this).val());
                                });
                                // set the computed value to 'totalPrice' textbox
                                $('#totalPrice').val((sum).toFixed(2));
                            });
                        </script>

                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Total Target in Amount</td>
                            <td style="text-align:right"><?=number_format($tpre_target,2);?></td>
                            <td style="text-align:center"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td align="right" ><input style="height: 25px; width: 80px;font-size: 11px; text-align: right" type='text' id='totalPrice' value="<?=number_format($ttotalamount,2);?>" disabled /></td>
                        </tr>
    <?php if($GET_status=='UNCHECKED'){ ?>
                        <tr><td colspan="3" style="vertical-align: middle; text-align: right">Search New Item</td>
                            <td colspan="5"><select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id">
                                    <option>0</option>
                                    <? $sql_item_id="SELECT i.item_id,concat(i.finish_goods_code,' : ',i.item_name) FROM  item_info i,".$table_details." d
                                     WHERE  i.item_id=d.item_id and 
                                     d.target_no=".$_GET[target_no]." and 
                                     d.item_status in ('0')							 
							  order by i.item_id";
                                    advance_foreign_relation($sql_item_id,$item_id);?>
                                </select>
                            </td><td><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 12px">Add</button></td></tr>
        <?php } ?>
                    </table>
                    <?php mysqli_close($conn); ?>


                        <br><br>
                        <p>
                            <button style="float: right;" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Checked the Target </button>
                        </p>

                </form>
            </div>
        </div>
    </div>

<?php } ?>
<?php require_once 'footer_content.php' ?>