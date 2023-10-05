<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");
//debugg($templateFolder);
debugg('location');
//debugg($arResult["ORDER_PROP"]["USER_PROPS_Y"][6]);
//debugg($arResult["ORDER_PROP"]["USER_PROPS_N"]['VARIANTS']);
?>

<?// debugg($arResult["ORDER_PROP"]["USER_PROPS_Y"]); ?>
<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $k => $v):?>
    <? if ($v['NAME'] == 'Индекс') { // пропускаю поле Индекс
        continue;
    } ?>
	<?if($v["PROPS_GROUP_ID"] == 3):?>
		<?$location[$v["ID"]] = $v;?>
		<?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
			<?$location[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
		<?endif;?>
	<?endif;?>
<?endforeach;?>

<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $k => $v):?>
	<?if($v["PROPS_GROUP_ID"] == 3):?>
		<?$location[$v["ID"]] = $v;?>
		<?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
			<?$location[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
		<?endif;?>
	<?endif;?>
<?endforeach;?>

<?usort($location, function($a, $b){
	return ($a['SORT'] - $b['SORT']);
});?>

<?php
$arLocalShops = [];
if (\Bitrix\Main\Loader::includeModule('sale')) {
    //debugg($_REQUEST);
    //debugg($_POST);

    $moscow_id = 129;
    if ($_REQUEST['ORDER_PROP_6']) {
        $location_id = $_REQUEST['ORDER_PROP_6'];
    } else {
        $location_id = $moscow_id;
    }
    debugg('$location_id');
    debugg($location_id);
    //$item = \Bitrix\Sale\Location\TypeTable::getById($_REQUEST['ORDER_PROP_6'])->fetch();
    $res = \Bitrix\Sale\Location\LocationTable::getList(array(
        'filter' => array(
            '=ID' => $location_id,
            '=NAME.LANGUAGE_ID' => LANGUAGE_ID,
        ),
        'select' => array(
            '*',
            'NAME_RU' => 'NAME.NAME',
            'TYPE_CODE' => 'TYPE.CODE'
        ),
    ));
    while($item = $res->fetch()) { // для shops.php
        //debugg($item);
        $arResult["CITY_PLACE"]['ID'] = $item['CITY_ID'];
        $arResult["CITY_PLACE"]['VALUE'] = $item['NAME_RU'];
        $arResult["CITY_PLACE"]['TYPE'] = $item['TYPE_CODE'];  // CITY
    }
    debugg($arResult["CITY_PLACE"]);

// Инфоблок 23 Адреса магазинов:
// добавить символьный код API shopAddresses
// добавить свойство Город / ATT_CITY (Строка)
// заполнить свойство Город во всех элементах инфоблока

    $elements = \Bitrix\Iblock\Elements\ElementShopAddressesTable::getList([ // API = ShopAddresses
        'select' => ['ID', 'NAME', 'ATT_CITY', 'SORT'],
        'filter' => [
            '=ACTIVE' => 'Y',
            'IBLOCK_ID' => 23,
        ],
        'order' => ['SORT' => 'ASC'],
    ])->fetchAll();
    foreach ($elements as $key=>$element) {
        //debugg($element);
        if ($element['IBLOCK_ELEMENTS_ELEMENT_SHOP_ADDRESSES_ATT_CITY_VALUE']) {
            $el_city['ID'] = $element['ID'];
            $el_city['CITY'] = $element['IBLOCK_ELEMENTS_ELEMENT_SHOP_ADDRESSES_ATT_CITY_VALUE'];
            $el_city['ADDRESS'] = $element['NAME'];
            $arResult['CITY_ADDRESSES'][] = $el_city;
        }
        //debugg($element->getShopAddresses()->getValue());
    }
    //debugg($arResult['CITY_ADDRESSES']);

    /*
     * в class.php
        $ar_regions = [];
        $ar_regions[4] = 'Центр';  //  переносить вручную из польз.свойства UF_SHOP_REGIONS (Магазин / Склады)
        $ar_regions[5] = 'Северо-Запад';
        $ar_regions[6] = 'Юг';
        $ar_regions[7] = 'Поволжье';
        $ar_regions[8] = 'Дальний Восток';
        $arResult["REGION_ITEM"]['ID'] = 4;
        $arResult["REGION_ITEM"]['VALUE'] = $ar_regions[4];


    $location_item = \Bitrix\Sale\Location\LocationTable::getById($location_id)->fetch(); // получаю выбранное место доставки
    debugg($location_item);
    $res = \Bitrix\Sale\Location\LocationTable::getList(array(  // нахожу регион выбранного города
        'filter' => array(
            '=ID' => array($location_item['REGION_ID']),
            '=PARENT.NAME.LANGUAGE_ID' => LANGUAGE_ID,
            '=PARENT.TYPE.NAME.LANGUAGE_ID' => LANGUAGE_ID,
            'TYPE_CODE' => 'COUNTRY_DISTRICT',
        ),
        'select' => array(
            'ID',
            'CODE',
            'PARENT.*',
            'NAME_RU' => 'PARENT.NAME.NAME',
            'TYPE_CODE' => 'PARENT.TYPE.CODE',
            'TYPE_NAME_RU' => 'PARENT.TYPE.NAME.NAME',
        ),
    ));
    while($item = $res->fetch()) { // собираю магазины в регионе места доставки
        $region_item[] = $item;
    }
    //debugg($region_item);
    //debugg($region_item[0]['TYPE_NAME_RU']);
    //debugg($region_item[0]['NAME_RU']);
    $arResult["REGION_ITEM"]['ID'] = 0; // нет магазина в регионе
    $arResult["REGION_ITEM"]['VALUE'] = $region_item[0]['NAME_RU']; // для shops.php
    */
}
//debugg($location);
?>

<?// echo '<pre>'; print_r($arResult); echo '</pre>';?>
<?// debugg($arParams["TEMPLATE_LOCATION"]); ?>

<div class = "ajorder-section">
	<h4 class = "ajorder-section_header">Регион доставки</h4>
	<div class = "ajorder-section-inner">
		<div class = "ajorder-flex-inputs">
			<?PrintPropsForm($location, $arParams["TEMPLATE_LOCATION"]);?>
		</div>
		<div class = "ajorder-section-after_text">
			Выберите свой город в списке. Если вы не нашли свой город, выберите "Другое местоположение", а город впишите в комментарий к заказу. Наши менеджеры свяжутся с вами и рассчитают стоимость доставки.
		</div>
	</div>
</div>
