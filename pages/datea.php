<div class="input-append date form_datetime" data-date="2012-12-21T15:25:00Z">
    <input size="16" type="text" value="" readonly>
    <span class="add-on"><i class="icon-remove"></i></span>
    <span class="add-on"><i class="icon-th"></i></span>
</div>
<input type="text" id="mirror_field" value="" readonly />
 
<script type="text/javascript">
    $(".form_datetime").datetimepicker({
        format: "dd MM yyyy - hh:ii",
        linkField: "mirror_field",
        linkFormat: "yyyy-mm-dd hh:ii"
    });
</script>   