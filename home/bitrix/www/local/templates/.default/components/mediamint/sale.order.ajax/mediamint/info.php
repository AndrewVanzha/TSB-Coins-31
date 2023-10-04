<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?if (CModule::IncludeModule("sale")):?>
	<?global $USER;?>
	<?$arFilter = Array("USER_ID" => $USER->GetID());?>
	<?$rsOrders = CSaleOrder::GetList(array("ID" => "ASC"), $arFilter);?>
	<?if($rsOrders->SelectedRowsCount()):?>
		<div id = "succes-message">
			Вы уже заказывали в нашем интернет-магазине, поэтому ваши данные заполнились автоматически. Проверьте информацию, либо внесите необходимые изменения и нажмите кнопку “Оформить заказ” 
		</div>
	<?endif;?>
<?endif;?>