<?php

/* Created on: 25/02/2023
 * Author: Md Ismail Hossain
 *
 * Description: This class manages crud.
 */

require_once 'base.php';
class crud{
    public  $table_name;
    public  $table_mail;
    public  $fields = array();
    public  $fields_empty = array();
    public  $fields_type = array();

    public function crud($table_name)
    {   global $conn;
        $this->table_name = @$table_name;
        $sql="SHOW COLUMNS FROM ".$this->table_name;
        $query=mysqli_query($conn, $sql);
        while($res=@mysqli_fetch_row($query))
        {
            $name=$res[0];
            $type=$res[1];
            $this->fields_empty = array_merge($this->fields_empty,array($name=>''));
            $this->fields = array_merge($this->fields,array($name));
            $this->fields_type = array_merge($this->fields_type,array($name=>$type));
        }
		$this->table_name = @$table_name;
    }

    public function insert($tag='',$id='')
    {
        $vars = get_vars($this->fields);
        if ( count($vars) > 0 )
            $id=db_insert($this->table_name,$vars);
        return $id;
    }

    public function update($tag)
    {   $vars = get_vars($this->fields);
        if ( count($vars) > 0 )
            db_update($this->table_name,$_POST[$tag],$vars,$tag);
        return $id;
    }
	 public function delete($condition)
    {
        global $conn;
        $sql = "delete from $this->table_name where $condition limit 1";
        return mysqli_query($conn, $sql);
    }

    public function check_before_delete($field,$table,$postvalue)
    {
        global $conn;
        $sql = "select COUNT(".$field.") from ".$table." where ".$field."=".$post_value." limit 1";
        return mysqli_query($conn, $sql);
    }
    public function delete_all($condition)
    {   global $conn;
        $sql = "delete from $this->table_name where $condition";
        return mysqli_query($conn, $sql);
    }
    public function clear_all_data($condition=1)
    {   global $conn;
        $sql = "delete from $this->table_name where $condition";
        return mysqli_query($conn, $sql);
    }
    public function link_report($sql,$link=''){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<table  id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr class="oe_list_header_columns"><th>#</th>';
        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align: middle; text-align: center">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr onclick="DoNavPOPUP('.$row[0].')"><td>'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        $str .='<td>'.$b."</td>";}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table>';
        return $str;
    }
    public function link_report_popup($sql,$link=''){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<table  id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr class="oe_list_header_columns"><th>#</th>';
        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align: middle; text-align: center">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr onclick="DoNavPOPUP('.$row[0].')"><td>'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        $str .='<td>'.$b."</td>";}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table>';
        return $str;
    }
    public function link_report_voucher($sql,$link=''){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<table class="table table-striped table-bordered" id="customers" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: bisque"><th style="vertical-align:middle">#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="cursor:pointer" onclick="DoNavPOPUP('.$row[0].')"><td>'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        $str .='<td>'.$b."</td>";}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table>';
        return $str;
    }

	public function report_general($sql,$link=''){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<table id="customers" style="width:100%; font-size: 11px">';
        $str .='<thead><tr><th style="vertical-align:middle">#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th>'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="cursor:pointer"  onclick="DoNavPOPUP('.$row[0].')"><td>'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        $str .='<td>'.$b."</td>";}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }

		$str .='<tr class="footer">';
		if(isset($sl))$str .='<td>&nbsp;</td>';
		for($i=0;$i<$cols;$i++)
			{

				if($_POST['report']==3){				if($coloum[$i]=='rcv_amt') $str .='<td>&nbsp;</td>';
				elseif($show[$i]!=1&&$sum[$i]!=0)$str .='<td style="text-align:right">'.$sum[$i].'</td>';
				else $str .='<td>&nbsp;</td>';}
else{				if($show[$i]!=1&&$sum[$i]!=0)$str .='<td style="text-align:right">'.number_format($sum[$i],2).'</td>';
				else $str .='<td>&nbsp;</td>';}
			}
		$str .='</tr></tbody>';
        $str .='</table>';
        return $str;
    }


	public function report_templates($sql,$link=''){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <div class="x_content">
		<table class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th>#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th>'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="cursor:pointer"  onclick="DoNavPOPUP('.$row[0].')"><td>'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        $str .='<td>'.$b."</td>";}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table></div></div></div>';
        return $str;
    }


	public function report_templates_with_data($sql,$title){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
		<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th style="vertical-align:middle">#</th>';
        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="cursor:pointer"  onclick="DoNavPOPUP('.$row[0].')"><td>'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        $str .='<td>'.$b."</td>";}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table></div></div></div>';
        return $str;
		mysqli_close($conn);
    }

