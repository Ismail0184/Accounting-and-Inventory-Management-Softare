<?php
session_start();


$mode = $_GET['view'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="imagetoolbar" content="no">
    <meta name="distribution" content="global" />
    <meta name="revisit" content="10 days" />
    <meta name="revisit-after" content="10 days" />
    <meta name="resource-type" content="document" />
    <meta name="audience" content="all" />
    <meta name="rating" content="general" />
    <meta name="robots" content="all" />
    <meta name="robots" content="index, follow" />
    <meta name="language" content="en" />
    <meta name="country" content="US" />
    <title>Add City</title>
    <script type="text/javascript" src="<?=$INFO['siteaddr']?>scripts/selectstate.js"></script>
</head>
<body bgcolor="#FFFFFF" text="#000000">
<center>
    <table width="300" cellpadding="0" cellspacing="0" align="center">
        <tr><td align="center"><br />
                <? if ($mode == "window") {
                if($_POST['AddCity'] == "Add City") {
                    $country = $_POST['country'];
                    $states = $_POST['state'];
                    $city = $_POST['cname'];
                    print_r($_POST);
                } // Close Post ?>
                <fieldset>
                    <legend>Add City</legend>
                    <table cellpadding="0" cellspacing="0" align="left">
                        <form name="AddCities" method="POST">
                            <tr>
                                <td>Country:&nbsp;</td>
                                <td>
                                    <select name="country" style="width: 146px" onblur="showState(this.value);">
                                        <option value="0" selected="selected">&nbsp;</option>
                                        <? $sql = "select * from `countries` ORDER BY `sortorder`";
                                        $result = $DB->query($sql);
                                        while($row = mysql_fetch_array($result)) { ?>
                                            <option value="<?=$row[0]?>"><?=ucwords($row[1])?></option>
                                        <? }?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>State:&nbsp;</td>
                                <td>
                                    <div id="txtstate">
                                        <select name="state" id="state" style="width: 146px;">
                                            <option value="0" selected="selected">&nbsp;</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>State:&nbsp;</td>
                                <td>
                                    <div id="txtstate">
                                    </div>
                                </td>
                            </tr>

                            <tr><td>City Name:&nbsp;</td><td><input type="text" name="cname" size="20" /></td></tr>
                            <tr><td colspan="2" align="center"><br /><br /><input type="submit" name="AddCity" value="Add City" /></td></tr>
                        </form>
                    </table>
                </fieldset>
                
            </td></tr>
    </table>
</center>
</body>
</html>