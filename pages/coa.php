<?php
require_once 'support_file.php';
$title='Accounts Report';
?>





<?php require_once 'header_content.php'; ?>
<script src="javascript_coa/jquery-1.5.2.1.js"></script>
<link rel="stylesheet" href="css_coa/jquery.autocomplete.css" type="text/css" />
<script type="text/javascript" src="javascript_coa/jquery.autocomplete.js"></script>
<script>
    $(document).ready(function(){
        //  var data = "Core Selectors Attributes Traversing Manipulation CSS Events Effects Ajax Utilities".split(" ");
        var data = "<?php echo $_SESSION['achd'];?>";
        var datasub = "<?php echo $_SESSION['shead'];?>";
        var datasubs = "<?php echo $_SESSION['projectname'];?>";

        data =data.split(", ");
        datasub =datasub.split(", ");
        $("#lcnumber").autocomplete(data);
        $("#item").autocomplete(datasub);
        $("#item").autocomplete(datasub);

        $("#master").hide();
        $("#report").click(function () {
            $("#inputdiv").hide("slow");
            $("#master").show(1000);
        });

        $("#hideme").click(function () {
            $("#inputdiv").show("slow");
            $("#master").hide(1000);
        });

    });
</script>

<!-- CSS and link-->
<link type="text/css" href="css_coa/logpart.css" media="screen" rel="stylesheet" />
<link type="text/css" href="css_coa/form.css" media="screen" rel="stylesheet" />
<link type="text/css" href="css_coa/form.css" media="screen" rel="stylesheet" />


<!--for validation code-->
<link href="css_coa/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
<!--<script src="js_coa/jquery-1.5.1.min.js" type="text/javascript"></script>
-->
<script src="js_coa/languages/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="js_coa/jquery.validationEngine.js" type="text/javascript"></script>

<script>

    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#myform").validationEngine('attach', {promptPosition : "topLeft"});
    });
</script>
<!--end of the validation code
-->


<!--Report link-->
<script src="js_coa/jquery-1.2.1.min.js" type="text/javascript"></script>
<script src="js_coa/menu.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css_coa/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--end of Report link-->

