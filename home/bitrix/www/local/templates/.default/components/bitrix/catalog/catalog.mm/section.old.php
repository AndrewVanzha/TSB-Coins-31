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
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);

if (!isset($arParams['FILTER_VIEW_MODE']) || (string)$arParams['FILTER_VIEW_MODE'] == '')
    $arParams['FILTER_VIEW_MODE'] = 'VERTICAL';

$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');
$isFilter = ($arParams['USE_FILTER'] == 'Y');

if ($isFilter)
{
    $arFilter = array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ACTIVE" => "Y",
        "GLOBAL_ACTIVE" => "Y",
    );
    if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
        $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
    elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
        $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];

    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
    {
        $arCurSection = $obCache->GetVars();
    }
    elseif ($obCache->StartDataCache())
    {
        $arCurSection = array();
        if (Loader::includeModule("iblock"))
        {
            $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                if ($arCurSection = $dbRes->Fetch())
                    $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);

                $CACHE_MANAGER->EndTagCache();
            }
            else
            {
                if(!$arCurSection = $dbRes->Fetch())
                    $arCurSection = array();
            }
        }
        $obCache->EndDataCache($arCurSection);
    }
    if (!isset($arCurSection))
        $arCurSection = array();
}

#Получаем количество активных элементов в разделе
$count = CIBlockSection::GetSectionElementsCount($arCurSection["ID"], Array("CNT_ACTIVE"=>"Y"));

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
            <a href="<?=$arResult["FOLDER"].$arResult["VARIABLES"]["SECTION_CODE_PATH"].'/'?>" <?=( ($_GET["sort-link"] == "") ? 'class="is-active"' : '') ?>>Все</a>
            <a href="<?=$APPLICATION->GetCurPageParam("sort-link=is_expected",array("sort-link"))?>" <?=( ($_GET["sort-link"] == "is_expected") ? 'class="is-active"' : '') ?>>Ожидаемые</a>
            <a href="<?=$APPLICATION->GetCurPageParam("sort-link=is_new",array("sort-link"))?>" <?=( ($_GET["sort-link"] == "is_new") ? 'class="is-active"' : '') ?>>Новинки</a>
        </div>
    </div>
    <?
$this->EndViewTarget();



$filter = Array(
    "IBLOCK_ID"=>$arParams["IBLOCK_ID"], 
    "ID"=>$arCurSection['ID'], 
    "ACTIVE"=>"Y", 
    "IBLOCK_ACTIVE"=>"Y", 
    "GLOBAL_ACTIVE"=>"Y"
);
$rsResult = CIBlockSection::GetList(array("SORT" => "ASC"), $filter  , false, $arSelect = array( "UF_*"));
$res_array = CIBlockSection::GetByID($arCurSection['ID']);
$section_list = $res_array->GetNext();
$SEO_H1 = "";
while ($arParam = $rsResult->Fetch()){ $SEO_H1 = $arParam['UF_SEO_H1']; }


?>
<div class="content-block-2">
    <div class="filter-wrap">
        <div class="block clearfix">
            <div class="title">

                <? if ($SEO_H1) 
                    {?>
                        <h2 class="aligner" style="color:rgb(1, 45, 107);font-size: 25px;font: 25px/1.1 'ProximaNovaCond-Bold', sans-serif;text-transform: uppercase;vertical-align: bottom;"><?
                            echo $SEO_H1;?>
                        </h2><?
                    } else 
                        {?>
                        <h1 class="aligner">
                            <?$APPLICATION->ShowTitle(false);?>
                        </h1><?
                        }
                ?>
                <div class="number aligner">( <?=$count?> )</div>
            </div>

            <?
            /*--- Список разделов ---*/
            // $APPLICATION->IncludeComponent(
            //     "bitrix:catalog.section.list",
            //     "",
            //     array(
            //         "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            //         "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            //         "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
            //         "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
            //         "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            //         "CACHE_TIME" => $arParams["CACHE_TIME"],
            //         "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            //         "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
            //         "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
            //         "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            //         "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
            //         "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
            //         "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
            //         "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
            //     ),
            //     $component,
            //     array("HIDE_ICONS" => "Y")
            // );
            ?>

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

            <?/*--- Если инностранные монеты ---*/?>
            <? if ($arCurSection['ID'] == 17) { ?>
                <div class="banner-box">
                    <a href="/kollektsii/" title="Коллекции">
                        <img src="/assets/images/banner-1.jpg" alt="Коллекции"></a>
                </div>
                <div class="collection-link">
                    <a href="/kollektsii/" class="button" title="Коллекции">Коллекции</a>
                </div>
            <? } ?>
        </div>
        <?
        //если 0 то выводим умный фильтр
        if ($isFilter && $count > 0) {?>

            <?/*--- Умный фильтр ---*/?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.smart.filter",
                "filter",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => $arCurSection['ID'],
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "PRICE_CODE" => "",
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
                    "SEF_MODE" => $arParams["SEF_MODE"],
                    "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
                    "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                    "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            );?>
        <? } ?>
        <?// sdt code ** добавление разделов для механизма тегирования
        $APPLICATION->IncludeComponent(
            "webtu:section.filter.add",
            "",
            Array(
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "PARENT_SECTION_ID" => $arCurSection['ID'],
                "PROP_CODE" => ""
            )
        );?>
    </div>
    <?
    $intSectionID = $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        ".default",
        array(
            "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],

            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],

            "ELEMENT_SORT_FIELD" => !empty($sort_masiv[0]) ? $sort_masiv[0] : "sort",
            "ELEMENT_SORT_ORDER" => count($sort_masiv)>1 ? $sort_masiv[1] : "desc",

            "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
            "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
            "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
            "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
            "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
            "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
            "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
            "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
            "BASKET_URL" => $arParams["BASKET_URL"],
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
            "FILTER_NAME" => "arrFilter",
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

            "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
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
            "ADD_SECTIONS_CHAIN" => "Y",
            'ADD_TO_BASKET_ACTION' => $basketAction,
            'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
            'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
            'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
            'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : '')
        ),
        $component
    );
    if ($SEO_H1) 
    {?>
        <h1 style="margin: 20px 0 20px 0"><?$APPLICATION->ShowTitle(false);?></h1><?
    }
    // sdt code ** SEO-текст для разделов
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            "tagsContent",
            array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                "ADD_SECTIONS_CHAIN" => "N"
            ),
            $component,
            array("HIDE_ICONS" => "Y")
        );
        // sdt code ** вывод разделов для механизма тегирования
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            "tags",
            array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                "ADD_SECTIONS_CHAIN" => "N"
            ),
            $component,
            array("HIDE_ICONS" => "Y")
        );
        // end sdt code?>
</div>

<?
$GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;
unset($basketAction);
?>

