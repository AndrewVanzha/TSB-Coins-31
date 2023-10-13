<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
?>
<script type="text/javascript">
    var arBasketItems = [];
    <?/** --- BASKET_LIST --- **/
    #Подключение модулей
    if(!Loader::includeModule('sale')){ return; }
    $dbItems = CSaleBasket::GetList(
            array(
                "NAME"=>"ASC",
                "ID"=>"ASC"
                ),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
                ),
            false,
            false,
            array(
                "ID",
                "NAME",
                "PRODUCT_ID",
                "QUANTITY"
                )
        );
    while ($arItem = $dbItems->GetNext(true, false)){
        $arItem["BX_PRODUCT_ID"] = $this->GetEditAreaId($arItem['PRODUCT_ID']);
        ?>arBasketItems.push("<?=$arItem["BX_PRODUCT_ID"]?>");<?
    }
    /** --- END BASKET_LIST --- **/
    ?>
    if(arBasketItems.length > 0){
        $.each(arBasketItems, function(index, value){
            if ($("#"+value).length>0){
                $("#"+value+" .product-details_info").css('display','none');
                $("#"+value+" #in_cart").css('display','block');
            }
        });
    }
</script>
