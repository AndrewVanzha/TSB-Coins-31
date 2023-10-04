<?php

/*--- Получаем список товаров со скидкой (очень корявый способ, другой пока не нашел) ---*/
$arDiscountElementID = array();

$db_res = CSaleDiscount::GetList(array(), array( "LID" => "s1", "ACTIVE" => "Y", ), false, false, array( 
	"ID",
	"XML_ID", 
	"LID", 
	"SITE_ID", 
	"NAME", 
	"PRICE_FROM", 
	"PRICE_TO", 
	"CURRENCY", 
	"DISCOUNT_VALUE", 
	"DISCOUNT_TYPE", 
	"ACTIVE", 
	"SORT", 
	"ACTIVE_FROM", 
	"ACTIVE_TO", 
	"TIMESTAMP_X", 
	"MODIFIED_BY", 
	"DATE_CREATE", 
	"CREATED_BY", 
	"PRIORITY", 
	"LAST_DISCOUNT", 
	"VERSION", 
	"CONDITIONS", 
	"UNPACK", 
	"APPLICATION", 
	"ACTIONS", 
	) 
); 

while($ar_res = $db_res->Fetch()) 
{ 
	$ACTIONS = unserialize($ar_res['ACTIONS']);
    
	foreach ($ACTIONS['CHILDREN'] as $key => $value) {
		foreach ($value as $key2 => $value2) {
			if ($key2 == 'CHILDREN') {
				foreach ($value2 as $key3 => $value3) {
					foreach ($value3 as $key4 => $value4) {
						if (is_array($value4)) {
							foreach ($value4 as $key5 => $value5) {
								if (is_array($value5)) {
								
									if ($value5['CLASS_ID'] == 'CondIBElement') {
										foreach ($value5['DATA']['value'] as $key => $element_id) {
											$arDiscountElementID[] = $element_id;
										}
										
									}
									elseif ($value5['CLASS_ID'] == 'CondIBSection') {
										$section_id = $value5['DATA']['value'];
										$arSelect = Array("ID");
										$arFilter = Array("IBLOCK_ID" => 8, "ACTIVE" =>"Y", "SECTION_ID" => $section_id);
										$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
										while($ob = $res->GetNextElement()) {
											$arFields = $ob->GetFields();
											$arDiscountElementID[] = $arFields['ID'];
										}
									} 
									
								}
							}
							
						}
						
					}
					
				}
			}
			
		}
		
	}

	
	
}

if (!empty($arDiscountElementID)) {
	$GLOBALS["arrFilter"] = array("ID" => $arDiscountElementID);
}






