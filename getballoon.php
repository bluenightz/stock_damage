<?php 
	$r = $db->record(DB_PREFIX.'item'," item_code = '".$_GET['code']."'");
	$m = $db->record(DB_PREFIX.'materials',"materials_id = '".$_GET['code']."' OR materials_barcode = '". $_GET['code']."'");
	if($r === FALSE && $m === FALSE): echo 'false'; exit; endif;
	$code = strtoupper($_GET['code']);
//	if($row){
//		foreach($row as $r){
if($r !== FALSE && $m === FALSE ){
			$code1 		= substr($code,0,1);
			$code2 		= substr($code,1,1);
			$mainFolder	= $ball->itemFolder($code1,'group',0);
			$subFolder 	= $ball->itemFolder($code2,'subgroup',$mainFolder);
			$svgfolder 	= 'images/Balloons/'. $ball->FolderName($mainFolder).'/'.$ball->FolderName($subFolder).'/';
			$img 	= $ball->svgtopng($svgfolder . $code);
			
			$itemNo = $ball->item_no($code);
?>
<tr class="balloonitem"> <!-- start item -->
	<td class="leftcolumn">
		<table class="basic-barcodetable">
			<tr class="rowhead">
				<td>รายการสินค้า</td>
			</tr>
			<tr>
				<td>
					<div class="img">
						<strong class="title"><span class="runnumber">1</span>. <?php echo $code; ?> </strong>
						<img src="<?php echo $img; ?>" alt="">
						<strong class="edit">แก้ไขแบบ</strong>
						<a href="" class="btn-trash clickable"></a>
					</div>
				</td>
			</tr>
			<tr style="display:none;">
				<td>
					<div class="balloonprice">
						<div class="balloonprice-title">ราคาบอลลูน : </div><div class="balloonprice-price">&nbsp;</div>
					</div>
				</td>
			</tr>
		</table>
	</td>
	<td>
		<table class="basic-barcodetable matlist" >
			<tr class="rowhead">
				<td class="col1">ลำดับ</td>
				<td class="col2">ภาพ</td>
				<td class="col3">รหัส</td>
				<td class="col4">แก๊ส</td>
				<td class="col5">ราคา</td>
				<td class="col5">จำนวน</td>
				<td class="col5">มูลค่าเสียหาย</td>
				<td class="" colspan='2'>จำนวนเสียหาย</td>
				<td class="spacetd">&nbsp;</td>
				<td class="col10">สต๊อค</td>
				<td class="spacetd lasttd">&nbsp;</td>
			</tr>
			<?php 
			$mat = $db->result(DB_PREFIX.'itemlist',"ref_item_no = '".$itemNo ."'","ref_materials_id asc");
			if($mat){
				$i= 0;
				foreach($mat as $m){
			?>
			<tr class="matitem">
				<td class="col1 matnumber"><?php echo ++$i; ?></td>
				<td class="col2">
					<div class="tmbnmat"><img src="images/Materials/<?php echo $ball->materials_img($m->ref_materials_id); ?>" alt=""></div>
				</td>
				<td class="col3">
					<div class="codewrap"><?php echo '<span>' . $m->ref_materials_id .' </span><br> 
						'. $m->list_name ; ?>
						<div class="matTH">ชื่อวัสดุ</div>
					</div>
				</td>
				<td class="col5"><span class="gas"><select class="clickable chkgas"><option value="Y" <?php echo $m->list_gas == 'Y' ? 'selected':''; ?> rel="<?php echo $ball->materialsPrice($m->ref_materials_id);?>">Yes</option><option value="N"  <?php echo $m->list_gas == 'N' ? 'selected':''; ?> rel="<?php echo $ball->materials($m->ref_materials_id)->materials_price;?>">No</option></select></span></td>
				<td class="col4"><span class="price"><?php echo $ball->unitPrice($m->ref_materials_id,$m->list_no); ?></span></td>
				<td class="col5"><span class="quantity"><?php echo $m->materials_num; ?></span></td>
				
				<td class="col5"><span class="matallprice"><?php echo ( $m->materials_num * $ball->materialsPrice($m->ref_materials_id) ) ?></span></td>
				<td class="col6"><div class="btnBC clickable btnBC-up"></div></td>
				<td class="col7"><div class="btnBC clickable btnBC-down"></div></td>
				<!-- <td class="col8"><div class="btnBC clickable btnBC-delete"></div></td> -->
				<td class="spacetd">&nbsp;</td>
				<td class="col10"><span class="instock"><?php echo $ball->office_balance($m->ref_materials_id, $_SESSION['LOGIN']['OFFICE']) - $m->materials_num; ?></span></td>
				<td class="spacetd lasttd">&nbsp;</td>
			</tr>
			<?php 
				}
			}
			?>
			<!--
			<tr class="matitem">
				<td class="col1 matnumber">2</td>
				<td class="col2">
					<div class="tmbnmat"><img src="http://www.balloonoffice.com/images/Materials/1407122160.jpg" alt=""></div>
				</td>
				<td class="col3">
					<div class="codewrap">AFA24477000 <br> 
						Happy Birthday Striped Cupcake</div>
				</td>
				<td class="col4"><span class="price">100</span></td>
				<td class="col5"><span class="quantity">5</span></td>
				<td class="col6"><div class="btnBC clickable btnBC-up"></div></td>
				<td class="col7"><div class="btnBC clickable btnBC-down"></div></td>
				<td class="col8"><div class="btnBC clickable btnBC-delete"></div></td>
				<td class="spacetd">&nbsp;</td>
				<td class="col10"><span class="instock">50</span></td>
				<td class="spacetd lasttd">&nbsp;</td>
			</tr>
			<tr class="matitem">
				<td class="col1 matnumber">3</td>
				<td class="col2">
					<div class="tmbnmat"><img src="http://www.balloonoffice.com/images/Materials/1407122160.jpg" alt=""></div>
				</td>
				<td class="col3">
					<div class="codewrap">AFA24477000 <br> 
						Happy Birthday Striped Cupcake</div>
				</td>
				<td class="col4"><span class="price">150</span></td>
				<td class="col5"><span class="quantity">6</span></td>
				<td class="col6"><div class="btnBC clickable btnBC-up"></div></td>
				<td class="col7"><div class="btnBC clickable btnBC-down"></div></td>
				<td class="col8"><div class="btnBC clickable btnBC-delete"></div></td>
				<td class="spacetd">&nbsp;</td>
				<td class="col10"><span class="instock">20</span></td>
				<td class="spacetd lasttd">&nbsp;</td>
			</tr>
			<tr class="matitem">
				<td class="col1 matnumber">4</td>
				<td class="col2">
					<div class="tmbnmat"><img src="http://www.balloonoffice.com/images/Materials/1407122160.jpg" alt=""></div>
				</td>
				<td class="col3">
					<div class="codewrap">AFA24477000 <br> 
						Happy Birthday Striped Cupcake</div>
				</td>
				<td class="col4"><span class="price">900</span></td>
				<td class="col5"><span class="quantity">2</span></td>
				<td class="col6"><div class="btnBC clickable btnBC-up"></div></td>
				<td class="col7"><div class="btnBC clickable btnBC-down"></div></td>
				<td class="col8"><div class="btnBC clickable btnBC-delete"></div></td>
				<td class="spacetd">&nbsp;</td>
				<td class="col10"><span class="instock">30</span></td>
				<td class="spacetd lasttd">&nbsp;</td>
			</tr>
			-->
		</table>
	</td>
	<td class="rightcolumn">
		<table class="basic-barcodetable">
			<tr class="rowhead">
				<td>จำนวนสินค้า</td>
			</tr>
			<tr>
				<td>
					<div class="quantity">
						1
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="btnBC clickable btnBC-up">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="btnBC clickable btnBC-down">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="btnBC clickable btnBC-plus">
					</div>
				</td>
			</tr>
			<tr class="rowhead">
				<td>ราคาต่อชุด</td>
			</tr>
			<tr>
				<td class="price-1">
					<div>xxx</div>
				</td>
			</tr>
		</table>
	</td>
</tr> <!-- end item -->
<!-- space between item -->
<tr> 
	<td colspan="3">&nbsp;</td>
</tr><!-- space between item -->
<?php 
	} 
	//--------------:: MATERIALS DATA ::----------------------------//
	if($m !== FALSE && $r === FALSE){
?>

<tr class="balloonitem"> <!-- start item -->
	<td class="leftcolumn">
		<table class="basic-barcodetable">
			<tr class="rowhead">
				<td>รายการสินค้า</td>
			</tr>
			<tr>
				<td>
					<div class="img">
						<strong class="title"><span class="runnumber">1</span>. <?php echo $m->materials_id; ?> </strong>
						<img src="<?php echo 'images/Materials/'.$m->materials_image; ?>" alt="">
						<strong class="edit">แก้ไขแบบ</strong>
						<a href="" class="btn-trash clickable"></a>
					</div>
				</td>
			</tr>
			<tr style="display:none;">
				<td>
					<div class="balloonprice">
						<div class="balloonprice-title">ราคาบอลลูน : </div><div class="balloonprice-price">&nbsp;</div>
					</div>
				</td>
			</tr>
		</table>
	</td>
	<td>
		<table class="basic-barcodetable matlist" >
			<tr class="rowhead">
				<td class="col1">ลำดับ</td>
				<td class="col2">ภาพ</td>
				<td class="col3">รหัส</td>
				<td class="col4">แก๊ส</td>
				<td class="col5">จำนวน</td>
				<td class="col5">ราคา</td>
				<td class="col5">มูลค่าเสียหาย</td>
				<td class="" colspan='2'>จำนวนเสียหาย</td>
				<td class="spacetd">&nbsp;</td>
				<td class="col10">สต๊อค</td>
				<td class="spacetd lasttd">&nbsp;</td>
			</tr>
			<tr class="matitem">
				<td class="col1 matnumber">1</td>
				<td class="col2">
					<div class="tmbnmat"><img src="images/Materials/<?php echo $m->materials_image; ?>" alt=""></div>
				</td>
				<td class="col3">
					<div class="codewrap"><?php echo '<span>' . $m->materials_id .' </span><br> 
						'. $m->materials_detail ; ?>
						<div class="matTH">ชื่อวัสดุ</div>
					</div>
				</td>
				<td class="col5"><span class="gas"><select class="clickable chkgas"><option value="Y" selected rel="<?php echo $ball->materialsPrice($m->materials_id);?>">Yes</option><option value="N"   rel="<?php echo $ball->materials($m->materials_id)->materials_price;?>">No</option></select></span></td>
				<td class="col4"><span class="price"><?php echo $ball->materialsPrice($m->materials_id); ?></span></td>
				<td class="col5"><span class="quantity">1</span></td>
				<td class="col5"><span class="matallprice"><?php echo ( $m->materials_num * $ball->materialsPrice($m->materials_id) ) ?></span></td>
				<td class="col6"><div class="btnBC clickable btnBC-up"></div></td>
				<td class="col7"><div class="btnBC clickable btnBC-down"></div></td>
				<!-- <td class="col8"><div class="btnBC clickable btnBC-delete"></div></td> -->
				<td class="spacetd">&nbsp;</td>
				<td class="col10"><span class="instock"><?php echo $ball->office_balance($m->materials_id, $_SESSION['LOGIN']['OFFICE']) - 1; ?></span></td>
				<td class="spacetd lasttd">&nbsp;</td>
			</tr>
		</table>
	</td>
	<td class="rightcolumn">
		<table class="basic-barcodetable">
			<tr class="rowhead">
				<td>จำนวนสินค้า</td>
			</tr>
			<tr>
				<td>
					<div class="quantity">
						1
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="btnBC clickable btnBC-up">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="btnBC clickable btnBC-down">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="btnBC clickable btnBC-plus">
					</div>
				</td>
			</tr>
			<tr class="rowhead">
				<td>ราคาต่อชุด</td>
			</tr>
			<tr>
				<td class="price-1">
					<div>xxx</div>
				</td>
			</tr>
		</table>
	</td>
</tr> <!-- end item -->
<!-- space between item -->
<tr> 
	<td colspan="3">&nbsp;</td>
</tr><!-- space between item -->
<?php } ?>