<?
// https://bazarow.ru/blog-note/14128/
// https://nikaverro.ru/blog/bitrix/sale-order-bitrix-api-d7/
// https://burlaka.studio/lab/order_basket_and_so_so/
// https://maxyss.ru/blog/Work_code/zakaz-na-d7-poluchit-polya-izmenit-polya-sokhranit/
// https://know-online.com/post/bitrix-order

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle("Генератор отчета по заказам");
CJSCore::Init(array("jquery"));
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use Bitrix\Sale;
Loader::includeModule("sale");
Loader::includeModule('iblock');
//$generated_xls_php = $_SERVER["DOCUMENT_ROOT"] . '/reports/sale_report/generated.xls.php';
$generated_xls_php = $_SERVER["DOCUMENT_ROOT"] . '/reports/sale_report/generated.xls';
//print_r($generated_xls_php);
?>
<?
if (!empty($_POST['lastOrderId'])) {
    $arFilter = array(
        ">=DATE_INSERT" => $_POST['dateFrom'],
        "<=DATE_INSERT" => $_POST['dateTo'],
        ">ID" => $_POST['lastOrderId']
    );
} else {
    $arFilter = array(
        ">=DATE_INSERT" => $_POST['dateFrom'],
        "<=DATE_INSERT" => $_POST['dateTo']
    );
    $vfile = file_put_contents($generated_xls_php, '');
    //file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/reports/a_test.json', json_encode($generated_xls_php));

    /*$fileHeader = '<?
Header("Content-Type: application/force-download");
Header("Content-Type: application/octet-stream");
Header("Content-Type: application/download");
Header("Content-Disposition: attachment;filename=excel_orders.xls");
Header("Content-Transfer-Encoding: binary");
?>*/
    $fileHeader = '<?
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        td {
            mso-number-format: \@;
        }
        .number0 {
            mso-number-format: 0;
        }
        .number2 {
            mso-number-format: Fixed;
        }
    </style>
</head>
<body>

<table border="1">
    <tr>
        <td>Дата</td>
        <td>ID заказа</td>
        <td>Сумма</td>
        <td>ФИО покупателя</td>
        <td>Email покупателя</td>
        <td>Статус</td>
        <td>Доставка</td>
        <td>Артикул товара</td>
        <td>Количество товара</td>
        <td>Цена товара</td>
        <td>Наименование товара</td>
    </tr>';
    /*
    <tr>
        <td>ID заказа</td>
        <td>ID товара</td>
        <td>Наименование товара</td>
        <td>Наименование [ID] товара</td>
        <td>Заказ: Доставка</td>
        <td>Оплата: Дата оплаты</td>
        <td>Оплата: Сумма</td>
        <td>Отгрузка: Дата отгрузки</td>
        <td>Общее количество в заказе</td>
        <td>Цена товара</td>
        <td>Скидка на товар</td>
        <td>Количество товара</td>
        <td>Сумма товара</td>
        <td>Налог (%)</td>
        <td>Оплата: Дата докуммента возврата</td>
        <td>Статус: Наименование</td>
        <td>Заказ: Заказ отменён</td>
    </tr>';
     */
    $vfile = file_put_contents($generated_xls_php, $fileHeader, FILE_APPEND);
}
$ii = 0;

$arSaleStatus = [];
$statusResult = \Bitrix\Sale\Internals\StatusLangTable::getList(array(
    'order' => array('STATUS.SORT'=>'ASC'),
    'filter' => array('STATUS.TYPE'=>'O','LID'=>LANGUAGE_ID),
    'select' => array('STATUS_ID','NAME','DESCRIPTION'),
));
while($status = $statusResult->fetch()) {
    $arSaleStatus[] = $status; // получаю массив статусов с расшифровкой
}
//echo '<pre>';print_r($arSaleStatus);echo '</pre>';

$arOrders = [];
$db_res_orders = \Bitrix\Sale\Order::getList([ // получаю список заказов с полями
    'select' => ['ID', 'PRICE', 'CURRENCY', 'STATUS_ID', 'DELIVERY_ID', 'PRICE_DELIVERY', 'USER_ID', 'PAY_SYSTEM_ID'],
    'filter' => $arFilter,
]);
while ($order = $db_res_orders->fetch()) {
    $arOrders[] = $order;
}
//echo '<pre>';print_r($arOrders);echo '</pre>';

