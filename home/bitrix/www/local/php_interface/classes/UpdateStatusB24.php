<?

include_once($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/vendor/crest/crest.php');
//https://mrcappuccino.ru/blog/post/work-with-order-bitrix-d7
//https://dev.1c-bitrix.ru/api_help/sale/events/events_status_order.php
//https://www.sng-it.ru/snippet/sobytiya-izmeneniya-zakaza-dlya-vebkhukov-v-bitriks24.html
//https://github.com/studiofact/wiki-bitrix/wiki
use Bitrix\Main;
use \Bitrix\Sale;
use \Bitrix\Sale\Order;

class UpdateStatusB24 
{
	
	private const TOKEN = 'udar3tjicfcv3pm6';
	//private const TOKEN = '6zlqqks9mebieur7';
	//private const TOKEN = '7k178zt9rpgcwn0n';
	const DOMEN = '10.222.222.193';
	//const DOMEN = 'andreww1000.h1n.ru';
	//const DOMEN = 'mybx24.bitrix24.ru';

	const LOG_FILE = '/home/bitrix/www/local/php_interface/logs/';
	private static $status = 'N';
	private static $stageId = 'C1:NEW';
	private static $orderId = null;
	private static $order = null;	
	private static $dealId = null;
	private static $deal = null;
	private static $oldStageId = null;
	private static $assignedId = null;
	private static $oldAssignedId = null;
	private static $delivery = null;
	//private static $categoryId = 1;
	//private static $pickupId = 0;
		
	
	//public function updateOrder( $orderId, $status )// для OnSaleStatusOrder
	public function updateOrder( $event ) // для OnSaleStatusOrderChange
	{
	self::log2file('start','start');
	
		$parameters = $event->getParameters();
		
		//self::$orderId    = $orderId;
		//self::$status     = $status;
		//self::$order 	  = self::getOrder($orderId);
		self::$status  = $parameters['VALUE'];
		self::$stageId = self::getStageId(self::$status);	
		self::$order   = $parameters['ENTITY'];
		self::$orderId = self::$order->getField("ID"); // ID заказа
		
	//self::log2file(self::$orderId,'-orderId');	
	self::log2file(self::$status,'-status');	
	self::log2file(self::$stageId,'-stageId');	
		
		self::$dealId 	  = self::getDealId(self::$order);		
	self::log2file(self::$dealId,'-dealId');	
		self::$deal       = self::getDealCrest(self::$dealId, self::$orderId);
		self::$oldStageId = self::$deal["UF_CRM_DEAL_STAGE"];
		self::$assignedId = self::$deal["ASSIGNED_BY_ID"];
		self::$oldAssignedId = self::$deal["UF_CRM_DEAL_ASSIGNED"];
	self::log2file(self::$oldStageId,'-oldStageId');		
		self::$delivery   = self::getDelivery(self::$order);
	//self::log2file(self::$delivery,'-delivery');			
		$deliveryId  	  = (self::$delivery == "Самовывоз")? 170: 171;
	//self::log2file($deliveryId,'-deliveryId');	
		
		//if( self::$oldStageId != null && self::$stageId != self::$oldStageId && self::$oldStageId != 'C1:UC_CG9PZ0' )
		if( self::$oldStageId != null && self::$stageId != self::$oldStageId )
		{		
			$metod = "crm.deal.update";
			//$dealId = $arProps['VALUE'];	
			$data_deal = array(	
				'id' =>  (int)self::$dealId,
				'fields' => array(
					"STAGE_ID" => self::$stageId,
					"ASSIGNED_BY_ID" =>	self::$assignedId,
					"UF_CRM_DEAL_ORDER" =>	self::$orderId,
					//"CATEGORY_ID" => self::$categoryId,
					"UF_CRM_DEAL_DELIVERY" => $deliveryId,
					//"UF_CRM_DEAL_PICKUP" => $pickupId,
					"UF_CRM_DEAL_STAGE" => self::$stageId,
					"UF_CRM_DEAL_ASSIGNED" => self::$oldAssignedId,
				),
				'params' => array("REGISTER_SONET_EVENT" => "N")
			);
				
	self::log2file($data_deal,'-data_deal');
				
			$queryData = http_build_query($data_deal);				
			$r = self::post2crm($metod, $queryData);

			
		}		
	self::log2file('finish','-finish');		
	unset($event);
	
	}
	private static function getDealCrest($id,$orderId)
	{
		if($id == null)
		{
	//self::log2file('$id == null', '-getDealCrest--IN-FUNC');
	
			$deal = CRest::call(
				'crm.deal.list',
				[				
					'order' => ['ID' => 'ASC'],
					'filter' => [ 
									"UF_CRM_DEAL_ORDER" => (int)$orderId,
									"CATEGORY_ID" => 1,
								],
					'select' => [ "ID", "UF_CRM_DEAL_STAGE", "STAGE_ID", "ASSIGNED_BY_ID","UF_CRM_DEAL_ASSIGNED"]
				]
			);
			
	self::log2file($deal['result'][0]['ID'], '-getDealCrest-DEAL_ID--IN-FUNC');
			
			self::$dealId = $deal['result'][0]['ID'];
			//$order->getPropertyCollection()->getItemByOrderPropertyId(25)->setValue(self::$dealId);
			//$order->save();
			
			return $deal['result'][0];
		}
		else
		{
			
		$deal = CRest::call(
				'crm.deal.get',
				['ID' => (int)$id, ]
			);
	self::log2file('Получена Сделка'.$deal['result']['ID'] , '-getDealCrest-DEAL_ID--IN-FUNC');		
		return $deal['result'];
		
		}
	
	
	}
	
	private static function getOrderProps($orderId, $status)
	{
		//выборка по нескольким свойствам (например, по LOCATION и ADDRESS):
		$dbOrderProps = CSaleOrderPropsValue::GetList(
			array("SORT" => "ASC"),
			array("ORDER_ID" => $intOrderID, "CODE"=>array("DEAL_ID", "ADDRESS"))
		);
		while ($arOrderProps = $dbOrderProps->GetNext()):
	//self::log2file($arOrderProps, '-getOrderProps');
		endwhile;
		
	}
	
	private static function getStageId($status)
	{
		$statusImArr = array(
			"N"=>"Принят, ожидает подтверждения",
		    "G"=>"Подтвержден, ожидает оплаты",
		    "P"=>"Оплачен, ожидает получения",
		    "H"=>"Подтвержден, ожидает получения",
		    "F" =>"Выполнен",
		    "BA"=>"Отклонен АБС (BAD)",
		    "DC"=>"Отменен",
		    "NO"=>"Не принят АБС",
		    "OK"=>"Принят АБС",
		    "RJ"=>"Принят АБС (R)",
		    "UN"=>"Неизвестная ошибка",
		    "J"=>"Доставляется",
		);
		$statusStageArr = Array(
			"N"   => "C1:NEW",			//Принят, ожидает подтверждения	
			"G"   => "C1:PREPARATION", 	//Подтвержден, ожидает оплаты
			"P"   => "C1:EXECUTING", 	//Оплачен, ожидает получения
			"H"   => "C1:UC_H7F0CF",	//Подтвержден, ожидает получения
			"OK"  => "C1:1",			//Принят АБС
			"RJ"  => "C1:FINAL_INVOICE",//Принят АБС (R)Принят АБС
			"NO"  => "C1:LOSE",			//Не принят АБС
			"BA"  => "C1:2",			//Отклонен АБС (BAD)
			"DC"  => "C1:3",			//Отменен
			"UN"  => "C1:5",			//Неизвестная ошибка
			"J"   => "C1:4",			//Доставляется
			"F"   => "C1:UC_6BWG8U",	//Заказ доставлен и оплачен	
		);
		return $statusStageArr[$status];
	}
	private static function getOrder($orderId)
	{
	self::log2file('Запрос заказа', "-getOrder-$orderId-in--FUNC" );	
		return Sale\Order::load(self::$orderId);
	}
	
	private static function getDealId($order)
	{	
		$dealId = self::$order->getPropertyCollection()->getItemByOrderPropertyId(25)->getValue();
	self::log2file('Запрос id сделки по свойству заказа', "-getDealId-$dealId-in--FUNC" );
	
		return $dealId;
	}
	
	private static function getDelivery($order)
	{
		$shipmentCollection = $order->getShipmentCollection(); 
		$lastShipment = null;
		$store_name = "";
		
		foreach($shipmentCollection as $shipment)
		{
			$shipment_nameDirty = $shipment->getDeliveryName(); //тут имя доставки
			$pieces = explode("(", $shipment_nameDirty);
			$shipment_name = $pieces[0]; // а тут просто очистили от лишнего в скобочках
		}
		
	self::log2file($shipment_name, "-getDelivery-$shipment_name-in--FUNC" );		
		
		return $shipment_name;
	}
	
	private static function post2crm($metod, $queryData)
	{
		$queryUrl = "https://".self::DOMEN."/rest/1/".self::TOKEN."/$metod";
		
		// обращаемся к Битрикс24 при помощи функции curl_exec
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryData,
		));
		$result = curl_exec($curl);
		curl_close($curl);
	self::log2file($result, "-post2crm-$metod-in--FUNC" );		
		return $result;		
	}
	
	
	private static function log2file($arr, $fn = null, $folder=__DIR__.'/logStatusUpdate/')
	{  
		$dir = $folder;

		if(!file_exists($dir))
		{
			mkdir($dir, 0777, true);
		}	

		  $error = "";
		  $fn = $fn ? "-" . str_replace(['\\', '/', ' '], '', $fn) : "";
		  $fp = fopen($dir . date("Y-m-d H:i:s") .'-'. round(microtime(true) * 1000) . "-log2file{$fn}.log", "a");
		  $test = fwrite($fp, date("Y-m-d H:i:s") . "\r\n" . var_export($arr, true) . "\r\n");
		  

		  if (!$test) {
			$error = "Ошибка при записи в файл " . $dir . date("Y") . "-log2file{$fn}.log";
			$test = fwrite($fp, date("Y-m-d H:i:s") . ";" . $error . "\r\n");
		  }
		  fclose($fp);

		  return $error;
	}
}


?>