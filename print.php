<!-- <link rel="stylesheet" href="module/stock_show/print.css" media="print"> -->
<style>
	.mainbox{
		height:auto;
	}
	.a4{
		width:720px;margin:0 auto;
	}
	.bill {
	    background-color:#fff;
	    max-width:320px;
	    padding-right:15px;
	    padding-left:15px;
    	font-family: 'Tahoma';
    	font-size: 12px;
    	/*font-weight: normal;*/
    	line-height: 16px;
	}
	.f10px{
		font-size: 10px !important;
	}
	body,html{
		height:auto;
		overflow:visible;
		/*font:normal 12px/14px 'FontA' !important;*/
	}
	@media print{
	    .mainbox {
	        max-width:100%;
	        margin:0 auto; 
	    } 
	    #header-logo{
	        display:none;
	    }
	    .bill{
	    	width:100%;max-width: none;
	    	font-weight: normal !important;
	    	font-size:18px; line-height: 22px;
	    	padding-right: 30px;
	    	padding-left: 30px;
	    }  
	}
	.paper-head{
		margin-bottom: 15px;
	}
	.text1{
		font-size: 14px;
		display:block;
		margin-bottom: 10px;
	}
	.centertext {
	    text-align:center;
	}
	.nowrap{
		white-space: nowrap;
	}
	.ta-r{
		text-align:right;
	}
	.underline:after {
	    display:block;
	    content:"";
	    padding-top:10px;
	    margin:0 15px 10px 15px;
	   border-bottom:1px dashed #000;
	}
	.logoimg{
		width:60%;
		margin:30px 0 10px 0;
	} 
	.col-xs-last{
		width:auto;
		overflow:hidden;
		float:none;
		position: relative;

	}
	.row-product{
		margin-bottom: 8px;
	}
	@page{
		
	}
</style>

<?php $rs = $db->record(DB_PREFIX.'formheader',"form_no = '".$_GET['id']."' AND form_type='sellbill'");
	  if($rs === FALSE){ alert('ไม่พบรายการขายนี้ กรุณาเลือกรายการใหม่'); redirect('report.html'); }
 ?>
