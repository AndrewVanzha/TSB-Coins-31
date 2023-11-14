<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/vendor/crest/crest.php');
// https://mrcappuccino.ru/blog/post/work-with-order-bitrix-d7
// https://dev.1c-bitrix.ru/api_help/sale/events/events_status_order.php
// https://www.sng-it.ru/snippet/sobytiya-izmeneniya-zakaza-dlya-vebkhukov-v-bitriks24.html
// https://github.com/studiofact/wiki-bitrix/wiki

use Bitrix\Main;
use \Bitrix\Sale;
use \Bitrix\Sale\Order;

class UpdateStatusInB24
{
    private const TOKEN = '9ha6yox0tcv5v5xv';
    const DOMEN = 'andreww1000.h1n.ru';

    const LOG_FILE = '/home/bitrix/www/local/php_interface/logs/';
    private static $status = 'N';
    private static $stageId = 'C1:NEW';
    private static $orderId = null;
    private static $order = null;
    private static $dealId = null;
    private static $deal = null;
    private static $oldStageId = null;
    private static $assignedId = null;
    private static $oldAssignedId = null;
    private static $delivery = null;
    //private static $categoryId = 1;
    //private static $pickupId = 0;

    //public function updateOrder( $orderId, $status )// для OnSaleStatusOrder
    public function updateOrder($event) // для OnSaleStatusOrderChange
    {
        self::log2file('start', 'start');
        file_put_contents(__DIR__ . '/a_updateOrder.log', ' updateOrder ', FILE_APPEND);

    }

    private static function log2file($arr, $fn = null, $folder=__DIR__.'/logStatusUpdate/')
    {
        $dir = $folder;

        if(!file_exists($dir))
        {
            mkdir($dir, 0777, true);
        }

        $error = "";
        $fn = $fn ? "-" . str_replace(['\\', '/', ' '], '', $fn) : "";
        $fp = fopen($dir . date("Y-m-d H:i:s") .'-'. round(microtime(true) * 1000) . "-log2file{$fn}.log", "a");
        $test = fwrite($fp, date("Y-m-d H:i:s") . "\r\n" . var_export($arr, true) . "\r\n");


        if (!$test) {
            $error = "Ошибка при записи в файл " . $dir . date("Y") . "-log2file{$fn}.log";
            $test = fwrite($fp, date("Y-m-d H:i:s") . ";" . $error . "\r\n");
        }
        fclose($fp);

        return $error;
    }
}