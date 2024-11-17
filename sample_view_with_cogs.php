<?php
session_start();
//====================== EOF ===================
//var_dump($_SESSION);
require "../../support/inc.all.php";



$chalan_no = $_REQUEST['v_no'];
$datas = find_all_field('sale_do_chalan', 's', 'chalan_no=' . $chalan_no);

$sql1 = "select b.* from sale_do_chalan b where b.do_no = '" . $datas->do_no . "' order by id";

$marketing_person = find_a_field('sale_do_master', 'marketing_person', 'do_no=' . $datas->do_no);

$ref_no = find_a_field('sale_do_master', 'ref_no', 'do_no=' . $datas->do_no);

$do_status = find_a_field('sale_do_master', 'status', 'do_no=' . $datas->do_no);

$data1 = mysql_query($sql1);
$pi = 0;
$total = 0;

while ($infos = mysql_fetch_object($data1)) {
  $entry_time = $infos->entry_time;
  $pi++;
  $sqlc = 'select sum(total_unit) total_unit,driver_name,entry_by,driver_name_real,vehicle_no,delivery_man,chalan_date 
  from sale_do_chalan where chalan_no=' . $chalan_no . ' and order_no=' . $infos->id;
  $queryc = mysql_query($sqlc);
  $info = mysql_fetch_object($queryc);
  
  //$info=find_all_field('sale_do_chalan','s','chalan_no='.$chalan_no.' and order_no='.$infos->id);  $do_no = $infos->do_no;
  
   $net_unit_price[] = $net_rate = find_a_field('sale_do_details', 'net_rate', 'do_no="'.$infos->do_no.'" and item_id="'.$infos->item_id.'"');

  $dept = $infos->depot_id;
  $pack_size[] = $infos->undel;
  $item_id[] = $infos->item_id;
  $lot_no[] = $infos->lot_no;
  $cost_price[] = $infos->purchase_price;
  $cost_amount[] = $infos->cogs_amount;
  $sale_amount[] = $infos->total_amt;
  $dist_unit[] = $infos->dist_unit;
  $unit_price[] = $infos->unit_price;
  $pkt_size[] = $infos->pkt_size;
  $item = find_all_field('item_info', 'mhafuz', 'item_id=' . $infos->item_id);
  $item_name[] = $item->item_name;
  $unit_name[] = $item->unit_name;
  $sps[] = $item->sub_pack_size;
  $fgc[] = $item->finish_goods_code;
  $order_total_qty[] = $infos->total_unit;
  $sub_pkt_size[] = (($item->sub_pack_size > 1) ? $item->sub_pack_size : 1);

  //$pkt_unit[] = $info->pkt_unit;
  //$dist_unit[] = $info->dist_unit;
  //$pkt_unit[] = (int)$info->total_unit;
  //$dist_unit[] = (int)$info->total_unit;
  $total_unit[] = $info->total_unit;
  $order_no[] = $infos->id;
  $gift_on_order[] = $infos->gift_on_order;
  if ($info->total_unit > 0) {
    $chalan_date = $info->chalan_date;

    $store_sl = $info->driver_name;
    $entry_by = $info->entry_by;
    $driver_name_real = $info->driver_name_real;
    $vehicle_no = $info->vehicle_no;
    $delivery_man = $info->delivery_man;
  }
}
$ssql = 'select a.*,b.do_date from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no=' . $datas->do_no;
$dealer = find_all_field_sql($ssql);
$dept = 'select warehouse_name from warehouse where warehouse_id=' . $datas->depot_id;
$deptt = find_all_field_sql($dept);

$ssql = 'select b.* from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no=' . $datas->do_no;
$wdo = find_all_field_sql($ssql); 


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

  <title>.: Challan Copy :.</title>

  <link href="../css/invoice.css" type="text/css" rel="stylesheet" />

  <script type="text/javascript">
    function hide() {
      document.getElementById("pr").style.display = "none";
    }
  </script>

  <style type="text/css">
    <!--
    .style9 {
      font-size: 12px
    }
    -->
  </style>

</head>

