<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");
//debugg($templateFolder);
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
