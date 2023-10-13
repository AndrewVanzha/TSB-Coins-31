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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles['TILE'];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));


if ($arResult["SECTIONS_COUNT"] > 0){ ?>
    <h1><?=$APPLICATION->GetTitle()?></h1>
    <div class="clearfix">
        <? foreach ($arResult['SECTIONS'] as $arSection){
            $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
            $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
            
            if (false === $arSection['PICTURE'] or !is_array($arSection['PICTURE']) ){
                $arSection['PICTURE'] = array(
                    'SRC' => $arCurView['EMPTY_IMG'],
                    'ALT' => (
                        '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                        ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                        : $arSection["NAME"]
                    ),
                    'TITLE' => (
                        '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                        ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                        : $arSection["NAME"]
                    )
                );

            }?>
            <div class="col">
                <a href="<?=$arSection['SECTION_PAGE_URL'];?>" class="col_img" id="<? echo $this->GetEditAreaId($arSection['ID'])?>">
                    <img src="<?=$arSection['PICTURE']["SRC"];?>" alt="<?=$arSection['PICTURE']["ALT"];?>">
                </a>
                <div class="col_title" style="text-align: center;"><?=$arSection['NAME'];?></div>
            </div>
        <? } ?>
    </div> 
<? } ?>
