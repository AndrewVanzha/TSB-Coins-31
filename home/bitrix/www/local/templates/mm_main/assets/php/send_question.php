<?php
header('Content-Type: application/json');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');

if ($data = file_get_contents('php://input')) {
if ($info = (json_decode($data, true))) {
if ($info['name'] && $info['phone'] && $info['iblock']) {


    global $USER;
    $userId = $USER->GetID();
    $el = new CIBlockElement;
    
    $title = "Вопрос с формы обратной связи от пользователя ".$info['NAME']."";
    $arParams_trans = array("replace_space"=>"-","replace_other"=>"-");
    $trans = Cutil::translit($title,"ru",$arParams_trans);

    $arLoadProductArray = Array(
        "IBLOCK_ID"          => 25,
        "NAME"               => $title,
        "CODE"               => $trans.'-'.mktime(),
        "ACTIVE"             => "N",
        "PREVIEW_TEXT"       => $info['question'],
        "DETAIL_TEXT"	     => "",
        "PROPERTY_VALUES"    => array(
            "FBF_UID"        => $userId,
            "FBF_NAME"       => $info['name'],
            "FBF_PHONE"      => $info['phone'],
        ),
        "DATE_ACTIVE_FROM" => date("d.m.Y H:i:s"),
        "DETAIL_PICTURE" => "",
    );
    if($PRODUCT_ID = $el->Add($arLoadProductArray)){
        $arMailFields = array(
            "USER_NAME"      => $info['name'],
            "USER_PHONE"     => $info['phone'],
            "RESPONSE"       => $info['question'],
            "DATE"           => date("d.m.Y H:i:s")
        );
        $result = CEvent::Send('QUESTION_FROM_FBF', SITE_ID, $arMailFields);
        if ($result) {
            echo json_encode([
                'status' => 'Спасибо. Ваш вопрос отправлен на модерацию.'
            ]);
        }
    }

}}}
