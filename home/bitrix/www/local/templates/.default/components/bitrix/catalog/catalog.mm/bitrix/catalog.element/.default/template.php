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

global $USER;
$currUserId = $USER->GetID();
function getUserDesired($userId) {
    if ($userId) {
        $rsUser = CUser::GetByID($userId);
        $arUser = $rsUser->Fetch(); 
        $desiredArr =  $arUser['UF_DESIRED'] ? explode(',', $arUser['UF_DESIRED']) : [];
        return $desiredArr;
    } else {
        $desiredArr = $_COOKIE['desired'] ? explode(',', $_COOKIE['desired']) : [];
        return $desiredArr;
    }
}
$epxloded = explode(':', $_SERVER[HTTP_HOST])[0];
$uri = $APPLICATION->GetCurPage();
$current_link = "https://$epxloded$uri";
$shareURL = "https://vk.com/share.php?url=".urlencode($current_link)."&title=".urlencode($arResult['NAME'])."&utm_source=share2";
$desiredArr = getUserDesired($currUserId);
$liked = in_array($arResult['ID'], $desiredArr);

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
if (!$arResult["DETAIL_PICTURE"]['SRC']) {
    $arResult["DETAIL_PICTURE"] = $arResult['PREVIEW_PICTURE'];
}



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

$new = false;
if ( $arResult["PROPERTIES"]["NEW_PRODUCT"]["VALUE"] == 1 ) $new = true;

$statuses = [];
if ($sale) {
    $statuses[] = 'Распродажа'; 
}
if ($new) {
    $statuses[] = 'Новинка'; 
}