    function select_a_report($module_id)
    {
        global $conn;
        $str = '';
        $query = mysqli_query($conn, "
SELECT zm.optgroup_label_name,zs.report_name as subzonename,zs.report_id FROM module_reportview_optgroup_label AS zm
RIGHT JOIN module_reportview_report AS zs ON zm.optgroup_label_id = zs.optgroup_label_id RIGHT JOIN user_permission_matrix_reportview AS p ON p.optgroup_label_id=zm.optgroup_label_id AND p.report_id=zs.report_id WHERE p.status in ('1') and p.module_id='".$module_id."' and p.user_id=".$_SESSION['userid']."
ORDER BY zm.sl, zs.sl");
        $result = array();
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $cat_name = $row['optgroup_label_name'];
            if (!isset($results[$cat_name])) {
                $results[$cat_name] = array();
            }
            $results[$cat_name][] = array("subzonename" => $row['subzonename'], "report_id" => $row['report_id']);
        }
        if (!empty($results)) {
            $str .= '<select id="first-name"  required="required" size="27" style="font-size: 11px; border: none;white-space: nowrap;
  overflow:auto;
  text-overflow: ellipsis;" name="report_id" onchange="javascript:reload(this.form)" class="form-control col-md-7 col-xs-12">';
            foreach ($results as $category => $subcats) {
                $str .= '<optgroup label="' . $category . '">';
                foreach ($subcats as $subcategory) {
                    $report_id = @$_GET['report_id'];
                    if ($report_id == $subcategory['report_id']) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    $str .= '<option style="height:20px" value="' . $subcategory['report_id'] . '" ' . $selected . '>' . $subcategory['subzonename'] . '</option>';
                }
                $str .= '</optgroup>';
            }
        } else {
            $str .= '<p style="text-align: center">You do not have permission to view the reports. Please contact the person concerned.</p>';    }
        $str .= '</select>';
        return $str;
    }
	public function report_templates_with_status_periodical($sql,$status){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
		<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th style="vertical-align:middle">#</th>';
        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="cursor:pointer"  onclick="DoNavPOPUP('.$row[0].')"><td style="vertical-align:middle">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
						if($b=='YES'):
							$sp='<span class="label label-success" style="font-size:10px">Settled</span>';
						elseif ($b=='PENDING'):
							$sp='<span class="label label-warning" style="font-size:10px">Unsettled</span>';
						elseif ($b=='PREMATURE'):
							$sp='<span class="label label-warning" style="font-size:10px">PREMATURE</span>';
						elseif($b=='PROCESSING'):
							$sp='<span class="label label-info" style="font-size:10px">PROCESSING</span>';
							elseif($b=='ROCOMMENDED'):
							$sp='<span class="label label-info" style="font-size:10px">ROCOMMENDED</span>';
						elseif($b=='COMPLETED' || $b=='VERIFIED'):
							$sp='<span class="label label-success" style="font-size:10px">COMPLETED</span>';
							elseif($b=='APPROVED'):
							$sp='<span class="label label-success" style="font-size:10px">COMPLETED</span>';
                        elseif($b=='SETTLED'):
                            $sp='<span class="label label-success" style="font-size:10px">SETTLED</span>';
							elseif($b=='RETURNED'):
							$sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
						elseif($b=='CANCELED'):
						$sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
						elseif($b=='REJECTED'):
							$sp='<span class="label label-danger" style="font-size:10px">REJECTED</span>';
                        elseif($b=='BOUNCED'):
                            $sp='<span class="label label-danger" style="font-size:10px">BOUNCED</span>';
						elseif($b=='CHECKED'):
							$sp='<span class="label label-primary" style="font-size:10px">CHECKED</span>';
						elseif($b=='UNCHECKED'):
							$sp='<span class="label label-default" style="font-size:10px">UNCHECKED</span>';
							elseif($b=='MANUAL'):
							$sp='<span class="label label-default" style="font-size:10px">MANUAL</span>';
						elseif($b=='NO'):
							$sp='<span class="label label-danger" style="font-size:10px">Unchecked</span>';
						else :
                        	$sp=$b;
						endif;
						$str .='<td style="vertical-align:middle">'.$sp.'</td>';
						}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table></div></div></div>';
        return $str;
		mysqli_close($conn);
    }
    public function report_templates_with_status($sql,$status){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
		<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th style="vertical-align:middle">#</th>';
        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                $sl = 0;
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="cursor:pointer"  onclick="DoNavPOPUP('.$row[0].')"><td style="vertical-align:middle">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        if($b=='YES'):
                            $sp='<span class="label label-success" style="font-size:10px">Settled</span>';
                        elseif ($b=='PENDING'):
                            $sp='<span class="label label-warning" style="font-size:10px">Unsettled</span>';
                        elseif ($b=='PREMATURE'):
                            $sp='<span class="label label-warning" style="font-size:10px">PREMATURE</span>';
                        elseif($b=='PROCESSING'):
                            $sp='<span class="label label-info" style="font-size:10px">PROCESSING</span>';
                        elseif($b=='ROCOMMENDED'):
                            $sp='<span class="label label-info" style="font-size:10px">ROCOMMENDED</span>';
                        elseif($b=='COMPLETED' || $b=='VERIFIED'):
                            $sp='<span class="label label-success" style="font-size:10px">COMPLETED</span>';
                        elseif($b=='APPROVED'):
                            $sp='<span class="label label-success" style="font-size:10px">COMPLETED</span>';
                        elseif($b=='SETTLED'):
                            $sp='<span class="label label-success" style="font-size:10px">SETTLED</span>';
                        elseif($b=='RETURNED'):
                            $sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
                        elseif($b=='CANCELED'):
                            $sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
                        elseif($b=='REJECTED'):
                            $sp='<span class="label label-danger" style="font-size:10px">REJECTED</span>';
                        elseif($b=='BOUNCED'):
                            $sp='<span class="label label-danger" style="font-size:10px">BOUNCED</span>';
                        elseif($b=='CHECKED'):
                            $sp='<span class="label label-primary" style="font-size:10px">CHECKED</span>';
                            elseif($b=='RECORDED'):
                                $sp='<span class="label label-primary" style="font-size:10px">RECORDED</span>';
                            elseif($b=='UNRECORDED'):
                                $sp='<span class="label label-default" style="font-size:10px">UNRECORDED</span>';
                        elseif($b=='UNCHECKED'):
                            $sp='<span class="label label-default" style="font-size:10px">UNCHECKED</span>';
                        elseif($b=='MANUAL'):
                            $sp='<span class="label label-default" style="font-size:10px">MANUAL</span>';
                        elseif($b=='NO'):
                            $sp='<span class="label label-danger" style="font-size:10px">Unchecked</span>';
                        else :
                            $sp=$b;
                        endif;
                        $str .='<td style="vertical-align:middle">'.$sp.'</td>';
                    }
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table></div></div></div>';
        return $str;
        mysqli_close($conn);
    }
    public function report_templates_with_status_add_new($sql,$title,$c_class,$action,$create){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-'.$c_class.' col-sm-12 col-xs-12">
                        <div class="x_panel">
						<div class="x_title">
                                <h2>'.$title.'</h2>';
        if($create>0):
            $str.='<ul class="nav navbar-right panel_toolbox">
                                        <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#addModal">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:white; font-size:12px">Add New</span>
                                        </a>'; endif;
        $str.='</ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
		<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white;"><th style="vertical-align:middle;">#</th>';
        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="cursor:pointer"  onclick="DoNavPOPUP('.$row[0].')"><td style="vertical-align:middle">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        if($b=='YES' || $b=='USED'):
                            $sp='<span class="label label-success" style="font-size:10px">Settled</span>';
                        elseif ($b=='PENDING'):
                            $sp='<span class="label label-warning" style="font-size:10px">Unsettled</span>';
                        elseif ($b=='PREMATURE'):
                            $sp='<span class="label label-warning" style="font-size:10px">PREMATURE</span>';
                        elseif($b=='PROCESSING'):
                            $sp='<span class="label label-warning" style="font-size:10px">PREMATURE</span>';
                        elseif($b=='ISSUED'):
                            $sp='<span class="label label-info" style="font-size:10px">ISSUED</span>';
                        elseif($b=='ROCOMMENDED'):
                            $sp='<span class="label label-info" style="font-size:10px">ROCOMMENDED</span>';
                        elseif($b=='COMPLETED' || $b=='VERIFIED'):
                            $sp='<span class="label label-success" style="font-size:10px">COMPLETED</span>';
                        elseif($b=='APPROVED'):
                            $sp='<span class="label label-success" style="font-size:10px">COMPLETED</span>';
                        elseif($b=='SETTLED'):
                            $sp='<span class="label label-success" style="font-size:10px">SETTLED</span>';
                        elseif($b=='RETURNED'):
                            $sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
                        elseif($b=='CANCELED'):
                            $sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
                        elseif($b=='REJECTED'):
                            $sp='<span class="label label-danger" style="font-size:10px">REJECTED</span>';
                        elseif($b=='BOUNCED'):
                            $sp='<span class="label label-danger" style="font-size:10px">BOUNCED</span>';
                        elseif($b=='CHECKED'):
                            $sp='<span class="label label-primary" style="font-size:10px">CHECKED</span>';
                        elseif($b=='UNCHECKED'):
                            $sp='<span class="label label-default" style="font-size:10px">UNCHECKED</span>';
                        elseif($b=='UNUSED'):
                            $sp='<span class="label label-default" style="font-size:10px">UNUSED</span>';
                        elseif($b=='MANUAL'):
                            $sp='<span class="label label-default" style="font-size:10px">MANUAL</span>';
                        elseif($b=='NO'):
                            $sp='<span class="label label-danger" style="font-size:10px">Unchecked</span>';
                        else :
                            $sp=$b;
                        endif;
                        $str .='<td style="vertical-align:middle">'.$sp.'</td>';
                    }
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table></div></div></div>';
        return $str;
        mysqli_close($conn);
    }

	public function report_templates_with_add_active_inactive($sql,$title,$c_class,$action,$create){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-'.$c_class.' col-sm-12 col-xs-12">
                        <div class="x_panel">
						<div class="x_title">
                                <h2>'.$title.'</h2>';
								if($create>0):
								$str.='<ul class="nav navbar-right panel_toolbox">
                                        <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#addModal">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:white; font-size:12px">Add New</span>
                                        </a>'; endif;
                                $str.='</ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
		<form action="'.$page.'" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
		<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th>#</th>';
        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
			$str .='</tr></thead>';
            $c=0;
			$sta='checked';
			$sta2='';
            if (mysqli_num_rows($result)>0):
                while($row = mysqli_fetch_array($result)):
                    $str .='<tr style="cursor:pointer"><td style="vertical-align:middle" onclick="DoNavPOPUP('.$row[0].')">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++):
                        $b=$row[$i];
					if($b=='1' || $b=='0'):
					if($action==1 || $action==2):
                    $str .='<td style="vertical-align:middle; text-align:left">
					<input type="checkbox" style="margin-top:-2px;" data="'.$row[0].'" class="status_checks btn '.(($row['status'])? ' btn-success': ' btn-danger').'" '.(($row['status'])? 'checked':'').' />'.(($row['status'])? ' Active': ' Deactivate').'</td>';
					endif; else:
                    $str .='<td style="vertical-align:middle">'.$b."</td>";
					endif; endfor;
					$str .='</tr></thead>';
                endwhile;endif;
            mysqli_free_result($result);
        }
        $str .='</table></form></div></div></div>';
        return $str;
		mysqli_close($conn);
    }

	public function report_templates_with_add_new($sql,$title,$c_class,$action,$create,$page){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-'.$c_class.' col-sm-12 col-xs-12">
                        <div class="x_panel">
						<div class="x_title">
                                <h2>'.$title.'</h2>';
								if($create>0):
								$str.='<ul class="nav navbar-right panel_toolbox">
                                        <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#addModal">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:white; font-size:12px">Add New</span>
                                        </a>'; endif;
                                $str.='</ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
		<form action="'.$page.'" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
		<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th style="vertical-align:middle;height:50px">#</th>';
        if ($result = mysqli_query($conn , $sql)) :
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) :
                $str .='<th style="vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            endforeach;
			if($action==1 || $action==2):
            $str .='<th style="width:10%;vertical-align:middle; text-align:center">Action</th>';
			endif;
			$str .='</tr></thead><tbody>';
            $c=0;
            if (mysqli_num_rows($result)>0):
                $sl = 0;
                while($row = mysqli_fetch_array($result)):
                    $str .='<tr><td style="vertical-align:middle">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) :
                        $b=$row[$i];
						if($b=='YES'):
							$sp='<span class="label label-success" style="font-size:10px">Settled</span>';
						elseif ($b=='PENDING'):
							$sp='<span class="label label-warning" style="font-size:10px">Unsettled</span>';
						elseif ($b=='PREMATURE'):
							$sp='<span class="label label-warning" style="font-size:10px">PREMATURE</span>';
						elseif($b=='PROCESSING'):
							$sp='<span class="label label-info" style="font-size:10px">PROCESSING</span>';
							elseif($b=='ROCOMMENDED'):
							$sp='<span class="label label-info" style="font-size:10px">ROCOMMENDED</span>';
						elseif($b=='COMPLETED' || $b=='VERIFIED'):
							$sp='<span class="label label-success" style="font-size:10px">COMPLETED</span>';
							elseif($b=='APPROVED'):
							$sp='<span class="label label-success" style="font-size:10px">COMPLETED</span>';
                        elseif($b=='SETTLED'):
                            $sp='<span class="label label-success" style="font-size:10px">SETTLED</span>';
							elseif($b=='RETURNED'):
							$sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
						elseif($b=='CANCELED'):
						$sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
                        elseif($b=='BOUNCED'):
                        elseif($b=='Inactive' || $b=='INACTIVE' || $b=='Not In Service'):
                            $sp='<span class="label label-danger" style="font-size:10px">Inactive</span>';
                        elseif($b=='Active' || $b=='ACTIVE' || $b=='In Service'):
                            $sp='<span class="label label-success" style="font-size:10px">Active</span>';
                        elseif($b=='BOUNCED'):
                            $sp='<span class="label label-danger" style="font-size:10px">BOUNCED</span>';
                        elseif($b=='SUSPENDED'):
                            $sp='<span class="label label-danger" style="font-size:10px">SUSPENDED</span>';
                            elseif($b=='HOLDED'):
                                $sp='<span class="label label-info" style="font-size:10px">HOLDED</span>';    
						elseif($b=='CHECKED'):
							$sp='<span class="label label-primary" style="font-size:10px">CHECKED</span>';
						elseif($b=='UNCHECKED'):
							$sp='<span class="label label-default" style="font-size:10px">UNCHECKED</span>';
							elseif($b=='MANUAL'):
							$sp='<span class="label label-default" style="font-size:10px">MANUAL</span>';
						elseif($b=='NO'):
							$sp='<span class="label label-danger" style="font-size:10px">Unchecked</span>';
						else :
                        	$sp=$b;
						endif;    
                    $str .='<td style="vertical-align:middle">'.$sp."</td>";endfor;
					if($action==1 || $action==2):
                    $str .='<td style="vertical-align:middle; text-align:center">
					<button type="button" style="background-color:transparent; border:none; margin:0px; font-size:15px; padding:0px"  title="View Details" data-toggle="tooltip" class="viewBtn"><i class="fa fa-eye"></i></button>
					<button type="button" style="background-color:transparent; border:none; margin:0px; font-size:13px; padding:0px"  title="Update Record" data-toggle="tooltip" class="updateBtn" onclick="DoNavPOPUP('.$row[0].')"> <i class="fa fa-pencil"></i></button>
					<button type="submit" name="deletedata'.$row[0].'" style="background-color:transparent;color:red; border:none; margin:0px; font-size:13px; padding:0px" onclick="return window.confirm(\'Are you sure you want to delete this?\');" title="Delete Record" data-toggle="tooltip"><span class="glyphicon glyphicon-trash"></span></button>
					</td>';
					endif;
					$str .='</tr>';
                endwhile;$str .='</tbody>';
				endif;
            mysqli_free_result($result);
        endif;
        $str .='</table></form></div></div></div>';
        return $str;
		mysqli_close($conn);
    }


	public function report_templates_JSON($sql,$title,$c_class){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-'.$c_class.' col-sm-12 col-xs-12">
                        <div class="x_panel">
						<div class="x_title">
                                <h2>'.$title.'</h2>
								<ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right">
                                        <a class="btn btn-sm btn-default" data-toggle="modal" data-target="#darkModalForm">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Add New</span>
                                        </a>
                                    </div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
		<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th>#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="cursor:pointer"  onclick="DoNavPOPUP('.$row[0].')"><td>'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        $str .='<td>'.$b."</td>";}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table></div></div></div>';
        return $str;
		mysqli_close($conn);
    }


	public function report_templates_with_title_and_class($sql,$title,$c_class){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-'.$c_class.' col-sm-12 col-xs-12">
                        <div class="x_panel">
						<div class="x_title">
                                <h2>'.$title.'</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
		<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th>#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            $sl = 0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="cursor:pointer"  onclick="DoNavPOPUP('.$row[0].')"><td>'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        $str .='<td>'.$b."</td>";}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        }
        $str .='</table></div></div></div>';
        return $str;
		mysqli_close($conn);
    }


    public function recent_voucher_view($sql,$link,$v_type){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<table class="table table-striped table-bordered" style="width:100%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th>#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th>'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead>';
            $str .='<!--tfoot><tr>';
            for($i=1;$i<$cols;$i++)
            { $str .='<td></td>'; }
            $str .='</tr></tfoot-->';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $onclick='OpenPopupCenter("'.$link.'?v_type='.$v_type.'&vdate='.$row[1].'&v_no='.$row[2].'&view=Show&in='.$v_type.'", "TEST!?", 1000, 600)';
                    $str .="<tr><td style='text-align: left; cursor: pointer' onclick='".$onclick."'>".($sl=$sl+1)."</td>";
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        if(is_numeric($b)){
                            $align='text-align:right';
                        }
                        $str .='<td style="'.$align.'"><a href="voucher_print1.php?v_type=Receipt&vo_no='.$row[2].'"  target="_blank">'.$b."</a></td>";}
                    $str .='</tr></thead>';
                }}
            mysqli_free_result($result);
        } else {
            $str .='<tr><td colspan="4" style="text-align: center">No data available in table</td></tr>';
        }
        $str .='</table>';
        return $str;
		mysqli_close($conn);
    }
    public function module_view($sql,$url,$link){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<table style="width:100%; font-size: 12px">';
        $str .='<tr class="oe_list_header_columns">';
        if ($result = mysqli_query($conn , $sql)):
            $cols = mysqli_num_fields($result);
            if (mysqli_num_rows($result)>0):
                while($row = mysqli_fetch_array($result)):
                    if($row[0]==$_SESSION['module_id']):
                        $background_color='#FFDAB9';
						else :
                        $background_color='transparent';
                    endif;
                    for($i=5;$i<$cols;$i++):
                        $b=$row[$i];
                        $str .='<td align="center" style="width: auto; padding: 2px;background-color:'.$background_color.';vertical-align: middle;  text-align: center; font-weight: bold; border-radius: 5px;">'.
                            "<a href='$url$link$row[0]'>".$b."</a></td>";endfor;
                endwhile;endif;
				$str .='</tr>';
            mysqli_free_result($result);
        endif;
        $str .='</table>';
        return $str;
		mysqli_close($conn);
    }



    public function dashboard_modules($sql,$url,$link){
        global $conn;
        if($sql==NULL) return NULL;
        $str = '';
        $str.='
		<table style="width:100%; font-size: 12px">';
        $str .='<tr class="oe_list_header_columns">';
        $result = mysqli_query($conn, "SET NAMES utf8");//the main trick
        if ($result = mysqli_query($conn , $sql)):
            $cols = mysqli_num_fields($result);
            if (mysqli_num_rows($result)>0):
                while($row = mysqli_fetch_array($result)):
                    if($row[0]==@$_SESSION['module_id']):
                        $check_or_notification="<i class='fa fa-check'></i>";
						else :
                        $check_or_notification='';
                    endif;
                    for($i=5;$i<$cols;$i++):
                        $b=$row[$i];
                        $c=$row[2];
                        $d=$row[4];
                        $str .="<a class='btn btn-app' href='$url$link$row[0]' style='height:85px; margin-left: 40px;width:115px'><span class='badge bg-red'>".$check_or_notification."</span><i style='color:".$row[3]."' class='".$c."'></i> ".$d."</a>";
                        endfor;
                endwhile;endif;
				$str .='</tr>';
            mysqli_free_result($result);
        endif;
        $str .='</table>';
        return $str;
		mysqli_close($conn);
    }

    public function dashboard_quick_access_menu($sql,$url,$link){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<table style="width:100%; font-size: 12px">';
        $str .='<tr class="oe_list_header_columns">';
        $result = mysqli_query($conn, "SET NAMES utf8");//the main trick
        if ($result = mysqli_query($conn , $sql)):
            $cols = mysqli_num_fields($result);
            if (mysqli_num_rows($result)>0):
                while($row = mysqli_fetch_array($result)):
                    for($i=3;$i<$cols;$i++):
                        $b=$row[$i];
                        $check_or_notification = '';
                        $str .="<a class='btn btn-app' href='$row[1]' style='height:'><span class='badge bg-red'>".$check_or_notification."</span><i class='".$row[2]."'></i> ".$b."</a>";
                        endfor;
                endwhile;endif;
				$str .='</tr>';
            mysqli_free_result($result);
        endif;
        $str .='</table>';
        return $str;
		mysqli_close($conn);
    }

    public function get_submenu_under_mainmenu($sql,$url,$link){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        if ($result = mysqli_query($conn , $sql)):
            $cols = mysqli_num_fields($result);
            if (mysqli_num_rows($result)>0):
                while($row = mysqli_fetch_array($result)):
                        $str .='
                        <a style="" class="btn btn-sm btn-default"  href="'.$row[3].'">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">'.$row[2].'</span>
                        </a>';
                endwhile;endif;
            mysqli_free_result($result);
        endif;
        return $str;
		mysqli_close($conn);
    }


    public function master_menu_accounts($module_id)
    {
        global $conn;
        $query=mysqli_query($conn, "
SELECT zm.zonename,zm.faicon,zs.zonename as subzonename FROM zone_main AS zm
RIGHT JOIN zone_sub AS zs ON zm.zonecode = zs.zonecodemain where
zm.zonecode not in ('0','0') and
zs.module=".$module_id."
ORDER BY zm.zonecode, zs.sl");

        $result = array();
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            $cat_name = $row['zonename'];
            $ficonname = $row['faicon'];
            $subcat_name = $row['subzonename'];
            if(!isset($results[$cat_name])){
                $results[$cat_name] = array();
            }
            $results[$cat_name][] = $subcat_name;
        }
        if(!empty($results)){
            foreach($results as $category => $subcats){
                $str .= '<li><a href="#"><i class="' . $ficonname. '"></i>' . $category. " <span class='fa fa-chevron-down'></span></a>";
                $str .= '<ul class="nav child_menu">';

                foreach($subcats as $subcategory){
                    $str .= '<li><a href="'.$row['url'].'">' . $subcategory . "</a></li>";
                }
                $str .= '</ul></li>';
            }
        }
        return $str;
    }




    public function master_menu_view($sql,$link=''){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<table style="width:100%; font-size: 12px">';
        $str .='<tr class="oe_list_header_columns">';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    for($i=2;$i<$cols;$i++) {
                        $b=$row[$i];
                        $str .='<td align="center" style="width: auto; padding: 5px; vertical-align: middle;  text-align: center; font-weight: bold; border-radius: 5px;">'.
                            "<a href='<?php echo $url.$urls.$data->id; ?>'>"
                            .$b."</a></td>";}
                    }
            }$str .='</tr>';
            mysqli_free_result($result);
        }
        $str .='</table>';
        return $str;
    }
}



