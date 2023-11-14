<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);


?>
<div class="catalog-form-wrapper">
	<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
		<?foreach($arResult["HIDDEN"] as $arItem):?>
		<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
		<?endforeach;?>

		<div class="mobile-filters__left">
            <?foreach ($arResult["CUSTOM_DATA"]['CHECKBOXES'] as $key => $arItem) {?>
                <div class="filter-property-item">
                    <p class="filter-property__name"><?=$arItem['NAME']?></p>
                    <div class="filter-property__values">
                        <?foreach ($arItem["VALUES"] as $val => $ar) {?>
                        <div class="custom-checkbox-wrapper filter-property__value">
                            <input 
                            onChange="smartFilter.click(this)"
                            type="checkbox" 
                            value="<?=$ar["HTML_VALUE"]?>"
                            name="<?=$ar["CONTROL_NAME"]?>"
                            <?=($ar["CHECKED"]? 'checked="checked"': '')?>

                            class="filter-property__value-checkbox" 
                            id="<?=$ar["CONTROL_ID"]?>">
                            
                            <label 
                            data-role="label_<?=$ar["CONTROL_ID"]?>"
                            for="<?=$ar["CONTROL_ID"]?>" class="custom-checkbox checkbox-label">
                                <?=$ar["VALUE"];?>
                            </label>

                        </div>
                        <?}?>
                    </div>
                </div>
            <?}?>
        </div>

        <div class="mobile-filter__right">
            <?foreach ($arResult["CUSTOM_DATA"]['DROPDOWN'] as $key => $arItem) {?>
                <?$arCur = current($arItem["VALUES"]);?>
                <div class="filter-property-item select">
                    <select 
                    onChange="smartFilter.click(this)"
                    name="<?=$arItem["CODE"]; ?>" 
                    id="<?=$arItem["CODE"]; ?>">
                        <option 
                        id="not-value"
                        value="" 
                        ><?=$arItem['NAME'];?></option>
                        <?foreach ($arItem["VALUES"] as $val => $ar) {?>
                            <?if (!$ar["DISABLED"]) {?>
                                <option 
                                <?= $ar["CHECKED"] ? 'selected="selected"' : ''?>
                                id="<?echo $ar["CONTROL_NAME"]?>"
                                value="Y"><?=$ar["VALUE"];?></option>
                            <?}?>
                        <?}?>
                    </select>
                </div>
            <?}?>
            <input 
            type="submit"
            id="del_filter"
			name="del_filter"
            value="Сбросить" 
            class="mint-btn filled big reset-filters full-width">
        </div>

	</form>
</div>


<script type="text/javascript">
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>

<script>
    $(document).ready(function(){
        <? //Формируем дополнительные jquery обработчики для select-ов
        foreach($arResult["ITEMS"] as $key=>$arItem) {
            if(!empty($arItem["VALUES"]) && !isset($arItem["PRICE"])) {
            ?>
                //Проставляем name select-ам с уже выбранными свойствами
                setTimeout(function(){
                    var SelectOption = $("#<?=$arItem['CODE']; ?> option:selected").attr('id');
                    $("#<?=$arItem['CODE']; ?>").attr('name',SelectOption);
                },1500);
                //заполнение name с реакцией на change
                $("#<?=$arItem['CODE']; ?>").on('change', function(){
                    var SelectOption = $("#<?=$arItem['CODE']; ?> option:selected").attr('id');
                    $("#<?=$arItem['CODE']; ?>").attr('name',SelectOption);
                });
            <?
            }
        }?>
    });
</script>