<style>
    .button {
        color: #fff;
        border-top:3px double #9cf;
        border-left:3px double #9cf;
        border-right:3px double #4080BF;
        border-bottom:3px double #4080BF;
        background: #996;
        text-align: center;
    }
    .button:hover { background-color:#BBB; color:#999;}

    #logcontainer{
        border:5px solid #996;
        height:330px;
        min-width:400px;
        -moz-border-radius: 20px;
        border-radius: 20px;
        border-right-radius: 20px;

    }

    .logtext{-moz-border-radius: 20px;
        border-radius: 20px;
        padding:5px;
    }
    #logbutton{
        height:30px;
    }
    h1{	font-size:22px}

    table{

    }
    table td { height:40px; border-bottom:1px solid #996; width:100px}
    input { width:200px;
        height:23px;
        -moz-border-radius: 20px;
        border-radius: 20px;
        padding:4px;
        font-size:18px;
    }

    table th{ border-bottom:1px solid #036; padding:5px}

    input:focus{ background-color:#FF3}



    <!--for bottom menu->
        .nav-wrap {  background-color: rgba(0,0,0,0.6); border-top: 2px solid white; border-bottom: 5px solid white; }

    .group:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
    *:first-child+html .group { zoom: 1; } /* IE7 */

    #example-one { margin: 0 auto; list-style: none; position: relative; width: 650px; }
    #example-one li { display: inline; }
    #example-one li a { color: #bbb; font-size: 14px; display: block; float: left; padding: 6px 10px 4px 10px; text-decoration: none;  }
    #example-one li a:hover { color: white; }
    #magic-line { position: absolute; bottom: -5px; left: 0; width: 100px; height: 5px; background: #930; }
</style>
</head>
<?php require_once 'body_content.php'; ?>



<p align="center"><u><b><span id ='lang'><?php echo $translator[chartofaccount]?></span></b></u></p><br />
	
	<?php					
	echo "<ul id='menu4' class='menu collapsible expandfirst' style='padding-left:20px'>"; // ReportLevel
		echo "<li>";
         $ReportLevel = mysql_query("select * from ledger_group where 1 order by group_id");
        $m=1;
		while ( $ReportLevelrows = mysql_fetch_array($ReportLevel)){
			echo "<a href='#' style='padding-left:10px'>$ReportLevelrows[group_name]</a>";
			$m=$m+1;	
			echo "<ul id='rl$m' class='menu collapsible expandfirst'>"; // MainGroup
		echo "<li>";
         $maingroup = mysql_query("select * from accounts_ledger where ledger_group_id='$ReportLevelrows[group_id]' ");
        $k=1;
		while ( $maingrouprows = mysql_fetch_array($maingroup)){
			echo "<a href='#' style='padding-left:10px'>$maingrouprows[ledger_id] - $maingrouprows[ledger_name]</a>";
			$k=$k+1;	
		echo "<ul id='mg$k' class='menu collapsible expandfirst'>"; //Subsidiary
		echo "<li>";
         $Subsidiary = mysql_query("select * from sub_ledger where ledger_id='$maingrouprows[ledger_id]' ");
        $j=1;
		while ( $Subsidiaryrows = mysql_fetch_array($Subsidiary)){
			echo "<a href='#' style='padding-left:20px'>$Subsidiaryrows[sub_ledger_id] - $Subsidiaryrows[sub_ledger]</a>";
			$j=$j+1;
		echo "<ul id='ss$j' class='menu collapsible expandfirst'>"; // Subsubsidiary
		echo "<li>";
         $subsubhead = mysql_query("select distinct Subsubsidiary from coa_subsubsidiary where Subsidiary='$Subsidiaryrows[Subsidiary]' and Company='$_SESSION[company]'");
        $i=1;
		while ( $subsubheadrows = mysql_fetch_array($subsubhead)){
			echo "<a href='#' style='padding-left:50px'>$subsubheadrows[Subsubsidiary]</a>";
			$i=$i+1;

		echo "<ul id='ssub$i' class='menu collapsible expandfirst'>"; // AccountHead
		echo "<li>";
		$AccountHead=mysql_query("select distinct AccountHead, off, ID from coa_accounthead where Subsubsidiary='$subsubheadrows[Subsubsidiary]' and Company='$_SESSION[company]'");
					while ($AccountHeadrows=mysql_fetch_array($AccountHead)){
		            $rn='';
					$rownumber=mysql_query("select Subhead from coa_subhead where AccountHead='$AccountHeadrows[AccountHead]' and Company='$_SESSION[company]' order by Subhead");
			  		$rn=mysql_num_rows($rownumber);
					if ($AccountHeadrows[off]==0) 
					{$acc="<span id='ok'>".$AccountHeadrows[AccountHead]."</span>"; $off=1;} else 
					{$acc="<span id='no'>".$AccountHeadrows[AccountHead]."</span>"; $off=0;}
					if ($rn==0) {echo "<a href='#' style='padding-left:150px'>$acc</a></li>";} else {
					echo "<a href='#' style='padding-left:150px'>$AccountHeadrows[AccountHead]- $rn</a></li>";}
					echo "<ul>";
              $subhead=mysql_query("select Subhead from coa_subhead where AccountHead='$AccountHeadrows[AccountHead]' and Company='$_SESSION[company]' order by Subhead");
					while ($subheadrows=mysql_fetch_array($subhead)){
						echo "<li ><a href='http://www.pivotx.net/' style='padding-left:200px'>$subheadrows[Subhead]</a></li>";
					}
			echo "</ul>";
			 }
			echo "</li></ul>"; // AccountHead
 			}
	echo "</li></ul>"; // Subsubsidiary
	}
	echo "</li></ul>"; //Subsidiary
	}
	echo "</li></ul>"; // MainGroup
	}
	echo "</li></ul>"; // ReportLevel
	?>
    
 
</div><br />


<?php require_once 'footer_content.php' ?>