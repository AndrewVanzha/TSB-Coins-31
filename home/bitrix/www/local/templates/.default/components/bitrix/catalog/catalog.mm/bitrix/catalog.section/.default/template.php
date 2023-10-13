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
$this->addExternalCss('/local/templates/mm_main/assets/css/components/catalog-section-list.css');
$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

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
$desiredArr = getUserDesired($currUserId);


if (!empty($arResult['ITEMS'])): ?>
<div 
    class="catalog-coins-items <?=($_COOKIE['view'] == "list" ? 'list' : 'grid')?>">
    <?
        foreach($arResult['ITEMS'] as $key => $arItem) {
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
            $strMainID = $this->GetEditAreaId($arItem['ID']);
            
            $arItemIDs = array(
                'ID' => $strMainID,
                'PICT' => $strMainID.'_pict',
                'SECOND_PICT' => $strMainID.'_secondpict',
                'STICKER_ID' => $strMainID.'_sticker',
                'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
                'QUANTITY' => $strMainID.'_quantity',
                'QUANTITY_DOWN' => $strMainID.'_quant_down',
                'QUANTITY_UP' => $strMainID.'_quant_up',
                'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
                'BUY_LINK' => $strMainID.'_buy_link',
                'BASKET_ACTIONS' => $strMainID.'_basket_actions',
                'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
                'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
                'COMPARE_LINK' => $strMainID.'_compare_link',

                'PRICE' => $strMainID.'_price',
                'DSC_PERC' => $strMainID.'_dsc_perc',
                'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
                'PROP_DIV' => $strMainID.'_sku_tree',
                'PROP' => $strMainID.'_prop_',
                'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
                'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
            );

                            #Работа с ценой
            $minPrice = false;
            if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE']))
                $minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
            
            
            $printPrice = $minPrice['PRINT_DISCOUNT_VALUE'];
            $printPrice_old = $minPrice['PRINT_VALUE'];
            $price = $minPrice['DISCOUNT_VALUE'];
            $price_old = $minPrice['VALUE'];
            $sale = false;
            $sale_percent = $minPrice['DISCOUNT_DIFF_PERCENT'];

            if (($price != $price_old) && ($price > 0 || $sale_percent > 0)) $sale = true;

            //новинка
            $new = false;
            if ( $arItem["PROPERTIES"]["NEW_PRODUCT"]["VALUE"] == 1 ) $new = true;

            // есть в лайках
            $liked = in_array($arItem['ID'], $desiredArr);

            //Название товара и описание
            $productTitle = (
            isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
                : $arItem['NAME']
            );
            $imgTitle = (
            isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
                : $arItem['NAME']
            );
            $img = !empty($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE']['SRC'] : $arItem['PREVIEW_PICTURE_SECOND']['SRC'] ;

            if (empty($img)) $img = $this->GetFolder().'/images/no_photo.png';

            $aditionalImages = [];
            
            foreach (
                $arItem['PROPERTIES']['MORE_PHOTO']['VALUE'] as $keyImg => $arImg
            ) {
                if ($keyImg < 3)
                {
                    $aditionalImages[] = CFile::GetPath($arImg);
                }
                else
                {
                    break;
                }
            }
            ?>
            <div
            class="catalog-coins__coin-item" 
            id="<?=$strMainID?>">
                <script>
                    var arJSParams_<?=$arItem["ID"]?> = [];
                    arJSParams_<?=$arItem["ID"]?> ["ID"] = '<?=$arItem['ID']?>';
                </script>

                <div class="top-bar has-status">
                    <div class="coin-item__statuses">
                        <?/*=(
                            $sale ? '<p class="coin-item__status sale">Распродажа</p>' : ''
                        )*/?>
                        <?=(
                        $sale ? '<p class="coin-item__status sale">Акция</p>' : ''
                        )?>
                        <?=(
                            $new ? '<p class="coin-item__status is-new">Новинка</p>' : ''
                        )?>
                    </div>
                    <button 
                    onclick="addToDesiered(this, <?=$arItem['ID'];?>)"
                    class="add-to-liked <?= $liked ? 'liked' : ''?>">
                        <img 
                        src="/upload/mm_upload/icons/linked-coins/heart-gold.svg" 
                        alt="<?=$arItem['NAME'];?>">
                        <img src="/upload/mm_upload/icons/linked-coins/heart-gold-filled.svg" alt="<?=$arItem['NAME'];?>">
                    </button>
                </div>

                <div class="mobile-needed-wrapper">
                    <a 
                    href="<?=$arItem['DETAIL_PAGE_URL']?>"
                    class="mobile-preview-wrapper">
                        <?if (count($aditionalImages) > 0):?>
                            <div 
                            data-count="<?count($aditionalImages) + 1?>"
                            class="images__line-galery">
                                <div class="images__line-galery-items-wrapper">
                                    <div class="images__line-galery-item active">
                                        <img 
                                        src="<?=$img?>" 
                                        alt="<?=$arItem['NAME'];?>">
                                    </div>
                                    <?foreach ($aditionalImages as $keyAdImg => $adImg) {?>
                                        <div class="images__line-galery-item">
                                            <img 
                                            src="<?=$adImg?>" 
                                            alt="<?=$arItem['NAME'];?>">
                                        </div>
                                    <?}?>
                                </div>
                                <div class="images__line-galery-bullets">
                                    <span class="active"></span>
                                    <?foreach ($aditionalImages as $key => $value) echo '<span></span>';?>
                                </div>
                            </div>
                        <?else:?>
                            <div class="coin-item__images-preview">
                                <img 
                                src="<?=$img?>" alt="<?=$arItem['NAME'];?>">
                            </div>
                        <?endif;?>
                        <p class="coin-item__is-in-stock">
                            <?if ($price > 0 && $arItem["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1):?>
                                В наличии
                            <?else:?>
                                Нет в наличии
                            <?endif;?>
                        </p>
                    </a>
                    <div class="coin-item__description-wrapper">
                        <div class="coin-item__description-text">
                            <p class="coin-item__created-in">
                                <?=$arItem["PROPERTIES"]["COUNTRY"]["VALUE"];?>
                            </p>
                            <a 
                            href="<?=$arItem['DETAIL_PAGE_URL']?>"
                            class="coin-item__name">
                                <?=$arItem['NAME'];?>
                            </a>
                            <p class="buy-price">
                            <?if (strlen($arItem["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"]) > 0) {?>
                                Цена выкупа 
                                <span>
                                    <?=number_format($arItem["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"], 0, '.', ' ');?>&nbsp;₽
                                </span>
                            <?}?>
                            </p>
                        </div>

                        <?//echo'<pre>';print_r($arItem["PROPERTIES"]['ARTICLE']['VALUE']);echo'</pre>';?>
                        <div class="bottom-bar">
                            <div class="sell-price-wrapper">
                                <p class="coin-item__sell-price"><?=(
                                    $price > 0 && 
                                    $arItem["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1 ? 
                                    $printPrice : "<span class='coin-item__sell-not-stock'>Нет в наличии</span>")?></p>
                                <?if (
                                    $sale == true && 
                                    $price > 0 && 
                                    $arItem["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1
                                ):?>
                                    <p class="coin-item__old-price"><?=$printPrice_old?></p>
                                <?endif;?>
                            </div>

                            <p class="list-coin-item__buy-price">
                                <?if (strlen($arItem["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"]) > 0) {?>
                                    Цена выкупа 
                                    <span>
                                        <?=number_format($arItem["PROPERTIES"]["REDEMPTION_PRICE"]["VALUE"], 0, '.', ' ');?>&nbsp;₽
                                    </span>
                                <?}?>
                            </p>
                            <?if ($price > 0 && $arItem["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1) {?>
                                <button 
                                id="cart_<?=$arItem["ID"]?>"
                                class="add-to-cart"
                                onclick="
                                addToCartProduct('<?=$arItem["ID"]?>', 1)">
                                    <img src="/upload/mm_upload/icons/app-footer/shopping-cart.svg" alt="корзина">
                                </button>
                            <?} else {?>
                                <button 
                                class="add-to-cart notify"
                                onclick="findPopupSubscription('<?=$arItem["ID"]?>')">
                                    <img src="/upload/mm_upload/icons/app-footer/bell.svg" alt="корзина">
                                </button>
                            <?}?>

                        </div>

                        <? if ($arItem["PROPERTIES"]['ARTICLE']['VALUE'] == 52169060 || $arItem["PROPERTIES"]['ARTICLE']['VALUE'] == 52160060) : // монета ?>
                            <div class="coin-item__description-text--info js-coin-item__description-text--info">
                                <span class="coin-item__description-text--internal">i</span>
                                <?/*?>
                                <div class="popup-box">  <?// работает js, hover не работает ?>
                                    <div class="popup-box--info"><span class="popup-box--internal">i</span></div>
                                    <div>Информацию о наличии и о стоимости монеты в Вашем регионе узнавайте по тел.8 (800) 505 0476</div>
                                </div>
                                <?*/?>
                            </div>
                        <? endif; ?>

                    </div>
                </div>

            </div>

        <?}
    ?>
</div>
<div>
    <?echo $arResult["NAV_STRING"];?>
</div>
<?else:?>
    <p class="heading-3 empty-catalog-title">К сожалению, по вашему запросу ничего не нашлось</p>
<?endif;?>


<script> // вывод всплывающего окошка по некоторым товарным позициям (17.3.23)
    $(document).ready(function() {
        $('.js-coin-item__description-text--info').hover(
            //$('.js-coin-item__description-text--info').click(
            function() {
                //console.log('**');
                $('.popup-infobox').addClass("popup-infobox-hover");
                let window_width = $(window).width();
                let popup_infobox_width = $('.popup-infobox').width();
                let coord = $(this).offset(); //('.coin-item__description-text--internal');
                //console.log(coord);
                if(popup_infobox_width < (window_width - coord.left)) { // вывожу справа от i
                    $('.popup-infobox').offset({top: coord.top + 20, left: coord.left + 2});
                }
                else {
                    if(popup_infobox_width < (coord.left)) { // вывожу слева от i
                        $('.popup-infobox').offset({top: coord.top + 20, left: coord.left - popup_infobox_width - 2});
                    }
                    else {  // вывожу посередине экрана
                        $('.popup-infobox').offset({top: coord.top + 20, left: (window_width - popup_infobox_width) / 2});
                    }
                }
            },
            function() {
                $('.popup-infobox').removeClass("popup-infobox-hover");
            }
        );
    });
</script>


