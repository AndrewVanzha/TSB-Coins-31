<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="content-container container-ordersuc">
	<h1 class="heading-1">Оформление заказа</h1>
<?
	if (!empty($arResult["ORDER"]))
	{
		?>
		<div class="ordersuc-header">Заказ сформирован</div>
		<p>
		<?= GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?>
		</p>
		<p>
		<?= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
		</p>
		<p class="bold">
		Обратите внимание что опалата заказа будет доступна после подтверждения паспортных данных в личном кабинете.
		</p>
					
	<?
	}
	else
	{
		?>
		<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

		<table class="sale_order_full_table">
			<tr>
				<td>
					<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
					<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
				</td>
			</tr>
		</table>
		<?
	}
?>
</div>
