<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
// $bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
// $colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
// $bPropsColumn = false;
// $bUseDiscount = false;
// $bPriceType = false;
// $bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
?>
<?// echo '<pre>'; print_r($arResult); echo '</pre>';?>

<div class = "ajorder-section">
	<h4 class = "ajorder-section_header"><?=GetMessage("SALE_PRODUCTS_SUMMARY");?></h4>
	<div class = "ajorder-section-inner">
		<div class = "ajorder-basket">
			<?foreach ($arResult["GRID"]["ROWS"] as $k => $arData):?>
				<?if (strlen($arData["data"]["PREVIEW_PICTURE_SRC"]) > 0):?>
					<?$url = $arData["data"]["PREVIEW_PICTURE_SRC"];?>
				<?elseif (strlen($arData["data"]["DETAIL_PICTURE_SRC"]) > 0):?>
					<?$url = $arData["data"]["DETAIL_PICTURE_SRC"];?>
				<?else:?>
					<?$url = $templateFolder."/images/no_photo.png";?>
				<?endif;?>
				<div class = "ajorder-basket-item">
					<a class = "basket-item-img_name" href = "<?=$arData["data"]["DETAIL_PAGE_URL"];?>">
						<img src = "<?=$url;?>" alt = "<?=$arData['data']['NAME'];?>">
						<div class = "basket-item-name"><?=$arData["data"]["NAME"];?></div>
					</a>
					<div class = "basket-item-dopinfo-wrapper">
						<div class = "basket-item-dopinfo">
							<div class = "name">Артикул</div>
							<div class = "value"><?=$arData["data"]["PROPERTY_ARTICLE_VALUE"];?></div>
						</div>
						<div class = "basket-item-dopinfo">
							<div class = "name">Количество</div>
							<div class = "value"><?=$arData["data"]["QUANTITY"]?> шт</div>
						</div>
						<div class = "basket-item-dopinfo">
							<div class = "name">Сумма</div>
							<div class = "value"><?=$arData["data"]["SUM"];?></div>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
</div>