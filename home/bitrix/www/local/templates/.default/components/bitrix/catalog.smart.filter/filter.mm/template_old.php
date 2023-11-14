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

<div class="mobile-filter">Фильтр</div>
<div class="filter-toggle">
    <div class="bx-filter <?=$templateData["TEMPLATE_CLASS"]?> <?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL") echo "bx-filter-horizontal"?>">
        <form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
            <?foreach($arResult["HIDDEN"] as $arItem):?>
                <input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
            <?endforeach;?>
            <div class="block">
                <div class="row">
                    <?
                    //not prices
                    foreach($arResult["ITEMS"] as $key=>$arItem) {
                    if (
                        empty($arItem["VALUES"])
                        || isset($arItem["PRICE"])
                    )
                        continue;

                    if (
                        $arItem["DISPLAY_TYPE"] == "A"
                        && (
                            $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
                        )
                    )
                        continue;
                    ?>

                    <div class="<? if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"): ?>col-sm-6 col-md-4<?
                    else: ?>col-lg-12<?endif ?> bx-filter-parameters-box <? if ($arItem["DISPLAY_EXPANDED"] == "Y"): ?>bx-active<?endif ?>">
                        <div class="bx-filter-block" data-role="bx_filter_block">
                                <?
                                $arCur = current($arItem["VALUES"]);
                                switch ($arItem["DISPLAY_TYPE"]) {
                                case "B"://NUMBERS
                                ?>
                                    <div class="col-xs-6 bx-filter-parameters-box-container-block bx-left">
                                        <i class="bx-ft-sub"><?= GetMessage("CT_BCSF_FILTER_FROM") ?></i>
                                        <div class="bx-filter-input-container">
                                            <input
                                                    class="min-price"
                                                    type="text"
                                                    name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                                    id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                                    value="<? echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                                    size="5"
                                                    onkeyup="smartFilter.keyup(this)"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-xs-6 bx-filter-parameters-box-container-block bx-right">
                                        <i class="bx-ft-sub"><?= GetMessage("CT_BCSF_FILTER_TO") ?></i>
                                        <div class="bx-filter-input-container">
                                            <input
                                                    class="max-price"
                                                    type="text"
                                                    name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                                    id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                                    value="<? echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                                    size="5"
                                                    onkeyup="smartFilter.keyup(this)"
                                            />
                                        </div>
                                    </div>
                                <?
                                break;

                                case "P"://DROPDOWN
                                    $checkedItemExist = false;

                                    if ($arItem["CODE"] == "NOMINAL" || $arItem["CODE"] == "RELEASE_YEAR" ||  $arItem["CODE"] == "PROBA" ||  $arItem["CODE"] == "SET" || $arItem["CODE"] == "METAL") $size = 2;
                                    else $size = 2;

                                    if ($arItem["CODE"] == "RELEASE_YEAR") $arItem["NAME"] = "Год";
                                    ?>
                                    <div class="size size-<?=$size?>">
                                        <select id="<?=$arItem["CODE"]; ?>" name="<?=$arItem["CODE"]; ?>" onChange="smartFilter.clickSelect(this)" >
                                        <option id="not-value" value="" ><?echo $arItem["NAME"];?></option>
                                        <?foreach($arItem["VALUES"] as $val => $ar) {
                                            if (!$ar["DISABLED"]) { ?>
                                                <option <?= $ar["CHECKED"] ? 'selected="selected"' : ''?> id="<?echo $ar["CONTROL_NAME"]?>" value="Y" ><?echo $ar["VALUE"];?></option>
                                            <? } ?>
                                        <? }?>
                                        </select>
                                    </div>
                                <?
                                break;
                                default://DROPDOWN
                                    $checkedItemExist = false;

                                    if ($arItem["CODE"] == "NOMINAL" || $arItem["CODE"] == "RELEASE_YEAR" ||  $arItem["CODE"] == "PROBA" ||  $arItem["CODE"] == "SET" || $arItem["CODE"] == "METAL") $size = 2;
                                    else $size = 1;

                                    if ($arItem["CODE"] == "RELEASE_YEAR") $arItem["NAME"] = "Год";
                                    ?>
                                        <div class="size size-<?=$size?>">
                                            <select id="<?=$arItem["CODE"]; ?>" name="<?=$arItem["CODE"]; ?>" onChange="smartFilter.clickSelect(this)" >
                                                <option id="not-value" value="" ><?echo $arItem["NAME"];?></option>
                                                <?foreach($arItem["VALUES"] as $val => $ar) {
                                                    if (!$ar["DISABLED"]) { ?>
                                                        <option <?= $ar["CHECKED"] ? 'selected="selected"' : ''?> id="<?echo $ar["CONTROL_NAME"]?>" value="Y" ><?echo $ar["VALUE"];?></option>
                                                    <? } ?>
                                                <? }?>
                                            </select>
                                        </div>
                            <?
                                }
                                ?>

                        </div>
                    </div>
                        <?
                    }
                    ?>
                </div>
            </div>
            <div class="block clearfix">

                <?$APPLICATION->ShowViewContent('filterLinks');?>

                <div class="right">
                    <div class="bx-filter-popup-result <?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
                        <?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
                        <span class="arrow"></span>
                        <br/>
                        <a href="<?echo $arResult["FILTER_URL"]?>" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
                    </div>
                    <input
                            type="submit"
                            id="del_filter"
                            name="del_filter"
                            сlass="button aligner"
                            value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>"
                    />
                    <?/*
                        <input
                            type="submit"
                            id="set_filter"
                            name="set_filter"
                            class="button aligner"
                            value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"
                    />
                    */?>
                </div>
            </div>
        </form>
    </div>
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