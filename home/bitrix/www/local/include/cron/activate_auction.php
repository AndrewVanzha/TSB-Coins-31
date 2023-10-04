<?
$_SERVER["DOCUMENT_ROOT"] = "/home/coins/web/coins.tsbnk.ru/public_html";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require_once($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Loader;
use \Webtu\Auction\Handler;


if (!Loader::includeModule("webtu.auction")) { return; }

# Активируем аукционы
Handler::auctionActivate();


?>