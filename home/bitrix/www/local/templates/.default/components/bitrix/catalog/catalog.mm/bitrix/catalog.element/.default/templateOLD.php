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

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BASIS_PRICE' => $strMainID.'_basis_price',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'BASKET_ACTIONS' => $strMainID.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);

$tile_empty = $this->GetFolder().'/images/tile-empty.png';
if ($arResult['DETAIL_PICTURE']['SRC']){
	$img = $arResult['DETAIL_PICTURE']['SRC'];
}
else{
	$img = "";
}
/** --- Цена товаров --- **/
$minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);

$printPrice = $minPrice['PRINT_DISCOUNT_VALUE'];
$printPrice_old = $minPrice['PRINT_VALUE'];
$price = $minPrice['DISCOUNT_VALUE'];
$price_old = $minPrice['VALUE'];
$sale = false;
$sale_percent = $minPrice['DISCOUNT_DIFF_PERCENT'];

if (($price != $price_old) && ($price > 0 || $sale_percent > 0)) $sale = true;

?>
<script>
    var arJSParams_<?=$arResult["ID"]?> = [];
    arJSParams_<?=$arResult["ID"]?> ["ID"] = '<?=$arResult['ID']?>';
</script>

<div class="link-back"><a href="<?=$arResult["SECTION"]["SECTION_PAGE_URL"]?>">Назад к списку монет</a></div>
<div itemscope itemtype="http://schema.org/Product">
<div class="content-block-2">
    <div class="content-product-wrap clearfix">
        <div class="content-product">
            <div class="product-slider">
                <div class="slider-content">

                    <? if (!empty($img)) { ?>
                        <div class="item">
                            <a href="<?=$img?>" data-fancybox="gallery">
                                <img itemprop="image" src="<?=$img?>" alt="<?=$arResult["NAME"]?>">
                            </a>
                        </div>
                    <? } ?>
                    <? foreach ($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $key => $img_id) { ?>
                        <div class="item">
                            <a href="<?=CFile::GetPath($img_id)?>" data-fancybox="gallery">
                                <img src="<?=CFile::GetPath($img_id)?>" alt="<?=$arResult["PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"][$key]?>">
                            </a>
                        </div>
                    <? } ?>
                </div>
                <div class="slider-thumbs">
                    <? if (!empty($img)) { ?>
                        <div class="item">
                            <img src="<?=$img?>" alt="<?=$arResult["NAME"]?>">
                        </div>
                    <? } ?>
                    <? foreach ($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $key => $img_id) { ?>
                        <div class="item">
                            <img src="<?=CFile::GetPath($img_id)?>" alt="<?=$arResult["PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"][$key]?>">
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
        <div class="content-product-info">
            <h1 itemprop="name"><?=$arResult['NAME']?></h1>
            <div class="product-top clearfix">
                <div class="social-product">
                    <div class="label aligner">Поделиться ссылкой</div>
                    <div class="aligner">
                       <? //<a target="_blank" href="https://www.facebook.com/Transstroybank/" class="item-2">facebook</a>
                        //<a target="_blank" href="https://www.instagram.com/coins.tsbnk/" class="item-3">vk</a>?>
                         <?$APPLICATION->IncludeComponent(
                            "api:yashare",
                            "",
                            Array(
                                "DATA_DESCRIPTION" => "",
                                "DATA_IMAGE" => "",
                                "DATA_TITLE" => "",
                                "DATA_URL" => "",
                                "LANG" => "ru",
                                "QUICKSERVICES" => array("vkontakte","facebook"),
                                "SHARE_SERVICES" => array(),
                                "SIZE" => "m",
                                "TYPE" => "icon",
                                "UNUSED_CSS" => "N",
                                "twitter_hashtags" => ""
                            )
                        );?>
                    </div>
                </div>
                <div class="reviews-link"><a href="#reviews" class="icon-2">Мнения экспертов</a></div>
            </div>
            <div class="block">
                <? if ($price > 0) { ?>
                    <div class="product-price">
                        <div class="price aligner" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span itemprop="price"><?=$printPrice?></span><span style="display: none;" itemprop="priceCurrency">RUB</span></div>
                        <? if ($sale) { ?>
                            <div class="price-old aligner"><?=$printPrice_old?></div>
                        <? } ?>
                    </div>
                    <? if ($arResult["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1) { ?>
                        <div class="stock">в наличии</div>
                    <? } else { ?>
                        <div class="stock">под заказ</div>
                    <? } ?>
                <? } ?>
            </div>
            <div class="block">
                <? /*<div class="title-note">Специальная микрогравировка на высоком рельефе</div>*/ ?>
                <?
                if (!empty($arResult["PROPERTIES"]["NOMINAL"]["VALUE"])){
                    echo '<div>';
                    echo 'Номинал: '.$arResult["PROPERTIES"]["NOMINAL"]["VALUE"];
                    if (!empty($arResult["PROPERTIES"]["NAME_MONEY_MARK_LITERAL"]["VALUE"])) {
                        echo ' '.$arResult["PROPERTIES"]["NAME_MONEY_MARK_LITERAL"]["VALUE"];
                    }
                    echo '</div>';
                }

                foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty) {
                    echo '<div>';
                    echo $arProperty["NAME"].': ';
                    if (is_array($arProperty["DISPLAY_VALUE"])) {
                        echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
                    }
                    else {
                        if ($arProperty["DISPLAY_VALUE"] == 'true') {
                            $arProperty["DISPLAY_VALUE"] = 'Eсть';
                        }
                        elseif ($arProperty["DISPLAY_VALUE"] == 'false') {
                            $arProperty["DISPLAY_VALUE"] = 'Нет';
                        }
                        echo $arProperty["DISPLAY_VALUE"];
                    }
                    echo '</div>';
                }?>
            </div>
            <div class="product-action">
                <?/*--- Если юбил монеты ---*/?>
                <? if ($arResult["IBLOCK_SECTION_ID"] == 18) { ?>
                    <div class="info-hover-text">
                                    Размен возможен в <a href="https://coins.tsbnk.ru/dostavka-i-oplata/" target="_blank">офисах банка</a>
                    </div>
                <? } else { ?>
                    <? if ($price > 0 && $arResult["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1) { ?>
                        <div class="aligner adap_none">
                            <div class="count">
                                <button type="button" class="count-minus" onclick="countMinusProduct(this, arJSParams_<?=$arResult["ID"]?>)">-</button>
                                <input class="count-num" type="text" value="1" onchange="changeCountProduct(this, arJSParams_<?=$arResult["ID"]?>)">
                                <button type="button" class="count-plus" onclick="countPlusProduct(this, arJSParams_<?=$arResult["ID"]?>)">+</button>
                            </div>
                        </div>
                        <div class="aligner adap_none">
                            <a id="cart_<?=$arResult["ID"]?>" href="javascript:void(0);" class="button" onclick="addToCartProduct('<?=$arResult["ID"]?>', 1)">Добавить в корзину</a>
                        </div>

                        <a data-fancybox data-src="#popup-buy-2" href="javascript:void();" class="button adap_but">Оформить заказ</a>

                    <? } else { ?>
                        <div class="aligner">
                            <a data-fancybox="" data-src="#popup-subscription" href="javascript:;" class="product-link">Узнать о поступлении</a>
                        </div>
                    <? } ?>
                <? } ?>
            </div>
            <div class="product-action">
                <div class="aligner">
                    <?
                    /** --- Избранное --- **/
                    if (!empty($_SESSION["LIKED_PRODUCTS"][$arResult["ID"]] )) {
                        echo '<a href="javascript:void(0);" onclick="deleted_liked(this,'.$arResult["ID"].')" class="favorite icon is-active" title="Удалить из отложенного">В отложенное</a>';
                    }
                    else {
                        echo '<a href="javascript:void(0);" onclick="add_liked(this,'.$arResult["ID"].')" class="favorite icon" title="Добавить в отложенное">В отложенное</a>';
                    }
                    ?>
                </div>
                <? if ($arResult["IBLOCK_SECTION_ID"] == 18) { ?>
                    <div class="aligner">
                        <a data-fancybox data-src="#popup-exchange" href="javascript:void();" class="button">Разменять</a>
                    </div>
                <? } else { ?>
                    <? if ($price > 0 && $arResult["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1) { ?>
                        <div class="aligner adap_none" >
                            <a data-fancybox data-src="#popup-buy" href="javascript:void();" class="button">Заказать звонок</a>
                        </div>
                    <? } ?>
                <? } ?>
            </div>
			<?if(strlen($arResult["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"]) > 0):?>
            <div class="product-action">
                <div class="redemption-price">
                        <p class='caption'>Хотите продать?</p>
                        <p>Цена выкупа: <span class="r-price"><?=number_format($arResult["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"], 0, ".", " ")?> руб.</span></p>
                        <a data-fancybox data-src="#popup-redemption-price" href="javascript:void();" class="button">Продать</a>
                </div>
            </div>
            <?endif?>
            <?
            /*
             <div class="product-action">
                <div class="note icon-2">Бесплатная доставка при заказе от 10 000 р</div>
             </div>
             */
            ?>
        </div>
    </div>
</div>
<? if (!empty($arResult["DETAIL_TEXT"])) { ?>
    <div class="content-block-2">
        <div class="heading">Описание монеты</div>
        <div itemprop="description"><?=$arResult["DETAIL_TEXT"]?></div>
    </div>
<? } ?>
</div>
<?

//sdtcode ** openGraph
$ogDescription = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"];
$ogDescriptionShow = $ogDescription ? $ogDescription : 'Купить золотые и серебряные монеты России и мира в интернет-магазине «ТрансСтройБанк». Юбилейные и инвестиционные монеты в Москве и России. Серии монет из золота и серебра для коллекции.';
$APPLICATION->AddHeadString('<meta property="og:title" content="'.$arResult["NAME"].'" />');
$APPLICATION->AddHeadString('<meta property="og:description" content="'.$ogDescriptionShow.'" />');
$APPLICATION->AddHeadString('<meta property="og:url" content="http://coins.tsbnk.ru'.$arResult["DETAIL_PAGE_URL"].'" />');
$APPLICATION->AddHeadString('<meta property="og:type" content="catalog" />');
if(!empty($arResult["PREVIEW_PICTURE"]["SRC"]))
{
    $APPLICATION->AddHeadString('<meta property="og:image" content="http://coins.tsbnk.ru'.$arResult["PREVIEW_PICTURE"]["SRC"].'" />');
}
//end sdtcode
?>

<?/*--- Запись товара в просмотренные ---*/?>
<script type="text/javascript">
    var viewedCounter = {
        path: '/bitrix/components/bitrix/catalog.element/ajax.php',
        params: {
            AJAX: 'Y',
            SITE_ID: "<?= SITE_ID ?>",
            PRODUCT_ID: "<?= $arResult['ID'] ?>",
            PARENT_ID: "<?= $arResult['ID'] ?>"
        }
    };
    BX.ready(
        BX.defer(function(){
            BX.ajax.post(
                viewedCounter.path,
                viewedCounter.params
            );
        })
    );
</script>