<?
header('Content-Type: application/json');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

function translit($title)
{
    $arParams_trans = array("replace_space"=>"-","replace_other"=>"-");
    return Cutil::translit($title,"ru",$arParams_trans);
}

if ($data = file_get_contents('php://input')) {
if ($formInfo = (json_decode($data, true))) {
    if (
        !$formInfo['id'] || 
        !$formInfo['name'] || 
        !$formInfo['phone']
    ) exit();
    CModule::IncludeModule('iblock');
    $rsElem = CIBlockElement::GetList(
        [],
        [
            'SECTION_GLOBAL_ACTIVE' => 'Y',
            'IBLOCK_ID' => 6,
            'ID' => $formInfo['id'],
        ], false, false,
        array(
            'NAME',
            'IBLOCK_ID',
            'ID',
            'DETAIL_PAGE_URL',
            'PREVIEW_PICTURE',
        )
    );
    $elem = $rsElem->GetNext();

    if (!$elem) exit();

    global $USER;
    $user_id = $USER->GetID();

    $title = "Заявка на товара ID=".$elem['ID']. " (".$elem['NAME'].")";
    $trans = translit($title);
    $arLoadProductArray = Array(
        "IBLOCK_ID"          => 22,
        "NAME"               => $title,
        "CODE"               => $trans.'-'.mktime(),
        "ACTIVE"             => "N",
        "PREVIEW_TEXT"       => "",
        "DETAIL_TEXT"	     => "",
        "PROPERTY_VALUES"    => array(
            "PRODUCT_ID"     => (int)$elem["ID"],
            "PRODUCT_NAME"   => $elem["NAME"],
            "USER_ID"        => $user_id,
            "USER_NAME"      => $formInfo['name'],
            "USER_NUMBER"    => $formInfo['phone']
        ),
        "DATE_ACTIVE_FROM" => date("d.m.Y H:i:s"),
        "DETAIL_PICTURE" => "",
    );
    $el = new CIBlockElement;
    if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        $arMailFields = array(
            "PRODUCT_ID"     => $elem["ID"],
            "PRODUCT_NAME"   => $elem["NAME"],
            "USER_NAME"      => $formInfo['name'],
            "USER_NUMBER"    => $formInfo['phone'],
            "DATE"           => date("d.m.Y H:i:s")
        );
        $result = CEvent::Send('FEEDBACK_REDEMPTION_PRICE', SITE_ID, $arMailFields); 
        if ($result) {
            echo json_encode(['status' => [
                'ID' => $PRODUCT_ID,
                'TYPE' => 'SELL'
            ]]);
        }
    }
}}