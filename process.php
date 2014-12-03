<?php 
	if( $_GET['Mode'] == 'promotion' ){
		//$price = $ball->promotion($_POST['price']);
		$tbl 	= DB_PREFIX.'promotions';
		$where 	=  "promotion_price <= '". $_POST['price'] ."' AND promotion_start <= '". time() ."' AND promotion_end >= '". time() ."' ";
		//echo '<pre>'. $where .'</pre>';
		$pr 	= $db->result($tbl,$where,"promotion_price desc","0,1");
		if($pr){
			foreach($pr as $p){
				$disc 	= $_POST['price'] * $p->promotion_discount / 100;
				$promotion_name = $p->promotion_name;
				$promotion_price = $p->promotion_price;
				$promotion_discount = $p->promotion_discount;
			}
		}else{
			$disc = 0;
			$promotion_name 	= '';
			$promotion_price 	= '';
			$promotion_discount = '';
		}
		echo '{"price" : '.$disc.',"promotion_name":"'. $promotion_name .'","promotion_discount": '. $promotion_discount .' ,"promotion_price":'. $promotion_price .'}';
	}

	if($_GET['Mode'] == 'balloonSearch'){
		if ($_GET['term'] == '') exit ;
			$q = $_GET["term"];
			$tb 	= 	DB_PREFIX.'item';
			$where 	= 	"item_code like '". strtoupper($q) ."%' AND item_active = 'Y'";
			$order 	=	"item_code asc"; 
			$row 	=	$db->result($tb,$where,$order,"0,20");
			//printArray($row);
			$resultArray = array();
			if($row){
				$arrCol = array();
				foreach ($row as $r) {
						$code1 		= substr($r->item_code,0,1);
						$code2 		= substr($r->item_code,1,1);
						$mainFolder	= $ball->itemFolder($code1,'group',0);
						$subFolder 	= $ball->itemFolder($code2,'subgroup',$mainFolder);
						$svgfolder 	= 'images/Balloons/'. $ball->FolderName($mainFolder).'/'.$ball->FolderName($subFolder).'/';
						$chk_img = $ball->svgtopng($svgfolder . $r->item_code);
						
						$arrCol['value'] 	= strtoupper($r->item_code); 
						$arrCol['label'] 	= $r->item_detail; 
						$arrCol['key'] 		= strtoupper($r->item_code);
						$arrCol['image'] 	= $chk_img;//r->item_name;
						$arrCol['svg'] 		= $chk_img;
						array_push($resultArray,$arrCol);
				}
			}
			
			$tbm 	=	DB_PREFIX.'materials';
			$mwhere =	"materials_id like '". strtoupper($q) ."%' OR materials_barcode like '".$q."%'";
			$morder	=	"materials_id asc";
			$mrow 	= 	$db->result($tbm,$mwhere,$morder,"0,20");
			if($mrow){
				foreach($mrow as $mr){
						$arrCol['value'] 	= strtoupper($mr->materials_id); 
						$arrCol['label'] 	= $mr->materials_detail; 
						$arrCol['key'] 		= strtoupper($mr->materials_id);
						$arrCol['image'] 	= 'images/Materials/'.$mr->materials_image;
						$arrCol['svg'] 		= $chk_img;
						array_push($resultArray,$arrCol);
				}
			}
			echo json_encode($resultArray);
			unset($resultArray);
	}
	
	if($_GET['Mode'] == 'Sell'){
		$tbheader 	= DB_PREFIX.'formheader';
		$formNo 	= $db->createID($tbheader,'form_no');
		parse_str($_POST['customer'], $post);
		//printArray($post);
		
		//printArray($_POST['blist']);exit;
		if(empty($post['cust_id'])){
			$ctm = array();
			$cmt['customer_name'] 		= $post['cust_name'];
			$cmt['customer_email'] 		= $post['cust_email'];
			$cmt['customer_company'] 	= $post['cust_company'];
			$cmt['customer_address'] 	= $post['cust_address'];
			$cmt['customer_tel'] 		= $post['cust_phone'];
			$cmt['customer_mobile'] 	= $post['cust_mobile'];
			$cmt['customer_fax'] 		= $post['cust_fax'];
			$cmt['customer_date'] 		= date('Y-m-d H:i:s');
			$cmt['customer_update'] 	= date('Y-m-d H:i:s');
			$cmt['ref_office_no'] 		= $ball->ref_office();
			$cmt['ref_admin_no'] 		= $_SESSION['LOGIN']['ID'];
			if(!empty($cmt['customer_name'])){
				$customer_no	= $db->createID(DB_PREFIX.'customer','customer_no');
				$cmt['customer_no'] 		= $customer_no;
				$db->insert(DB_PREFIX.'customer',$cmt);
			}else{
				$customer_no	= 7;
			}
		}else{
			$customer_no	=  $post['cust_id'];
		}
		
		$data['ref_office_no'] 	= $_SESSION['LOGIN']['OFFICE'];
		$data['ref_admin_id'] 	= $_SESSION['LOGIN']['ID'];
		$data['form_date'] 		= date('Y-m-d H:i:s');
		
		$data['form_type'] 		= 'sellbill';
		$data['ref_customer_no']	= 	$customer_no;
		$data['form_no'] 			= 	$formNo;
		$data['form_id'] 			= 	$ball->runBill(OFFICE_NO);
		$data['form_code'] 			= 	$ball->formCode('B');
		$data['form_send_date'] 	=	$_POST['send_date'];
		$data['form_send_time'] 	=	$_POST['send_time'];
		$data['form_amphur'] 		=	$_POST['hamphur'];
		$data['form_province'] 		=	$_POST['hprovince'];
		$data['form_shipp_detail']	=	$_POST['shipp_detail'];
		$data['form_promotion'] 	=	$_POST['ref_promotion_no'] != '' ? $_POST['promotion']:'';
		$data['ref_promotion_no'] 	=	$_POST['ref_promotion_no'];
		$data['form_discount'] 		=	$_POST['form_discount'];
		$data['form_getit'] 		=	$_POST['form_getit'];
		$data['form_shipping'] 		=	$_POST['form_shipping'];
		$data['form_shipping_type'] =	$_POST['form_shipping_type'];
		$data['ref_delivery_no'] 	=	$_POST['delivery_no'];
		//printArray($data); exit;
		$data['form_shipping_price'] = ($ball->shipping($_POST['delivery_no'])->delivery_price ? $ball->shipping($_POST['delivery_no'])->delivery_price : 0 );//$_POST['form_shipping_car_price'];
	
		/*
		if($_POST['form_shipping_type'] == 'car'){
			$data['form_shipping_price'] = $_POST['form_shipping_car_price'];
		}else if($_POST['form_shipping_type'] == 'taxi'){
			$data['form_shipping_price'] = $_POST['form_shipping_taxi_price'];
			$data['ref_delivery_no'] = $_POST['ref_delivery_no'];
		}else{
			$data['form_shipping_price'] = 0;
		}
		*/
		//$data['form_shipping_price']=	($_POST['form_shipping_type'] == 'car') ? $_POST['form_shipping_car_price'] : ($_POST['form_shipping_type'] == 'taxi') ? $_POST['form_shipping_taxi_price'] : 0 ;
		$data['form_setup'] 		=	$_POST['form_setup'];
		//$data['form_promotion'] 	=	'';//$_POST['form_setup'];
		
		$fWhere 	= "form_no = '". $formNo ."' AND form_type = 'sellbill' AND ref_office_no = '". $data['ref_office_no'] ."'";		
		
		$chk = $db->record($tbheader, $fWhere);
		$itemNo = $ball->item_no($code);
		
		if($chk === FALSE){
			$db->insert(DB_PREFIX.'formheader',$data);
		}
		//printArray($data);

		//:: FORM DETAIL SELL ON BILL DESIGN BILL :://
		//:: FORM MATERIALS LIST SELL ON BILL DESIGN BILL :://	
		//printArray($_POST['blist']);
		if($_POST['blist']){
			foreach($_POST['blist'] as $b){
				if($b){
					foreach($b as $bk => $bv ){
						if($bv['mat']){
							$price = 0;
							foreach($bv['mat'] as $tokey => $toval){
								$price += $toval['total'];
							}
						}
						
						//echo '<pre>', print_r($bv),'</pre>';
						$dWhere = "ref_form_no = '$formNo' AND materials_id = '". $bv['code']."'";
		//echo '[Where : '. $dWhere .']';
		$frs = $db->record(DB_PREFIX.'formdetail',$dWhere);
		$fdata['ref_materials_no'] 	= $ball->itemID($bv['code']);
		$fdata['ref_form_no'] 		= $formNo;
		$fdata['ref_office_no'] 	= $_SESSION['LOGIN']['OFFICE'];
		$fdata['materials_id'] 		= $bv['code'];
		$fdata['formdetail_unit'] 	= $bv['quantity'];
		$fdata['formdetial_price'] 	= $price;//$ball->itemPrice($itemNo);//$_POST['sell_price'];
		$fdata['formdetail_type'] 	= 'sellbill';
		$item = $db->record(DB_PREFIX.'item',"item_code = '". $bv['code'] ."'");
		//if($item !== FALSE)
		if($frs === FALSE){
			$rs = $db->insert(DB_PREFIX.'formdetail',$fdata);
		}else{
			$rs = $db->update(DB_PREFIX.'formdetail',$fdata,$dWhere);
		}
		$ref = $db->record(DB_PREFIX.'formdetail',$dWhere);
		if($bv['mat']){
			foreach($bv['mat'] as $k => $r){
			//$sell=array();
				$sell['ref_materials_id'] 	= $r['code'];
				$sell['ref_form_no'] 		= $formNo;
				$sell['ref_formdetail_no'] 	= $ref->formdetail_no;
				$sell['ref_item_no']		= $itemNo;
				$sell['sell_gas'] 			= $r['gas'];
				$sell['sell_unit'] 			= $r['quantity'];
				$sell['sell_price'] 		= $r['total'];
				$sell['sell_shipping'] 		= $_POST['form_shipping'];
				$sell['sell_shipping_type'] = $data['form_shipping_type'];
				$sell['ref_office_no'] 		= OFFICE_NO;
				$sell['ref_delivery_no'] 	= $_POST['delivery_no'];
				$sell['delivery_by'] 		= $ball->shipping($_POST['delivery_no'])->ref_office_no ? $ball->shipping($_POST['delivery_no'])->ref_office_no : 0;
		//printArray($sell);exit;
				$chksell = $db->record(DB_PREFIX.'sell',"ref_form_no = '$formNo' AND ref_formdetail_no = '". $ref->formdetail_no ."' AND ref_materials_id = '". $r['code'] ."'");
				if($chksell === FALSE) $db->insert(DB_PREFIX.'sell',$sell);
			}

		}
				}
			}
		}
		}
		$form['id'] = $formNo;
		echo json_encode($form);
		unset($form);
		/*
		printArray($_POST); 
		printArray($data); 
		printArray($fdata); 
		printArray($sell); 
		
		exit;*/
		//redirect('stock_show-print/'.$formNo.'printing.html');
	}
	
	if($_GET['Mode'] == 'MaterialsSearch'){
		//if ($_GET['term'] == '') exit ;
			$q 		= $_GET["term"];
			$tb 	= DB_PREFIX.'materials';
			$where 	= "materials_id like '". strtoupper($q) . "%' OR materials_barcode like '".$q."%'";
			$order 	= 'materials_id asc';
			$limit 	= '20';
			
			$row 	=	$db->result($tb,$where,$order,$limit);

		$resultArray = array();
			if($row){
				$arrCol = array();
				foreach ($row as $r) {
		 		$arrCol['value'] 	= $r->materials_id; //$obResult->fields['rm_descript'];
		        $arrCol['label'] 	= $r->materials_id; //$obResult->fields['rm_descript'];
		        $arrCol['key'] 		= $r->materials_detail; //$obResult->fields['rm_code'];
		 		$arrCol['no'] 		= $r->materials_no; //$obResult->fields['rm_descript'];
				$arrCol['image']	= 'images/Materials/'.$r->materials_image;
		         
		        array_push($resultArray,$arrCol);
				}
			}
			echo json_encode($resultArray);
			unset($resultArray);
	}
	
	if($_GET['Mode'] == 'ShownewSlot'){
			$tb 	= DB_PREFIX.'materials';
			$where 	= "materials_no = '". $_GET['id'] . "'";
			
			$r 	=	$db->record($tb,$where);
			$mat["material"] = array(
				"id" => $r->materials_no,
				"fullcode" => $r->materials_id,
				"name" => $r->materials_detail,
				"price" => $ball->materialsPrice($r->materials_id),
				"gas"	=> $ball->gasused($r->materials_id) == 0 ? 'N':'Y',
				"pricegas" => $ball->materialsPrice($r->materials_id),
				"pricenogas" => $ball->materials($r->materials_id)->materials_price,
				"quantity" => 1,
				"img" => "images/Materials/". $r->materials_image,
				"stock" => $ball->office_balance($r->materials_id, $_SESSION['LOGIN']['OFFICE']),
			);
			if(r !== FALSE){
			echo json_encode($mat);
			unset($resultArray);
			}else{ 
				return false;
			}
	}
	
	if($_GET['Mode'] == 'chkMaterials'){
			$tb 	= DB_PREFIX.'materials';
			$where 	= "materials_id = '". $_GET['code'] . "'";
			$r 	=	$db->record($tb,$where);
			
			$arr['no'] = $r !== FALSE ? $r->materials_no : 'false';
			$arr['code'] = $r !== FALSE ? $r->materials_id : 'false';
			echo  json_encode($arr);
			unset($arr);
	}

	if($_GET['Mode'] == 'Vehicle_list'){
		$tb 	= DB_PREFIX.'delivery';
		$where 	= "ref_office_no = '".$_GET['office']."'";	
		$rs 	= $db->result($tb,$where,$order,null,'*',"ref_vehicle_no");
		if($rs){
			foreach($rs as $r){
				echo '<option value="'.$r->ref_vehicle_no.'">'. $ball->vehicle($r->ref_vehicle_no)->vehicle_name .'</option>'."\n";
			}
		}
	}

	if($_GET['Mode'] == 'Province'){
	
		//if (empty($_GET['term'])) exit ;
		//change in next line:
		$q = mb_strtolower(($_GET["term"]),'UTF-8');
		if (get_magic_quotes_gpc()) $q = stripslashes($q);//$_GET["term"];
//			printArray($_POST);
//			echo $q;

			$tbv	= 	DB_PREFIX.'province';
			$tbd 	=   DB_PREFIX.'delivery';
			$inner 	=   "$tbv INNER JOIN $tbd ON $tbv.PROVINCE_ID = $tbd.ref_province_no AND $tbd.ref_vehicle_no = '".$_GET['vehicle']."' AND $tbv.PROVINCE_NAME like '%$q%'";
			$order 	=	"PROVINCE_NAME asc, PROVINCE_ID asc";
			$row 	=	$db->result($inner,null,$order,null,'*',"$tbd.ref_province_no");

		$resultArray = array();
			if($row){
				$arrCol = array();
				foreach ($row as $r) {
					$chk = $db->record(DB_PREFIX.'delivery',"ref_province_no = '". $r->PROVINCE_ID ."'");
					if($chk !== FALSE){
						$arrCol['value'] 			= $r->PROVINCE_NAME;
						$arrCol['label'] 			= $r->PROVINCE_NAME;
						$arrCol['PROVINCE_ID'] 		= $r->PROVINCE_ID;
						$arrCol['PROVINCE_CODE'] 	= $r->PROVINCE_CODE;
						$arrCol['PROVINCE_NAME'] 	= $r->PROVINCE_NAME;
						$arrCol['GEO_ID'] 			= $r->GEO_ID;
					}
		        array_push($resultArray,$arrCol);
				}
			}
			echo json_encode($resultArray);
	}

	if($_GET['Mode'] == 'Amphur'){
		//change in next line:
		$q = mb_strtolower(($_GET["term"]),'UTF-8');
		if (get_magic_quotes_gpc()) $q = stripslashes($q);//$_GET["term"];
//			printArray($_GET); exit;
//			echo $q;
			$tba 	= 	DB_PREFIX.'district';
			$tbd 	=   DB_PREFIX.'delivery';
			$where 	= 	null;
			$inner  = 	"$tba INNER JOIN $tbd ON $tba.PROVINCE_ID = '".$_GET['ref']."' AND $tba.DISTRICT_ID = $tbd.ref_district_no AND $tbd.ref_office_no = '". $_GET['office'] ."' ".(!empty($q) ? " AND  $tba.DISTRICT_NAME like '%$q%'" : '') ;
			$order 	=	"$tba.DISTRICT_NAME asc, $tba.DISTRICT_ID asc";
			$row 	=	$db->result($inner,$where,$order,null,"*","$tba.DISTRICT_ID");

		$resultArray = array();
			if($row){
				$arrCol = array();
				foreach ($row as $r) {
					$arrCol['value'] 		= $r->DISTRICT_NAME;
					$arrCol['label'] 		= $r->DISTRICT_NAME;
					$arrCol['AMP_NAME']		= $r->DISTRICT_NAME;
					$arrCol['AMPHUR_ID'] 	= $r->DISTRICT_ID;
					$arrCol['AMPHUR_CODE'] 	= $r->DISTRICT_CODE;
					$arrCol['AMPHUR_NAME'] 	= $r->DISTRICT_NAME;
					$arrCol['GEO_ID'] 		= $r->GEO_ID;
					$arrCol['PROVINCE_ID'] 	= $r->PROVINCE_ID;
					
//					$dlv = $db->record(DB_PREFIX.'delivery',"ref_province_no = '".$_GET['ref']."' AND ref_district_no = '". $r->DISTRICT_ID ."'");
					$arrCol['DELIVERY'] 	= $r->delivery_price;
					$arrCol['DELIVERY_NO'] 	= $r->delivery_no;
		        array_push($resultArray,$arrCol);
				}
			}
			echo json_encode($resultArray);
	}	
	
	if($_GET['Mode'] == 'ChkLocation'){
		$q = mb_strtolower(($_GET["term"]),'UTF-8');
		if (get_magic_quotes_gpc()) $q = stripslashes($q);//$_GET["term"];
			$tba 	= 	DB_PREFIX.'district';
			$where 	= 	"AMPHUR_ID = '". $_GET['amphur'] ."' AND PROVINCE_ID = '". $_GET['province'] ."' AND DISTRICT_NAME like '%$q%'";
			$order 	=	"DISTRICT_NAME asc";
			$row 	=	$db->result($tba,$where,$order,"20");

		$resultArray = array();
			if($row){
				$arrCol = array();
				foreach ($row as $r) {
					$arrCol['value'] 		= $r->DISTRICT_NAME;
					$arrCol['label'] 		= $r->DISTRICT_NAME;
					$arrCol['AMPHUR_ID'] 	= $r->DISTRICT_ID;
					$arrCol['AMPHUR_CODE'] 	= $r->DISTRICT_CODE;
					$arrCol['AMPHUR_NAME'] 	= $r->DISTRICT_NAME;
					$arrCol['GEO_ID'] 		= $r->GEO_ID;
					$arrCol['PROVINCE_ID'] 	= $r->PROVINCE_ID;
		        array_push($resultArray,$arrCol);
				}
			}
			echo json_encode($resultArray);
	}
	
	if($_GET['Mode'] == 'ListAmphur'){
		$ball->option_amphur($_GET['province']); 
	}

	if($_GET['Mode'] == 'locationSave'){
		parse_str($_POST['data'], $d);
//		printArray($d);
		$de = array(
						'ref_province_no'	=> $d['select-province'],
						'ref_amphur_no'		=> $d['select-province'],
						'ref_vehicle_no'	=> $d['vehicle'],
						'delivery_price'	=> $d['shipping_price'],
						'ref_admin_create'	=> $_SESSION['LOGIN']['ID'],
						'delivery_create'	=> time(),
						'ref_office_no'		=> OFFICE_NO,
					);
		if($d['h-location'] != ''){
			$de['ref_district_no']	= $d['h-location'];		
		}else{
			$data = array(
						'PROVINCE_ID' 	=> $d['select-province'],
						'AMPHUR_ID' 	=> $d['select-amphur'],
						'DISTRICT_NAME' => $d['txt-location'],
						'GEO_ID' 		=> $ball->hamphur($d['select-amphur'])->GEO_ID,
						);
			$db->insert(DB_PREFIX.'district',$data);
			$id = $db->record(DB_PREFIX.'district',$data);
			$de['ref_district_no']	= $id->DISTRICT_ID;
		}
			$delivery = $db->insert(DB_PREFIX.'delivery',$de);
			$result = array();
				$result['hprovince'] = $d['select-province'];
				$result['hamphur'] 	= $d['select-amphur'];
				$result['province'] = $ball->province($d['select-province']);
				$result['amphur'] 	= $ball->amphur($d['select-amphur']);
				echo json_encode($result);
	
	}
	?>