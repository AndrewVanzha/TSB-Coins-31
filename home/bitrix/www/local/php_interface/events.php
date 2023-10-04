<?php
//https://tichiy.ru/wiki/rabota-s-zakazom-bitriks-na-d7/

use Bitrix\Main;
use \Bitrix\Sale;
use \Bitrix\Sale\Order;

include_once(__DIR__.'/vendor/crest/crest.php');

$eventManager = \Bitrix\Main\EventManager::getInstance();

//$eventManager->addEventHandlerCompatible(
$eventManager->addEventHandler(
    'sale',

    //'OnSaleOrderBeforeSaved',
    //'OnOrderAdd',
    'OnSaleOrderSaved',
    //'OnSaleStatusOrderChange',

    ['PushToB24', 'exportOrder']
    //['\\Crm\\PushToB24', 'exportOrder']
    //[\Crm\PushToB24::class, 'exportOrder']
    //[PushToB24i::class, 'exportOrder']
    //'specFunction'
);

/*
$eventManager->addEventHandler( // исходный код

    'sale', 
    //'OnSaleOrderBeforeSaved',
	//'OnOrderAdd',	
    'OnSaleOrderSaved', 

	['ExportToB24', 'exportOrder']

); 
*/
$eventManager->addEventHandler(

    'sale',
	
    //'OnSalePayOrder',
	//'OnSaleDeliveryOrder',	
	//'OnSaleCancelOrder',	
	//'OnOrderUpdate',	
    //'OnStatusUpdate', //после изменения статуса заказа не срабатывает при изменении статуса заказа
	//'OnSaleStatusOrder',	//после изменения статуса заказа
	
    'OnSaleStatusOrderChange',
	
	['UpdateStatusB24', 'updateOrder']
	
	
	//function($orderId, $status)
	/*
	function( $event )
	{
		$parameters = $event->getParameters();
    //if ($parameters['VALUE'] === 'F')
    //{
     
	  $status = $parameters['VALUE'];
	  //Можно по имени с помощью $event->getParameter('NAME')
	  $statusName = $parameters['NAME'];
	  
      $order  = $parameters['ENTITY'];
	  $orderID = $order->getField("ID"); // ID заказа
	  
	  $deliveryID = $order->getField("DELIVERY_ID"); // ID заказа
	  $payStatus = $order->getField("PAYED"); //* Статус оплаты (Y/N)
	  $deducate = $order->getField("DEDUCTED"); //* Отгрузка заказ
	  $xmlID = $order->getField("XML_ID");
	  
$propertyCollection = $order->getPropertyCollection();	  	
$dealId = $propertyCollection->getItemByOrderPropertyId(25)->getValue();
	  
	  
      ob_start();
		echo PHP_EOL."<pre>".PHP_EOL;
		echo date("Y.m.d G:i:s") . "\n";
		echo "-------status--------- \n";
			var_dump($status) . "\n";
		echo "-------orderID--------- \n";
			var_dump($orderID) . "\n";
		echo "-------dealId--------- \n";
			var_dump($dealId) . "\n";
		echo "-------deliveryID--------- \n";
			var_dump($deliveryID) . "\n";
		echo "-------payStatus--------- \n";
		    var_dump($payStatus) . "\n";
		echo "-------deducate--------- \n";
			var_dump($deducate) . "\n";
		echo "-------xmlID--------- \n";
			var_dump($xmlID) . "\n";		
		echo PHP_EOL."<pre>".PHP_EOL;
		file_put_contents(__DIR__ . '/EventsStatusUpdate.log', ob_get_clean(), FILE_APPEND);
    //}
		   return new \Bitrix\Main\EventResult(
      \Bitrix\Main\EventResult::SUCCESS);
	}
	*/

);

unset($eventManager);


// организовать поиск только по названиям
AddEventHandler("search", "BeforeIndex", array("SearchHandlers", "BeforeIndexHandler"));
class SearchHandlers
{
    function BeforeIndexHandler($arFields)
    {
        if($arFields["MODULE_ID"] == "iblock")
        {
            if(array_key_exists("BODY", $arFields) && substr($arFields["ITEM_ID"], 0, 1) != "S") // Только для элементов
            {
                $arFields["BODY"] = "";
            }

            if (substr($arFields["ITEM_ID"], 0, 1) == "S") // Только для разделов
            {
                $arFields['TITLE'] = "";
                $arFields["BODY"] = "";
                $arFields['TAGS'] = "";
            }
        }

        return $arFields;
    }
}

?>