foreach ($arOrders as $kk=>$order) {
    $orderCollection = \Bitrix\Sale\Order::load($order['ID']);
    //echo '<pre>';print_r($orderCollection);echo '</pre>';

    $db_res_items = \Bitrix\Sale\Basket::getList([
        'select' => ['PRODUCT_ID', 'NAME', 'PRICE', 'CURRENCY', 'QUANTITY'],
        'filter' => ['=ORDER_ID' => $order['ID'],],
    ]);
    $orderItems = [];
    while ($item = $db_res_items->fetch()) {
        $orderItems[] = $item;
    }
    $arOrders[$kk]['ORDER'] = $orderItems;
    unset($orderItems);

    //$basket = $orderCollection->getBasket();  // другой вариант извлечения товара из заказа - полный список
    //$basketProductFields = [];
    //foreach ($basket->getBasketItems() as $bi) {
    //    $basketProductFields[] = $bi->getFieldValues();
    //}
    //$arOrders[$kk]['ORDER'] = $basketProductFields;
    //unset($basketProductFields);

    //$order['USER_NAME'] . $order['USER_LAST_NAME']
    //$order['USER_EMAIL']

    $propCollection = $orderCollection->getPropertyCollection();
    $arOrders[$kk]['USER_NAME'] = $propCollection->getPayerName()->getValue();
    $arOrders[$kk]['USER_EMAIL'] = $propCollection->getUserEmail()->getValue();

    $arOrders[$kk]['DATE_INSERT_FORMAT'] = $orderCollection->getDateInsert()->toString(); // получаю дату создания заказа

    $key = array_search($order['STATUS_ID'], $arSaleStatus);  // расшифровка статуса заказа
    $arOrders[$kk]['STATUS'] = $arSaleStatus[$key]['NAME'];

    $shipmentCollection = $orderCollection->getShipmentCollection();
    foreach($shipmentCollection as $shipment)
    {
        $shipment_nameDirty = $shipment->getDeliveryName(); // получаю имя доставки
        $arOrders[$kk]['SHIPMENT'][] = $shipment_nameDirty;
    }
}
//echo '<pre>';print_r($arOrders);echo '</pre>';

//file_put_contents("/home/bitrix/www" . '/reports/a_$arOrders.json', json_encode($arOrders));
$className = \Bitrix\Iblock\Iblock::wakeUp(6)->getEntityDataClass(); // Каталог монет
//echo '<pre>';print_r($className);echo '</pre>';
\Bitrix\Iblock\Iblock::wakeUp(6)->getEntityDataClass(); // Каталог монет

//$arProducts = [];
//$arProds = [];
foreach ($arOrders as $kk=>$order) {
    //echo '<pre>';print_r($order);echo '</pre>';
    foreach ($order['ORDER'] as $jj=>$item) {
        //echo '<pre>';print_r($item['PRODUCT_ID']);echo '</pre>';
        //$db_res_product = \Bitrix\Iblock\Elements\ElementPhpapiTable::getList([ // ElementPhpapiTable не работает

        $db_res_product = \Bitrix\Iblock\Elements\ElementCatalogBlockTable::getList([ // API = CatalogBlock
            //'select' => ['ID', 'NAME', 'ARTICLE', 'COLLECTION', 'CATALOGUE_NUM', 'MANAFACTURER', 'SET_COINS'],
            'select' => [
                'ARTICLE_' => 'ARTICLE',
                'MANAFACTURER_' => 'MANAFACTURER',
            ],
            'filter' => ['=ACTIVE' => 'Y', 'ID' => $item['PRODUCT_ID']],
        ])->fetchAll();
        //])->fetchObject();
        //$arOrders[$kk]['ORDER'][$jj]['PRODUCT'] = $db_res_product->getArticle()->getValue();

        foreach ($db_res_product as $key=>$product) {
            //$arProducts[] = $product;
            $arOrders[$kk]['ORDER'][$jj]['PRODUCT'] = $product;
        }
        //echo '<pre>';print_r($arProducts);echo '</pre>';

        $rs_prod = CIBlockElement::GetList(
            array(),
            array('IBLOCK_ID' => 6, 'ID' => $item['PRODUCT_ID'], 'ACTIVE' => 'Y'),
            false,
            false,
            array('PROPERTY_ARTICLE', 'PROPERTY_MANAFACTURER')
        );
        while ($ar_prod = $rs_prod->Fetch()) {
            $arOrders[$kk]['ORDER'][$jj]['PROPS'] = $ar_prod;
            $arProds[] = $ar_prod;
        }
    }

}
//echo '<pre>';print_r($arOrders);echo '</pre>';