function paging($per_pg)
{
    echo '<div id="pageNavPosition"></div><script type="text/javascript"><!--
		var pager = new Pager("grp",'.$per_pg.');
		pager.init();
		pager.showPageNav("pager", "pageNavPosition");
		pager.showPage(1);
	//--></script>
	<script type="text/javascript">
		document.onkeypress=function(e){
		var e=window.event || e
		var keyunicode=e.charCode || e.keyCode
		if (keyunicode==13)
		{
			return false;
		}
	}
	</script>';
}

class htmldiv extends crud {
public function body_content(){
    $str = '';
	$str.='<!DOCTYPE html>';
	return false;
}

public function header_content($title){
    $str = '';
	 if($title==NULL) return NULL;
	$str.='<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>'.$_SESSION['company_name'].' | '.$title.'</title>
    <link rel="icon" href="../assets/images/icon/title.png" type="image/icon type">
    <!-- Select2 -->
    <link href="../assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../assets/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../assets/vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../assets/build/css/custom.min.css" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="../assets/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
</head>';
	return $str;
}
public function footer_content(){
    $str = '';
	$str.='</div>
</div>
</div>';

if ($_GET) {
    $str .= '
    <footer>
        <div class="pull-right"></div>
        <div class="clearfix"></div>
    </footer>';
} else {
    $str .= '
<footer>
    <div class="pull-right">Powered By: <strong>Raresoft</strong> </div>
    <div class="clearfix">©' . date('Y') . '<strong> Raresoft</strong> All Rights Reserved</div>
</footer>';
}
$str .= '</div>
</div>
<!-- jQuery -->
<script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="../assets/vendors/nprogress/nprogress.js"></script>
<!-- iCheck -->
<script src="../assets/vendors/iCheck/icheck.min.js"></script>
<!-- Datatables -->

<script src="../assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="../assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="../assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="../assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="../assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="../assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="../assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="../assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="../assets/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
<script src="../assets/vendors/jszip/dist/jszip.min.js"></script>
<script src="../assets/vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="../assets/vendors/pdfmake/build/vfs_fonts.js"></script>
<script src="../assets/vendors/switchery/dist/switchery.min.js"></script>
<!-- Select2 -->
<script src="../assets/vendors/select2/dist/js/select2.full.min.js"></script>
<!-- Custom Theme Scripts -->
<script src="../assets/build/js/custom.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../assets/vendors/moment/min/moment.min.js"></script>
<script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- jQuery custom content scroller -->
<script src="../assets/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- Datatables -->
<script>
    $(document).ready(function() {
        var handleDataTableButtons = function() {
            if ($("#datatable-buttons").length) {
                $("#datatable-buttons").DataTable({
                    dom: "Bfrtip",
                    buttons: [
                        {   extend: "copy",
                            className: "btn-sm"
                        },
                        {
                            extend: "csv",
                            className: "btn-sm"
                        },
                        {
                            extend: "excel",
                            className: "btn-sm"
                        },
                        {
                            extend: "pdfHtml5",
                            className: "btn-sm"
                        },
                        {
                            extend: "print",
                            className: "btn-sm"
                        },
                    ],
                    responsive: true
                });
            }
        };
        TableManageButtons = function() {
            "use strict";
            return {
                init: function() {
                    handleDataTableButtons();
                } };
        }();
        $(\'#datatable\').dataTable();
        $(\'#datatable-keytable\').DataTable({
            keys: true
        });

        $(\'#datatable-responsive\').DataTable();
        $(\'#datatable-scroller\').DataTable({
            ajax: "js/datatables/json/scroller-demo.json",
            deferRender: true,
            scrollY: 380,
            scrollCollapse: true,
            scroller: true
        });
        $(\'#datatable-fixed-header\').DataTable({
            fixedHeader: true
        });
        var $datatable = $(\'#datatable-checkbox\');
        $datatable.dataTable({
            \'order\': [[ 1, \'asc\' ]],
            \'columnDefs\': [
                { orderable: false, targets: [0] }
            ]
        });
        $datatable.on(\'draw.dt\', function() {
            $(\'input\').iCheck({
                checkboxClass: \'icheckbox_flat-green\'
            });
        });
        TableManageButtons.init();
    });

