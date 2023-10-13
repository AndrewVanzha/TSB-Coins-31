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

$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/local/include/countryList.php"))
    include_once $_SERVER['DOCUMENT_ROOT'] . '/local/include/countryList.php';




if (!empty($arResult['ITEMS'])){ ?>
    <div class="product-wrap">
        <div class="row">
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

                ?>
                <div class="product-box <?=$_COOKIE['view']?>" id="<?=$strMainID?>">

                    <script>
                        var arJSParams_<?=$arItem["ID"]?> = [];
                        arJSParams_<?=$arItem["ID"]?> ["ID"] = '<?=$arItem['ID']?>';
                    </script>

                    <? if (!empty($arItem["PROPERTIES"]["COUNTRY"]["VALUE"])) {

                        $img_country = "";
                        $country_code = array_search($arItem["PROPERTIES"]["COUNTRY"]["VALUE"], $countries);

                        if (strlen($country_code) == 2) {
                            $img_country = '/assets/images/flags/'.strtolower($country_code).'.png';
                        }
                        ?>
                        <div class="product-auction-header clearfix">
                            <div class="left">
                                <? if (!empty($img_country)) { ?>
                                    <img class="aligner" src="<?=$img_country?>" alt="<?=$arItem["PROPERTIES"]["COUNTRY"]["VALUE"]?>">
                                <? } ?>

                                <span class="aligner country"><?=$arItem["PROPERTIES"]["COUNTRY"]["VALUE"]?></span>
                            </div>
                        </div>
                    <? } ?>

                    <div class="mark-wrap">
                        <? if ($new) { echo '<div class="mark new">new</div>'; } ?>
                        <? if ($sale) { echo '<div class="mark sale">sale</div>'; } ?>
                    </div>

                    <div class="img">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=$arItem['NAME']?>">
                            <img src="<?=$img?>" alt="<?=$arItem['NAME']?>">
                        </a>
                    </div>

                    <div class="info">
                        <div class="info-visible">
                            <div class="title">
                                <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                            </div>
                            <div class="details">
                                <?

                                if (!empty($arItem["PROPERTIES"]["NOMINAL"]["VALUE"])){
                                    echo '<div>';
                                        echo 'Номинал: '.$arItem["PROPERTIES"]["NOMINAL"]["VALUE"];
                                        if (!empty($arItem["PROPERTIES"]["NAME_MONEY_MARK_LITERAL"]["VALUE"])) {
                                            echo ' '.$arItem["PROPERTIES"]["NAME_MONEY_MARK_LITERAL"]["VALUE"];
                                        }
                                    echo '</div>';
                                }
                                ?>
                                <?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty) {
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
                            <? if ($price > 0) { ?>
                                <div class="price-wrap">
                                    <div class="price"><?=$printPrice?></div>
                                    <? if ($sale == true) { ?>
                                        <div class="price-old"><?=$printPrice_old?></div>
                                    <? } ?>
                                </div>
                            <? } ?>
                        </div>
                        <div class="info-hover">
                            <? if (is_array($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"])) { ?>
                                <div class="block">
                                    <div class="product-thumbs row">
                                        <? foreach ($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $key => $img_id) { ?>
                                            <div class="item">
                                                <a href="<?=CFile::GetPath($img_id)?>" data-fancybox="images-<?=$arItem["ID"]?>">
                                                    <img src="<?=CFile::GetPath($img_id)?>" alt="<?=$arItem["PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"][$key]?>">
                                                </a>
                                            </div>
                                        <? } ?>
                                    </div>
                                </div>
                            <? } ?>
                            <div class="block">

                                <?/*--- Если юбил монеты ---*/?>
                                <? if ($arItem["IBLOCK_SECTION_ID"] == 18) { ?>
                                    <div class="info-hover-text">
                                              Размен возможен в <a href="https://coins.tsbnk.ru/dostavka-i-oplata/" target="_blank">офисах банка</a>
                                    </div>
                                <? } elseif ($price > 0 && $arItem["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1) { ?>
                                    <div class="product-btn clearfix">
                                        <div class="left">
                                            <?
                                            /** --- Избранное --- **/
                                            if (!empty($_SESSION["LIKED_PRODUCTS"][$arItem["ID"]] )) {
                                                echo '<a href="javascript:void(0);" onclick="deleted_liked(this,'.$arItem["ID"].')" class="favorite is-active" title="Удалить из отложенного"></a>';
                                            }
                                            else {
                                                echo '<a href="javascript:void(0);" onclick="add_liked(this,'.$arItem["ID"].')" class="favorite" title="Добавить в отложенное"></a>';
                                            }
                                            ?>
                                            <div class="count">
                                                <button type="button" class="count-minus" onclick="countMinusProduct(this, arJSParams_<?=$arItem["ID"]?>)">-</button>
                                                <input class="count-num" type="text" value="1" onchange="changeCountProduct(this, arJSParams_<?=$arItem["ID"]?>)">
                                                <button type="button" class="count-plus" onclick="countPlusProduct(this, arJSParams_<?=$arItem["ID"]?>)">+</button>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <a id="cart_<?=$arItem["ID"]?>" href="javascript:void(0);" class="button" onclick="addToCartProduct('<?=$arItem["ID"]?>', 1)">Купить</a>
                                        </div>

                                    </div>
                                <? } else { ?>
                                    <div class="info-hover-text">
                                        В данный момент монеты нет в наличии
                                    </div>
                                <? } ?>

                            </div>
                        </div>

                        <? if ($arItem["IBLOCK_SECTION_ID"] == 18) { ?>
                            <div class="mobile-btn">
                                <div class="info-hover-text">
                                    Размен возможен в <a href="https://coins.tsbnk.ru/dostavka-i-oplata/" target="_blank">офисах банка</a>
                                </div>
</div>
                        <? } elseif ($price > 0 && $arItem["PROPERTIES"]["AVAILABLE"]["VALUE"] == 1) { ?>
                            <div class="mobile-btn"><a id="cartMobile_<?=$arItem["ID"]?>" href="javascript:void(0);" class="button" onclick="addToCartProduct('<?=$arItem["ID"]?>', 1)">Купить</a></div>
                        <? } else { ?>
                            <div class="mobile-btn">
                                <a href="javascript:void(0);" class="button" onclick="findPopupSubscription('<?=$arItem["ID"]?>')">Узнать о поступлении</a>
                            </div>
                        <? } ?>
                    </div>

                </div>
                <?

            } //end foreach
            ?>
        </div>
    </div>
    <?
    if ($arParams["DISPLAY_BOTTOM_PAGER"]){
        echo $arResult["NAV_STRING"];
    }
    ?>
<?}//end empty($arResult['ITEMS'])?>

