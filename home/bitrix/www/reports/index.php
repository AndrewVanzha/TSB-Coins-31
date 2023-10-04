<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("report");
?>
<?php
CModule::IncludeModule("sale");
use Bitrix\Sale;

// home/bitrix/www/local/php_interface/init.php
// home/bitrix/www/local/php_interface/events.php
// home/bitrix/www/reports/sale_report/admin_step.php
// home/bitrix/www/reports/sale_report/report_step.php

/*
function debugg($data)
{
    global $USER;
    if($USER->GetID() == 107) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
*/
$arFilter = Array();
$arOrders = array();
$ii = 0;
?>
Report
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