<body style="font-family:Tahoma, Geneva, sans-serif">

  <table width="800" border="0" cellspacing="0" cellpadding="0" align="center">

    <tr>

      <td>
        <div class="header">

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">

                        <tr>

                          <td width="20%" align="left"><img src="../../../logo/aksidlogo.png" width="200px" height="100px" />

                          <td width="40%">&nbsp;</td>

                          <td align="left" width="40%" colspan="2"><span style="font-size:20px;"><strong>Aksid Corporation Limited</strong></span><br />House 4, 5th Floor, Road 12, Baridhara J-Block<span style="text-align:center; color:#000000; font-size:12px;"> 12 Pragati Ave, Dhaka 1212, Bangladesh</span><br /> Phone No: +88 0170 0761 420 <br />Email:khizir@aksidcorp.com</td>
                        </tr>
                      </table>
                    </td>

                  </tr>

                </table>
              </td>

            </tr>

            <tr>

              <td><br /><br />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">

                        <tr>

                          <td width="30%" align="left">&nbsp;</td>

                          <td width="40%" align="center" style="font-size:20px;"><strong>COGS</strong></td>

                          <td align="left" width="30%" colspan="2">&nbsp;</td>
                        </tr>
                      </table>
                    </td>

                  </tr>

                </table><br /><br />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td valign="top">
                      <table width="100%" border="0" cellspacing="2" cellpadding="1" style="font-size:13px">

                        <tr>

                          <td>Customer Name </td>

                          <td>:&nbsp;</td>

                          <td><?php echo $dealer->dealer_name_e . '- ' . $dealer->dealer_code; ?></td>
                        </tr>

                        <tr>

                          <td valign="top"> Address</td>

                          <td>:&nbsp;</td>

                          <td valign="top"><?php echo $dealer->address_e . '&nbsp;' . ' Mobile: ' . $dealer->mobile_no; ?>&nbsp;</td>
                        </tr>

                        <tr>

                          <td valign="top">Delivery Address </td>

                          <td>:&nbsp;</td>

                          <td><?php if ($wdo->new_delivery_address != '') {
                                echo $wdo->new_delivery_address;
                              } else {
                                echo $dealer->propritor_name_e;
                              } ?> </td>
                        </tr>
                        <tr>

                          <td valign="middle">Contact Person</td>

                          <td>:&nbsp;</td>

                          <td>&nbsp;<?php if ($wdo->new_contact_person != '') {
                                      echo $wdo->new_contact_person;
                                    } else {
                                      echo $dealer->propritor_name_b;
                                    } ?>, Mobile : <?php if ($wdo->new_contact_number != '') {
                                                      echo $wdo->new_contact_number;
                                                    } else {
                                                      echo $dealer->mobile_no;
                                                    } ?></td>
                        </tr>

                        <tr>

                          <td>DO Date </td>

                          <td>:&nbsp;</td>

                          <td><?php echo date('Y-m-d', strtotime($wdo->do_date)); ?>&nbsp;</td>
                        </tr>

                        <tr>

                          <td>Store </td>

                          <td>:&nbsp;</td>

                          <td> <?php echo $deptt->warehouse_name; ?>&nbsp;</td>
                        </tr>

                        <tr>

                          <td>Sales Person </td>

                          <td>:&nbsp;</td>

                          <td><?= find_a_field('personnel_basic_info', 'PBI_NAME', 'PBI_ID=' . $marketing_person) . ' - Mobile: ' . find_a_field('personnel_basic_info', 'PBI_MOBILE', 'PBI_ID=' . $marketing_person); ?>&nbsp;</td>
                        </tr>
                        <tr>

                          <td valign="top">Remarks </td>

                          <td>:&nbsp;</td>

                          <td><?php if ($wdo->remarks != '') {
                                echo $wdo->remarks;
                              } ?> </td>
                        </tr>

                        <tr>

                          <td valign="top">Vehicle No </td>

                          <td>:&nbsp;</td>

                          <td><?php echo $vehicle_no; ?></td>
                        </tr>
                      </table>
                    </td>

                    <td width="30%">
                      <table width="100%" border="0" cellspacing="0" cellpadding="1" style="font-size:13px">

                        <tr>

                          <td align="" valign="middle">Challan Date</td>

                          <td>:&nbsp;</td>

                          <td><?= $chalan_date ?></td>

                        </tr>

                        <tr>

                          <td valign="middle">Challan No</td>

                          <td>:&nbsp;</td>

                          <td><strong><?php echo $chalan_no; ?></strong></td>

                        </tr>

                        <tr>

                          <td valign="middle">DO No</td>

                          <td>:&nbsp;</td>

                          <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="1">

                              <tr>

                                <td><strong><?php echo $datas->do_no; ?></strong>&nbsp;</td>

                              </tr>

                            </table>
                          </td>

                        </tr>

                        <tr>

                          <td valign="middle">DO Status</td>

                          <td>:&nbsp;</td>

                          <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="1">

                              <tr>

                                <td><?php echo $datas->do_status; ?>&nbsp;</td>

                              </tr>

                            </table>
                          </td>

                        </tr>

                        <tr>

                          <td valign="middle">PO No</td>

                          <td>:&nbsp;</td>

                          <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="1">

                              <tr>

                                <td><?php echo $ref_no ?>&nbsp;</td>

                              </tr>

                            </table>
                          </td>

                        </tr>

                        <tr>

                          <td valign="middle">Store Serial No</td>

                          <td>:&nbsp;</td>
                          <td><?php echo $store_sl; ?>&nbsp;</td>
                        </tr>
                        <tr>

                          <td valign="middle">Driver Name </td>

                          <td>:&nbsp;</td>
                          <td><?php echo $driver_name_real; ?>&nbsp;</td>
                        </tr>
                        <tr>

                          <td valign="middle">Delivered By</td>

                          <td>:&nbsp;</td>
                          <td><?php echo $delivery_man; ?>&nbsp;</td>
                        </tr>

                      </table>
                    </td>

                  </tr>

                </table>
              </td>

            </tr>

          </table>

        </div>
      </td>

    </tr>

    <tr>

      <td></td>

    </tr>

    <tr>

      <td>
        <div id="pr">

          <div align="left">

            <input name="button" type="button" onclick="hide();window.print();" value="Print" />

            <nobr>

              <!--<a href="chalan_bill_view.php?v_no=<?= $_REQUEST['v_no'] ?>">Bill</a>&nbsp;&nbsp;-->

              <a href="chalan_bill_corporate.php?v_no=<?= $_REQUEST['v_no'] ?>">View Bill</a>&nbsp;&nbsp;

              <!--<a href="chalan_view_mis.php?v_no=<?= $_REQUEST['v_no'] ?>">MIS Copy</a>-->

            </nobr>
          </div>

        </div>

        <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="2" style="font-size:11px">

          <tr>

            <td align="center" bgcolor="#CCCCCC"><strong>SL</strong></td>

            <td align="center" bgcolor="#CCCCCC"><strong>Code</strong></td>

            <td align="center" bgcolor="#CCCCCC">
              <div align="center"><strong>Product Name</strong></div>
            </td>

            <td align="center" bgcolor="#CCCCCC"><strong>UOM</strong></td>

            <td align="center" bgcolor="#CCCCCC"><strong>Qty</strong></td>

            <td align="center" bgcolor="#CCCCCC"><strong>DO Rate </strong></td>

            <td align="center" bgcolor="#CCCCCC"><strong>Cost Rate </strong></td>
			
			 <td align="center" bgcolor="#CCCCCC"><strong> Net Margin </strong></td>

            <td align="center" bgcolor="#CCCCCC"><strong>DO Value </strong></td>

            <td align="center" bgcolor="#CCCCCC"><strong>Cogs Value </strong></td>

            <td align="center" bgcolor="#CCCCCC"><strong>DO No.</strong></td>

            <td align="center" bgcolor="#CCCCCC"><strong>Import/Purchase No.</strong></td>

          </tr>

          <? for ($i = 0; $i < $pi; $i++) {
            if ($fgc[$i] != 2000 and $fgc[$i] != 2001) {

              $total_do_value += $sale_amount[$i];

              $total_cogs_value += $cost_amount[$i];
			  
			  
if($net_unit_price[$i]>0){
$sales_price = $net_unit_price[$i];
}else{
$sales_price = $unit_price[$i];
}	

// Calculate Net Margin
$net_margin = (($sales_price - $cost_price[$i]) / $sales_price) * 100;

// Determine color based on the net margin
if ($net_margin < 5) {
    $color = "red";
} else {
    $color = "green";
}
			  
			 
		

          ?>

              <tr style="font-size:13px;">

                <td align="center" valign="top"><?= ++$kk ?></td>

                <td align="left" valign="top"><?= $fgc[$i]; ?></td>

                <td align="left" valign="top"><?= $item_name[$i]; ?>

                  <?= ($gift_on_order[$i] > 0) ? '<b>(Free)</b>' : ''; ?></td>

                <td align="left" valign="top"><?= $unit_name[$i]; ?></td>

                <td align="left" valign="top"><?= $dist_unit[$i] ?></td>

                <td align="left" valign="top"><?=$unit_price[$i]; ?></td>
				<td align="right" valign="top"><?= $cost_price[$i] ?></td>
				
				 <td align="left" valign="top"><?="<span style='color: $color;'>N.M: " . number_format($net_margin, 2) . "%</span>";?></td>

                <td align="right" valign="top"><?= $sale_amount[$i] ?></td>

                <td align="right" valign="top"><?= $cost_amount[$i] ?></td>

                <td align="right" valign="top"><?= $do_no ?></td>

                <td align="right" valign="top">
                  <a href="../other_receive/import_report.php?v_no=<?= $lot_no[$i] ?>"><?= $lot_no[$i] ?></a>
                </td>

              </tr>

          <? }
          } ?>

          <tr style="font-size:16px;">

            <td colspan="8" align="right" valign="top"><strong>Total:</strong></td>

            <td align="right" valign="top"><strong><?= number_format($total_do_value, 0) ?></strong></td>

            <td align="right" valign="top"><strong><?= number_format($total_cogs_value, 0) ?></strong></td>

            <td colspan="2" align="right" valign="top">&nbsp;</td>

          </tr>

        </table>
      </td>

    </tr>

    <tr>

      <td align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>

            <td colspan="2"><span class="style9">Chalan Print At:

                <?= date('Y-m-d h:s:i A'); ?>

              </span></td>

          </tr>

          <!--<tr>

		<?

    //$remarks= find_a_field('sale_do_master','remarks','do_no='.$do_no);

    //if($remarks!=""){
    ?>

		 

          <td width="50%"><b>NOTE</b> : 

		 <?

      //echo $remarks;

      // }
      ?>

		   </td>

          <td>&nbsp;</td>

        </tr>-->
          <tr>

            <td colspan="2" align="center"><strong><br /><br /><br /><br />

              </strong>

              <table width="100%" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="30%">
                    <div align="center" style="margin-bottom:-18px;font-size:13px;"><?= find_a_field('user_activity_management', 'fname', 'user_id=' . $entry_by); ?></div>
                  </td>

                  <td width="30%">
                    <div align="left"> &nbsp; </div>
                  </td>

                  <td width="30%">
                    <div align="left"> &nbsp;</div>
                  </td>
                </tr>

              </table>
            </td>

          </tr>

          <tr>

            <td colspan="2" align="center"><strong>

              </strong>

              <table width="100%" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="30%">
                    <div align="center">-------------------- </div>
                  </td>

                  <td width="30%">
                    <div align="center">-------------------- </div>
                  </td>

                  <td width="30%">
                    <div align="center">-------------------- </div>
                  </td>
                </tr>

              </table>
            </td>

          </tr>

          <tr>

            <td colspan="2" align="center"><strong>

              </strong>

              <table width="100%" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="30%">
                    <div align="center" style="margin-top:-10px;font-size:13px;">Prepared By </div>
                  </td>

                  <td width="30%">
                    <div align="center" style="margin-top:-10px;font-size:13px;">Checked By</div>
                  </td>

                  <td width="30%">
                    <div align="center" style="margin-top:-10px;font-size:13px;">Authorized By </div>
                  </td>
                </tr>

              </table>
            </td>

          </tr>
          <tr>

            <td><br /><br /><br /></td>

            <td>&nbsp;</td>

          </tr>

          <tr>

            <td colspan="2" style="font-size:13px">

              <em>All goods are received in a good condition as per Terms</em>
            </td>

          </tr>

          <tr>

            <td colspan="2" align="center"><strong><br /><br /><br />

              </strong>

              <table width="100%" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="30%">
                    <div align="center">-------------------------- </div>
                  </td>

                  <td width="30%">
                    <div align="left">&nbsp; </div>
                  </td>

                  <td width="30%">
                    <div align="left">&nbsp; </div>
                  </td>
                </tr>

              </table>
            </td>

          </tr>

          <tr>

            <td colspan="2" align="center"><strong>

              </strong>

              <table width="100%" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="30%">
                    <div align="center" style="margin-top:-18px;font-size:13px;">Customer Singnature </div>
                  </td>

                  <td width="30%">
                    <div align="left">&nbsp; </div>
                  </td>

                  <td width="30%">
                    <div align="left">&nbsp; </div>
                  </td>
                </tr>

              </table>
            </td>

          </tr>
          <tr>

            <td colspan="2" align="right"><br /><br /><br /><br /><br /><img src="../../../logo/Sikalogo.png" width="200px" height="100px" /></td>
          </tr>
        </table>

        <div class="footer1"> </div>
      </td>

    </tr>

  </table>

</body>

</html>