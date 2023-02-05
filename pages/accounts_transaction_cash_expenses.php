<?php
 ob_start();
 session_start();
 require_once 'base.php';
  require_once 'create_id.php';
  require_once 'module.php';
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['login_email']) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM company WHERE companyid=".$_SESSION['companyid']);
 $userRow=mysql_fetch_array($res);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $_SESSION[company]; ?> | Cash Expenses Transaction</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
<!--SCRIPT language=JavaScript>

function doAlert(form)
{
var val=form.amount.value;
var val2=form.balance.value;

if (Number(val)>Number(val2)){
alert('oops!! Exceed Cash Balance!! Thanks');

form.amount.value='';
}
form.amount.focus();
}</script---> 


<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.ledger.options[form.ledger.options.selectedIndex].value;
	self.location='accounts_transaction_cash_expenses.php?ledgercode=' + val ;
}


</script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="http://raresoft.org/" class="site_title"><i class="fa fa-paw"></i> <span>Raresoft</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
           <?php include ("pro.php");  ?>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                         <?php include("sidebar_menu.php"); ?>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
            <?php include("menu_footer.php"); ?>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
         <?php include("top.php"); ?>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
           
           

            <div class="row">
              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Cash Expenses Transaction</h2>
                     <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="accounts_transaction_cash_received.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Cash Received</span>
								</a>
                                
                                <a class="btn btn-sm btn-default"  href="accounts_transaction_bank_expenses.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Bank Expenses</span>
								</a>
                                
                                <a class="btn btn-sm btn-default"  href="accounts_transaction_bank_received.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Bank Received</span>
								</a>
                    			
                    			               
                    			<a class="btn btn-sm btn-default" style="color:#000" href="accountsreport.php">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Ledger Report</span>
                    			</a>
		 						
								
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    
                    
                      <?php 

				
                $getstarted=$_POST[getstarted];
				$inputby=$_SESSION['login_email'];
                $companyid=$_SESSION['companyid'];
                $create_date=date('Y-m-d');

				$VNumber=$_POST[VNumber];
                $TDate=$_POST[TDate];
				$amount=$_POST[amount];
                $subledgercode=$_POST[subledgercode];
				$companyid=$_SESSION['companyid'];
				

				
				$ledgerresults=mysql_query("Select * from accounts_ledger where ledgercode='$_GET[ledgercode]' and companyid='$_SESSION[companyid]'");
				$ledgerrow=mysql_fetch_array($ledgerresults);
				
				
				
				
				
			$subleresult=mysql_query("Select * from accounts_subledger where subledgercode='$subledgercode' and companyid='$_SESSION[companyid]'");
			$subledgerrow=mysql_fetch_array($subleresult);
			$subledger=$subledgerrow[subledger];
				
				
				$ttype=$_POST[ttype];
                $invoiceno=$_POST[invoiceno];
                $tdates=date("Y-m-d");
				$idatess=date('Y-m-d'); 
                $day = date('l', strtotime($idatess));
				$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
				$timess=$dateTime->format("d/m/y  H:i A");
				$note=$_POST[note];  
				$note0=$ledgerrow[ledger].'Paid'.','.$subledger.','. $note;
				$note1='Main Cash,'.'Paid'. $subledger. $note;





$mcresult=mysql_query("Select * from accounts_ledger where ledger='Main Cash' and companyid='$_SESSION[companyid]'");
$mcrow=mysql_fetch_array($mcresult);				



//echo "This is test";

//cash rceived transaction start from here		 this is test code...		

