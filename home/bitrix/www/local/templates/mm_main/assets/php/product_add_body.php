<?php
header('Content-Type: application/json');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ($data = file_get_contents('php://input')) {
if ($id = (json_decode($data, true))['id']) {?>

<?
    CModule::IncludeModule('iblock');
    $rsElem = CIBlockElement::GetList(
        [],
        [
            'SECTION_GLOBAL_ACTIVE' => 'Y',
            'IBLOCK_ID' => 6,
            'ID' => $id,
        ], false, false,
        array(
            'NAME',
            'IBLOCK_ID',
            'ID',
            'DETAIL_PAGE_URL',
            'PREVIEW_PICTURE',
            'CATALOG_GROUP_1',
        )
    );
    $elem = $rsElem->GetNext();
    $price = (float)$elem['CATALOG_PRICE_1'];
    $showPrice = $price > 0;
    $price = number_format($price, 0, '.', ' ');
    $img  = CFile::GetPath($elem['PREVIEW_PICTURE']);
?>

<?if ($elem) {?>
    <img 
    src="<?=$img?>" alt="<?=$elem['NAME']?>" class="added-modal__coin__avatar">

    <div class="added-modal__coin-desc">
        <p class="added-modal__coin-name"><?=$elem['NAME']?></p>

        <p class="added-modal__coin-price">
            <?=$price;?> &#8381;
        </p>
    </div>
<?}?>
<?
}}