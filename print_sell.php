<!-- <link rel="stylesheet" href="module/stock_show/print.css" media="print"> -->
<?php chk_online('login.html'); ?>
<?php $rs = $db->record(DB_PREFIX.'formheader',"form_no = '".$_GET['id']."' AND form_type='sellbill'");
	  if($rs === FALSE){ alert('ไม่พบรายการขายนี้ กรุณาเลือกรายการใหม่'); redirect('report.html'); }
 ?>
<input type="hidden" id="currentpage" value="print"/>
<div class="container print-detail" style="background:#fff;">
		<?php require dirname(__FILE__).'/head_form.php'; ?>
		<?php 
			$frmd = $db->result(DB_PREFIX.'formdetail',"ref_form_no = '".$_GET['id'] ."'","materials_id asc");
			if($frmd){
				$dno = 1;
			foreach($frmd as $frm){
				//$dprice = $db->sum(DB_PREFIX.'sell',"ref_formdetail_no ='". $frm->formdetail_no ."'",
				$uprice = $frm->formdetial_price / $frm->formdetail_unit;
					echo '
						<tr>
							<td ><div class="text-center"><b>'. $dno++ .'</b></div></td>
							<td><b>'. $frm->materials_id .'</b></td>
							<td><b>'. $ball->itemName($frm->materials_id) .'</b></td>
							<td><div class="text-center"><b>'. $frm->formdetail_unit .'</b></div></td>
							<td><div class="text-center"><b>'. nb2($uprice) .'</b></div></td>
							<td><div class="text-center"><b>'. nb2($frm->formdetial_price) .'</b></div></td>
						</tr>
					';
			$row = $db->result(DB_PREFIX.'sell',"ref_form_no = '".$_GET['id']."' AND ref_formdetail_no ='". $frm->formdetail_no ."'", "ref_materials_id asc");
			if($row){
				$rno += $dno;
				
				$no = 1;
				foreach($row as $r){
					$sum = $ball->materialsPrice($r->ref_materials_id) * $r->sell_unit;
					$amount += $sum;
					echo '
						<tr>
							<td ><div class="text-right" style="padding-left:5px;">'. $no++ .'</div></td>
							<td><div style="padding-left:5px;"><img src="images/last.png"> '. $r->ref_materials_id .'</td>
							<td><div style="padding-left:5px;"><img src="images/last.png"> '. $ball->materials_name($r->ref_materials_id) .'</div></td>
							<td><div class="text-right">'. $r->sell_unit .'</div></td>
							<td><div class="text-right">'. nb2($ball->materialsPrice($r->ref_materials_id)) .'</div></td>
							<td><div class="text-right">'. nb2($sum) .'</div></td>
						</tr>
					';
					if($rno%31 == 0){
						echo '</table>';//require dirname(__FILE__).'/footer_form.php';
						require dirname(__FILE__).'/head_form.php';
					}
				}
				$vat = $amount * 7 / 100;
				$rate = $amount - $vat;
			}
			}}
		?>		<tr>
			<td rowspan="4"><strong>หมายเหตุ</strong></td>
			<td colspan="2" rowspan="4"><?php echo nl2br($rs->form_shipp_detail); ?></td>
			<td colspan="2"><div class="text-right">รวมราคาสินค้า/SUB TOTAL</div></td>
			<td><div class="text-right"><strong><?php echo nb2($rate); ?></strong></div></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="text-right">หัก : ส่วนลด/LESS:DEPOSIT</div>
				<div class="text-right"><?php echo $rs->form_promotion; ?></div>
			</td>
			<td><div class="text-right"><strong><?php echo $rs->form_discount; ?></strong></div></td>
		</tr>
		<tr>
			<td colspan="2"><div class="text-right">รวมราคาสุทธิ/NET TOTAL</div></td>
			<td><div class="text-right"><strong>0.00</strong></div></td>
		</tr>
		<tr>
			<td colspan="2"><div class="text-right">ภาษีมูลค่าเพิ่ม/VAT</div></td>
			<td><div class="text-right"><strong><?php echo nb2($vat); ?></strong></div></td>
		</tr>
		<tr>
			<td colspan="3">ตัวอักษร : <strong><?php echo num2thai($amount); ?></strong></td>
			<td colspan="2"><div class="text-right">จำนวนเงินรวมทั้งสิ้น/GRAND TOTAL</div></td>
			<td><div class="text-right"><strong><?php echo nb2($amount); ?></strong></div></td>
		</tr>
		<?php require dirname(__FILE__).'/footer_form.php'; ?>
	<div class="row">
		<div class="col-md-8 col-xs-8"></div>
		<div class="col-md-4 col-xs-4 text-center">
			<p></p>
			<p></p>
			<p><strong><?php echo $ball->admins($rs->ref_admin_id)->admin_name .' '. $ball->admins($rs->ref_admin_id)->admin_surname; ?></strong></p>
			<p>ผู้รับเงิน</p>
			
		</div>
	</div>
</div>