if (isset($_POST['getstarted'])){
$valid = true;	
	




	if ( $amount==0)
{echo "<script> alert('Opps!! Invaild Transaction!!') </script>";
        $valid = false;}
	
	
		
if ($valid){
$delete=mysql_query("Delete from transaction_cash where VNumber='$VNumber' and companyid='$_SESSION[companyid]'");

$cashtransaction=mysql_query("INSERT INTO transaction_cash (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,subledgercode,subledger,VNumber,TDate,Amount,debitamount,creditamount,Note,SubID,companyid,Username,IPAdress,IDate,time,ttype,invoiceno,day,sectionname,journal) VALUES 
('$mcrow[accountreporttype]','$mcrow[rlid]','$mcrow[reportlevelname]','$mcrow[mgid]','$mcrow[maingroup]','$mcrow[subsidiaryid]','$mcrow[subsidiary]','$mcrow[ledgercode]','Main Cash','$subledgercode','$subledger','$VNumber','$tdates','-$amount','','$amount','$note0','0','$companyid','$_SESSION[login_email]','$ip','$create_date','$timess','Cash Expenses','$invoiceno','$day','All','CR')");

// Party ledger start from here....

$cashtransaction=mysql_query("INSERT INTO transaction_cash (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,subledgercode,subledger,VNumber,TDate,Amount,debitamount,creditamount,Note,SubID,companyid,Username,IPAdress,IDate,time,ttype,invoiceno,day,sectionname,journal) VALUES 
('$ledgerrow[accountreporttype]','$ledgerrow[rlid]','$ledgerrow[reportlevelname]','$ledgerrow[mgid]','$ledgerrow[maingroup]','$ledgerrow[subsidiaryid]','$ledgerrow[subsidiary]','$ledgerrow[ledgercode]','$ledgerrow[ledger]','$subledgercode','$subledger','$VNumber','$tdates','$amount','$amount','','$note1','1','$companyid','$_SESSION[login_email]','$ip','$tdates','$timess','Cash Expenses','$invoiceno','$day','All','DR')");

echo "Data Successfully Saved $ledgerrow[ledger]";
				ceshexpensesid(); ?>
                <meta http-equiv="refresh" content="0;accounts_transaction_cash_expenses.php">

<?php }} ?>                    
                    
                    
                    <form class="form-horizontal form-label-left" method="post">



                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%" tabindex="-1" onchange="javascript:reload(this.form)"  required="required" name="ledger" >
                            <option></option>
                            
                            <?php 
							$result=mysql_query("Select * from accounts_ledger where companyid='$_SESSION[companyid]'  order by ledger");
							while($row=mysql_fetch_array($result)){
								
								if(($_GET[ledgercode])==$row[ledgercode]){?> 
                                         
                 
                 
                 
                 <option selected value="<?php echo $row[ledgercode]; ?>"><?php echo $row[ledger]; ?></option>
                        <?php } else { ?>
                  <option  value="<?php echo $row[ledgercode]; ?>"><?php echo $row[ledger]; ?></option>
                    <?php }} ?>
                          </select>
                        </div>
                      </div>




<?php 
							$results=mysql_query("Select * from accounts_subledger where ledgercode='$_GET[ledgercode]' and companyid='$_SESSION[companyid]'");
							$row123=mysql_fetch_array($results);
							
							if($row123[subledgercode]>0){
								
								?>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Ledger</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="subledgercode" >
                            <option></option>
                            
                            <?php 
							$result=mysql_query("Select * from accounts_subledger where ledgercode='$_GET[ledgercode]' and companyid='$_SESSION[companyid]' order by subledger");
							while($row=mysql_fetch_array($result)){ ?>
								
								
                        <option  value="<?php echo $row[subledgercode]; ?>"><?php echo $row[subledger]; ?></option>
                    <?php } ?>
                          </select>
                        </div>
                      </div>
                      
                      
                      <?php } ?>
                      
                    




<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bill No</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%" tabindex="1"  name="invoiceno" >
                            <option></option>
                            
                            <?php 
							$result=mysql_query("Select distinct invoiceno,ledgercode from transaction_cash where ledgercode='$_GET[ledgercode]' and companyid='$_SESSION[companyid]' order by VNumber");
							while($row=mysql_fetch_array($result)){ ?>
								
			<?php 
			  $pandinginv=getSVALUE("transaction_cash", "SUM(Amount) as Amount", "where invoiceno='$row[invoiceno]' and ledgercode='$_GET[ledgercode]'  and companyid='$_SESSION[companyid]'");
			  $pandinginvs=substr($pandinginv,1);
			  ?>
			  
			  					
                        <option  value="<?php echo $row[invoiceno]; ?>"><?php echo $row[invoiceno]; ?>    (<?php echo $pandinginvs; ?> tk)</option>
                        
                        
                    <?php } ?>
                          </select>
                        </div>
                      </div>











               <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Cash Voucher<span class="required">*</span></label>
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="first-name" required="required" style="width:100%" value="<?php echo $_SESSION['ceshexpenses']; ?>" name="VNumber" class="form-control col-md-7 col-xs-12" readonly></div></div>


<?php 
			 $cashbalance=getSVALUE("transaction_cash", "SUM(Amount) as Amount", "where  ledger='Main Cash' and companyid='$_SESSION[companyid]'");             $cashbalances=number_format($cashbalance,2);
			  ?>
                      
                      <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Amount<span class="required">*</span></label>
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="first-name" required="required" style="width:47%"  name="amount" class="form-control col-md-7 col-xs-12" onkeyup="doAlert(this.form);">
               
               <input type="hidden" id="first-name" style="width:47%; margin-left:6%; font-weight:bold; color:#F00" value="<?php if($cashbalance>0) { echo $cashbalance; } else {echo "00";} ?>"  readonly name="balance" class="form-control col-md-7 col-xs-12">
               
               <input type="text" id="first-name" style="width:47%; margin-left:6%; font-weight:bold; color:#F00" value="Cash Balance: <?php if($cashbalance>0) { echo $cashbalances; } else {echo "00";}  ?> ৳"  readonly name="balances" class="form-control col-md-7 col-xs-12"></div></div>
                      
                      
                    <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Note<span class="required">*</span></label>
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <textarea id="first-name"  style="width:100%"  name="note" class="form-control col-md-7 col-xs-12"> </textarea></div></div>   
                      
                    
                     

                     
                     
                     
                      

                     


                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <a href="accounts_transaction_cash_received.php"  class="btn btn-primary">Cancel</a>
                          <button type="submit" class="btn btn-success" name="getstarted">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>


            

              
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="../vendors/starrr/dist/starrr.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <!-- bootstrap-daterangepicker -->
    <script>
      $(document).ready(function() {
        $('#birthday').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_4"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
    <!-- /bootstrap-daterangepicker -->

    <!-- bootstrap-wysiwyg -->
    <script>
      $(document).ready(function() {
        function initToolbarBootstrapBindings() {
          var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
              'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
              'Times New Roman', 'Verdana'
            ],
            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
          $.each(fonts, function(idx, fontName) {
            fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
          });
          $('a[title]').tooltip({
            container: 'body'
          });
          $('.dropdown-menu input').click(function() {
              return false;
            })
            .change(function() {
              $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
            })
            .keydown('esc', function() {
              this.value = '';
              $(this).change();
            });

          $('[data-role=magic-overlay]').each(function() {
            var overlay = $(this),
              target = $(overlay.data('target'));
            overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
          });

          if ("onwebkitspeechchange" in document.createElement("input")) {
            var editorOffset = $('#editor').offset();

            $('.voiceBtn').css('position', 'absolute').offset({
              top: editorOffset.top,
              left: editorOffset.left + $('#editor').innerWidth() - 35
            });
          } else {
            $('.voiceBtn').hide();
          }
        }

        function showErrorAlert(reason, detail) {
          var msg = '';
          if (reason === 'unsupported-file-type') {
            msg = "Unsupported format " + detail;
          } else {
            console.log("error uploading file", reason, detail);
          }
          $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
        }

        initToolbarBootstrapBindings();

        $('#editor').wysiwyg({
          fileUploadError: showErrorAlert
        });

        window.prettyPrint;
        prettyPrint();
      });
    </script>
    <!-- /bootstrap-wysiwyg -->

    <!-- Select2 -->
    <script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Select a state",
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
    <!-- /Select2 -->

    <!-- jQuery Tags Input -->
    <script>
      function onAddTag(tag) {
        alert("Added a tag: " + tag);
      }

      function onRemoveTag(tag) {
        alert("Removed a tag: " + tag);
      }

      function onChangeTag(input, tag) {
        alert("Changed a tag: " + tag);
      }

      $(document).ready(function() {
        $('#tags_1').tagsInput({
          width: 'auto'
        });
      });
    </script>
    <!-- /jQuery Tags Input -->

    <!-- Parsley -->
    <script>
      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#demo-form .btn').on('click', function() {
          $('#demo-form').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#demo-form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });

      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#demo-form2 .btn').on('click', function() {
          $('#demo-form2').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#demo-form2').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });
      try {
        hljs.initHighlightingOnLoad();
      } catch (err) {}
    </script>
    <!-- /Parsley -->

    <!-- Autosize -->
    <script>
      $(document).ready(function() {
        autosize($('.resizable_textarea'));
      });
    </script>
    <!-- /Autosize -->

    <!-- jQuery autocomplete -->
    <script>
      $(document).ready(function() {
        var countries = { AD:"Andorra",A2:"Andorra Test",AE:"United Arab Emirates",AF:"Afghanistan",AG:"Antigua and Barbuda",AI:"Anguilla",AL:"Albania",AM:"Armenia",AN:"Netherlands Antilles",AO:"Angola",AQ:"Antarctica",AR:"Argentina",AS:"American Samoa",AT:"Austria",AU:"Australia",AW:"Aruba",AX:"Åland Islands",AZ:"Azerbaijan",BA:"Bosnia and Herzegovina",BB:"Barbados",BD:"Bangladesh",BE:"Belgium",BF:"Burkina Faso",BG:"Bulgaria",BH:"Bahrain",BI:"Burundi",BJ:"Benin",BL:"Saint Barthélemy",BM:"Bermuda",BN:"Brunei",BO:"Bolivia",BQ:"British Antarctic Territory",BR:"Brazil",BS:"Bahamas",BT:"Bhutan",BV:"Bouvet Island",BW:"Botswana",BY:"Belarus",BZ:"Belize",CA:"Canada",CC:"Cocos [Keeling] Islands",CD:"Congo - Kinshasa",CF:"Central African Republic",CG:"Congo - Brazzaville",CH:"Switzerland",CI:"Côte d'Ivoire",CK:"Cook Islands",CL:"Chile",CM:"Cameroon",CN:"China",CO:"Colombia",CR:"Costa Rica",CS:"Serbia and Montenegro",CT:"Canton and Enderbury Islands",CU:"Cuba",CV:"Cape Verde",CX:"Christmas Island",CY:"Cyprus",CZ:"Czech Republic",DD:"East Germany",DE:"Germany",DJ:"Djibouti",DK:"Denmark",DM:"Dominica",DO:"Dominican Republic",DZ:"Algeria",EC:"Ecuador",EE:"Estonia",EG:"Egypt",EH:"Western Sahara",ER:"Eritrea",ES:"Spain",ET:"Ethiopia",FI:"Finland",FJ:"Fiji",FK:"Falkland Islands",FM:"Micronesia",FO:"Faroe Islands",FQ:"French Southern and Antarctic Territories",FR:"France",FX:"Metropolitan France",GA:"Gabon",GB:"United Kingdom",GD:"Grenada",GE:"Georgia",GF:"French Guiana",GG:"Guernsey",GH:"Ghana",GI:"Gibraltar",GL:"Greenland",GM:"Gambia",GN:"Guinea",GP:"Guadeloupe",GQ:"Equatorial Guinea",GR:"Greece",GS:"South Georgia and the South Sandwich Islands",GT:"Guatemala",GU:"Guam",GW:"Guinea-Bissau",GY:"Guyana",HK:"Hong Kong SAR China",HM:"Heard Island and McDonald Islands",HN:"Honduras",HR:"Croatia",HT:"Haiti",HU:"Hungary",ID:"Indonesia",IE:"Ireland",IL:"Israel",IM:"Isle of Man",IN:"India",IO:"British Indian Ocean Territory",IQ:"Iraq",IR:"Iran",IS:"Iceland",IT:"Italy",JE:"Jersey",JM:"Jamaica",JO:"Jordan",JP:"Japan",JT:"Johnston Island",KE:"Kenya",KG:"Kyrgyzstan",KH:"Cambodia",KI:"Kiribati",KM:"Comoros",KN:"Saint Kitts and Nevis",KP:"North Korea",KR:"South Korea",KW:"Kuwait",KY:"Cayman Islands",KZ:"Kazakhstan",LA:"Laos",LB:"Lebanon",LC:"Saint Lucia",LI:"Liechtenstein",LK:"Sri Lanka",LR:"Liberia",LS:"Lesotho",LT:"Lithuania",LU:"Luxembourg",LV:"Latvia",LY:"Libya",MA:"Morocco",MC:"Monaco",MD:"Moldova",ME:"Montenegro",MF:"Saint Martin",MG:"Madagascar",MH:"Marshall Islands",MI:"Midway Islands",MK:"Macedonia",ML:"Mali",MM:"Myanmar [Burma]",MN:"Mongolia",MO:"Macau SAR China",MP:"Northern Mariana Islands",MQ:"Martinique",MR:"Mauritania",MS:"Montserrat",MT:"Malta",MU:"Mauritius",MV:"Maldives",MW:"Malawi",MX:"Mexico",MY:"Malaysia",MZ:"Mozambique",NA:"Namibia",NC:"New Caledonia",NE:"Niger",NF:"Norfolk Island",NG:"Nigeria",NI:"Nicaragua",NL:"Netherlands",NO:"Norway",NP:"Nepal",NQ:"Dronning Maud Land",NR:"Nauru",NT:"Neutral Zone",NU:"Niue",NZ:"New Zealand",OM:"Oman",PA:"Panama",PC:"Pacific Islands Trust Territory",PE:"Peru",PF:"French Polynesia",PG:"Papua New Guinea",PH:"Philippines",PK:"Pakistan",PL:"Poland",PM:"Saint Pierre and Miquelon",PN:"Pitcairn Islands",PR:"Puerto Rico",PS:"Palestinian Territories",PT:"Portugal",PU:"U.S. Miscellaneous Pacific Islands",PW:"Palau",PY:"Paraguay",PZ:"Panama Canal Zone",QA:"Qatar",RE:"Réunion",RO:"Romania",RS:"Serbia",RU:"Russia",RW:"Rwanda",SA:"Saudi Arabia",SB:"Solomon Islands",SC:"Seychelles",SD:"Sudan",SE:"Sweden",SG:"Singapore",SH:"Saint Helena",SI:"Slovenia",SJ:"Svalbard and Jan Mayen",SK:"Slovakia",SL:"Sierra Leone",SM:"San Marino",SN:"Senegal",SO:"Somalia",SR:"Suriname",ST:"São Tomé and Príncipe",SU:"Union of Soviet Socialist Republics",SV:"El Salvador",SY:"Syria",SZ:"Swaziland",TC:"Turks and Caicos Islands",TD:"Chad",TF:"French Southern Territories",TG:"Togo",TH:"Thailand",TJ:"Tajikistan",TK:"Tokelau",TL:"Timor-Leste",TM:"Turkmenistan",TN:"Tunisia",TO:"Tonga",TR:"Turkey",TT:"Trinidad and Tobago",TV:"Tuvalu",TW:"Taiwan",TZ:"Tanzania",UA:"Ukraine",UG:"Uganda",UM:"U.S. Minor Outlying Islands",US:"United States",UY:"Uruguay",UZ:"Uzbekistan",VA:"Vatican City",VC:"Saint Vincent and the Grenadines",VD:"North Vietnam",VE:"Venezuela",VG:"British Virgin Islands",VI:"U.S. Virgin Islands",VN:"Vietnam",VU:"Vanuatu",WF:"Wallis and Futuna",WK:"Wake Island",WS:"Samoa",YD:"People's Democratic Republic of Yemen",YE:"Yemen",YT:"Mayotte",ZA:"South Africa",ZM:"Zambia",ZW:"Zimbabwe",ZZ:"Unknown or Invalid Region" };

        var countriesArray = $.map(countries, function(value, key) {
          return {
            value: value,
            data: key
          };
        });

        // initialize autocomplete with custom appendTo
        $('#autocomplete-custom-append').autocomplete({
          lookup: countriesArray
        });
      });
    </script>
    <!-- /jQuery autocomplete -->

    <!-- Starrr -->
    <script>
      $(document).ready(function() {
        $(".stars").starrr();

        $('.stars-existing').starrr({
          rating: 4
        });

        $('.stars').on('starrr:change', function (e, value) {
          $('.stars-count').html(value);
        });

        $('.stars-existing').on('starrr:change', function (e, value) {
          $('.stars-count-existing').html(value);
        });
      });
    </script>
    <!-- /Starrr -->
  </body>
</html>
