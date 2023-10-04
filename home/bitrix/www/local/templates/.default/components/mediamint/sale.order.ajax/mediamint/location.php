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
    $location_item = \Bitrix\Sale\Location\LocationTable::getById($location_id)->fetch(); // получаю выбранное место доставки
    debugg($location_item);

    $res = \Bitrix\Sale\Location\LocationTable::getList(array(
        'filter' => array(
            '=ID' => array($location_item['REGION_ID']),
            '=PARENT.NAME.LANGUAGE_ID' => LANGUAGE_ID,
            '=PARENT.TYPE.NAME.LANGUAGE_ID' => LANGUAGE_ID,
            'TYPE_CODE' => 'COUNTRY_DISTRICT',
        ),
        'select' => array(
            'PARENT.*',
            'NAME_RU' => 'PARENT.NAME.NAME',
            'TYPE_CODE' => 'PARENT.TYPE.CODE',
            'TYPE_NAME_RU' => 'PARENT.TYPE.NAME.NAME',
        ),
    ));
    while($item = $res->fetch()) { // собираю магазины в регионе места доставки
        $region_item[] = $item;
    }
    debugg($region_item);
    debugg($region_item[0]['TYPE_NAME_RU']);
    debugg($region_item[0]['NAME_RU']);
    $arResult["REGION_ITEM"]['ID'] = 0; // нет магазина в регионе
    $arResult["REGION_ITEM"]['VALUE'] = $region_item[0]['NAME_RU']; // для shops.php
}
?>

<?// echo '<pre>'; print_r($arResult); echo '</pre>';?>
<?// debugg($arParams["TEMPLATE_LOCATION"]); ?>
<?// debugg($location); ?>

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
