<?php
header('Content-Type: application/json');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

function getUserDesired($userId) {
    if ($userId) {
        $rsUser = CUser::GetByID($userId);
        $arUser = $rsUser->Fetch(); 
        $desiredArr =  $arUser['UF_DESIRED'] ? explode(',', $arUser['UF_DESIRED']) : [];
        return $desiredArr;
    } else {
        $desiredArr = $_COOKIE['desired'] ? explode(',', $_COOKIE['desired']) : [];
        return $desiredArr;
    }
}
function setUserDesired($userId, $desiredArr) {
    $desiredStr = implode(',', $desiredArr);
    if ($userId) {
        $user = new CUser;
        $user->Update($userId, ['UF_DESIRED' => $desiredStr]);
        $strError = '';
        $strError .= $user->LAST_ERROR;
        return $strError;
    } else {
        setcookie('desired', $desiredStr, time()+60*60*24*30, '/');
        return '';
    }
}


if ($data = file_get_contents('php://input')) {
if ($itemInfo = (json_decode($data, true))) {

    global $USER;
    $currUserId = $USER->GetID();
    $desired = getUserDesired($currUserId);

    $id = (int)$itemInfo['id'];
    $add = $itemInfo['add'];
    $inDesired = in_array($id, $desired);

    $shouldAction = $inDesired != $add;
    $newDesired = [];
    if ($shouldAction) {
        if ($add) {
            $newDesired = array_merge($desired, [$id]);
        } else {
            foreach ($desired as $key => $value) {
                if ($value != $id) {
                    $newDesired[] = $value;
                }
            }
        }
        $err = setUserDesired($currUserId, $newDesired);
    } else {
        $newDesired = $desired;
    }
    

    echo json_encode([
        'quant' => count($newDesired),
        'desired' => $newDesired,
        'error' => $err
    ]);

}}