</script>
<!-- Select2 -->
<script>
    $(document).ready(function() {
        $(".select2_single").select2({
            placeholder: "Select a Choose",
            allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple").select2({
            maximumSelectionLength: 4,
            placeholder: "With Max Selection limit 4",
            allowClear: true
        });
    });
</script>
<!-- /Select2 -->';
if ($_GET) {

    $str .= '';
} else {
    $str .= '
<script>
    $(function() {
        $(\'body\').removeClass(\'nav-md\').addClass(\'nav-sm\');
        $(\'.left_col\').removeClass(\'scroll-view\').removeAttr(\'style\');
        $(\'#sidebar-menu li\').removeClass(\'active\');
        $(\'#sidebar-menu li ul\').slideUp();
        });
        $(document).ready(function(){
            menuToggle();
            });
</script>';
}



$str.='
</body>
</html>';
	return $str;
	mysqli_close($conn);
	}

public function MIS_add_new_plant_cmu_warehouse($active,$res,$title,$unique,$c_class){
	global $crud;
    $str = '';
    if($active==0) return NULL;
	if($unique>0){
		$class_popup='';
		$data_view='';
		$class_popup_footer='';
	} else {

	$class_popup='<div class="modal fade" id="darkModalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog form-dark" role="document">
    <div class="modal-content card card-image">';
	$class_popupfooter='</div></div></div></div>';
	$data_view=$crud->report_templates_with_add_new($res,$title,12);
	}

	$str.='
	'.$data_view.$class_popup.'
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Add New</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                             <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
                        <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Custom Code<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
										<input type="hidden" name="warehouse_id" id="warehouse_id" />
                                            <input type="text" name="custom_code" id="custom_code" value="'.$custom_code.'" style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Warehouse / Plant / CMU Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" name="warehouse_name" id="warehouse_name" value="'.$warehouse_name.'" style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Nick Name</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="nick_name" style="width:100%; font-size: 11px" name="nick_name" value="'.$nick_name.'" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>



                                   <div class="form-group">
                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Address</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <textarea id="address" style="width:100%; height: 80px; font-size: 12px" name="address"  class="form-control col-md-7 col-xs-12" >'.$address.'</textarea>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Contact No</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="contact_no" style="width:100%; font-size: 11px" name="contact_no" value="'.$contact_no.'" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Email</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="email" style="width:100%; font-size: 11px" name="email" value="'.$email.'" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>




                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Type:<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <select style="width: 100%" class="select2_single form-control" name="use_type" id="use_type">
                                                <option></option>

                                            </select></div></div>


<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Associated Person<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="ap_name" style="width:100%; font-size: 12px"  required   name="ap_name" value="'.$ap_name.'" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">AP Designation<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="ap_designation" style="width:100%; font-size: 12px"    name="ap_designation" value="'.$ap_designation.'" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                   <div class="form-group">
								   <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="record" id="record" style="font-size:12px"  class="btn btn-primary">Record</button></div></div>
												</form>

                                '.$class_popupfooter.'</div></div>
                                </div>
                                </div>';
        return $str;
    }
}



 function recentvoucherview($sql,$link,$v_type,$css){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-4 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Recent Vouchers</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" style="overflow:scroll; height:'.$css.';">
		<table class="table table-striped table-bordered" style="width:100%; font-size: 10px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th>#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th>'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead><tbody>';
            $c=0;
            if (mysqli_num_rows($result)>0){
                $sl = 0;
                while($row = mysqli_fetch_array($result)) {
                    $onclick='OpenPopupCenter("'.$link.'?v_type='.$v_type.'&vdate='.$row[1].'&v_no='.$row[2].'&view=Show&in='.$v_type.'", "TEST!?", 1000, 600)';
                    $str .="<tr><td style='text-align: left; cursor: pointer' onclick='".$onclick."'>".($sl=$sl+1)."</td>";
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        if(is_numeric($b)){
                            $align='text-align:right';
                        } else {$align='text-align:left';}
                        $str .='<td style="'.$align.'"><a href="voucher_print1.php?v_type='.$v_type.'&vo_no='.$row[2].'"  target="_blank">'.$b."</a></td>";}
                    $str .='</tr>';
                }}else {
            $str .='<tr><td colspan="4" style="text-align: center">No data available in table</td></tr>';
        }
            mysqli_free_result($result);
        }
        $str .='</tbody></table></div></div></div>';
        return $str;
		mysqli_close($conn);
    }

