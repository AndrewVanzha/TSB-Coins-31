<?php
foreach( [
    /**
     * File for other kernel data:
     *    Service local integration
     *    Env file with local variables
     *        external service credentials
     *        feature enable flags
     */
    __DIR__.'/kernel.php',
	
	/**
     * Classes loader subscribe
     */
    __DIR__.'/autoload.php',

    /**
     * Events subscribe
     */
    __DIR__.'/events.php',

    /**
     * Include composer libraries
     */
    __DIR__.'/vendor/autoload.php',

    /**
     * Include old legacy code
     *   constant initiation etc
     */
    __DIR__.'/legacy.php',
	
	
	/**
     * Castom portal subscribe
     */
	__DIR__ . '/workflow/custom.php',
    ]
    as $filePath )
{
    if ( file_exists($filePath) )
    {
        require_once($filePath);
    }
}
unset($filePath);

CModule::AddAutoloadClasses(
    '', // не указываем имя модуля
    array(
        // ключ - имя класса, значение - путь относительно корня сайта к файлу с классом
        '\debugg\oop\dvlp' => "/local/php_interface/debugg.oop/dvlp.php",
    )
);
if (!function_exists("debugg")) {
    function debugg($data)
    {
        global $USER;
        if($USER->GetID() == 3814) {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
    }
}
?>