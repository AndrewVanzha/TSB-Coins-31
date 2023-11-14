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

#Получаем количество активных элементов в инфоблоке
$sect = CIBlockSection::GetList(Array("sort" => "asc", "name" => "asc"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "GLOBAL_ACTIVE" => "Y", "CNT_ACTIVE" => true), true, array("NAME"));

while($el = $sect->Fetch()) {
    $count += $el["ELEMENT_CNT"];
}

$price_sort_desc = 'catalog_PRICE_1-desc';
$price_sort_asc = 'catalog_PRICE_1-asc';

#варианты сортировки
$arSorts = array(
    "sort-desc"                 => "По умолчанию",
    "shows-desc"                => "По популярности",
    $price_sort_desc            => "По цене (дороже)",
    $price_sort_asc             => "По цене (дешевле)",
    "name-asc"                  => "По названию (возр.)",
    "name-desc"                 => "По названию (убыв.)",
);

$sort = array_key_exists( $_REQUEST["sort"], $arSorts ) ? $_REQUEST["sort"] : "sort-desc";
$sort_masiv = explode("-", $sort);

$arParams["SORTS"] = array();

if(count($arSorts) > 0) {
    foreach($arSorts as $key => $value){

        $sort_variant = array(
            "CODE"   => $key,
            "NAME"   => $value,
            "LABEL"  => $value,
            "ACTIVE" => $key == $sort_masiv[0].'-'.$sort_masiv[1] ? "Y" : "N"
        );
        $sort_variant["LINK"] = $APPLICATION->GetCurPageParam(
            "sort=".$key,array("sort")
        );

        $arParams["SORTS"][] = $sort_variant;
    }
}

#Получаем GET параметры для фильтрации (все, ожидаемые, новинки)
if ($_GET["sort-link"] == "is_expected") {
    $GLOBALS["arrFilter"] = array("<=catalog_PRICE_1" => 0);
}
if ($_GET["sort-link"] == "is_new") {
    $GLOBALS["arrFilter"] = array("=PROPERTY_NEW_PRODUCT_VALUE" => 1);
}


$this->SetViewTarget("filterLinks");
?>
<div class="left">
    <div class="filter-btn">
        <a href="<?=$arResult["FOLDER"]?>" <?=( ($_GET["sort-link"] == "") ? 'class="is-active"' : '') ?>>Все</a>
        <a href="<?=$APPLICATION->GetCurPageParam("sort-link=is_expected",array("sort-link"))?>" <?=( ($_GET["sort-link"] == "is_expected") ? 'class="is-active"' : '') ?>>Ожидаемые</a>
        <a href="<?=$APPLICATION->GetCurPageParam("sort-link=is_new",array("sort-link"))?>" <?=( ($_GET["sort-link"] == "is_new") ? 'class="is-active"' : '') ?>>Новинки</a>
    </div>
</div>
<?
$this->EndViewTarget();
?>
<div class="content-block-2">
    <div class="filter-wrap">
        <div class="block clearfix">
            <div class="title">
                <h1 class="aligner"><?=$APPLICATION->ShowTitle(false);?></h1>
                <div class="number aligner">( <?=$count?> )</div>
            </div>
            <div class="controls-wrap clearfix">
                <? if(count($arParams["SORTS"]) > 0) { ?>
                    <div class="size size-1">
                        <select name="sort-product" id="sort-product">
                            <? foreach ($arParams["SORTS"] as $key => $sort) {
                                if ($sort["ACTIVE"] == 'Y') $selected = " selected";
                                else $selected = "";
                                ?>
                                <option value="<?=$sort["LINK"]?>" <?=$selected?>><?=$sort["LABEL"]?></option>
                            <? } ?>
                        </select>
                    </div>
                    <script>
                        $( "#sort-product" ).change(function() {
                            window.location = $(this).val();
                        });
                    </script>
                <? } ?>
                <div class="controls-view-wrap">
                    <div class="controls-view list <?= ( ($_COOKIE['view'] == "list") ? 'active' : '')?>"></div>
                    <div class="controls-view table <?= ( ($_COOKIE['view'] == "table" || empty($_COOKIE['view'])) ? 'active' : '')?>"></div>
                </div>
            </div>
        </div>
        <?
        //если 0 то не выводим умный фильтр
        if ($count > 0) { ?>
            <?/*--- Умный фильтр (модифицированный) ---*/?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.smart.filter.modef",
                "filter",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => "",
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "SAVE_IN_SESSION" => "N",
                    "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                    "XML_EXPORT" => "Y",
                    "SECTION_TITLE" => "NAME",
                    "SECTION_DESCRIPTION" => "DESCRIPTION",
                    'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                    "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    "SEF_MODE" => "N",
                    "SEF_RULE" => "",
                    "SMART_FILTER_PATH" => "",
                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                    "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                    "DISPLAY_ELEMENT_COUNT" => "N",
                    "SHOW_ALL_WO_SECTION" => "Y"
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            );?>
        <? } ?>
    </div>
    <?$intSectionID = $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "",
        array(
            "FILTER_NAME" => "arrFilter",
            "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],

            "ELEMENT_SORT_FIELD" => !empty($sort_masiv[0]) ? $sort_masiv[0] : "sort",
            "ELEMENT_SORT_ORDER" => count($sort_masiv)>1 ? $sort_masiv[1] : "desc",

            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
            "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
            "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
            "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
            "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
            "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
            "SET_LAST_MODIFIED" => "N",
            "INCLUDE_SUBSECTIONS" => "Y",
            "BASKET_URL" => $arParams["BASKET_URL"],
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "SET_TITLE" => $arParams["SET_TITLE"],
            "MESSAGE_404" => $arParams["MESSAGE_404"],
            "SET_STATUS_404" => $arParams["SET_STATUS_404"],
            "SHOW_404" => $arParams["SHOW_404"],
            "FILE_404" => $arParams["FILE_404"],
            "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
            "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
            "PRICE_CODE" => $arParams["PRICE_CODE"],
            "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
            "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
            "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
            "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

            "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
            "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
            "PAGER_TITLE" => $arParams["PAGER_TITLE"],
            "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
            "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
            "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
            "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
            "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
            "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],

            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
            "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
            "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
            "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
            "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
            "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
            "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
            "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

            "SECTION_ID" => "",
            "SECTION_CODE" => "",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
            'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

            'LABEL_PROP' => $arParams['LABEL_PROP'],
            'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
            'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

            'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
            'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
            'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
            'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
            'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
            'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
            'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
            'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

            'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
            "ADD_SECTIONS_CHAIN" => "N",
            "ADD_TO_BASKET_ACTION" => "ADD",
            'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
            "BACKGROUND_IMAGE" => "-",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
            "SHOW_ALL_WO_SECTION" => "Y",
        ),
        $component
    );
    $GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;
    ?>
</div>



