<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle("Генератор отчета по заказам");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
CJSCore::Init(array("jquery"));
?>
<div class="adm-block-wrapper">
    <form action="report_step.php" method="post">
        <input type="text" placeholder="Дата с" onclick="BX.calendar({node: this, field: this, bTime: false});"
               name="dateFrom">
        <input type="text" placeholder="Дата по" onclick="BX.calendar({node: this, field: this, bTime: false});"
               name="dateTo">
        <button type="submit" class="adm-btn adm-btn-save">Создать отчет</button>
    </form>
</div>
<?
require($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog_admin.php"); ?>