function recentdataview($sql,$link,$v_type,$css,$title,$viewmoreURL,$divwidth){
        global $conn;
    $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<div class="col-md-'.$divwidth.' col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>'.$title.'</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" style="overflow:scroll; height:'.$css.';">
		<table class="table table-striped table-bordered" style="width:100%; font-size: 10px">';
        $str .='<thead><tr style="background-color: bisque"><th>#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
				$ism=$ism+1;
                $str .='<th>'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead><tbody>';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $onclick='';
                    $str .="<tr onclick='DoNavPOPUP(".$row[0].")' style='cursor: pointer;'><td style='text-align: left; vertical-align:middle'>".($sl=$sl+1)."</td>";
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
						if($b=='YES'):
							$sp='<span class="label label-success" style="font-size:10px">Settled</span>';
						elseif ($b=='PENDING'):
							$sp='<span class="label label-warning" style="font-size:10px">UNAPPEOVED</span>';
						elseif($b=='PROCESSING'):
							$sp='<span class="label label-info" style="font-size:10px">PROCESSING</span>';
							elseif($b=='RECOMMENDED'):
							$sp='<span class="label label-info" style="font-size:10px">APPROVED</span>';
						elseif($b=='COMPLETED' || $b=='VERIFIED'):
							$sp='<span class="label label-success" style="font-size:10px">COMPLETED</span>';
							elseif($b=='APPROVED'):
							$sp='<span class="label label-success" style="font-size:10px">GRANTED</span>';
							elseif($b=='RETURNED'):
							$sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
						elseif($b=='CANCELED'):
						$sp='<span class="label label-danger" style="font-size:10px">RETURNED</span>';
						elseif($b=='REJECTED'):
							$sp='<span class="label label-danger" style="font-size:10px">REJECTED</span>';
						elseif($b=='CHECKED'):
							$sp='<span class="label label-primary" style="font-size:10px">CHECKED</span>';
						elseif($b=='UNCHECKED'):
							$sp='<span class="label label-default" style="font-size:10px">UNCHECKED</span>';
							elseif($b=='MANUAL'):
							$sp='<span class="label label-default" style="font-size:10px">MANUAL</span>';
						elseif($b=='NO'):
							$sp='<span class="label label-danger" style="font-size:10px">Unchecked</span>';
						else :
                        	$sp=$b;
						endif;
						$str .='<td style="vertical-align:middle">'.$sp.'</td>';}
                    $str .='</tr>';
                }}else {
					$add=+1;
            $str .='<tr><td colspan="'.$ism.$add.'" style="text-align: center">No data available in table</td></tr>';
        }
            mysqli_free_result($result);
        }
        $str .='</tbody></table>';
		if($sl>0){
		$str .='<h6 style="text-align:center"><a href="'.$viewmoreURL.'" target="_new" style="font-size:11px" class="btn btn-round btn-info">View more..</a></h6>';}
		$str .='</div></div></div>';
        return $str;
		mysqli_close($conn);
    }

    function recentdataview_model($sql,$link,$v_type,$css,$title,$viewmoreURL,$divwidth,$page){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
        $str.='
		<table align="center" class="table table-striped table-bordered" style="width:'.$divwidth.'%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th>#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            $ism = 0;
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
				$ism=$ism+1;
                $str .='<th>'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='<th style="width:8%;vertical-align:middle; text-align:center">Action</th>';
            $str .='</tr></thead><tbody>';

            $c=0;
            $sl = 0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $onclick='';
                    $str .="<tr onclick='DoNavPOPUP(".$row[0].")' style='cursor: pointer;'><td style='text-align: left; vertical-align:middle'>".($sl=$sl+1)."</td>";
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
						$str .='<td style="vertical-align:middle">'.$b.'</td>';
                    }
                    $str .='<td style="vertical-align:middle"><button type="submit" name="deletedata'.$row[0].'" style="background-color:transparent;color:red; border:none; margin:0px; font-size:17px; padding:0px; float:left" class="fa fa-trash" onclick="return window.confirm(\'Are you sure you want to delete this?\');" title="Delete"></button> 
                    <a href="'.$page.'?id='.$row[0].'" style="margin:0px; font-size:17px;padding:0px; float:right; color:#337ab7" class="fa fa-edit" onclick="return window.confirm(\'Are you sure you want to edit this?\');" title="Edit"></a>'."</td>";
                    $str .='</tr>';
                }}else {
					$add=+1;
            $str .='<tr><td colspan="'.$ism.$add.'" style="text-align: center">No data available in table</td></tr>';
        }
            mysqli_free_result($result);
        }
        $str .='</tbody></table>';
        return $str;
		mysqli_close($conn);
    }
   

