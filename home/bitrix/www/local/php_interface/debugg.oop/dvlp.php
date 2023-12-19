<?php
namespace Debugg\Oop;

class Dvlp
{
    /**
     * Печать инфо на экран
     *
     * @param $date
     */
    public static function debug($data)
    {
        global $USER;
        if ($USER->GetID() == 3814) {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
    }

    public static function debugg($data)
    {
        self::debug($data);
    }

    /**
     * Запись json-логов в файл
     *
     * @param $type
     * @param $message
     * @param string $flag
     */
    public static function logger($type, $message, $flag='w')
    {
        if ($flag == 'a') {
            if (!empty($message)) {
                file_put_contents(
                    $_SERVER["DOCUMENT_ROOT"] .
                    '/logs/' .
                    $type .  '_' . date('YW') .'.log',
                    json_encode($message, JSON_UNESCAPED_UNICODE),
                    FILE_APPEND
                );
            }
        } else {
            if (!empty($message)) {
                file_put_contents(
                    $_SERVER["DOCUMENT_ROOT"] .
                    '/logs/' .
                    //$type .  '_' . date('YW') .'.log',
                    $type .  '_' . date('Ym') .'.log',
                    json_encode($message, JSON_UNESCAPED_UNICODE)
                );
            }
        }
        // $text = base64_encode(serialize($array));
        // $array = unserialize(base64_decode($text));
    }
}