<input type="hidden" id="currentpage" value="print"/>
<div class="container-fluid bill">
	<div class="row">
		<div class="col-xs-12 centertext">
			<img src="images/logo-01.png" alt="" class="logoimg">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 centertext">
			บริษัท บอลลูนอาร์ท จำกัด (สนง.ใหญ่)<br>
			289/2 ซอยพัฒนาการ20 ถนนพัฒนาการ <br>
			เขตสวนหลวง แขวงสวนหลวง กรุงเทพฯ 10250
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 centertext">
			<!-- บริษัท บอลลูนอาร์ท จำกัด (สำนักงานใหญ่)  -->
			<?php echo $ball->office($rs->ref_office_no); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 centertext">
			<!-- 289/2 ซอยพัฒนาการ20 ถนนพัฒนาการ แขวงสวนหลวง กรุงเทพฯ 10250 -->

			<?php //echo $ball->office_address($rs->ref_office_no); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 centertext">
			<nobr>เลขประจำตัวผู้เสียภาษี : 0135536000992</nobr>
			 <?php //echo 'เลขประจำตัวผู้เสียภาษี  '. $ball->offices($rs->ref_office_no)->office_vat; ; ?>
		</div>
	</div>
	<div class="row underline">
		<div class="col-xs-12 centertext">
			 <?php echo 'รหัสสาขา '. $ball->offices($rs->ref_office_no)->office_code  ?> <br>
			 <?php echo ' โทร. '. $ball->offices($rs->ref_office_no)->office_phone; ?>
			 <?php //echo ' โทร. '. $ball->offices($rs->ref_office_no)->office_phone .'  แฟกซ์. '. $ball->offices($rs->ref_office_no)->office_fax; ?>
		</div>
	</div>
	<!-- <div class="row underline">
		<div class="col-xs-12 centertext">
			สาขาที่ xxxxxxx โทร. 02-714-6690
		</div>
	</div> -->
	
	<div class="row">
		<div class="col-xs-12 centertext">
			ใบเสร็จรับเงิน/ใบกำกับภาษี
		</div>
	</div> 
	<div class="row">
		<div class="col-xs-12">เลขที่ <?php echo $ball->Billcode($rs->form_no) ; ?></div>
		<!-- <div class="col-xs-6 ta-r" id="timestr"> <?php echo slate_dateTime($rs->form_date); ?></div> -->
	</div>
	<div class="row">
		<div class="col-xs-12"> <?php echo slate_dateTime($rs->form_date); ?> </div>
	</div>
	<div class="row">
		<div class="col-xs-12">ชื่อ <?php echo $ball->customer($rs->ref_customer_no)->customer_name; ?></div>
	</div>
	<div class="row">
		<div class="col-xs-12">ที่อยู่ <?php echo $ball->customer($rs->ref_customer_no)->customer_address; ?></div>
	</div>	
	<div class="row">
		<div class="col-xs-12">โทร. <?php echo $ball->customer($rs->ref_customer_no)->customer_tel; ?></div>
	</div>
	<div class="row">
		<div class="col-xs-12">เลขประจำตัวผู้เสียภาษี <?php echo $ball->customer($rs->ref_customer_no)->customer_vat != '' ? $ball->customer($rs->ref_customer_no)->customer_vat : '00'; ?></div>
	</div>
	<div class="row underline">
		<div class="col-xs-12">สถานประกอบการ สาขาที่ <?php echo $ball->customer($rs->ref_customer_no)->customer_vat != '' ? $ball->customer($rs->ref_customer_no)->customer_vat : '00'; ?></div>
	</div>

	<?php 
		$row = $db->result(DB_PREFIX.'formdetail',"ref_form_no = '".$_GET['id']."'", "materials_id asc");
		//printArray($row);
		if($row){
			$no = 0;
			$total = 0;
			
			foreach($row as $r){
				$class = '';
				$price = $r->formdetial_price;
				if ($r === end($row))
        			$class = 'underline';
				echo '

				<div class="row row-product '. $class .'">
					<div class="col-xs-7" style="padding-right:0px;">'. (($no++)+1) .'. '. $ball->itemName($r->materials_id).' <br>
						'. $r->materials_id .'
					</div>
					<div class="col-xs-1 nowrap">'. $r->formdetail_unit .'</div>
					<div class="col-xs-4 col-xs-last ta-r">'. nb2($price) .'</div>
				</div>
				';
				$total += $price;

			}
			
			$bp			= $total * 7 /100;
			$rat 		= $total - $bp;
			//echo $rs->form_shipping;

			$dlv		= $db->record(DB_PREFIX.'delivery',"ref_province_no = '". $rs->form_province ."' AND ref_district_no = '". $rs->form_amphur ."'");
			$shipping	= $rs->form_shipping == 'Y' ? $rs->form_shipping_price : 0;//($dlv !== FALSE ? $dlv->delivery_price:0):0;
			$bship 		= $rs->form_shipping == 'Y' ? $shipping * 7/100 : 0;
			$discount   = $rs->form_discount;
			$net		= $rs->form_shipping == 'Y' ? $total + $rs->form_shipping_price:$total;//($dlv !== FALSE ? $total + $dlv->delivery_price : $total):$total;
			$sum 		= $rat + ($shipping-$bship);
			$vat 		= $net*7/100;
		}
		
	?>

	<div class="row">
		<div class="col-xs-12">รวม <?php echo $no ;?> รายการ</div>
	</div>
	<div class="row">
		<div class="col-xs-7">มูลค่าที่เสียภาษี(v)</div>
		<div class="col-xs-5 ta-r"> <?php echo nb2($rat); ?> </div>
	</div>
	
	<div class="row">
		<div class="col-xs-7">ค่าบริการจัดส่ง/ติดตั้ง</div>
		<div class="col-xs-5 ta-r"> <?php echo $dlv !== FALSE ? nb2($shipping-$bship) : 0 ; ?> </div>
	</div>
	<div class="row">
		<div class="col-xs-7">ราคารวม</div>
		<div class="col-xs-5 ta-r"> <?php echo nb2($sum); ?> </div>
	</div>	
	<div class="row">
		<div class="col-xs-7">ส่วนลด</div>
		<div class="col-xs-5 ta-r"> <?php echo nb2($discount); ?> </div>
	</div>
	<div class="row">
		<div class="col-xs-12"><?php echo $rs->form_promotion; ?></div>
	</div>
	<div class="row underline">
		<div class="col-xs-7">ภาษีมูลค่าเพิ่ม</div>
		<div class="col-xs-5 ta-r"><?php echo nb2($vat); ?></div>
	</div>
	<div class="row underline">
		<div class="col-xs-7">
			<nobr>จำนวนเงินที่ต้องชำระรวม</nobr>
		</div>
		<div class="col-xs-5 ta-r">
			<?php echo nb2($net-$discount); ?>
		</div>
	</div>
	<div class="row underline">
		<div class="col-xs-12 centertext">ขอบคุณทุกท่านที่อุดหนุนบอลลูนอาร์ทค่ะ<br>
			ผู้ขาย <?php echo $ball->admins($rs->ref_admin_id)->admin_name . ' ' . $ball->admins($rs->ref_admin_id)->admin_surname; ?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12">&nbsp;</div>
	</div>
	
</div>