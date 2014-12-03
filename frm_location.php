<!doctype html>
<html lang="en">
<head>
	<?php require FRONTPATH.'/inc_header.php'; ?>
	<?php require FRONTPATH.'/inc_colorStyle.php'; ?>
	<?php 
		if(isset($_GET['req_no']))
		$rs = $db->record(DB_PREFIX.'requisition',"req_no = '".$_GET['req_no']."'");
		
		$oim = empty($rs->office_import) ? OFFICE_NO : $rs->office_import;
		
		$comp = $db->result(DB_PREFIX.'office',null,"office_sort asc, office_name asc",null);
				if($comp){
					foreach($comp as $cm){
						$selectbox .= '<option value="' . $cm->office_no . '" '.($cm->office_no == OFFICE_NO ? 'selected':'').'>'. $cm->office_name .'</option>'."\n";
					}
				}
				
	?>
	<style>
		.col-md-2 {
		    float: left;
		    width: 13.5%;
		    padding-right:0px;
		}
		.col-md-2 select{
		    width: 100%;
		}
		.form-inline .col-md-2 .title{
			display:block;
			font-size: 11px;
		}
		.textbox{
			height:30px !important;width:100%;
		}
	</style>
</head>
<body>
	<input type="hidden" id="currentpage" name="currentpage" value="frmLocation"/>
	<form class="form-inline">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-12 text-center">
						<h3>เพิ่มพื้นที่การจัดส่ง</h3>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<span class="title">จังหวัด</span>
						<select name="select-province" id="select-province">
							<?php $ball->option_province(); ?>
						</select>
					</div>
					<div class="col-md-2">
						<span class="title">เขต/อำเภอ</span>
						<select name="select-amphur" id="select-amphur">
							<option value="">-เลือกอำเภอ-</option>
						</select>
					</div>
					<div class="col-md-2" style="width:23%;">
						<span class="title">พื้นที่จัดส่ง</span>
						<input type="text" name="txt-location" id="txt-location" class="textbox"/>
						<input type="hidden" name="h-location" id="h-location" class="textbox"/>
					</div>
					<div class="col-md-2">
						<span class="title">ยานพาหนะ</span>
						<select name="vehicle" id="vehicle">
							<?php $ball->selectVehicle(); ?>
						</select>
					</div>
					<div class="col-md-2">
						<span class="title">ราคา</span>
						<input type="text" name="shipping_price" id="shipping_price" class="textbox">
					</div>
<!--					
					<div class="col-md-2">
						<span class="title">สาขา</span>
						<select name="select-office" id="select-office">
							<?php echo $selectbox; ?>
						</select>
					</div>
<!--
					<div class="col-md-2" style="float:none;width:auto;overflow:hidden;">
						<span class="title">&nbsp;</span>
						<button class="btn btn-success">+</button>
					</div>
-->
				</div>
				<div class="row" style="text-align:right; padding-right: 23px; margin-top: 10px;">
					<button class="BtnSave btn btn-default"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
					<button type="reset" class="BtnReset btn btn-default"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
				</div>
			</div>
		</div>
	</form>
  	<?php require FRONTPATH.'/inc_script.php'; ?>
</body>
</html>