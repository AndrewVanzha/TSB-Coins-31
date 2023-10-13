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
if($_GET["sort-link"] == "is_instock" || $_GET["sort-link"] == "") {
    $GLOBALS["arrFilter"] = array(
        ">catalog_PRICE_1" => 0,
        "=PROPERTY_AVAILABLE_VALUE" => 1
    );
}

$tabFilterLinks = [
    [
        'TEXT' => 'В наличии',
        'HREF' => $APPLICATION->GetCurPageParam("sort-link=is_instock",array("sort-link")),
        'ACTIVE' => ($_GET["sort-link"] == "is_instock" || $_GET["sort-link"] == "")
    ],
    [
        'TEXT' => 'Ожидаемые',
        'HREF' => $APPLICATION->GetCurPageParam("sort-link=is_expected",array("sort-link")),
        'ACTIVE' => ($_GET["sort-link"] == "is_expected")
    ],
    [
        'TEXT' => 'Новинки',
        'HREF' => $APPLICATION->GetCurPageParam("sort-link=is_new",array("sort-link")),
        'ACTIVE' => ($_GET["sort-link"] == "is_new")
    ],
    [
        'TEXT' => 'Все',
        'HREF' => $APPLICATION->GetCurPageParam("sort-link=is_all",array("sort-link")),
        'ACTIVE' => ($_GET["sort-link"] == "is_all")
    ],
];
debugg('sections');
global $arrFilter;
//debugg('$arrFilter');
//debugg($arrFilter);
//debugg($arResult);
?>
<div class="content-container">
    <h1 class="heading-1 catalog-section-name"><?
        if ($SEO_H1) {
            echo $SEO_H1;
        } else {
            $APPLICATION->ShowTitle(false);
        }
    ?></h1>

    <section class="section-tabs-sort-wrapper">
        <div class="catalog-sections-tabs">
            <?foreach ($tabFilterLinks as $keyTag => $arTag) {?>
                <a 
                href="<?=$arTag['HREF']?>" 
                class="catalog-section-tab <?=($arTag['ACTIVE'] ? 'active': '')?>">
                    <?=$arTag['TEXT']?>
                </a>
            <?}?>
        </div>  

        <div class="catalog-sort">
            <select 
            name="sort-product" 
            id="sort-product" 
            class="sort-type-select">
                <?foreach ($arParams["SORTS"] as $key => $sort):?>
                    <option 
                    <?=$sort["ACTIVE"] == 'Y' ? 'selected' : '';?>
                    value="<?=$sort["LINK"]?>"
                    ><?=$sort["LABEL"]?></option>
                <?endforeach;?>
            </select>
            <script>
                $( "#sort-product" ).change(function() {
                    window.location = $(this).val();
                });
            </script>

            <div class="render-options">
                <button 
                class="render-options__button <?= ( ($_COOKIE['view'] == "list") ? 'active' : '')?>" 
                data-render-type="list">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_2_5915)">
                            <path d="M17.1027 15.5366H5.06613C4.57004 15.5366 4.16797 15.1345 4.16797 14.6384C4.16797 14.1423 4.57004 13.7402 5.06613 13.7402H17.1024C17.5984 13.7402 18.0005 14.1423 18.0005 14.6384C18.0005 15.1345 17.5987 15.5366 17.1027 15.5366Z"/>
                            <path d="M17.1027 9.89788H5.06613C4.57004 9.89788 4.16797 9.4958 4.16797 8.99972C4.16797 8.50364 4.57004 8.10156 5.06613 8.10156H17.1024C17.5984 8.10156 18.0005 8.50364 18.0005 8.99972C18.0008 9.4958 17.5987 9.89788 17.1027 9.89788Z"/>
                            <path d="M17.1027 4.26116H5.06613C4.57004 4.26116 4.16797 3.85909 4.16797 3.363C4.16797 2.86692 4.57004 2.46484 5.06613 2.46484H17.1024C17.5984 2.46484 18.0005 2.86692 18.0005 3.363C18.0005 3.85909 17.5987 4.26116 17.1027 4.26116Z"/>
                            <path d="M1.20623 4.63902C1.87241 4.63902 2.41245 4.09897 2.41245 3.43279C2.41245 2.76661 1.87241 2.22656 1.20623 2.22656C0.540046 2.22656 0 2.76661 0 3.43279C0 4.09897 0.540046 4.63902 1.20623 4.63902Z" />
                            <path d="M1.20623 10.2054C1.87241 10.2054 2.41245 9.66538 2.41245 8.9992C2.41245 8.33301 1.87241 7.79297 1.20623 7.79297C0.540046 7.79297 0 8.33301 0 8.9992C0 9.66538 0.540046 10.2054 1.20623 10.2054Z" />
                            <path d="M1.20623 15.7757C1.87241 15.7757 2.41245 15.2357 2.41245 14.5695C2.41245 13.9033 1.87241 13.3633 1.20623 13.3633C0.540046 13.3633 0 13.9033 0 14.5695C0 15.2357 0.540046 15.7757 1.20623 15.7757Z" />
                        </g>
                        <defs>
                            <clipPath id="clip0_2_5915">
                            <rect width="18" height="18" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                </button>

                <button 
                class="render-options__button <?= ( ($_COOKIE['view'] == "table" || empty($_COOKIE['view'])) ? 'active' : '')?>" 
                data-render-type="grid">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.21534 0H2.08153C0.933785 0 0 0.933785 0 2.08153V6.21534C0 7.36309 0.933785 8.29688 2.08153 8.29688H6.21534C7.36309 8.29688 8.29688 7.36309 8.29688 6.21534V2.08153C8.29688 0.933785 7.36309 0 6.21534 0ZM6.89062 6.21534C6.89062 6.58768 6.58768 6.89062 6.21534 6.89062H2.08153C1.70919 6.89062 1.40625 6.58768 1.40625 6.21534V2.08153C1.40625 1.70919 1.70919 1.40625 2.08153 1.40625H6.21534C6.58768 1.40625 6.89062 1.70919 6.89062 2.08153V6.21534Z"/>
                        <path d="M15.8906 0H11.8125C10.6494 0 9.70312 0.946266 9.70312 2.10938V6.1875C9.70312 7.35061 10.6494 8.29688 11.8125 8.29688H15.8906C17.0537 8.29688 18 7.35061 18 6.1875V2.10938C18 0.946266 17.0537 0 15.8906 0ZM16.5938 6.1875C16.5938 6.5752 16.2783 6.89062 15.8906 6.89062H11.8125C11.4248 6.89062 11.1094 6.5752 11.1094 6.1875V2.10938C11.1094 1.72167 11.4248 1.40625 11.8125 1.40625H15.8906C16.2783 1.40625 16.5938 1.72167 16.5938 2.10938V6.1875Z"/>
                        <path d="M6.21534 9.29688H2.08153C0.933785 9.29688 0 10.2307 0 11.3784V15.5122C0 16.66 0.933785 17.5938 2.08153 17.5938H6.21534C7.36309 17.5938 8.29688 16.66 8.29688 15.5122V11.3784C8.29688 10.2307 7.36309 9.29688 6.21534 9.29688ZM6.89062 15.5122C6.89062 15.8846 6.58768 16.1875 6.21534 16.1875H2.08153C1.70919 16.1875 1.40625 15.8846 1.40625 15.5122V11.3784C1.40625 11.0061 1.70919 10.7031 2.08153 10.7031H6.21534C6.58768 10.7031 6.89062 11.0061 6.89062 11.3784V15.5122Z" />
                        <path d="M15.8906 9.29688H11.8125C10.6494 9.29688 9.70312 10.2431 9.70312 11.4062V15.4844C9.70312 16.6475 10.6494 17.5938 11.8125 17.5938H15.8906C17.0537 17.5938 18 16.6475 18 15.4844V11.4062C18 10.2431 17.0537 9.29688 15.8906 9.29688ZM16.5938 15.4844C16.5938 15.8721 16.2783 16.1875 15.8906 16.1875H11.8125C11.4248 16.1875 11.1094 15.8721 11.1094 15.4844V11.4062C11.1094 11.0185 11.4248 10.7031 11.8125 10.7031H15.8906C16.2783 10.7031 16.5938 11.0185 16.5938 11.4062V15.4844Z"/>
                    </svg>
                </button>

                <button id="open-mobile-filters" class="">
                    <svg class="open-filters" width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24.5413 25.4781C24.79 25.4781 25.0284 25.3794 25.2042 25.2035C25.38 25.0277 25.4788 24.7893 25.4788 24.5406V21.625C26.2382 21.4214 26.9091 20.9729 27.3877 20.3492C27.8662 19.7254 28.1256 18.9612 28.1256 18.175C28.1256 17.3888 27.8662 16.6246 27.3877 16.0008C26.9091 15.3771 26.2382 14.9286 25.4788 14.725V5.45313C25.4788 5.20448 25.38 4.96603 25.2042 4.79021C25.0284 4.6144 24.79 4.51562 24.5413 4.51562C24.2927 4.51562 24.0542 4.6144 23.8784 4.79021C23.7026 4.96603 23.6038 5.20448 23.6038 5.45313V14.7344C22.8445 14.938 22.1735 15.3864 21.695 16.0102C21.2164 16.634 20.957 17.3982 20.957 18.1844C20.957 18.9706 21.2164 19.7348 21.695 20.3585C22.1735 20.9823 22.8445 21.4308 23.6038 21.6344V24.55C23.6063 24.797 23.7061 25.0331 23.8817 25.2069C24.0572 25.3807 24.2943 25.4781 24.5413 25.4781ZM22.8257 18.175C22.8257 17.8357 22.9263 17.504 23.1148 17.2219C23.3033 16.9397 23.5713 16.7198 23.8848 16.59C24.1983 16.4601 24.5432 16.4261 24.876 16.4923C25.2088 16.5585 25.5145 16.7219 25.7544 16.9619C25.9944 17.2018 26.1578 17.5075 26.224 17.8403C26.2902 18.1731 26.2562 18.5181 26.1263 18.8315C25.9965 19.145 25.7766 19.413 25.4945 19.6015C25.2123 19.79 24.8806 19.8906 24.5413 19.8906C24.0863 19.8906 23.6499 19.7099 23.3282 19.3881C23.0064 19.0664 22.8257 18.63 22.8257 18.175Z" fill="#777777"/>
                        <path d="M15.0008 25.4801C15.2494 25.4801 15.4879 25.3814 15.6637 25.2055C15.8395 25.0297 15.9383 24.7913 15.9383 24.5426V11.5489C16.7785 11.3216 17.5075 10.7969 17.9898 10.0724C18.4721 9.34782 18.6748 8.47275 18.5601 7.60994C18.4455 6.74714 18.0214 5.95534 17.3667 5.38184C16.7119 4.80834 15.8712 4.49219 15.0008 4.49219C14.1304 4.49219 13.2896 4.80834 12.6349 5.38184C11.9802 5.95534 11.5561 6.74714 11.4414 7.60994C11.3268 8.47275 11.5295 9.34782 12.0118 10.0724C12.494 10.7969 13.2231 11.3216 14.0633 11.5489V24.5426C14.0633 24.7913 14.1621 25.0297 14.3379 25.2055C14.5137 25.3814 14.7521 25.4801 15.0008 25.4801ZM13.2852 8.10826C13.2833 7.76855 13.3823 7.43594 13.5697 7.15257C13.7571 6.86921 14.0244 6.64785 14.3377 6.51656C14.651 6.38527 14.9963 6.34996 15.3297 6.41509C15.6631 6.48023 15.9697 6.64288 16.2106 6.88244C16.4514 7.122 16.6158 7.42767 16.6827 7.76072C16.7497 8.09378 16.7163 8.43921 16.5867 8.75325C16.4571 9.06728 16.2372 9.33578 15.9549 9.52471C15.6726 9.71365 15.3405 9.81451 15.0008 9.81451C14.5474 9.81452 14.1124 9.63505 13.791 9.31534C13.4695 8.99562 13.2876 8.56165 13.2852 8.10826Z" fill="#777777"/>
                        <path d="M4.51788 18.4469V24.5406C4.51788 24.7893 4.61665 25.0277 4.79247 25.2035C4.96828 25.3794 5.20674 25.4781 5.45538 25.4781C5.70402 25.4781 5.94248 25.3794 6.11829 25.2035C6.29411 25.0277 6.39288 24.7893 6.39288 24.5406V18.4469C7.15224 18.2433 7.8232 17.7948 8.30174 17.171C8.78029 16.5473 9.03967 15.7831 9.03967 14.9969C9.03967 14.2107 8.78029 13.4465 8.30174 12.8227C7.8232 12.1989 7.15224 11.7505 6.39288 11.5469V5.45313C6.39288 5.20448 6.29411 4.96603 6.11829 4.79021C5.94248 4.6144 5.70402 4.51562 5.45538 4.51562C5.20674 4.51562 4.96828 4.6144 4.79247 4.79021C4.61665 4.96603 4.51788 5.20448 4.51788 5.45313V11.5469C3.75852 11.7505 3.08756 12.1989 2.60901 12.8227C2.13047 13.4465 1.87109 14.2107 1.87109 14.9969C1.87109 15.7831 2.13047 16.5473 2.60901 17.171C3.08756 17.7948 3.75852 18.2433 4.51788 18.4469ZM5.45538 13.2813C5.79509 13.2794 6.12771 13.3784 6.41107 13.5658C6.69443 13.7532 6.91579 14.0205 7.04708 14.3338C7.17837 14.6471 7.21369 14.9924 7.14855 15.3258C7.08341 15.6592 6.92076 15.9658 6.6812 16.2067C6.44165 16.4475 6.13597 16.6119 5.80292 16.6788C5.46987 16.7458 5.12443 16.7124 4.8104 16.5828C4.49636 16.4532 4.22786 16.2333 4.03893 15.951C3.85 15.6687 3.74913 15.3366 3.74913 14.9969C3.74912 14.5435 3.92859 14.1085 4.24831 13.787C4.56802 13.4656 5.00199 13.2837 5.45538 13.2813Z" fill="#777777"/>
                    </svg>

                    <svg class="close-filters" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 1.17838L18.8216 0L10 8.82162L1.17838 0L0 1.17838L8.82162 10L0 18.8216L1.17838 20L10 11.1784L18.8216 20L20 18.8216L11.1784 10L20 1.17838Z" fill="#00345E"/>
                    </svg>
                </button>
            </div>

        </div>
    </section>
    <section class="catalog-content-wrapper">
        <aside class="catalog-filter">
        <?//debugg($arrFilter);
        //если 0 то не выводим умный фильтр
        if ($count > 0) { ?>
            <?/*--- Умный фильтр (модифицированный) ---*/?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.smart.filter.modef",
                "filter.mm",
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
        </aside> 

        <? //global $arrFilter;
        //debugg('$arrFilter');
        //debugg($arrFilter); ?>

        <section class="catalog-coins-wrapper">
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

            
        </section>


</div>



