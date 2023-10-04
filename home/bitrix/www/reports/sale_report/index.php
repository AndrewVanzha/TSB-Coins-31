<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("report");
?>
<?php
CModule::IncludeModule("sale");
use Bitrix\Sale;

function debugg($data)
{
    global $USER;
    if($USER->GetID() == 107) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

$arFilter = Array();
$arOrders = array();
$ii = 0;
?>
Report
<?php
$db_sales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arFilter);
while ($ar_sales = $db_sales->Fetch()) {
    $basket = Sale\Order::load($ar_sales['ID'])->getBasket();
    $basketItems = $basket->getBasketItems();
    $counter = 0;
    debugg($ii);
    debugg($ar_sales);
    $arOrders[$ii] = $ar_sales;
    $ii += 1;
}
debugg($arOrders);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