function reportview($sql,$title,$width,$tfoot,$colspan){
        global $conn;
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
        $now = $dateTime->format("d/m/Y  h:i:s A");
        if($sql==NULL) return NULL;
        if ($result = mysqli_query($conn , $sql)) {
            $str.='
		<title>'.$_SESSION['company_name'].' | '.$title.'</title>
        <p align="center" style="margin-top:-5px; font-weight: bold; font-size: 22px">'.$_SESSION['company_name'].'</p>
        <p align="center" style="margin-top:-18px; font-size: 15px; font-weight: bold">'.$title.'</p> ';
            if($_POST['f_date']>0){
                $str.='
		        <p align="center" style="margin-top:-15px; font-size: 12px">Date Interval: Between '.$_POST['f_date'].' and '.$_POST['t_date'].' </p>';
            }
            $str.='<table align="center" id="customers"  style="width:'.$width.'%; border: solid 1px #999; border-collapse:collapse;font-size:11px">';
            $str .='<thead><p style="width:'.$width.'%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: '.$now.' </p><tr  style="border: solid 1px #999;font-weight:bold; font-size:11px; background-color: #f5f5f5">';
            $str.='<th>#</th>';
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="border: solid 1px #999; padding:2px;vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
                $ism=$ism+2;
            }
            $str .='</tr></thead>';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="border: solid 1px #999; font-size:11px; font-weight:normal;"  onclick="DoNavPOPUP('.$row[0].')"><td align="center" style="border: solid 1px #999; padding:2px">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];

						   if($row[$i]=='0'){ $str .='<td style="text-align:center;border: solid 1px #999; padding:2px"></td>';}
               elseif(is_float($b)) {$str .='<td style="text-align:right;border: solid 1px #999; padding:2px">'.$row[$i].'</td>';}

               else {$str .='<td style="border: solid 1px #999; padding:2px">'.$b."</td>";}}
                    $str .='</tr></thead>';
                }} else {            $str .='<tr style="border: solid 1px #999; font-size:11px;"><td colspan="'.$ism.'" style="border: solid 1px #999; padding:2px; text-align: center; font-size: 11px">No data available in table</td></tr>';
            }
            mysqli_free_result($result);
            if($tfoot>0){
              $str .='<tfoot><tr><th colspan="'.$colspan.'" style="text-align:left; border: solid 1px #999; font-size:11px;">Total</th></tr></tfoot>';
            }
            $str .='</table>';
            $str .='<p style="width:'.$width.'%; text-align:left; margin-left: 15px;font-size:11px; font-weight:normal">Report Generated By: '.$_SESSION[username].', '.$_SESSION[designation].'. </p>';

        } else { $str .='<h4 style="text-align: center">Oops!! Invalid Query</h4><br>  '.$sql.'</h4>'; }
        return $str;
		mysqli_close($conn);
    }


function bl_pl_support_data_view($sql,$title,$width){
        global $conn;
        if($sql==NULL) return NULL;
        $str.='
		<title>'.$_SESSION['company_name'].' | '.$title.'</title>
		<table align="center" id="customers"  style="width:'.$width.'%; border:solid 1px #999; border-collapse:collapse;font-size:11px">';
        $str .='<thead><tr style="border: solid 1px #999;font-weight:bold; font-size:11px; background-color: bisque"><th>#</th>';
        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
        $str .='<th style="border: solid 1px #999; padding:2px;vertical-align:middle">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }$str .='</tr></thead>';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="border: solid 1px #999; font-size:11px; font-weight:normal;cursor:pointer"  onclick="DoNavPOPUP('.$row[0].')"><td align="center" style="border: solid 1px #999; padding:2px">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
						if(is_numeric($row[$i])){
							$str .='<td style="text-align:right;border: solid 1px #999; padding:2px">'.$row[$i].'</td>';
							} else {
                            $str .='<td style="border: solid 1px #999; padding:2px;text-align:left">'.$b."</td>";}}
                    $str .='</tr></thead>';
					$ta=$ta+$row[2];
					$tb=$tb+$row[3];
					$tc=$tc+$row[4];
                }}
				 $str .='<tr><th colspan="2" style="border: solid 1px #999; padding:2px; text-align:right">Total</th>
				 <th style="text-align:right; border: solid 1px #999; padding:2px;">'.number_format($ta,2).'</th><th style="text-align:right; border: solid 1px #999; padding:2px;">'.number_format($tb,2).'</th><th style="text-align:right; border: solid 1px #999; padding:2px;">'.number_format($tc,2).'</th>
				 <tr>';
            mysqli_free_result($result);
        }
        $str .='</table>';
        return $str;
		mysqli_close($conn);
    }


function voucher_delete_edit($sql,$unique,$unique_GET,$COUNT_details_data,$page){
        global $conn;
        $str = '';
        if($sql==NULL) return NULL;
		$str.='
		<form action=""  name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">';
		if($COUNT_details_data>0):
        $str.='
		<input type="hidden" name="'.$unique.'" id="'.$unique.'"  value="'.$unique_GET.'">
		<table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">';
        $str .='<thead><tr style="background-color: #3caae4; color:white"><th style="vertical-align:middle; text-align:center">#</th>';
        if ($result = mysqli_query($conn , $sql)):
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) :
                $str .='<th style="vertical-align:middle; text-align:center">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            endforeach;
            $str .='<th style="width:5%; text-align:center; vertical-align:middle">Action</th></tr></thead><tbody>';
            $c=0;
            if (mysqli_num_rows($result)>0):
                $sl = 0;
                $tdramt = 0;
                $tcramt = 0;
                while($row = mysqli_fetch_array($result)):
                    $str .='<tr style="vertical-align:middle"><td style="vertical-align:middle; text-align:center">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++):
                        $b=$row[$i];
						if(is_numeric($row[$i])):
                        $str .='<td style="vertical-align:middle; text-align:right">'.number_format($b,2)."</td>";
						else :
						$str .='<td style="vertical-align:middle;">'.$b."</td>";
						endif;endfor;
                        $tdramt=$tdramt+$row[4];
                        $tcramt=$tcramt+$row[5];
						$str .='<td style="vertical-align:middle"><button type="submit" name="deletedata'.$row[0].'" style="background-color:transparent;color:red; border:none; margin:0px; font-size:17px; padding:0px; float:left" class="fa fa-trash" onclick="return window.confirm(\'Are you sure you want to delete this?\');" title="Delete"></button> 
                        <a href="'.$page.'?id='.$row[0].'" style="margin:0px; font-size:17px;padding:0px; float:right; color:#337ab7" class="fa fa-edit" onclick="return window.confirm(\'Are you sure you want to edit this?\');" title="Edit"></a>'."</td>";
                    $str .='</tr>';
                endwhile;endif;
            mysqli_free_result($result);
        endif;
        $str .='</tbody></table>';
		endif;

		$str .='<button style="float: left; font-size: 11px; margin-left: 1%" type="submit" name="cancel" onclick="return window.confirm(\'Are you sure you want to delete this?\');" class="btn btn-danger">Delete the Voucher </button>';
		if($COUNT_details_data>0):
		if(number_format($tdramt,2) === number_format($tcramt,2)) {
            $str .= '<button style="float: right; font-size: 11px; margin-right: 1%" type="submit" name="confirmsave" onclick="return window.confirm(\'Are you sure you want to confirm this?\');" class="btn btn-success">Confirm and Finish Voucher </button>';
        } else {
            $str .= '<h6  style="color: red; font-weight: bold; float: right; margin-right: 1%">Invalid Voucher. Debit ('.$tdramt.') and Credit ('.$tcramt.') amount are not equal !!</h6>';
            //$str .= '<button style="float: right; font-size: 11px; margin-right: 1%" type="submit" name="confirmsave" onclick="return window.confirm(\'Are you sure you want to confirm this?\');" class="btn btn-success">Confirm and Finish Voucher </button>';
        };endif;$str .='</form>';
        return $str;
		mysqli_close($conn);
    }


