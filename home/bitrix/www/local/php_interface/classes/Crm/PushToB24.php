<?php
//namespace Crm;

include_once($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/vendor/crest/crest.php');

ini_set("memory_limit", "2028M");
ini_set("post_max_size", "512M");
ini_set("upload_max_filesize", "512M");
ini_set("max_execution_time", "900000");
ini_set("max_input_time", "6000");
ini_set('auto_detect_line_endings', '1');

use Bitrix\Sale,
    Bitrix\Main,
    Bitrix\Main\Event,
    Bitrix\Main\Type\DateTime;
Bitrix\Main\Loader::includeModule('sale');

use Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    //Bitrix\Sale\PaySystem,
    //Bitrix\Main\Service\GeoIp,
    Bitrix\Catalog;
Bitrix\Main\Loader::includeModule('iblock');

class PushToB24
{
    //URL вебхука
    private const URL = 'https://andreww1000.h1n.ru/rest/1/9ha6yox0tcv5v5xv/';

    public static function exportOrder(Bitrix\Main\Event $event)
    //public static function exportOrder()
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/a_exportOrder.log', ' exportOrder '.__DIR__, FILE_APPEND);
        self::log2file(" IS_NEW: " . $event->getParameter("IS_NEW"),'-start--IS_NEW');
        //echo '123';
    }

    //Функция логирования в файл
    /**
     * @param $str string строка в протокол
     * @param $fn string модификатор имени файла
     * @return string
     */
    private static function log2file($str, $fn = null, $folder=__DIR__."/logPushToB24/")
    {
        if(!file_exists($folder))
        {
            mkdir($folder, 0777, true);
        }
        $error = "";
        $fn = $fn ? "-" . str_replace(['\\', '/', ' '], '', $fn) : "";
        $fp = fopen($folder . date("Y") . "-log2file{$fn}.log", "a");
        $test = fwrite($fp, date("Y-m-d H:i:s") . ";" . var_export($str,true) . "\r\n");

        if (!$test) {
            $error = "Ошибка при записи в файл " . $folder . date("Y") . "-log2file{$fn}.log";
        }
        fclose($fp);

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/a_exportOrder_error.log', $error, FILE_APPEND);
        return $error;
    }
}