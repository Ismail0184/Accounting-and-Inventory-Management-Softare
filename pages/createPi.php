<?phprequire_once 'support_file.php';$title="Bank & Branch";// ::::: Edit This Section ::::: $title = 'Create Proforma Invoice';   // Page Name and Page Title$page = "createPi.php";  // PHP File Name$input_page = "createPi_input.php";$root = 'proforma';$table = 'xlc_pi_master';  // Database Table Name Mainly related to this page$unique = 'id';   // Primary Key of this Database table$shown = 'buyer_id';    // For a New or Edit Data a must have data field// ::::: End Edit Section :::::$crud = new crud($table);if ($_GET['first_pi'] > 0)    unset($_SESSION['pi_id']);elseif ($_REQUEST['unpi_id'] > 0)    $pi_id = $_SESSION['pi_id'] = $_REQUEST['unpi_id'];elseif ($_POST['pi_id'] > 0)    $pi_id = $_SESSION['pi_id'] = $_POST['pi_id'];elseif ($_GET['pi_id'] > 0)    $pi_id = $_SESSION['pi_id'] = $_GET['pi_id'];if (isset($_POST['new'])) {    $_POST['entry_at'] = date('Y-m-d h:s:i');    if (!isset($_SESSION['pi_id'])) {        unset($$unique);        $pi_id = $_SESSION['pi_id'] = $crud->insert();        unset($$unique);        $type = 1;        $msg = 'Proforma Invoice Initialized. (Proforma Invoice ID-' . $pi_id . ')';    } else {        $crud->update($unique);        $type = 1;        $msg = 'Successfully Updated.';    }    $_SESSION['buyer_id'] = $_POST['buyer_id'];}$pi_id = $_SESSION['pi_id'];if (isset($_POST['confirm'])) {    unset($_POST);    $_POST['id'] = $pi_id;    $_POST['prepared_at'] = date('Y-m-d h:s:i');    $_POST['status'] = 'DONE';    $crud = new crud('xlc_pi_master');    $crud->update('id');    unset($pi_id);    unset($_SESSION['pi_id']);    $type = 1;    $msg = 'Successfully Send to Factory.';}if (isset($_POST['delete'])) {    $crud = new crud('xlc_pi_master');    $condition = "id=" . $pi_id;    $crud->delete($condition);    $crud = new crud('xlc_pi_details');    $condition = "pi_id=" . $pi_id;    $crud->delete_all($condition);    unset($pi_id);    unset($_SESSION['pi_id']);    $type = 1;    $msg = 'Successfully Deleted.';}if ($pi_id > 0) {    $condition = $unique . "=" . $pi_id;    $data = db_fetch_object($table, $condition);    while (list($key, $value) = each($data)) {        $$key = $value;    }}?><script type="text/javascript">    function DoNav(lk) {        return GB_show('ggg', '../pages/<?= $root ?>/<?= $input_page ?>?<?= $unique ?>=' + lk, 600, 940)    }</script><script type="text/javascript">    function confirmation()    {   var answer = confirm("Are you sure?")        if (answer)        {            return true;        } else {            if (window.event) // True with IE, false with other browsers            { window.event.returnValue = false; //IE specific            } else {                return false            } }}</script><form action="createPi.php" method="post" enctype="multipart/form-data">    <div class="oe_view_manager oe_view_manager_current">        <? include('../../common/title/title_bar_lc.php');?>        <div class="oe_view_manager_body">            <div  class="oe_view_manager_view_list"></div>            <div class="oe_view_manager_view_form">                <div style="opacity: 1;" class="oe_formview oe_view oe_form_editable">                    <div class="oe_form_buttons"></div>                    <div class="oe_form_sidebar"></div>                    <div class="oe_form_pager"></div>                    <div class="oe_form_container"><div class="oe_form">                            <div class="">                                <?php /* ?>    <? include('../../common/report_bar.php');?><?php */ ?>                                <div class="oe_form_sheetbg">                                    <div class="oe_form_sheet oe_form_sheet_width">                                        <div  class="oe_view_manager_view_list">                                            <div  class="oe_list oe_view">                                                <table class="oe_form_group " border="0" cellpadding="0" cellspacing="0">                                                    <tbody>                                                        <tr class="oe_form_group_row">                                                            <td class="oe_form_group_cell">                                                                <table class="oe_form_group " border="0" cellpadding="0" cellspacing="0">                                                                    <tbody>                                                                        <tr class="oe_form_group_row" style="margin-top:10px;">                                                                            <td colspan="1" bgcolor="#E8E8E8" class="oe_form_group_cell" style="padding-top:5px;">&nbsp;&nbsp;PI ID : </td>                                                                            <td bgcolor="#E8E8E8" class="oe_form_group_cell" style="padding-top:5px;"><?if($_SESSION['pi_id']>0) $pi_id =  $_SESSION['pi_id']; else {$pi_id =  find_a_field('xlc_pi_master','max(id)+1','1');if($pi_id<1) $pi_id = 1;}?>                                                                                <input  name="id" type="text" id="id" value="<?=$pi_id ?>" readonly="readonly"/>                                                                                <input  name="pi_id2" type="hidden" id="pi_id2" value="<?=$pi_id ?>"/>                                                                                </center>                                                                            </td>                                                                            <td bgcolor="#E8E8E8" class="oe_form_group_cell" style="padding-top:5px;"><span class="oe_form_group_cell oe_form_group_cell_label">PI NO :</span></td>                                                                            <td bgcolor="#E8E8E8" class="oe_form_group_cell" style="padding-top:5px;"><input name="pi_no" id="pi_no" type="text" value="<?=($pi_no=='')?'SP/PI/2015/'.$pi_id:$pi_no;?>" /></td>                                                                        </tr>                                                                        <tr class="oe_form_group_row">                                                                          <td colspan="1" bgcolor="#fff" class="oe_form_group_cell oe_form_group_cell_label"><label>&nbsp;&nbsp;Party Name : </label></td>                                                                          <td bgcolor="#fff" class="oe_form_group_cell">																		  <select name="party_id"id="party_id" required="required" style="width:147px">                                                                              <option value="">Select One</option>                                                                              <? foreign_relation('lc_buyer','id','buyer_name',$party_id);?>                                                                            </select>                                                                          </td>                                                                          <td  bgcolor="#fff" class="oe_form_group_cell"><span class="oe_form_group_cell oe_form_group_cell_label">Buyer Name  : </span></td>                                                                          <td bgcolor="#fff" class="oe_form_group_cell">																		  <select name="buyer_id"id="buyer_id" style="width:147px">                                                                              <option value="">Select One</option>                                                                              <option></option>                                                                              <? foreign_relation('lc_brand_buyer','id','brand_buyer_name',$buyer_id);?>                                                                            </select>                                                                          </td>                                                                        </tr>                                                                        <tr class="oe_form_group_row">                                                                          <td bgcolor="#E8E8E8" class="oe_form_group_cell oe_form_group_cell_label">&nbsp;&nbsp;PI Revise date:</td>                                                                          <td colspan="3" bgcolor="#E8E8E8" class="oe_form_group_cell oe_form_group_cell_label"><input name="pi_revise_date" type="text" id="pi_revise_date" required="required" style="width:300px;" value="<?= $pi_revise_date ?>" /></td>                                                                        </tr>                                                                        <tr class="oe_form_group_row">                                                                            <td bgcolor="#FFFFFF" width="24%" colspan="1" class="oe_form_group_cell oe_form_group_cell_label">&nbsp;&nbsp;PI Amount : </td>                                                                            <td bgcolor="#FFFFFF" width="29%" colspan="1" class="oe_form_group_cell">                                                                                <input name="<?= $unique ?>" id="<?= $unique ?>" value="<?= $$unique ?>" type="hidden" />                                                                                <span class="oe_form_group_cell" style="padding-top:5px;">                                                                                <input  name="id2" type="text" id="id2" value="<?=find_a_field('xlc_pi_details','sum(amount)','pi_id='.$_SESSION['pi_id'])?>" readonly="readonly"/>                                                                                </span></td>                                                                            <td bgcolor="#FFFFFF" width="19%" class="oe_form_group_cell"><span class="oe_form_group_cell oe_form_group_cell_label"> PI Issue date:</span></td>                                                                            <td bgcolor="#FFFFFF" width="28%" class="oe_form_group_cell">                                                                                <input name="pi_issue_date" type="text" id="pi_issue_date" required                                                                                       value="<?= $pi_issue_date ?>" />                                                                            </td>                                                                        </tr>                                                                    <tr class="oe_form_group_row" style="margin-top:10px;">                                                                    <style type="text/css">                                                                        .Update{                                                                            background:#a1a1a1;color:#fff;                                                                        }                                                                        .Save{                                                                            background:#dedede;color:#fff;                                                                        }                                                                    </style>                                                                    <td colspan="4" bgcolor="" class="oe_form_group_cell" style="padding-top:20px;">                                                                    <center>                                                                        <? if($_SESSION['pi_id']>0) $btn_name='Update'; else $btn_name='Save'; ?>                                                                        <input name="new" type="submit" class="btn1" value="<?= $btn_name ?>" style="width:100px; font-weight:bold; font-size:12px;" />                                                                        <? if($_SESSION['pi_id']>0) { $btn_name='CONFIRM PI'; ?>                                                                        <input name="confirm" onclick="return confirmation();" type="submit" class="btn1" value="<?= $btn_name ?>" style="width:100px;color:green; font-weight:bold; font-size:12px;" />                                                                        <? } ?>                                                                        <? if($_SESSION['pi_id']>0) { $btn_name='Delete PI'; ?>                                                                        <input name="delete" id="delete" onclick="return confirmation();"  type="submit" class="btn1" value="<?= $btn_name ?>" style="width:100px;color:#A00000 ; font-weight:bold; font-size:12px;" />                                                                        <? } ?>                                                                        <input type="button" name="Submit" value="Print PI"  onClick="window.open('proforma_view.php?pi_id=<?=$_SESSION['pi_id'];?>','_blank');" />                                                                    </center>                                                            </td>                                                        </tr>                                                    </tbody>                                                </table>                                                <p>&nbsp;</p>                                                </td>                                                </tr>                                                </tbody>                                                </table>                                                <? if($_SESSION['pi_id']>0){?>                                                <? //include('../../common/title/report_bar_proforma.php');?>                                                                                                <?php                                                $res = 'select a.id,c.pi_no as PI_NO,b.item_name,a.style_no as style_or_PO_NO,a.specification,a.meassurment,a.qty,a.rate,a.unit,a.amount from  xlc_pi_details a,item_info b,xlc_pi_master c where a.pi_id= ' . $pi_id . '  and a.item_id = b.item_id and a.pi_id=c.id';//echo $res;                                                echo $crud->link_report($res, $link);                                                ?><? } ?>                                            </div>                                        </div>                                    </div>                                </div>                                <div class="oe_chatter">                                    <div class="oe_followers oe_form_invisible">                                        <div class="oe_follower_list"></div>                                    </div>                                       </div>                                                                </div>                                                        </div>                                           </div>                </div>                                </div>        </div>    </div></form><?php require_once 'footer_content.php' ?>