function added_data_delete_edit($sql,$unique,$unique_GET,$COUNT_details_data,$page,$total_amount,$colspan){
    global $conn;
    $str = '';
    if($sql==NULL) return NULL;
    $str.='
		<form action="'.$page.'"  name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">';
    if($COUNT_details_data>0):
        $str.='
		<input type="hidden" name="'.$unique.'" id="'.$unique.'"  value="'.$unique_GET.'">
		<table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">';
        $str .='<thead><tr style="background-color: bisque; "><th style="vertical-align:middle; text-align:center">#</th>';

        if ($result = mysqli_query($conn , $sql)):
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val):
                $str .='<th style="vertical-align:middle; text-align:center">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            endforeach;
            $str .='<th style="width:5%; text-align:center; vertical-align:middle">Action</th></tr></thead><tbody>';
            $c=0;
            if (mysqli_num_rows($result)>0):
                while($row = mysqli_fetch_array($result)):
                    $str .='<tr style="vertical-align:middle"><td style="vertical-align:middle; text-align:center">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++):
                        $b=$row[$i];
                        if(is_numeric($row[$i])):
                            $str .='<td style="vertical-align:middle; text-align:right">'.$b."</td>";
                        else :
                            $str .='<td style="vertical-align:middle;">'.$b."</td>";
                        endif;
                    endfor;
                    $str .='<td style="vertical-align:middle"><button type="submit" name="deletedata'.$row[0].'" style="background-color:transparent;color:red; border:none; margin:0px; font-size:17px; padding:0px; float:left" class="fa fa-trash" onclick="return window.confirm(\'Are you sure you want to delete this?\');" title="Delete"></button> <a href="'.$page.'?id='.$row[0].'" style="margin:0px; font-size:17px;padding:0px; float:right" class="fa fa-edit" onclick="return window.confirm(\'Are you sure you want to edit this?\');" title="Edit"></a>'."</td>";
                    $str .='</tr>';
                endwhile;endif;
            mysqli_free_result($result);
        endif;
        $str .='</tbody>';
        if($total_amount>0):
            $str .='<tfoot><tr><th colspan="'.$colspan.'" style="text-align:right">Total Amount = </th><th style="text-align:right">'.number_format($total_amount,2).'</th><td></td></tr>';
            endif;
        $str .='</tfoot></table>';
		endif;
    $str .='<button style="float: left; font-size: 11px; margin-left: 1%" type="submit" name="cancel" onclick="return window.confirm(\'Are you sure you want to delete this?\');" class="btn btn-danger">Delete</button>';

    if($COUNT_details_data>0):
            $str .='<button style="float: right; font-size: 11px; margin-right: 1%" type="submit" name="confirm" onclick="return window.confirm(\'Are you sure you want to confirm this?\');" class="btn btn-success">Confirm and Finish</button>';
        endif;$str .='</form>';
    return $str;
    mysqli_close($conn);
}


function added_data_delete_edit_invoice($sql,$unique,$unique_GET,$COUNT_details_data,$page,$colspan,$row_get,$commission){
    global $conn;
    if($sql==NULL) return NULL;
    $str.='
		<form action="'.$page.'"  name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">';
    if($COUNT_details_data>0):
        $str.='
		<input type="hidden" name="'.$unique.'" id="'.$unique.'"  value="'.$unique_GET.'">
		<table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">';
        $str .='<thead><tr style="background-color: bisque; "><th style="vertical-align:middle; text-align:center">#</th>';
        if ($result = mysqli_query($conn , $sql)):
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val):
                $str .='<th style="vertical-align:middle; text-align:center">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            endforeach;
            $str .='<th style="width:5%; text-align:center; vertical-align:middle">Action</th></tr></thead><tbody>';
            $c=0;
            if (mysqli_num_rows($result)>0):
                while($row = mysqli_fetch_array($result)):
                    $str .='<tr style="vertical-align:middle"><td style="vertical-align:middle; text-align:center">'.($row[0]).'</td>';
                    for($i=1;$i<$cols;$i++):
                        $b=$row[$i];
                        if(is_numeric($row[$i])):
                            $str .='<td style="vertical-align:middle; text-align:right">'.$b."</td>";
                        else :
                            $str .='<td style="vertical-align:middle;">'.$b."</td>";
                        endif;
                    endfor;
                    $amount=$amount+$row[$row_get];
                    if($row[1]==0):
                    $str .='<td style="vertical-align:middle; text-align: center"><button type="submit" name="deletedata'.$row[0].'" style="background-color:transparent; border:none; font-size:17px;" class="fa fa-trash" onclick="return window.confirm(\'Are you sure you want to delete this?\');" title="Delete"></button> 
                    '."</td>";
                else:
                    $str .='<td style="vertical-align:middle; color: red; font-weight: bold; text-align: center">[Free]</td>';

                endif;
                    $str .='</tr>';
                endwhile;endif;
            mysqli_free_result($result);
        endif;
        $str .='</tbody>';
        $str .='<tfoot><tr><th colspan="'.$colspan.'" style="text-align:right">Order Value = </th><th style="text-align:right">'.number_format($amount,2).'</th><td></td></tr>';
        if($commission>0):
            $comissionGET=($amount/100)*$commission;
            $str.='<input type="hidden" name="commission_amount" id="commission_amount"  value="'.$comissionGET.'">';
        $str .='<tr><th colspan="'.$colspan.'" style="text-align:right">Less: Commission = </th><th style="text-align:right">'.number_format($comissionGET,2).'</th><td></td></tr>';
        $str .='<tr><th colspan="'.$colspan.'" style="text-align:right">Total Receivable Value = </th><th style="text-align:right">'.number_format($amount-$comissionGET,2).'</th><td></td></tr></table>';
    
    endif;
    $str .='</tfoot></table>';
		endif;
    $str .='<button style="float: left; font-size: 11px; margin-left: 1%" type="submit" name="cancel" onclick="return window.confirm(\'Are you sure you want to delete this?\');" class="btn btn-danger">Delete</button>';
    if($COUNT_details_data>0):
            $str .='<button style="float: right; font-size: 11px; margin-right: 1%" type="submit" name="confirm" onclick="return window.confirm(\'Are you sure you want to confirm this?\');" class="btn btn-success">Confirm and Finish</button>';
        endif;$str .='</form>';
    return $str;
    mysqli_close($conn);
}






function added_data_delete_edit__special_invoice($sql,$unique,$unique_GET,$COUNT_details_data,$page,$colspan,$row_get,$commission){
    global $conn;
    if($sql==NULL) return NULL;
    $str.='
		<form action="'.$page.'"  name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">';
    if($COUNT_details_data>0):
        $str.='
		<input type="hidden" name="'.$unique.'" id="'.$unique.'"  value="'.$unique_GET.'">
		<table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">';
        $str .='<thead><tr style="background-color: bisque; "><th style="vertical-align:middle; text-align:center">#</th>';
        if ($result = mysqli_query($conn , $sql)):
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val):
                $str .='<th style="vertical-align:middle; text-align:center">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            endforeach;
            $str .='<th style="width:5%; text-align:center; vertical-align:middle">Action</th></tr></thead><tbody>';
            $c=0;
            if (mysqli_num_rows($result)>0):
                while($row = mysqli_fetch_array($result)):
                    $str .='<tr style="vertical-align:middle"><td style="vertical-align:middle; text-align:center">'.($row[0]).'</td>';
                    for($i=1;$i<$cols;$i++):
                        $b=$row[$i];
                        if(is_numeric($row[$i])):
                            $str .='<td style="vertical-align:middle; text-align:right">'.$b."</td>";
                        else :
                            $str .='<td style="vertical-align:middle;">'.$b."</td>";
                        endif;
                    endfor;
                    $amount=$amount+$row[$row_get];
                    if($row[8]>0):
                    $str .='<td style="vertical-align:middle"><button type="submit" name="deletedata'.$row[0].'" style="background-color:transparent;color:red; border:none; margin:0px; font-size:17px; padding:0px; float:left" class="fa fa-trash" onclick="return window.confirm(\'Are you sure you want to delete this?\');" title="Delete"></button> 
                    <a href="'.$page.'?id='.$row[0].'" style="margin:0px; font-size:17px;padding:0px; float:right" class="fa fa-edit" onclick="return window.confirm(\'Are you sure you want to edit this?\');" title="Edit"></a>'."</td>";
                else:
                    $str .='<td style="vertical-align:middle"></td>';

                endif;
                    $str .='</tr>';
                endwhile;endif;
            mysqli_free_result($result);
        endif;
        $str .='</tbody>';
        $str .='<tfoot><tr><th colspan="'.$colspan.'" style="text-align:right">Order Value = </th><th style="text-align:right">'.number_format($amount,2).'</th><td></td></tr>';
        if($commission>0):
            $comissionGET=($amount/100)*$commission;
            $str.='<input type="hidden" name="commission_amount" id="commission_amount"  value="'.$comissionGET.'">';
        $str .='<tr><th colspan="'.$colspan.'" style="text-align:right">Less: Commission = </th><th style="text-align:right">'.number_format($comissionGET,2).'</th><td></td></tr>';
        $str .='<tr><th colspan="'.$colspan.'" style="text-align:right">Total Receivable Value = </th><th style="text-align:right">'.number_format($amount-$comissionGET,2).'</th><td></td></tr></table>';
    
    endif;
    $str .='</tfoot></table>';


		endif;
    $str .='<button style="float: left; font-size: 11px; margin-left: 1%" type="submit" name="cancel" onclick="return window.confirm(\'Are you sure you want to delete this?\');" class="btn btn-danger">Delete</button>';
    if($COUNT_details_data>0):
            $str .='<button style="float: right; font-size: 11px; margin-right: 1%" type="submit" name="confirm" onclick="return window.confirm(\'Are you sure you want to confirm this?\');" class="btn btn-success">Confirm and Finish</button>';
        endif;$str .='</form>';
    return $str;
    mysqli_close($conn);
}





