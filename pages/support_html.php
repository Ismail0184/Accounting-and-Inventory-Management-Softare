<input name="<?=$unique?>" type="hidden" id="<?=$unique?>" value="<?=$$unique?>"  />
<input type="hidden" id="create_date" style="width:400px"    name="create_date" value="<?=$_SESSION['create_date'];?>" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="entry_status" style="width:400px"    name="entry_status" value="MANUAL" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="ip" style="width:400px"    name="ip" value="<?=$ip?>" class="form-control col-md-7 col-xs-12">
<input type="hidden" id="entry_by" style="width:400px"    name="entry_by" value=<?=$_SESSION['userid']?>"" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="entry_at" style="width:400px"    name="entry_at" value=<?=date('Y-m-d H:i:s');?>"" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="edit_at" style="width:400px"    name="edit_at" value=<?=date('Y-m-d H:i:s');?>"" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="section_id" style="width:400px"   name="section_id" value="<?=$_SESSION['sectionid']?>" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="company_id" style="width:400px"    name="company_id" value="<?=$_SESSION['companyid']?>" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="companyid" style="width:400px"    name="companyid" value="<?=$_SESSION['companyid']?>" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="work_order_for_department" style="width:400px"    name="work_order_for_department" value="<?=$_SESSION["department"]?>" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="time" style="width:400px"    name="time" value="<?=$now;?>" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="day_name" style="width:400px"    name="day_name" value="<?=$day;?>" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="day" style="width:400px"    name="day" value="<?=$thisday;?>" class="form-control col-md-7 col-xs-12">
<input type="hidden" id="month" style="width:400px"    name="month" value=<?=$thismonth;?>"" class="form-control col-md-7 col-xs-12" >
<input type="hidden" id="year" style="width:400px"   name="year" value="<?=$thisyear;?>" class="form-control col-md-7 col-xs-12" >