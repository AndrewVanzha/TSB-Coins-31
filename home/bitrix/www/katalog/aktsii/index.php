<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Купить монеты со скидкой. Акция на монеты России и мира | ТрансСтройБанк");
$APPLICATION->SetTitle("Акции");

if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/local/include/product_sale.php"))
    require_once $_SERVER['DOCUMENT_ROOT'] . "/local/include/product_sale.php";


if (count($GLOBALS["arrFilter"]) > 0) {
    $price_sort_desc = 'catalog_PRICE_1-desc';
    $price_sort_asc = 'catalog_PRICE_1-asc';

    #варианты сортировки
    $arSorts = array(
        "sort-desc" => "По умолчанию",
        "shows-desc" => "По популярности",
        $price_sort_desc => "По цене (дороже)",
        $price_sort_asc => "По цене (дешевле)",
        "name-asc" => "По названию (возр.)",
        "name-desc" => "По названию (убыв.)",
    );

    $sort = array_key_exists($_REQUEST["sort"], $arSorts) ? $_REQUEST["sort"] : "sort-desc";
    $sort_masiv = explode("-", $sort);
    ?>
    <?/*--- Товары со скидкой ---*/ ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "products_sale",
        Array(
            "ACTION_VARIABLE" => "action",
            "ADD_PICT_PROP" => "MORE_PHOTO",
            "ADD_PROPERTIES_TO_BASKET" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "ADD_TO_BASKET_ACTION" => "ADD",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "BACKGROUND_IMAGE" => "-",
            "BASKET_URL" => "/personal/cart/",
            "BROWSER_TITLE" => "-",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "COMPATIBLE_MODE" => "Y",
            "CONVERT_CURRENCY" => "N",
            "CUSTOM_FILTER" => "",
            "DETAIL_URL" => "/katalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
            "DISCOUNT_PERCENT_POSITION" => "bottom-right",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "DISPLAY_COMPARE" => "N",
            "DISPLAY_TOP_PAGER" => "N",

            "ELEMENT_SORT_FIELD" => !empty($sort_masiv[0]) ? $sort_masiv[0] : "sort",
            "ELEMENT_SORT_ORDER" => count($sort_masiv) > 1 ? $sort_masiv[1] : "desc",
            "SORTS_SHOW_ELEMENTS" => $arSorts,

            "ELEMENT_SORT_FIELD2" => "",
            "ELEMENT_SORT_ORDER2" => "",
            "ENLARGE_PRODUCT" => "STRICT",
            "FILTER_NAME" => "arrFilter",
            "HIDE_NOT_AVAILABLE" => "N",
            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
            "IBLOCK_ID" => "6",
            "IBLOCK_TYPE" => "catalog",
            "INCLUDE_SUBSECTIONS" => "Y",
            "LABEL_PROP" => array(),
            "LAZY_LOAD" => "N",
            "LINE_ELEMENT_COUNT" => "3",
            "LOAD_ON_SCROLL" => "N",
            "MESSAGE_404" => "Y",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "META_DESCRIPTION" => "-",
            "META_KEYWORDS" => "-",
            "OFFERS_LIMIT" => "5",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => ".default",
            "PAGER_TITLE" => "Товары",
            "PAGE_ELEMENT_COUNT" => "8",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRICE_CODE" => array("BASE"),
            "PRICE_VAT_INCLUDE" => "Y",
            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_PROPERTIES" => array(""),
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PRODUCT_QUANTITY_VARIABLE" => "quant",
            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
            "PRODUCT_SUBSCRIPTION" => "Y",
            "PROPERTY_CODE" => array(
                0 => "RELEASE_YEAR",
                1 => "METAL",
                2 => "PROBA",
                3 => "QUALITY",
                4 => "",
            ),
            "PROPERTY_CODE_MOBILE" => array(),
            "RCM_PROD_ID" => "",
            "RCM_TYPE" => "personal",
            "SECTION_CODE" => "",
            "SECTION_CODE_PATH" => "",
            "SECTION_ID" => "",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "SECTION_URL" => "/katalog/#SECTION_CODE_PATH#/",
            "SECTION_USER_FIELDS" => array("", ""),
            "SEF_MODE" => "Y",
            "SEF_RULE" => "",
            "SET_BROWSER_TITLE" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "Y",
            "SET_META_KEYWORDS" => "N",
            "SET_STATUS_404" => "Y",
            "SET_TITLE" => "N",
            "SHOW_404" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "SHOW_CLOSE_POPUP" => "N",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_FROM_SECTION" => "N",
            "SHOW_MAX_QUANTITY" => "N",
            "SHOW_OLD_PRICE" => "Y",
            "SHOW_PRICE_COUNT" => "1",
            "SHOW_SLIDER" => "Y",
            "SLIDER_INTERVAL" => "3000",
            "SLIDER_PROGRESS" => "N",
            "TEMPLATE_THEME" => "blue",
            "USE_ENHANCED_ECOMMERCE" => "N",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            "USE_PRICE_COUNT" => "N",
            "USE_PRODUCT_QUANTITY" => "Y"
        )
    ); ?>

<?
}  else {
    echo '<h1>'.$APPLICATION->ShowTitle().'</h1>';
    echo '<h2>Нет действующих акций</h2>';
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>