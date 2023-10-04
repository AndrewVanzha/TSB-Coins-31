<?
/*
Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    'ExportToB24' => '/local/php_interface/classes/ExportToB24.php',
    'UpdateStatusB24' => '/local/php_interface/classes/UpdateStatusB24.php',
]);
/*/
Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    'PushToB24' => '/local/php_interface/classes/Crm/PushToB24.php',
    // Crm\ExportToB24::class => '/local/php_interface/classes/Crm/PushToB24.php',
]);

?>