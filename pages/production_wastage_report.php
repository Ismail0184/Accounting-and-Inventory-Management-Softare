<?php
require_once 'support_file.php';
$title="Production Wastage Report";

$now=time();
$unique='pi_no';
$unique_field='ref_no';
$table="production_wastage_master";
$page="production_wastage_report.php";
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
?>


<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>



<?php
if(!isset($_GET[pi_no])){
?>
<form action="" method="POST">
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>List of <?=$title;?></h2>
            <div class="clearfix"></div>
            <table align="center">
                <tr><td><input type="text" id="f_date" style="width:100px; height:30px" required="required" name="f_date"  class="form-control col-md-7 col-xs-12" value="<?php if (isset($_POST[f_date])){ echo $_POST[f_date]; } else {echo date('m')?>/01/<?=date('Y');} ?>" placeholder="From Date" ></td>
                    <td style="width:10px; text-align:center"> -</td>
                    <td><input type="text" id="t_date" style="width:100px; height:30px" required="required" name="t_date"  class="form-control col-md-7 col-xs-12" value="<?php if (isset($_POST[t_date])){ echo $_POST[t_date];} else {echo date('m')?>/<?=date('d')?>/<?=date('Y'); }?>"  placeholder="to Date"></td>
                    <td style="padding:10px"><button type="submit" name="initiate"  class="btn btn-success">View Report</button></td>
                </tr>
            </table>
        </div>

        <div class="x_content">
                      <?php
                      if(isset($_POST[initiate])){
                      $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                      $to_date=date('Y-m-d' , strtotime($_POST[t_date]));
                      }
                      $i=$i+1;
                      $res='select m.'.$unique.',m.'.$unique.' as Code,m.'.$unique_field.',m.date,i.item_name,i.unit_name,m.remarks from '.$table.' m,
                       production_westage_detail d,
                       item_info i
                       
                       WHERE 
                      m.ref_no=d.ref_no and 
                      d.fg_for=i.item_id and
                      m.date between "'.$from_date.'" and "'.$to_date.'" group by d.fg_for order by m.ref_no DESC ';
echo $crud->link_report_popup($res,$link);?>
 </div></div></div></form>
    <?php } ?>



<?php
if(isset($_GET[pi_no])){
    ?>
    <form action="" method="POST">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <?php
                    $res='select m.'.$unique.',m.'.$unique.' as Code,m.'.$unique_field.',m.date,i.item_name,i.unit_name,d.lot,d.batch,d.total_unit as qty,d.unit_price as price,d.total_amt from '.$table.' m,
                       production_westage_detail d,
                       item_info i
                       
                       WHERE 
                      m.ref_no=d.ref_no and 
                      d.item_id=i.item_id and
                      d.pi_no="'.$_GET[pi_no].'" group by d.item_id order by m.ref_no DESC ';
                    echo $crud->link_report($res,$link);?>
                </div></div></div></form>
<?php } ?>



<?php require_once 'footer_content.php' ?>