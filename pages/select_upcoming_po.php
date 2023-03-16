 <?php
require_once 'support_file.php';
$title="GRN";
$now=time();
$unique='po_no';
$unique_field='name';
$table='';
$page="po_receive.php";

if($_POST['f_date']){
$f_date =$_POST[f_date];
$fdate=date('Y-m-d' , strtotime($f_date));}

if($_POST['t_date']){
$t_date =$_POST[t_date];
$tdate=date('Y-m-d' , strtotime($t_date));}
$print_page="po_print_view.php";

?>



<?php require_once 'header_content.php'; ?>
   <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 230,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>


                                

<?
if(isset($_POST['submitit'])){                                
if($_POST['vendor_id']!=''){	
$vendorcon='and a.vendor_id="'.$_POST['vendor_id'].'"' ;	
} else {$vendorcon='';}

                                $con .= 'and a.po_date between "'.$fdate.'" and "'.$tdate.'"';
                                $res='select  a.po_no,a.po_no, a.po_date as "PO Date", v.vendor_name,
								CONCAT(c.fname, " <br> at: ", a.entry_at) as "Entry_by",
								CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=a.checkby), " <br> at: ", a.checkby_date) as checked_by,
								CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=a.recommended), " <br> at: ", a.recommended_date) as recommended_by,
								CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=a.authorise), " <br> at: ", a.authorized_date) as authorized_by, a.status 
                               from  
                               purchase_master a,
                               warehouse b,
                               users c, 
                               vendor v 
                               where   
                               a.warehouse_id=b.warehouse_id and 
                               a.entry_by=c.user_id and 
                               a.vendor_id=v.vendor_id   and 
                               a.po_type not in ("Asset")  '.$con.$vendorcon.' 
                               order by a.po_no DESC';

                               } else {
                               
                                $res='select  a.po_no,a.po_no, a.po_date as "PO Date", v.vendor_name,
								CONCAT(c.fname, " <br> at: ", a.entry_at) as "Entry_by",
								CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=a.checkby), " <br> at: ", a.checkby_date) as checked_by,
								CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=a.recommended), " <br> at: ", a.recommended_date) as recommended_by,
								CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=a.authorise), " <br> at: ", a.authorized_date) as authorized_by, a.status 
								from 
								purchase_master a,
								warehouse b,
								users c, 
								vendor v 
								where  
								a.warehouse_id=b.warehouse_id and 
								a.entry_by=c.user_id and 
								a.vendor_id=v.vendor_id and 
								a.status="PROCESSING" and
								a.po_type not in ("Asset")
								order by a.po_no DESC';
                                }?>
                                    
                   
                   
<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size:11px">
    <?php require_once 'support_html.php';?>
    <table align="center" style="width: 60%;">
        <tr><td><input type="date"  style="width:150px; font-size: 11px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" required max="<?=date('Y-m-d')?>"  name="f_date" class="form-control col-md-7 col-xs-12" >
            <td style="width:10px; text-align:center"> -</td>
            <td><input type="date"  style="width:150px; font-size: 11px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" required max="<?=date('Y-m-d')?>"   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
            <td style="width:10px; text-align:center"> -</td>                                       
            <td style="width:30%">
            <select class="select2_single form-control" name="vendor_id" id="vendor_id" style="width:100%;font-size:11px; max-height:10px">
            <option></option>
            <?=foreign_relation('vendor','vendor_id','vendor_name',$_POST['vendor_id']);?></select>
            </td>
            <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="submitit"  class="btn btn-primary">View Available PO</button></td>
        </tr>
    </table>
</form>       


 <?=$crud->report_templates_with_status($res,$title);?>                                       
<?=$html->footer_content();?>