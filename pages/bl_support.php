




<table style="display:none" width="98%" cellspacing="0" cellpadding="2" border="0" class="tabledesign">
    <tr style="background:#0C9; font-weight:bold; color:#FFF; font-size:14px;">
        <td colspan="3" style="color:#FFF;">Revenue</td></tr>

    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px">Sales</td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $salesQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.cr_amt-j.dr_amt)  as sales  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('3002','3003') and 
				   a.ledger_id not in ('3002000600000000','3003000200000000') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($salesROW=mysql_fetch_object($salesQUARY)){
                $salesNormal=$salesROW->sales;  }
            echo number_format($salesNormal,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $salespreQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.cr_amt-j.dr_amt)  as salespre  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('3002','3003') and 
				   a.ledger_id not in ('3002000600000000','3003000200000000') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($salespreROW=mysql_fetch_object($salespreQUARY)){
                $salespreNormal=$salespreROW->salespre;  }
            echo number_format($salespreNormal,2);?></td>
    </tr>



    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px">Less: Sales Return</td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $salesreturnQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as salesreturn  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_id in ('3002000600000000','3003000200000000') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($salesreturnROW=mysql_fetch_object($salesreturnQUARY)){
                $salesreturn=$salesreturnROW->salesreturn;  }
            echo number_format($salesreturn,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $salesreturnPREQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as salesreturnpre  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_id in ('3002000600000000','3003000200000000') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($salesreturnPREROW=mysql_fetch_object($salesreturnPREQUARY)){
                $salesreturnPRE=$salesreturnPREROW->salesreturnpre;  }
            echo number_format($salesreturnPRE,2);?></td>
    </tr>



    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px"><strong>Gross Sales </strong></td>
        <td align="right" bgcolor="#FFFFFF">
            <strong><?php $sales=$salesNormal-$salesreturn; echo number_format($sales,2);?></strong></td>

        <td align="right" bgcolor="#FFFFFF">
            <strong><? $salespre=$salespreNormal-$salesreturnPRE; echo number_format($salespre,2);?></strong></td>
    </tr>




    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px">Less: VAT</td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $led=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as vat  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4015') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($vatROW=mysql_fetch_object($led)){
                $totalvat=$vatROW->vat;  }
            echo number_format($totalvat,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $ledPRE=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as vatpre  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4015') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($vatPREROW=mysql_fetch_object($ledPRE)){
                $totalvatpre=$vatPREROW->vatpre;  }
            echo number_format($totalvatpre,2);?></td>
    </tr>



    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px">Less: Supplementary Duty (SD)</td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $SDQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as SD  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4016') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($SDROW=mysql_fetch_object($SDQUARY)){
                $totalSD=$SDROW->SD;  }
            echo number_format($totalSD,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $SDPREQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as SDPRE  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4016') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($SDPREROW=mysql_fetch_object($SDPREQUARY)){
                $totalSDpre=$SDPREROW->SDPRE;  }
            echo number_format($totalSDpre,2);?></td>
    </tr>






    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px"><strong>Net Sales </strong></td>
        <td align="right" bgcolor="#FFFFFF"><strong><? $netSalesCurrent = $sales-($totalvat+$totalSD); echo number_format($netSalesCurrent,2); ?></strong></td>
        <td align="right" bgcolor="#FFFFFF"><strong><? $netSalesPrevious = $salespre-($totalvatpre+$totalSDpre); echo number_format($netSalesPrevious,2); ?></strong></td>
    </tr>







    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px"><a href="cost_center_wise_with_cogs.php?cost_center_id=18" target="_new">Cost of Goods Sales  (COGS)</a></td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $COGSQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as COGS  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4001','4018') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($COGSROW=mysql_fetch_object($COGSQUARY)){
                $totalcogs=$COGSROW->COGS;  }
            /////////////////////////// factory Cost
            $FactoryCQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as FactoryCEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   j.cc_code in ('18') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($FactoryCRow=mysql_fetch_object($FactoryCQUARY)){
                $FactoryCurrent=$FactoryCRow->FactoryCEXP+$totalcogs;  }
            echo number_format($FactoryCurrent,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $COGSPREQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as COGSPRE  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4001','4018') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($COGSPREROW=mysql_fetch_object($COGSPREQUARY)){
                $totalcogspre=$COGSPREROW->COGSPRE;  }


            /////////////////////////// factory Cost
            $FactoryCPREQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as FactoryCPREEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   j.cc_code in ('18') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($FactoryCPRERow=mysql_fetch_object($FactoryCPREQUARY)){
                $FactoryPrevious=$FactoryCPRERow->FactoryCPREEXP+$totalcogspre;  }
            echo number_format($FactoryPrevious,2);?></td>
    </tr>















    <tr style="background-color:#0099FF; color:#FFF; font-weight:bold">

        <td style="text-align:right; color:#FFF"><strong>Gross Profit/Loss</strong></td>

        <td style="text-align:right; color:#FFF"><? $grossSalesCurrent = ($netSalesCurrent-$FactoryCurrent);
            if($grossSalesCurrent>0){
                $grossSalesCurrents=number_format($grossSalesCurrent,2);
            } else {
                $grossSalesCurrents=	"(".number_format(substr($grossSalesCurrent,1),2).")";
            }
            echo $grossSalesCurrents;?></td>



        <td style="text-align:right; color:#FFF">
            <? $grossSalesPrevious = ($netSalesPrevious-$FactoryPrevious);
            if($grossSalesPrevious>0){
                $grossSalesPreviouss=number_format($grossSalesPrevious,2);
            } else {
                $grossSalesPreviouss=	"(".number_format(substr($grossSalesPrevious,1),2).")";
            }
            echo $grossSalesPreviouss;?>
            <? //$grossSalesPrevious = ($netSalesPrevious-$totalcogspre); echo number_format($grossSalesPrevious,2);?></td>
    </tr>


    <tr style="background:#0C9; font-weight:bold; color:#FFF; font-size:14px"><td colspan="3" style="color:#FFF">Operating Expenses</td>
    </tr>









    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px"><a href="pl_details.php?groupid=admin" target="_new">Administrative  Expenses</a></td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $adminEXPQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as adminEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   j.cc_code in ('19','20','23','35','36','37','38','17','39') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($adminEXPROW=mysql_fetch_object($adminEXPQUARY)){
                $adminExpCurrent=$adminEXPROW->adminEXP;  }
            echo number_format($adminExpCurrent,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $adminEXPPREQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as adminEXPPRE  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   j.cc_code in ('19','20','23','35','36','37','38','17','39') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($adminEXPPREROW=mysql_fetch_object($adminEXPPREQUARY)){
                $adminExpPrevious=$adminEXPPREROW->adminEXPPRE;  }
            echo number_format($adminExpPrevious,2);?></td>
    </tr>



    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px"><a href="cost_center_wise_with_21_34.php?cost_center_id=21&cost_center_id2=34" target="_new">Selling and Distribution Expenses</a></td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $SandDEQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as SandDEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   j.cc_code in ('21','34','40','41') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($SandDErow=mysql_fetch_object($SandDEQUARY)){
                $SandDErowCurrentAmounttotal=$SandDErow->SandDEXP;  }
            echo number_format($SandDErowCurrentAmounttotal,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $SandDEPREQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as SandDEXPPRE  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   j.cc_code in ('21','34','40','41') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($SandDEPRErow=mysql_fetch_object($SandDEPREQUARY)){
                $SandDErowCurrentAmounttotalPre=$SandDEPRErow->SandDEXPPRE;  }
            echo number_format($SandDErowCurrentAmounttotalPre,2);?></td>
    </tr>



    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px"><a href="cost_center_wise_details.php?cost_center_id=22" target="_new">Marketing Expenses</a></td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $marCurrentQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as marCurrentEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   j.cc_code in ('22') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($marCurrentRow=mysql_fetch_object($marCurrentQUARY)){
                $marketingExpCurrent=$marCurrentRow->marCurrentEXP;  }
            echo number_format($marketingExpCurrent,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $marCurrentPREQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as marPreviousEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   j.cc_code in ('22') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($marPreviousRow=mysql_fetch_object($marCurrentPREQUARY)){
                $marketingExpPrevious=$marPreviousRow->marPreviousEXP;  }
            echo number_format($marketingExpPrevious,2);?></td>
    </tr>





    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px">Sales Promotional Expenses</td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $SPECurrentQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as SPCurrentEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4013') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($SPECurrentROW=mysql_fetch_object($SPECurrentQUARY)){
                $totalspx=$SPECurrentROW->SPCurrentEXP;  }
            echo number_format($totalspx,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $SPEPreviousQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as SPPreviousEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4013') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($SPEPreviousROW=mysql_fetch_object($SPEPreviousQUARY)){
                $totalspxs=$SPEPreviousROW->SPPreviousEXP;  }
            echo number_format($totalspxs,2);?></td>
    </tr>





    <tr style="background-color:#0099FF; color:#FFF; font-weight:bold">

        <td style="text-align:right; color:#FFF"><strong>Operating Expenses </strong></td>
        <td style="text-align:right; color:#FFF"><strong><? $opertaingExpCurrent = ($adminExpCurrent+$SandDErowCurrentAmounttotal+$totalspx+$marketingExpCurrent); echo number_format($opertaingExpCurrent,2); ?></strong></td>
        <td style="text-align:right; color:#FFF"><strong><? $opertaingExpPrevious = ($adminExpPrevious+$SandDErowCurrentAmounttotalPre+$totalspxs+$marketingExpPrevious); echo number_format($opertaingExpPrevious,2); ?></strong></td>
    </tr>


    <tr style="background-color:#0099FF; color:#FFF; font-weight:bold">
        <td style="text-align:right; color:#FFF"><strong>Operating Profit </strong></td>
        <td style="text-align:right; color:#FFF"><strong><? $operatingProfitCurrent = ($grossSalesCurrent-$opertaingExpCurrent);
                if($operatingProfitCurrent>0){
                    $operatingProfitCurrents=number_format($operatingProfitCurrent,2);
                } else {
                    $operatingProfitCurrents='('.number_format(substr($operatingProfitCurrent,1),2).')';
                }
                echo $operatingProfitCurrents; ?></strong></td>
        <td style="text-align:right; color:#FFF"><strong><? $operatingProfitPrevious = ($grossSalesPrevious-$opertaingExpPrevious);

                if($operatingProfitPrevious>0){
                    $operatingProfitPreviouss=number_format($operatingProfitPrevious,2);
                } else {
                    $operatingProfitPreviouss='('.number_format(substr($operatingProfitPrevious,1),2).')';
                }
                echo $operatingProfitPreviouss; ?></strong></td></tr>

    <tr style="background:#0C9; font-weight:bold; color:#FFF; font-size:14px"><td colspan="3" style="color:#FFF">OTHER EXPENSES</td></tr>







    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px">Financial Expenses </td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $FXCurrentQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as FXCurrentEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4007') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($FXCurrentROW=mysql_fetch_object($FXCurrentQUARY)){
                $totalfinancialcost=$FXCurrentROW->FXCurrentEXP;  }
            echo number_format($totalfinancialcost,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $FXPreviousQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as FXPreviousEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4007') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($FXPreviousROW=mysql_fetch_object($FXPreviousQUARY)){
                $totalfinancialcostpre=$FXPreviousROW->FXPreviousEXP;  }
            echo number_format($totalfinancialcostpre,2);?></td>
    </tr>






    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px"> Extra Ordinary Loss </td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $EOLurrentQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as EOLCurrentEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4012') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($EOLCurrentROW=mysql_fetch_object($EOLurrentQUARY)){
                $totaleol=$EOLCurrentROW->EOLCurrentEXP;  }
            echo number_format($totaleol,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $EOLPreviousQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as FXPreviousEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4012') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($EOLPreviousROW=mysql_fetch_object($EOLPreviousQUARY)){
                $totaleolpre=$EOLPreviousROW->FXPreviousEXP;  }
            echo number_format($totaleolpre,2);?></td>
    </tr>




    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px">Non-Operating Expenses (Royalty) </td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $NOERCurrentQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as NOERCurrentEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4005') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($NOERCurrentROW=mysql_fetch_object($NOERCurrentQUARY)){
                $totalroyality=$NOERCurrentROW->NOERCurrentEXP;  }
            echo number_format($totalroyality,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $NOERPreviousQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as NOERPreviousEXP  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4005') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($NOERPreviousROW=mysql_fetch_object($NOERPreviousQUARY)){
                $totalroyalitypre=$NOERPreviousROW->NOERPreviousEXP;  }
            echo number_format($totalroyalitypre,2);?></td>
    </tr>






    <tr style="background-color:#0099FF; color:#FFF; font-weight:bold">

        <td bgcolor="#0099FF" style="text-align:right; color:#FFF; font-weight:bold"><strong>Total Other Expenses </strong></td>

        <td align="right" bgcolor="#0099FF" style="text-align:right; color:#FFF; font-weight:bold"><strong><? $otherExpCurrent = $totalfinancialcost+$totaleol+$totalembenifit+$totalroyality; echo number_format($otherExpCurrent,2); ?></strong></td>
        <td align="right" bgcolor="#0099FF" style="text-align:right; color:#FFF; font-weight:bold"><strong><? $otherExpPrevious = $totalfinancialcostpre+$totaleolpre+$totalembenifitpre+$totalroyalitypre; echo number_format($otherExpPrevious,2); ?></strong></td>
    </tr>


    <tr style="background-color:#0099FF; color:#FFF; font-weight:bold">
        <td bgcolor="#0099FF" style="text-align:right; color:#FFF; font-weight:bold"><strong>NET OPERATING PROFIT OVER EXPENSES </strong></td>

        <td align="right" bgcolor="#FF9999" style="background-color:#0099FF; color:#FFF; font-weight:bold"><strong><? $netOperProfitCurrent = ($operatingProfitCurrent-$otherExpCurrent);

                if($netOperProfitCurrent>0){
                    echo number_format($netOperProfitCurrent,2);  } else {echo '('.number_format(substr($netOperProfitCurrent,1),2).')'; }  ?></strong></td>


        <td align="right" bgcolor="#FF9999" style="background-color:#0099FF; color:#FFF; font-weight:bold"><strong><? $netOperProfitPrevious = ($operatingProfitPrevious-$otherExpPrevious);


                if($netOperProfitPrevious>0){
                    echo number_format($netOperProfitPrevious,2);  } else {echo '('.number_format(substr($netOperProfitPrevious,1),2).')'; }  ?></strong></td>
    </tr>













    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px">Other Income </td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $OICurrentQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.cr_amt-j.dr_amt)  as OICurrent  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('3001') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($OICurrentROW=mysql_fetch_object($OICurrentQUARY)){
                $totherincome=$OICurrentROW->OICurrent;  }
            echo number_format($totherincome,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $OIPreviousQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.cr_amt-j.dr_amt)  as OIPrevious  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('3001') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($OIPreviousROW=mysql_fetch_object($OIPreviousQUARY)){
                $totherincomepre=$OIPreviousROW->OIPrevious;  }
            echo number_format($totherincomepre,2);?></td>
    </tr>







    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px"><strong>Net Profit/(Loss) Before Tax </strong></td>
        <td align="right" bgcolor="#FFFFFF"><strong><? $pbtCurrent = $netOperProfitCurrent+$totherincome;
                if($pbtCurrent>0) { echo number_format($pbtCurrent,2); } else { echo '('.number_format(substr($pbtCurrent,1),2).')'; } ?></strong></td>
        <td align="right" bgcolor="#FFFFFF"><strong>

                <? $pbtPrevious = $netOperProfitPrevious+$totherincomepre;

                if($pbtPrevious>0) { echo number_format($pbtPrevious,2); } else { echo '('.number_format(substr($pbtPrevious,1),2).')'; }?>
            </strong></td>
    </tr>






    <tr>
        <td bgcolor="#FFFFFF" style="padding-left:20px">Provision for Income Tax </td>
        <td align="right" bgcolor="#FFFFFF">
            <?
            $ITAXCurrentQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as ITAXCurrent  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4009') and 
				   j.jvdate <= '$to_date' ".$sec_com_connection."");
            while($ITAXCurrentROW=mysql_fetch_object($ITAXCurrentQUARY)){
                $incomeTaxCurrent=$ITAXCurrentROW->ITAXCurrent;  }
            echo number_format($incomeTaxCurrent,2);?></td>


        <td align="right" bgcolor="#FFFFFF">
            <?
            $ITAXPreviousQUARY=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as ITAXPrevious  from 
				   accounts_ledger a,
				   journal j
				   WHERE  
				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('4009') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
            while($ITAXPreviousROW=mysql_fetch_object($ITAXPreviousQUARY)){
                $incomeTaxPrevious=$ITAXPreviousROW->ITAXPrevious;  }
            echo number_format($incomeTaxPrevious,2);?></td>
    </tr>



    <tr style="font-size:14px;">

        <td bgcolor="#0099FF" style="color:#FFF"><strong>Net Profit/(Loss) after tax</strong></td>

        <td align="right" bgcolor="#0099FF" style="color:#FFF"><strong><? $patCurrent = $pbtCurrent-$incomeTaxCurrent;
                if($patCurrent>0){
                    echo number_format($patCurrent,2); } else { echo '('.number_format(substr($patCurrent,1),2).')'; } ?></strong></td>


        <td align="right" bgcolor="#0099FF" style="color:#FFF"><strong><? //$patPrevious = $pbtPrevious-$incomeTaxPrevious; echo number_format($patPrevious,2); ?>

                <? $patPrevious = $pbtPrevious-$incomeTaxPrevious;
                if($patPrevious>0){
                    echo number_format($patPrevious,2); } else { echo '('.number_format(substr($patPrevious,1),2).')'; } ?></strong></td>
    </tr></table>