$in_stock = $price > 0 && $arResult["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1;

$aditionalImages = [];
            
foreach (
    $arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $keyImg => $arImg
) {
    $aditionalImages[] = CFile::GetPath($arImg);
}
debugg('element template');
?>
<script>
    var arJSParams_<?=$arResult["ID"]?> = [];
    arJSParams_<?=$arResult["ID"]?> ["ID"] = '<?=$arResult['ID']?>';
</script>

<div class="content-container">
	<h2 class="heading-1 mobile-coin-name"><?=$arResult['NAME']?></h2>

    <section class="catalog-detail__top-bar">
        <div class="catalog-detail__top-bar-left">
            <?
                foreach ($statuses as $key => $status) 
                {?>
                    <p class="catalog-detail__coin-status"><?=$status?></p>
                <?}
            ?>
        </div>
        
        <div class="catalog-detail__top-bar-right">
            <p class="catalog-detail__is-in-stock">
                <? if ($in_stock) {
                    echo "В наличии";
                }
                else
                {
                    echo "Нет в наличии";
                } ?>
            </p>
            <p class="catalog-detail__coin-id">Артикул <?=$arResult["PROPERTIES"]["ARTICLE"]['VALUE']?></p>

            <div class="likes-share-wrapper">
                <button 
                onclick="addToDesiered(this, <?=$arResult['ID'];?>)"
                class="add-to-liked <?=$liked?>"
                id="add-to-likes">
                     <img src="/upload/mm_upload/icons/linked-coins/heart-gray.svg" alt="<?=$arItem['NAME'];?>">

                    <img src="/upload/mm_upload/icons/linked-coins/heart-gold-filled.svg" alt="<?=$arItem['NAME'];?>">
                </button>

                <a 
                target="_blank" 
                href="<?=$shareURL?>"
                id="share">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 16.08C17.24 16.08 16.56 16.38 16.04 16.85L8.91 12.7C8.96 12.47 9 12.24 9 12C9 11.76 8.96 11.53 8.91 11.3L15.96 7.19C16.5 7.69 17.21 8 18 8C19.66 8 21 6.66 21 5C21 3.34 19.66 2 18 2C16.34 2 15 3.34 15 5C15 5.24 15.04 5.47 15.09 5.7L8.04 9.81C7.5 9.31 6.79 9 6 9C4.34 9 3 10.34 3 12C3 13.66 4.34 15 6 15C6.79 15 7.5 14.69 8.04 14.19L15.16 18.35C15.11 18.56 15.08 18.78 15.08 19C15.08 20.61 16.39 21.92 18 21.92C19.61 21.92 20.92 20.61 20.92 19C20.92 17.39 19.61 16.08 18 16.08Z"/>
                    </svg>
                </a>
                
                <?
                // $APPLICATION->IncludeComponent(
                //     "api:yashare",
                //     "yashare.mm",
                //     Array(
                //         "DATA_DESCRIPTION" => "",
                //         "DATA_IMAGE" => "",
                //         "DATA_TITLE" => "",
                //         "DATA_URL" => "",
                //         "LANG" => "ru",
                //         "QUICKSERVICES" => array("vkontakte"),
                //         "SHARE_SERVICES" => array(),
                //         "SIZE" => "m",
                //         "TYPE" => "icon",
                //         "UNUSED_CSS" => "N",
                //         "twitter_hashtags" => ""
                //     )
                // );
                ?>

            </div>
        </div>
    </section>

    <section class="coin-detail-info">
        <div class="coin-detail-info__up-mobile-wrapper">
            <div class="mobile-top-bar">
				<div class="catalog-detail__top-bar-left">
					<div class="mobile-statuses">
						<?
						foreach ($statuses as $key => $status)
						{?>
							<p class="catalog-detail__coin-status"><?=$status?></p>
						<?}
						?>
					</div>

					<div class="mobile-status-properties">
						<p class="catalog-detail__is-in-stock">
							<? if ($in_stock) {
								echo "В наличии";
							}
							else
							{
								echo "Нет в наличии";
							} ?>
						</p>
						<p class="catalog-detail__coin-id">Артикул <?=$arResult["PROPERTIES"]["ARTIKUL"]?></p>
					</div>
				</div>

				<div class="likes-share-wrapper">
                    <button 
                    onclick="addToDesiered(this, <?=$arResult['ID'];?>)"
                    class="add-to-liked <?=$liked?>"
                    id="add-to-likes">
                        <img src="/upload/mm_upload/icons/linked-coins/heart-gray.svg" alt="<?=$arItem['NAME'];?>">

                        <img src="/upload/mm_upload/icons/linked-coins/heart-gold-filled.svg" alt="<?=$arItem['NAME'];?>">
                    </button>

					<a 
                    target="_blank" 
                    href="<?=$shareURL?>"
                    id="share">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M18 16.08C17.24 16.08 16.56 16.38 16.04 16.85L8.91 12.7C8.96 12.47 9 12.24 9 12C9 11.76 8.96 11.53 8.91 11.3L15.96 7.19C16.5 7.69 17.21 8 18 8C19.66 8 21 6.66 21 5C21 3.34 19.66 2 18 2C16.34 2 15 3.34 15 5C15 5.24 15.04 5.47 15.09 5.7L8.04 9.81C7.5 9.31 6.79 9 6 9C4.34 9 3 10.34 3 12C3 13.66 4.34 15 6 15C6.79 15 7.5 14.69 8.04 14.19L15.16 18.35C15.11 18.56 15.08 18.78 15.08 19C15.08 20.61 16.39 21.92 18 21.92C19.61 21.92 20.92 20.61 20.92 19C20.92 17.39 19.61 16.08 18 16.08Z"/>
						</svg>
					</a>

                    <?
                    // $APPLICATION->IncludeComponent(
                    //     "api:yashare",
                    //     "yashare.mm",
                    //     Array(
                    //         "DATA_DESCRIPTION" => "",
                    //         "DATA_IMAGE" => "",
                    //         "DATA_TITLE" => "",
                    //         "DATA_URL" => "",
                    //         "LANG" => "ru",
                    //         "QUICKSERVICES" => array("vkontakte"),
                    //         "SHARE_SERVICES" => array(),
                    //         "SIZE" => "m",
                    //         "TYPE" => "icon",
                    //         "UNUSED_CSS" => "N",
                    //         "twitter_hashtags" => ""
                    //     )
                    // );
                ?>
				</div>
			</div>

			<div class="coin-gallery-wrapper">
                <div class="mobile-relative-mini-photos-wrapper">
                    <div class="coin-preview-pictures">
                        <?if ($arResult["DETAIL_PICTURE"]["SRC"]) {?>
                            <img 
                            src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" 
                            alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>" 
                            class="coin__preview-picture active">
                        <?}?>
                        <?
                            foreach ($aditionalImages as $key => $imageSRC) 
                            {
                                if (!$arResult["DETAIL_PICTURE"]["SRC"] && $key==0)
                                {?>
                                    <img src="<?=$imageSRC?>" alt="<?=$arResult['NAME']?>" class="coin__preview-picture active"> 
                                <?}?>
                                <img src="<?=$imageSRC?>" alt="<?=$arResult['NAME']?>" class="coin__preview-picture">  
                            <?}
                        ?>
                    </div>
                </div>
                

                <?if ($arResult["DETAIL_PICTURE"]["SRC"]) {?>
                    <img 
                    src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" 
                    alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>" 
                    class="coin-preview-big">
                <?}?>
            </div>

            <?//echo'<pre>';print_r($arResult["DISPLAY_PROPERTIES"]['ARTICLE']['DISPLAY_VALUE']);echo'</pre>';?>
            <div class="coin-description-text">
                <h1 class="heading-1 coin__title"><?=$arResult["NAME"]?></h1>

                <div class="coin__description-table prices">
                    <div class="coin__description-table__row">
                        <?if($price > 0) {?>
                        <div 
                        class="coin__price" 
                        itemprop="offers" 
                        itemscope 
                        itemtype="http://schema.org/Offer">
                            <div class="coin__price-actual"><span itemprop="price"><?=$printPrice?></span><span
                                style="display:none;"
                                itemprop="priceCurrency">RUB</span>
                                <? if ($arResult["DISPLAY_PROPERTIES"]['ARTICLE']['DISPLAY_VALUE'] == 52169060 || $arResult["DISPLAY_PROPERTIES"]['ARTICLE']['DISPLAY_VALUE'] == 52160060) : // монета 17.3.23 ?>
                                    <span class="coin__price-actual--info js-coin__price-actual--info">
                                        <span class="coin__price-actual--internal">i</span>
                                    </span>
                                <? endif; ?>
                            </div>

                            <?if ($sale)
                            {?>
                                <p class="coin__price-old"><?=$printPrice_old?></p>
                            <?}?>
                        </div>
                        <?}?>
                        <?if ($in_stock) {?>
                            <button 
                            onclick="addToCartProduct(<?=$arResult['ID']?>, 1)"
                            id="add-to-cart" 
                            class="mint-btn filled big full-width">Добавить в корзину</button>
                        <?} else {?>
                            <button 
                            onclick="findPopupSubscription(<?=$arResult['ID']?>, 1)"
                            class="mint-btn filled big full-width">Узнать о поступлении</button>
                        <?}?>
                    </div>
                </div>

                <div class="coin__description-table desktop-properties">
                <?foreach ($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty) {?>
                    <?
                    $propValue = '';
                    if (is_array($arProperty["DISPLAY_VALUE"])) {
                        $propValue = implode(
                            "&nbsp;/&nbsp;", 
                            $arProperty["DISPLAY_VALUE"]
                        );
                    } else {
                        if ($arProperty["DISPLAY_VALUE"] == 'true') {
                            $propValue = 'Eсть';
                        } elseif ($arProperty["DISPLAY_VALUE"] == 'false') {
                            $propValue = 'Нет';
                        } else {
                            $propValue = $arProperty["DISPLAY_VALUE"];
                        }
                    }
                    
                    ?>
                    <div class="coin__description-table__row">
                        <p class="main-text coin__description-property-name"><?=$arProperty["NAME"];?></p>
                        <p class="coin__description-property-value"><?=$propValue?></p>
                    </div>
                <?}?>
                </div>
                <?if(strlen($arResult["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"]) > 0){?>
                    <div class="our-buy-price-wrapper desktop">
                        <p class="our-buy-price__text">
                            Выкупим такую монету за 
                            <span class="price-wrapper"><?=number_format($arResult["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"], 0, ".", " ")?> &#8381;</span>
                        </p>

                        <button onclick="openSellModal('<?=$arResult['ID'];?>')" class="mint-btn blue sell-coin">Продать</button>
                    </div>
                <?}?>
            </div>
        </div>

        <div class="mobile-description-table">
            <div class="coin__description-table mobile-specs" data-opened="true">
                <?foreach ($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty) {?>
                    <?
                    $propValue = '';
                    if (is_array($arProperty["DISPLAY_VALUE"])) {
                        $propValue = implode(
                            "&nbsp;/&nbsp;", 
                            $arProperty["DISPLAY_VALUE"]
                        );
                    } else {
                        if ($arProperty["DISPLAY_VALUE"] == 'true') {
                            $propValue = 'Eсть';
                        } elseif ($arProperty["DISPLAY_VALUE"] == 'false') {
                            $propValue = 'Нет';
                        } else {
                            $propValue = $arProperty["DISPLAY_VALUE"];
                        }
                    }
                    
                    ?>
                    <div class="coin__description-table__row">
                        <p class="main-text coin__description-property-name"><?=$arProperty["NAME"];?></p>
                        <p class="coin__description-property-value"><?=$propValue?></p>
                    </div>
                <?}?>
            </div>

            <button id="show-more-specs">Показать меньше</button>

            <?if(strlen($arResult["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"]) > 0){?>
            <div class="our-buy-price-wrapper">
                <p class="our-buy-price__text">
                    Выкупим такую монету за 
                    
                    <span class="price-wrapper"><?=number_format($arResult["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"], 0, ".", " ")?> &#8381;</span>
                </p>

                <button 
                onclick="openSellModal('<?=$arResult['ID'];?>')" 
                class="mint-btn blue sell-coin">Продать</button>
            </div>
            <?}?>
        </div>
    </section>
    <div class="popup-infobox"><? // монета 17.3.23 ?>
        <div class="popup-infobox--info"><span class="popup-infobox--internal">i</span></div>
        <div>Информацию о наличии и о стоимости монеты в Вашем регионе узнавайте по тел.8 (800) 505 0476</div>
    </div>
</div>
<div class="dark-wrapper">

<? 
if($arResult["PROPERTIES"]["MM_DESCYT"]['VALUE'] || 
$arResult["DETAIL_TEXT"]) {?>
		<section class="dark-coin-description">
			<div class="content-container">
				<h2 class="heading-3 dark-description__title">Описание монеты</h2>

				<div class="left-top-blur"></div>

				<div class="dark-description__text-video-wrapper">
					<? if ($arResult["DETAIL_TEXT"] != "")
					{?>
						<div
							class="main-text dark-description__text
								<?=$arResult["PROPERTIES"]["MM_DESCYT"]['VALUE'] == '' ? "" : "above-video"?>">
							<?=$arResult["DETAIL_TEXT"]?>
						</div>
					<?}?>

					<div class="dark-desctiption__image-video-wrappper <?= $arResult["DETAIL_TEXT"] == "" ? "full-width" : ""?>">
						<?
							// if video link
							if ($arResult["PROPERTIES"]["MM_DESCYT"]['VALUE'] != "")
							{?>
								
								<div
									class="dark-description__video-wrapper"
								>
									<div class="yt-video-blur"></div>

									<iframe
										class="yt-video-description"
										width="100%" height="100%"
										src="https://www.youtube.com/embed/<?=$arResult["PROPERTIES"]["MM_DESCYT"]['VALUE'];?>"
										title="YouTube video player"
										frameborder="0"
										allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
										allowfullscreen>
									</iframe>
								</div>
							<?} else if ($arResult["PROPERTIES"]["MM_DESCYT"]['VALUE'] == "" &&
							$arResult["PROPERTIES"]["MM_DESCIMG"]['VAUE'] != "")
							{?>
								<div class="description-image-wrapper">
									<div class="description-image-blur"></div>

									<img
										src="<?=CFile::GetPath($arResult["PROPERTIES"]["MM_DESCIMG"]['VAUE'])?>"
										alt="<?=$arResult['NAME']?>"
										class="dark-description__image">
								</div>
							<?}
							// if no video link, no desc image
							else if ($arResult["PROPERTIES"]["MM_DESCYT"]['VALUE'] == "" &&
							$arResult["PROPERTIES"]["MM_DESCIMG"]['VAUE'] == "" &&
							$arResult["DETAIL_PICTURE"]["SRC"] != "")
							{?>
								<div class="description-image-wrapper">
									<div class="description-image-blur"></div>

									<img
										src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
										alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
										class="dark-description__image">
								</div>
							<?}
						?>
					</div>
				</div>
			</div>

		</section>
	<?}?>

    <section class="coin-reviews">
        <?$APPLICATION->ShowViewContent('reviews');?>
    </section>
    
    <?$APPLICATION->ShowViewContent('fromseries');?>
    
    <?$APPLICATION->ShowViewContent('youlooked');?>

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

<script> // вывод всплывающего окошка по некоторым товарным позициям (17.3.23)
    BX.ready(
        BX.defer(function() {
            $('.js-coin__price-actual--info').hover(
                //$('.js-coin__price-actual--info').click(
                function() {
                    //console.log('**');
                    $('.popup-infobox').addClass("popup-infobox-hover");
                    let window_width = $(window).width();
                    let popup_infobox_width = $('.popup-infobox').width();
                    let coord = $(this).offset();
                    //console.log(coord);
                    if(popup_infobox_width < (window_width - coord.left)) { // вывожу справа от i
                        $('.popup-infobox').offset({top: coord.top + 30, left: coord.left + 2});
                        //console.log($('.popup-infobox').offset());
                    }
                    else {
                        if(popup_infobox_width < (coord.left)) { // вывожу слева от i
                            $('.popup-infobox').offset({top: coord.top + 30, left: coord.left - popup_infobox_width - 2});
                        }
                        else {  // вывожу посередине экрана
                            $('.popup-infobox').offset({top: coord.top + 30, left: (window_width - popup_infobox_width) / 2});
                        }
                    }
                },
                function() {
                    $('.popup-infobox').removeClass("popup-infobox-hover");
                }
            );
        })
    );
</script>