/*
$arOrder = []; // старый вариант
$arSelect = [
    'ID',
    'PAY_SYSTEM_ID',
    'DELIVERY_ID',
    'USER_ID',
    'PRICE_DELIVERY',
    'PRICE',
    'CURRENCY',
    'TAX_VALUE',
    'SUM_PAID',
    'DATE_INSERT_FORMAT',
    'USER_LOGIN',
    'USER_NAME',
    'USER_LAST_NAME',
    'USER_EMAIL',
    'STATUS_ID',
];
$dbRes = CSaleOrder::GetList(
    array('ID' => 'ASC'),
    $arFilter,
    false,
    array(),
    //array('nTopCount' => '50'),
    $arSelect,
    //array(),
    false,
);
while ($order = $dbRes->Fetch()) {
    $arOrder[$order['ID']]['ID'] = $order['ID'];
    $arOrder[$order['ID']]['PRICE_DELIVERY'] = $order['PRICE_DELIVERY'];
    $arOrder[$order['ID']]['DATE_PAYED'] = $order['DATE_PAYED'];
    $arOrder[$order['ID']]['SUM_PAID'] = $order['SUM_PAID'];
    $arOrder[$order['ID']]['STATUS_ID'] = $order['STATUS_ID'];
}
//echo '<pre>';print_r($arOrder);echo '</pre>';

foreach ($arOrder as $order) {
    //echo '<pre>';print_r($order['ID']);echo '</pre>';
    $arBasketItems = [];
    $dbBasketItems = CSaleBasket::GetList(
        array("NAME" => "ASC",),
        array("ORDER_ID" => $order['ID']),
        false,
        false,
        array("ORDER_ID", "PRODUCT_ID", "NAME", "QUANTITY", "PRICE", "DISCOUNT_VALUE"),
        //array(),
    );
    while ($arItems = $dbBasketItems->Fetch()) {
        $arBasketItems[] = $arItems;
    }
    //if ($order['CANCELED'] == 'Y') {
    //    $canceled = 'Да';
    //}
    //echo '<pre>';print_r($arBasketItems);echo '</pre>';

    $arOrder[$order['ID']]['ORDER'] = $arBasketItems;
    //foreach ($arBasketItems as $ii=>$item) {
    //    $arOrder[$order['ID']]['ORDER'][] = $item;
    //}

    $statusList = CSaleStatus::GetList(
        array(),
        array('ID' => $order['STATUS_ID']),
        false,
        false,
        array('NAME')
    );
    while ($status = $statusList->Fetch()) {
        $statusName = $status['NAME'];
    }
    //echo '<pre>';print_r($statusName);echo '</pre>';
    $arOrder[$order['ID']]['STATUS'] = $statusName;

    //echo '<pre>';print_r($arOrder);echo '</pre>';
    $lastOrderId = $order['ID'];
    unset($arBasketItems);
}
//echo '<pre>';print_r($arOrder);echo '</pre>';
*/

// формирую вывод в excel-таблицу
$orderData = '';
foreach ($arOrders as $order) {
    $orderData = '<tr>
                    <td>' . $order['DATE_INSERT_FORMAT'] . '</td>
                    <td>' . $order['ID'] . '</td>
                    <td>' . $order['PRICE'] . '</td>
                    <td>' . $order['USER_NAME'] . ' ' . $order['USER_LAST_NAME'] . '</td>
                    <td>' . $order['USER_EMAIL'] . '</td>
                    <td>' . $order['STATUS'] . '</td>
                    <td>' . $order['SHIPMENT'][0] . '</td>';
    foreach ($order['ORDER'] as $kk=>$item) {
        if (count($order['ORDER'])>1 && $kk>0) {
            $orderData .= '<tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>';
        }
        $orderData .=  '<td>' . $item['PRODUCT']['ARTICLE_VALUE'] . '</td>
                        <td>' . $item['QUANTITY'] . '</td>
                        <td>' . $item['PRICE'] . '</td>
                        <td>' . $item['NAME'] . '</td>
                      </tr>';
    }
    file_put_contents($generated_xls_php, $orderData, FILE_APPEND);
}
file_put_contents($generated_xls_php, '</table></body></html>', FILE_APPEND);

/*
if ($ii > 1) { // пошаговый ?>
    <form action="" method="post" id="postStep" style="display: none;">
        <input type="text" name="lastOrderId" value="<?= $lastOrderId ?>">
        <input type="text" name="dateFrom" value="<?= $_POST['dateFrom'] ?>">
        <input type="text" name="dateTo" value="<?= $_POST['dateTo'] ?>">
        <button type="submit"></button>
    </form>
    <div class="waitwindow" style="width: 500px;">
        Отчет готовится с шагом <?=$ii?> заказов за запрос и паузой 1 секунда
    </div>
    <script>
        function postForm() {
            $("#postStep").submit();
        }
        setTimeout(postForm, 1000);
    </script>
    <?php
} else {
    file_put_contents($generated_xls_php, '</table></body></html>', FILE_APPEND);?>
    <?/*?><a href="generated.xls.php" class="adm-btn adm-btn-save">Скачать отчет</a><?*/?><?/*?>
    <a href="generated.xls" class="adm-btn adm-btn-save" download>Скачать отчет</a>
    <a href="admin_step.php" class="adm-btn adm-btn-save">Создать новый отчет</a>
    <?php
    print_r($generated_xls_php);
    print_r(' $vfile= ');
    print_r($vfile);
    //LocalRedirect('generated.xls.php');
}*/
?>
    <a href="generated.xls" class="adm-btn adm-btn-save" download>Скачать отчет</a>
    <a href="admin_step.php" class="adm-btn adm-btn-save">Создать новый отчет</a>
<?php
require($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog_admin.php"); ?>