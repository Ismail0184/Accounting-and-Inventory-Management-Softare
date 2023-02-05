<?php
require_once 'support_file.php';
require_once 'class.numbertoword.php';

$company=mysqli_query($conn, "SELECT * FROM company WHERE  section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
$company_master=mysqli_fetch_object($company);

$chalan_no 		= $_REQUEST['do_no'];
$challan= find_all_field('sale_do_master','','do_no='.$_GET[do_no]);
foreach($challan as $key=>$value){
    $$key=$value;
}

$ssql = 'select a.*,b.do_date from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no='.$_GET[do_no];
$dealer = find_all_field_sql($ssql);
?>
<script type="text/javascript">
    function hide()
    {    document.getElementById("pr").style.display="none";
    }
</script>
<style>
    #invoice{
        padding: 30px;
    }

    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
        padding: 15px
    }

    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #3989c6
    }

    .invoice .company-details {
        text-align: right
    }

    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .contacts {
        margin-bottom: 20px
    }

    .invoice .invoice-to {
        text-align: left
    }

    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .invoice-details {
        text-align: right
    }

    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #3989c6
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice main .thanks {
        margin-top: -100px;
        font-size: 2em;
        margin-bottom: 50px
    }

    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }

    .invoice main .notices .notice {
        font-size: 1.2em
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }

    .invoice table td,.invoice table th {
        padding: 15px;
        background: #eee;
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        white-space: nowrap;
        font-weight: 400;
        font-size: 16px
    }

    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        color: #3989c6;
        font-size: 1.2em
    }

    .invoice table .qty,.invoice table .total,.invoice table .unit {
        text-align: right;
        font-size: 1.2em
    }

    .invoice table .no {
        color: #fff;
        font-size: 1.6em;
        background: #3989c6
    }

    .invoice table .unit {
        background: #ddd
    }

    .invoice table .total {
        background: #3989c6;
        color: #fff
    }

    .invoice table tbody tr:last-child td {
        border: none
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.2em;
        border-top: 1px solid #aaa
    }

    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    .invoice table tfoot tr:last-child td {
        color: #3989c6;
        font-size: 1.4em;
        border-top: 1px solid #3989c6
    }

    .invoice table tfoot tr td:first-child {
        border: none
    }

    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
    }

    @media print {
        .invoice {
            font-size: 11px!important;
            overflow: hidden!important
        }

        .invoice footer {
            position: absolute;
            bottom: 10px;
            page-break-after: always
        }

        .invoice>div:last-child {
            page-break-before: always
        }
    }
