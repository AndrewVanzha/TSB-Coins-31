<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if ($arResult['SECTIONS_COUNT'] == 0)
{
    $rsCurrentSection = CIBlockSection::GetByID($arParams['SECTION_ID']);

    if($arCurrentSection = $rsCurrentSection->GetNext()){

        if ($arCurrentSection['DEPTH_LEVEL'] != 1) {
            $rsSections = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'SECTION_ID' =>  $arCurrentSection['IBLOCK_SECTION_ID']), false, array());
            $arResult['SECTIONS_COUNT'] = $rsSections -> SelectedRowsCount();
            while ($arSection = $rsSections->GetNext()){
                $arResult['SECTIONS'][] = $arSection;
            }

            $rsParentSection = CIBlockSection::GetByID($arCurrentSection['IBLOCK_SECTION_ID']);
            if($arParentSection = $rsParentSection->GetNext()) {
                $arResult['PARENT_SECTION'] = $arParentSection;

            }
        }
    }
}
?>