<?
use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;
use \Bitrix\Iblock;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

class addToCartFancybox
{
    public function checkModule()
    {
        if (!Loader::includeModule('iblock')){ return false; }
        if(!Loader::includeModule('catalog')){ return false; }
        if(!Loader::includeModule('sale')){ return false; }
    }

    public function getResult()
    {
        global $USER;

        $request = Application::getInstance()->getContext()->getRequest();

        if($request->isPost()){

            $PRODUCT_ID = htmlspecialcharsex($request->getPost("product_id"));
            $QUANTITY = htmlspecialcharsex($request->getPost("quant"));

            if ($PRODUCT_ID > 0 && $QUANTITY > 0) {

                $BASE_PRICE = CPrice::GetBasePrice($PRODUCT_ID);
                $PRICE_CODE = array($BASE_PRICE["CATALOG_GROUP_NAME"]);

                $db_list = CIBlockElement::GetList(
                    array("SORT"=>"ASC"),
                    array("ID"=>$PRODUCT_ID),
                    false,
                    false,
                    array("IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE")
                );

                while($arFields = $db_list->GetNextElement()) {

                    $this->arResult = $arFields->GetFields();

                    #изображение товара
                    $PREVIEW_PICTURE = CFile::GetPath($this->arResult["PREVIEW_PICTURE"]);
                    $DETAIL_PICTURE = CFile::GetPath($this->arResult["DETAIL_PICTURE"]);

                    if( $PREVIEW_PICTURE != "" ) {
                        $img = $PREVIEW_PICTURE;
                    }
                    elseif ( $DETAIL_PICTURE != "" ) {
                        $img = $DETAIL_PICTURE;
                    }
                    else {
                        $img = "";
                    }
                    $this->arResult["IMG_SRC"] = $img;


                    $CATALOG_PRICES = CIBlockPriceTools::GetCatalogPrices($this->arResult["IBLOCK_ID"],$PRICE_CODE);
                    $arrayRes["CAN_BUY"] = CIBlockPriceTools::CanBuy($this->arResult["IBLOCK_ID"], $CATALOG_PRICES, $this->arResult);


                    #расчитываем цену товара
                    $ar_res = CPrice::GetBasePrice($PRODUCT_ID);
                    $dbPrice = CPrice::GetList(
                        array("QUANTITY_FROM" => "ASC","QUANTITY_TO" => "ASC","SORT" => "ASC"),
                        array("PRODUCT_ID" => $PRODUCT_ID,"CATALOG_GROUP_ID" => $ar_res["CATALOG_GROUP_ID"]),
                        false,
                        false,
                        array("ID","CATALOG_GROUP_ID","PRICE","CURRENCY","QUANTITY_FROM","QUANTITY_TO")
                    );

                    while ($arPrice = $dbPrice->Fetch()) {
                        $arDiscounts = CCatalogDiscount::GetDiscountByPrice(
                            $arPrice["ID"],
                            $USER->GetUserGroupArray(),
                            "N",
                            SITE_ID
                        );
                        $discountPrice = CCatalogProduct::CountPriceWithDiscount(
                            $arPrice["PRICE"],
                            $arPrice["CURRENCY"],
                            $arDiscounts
                        );
                        $arPrice["PRICE_FORMAT"] = FormatCurrency($arPrice["PRICE"], $arPrice["CURRENCY"]);
                        $arPrice["DISCOUNT_PRICE"] = $discountPrice;
                        $arPrice['DISCOUNT_PRICE_FORMAT'] = FormatCurrency($discountPrice, $arPrice["CURRENCY"]);

                        $this->arResult["PRICE"] = $arPrice;
                    }

                    #формируем всплывающее окно
                    $this->result = '<div id="popup-cart" class="popup popup-cart fancybox" style="display: inline-block;">';
                        $this->result .= '<div class="form-subs">';
                            $this->result .= '<div class="title-wrap">';
                                $this->result .= '<div class="title-form">Товар успешно добавлен в корзину</div>';
                            $this->result .= '</div>';
                            $this->result .= '<div class="clearfix info">';
                                $this->result .= '<div class="image"><img src="'.$this->arResult["IMG_SRC"].'" alt="'.$this->arResult["NAME"].'" class="product-image"></div>';
                                    $this->result .= '<div class="aligner description">';
                                        $this->result .= '<h6 class="name">'.$this->arResult["NAME"].'</h6>';
                                        $this->result .= '<strong class="price">'.$QUANTITY.'<small> x </small>'.$this->arResult["PRICE"]["DISCOUNT_PRICE_FORMAT"].'</strong>';
                                    $this->result .= '</div>';
                            $this->result .= '</div>';
                            $this->result .= '<div class="actions clearfix">';
                                $this->result .= '<a href="#" class="button" onclick="$.fancybox.close(); return false;">продолжить покупки</a>';
                                $this->result .= '<a href="/personal/cart/" class="button">перейти в корзину</a>';
                            $this->result .= '</div>';
                        $this->result .= '</div>';
                    $this->result .= '</div>';

                }

            }
        }
    }

    public function myExecuteComponent()
    {
        try{
            $this->checkModule();
            $this->getResult();

            return $this->result;

        }catch (Exception $e){
            ShowError($e->getMessage());
        }
    }
}

$addToCartFancybox = new addToCartFancybox();
$result_html = $addToCartFancybox->myExecuteComponent();

echo $result_html;


?>