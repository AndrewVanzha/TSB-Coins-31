<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Правила продажи монет из драгоценных металов дистанционным способом");
$APPLICATION->SetTitle("Правила продажи монет из драгоценных металлов дистанционным способом");

$APPLICATION->SetAdditionalCSS("/pravila-prodazhi/sell-rules.css");
?>

<div class="content-container">
    <div class="container-758">
        <h1 class="heading-1 sell-rules__title">Правила продажи</h1>

        <div class="sell-rules__rules-text main-text">
            <?
				$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/local/include/page_terms_sale.php"
					)
				);
			?>
        </div>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>