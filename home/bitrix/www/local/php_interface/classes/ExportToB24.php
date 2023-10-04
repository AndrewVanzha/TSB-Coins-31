<?php

ini_set("memory_limit", "2028M");
ini_set("post_max_size", "512M");
ini_set("upload_max_filesize", "512M");
ini_set("max_execution_time", "900000");
ini_set("max_input_time", "6000");
ini_set('auto_detect_line_endings', '1');

use Bitrix\Sale,
	Bitrix\Main, 
	Bitrix\Main\Type\DateTime;
\Bitrix\Main\Loader::includeModule('sale');
use Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    //Bitrix\Sale\PaySystem,
	//Bitrix\Main\Service\GeoIp,
	Bitrix\Catalog;
	\Bitrix\Main\Loader::includeModule('iblock');
	

class ExportToB24 
{
	//URL вебхука	
	//define('URL','https://10.222.222.193/rest/1/udar3tjicfcv3pm6');
	private const URL = 'https://10.222.222.193/rest/1/udar3tjicfcv3pm6';

    //private const URL = 'https://andreww1000.h1n.ru/rest/1/6zlqqks9mebieur7/';
    //private const URL = 'https://mybx24.bitrix24.ru/rest/1/7k178zt9rpgcwn0n/';

	public function exportOrder(\Bitrix\Main\Event $event)
	{
        //file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/a_exportOrder.log', ' exportOrder ', FILE_APPEND);
    	self::log2file(" IS_NEW: " . $event->getParameter("IS_NEW"),'-start--IS_NEW');
		if(!$event->getParameter("IS_NEW")){return;}           
		
		//Заказ		
		$order = $event->getParameter("ENTITY");
		// id заказа
		$order_id = $order->getId(); 
		
		$propsData = [];//свойства заказа для экпорта
		/* $name = "";
		$second_name = "";
		$last_name = "";
		$fio = " ";
		$email = "";
		$phone = "";
		$postCode = ""; */
		$city = "";
		/* $street = " ";
		$korpus =  " ";
		$flat = ""; */
		//$client_region = " ";
		
		//Свойства заказа в битрикс
		$propertyCollection = $order->getPropertyCollection();
		foreach ($propertyCollection as $propertyItem) 
		{
			if (!empty($propertyItem->getField("CODE"))) 
			{
				$propsData[$propertyItem->getField("CODE")] = trim($propertyItem->getValue());
			}
		}
		
		self::log2file(" propsData: " . print_r($propsData,1),'-propsData');
		//self::log2file(" propertyCollection: " . print_r($propertyCollection,1),'-propertyCollection');
		
		$fio 		 = $propsData['FIO'];
		$arFio 		 = explode(" ", $fio);        
        $name 		 = $arFio[1]? $arFio[1]:'';
        $last_name 	 = $arFio[0]? $arFio[0]:'';
        $second_name = $arFio[2];
		$email       = $propsData['EMAIL'];
		$phone       = $propsData['PHONE'];
		$postCode    = $propsData['ZIP'];		
		$street 	 = $propsData['ADDRESS'];	
		$home 		 = $propsData['HOME'];	
		$korpus 	 = $propsData['HOUSING'];	
		$flat 	     = $propsData['APARTAMENT'];
		$region 	 = $propsData['LOCATION'];
		
		/* if ($property = $propertyCollection->getItemByOrderPropertyId(6)) {
			$location = $property->getValue();
		}
		
		
		if ($property = $propertyCollection->getItemByOrderPropertyId(5)) {
			$city = $property->getValue();
		} */
		//$loc_arr = [];
		//$res = \Bitrix\Sale\Location\LocationTable::getList(array(
		//'filter' => array('=TYPE.ID' => '5', '=NAME.LANGUAGE_ID' => LANGUAGE_ID),
		//'select' => array('NAME_RU' => 'NAME.NAME')
		//));
		//while ($item = $res->fetch()) 
		//{
		//	$loc_arr [] = $item['NAME_RU']; 
		//}
		
		//self::log2file(" loc_arr: " . print_r($loc_arr,1)." region: ".print_r($region,1),'-loc_arr');
//Сделать поиск региона из внутренней базы		
		$regionFromIp = self::getRegionFromIp()? self::getRegionFromIp(): 'Скрыт';
		//$regionFromAddress = self::getRegionFromAddress($street.$home.$korpus.$flat);
		//$regionFromPostIndex = self::getRegionFromPostIndex($postCode);
		
		//$address       = ($store_id == 0)? $regionFromIp : $regionFromAddress[1];
		//$client_region = ($store_id == 0)? $regionFromIp : $regionFromAddress[0];
		//$address       = ($store_id == 0)? $regionFromIp :  $regionFromPostIndex;
		$address       = $regionFromIp;
		$client_region = $address;
		$comments      = "Клиент из интернет-магазина";
				
		$fields_contact = array(

            //"POST" => ,
            "COMMENTS" => $comments,
            //"HONORIFIC" => ,
            "NAME" => $fio,
            //"SECOND_NAME" => $second_name,
            //"LAST_NAME" => $last_name,
            //"PHOTO" => ,
            //"LEAD_ID" => ,
            "TYPE_ID" => "CLIENT",
            //"SOURCE_ID" => UC_QKA1ZJ,
            //"SOURCE_DESCRIPTION" => ,
            //"COMPANY_ID" => ,
            //"BIRTHDATE" => $client_birth_date,
            "EXPORT" => "Y",
            //"HAS_PHONE" => "Y",
            //"HAS_EMAIL" => "Y",
            //"HAS_IMOL" => "N",
            "DATE_CREATE" => date("Y-m-d H:i:s"),
            //"DATE_MODIFY" => date("Y-m-d H:i:s"),
            "ASSIGNED_BY_ID" => 48,
            "CREATED_BY_ID" => 1,
            "MODIFY_BY_ID" => 1,
            "OPENED" => "Y",
            //"ORIGINATOR_ID" => ,
            //"ORIGIN_ID" => ,
            //"ORIGIN_VERSION" => ,
            //"FACE_ID" => ,
            "ADDRESS" => $address,
            "ADDRESS_2" => $street." ".$home." ". $korpus. " ".$flat,
            "ADDRESS_CITY" => $city,
            "ADDRESS_POSTAL_CODE" => $postCode,
            "ADDRESS_REGION" => $client_region,
            //"ADDRESS_PROVINCE" => ,
            //"ADDRESS_COUNTRY" => ,
            //"ADDRESS_LOC_ADDR_ID" => ,
            //"UTM_SOURCE" => ,
            //"UTM_MEDIUM" => ,
            //"UTM_CAMPAIGN" => ,
            //"UTM_CONTENT" => ,
            //"UTM_TERM" => ,
            "UF_CRM_CONTACT_REGION" => $client_region,
            //"UF_CRM_CONTACT_CODE" => $kod_abs,
            "UF_CRM_CONTACT_SYMBOL" => "ФЛ",
            "UF_POTENTIAL_CONTACT" => 1,
            "PHONE" =>  array(
							array(
								//"ID" => 44,
								"VALUE_TYPE" => "MAILING", // WORK / MOBILE / HOME
								"VALUE" => $phone,
								"TYPE_ID" => "PHONE",
							)
						),
            "EMAIL" =>  array(
							array(
								//"ID" => 46,
								"VALUE_TYPE" => "MAILING",//"HOME"/"WORK"
								"VALUE" => $email,
								"TYPE_ID" => "EMAIL",
								)
						),
        );
		//лог
		self::log2file(" fields_contact: " . print_r($fields_contact,1),'-fields_contact');
		
		$metod_contact = "crm.contact.add";
		$data_contact = array(
			"fields" => $fields_contact
		);		
		
		$contact_id = self::executeHook($data_contact, self::URL, $metod_contact)['result'];
		
	//=========================================Экспорт заказа в сделку CRM==============================================================	
		//Корзина заказа
		$basket = $order->getBasket();
		
		//массив товаров в читаемом виде
		$arBasketInfo = $basket->getListOfFormatText();			
		$arBasketInfo = implode("||", $arBasketInfo);
		//Сумма заказа
		$orderSumma = $basket->getPrice();
		//Статус заказа
		$status_id = $order->getField("STATUS_ID");
		$basketTotal = 0;
		//$basketSumma = 0;
		foreach ($basket as $basketItem)
		{			
			$basketTotal += $basketItem->getQuantity();	
			//$basketSumma += $basketItem->getFinalPrice();
			/*
			$basketItem->getProductId();  // ID товара
			$basketItem->getPrice();      // Цена за единицу
			$basketItem->getQuantity();   // Количество
			$basketItem->getFinalPrice(); // Сумма
			*/
		}
		/**
		 * Собираем все свойства и их значения в массив
		 * @var \Bitrix\Sale\PropertyValue $propertyItem
		 */		
		
		//Доставка заказа
		$shipmentCollection = $order->getShipmentCollection(); 
		$lastShipment = null;
		$store_name = "";
		
		foreach($shipmentCollection as $shipment)
		{
			$shipment_nameDirty = $shipment->getDeliveryName(); //тут имя доставки
			$pieces = explode("(", $shipment_nameDirty);
			$shipment_name = $pieces[0]; // а тут просто очистили от лишнего в скобочках
			
			if (!$shipment->isSystem())
			{
			  $lastShipment =  $shipment;
			}
			if($lastShipment){
				$store_id = $lastShipment->getStoreId();//id склада самовывоза
				$store_address = self::getAddressPickup($store_id); //адресс пункта самовыавоза
				$store_name = self::get_store($order)['name'];//название офиса самовывоза
			}

		}
		$store_ids = array( 0 => 61, 1 => 60,	2 => 73, 4 => 63, 6 => 79, 7 => 160, 8 => 77, 9 => 80, 10 => 68, 14 => 78, 15 => 74, 16 => 60 );
		$region_ids = array( 0 => $client_region, 1 => "г. Москва",	2 => "Калининградская обл., г.Калиниград", 4 => 'респ. Татарстан, г.Казань', 
		6 => 'Пермский край, г.Пермь', 7 => 'Коаснодарский кр., г.Туапсе', 8 => 'Липецкая обл., г. Липецк', 9 => 'Нижегородская обл., г.Нижний Новгород', 10 => 'г. Санкт-Петербург', 14 => 'г. Москва', 15 => 'г. Москва', 16 => 'г. Санкт-Петербург' );
		$pickup_id = $store_ids[$store_id];
		self::log2file(" contact_id: " . print_r($contact_id,1),'-contact_id');
		$deal_id_abs = "";		
       
		$emails = [$email];
        $phones = [$phone];        
		$company_name = "";		
		$deal_sum = $order->getPrice();
		$company_id = 0;
		//$contact_id = 2262;
		//$deal_date = $order->getDateInsert(); // объект Bitrix\Main\Type\DateTime
		$deal_date = date("Y-m-d H:i:s"); // объект Bitrix\Main\Type\DateTime
		$delivery_id  = ($shipment_nameDirty == "Самовывоз")? 170: 171;
		$category_id  = 1;
		$stage_id     = "C1:NEW";
		
		
		//$assyned_id = $order->setFields('RESPONSIBLE_ID', $managerId); //для crm - ответственный 
		//$deal_region = ($store_id == 0)? $client_region: self::getRegionFromAddress($store_address)[0];
		$deal_region = ($store_id == 0)? $client_region: $region_ids[$store_id];
		$deal_place = ($shipment_nameDirty == "Самовывоз")? $store_address: $client_region ." "
		. $street." ".$home." ". $korpus. " ".$flat;
		
		$fields_deal = array(
            "TITLE" => "Заказ Интернет-магазина № $order_id",
            "TYPE_ID" => "SALE",
            "STAGE_ID" => $stage_id,
            //"PROBABILITY" => ,
            "CURRENCY_ID" => "RUB",
            "OPPORTUNITY" => (float)$deal_sum,
            "IS_MANUAL_OPPORTUNITY" => "N",
            //"TAX_VALUE" => 0.00,
            //"LEAD_ID" => ,
            "COMPANY_ID" => $company_id,
            "CONTACT_ID" => $contact_id,
            //"QUOTE_ID" => ,
            "BEGINDATE" => $deal_date,
            //"CLOSEDATE" => ,
            "ASSIGNED_BY_ID" => 48,
            "CREATED_BY_ID" => 1,
            "MODIFY_BY_ID" => 1,
            "DATE_CREATE" => $deal_date,
            "DATE_MODIFY" => $deal_date,
            "OPENED" => "Y",
            "CLOSED" => "N",
            "COMMENTS" =>  "Монеты: $arBasketInfo Заказ Интернет-магазина №  $order_id Название доставки:  $shipment_name Статус заказа: $status_id",
            //"COMMENTS" =>  "order:  end !",
            //"ADDITIONAL_INFO" => ,
            //"LOCATION_ID" => ,
            "CATEGORY_ID" => (int)$category_id,//направление сделки(Интернет-Магазин/отделение банка)
            //"STAGE_SEMANTIC_ID" => "S",
            "IS_NEW" => "Y",
            "IS_RECURRING" => "N",
            "IS_RETURN_CUSTOMER" => "N",
            "IS_REPEATED_APPROACH" => "N",
            //"SOURCE_ID" => UC_QKA1ZJ,
            //"SOURCE_DESCRIPTION" => ,
            //"ORIGINATOR_ID" => ,
            "ORIGIN_ID" => $order_id,
            "MOVED_BY_ID" => 1,
            //"MOVED_TIME" => 2022-08-02T15:41:17+03:00,
            //"UTM_SOURCE" => ,
            //"UTM_MEDIUM" => ,
            //"UTM_CAMPAIGN" => ,
            //"UTM_CONTENT" => ,
            //"UTM_TERM" => ,
            "UF_CRM_DEAL_ID" => $deal_id_abs,
            "UF_CRM_DEAL_REGION_DEAL" => $deal_region,
            "UF_CRM_DEAL_PLACE_DEAL" => $pickup_id,
            "UF_CRM_DEAL_PLACE" => $deal_place,
			"UF_CRM_1663916300282" => (count($emails) > 0)? implode(",", $emails): $emails,
            "UF_CRM_1663916359704" => (count($phones) > 0)? implode(",", $phones): $phones,
            //"UF_CRM_1663916520370" => $last_name,
            "UF_CRM_1663916549344" => $fio,
            //"UF_CRM_1663916577947" => $second_name,
			//"UF_CRM_DEAL_COMPANY" => $company_name,
			"UF_CRM_DEAL_ORDER" => $order_id,
			"UF_CRM_DEAL_DELIVERY" => $delivery_id,
			"UF_CRM_DEAL_PICKUP" => 0,
			"UF_CRM_DEAL_ASSIGNED" => 1,
			"UF_CRM_DEAL_NEW" => 1,
			
			"UF_CRM_ORDER_PRODUCTS" => $arBasketInfo,//Состав заказа Строка
			"UF_CRM_ORDER_SUMMA"    => $orderSumma,//Сумма заказа ИМ Число или Деньги
			"UF_CRM_ORDER_TOTAL"    => $basketTotal, //Кол-во монет в заказе ИМ Целое число
			
			"UF_CRM_ORDER_DELIVERY" => $shipment_name, //Способ доставки ИМ Строка
			"UF_CRM_ORDER_PICKUP"   => $store_name, //Пункт выдачи ИМ - Название Строка
			"UF_CRM_ORDER_DATE"     => $deal_date, //Дата заказа ИМ Дата
		);
		//лог
		self::log2file(" fields_deal: " . print_r($fields_deal,1),'-fields_deal');
		//self::log2file(" address getRegionFromAddress: " . print_r(self::getRegionFromAddress($address)[0],1),'-getRegionFromAddress');
		//self::log2file(" getRegionFromIp: " . print_r(self::getRegionFromIp(),1),'-getRegionFromIp');
		
		$data_deal = array(
			"fields" => $fields_deal,
			'params' => array("REGISTER_SONET_EVENT" => "N")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.	
		);	
		$metod_deal = "crm.deal.add";			
		
		$deal_id = self::executeHook($data_deal, self::URL, $metod_deal)['result']; 
		self::log2file(" deal_id: " . print_r($deal_id,1),'-deal_id');
		
		//добавить свойство заказа DEAL_ID
		//$order->setField('DEAL_ID', $deal_id);
		$order->getPropertyCollection()->getItemByOrderPropertyId(25)->setValue($deal_id);
		//$order->save();
//===========================Добавление товаров в сделку======================================================================
		//корзина
		$basket = $order->getBasket();
		
		//массив товаров в заказе
		$offerList = array();
		//массив product_rows
		$product_rows = array(); 
		
		//цикл по товарам в заказе
		foreach ($basket as $item) 
		{	
			//получаем свойства товара
			$prop_common = CIBlockElement::GetByID($item->getProductId());
			$prop = array();
			 if ($ob = $prop_common->GetNextElement()) {
				 $prop = $ob->GetProperties();
			}
			$sku = '';
			$metall = '';
			$country = '';
			$product_id = '';
			$product_name = '';
			$price = '';
			if (count($prop) > 0) {
				$sku = (string)$prop['ARTICLE']['VALUE'];
				$metall =  $prop['METAL']['VALUE'];
				$country = $prop['COUNTRY']['VALUE'];
			}
			$product_id = (int)$item->getProductId();
			$product_name = (string)$item->getField('NAME');
			$price = (float)$item->getPrice();
			//проверяем монету по артикулу - есть ли такая в CRM
			$product_prop = @self::check_artikul($sku)[0];
			
			if(@$product_prop['XML_ID'] == $sku)
			{
				$pid = $product_prop[0]['ID'];
				self::log2file(" pid: " . print_r($pid,1) ." sku: " .  $sku,'-sku--pid');
			}
			else
			{
				//категория
				$cat = ($country == "Россия")? 110: 112;
				$cat1 = (stripos($product_name, "Инвестиц")) === false ? 109: 111;
				$fields_product = array(
                    "NAME" => $product_name, //Название	string	Обязательное
                    "CODE" => self::translit($product_name, "-"),
                    "ACTIVE" => "Y", //Активен	char
                    //"PREVIEW_PICTURE" => "",//Картинка для анонса	product_file
                    //"DETAIL_PICTURE" => "",//Детальная картинка	product_file
                    "SORT" => 500,    //Сортировка	integer
					"XML_ID" => $sku,	//Артикул
                    "MODIFIED_BY" => 1, //Кем изменён товар	integer
                    "CREATED_BY" => 1, //Кем создан товар	integer
                    "CATALOG_ID" => 14, //Идентификатор каталога	integer
                    "SECTION_ID" => $cat, //Идентификатор раздела	integer
                    //"DESCRIPTION" => "",//Описание	string
                    "DESCRIPTION_TYPE" => "html", //Тип описания	string
                    "PRICE" => $price, //Цена	double
                    "CURRENCY_ID" => "RUB", //Идентификатор валюты	string обяз
                    //"VAT_ID" => "", //Идентификатор ставки НДС	integer
                    "VAT_INCLUDED" => "N", //НДС включён в цену	char
                    "MEASURE" => "шт.", //Единица измерения	integer
                    "PROPERTY_63" => array(
                        //"valueId" => 98,
                        "value" => $metall,//string
                    ),

                    "PROPERTY_64" => array(
                        //"valueId" => 99,
                        "value" => (string)$sku//(string)
                    )
                );
				$data_product = array(
					"fields" => $fields_product	
				);	
				$metod_product = "crm.product.add";
				$pid =  self::executeHook($data_product, self::URL, $metod_product)['result'];
				self::log2file("new pid: " . print_r($pid,1),'-new--pid');
			}
			 $offerList = array(
			   "ARTICLE"=> $sku,
			   "PRODUCT_ID" 	=> (int)$pid,			   
			   "PRODUCT_NAME" 	=> $product_name,			   			
			   //"PROPERTIES"=> $prop,			  
			   "COUNT" 	=> (int) $item->getQuantity(),
			   "SUM" 	=> (float) $item->getFinalPrice(),
			   "PRICE" 	=> $price
			);
			self::log2file(" offerList: " . print_r($offerList,1),'-offerList');
			$product_rows[] = array(
			   //"ID" => 2 id (int)идентификатор элемента CRM, к которому привязана товарная позиция (например, идентификатор сделки)
                "OWNER_ID" => 14, // ID владельца(у нас - торговый каталог) (int) символьный код типа сущности CRM, к которому привязана товарная позиция. Узнать, какому типу сущности соответствует конкретный код можно через методы класса \CCrmOwnerTypeAbbr.
                "OWNER_TYPE" => "D", //Тип владельца (string)
                "PRODUCT_ID" => (int)$offerList['PRODUCT_ID'], //(int) идентификатор товара из каталога, которому соответствует товарная позиция
                "PRODUCT_NAME" => $offerList['PRODUCT_NAME'], // название товара (string)
                "ORIGINAL_PRODUCT_NAME" => $offerList['PRODUCT_NAME'],
                //"PRODUCT_DESCRIPTION" => "",
                "PRICE" => (float)$offerList['PRICE'], //Цена (double) стоимость за единицу товарной позиции с учетом скидок и налогов
                "PRICE_EXCLUSIVE" => (float)$offerList['PRICE'], //Цена без налога со скидкой(double) стоимость за единицу товарной позиции с учетом скидок, но без учета налогов
                "PRICE_NETTO" => (float)$offerList['PRICE'], //PRICE_NETTO(double) стоимость  без учета скидок и без учета налогов
                "PRICE_BRUTTO" => (float)$offerList['PRICE'], //PRICE_BRUTTO(double) стоимость с учетом налогов, но без учета скидок
                "PRICE_ACCOUNT" => (float)$offerList['PRICE'], //стоимость с учетом скидок и налогов, сконвертированная в валюту отчетов.
                "QUANTITY" => (float)$offerList['COUNT'], //Количество (double)
                "DISCOUNT_TYPE_ID" => 2, //Тип скидки (int)  Может быть 1 для скидки в абсолютном значении и 2 для скидки в процентах. По умолчанию равно 2
                "DISCOUNT_RATE" => 0, //Величина скидки(double) процент скидки .
                "DISCOUNT_SUM" => 0, //Сумма скидки (double) абсолютное значение скидки
                "TAX_RATE" => 0, //Налог (double) процент налога 
                "TAX_INCLUDED" => "N", //Налог включен в цену (char)
                "CUSTOMIZED" => "Y", //Изменен (char)
                "MEASURE_CODE" => 796, //Код единицы измерения (int) Настроить можно в разделе Единицы измерения настроек CRM.
                "MEASURE_NAME" => "шт", //Единица измерения (string) Настроить можно в разделе Единицы измерения настроек
                "SORT" => 10, // коэффициент сортировки (int)
                //"RESERVE_ID" => ,
                "RESERVE_QUANTITY" => 0,
			);
		}
		$data_products = array(
					"id" => (int)$deal_id,
					"rows" => $product_rows					
				);	
		$metod_products = "crm.deal.productrows.set";
	self::log2file('data_products: '.print_r($data_products,1), '-finish--data_products');
		self::executeHook($data_products, self::URL, $metod_products);	

			
	}
	//Проверка есть ли такой товар в CRM по артикулу -> XML_ID
	private function check_artikul ($articul) 
	{

		//$queryUrl = 'https://10.222.222.193/rest/1/udar3tjicfcv3pm6/crm.product.list.json';
		$queryUrl = self::URL.'/crm.product.list.json';
		//echo $queryUrl . '<br>';
		$now = new DateTime();
		$params = array(
			'order' => array( 'BEGINDATE' => 'DESC' ),
			'FILTER' => array(
				//'>BEGINDATE' => $now->modify('-7 day')->format('d.m.Y H:i:s'),
					'CHECK_PERMISSIONS' => 'N',
				'CATEGORY_ID' => 14,
				'XML_ID' => $articul			
			),
			'select' => array(			
				'XML_ID'
			),

		); 
		$queryData = http_build_query($params);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			//CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			//CURLOPT_USERPWD => "bitrix:pPkkqgip8hbWSladTIAh",
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryData,
		));

		$result = json_decode(curl_exec($curl),true);
		curl_close($curl);

		return (!isset($result['error']) or empty($result['error']))? $result['result']: $result['error'];
	}
	
	// получаем адрес пункта самовывоза по id
	private function getAddressPickup($store_id)
	{
		
		$connection = \Bitrix\Main\Application::getConnection();
		$sql = '
			SELECT ADDRESS 
			FROM b_catalog_store
			WHERE id = ' . (int)$store_id . '
		';
		$store = $connection->query($sql)->fetch();
		if (!$store) {
			return false;
		}

		return $store['ADDRESS'];
	}
	private function getRegionFromAddress($address)
	{
		require_once('Suggestions.php');		
		$is_bot = preg_match(
		 "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i", 
		 $_SERVER['HTTP_USER_AGENT']
		);		
		// получаем регион по адресу
		$token = '49d6bfad5748ee681d9e571384ebb5f9a2be0f71';
		$dadata = new Suggestions($token);
		//$query = 'Люблинская 76';
		$data = array(
			'query' => $address
		);
		$resp = $dadata->suggest("address", $data)->suggestions[0];
		
		return !$is_bot ? [$resp->data->region_with_type, $resp->value]:[];
	}
	private function getRegionFromIp()
	{
		$is_bot = preg_match(
		 "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i", 
		 $_SERVER['HTTP_USER_AGENT']
		);
		$geo = !$is_bot ? json_decode(file_get_contents('http://api.sypexgeo.net/json/'), true) : [];
		return $geo['region']['name_ru'];
	}
	//Получения региона из почтового индекса
	private function getRegionFromPostIndex($postIndex)
	{
		$is_bot = preg_match(
		 "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i", 
		 $_SERVER['HTTP_USER_AGENT']
		);
		$postIndex = trim((string)$postIndex);
		$index = substr($postIndex, 0, 3);
		$queryUrl = 'https://www.postindexapi.ru/json/'.$index.'/'.$postIndex.'.json';			
		$result = !$is_bot ? file_get_contents($queryUrl, false) : [];
		$output = json_decode($result);
		$output = json_decode(json_encode($output), true);
		return $output;
	}
	
	//Запрос к REST API
	private function executeHook($params, $url, $metod)
	{
			$queryUrl = "$url/$metod.json";
			$queryData = http_build_query($params);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_SSL_VERIFYPEER => 0,
				//CURLOPT_SSL_VERIFYHOST => FALSE,
				//CURLOPT_USERPWD => "bitrix:pPkkqgip8hbWSladTIAh",
				CURLOPT_POST => 1,
				CURLOPT_HEADER => 0,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $queryUrl,
				CURLOPT_POSTFIELDS => $queryData,
			));

			$result = curl_exec($curl);
			
			curl_close($curl);

			//echo  $queryUrl;
			//echo "<pre>";
			//print_r($result);
			//echo "</pre>";
	//self::log2file(" result: " . print_r($result,1),"-result--$metod");
	//self::log2file(" queryUrl: " . print_r($queryUrl,1),"-queryUrl--$metod");
			return json_decode($result, true);
	}

	
		
	//Получить склад самовывоза
	private function get_store($orderInfo)
	{
		//use\Bitrix\Catalog  ;  //подключать обязательно в самом верху файла, даже выше собственного кода   
       
		//$orderInfo = \Bitrix\Sale\Order::load($order_id);  // по ID заказа получаем обьект  

		$store_id = false;
		foreach ($orderInfo->getShipmentCollection() as $s){
		   $store_id = $s->getStoreId();
		   if ($store_id) {
			  break;
		   }
		}

		if($store_id){
		   $arStore = Catalog\StoreTable::getRow([
			  'select' => ['TITLE', 'ADDRESS', 'PHONE'],
			  'filter' => [
				 'ID' => $store_id,
			  ]
		   ]);
		}

		return array('address' => $arStore['ADDRESS'] , 'name' => $arStore['TITLE'], 'phone' => $arStore['PHONE']);
	}

	/*--------функция отправки сообщения----------*/

	private function sendMessage($message, $email) {
	  $url = self::url();
	  $headers = "From: ".$url. "\r\n" .
		"MIME-Version: 1.0" . "\r\n" .
		"Content-type: text/html; charset=UTF-8" . "\r\n";	 
	  $mail = mail($email, $message . "  на сайте: " . $url, $message, $headers);
	  //}
	  //echo "сообщение отправлено с текстом " . $res_message;
	}

	/*----оперделение url сайта---------------*/


	private function url() {
	  if ($_SERVER['HTTPS'] == "on")
	  {
		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
	  }
	  else
	  {
		$protocol = 'http';
	  }

	  return $protocol . "://" . $_SERVER['HTTP_HOST'];
	}

	//Функция логирования в файл
	/**
	 * @param $str string строка в протокол
	 * @param $fn string модификатор имени файла
	 * @return string
	 */
	private function log2file($str, $fn = null, $folder=__DIR__."/logExportToB24/")
	{  
	if(!file_exists($folder))
		{
			mkdir($folder, 0777, true);
		}
	  $error = "";
	  $fn = $fn ? "-" . str_replace(['\\', '/', ' '], '', $fn) : "";
	  $fp = fopen($folder . date("Y") . "-log2file{$fn}.log", "a");
	  $test = fwrite($fp, date("Y-m-d H:i:s") . ";" . var_export($str,true) . "\r\n");

	  if (!$test) {
		$error = "Ошибка при записи в файл " . $folder . date("Y") . "-log2file{$fn}.log";
	  }
	  fclose($fp);

	  return $error;
	}

	

	/*
	Отменяем создание заказа при отсутствии каких-то данных и выдаем сообщение в процедуре заказа, при этом проверяем, не было ли сгенерировано ошибок другими обработчиками, для этого регистрируем событие с большой сортировкой, чтобы оно выполнялось в конце (флага IS_NEW в этом событии нет, поэтому проверяем, что заказ еще не создан с помощью проверки отсутствия ID заказа):


	*/
	private function file_post_contents($url = 'https://10.222.222.193/app/importexport/getData.php', $data=array('key'=>"1q2w3e!QWE!",'art'=>'id'), $username = 'admin', $password = 'Kmr-bYp-CkW-sS2')
	{
		/*
		$auth = base64_encode("username:password");
		$context = stream_context_create([
			"http" => [
				"header" => "Authorization: Basic $auth"
			]
		]);
		$homepage = file_get_contents("http://example.com/file", false, $context );
		
		*/
		
		$u = 'https://10.222.222.193/app/importexport/getData.php?key=1q2w3e!QWE!&art=id';
		$url = 'https://10.222.222.193/app/importexport/getData.php';
		$data = array(
			'key'=>'1q2w3e!QWE!',
			'art'=>'id'
		);
		$postdata = http_build_query($data);
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);
		if($username && $password)
		{
			$opts['http']['header'] .= ("Authorization: Basic " . base64_encode("$username:$password"));
		}
		$context = stream_context_create($opts);
		return json_decode(file_get_contents($url, false, $context), true);
		//return json_decode(file_get_contents('http://10.222.222.193/app/importexport/getData.php?key=1q2w3e!QWE!&art=id', false), true);
	} 
		// Транслитерация строк.
	private function translit($str, $s = "")
	{
		$rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
		$lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
		return preg_replace("~[^-a-z0-9_]+~u", "$s", strtolower(str_replace($rus, $lat, $str)));
	}

	function changePropertyBeforeSaved(Main\Event $event)
	{
		/** @var \Bitrix\Sale\Order $order */
		$order = $event->getParameter("ENTITY");

		/** @var \Bitrix\Sale\PropertyValueCollection $propertyCollection */
		$propertyCollection = $order->getPropertyCollection();

		$propsData = [];

		/**
		 * Собираем все свойства и их значения в массив
		 * @var \Bitrix\Sale\PropertyValue $propertyItem
		 */
		foreach ($propertyCollection as $propertyItem) {
			if (!empty($propertyItem->getField("CODE"))) {
				$propsData[$propertyItem->getField("CODE")] = trim($propertyItem->getValue());
			}
		}

		/**
		 * Перебираем свойства и изменяем нужные значения
		 * @var \Bitrix\Sale\PropertyValue $propertyItem
		 */
		foreach ($propertyCollection as $propertyItem) {

			switch ($propertyItem->getField("CODE")) {

				// Установка полного адреса в формате: Адрес, Город, Индекс
				case 'F_PATH':
					$val = trim($propsData['ADDRESS'] . ', ' . $propsData['CITY'] . ', ' . $propsData['ZIP']);
					$propertyItem->setField("VALUE", $val);
					break;

				// Прописываем ФИО в одно поле
				case 'F_FIO':
					$val = $propsData['FNAME'] . ' ' . $propsData['LNAME'] . ' ' . $propsData['MNAME'];
					$propertyItem->setField("VALUE", $val);
					break;

			}
		}
		
		
      

	}
	
	
}













?>