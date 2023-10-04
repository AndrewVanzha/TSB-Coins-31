<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<div class = "ajorder-results">
	<div class = "ajorder-results_delivery">
		<div class = "delivery-name">Доставка:</div>
		<div class = "delivery-value">
			<?foreach ($arResult["DELIVERY"] as $k => $v):?>
				<?if($v["CHECKED"] == "Y"):?>
					<?=$v["NAME"];?>
					<?break;?>
				<?endif;?>
			<?endforeach;?>
		</div>
	</div>
	<div class = "ajorder-results_alttext">При оплате инвестиционных монет (Георгий Победоносец) банковской картой к цене добавляется комиссия 2,5 %</div>
	<div class = "ajorder-results_mwrapper">
		<div class = "ajorder-results_sum">
			<div class = "results_sum-name">Сумма:</div>
			<div class = "results_sum-price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"];?></div>
		</div>

		<a id = "ORDER_CONFIRM_BUTTON" class = "ajorder-makeorder" href = "javascript:void();" onclick = "submitForm('Y'); return false;">Оформить <span>заказ</span></a>
	</div>
	<div class = "ajorder-pages-links">
		<a href = "/dostavka-i-oplata/" class = "ajorder-page-links__link delivery-detail">Подробнее о доставке</a>
		<a href = "/dostavka-i-oplata/" class = "ajorder-page-links__link payment-detail">Подробнее об оплате</a>
		<a href = "/pravila-prodazhi/" class = "ajorder-page-links__link sell-rules">Правила продажи</a>
	</div>
</div>