</style>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<div id="invoice">
    <div id="pr">
    <div class="toolbar hidden-print">
        <div class="text-right">
            <p><button id="printInvoice" type="button" onclick="hide();window.print();" class="btn btn-info"><i class="fa fa-print"></i> Print</button></p>
        </div>
        <hr>
    </div></div>


    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col" style="">
                        <a target="_blank" href="https://lobianijs.com">
                            <img src="../../assets/images/icon/title.png" width="60%" data-holder-rendered="true" />
                        </a>
                    </div>
                    <div class="col company-details" style="">
                        <h3 class="name"><?=$_SESSION['company_name'];?></a></h3>
                        <div><?=$_SESSION['company_address'];?></div>
                        <div><?=$company_master->telephone;?></div>
                        <div><?=$company_master->website;?></div>
                    </div>
                </div>

            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light">INVOICE TO:</div>
                        <h2 class="to"><?php echo $dealer->dealer_name_e;?></h2>
                        <div class="address"><?php echo $dealer->address_e;?>.</div>
                        <div class="email">POC : <?php echo $dealer->contact_person.','.' Mobile: '.$dealer->contact_number;?>, Eamil : <?=$dealer->email?></div>
                    </div>
                    <div class="col invoice-details">
                        <h3 class="invoice-id">INVOICE</h3>
                        <div class="date">Date of Invoice: <?php echo date('d/M/Y',strtotime($do_date));?></div>
                        <div class="date">Invoice No: <?=$do_no;?></div>
                        <div class="date">OTT Name: <?=find_a_field('vendor','vendor_shortname','ledger_id='.$pc_code.'')?></div>
                        <div class="date">Remarks: <?=$remarks?></div>
                    </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th rowspan="2">#</th>
                        <th class="text-left">PACKAGES DESCRIPTION</th>
                        <th class="text-center">UNIT</th>
                        <th class="text-center">UNIT PRICE</th>
                        <th class="text-center">NO. OF PACKAGE</th>
                        <th class="text-center">TOTAL AMOUNT</th>
                    </tr>
                    </thead>
                    <tbody>

                    <? $sqlc = mysqli_query($conn, 'select c.*, i.item_name, i.unit_name, c.total_unit,c.total_amt from sale_do_details c, item_info i where i.item_id=c.item_id and i.finish_goods_code != 2001 and c.do_no='.$_GET[do_no].' group by c.item_id order by c.id asc');
                    while($datac = mysqli_fetch_object($sqlc)){
                    $details= find_all_field('sale_do_details','','id='.$datac->order_no);
                    ?>
                    <tr>
                        <td class="no"><?=++$kk;?></td>
                        <td class="text-left"><h3><?=$datac->item_name;?></h3></td>
                        <td class="unit" style="text-align: center"><?=$datac->unit_name;?></td>
                        <td class="unit" style="text-align: center"><?=$datac->unit_price;?></td>
                        <td class="unit" style="text-align: center"><?=$datac->total_unit;?></td>
                        <td class="total"><?=number_format($datac->total_amt,2);?></td>
                    </tr>
                    <?
                    $total_amount=$total_amount+$datac->total_amt;
                    }

                    ?>


                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">TOTAL</td>
                        <td>BDT <?=number_format($total_amount,2);?></td>
                    </tr>
                    <!--tr>
                        <td colspan="3"></td>
                        <td colspan="2">DISCOUNT</td>
                        <td>BDT 0.0</td>
                    </tr-->
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">Added: Tax</td>
                        <td>BDT <?=number_format($tax=(($total_amount*10/9)*.10),2);?></td>
                    </tr>
                    <!--tr>
                        <td colspan="3"></td>
                        <td colspan="2">SUBTOTAL</td>
                        <td>BDT <?=number_format($total_amount+$tax,2);?></td>
                    </tr-->
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">Added: VAT (15%)</td>
                        <td>BDT <?=number_format(($vat=($total_amount+$tax)*0.15),2)?></td>
                    </tr>
                    
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">GRAND TOTAL</td>
                        <td>BDT <?=number_format($tt=$total_amount+$vat+$tax,2);?></td>
                    </tr>
                    </tfoot>
                </table>
                <p style="float: right" colspan="3">Amount in word: (<? echo convertNumberToWordsForIndia(round($tt))?>)</p>

                <div class="thanks">Thank you!</div>
                <div class="notices">
                    <div>TERMS & CONDITIONS:</div>
                    <div class="notice"><strong>Payment:</strong> 7 days after invoice submission.</div>
                    <div class="notice"><strong>Payment Mode:</strong> Account payee check in favor of "<strong>LBC MEDIA ENTERTAINMENT COMPANY LTD.</strong>"</div>
                    <div class="notice"><strong>Bank Details:</strong> NRB Bank Ltd. A/C No.- 1012010144781, Branch- Principal Branch.</div>
                    <div class="notice"><strong>VAT & Tax:</strong> VAT and Tax are Exclusive.</div>
                </div>
            </main>
            <footer>
                Invoice was created on a computer and is valid without the signature and seal.
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>
<script>
    $('#printInvoice').click(function(){
        Popup($('.invoice')[0].outerHTML);
        function Popup(data)
        {
            window.print();
            return true;
        }
    });
</script>