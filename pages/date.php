<input type="text" id ="text1" onblur="someFunction()"/><br>
<input type="text" id ="text2" onblur="someFunction()"/><br>
<input type="text" readonly="readonly" disabled="disabled" id ="text3"/>

<script>
    function someFunction()
    {
        var p1 = $('#text1').val();
        var p2 = $('#text2').val;
        if(p1=='' || p2=='')
        {
            alert('Please Fill all the Values');
        }
        else
        {
            var p3 = ((p1/p2)*100)-100;
            $('#text3').val(p3);
        }
    }
</script>