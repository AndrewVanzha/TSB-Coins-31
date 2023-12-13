<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
use \Bitrix\Main,
    \Bitrix\Main\Localization\Loc as Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Currency,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Sale\Affiliate,
    Bitrix\Sale\DiscountCouponsManager,
    Bitrix\Main\Context;

Bitrix\Main\Loader::includeModule("sale");
DiscountCouponsManager::init();
?>


<?
function debugg($data)
{ echo '<pre>' . print_r($data, 1) . '</pre>'; }

if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD']=='POST')) {
    $mess_form_apply  = trim(htmlspecialchars($_POST["form_apply"])); // form_apply
    debugg($mess_form_apply);

    $myDate = new \Bitrix\Main\Type\Date();
    $myDate->add('-1M');
    $clientID = 3883;
    $arOrders = [];

    $parameters = [
        'filter' => [
            //"USER_ID" => $arClient['ID'], // $USER->GetID(),
            ">=DATE_INSERT" => $myDate,
        ],
        'order' => ["DATE_INSERT" => "ASC"]
    ];

    $dbRes = \Bitrix\Sale\Order::getList($parameters);
    $ii = 0;
    while ($order = $dbRes->fetch()) {
        //debugg($order);
        $arOrders[$ii]['CLIENT_ID'] = $order['USER_ID'];
        $arOrders[$ii]['ID'] = $order['ID'];
        $arOrders[$ii]['DATE_DEDUCTED'] = $order['DATE_DEDUCTED']; // Дата отгрузки заказа
        $arOrders[$ii]['DATE_UPDATE'] = $order['DATE_UPDATE']; // Дата последнего изменения заказа
        $arOrders[$ii]['PAYED'] = $order['PAYED'];
        $arOrders[$ii]['PRICE'] = $order['PRICE'];
        $arOrders[$ii]['STATUS_ID'] = $order['STATUS_ID']; // Код статуса заказа
        $arOrders[$ii]['MARKED'] = $order['MARKED']; // Флаг проблемности заказа (Y/N)
        $arOrders[$ii]['SUM_PAID'] = $order['SUM_PAID'];
        $arOrders[$ii]['CANCELED'] = $order['CANCELED']; // Флаг отмены заказа
        $arOrders[$ii]['RESPONSIBLE_LAST_NAME'] = $order['RESPONSIBLE_LAST_NAME']; // Фамилия ответственного за заказ пользователя
        //$arOrders[$ii]['USER_LAST_NAME'] = $order['USER_LAST_NAME']; // Фамилия пользователя, за кем закреплен заказ

        $ii += 1;
    }
    //debugg($arOrders);

    foreach ($arOrders as $kk=>$arItem) {
        //debugg($arItem);
        $rsUser = CUser::GetByID($arItem['CLIENT_ID']);
        $arUser = $rsUser->Fetch();
        //debugg($arUser);
        $arOrders[$kk]['CLIENT_NAME'] = $arUser['NAME'];
        $arOrders[$kk]['CLIENT_LAST_NAME'] = $arUser['LAST_NAME'];
        $arOrders[$kk]['CLIENT_EMAIL'] = $arUser['EMAIL'];
        $arOrders[$kk]['CLIENT_PHONE'] = $arUser['PERSONAL_PHONE'];
    }

}
?>
<style>
    .table th, td {
        border: 1px solid grey;
        text-align: right;
    }
    .table th {
        text-align: center;
    }
</style>

<??>
<table style="border: 1px solid grey;" class="table">
    <tr>
        <th>Client</th>
        <th>Client name</th>
        <th>Client phone</th>
        <th>Client email</th>
        <th>Order</th>
        <th>Shipped</th>
        <th>Paid</th>
        <th>Order sum</th>
        <th>Order date</th>
        <th>Rejection</th>
        <th>Status</th>
        <th>Problems</th>
    </tr>
    <? foreach ($arOrders as $arItem) : ?>
        <tr>
            <td><?=$arItem['CLIENT_ID']?></td>
            <td><?=$arItem['CLIENT_NAME']?></td>
            <td><?=$arItem['CLIENT_PHONE']?></td>
            <td><?=$arItem['CLIENT_EMAIL']?></td>
            <td><?=$arItem['ID']?></td>
            <td><?=$arItem['DATE_DEDUCTED']?></td>
            <td><?=$arItem['PAYED']?></td>
            <td><?=$arItem['PRICE']?></td>
            <td><?=$arItem['DATE_UPDATE']?></td>
            <td><?=$arItem['CANCELED']?></td>
            <td><?=$arItem['STATUS_ID']?></td>
            <td><?=$arItem['MARKED']?></td>
        </tr>
    <? endforeach; ?>
</table>
<??>
<?
$file="demo.xls";

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
?>