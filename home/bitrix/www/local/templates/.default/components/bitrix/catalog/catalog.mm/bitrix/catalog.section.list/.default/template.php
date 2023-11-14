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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

if ($arResult["SECTIONS_COUNT"] > 0) {
    echo '<div class="filter">';
        echo '<div class="filter-inner">';
            if ($arResult['SECTIONS_COUNT'] > 1) {
            	if ($arResult['SECTION']['DEPTH_LEVEL'] == 1)
                	echo '<div class="filter-item active"><a href="'.$arResult['SECTION']['SECTION_PAGE_URL'].'">Все модели</a></div>';
            	else
                    echo '<div class="filter-item"><a href="'.$arResult['PARENT_SECTION']['SECTION_PAGE_URL'].'">Все модели</a></div>';
            }

            foreach ($arResult['SECTIONS'] as $arSection){

                $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
				if ($arSection['SECTION_PAGE_URL'] == $arResult['SECTION']['SECTION_PAGE_URL'])
                	echo '<div class="filter-item active"><a href="'.$arSection['SECTION_PAGE_URL'].'">'.$arSection['NAME'].'</a></div>';
				else
                    echo '<div class="filter-item"><a href="'.$arSection['SECTION_PAGE_URL'].'">'.$arSection['NAME'].'</a></div>';
            }
        echo '</div>';
    echo '</div>';
} ?>