function added_data_delete_edit_purchase_order($sql,$unique,$unique_GET,$COUNT_details_data,$page,$colspan,$row_get,$commission,$VAT){
    global $conn;
    if($sql==NULL) return NULL;
    $str = '';
    $str.='
		<form action="'.$page.'"  name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">';
    if($COUNT_details_data>0):
        $str.='
		<input type="hidden" name="'.$unique.'" id="'.$unique.'"  value="'.$unique_GET.'">
		<table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">';
        $str .='<thead><tr style="background-color: bisque; "><th style="vertical-align:middle; text-align:center">#</th>';
        if ($result = mysqli_query($conn , $sql)):
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val):
                $str .='<th style="vertical-align:middle; text-align:center">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            endforeach;
            $str .='<th style="width:5%; text-align:center; vertical-align:middle">Action</th></tr></thead><tbody>';
            $c=0;
            $amount = 0;
            if (mysqli_num_rows($result)>0):
                while($row = mysqli_fetch_array($result)):
                    $str .='<tr style="vertical-align:middle"><td style="vertical-align:middle; text-align:center">'.($row[0]).'</td>';
                    for($i=1;$i<$cols;$i++):
                        $b=$row[$i];
                        if(is_numeric($row[$i])):
                            $str .='<td style="vertical-align:middle; text-align:right">'.$b."</td>";
                        else :
                            $str .='<td style="vertical-align:middle;">'.$b."</td>";
                        endif;
                    endfor;
                    $amount=$amount+$row[$row_get];

                        $str .='<td style="vertical-align:middle"><button type="submit" name="deletedata'.$row[0].'" style="background-color:transparent;color:red; border:none; margin:0px; font-size:17px; padding:0px; float:left" class="fa fa-trash" onclick="return window.confirm(\'Are you sure you want to delete this?\');" title="Delete"></button> 
                    <a href="'.$page.'?id='.$row[0].'" style="margin:0px; font-size:17px;padding:0px; float:right" class="fa fa-edit" onclick="return window.confirm(\'Are you sure you want to edit this?\');" title="Edit"></a>'."</td>";
                        
                    $str .='</tr>';
                endwhile;endif;
            mysqli_free_result($result);
        endif;
        $str .='</tbody>';
        $str .='<tfoot><tr><th colspan="'.$colspan.'" style="text-align:right">Order Value = </th><th style="text-align:right">'.number_format($amount,2).'</th><td></td></tr>';

        if($VAT>0):
            //$str.='<input type="hidden" name="total_vat_amount" id="total_vat_amount"  value="'.$commission.'">';
            $str .='<tr><th colspan="'.$colspan.'" style="text-align:right">Add : VAT(' .number_format($VAT).'%) = </th><th style="text-align:right">'.number_format(($amount/100)*$VAT,2).'</th><td></td></tr>';

        endif;
        if($commission>0):
            //$str.='<input type="hidden" name="commission_amount" id="commission_amount"  value="'.$commission.'">';
            $str .='<tr><th colspan="'.$colspan.'" style="text-align:right">Less: Commission = </th><th style="text-align:right">'.number_format($commission,2).'</th><td></td></tr>';
            $str .='<tr><th colspan="'.$colspan.'" style="text-align:right">Total Payable Value = </th><th style="text-align:right">'.number_format($amount-$commission,2).'</th><td></td></tr></table>';

        endif;
        $str .='</tfoot></table>';


    endif;
    $str .='<button style="float: left; font-size: 11px; margin-left: 1%" type="submit" name="cancel" onclick="return window.confirm(\'Are you sure you want to delete this?\');" class="btn btn-danger">Delete</button>';
    if($COUNT_details_data>0):
        $str .='<button style="float: right; font-size: 11px; margin-right: 1%" type="submit" name="confirm" onclick="return window.confirm(\'Are you sure you want to confirm this?\');" class="btn btn-success">Confirm and Finish</button>';
    endif;$str .='</form>';
    return $str;
    mysqli_close($conn);
}

function adds_data_delete_edit($sql,$unique,$unique_GET,$COUNT_details_data,$page){
    global $conn;
    if($sql==NULL) return NULL;
    $str.='
		<form action="'.$page.'"  name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">';
    if($COUNT_details_data>0) {
        $str.='
		<input type="hidden" name="'.$unique.'" id="'.$unique.'"  value="'.$unique_GET.'">
		<table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">';
        $str .='<thead><tr style="background-color: bisque; "><th style="vertical-align:middle; text-align:center">#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle; text-align:center">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='<th style="width:5%; text-align:center; vertical-align:middle">Action</th></tr></thead><tbody>';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="vertical-align:middle"><td style="vertical-align:middle; text-align:center">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        if(is_numeric($row[$i])){
                            $str .='<td style="vertical-align:middle; text-align:right">'.$b."</td>";
                        } else {
                            $str .='<td style="vertical-align:middle;">'.$b."</td>";
                        }
                    }
                    $str .='<td style="vertical-align:middle"><button type="submit" name="deletedata'.$row[0].'" style="background-color:transparent;color:red; border:none; margin:0px; font-size:17px; padding:0px; float:left" class="fa fa-trash" onclick="return window.confirm(\'Are you sure you want to delete this?\');" title="Delete"></button> <a href="'.$page.'?id='.$row[0].'" style="margin:0px; font-size:17px;padding:0px; float:right" class="fa fa-edit" onclick="return window.confirm(\'Are you sure you want to edit this?\');" title="Edit"></a>'."</td>";
                    $str .='</tr>';
                }}
            mysqli_free_result($result);
        }
        $str .='</tbody></table>';
    }

    $str .='<button style="float: left; font-size: 11px; margin-left: 1%" type="submit" name="cancel" onclick="return window.confirm(\'Are you sure you want to delete this?\');" class="btn btn-danger">Delete</button>';

    if($COUNT_details_data>0) {
            $str .='<button style="float: right; font-size: 11px; margin-right: 1%" type="submit" name="confirm" onclick="return window.confirm(\'Are you sure you want to confirm this?\');" class="btn btn-success">Confirm and Finish</button>';
        }$str .='</form>';
    return $str;
    mysqli_close($conn);
}

function dataview($sql,$unique,$unique_GET,$COUNT_details_data,$page){
    global $conn;
    if($sql==NULL) return NULL;
    $str.='<table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">';
        $str .='<thead><tr style="background-color: bisque; "><th style="vertical-align:middle; text-align:center">#</th>';

        if ($result = mysqli_query($conn , $sql)) {
            $cols = mysqli_num_fields($result);
            $fieldinfo = mysqli_fetch_fields($result);
            foreach (array_slice($fieldinfo, 1) as $key=>$val) {
                $str .='<th style="vertical-align:middle; text-align:center">'.ucwords(str_replace('_', ' ',$val->name)).'</th>';
            }
            $str .='</tr></thead><tbody>';
            $c=0;
            if (mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)) {
                    $str .='<tr style="vertical-align:middle"><td style="vertical-align:middle; text-align:center">'.($sl=$sl+1).'</td>';
                    for($i=1;$i<$cols;$i++) {
                        $b=$row[$i];
                        if(is_numeric($row[$i])){
                            $str .='<td style="vertical-align:middle; text-align:right">'.$b."</td>";
                        } else {
                            $str .='<td style="vertical-align:middle;">'.$b."</td>";
                        }
                    }
                    $str .='</tr>';
                }}
            mysqli_free_result($result);
        }
        $str .='</tbody></table>';
		return $str;
    mysqli_close($conn);
}

function selectmultipleoptions($values){
foreach ($values as $a){
     $str .="'".$a."',";
}return substr($str,0,-1);}

?>
