<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тест");
?>
ttt.php
<?
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs' . '/a_request.log', 'ttt ', FILE_APPEND);
file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/a_request.log', $_SERVER['DOCUMENT_ROOT'], FILE_APPEND);
;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>