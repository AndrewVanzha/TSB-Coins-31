<?php
header('Content-Type: application/json');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');

if ($data = file_get_contents('php://input')) {
if ($info = (json_decode($data, true))) {
if ($info['name'] && $info['phone'] && $info['iblock'] && $info['productid']) {

    $array_list = CIBlockElement::GetList(
        array("SORT"=>"ASC"), 
        array(
            "IBLOCK_ID"=>$info['iblock'],
            "ID"=>$info['productid'],
            "ACTIVE" => "Y"), 
        false, 
        false,
        array("IBLOCK_ID","ID","NAME") 
    );
    if($ob = $array_list->GetNextElement()){
        $productFields = $ob->GetFields();
    }
    global $USER;
    $userId = $USER->GetID();
    $el = new CIBlockElement;
    
    $title = "Отзыв на товара ID=".$productFields['ID']. " (".$productFields['NAME'].")";
    $arParams_trans = array("replace_space"=>"-","replace_other"=>"-");
    $trans = Cutil::translit($title,"ru",$arParams_trans);

    $arLoadProductArray = Array(
        "IBLOCK_ID"          => 11,
        "NAME"               => $title,
        "CODE"               => $trans.'-'.mktime(),
        "ACTIVE"             => "N",
        "PREVIEW_TEXT"       => $info['question'],
        "DETAIL_TEXT"	     => "",
        "PROPERTY_VALUES"    => array(
            "PRODUCT_ID"     => $productFields['ID'],
            "USER_ID"        => $userId,
            "USER_NAME"      => $info['name'],
            "USER_PHONE"     => $info['phone']
        ),
        "DATE_ACTIVE_FROM" => date("d.m.Y H:i:s"),
        "DETAIL_PICTURE" => "",
    );
    if($PRODUCT_ID = $el->Add($arLoadProductArray)){
        $arMailFields = array(
            "PRODUCT_ID"     => $productFields['ID'],
            "USER_NAME"      => $info['name'],
            "USER_PHONE"     => $info['phone'],
            "RESPONSE"       => $info['question'],
            "DATE"           => date("d.m.Y H:i:s")
        );
        $result = CEvent::Send('FEEDBACK_RESPONS_PRODUCT', SITE_ID, $arMailFields);
        if ($result) {
            echo json_encode([
                'status' => 'Спасибо. Ваш отзыв отправлен на модерацию'
            ]);
        }
    }

}}}
