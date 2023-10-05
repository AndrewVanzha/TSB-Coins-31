<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?php
debugg('delivery');
$ar_cities = [];
foreach ($arResult['CITY_ADDRESSES'] as $item) {
    $ar_cities[] = $item['CITY'];
}
$ar_cities = array_unique($ar_cities);
debugg($ar_cities);
if (!in_array($arResult["CITY_PLACE"]['VALUE'], $ar_cities)) {
    debugg('N');
    $arResult["DELIVERY"][25]['CHECKED'] = 'Y';  // id=25
    unset($arResult["DELIVERY"][3]);  // id=3 - самовывоз убираю
}
debugg('$_REQUEST BUYER_STORE');
debugg($_REQUEST['BUYER_STORE']);
debugg('arResult["BUYER_STORE"]');
debugg($arResult["BUYER_STORE"]);

$arResult["STORE_LIST_MDFD"] = [];  // $arResult["CITY_PLACE"]["VALUE"] - выбранный город
foreach ($arResult["STORE_LIST"] as $ix=>$store_list) {
    $city_name = str_replace(' ', '', $arResult["CITY_PLACE"]["VALUE"]);
    $store_list_address = str_replace(' ', '', $store_list['ADDRESS']);
    if (mb_strripos($store_list_address, $city_name)) {
        $arResult["STORE_LIST_MDFD"][$ix] = $store_list;
    }
}
debugg($arResult["STORE_LIST_MDFD"]);

if ($_REQUEST['BUYER_STORE']) {
    if ($_REQUEST['BUYER_STORE'] == 0) {
        //debugg('BUYER_STORE = 0');
        //debugg($arResult["STORE_LIST_MDFD"]);
    } else {
        debugg('$_REQUEST BUYER_STORE');
        debugg($_REQUEST['BUYER_STORE']);
        debugg('arResult["BUYER_STORE"]');
        debugg($arResult["BUYER_STORE"]);
        $shop_num = $_REQUEST['BUYER_STORE'];
        if (!array_key_exists($shop_num, $arResult["STORE_LIST_MDFD"])) {
            $arResult["BUYER_STORE"] = array_key_first($arResult["STORE_LIST_MDFD"]); // номер магазина с меньшим sort в регионе
            debugg('arResult["BUYER_STORE"]');
            debugg($arResult["BUYER_STORE"]);
        }
    }
}
?>
<script>
	function fShowStore(id, showImages, formWidth, siteId)
	{
		// var strUrl = '<?//=$templateFolder?>' + '/map.php';
		// var strUrlPost = 'delivery=' + id + '&showImages=' + showImages + '&siteId=' + siteId;

		// var storeForm = new BX.CDialog({
		// 			'title': '<?//=GetMessage('SOA_ORDER_GIVE')?>',
		// 			head: '',
		// 			'content_url': strUrl,
		// 			'content_post': strUrlPost,
		// 			'width': 600,
		// 			'height':450,
		// 			'resizable':false,
		// 			'draggable':true
		// 		});

		// var button = [
		// 		{
		// 			title: '<?//=GetMessage('SOA_POPUP_SAVE')?>',
		// 			id: 'crmOk',
		// 			'action': function ()
		// 			{
		// 				GetBuyerStore();
		// 				BX.WindowManager.Get().Close();
		// 			}
		// 		},
		// 		BX.CDialog.btnCancel
		// 	];
		// storeForm.ClearButtons();
		// storeForm.SetButtons(button);
		// storeForm.Show();
	}

	function GetBuyerStore()
	{
		BX('BUYER_STORE').value = BX('POPUP_STORE_ID').value;
		//BX('ORDER_DESCRIPTION').value = '<?//=GetMessage("SOA_ORDER_GIVE_TITLE")?>: '+BX('POPUP_STORE_NAME').value;
		BX('store_desc').innerHTML = BX('POPUP_STORE_NAME').value;
		BX.show(BX('select_store'));
	}

	function showExtraParamsDialog(deliveryId)
	{
		var strUrl = '<?=$templateFolder?>' + '/delivery_extra_params.php';
		var formName = 'extra_params_form';
		var strUrlPost = 'deliveryId=' + deliveryId + '&formName=' + formName;

		if (window.BX.SaleDeliveryExtraParams)
		{
			for(var i in window.BX.SaleDeliveryExtraParams)
			{
				strUrlPost += '&'+encodeURI(i)+'='+encodeURI(window.BX.SaleDeliveryExtraParams[i]);
			}
		}

		var paramsDialog = new BX.CDialog({
			'title': '<?=GetMessage('SOA_ORDER_DELIVERY_EXTRA_PARAMS')?>',
			head: '',
			'content_url': strUrl,
			'content_post': strUrlPost,
			'width': 500,
			'height':200,
			'resizable':true,
			'draggable':false
		});

		var button = [
			{
				title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
				id: 'saleDeliveryExtraParamsOk',
				'action': function ()
				{
					insertParamsToForm(deliveryId, formName);
					BX.WindowManager.Get().Close();
				}
			},
			BX.CDialog.btnCancel
		];

		paramsDialog.ClearButtons();
		paramsDialog.SetButtons(button);
		//paramsDialog.adjustSizeEx();
		paramsDialog.Show();
	}

	function insertParamsToForm(deliveryId, paramsFormName)
	{
		var orderForm = BX("ORDER_FORM"),
			paramsForm = BX(paramsFormName);
			wrapDivId = deliveryId + "_extra_params";

		var wrapDiv = BX(wrapDivId);
		window.BX.SaleDeliveryExtraParams = {};

		if (wrapDiv)
			wrapDiv.parentNode.removeChild(wrapDiv);

		wrapDiv = BX.create('div', {props: { id: wrapDivId}});

		for(var i = paramsForm.elements.length-1; i >= 0; i--)
		{
			var input = BX.create('input', {
				props: {
					type: 'hidden',
					name: 'DELIVERY_EXTRA['+deliveryId+']['+paramsForm.elements[i].name+']',
					value: paramsForm.elements[i].value
					}
				}
			);

			window.BX.SaleDeliveryExtraParams[paramsForm.elements[i].name] = paramsForm.elements[i].value;

			wrapDiv.appendChild(input);
		}

		orderForm.appendChild(wrapDiv);

		BX.onCustomEvent('onSaleDeliveryGetExtraParams',[window.BX.SaleDeliveryExtraParams]);
	}
</script>

<input type = "hidden" name = "BUYER_STORE" id = "BUYER_STORE" value = "<?=$arResult["BUYER_STORE"]?>">

<div class = "bx_section">
	<?if (!empty($arResult["DELIVERY"])):?>
		<?$width = ($arParams["SHOW_STORES_IMAGES"] == "Y") ? 40 : 40;?>
		<div class = "ajorder-section">
			<h4 class = "ajorder-section_header"><?=GetMessage("SOA_TEMPL_DELIVERY");?></h4>
			<div class = "ajorder-section-inner">
				<div class = "ajorder-radios-wrapper">
					<div class = "ajorder-radios">
						<?foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery):?>
							<?if ($delivery_id !== 0 && intval($delivery_id) <= 0):?>
								<?foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile):?>
									<div class = "bx_element ajorder-radio">
										<input type = "radio" id = "ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>" name = "<?=htmlspecialcharsbx($arProfile["FIELD_NAME"])?>" value = "<?=$delivery_id.":".$profile_id;?>" <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?> onclick = "submitForm();">
										<label for = "ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>">
											<?if (count($arDelivery["LOGOTIP"]) > 0):?>
												<?$arFileTmp = CFile::ResizeImageGet($arDelivery["LOGOTIP"]["ID"], array("width" => "40", "height" =>"40"), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
												<?$deliveryImgURL = $arFileTmp["src"];?>
											<?else:?>
												<?$deliveryImgURL = $templateFolder."/images/logo-default-d.gif";?>
											<?endif;?>

											<?if ($arDelivery["ISNEEDEXTRAINFO"] == "Y"):?>
												<?$extraParams = "showExtraParamsDialog('".$delivery_id.":".$profile_id."');";?>
											<?else:?>
												<?$extraParams = "";?>
											<?endif;?>

											<div class = "radio-img-wrapper" onclick = "BX('ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>').checked = true;<?=$extraParams?>submitForm();">
												<span style = 'background-image:url(<?=$deliveryImgURL?>);'></span>
												<div class = "radio-price">
													<?if ($arProfile["CHECKED"] == "Y" && doubleval($arResult["DELIVERY_PRICE"]) > 0):?>
														<?=GetMessage("SALE_DELIV_PRICE")?>:&nbsp;<b><?=$arResult["DELIVERY_PRICE_FORMATED"]?></b>
														<?if ((isset($arResult["PACKS_COUNT"]) && $arResult["PACKS_COUNT"]) > 1):?>
															<?=GetMessage('SALE_PACKS_COUNT').': <b>'.$arResult["PACKS_COUNT"].'</b>';?>
														<?endif;?>
													<?else:?>
														<?$APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '', array(
															"NO_AJAX" => $arParams["DELIVERY_NO_AJAX"],
															"DELIVERY" => $delivery_id,
															"PROFILE" => $profile_id,
															"ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
															"ORDER_PRICE" => $arResult["ORDER_PRICE"],
															"LOCATION_TO" => $arResult["USER_VALS"]["DELIVERY_LOCATION"],
															"LOCATION_ZIP" => $arResult["USER_VALS"]["DELIVERY_LOCATION_ZIP"],
															"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
															"ITEMS" => $arResult["BASKET_ITEMS"],
															"EXTRA_PARAMS_CALLBACK" => $extraParams
														), null, array('HIDE_ICONS' => 'Y'));?>
													<?endif;?>
												</div>
											</div>
											<div class = "radio-text" onclick = "BX('ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>').checked = true;<?=$extraParams?>submitForm();"><?=htmlspecialcharsbx($arDelivery["NAME"]);?></div>
										</label>
									</div>
								<?endforeach;?>
							<?else:?>

								<?if (count($arDelivery["STORE"]) > 0):?>
									<?$clickHandler = "onClick = \"fShowStore('".$arDelivery["ID"]."','".$arParams["SHOW_STORES_IMAGES"]."','".$width."','".SITE_ID."')\";";?>
								<?else:?>
									<?//$clickHandler = "onClick = \"BX('ID_DELIVERY_ID_".$arDelivery["ID"]."').checked=true; submitForm();\"";?>
									<?$clickHandler = "onClick = \"BX('ID_DELIVERY_ID_".$arDelivery["ID"]."').checked=true; submitForm(".$arDelivery["ID"].");\"";?>
								<?endif;?>

								<div class = "bx_element ajorder-radio js-delivery-element">
                                    <? debugg('ddd2_'.$arDelivery["ID"]) ?>
									<input type = "radio"id = "ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>" name = "<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>" value = "<?= $arDelivery["ID"] ?>"<?if ($arDelivery["CHECKED"] == "Y") echo " checked";?> onclick = "submitForm();">
                                    <label for = "ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" <?=$clickHandler?>>
										<?if (count($arDelivery["LOGOTIP"]) > 0):?>
											<?$arFileTmp = CFile::ResizeImageGet($arDelivery["LOGOTIP"]["ID"], array("width" => "120", "height" =>"120"), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
											<?$deliveryImgURL = $arFileTmp["src"];?>
										<?else:?>
											<?$deliveryImgURL = $templateFolder."/images/logo-default-d.gif";?>
										<?endif;?>

										<div class = "radio-img-wrapper">
											<img src = "<?=$deliveryImgURL?>" alt = "<?=htmlspecialcharsbx($arDelivery['NAME']);?>">
											<div class = "radio-price"><?=$arDelivery["PRICE_FORMATED"];?></div>
										</div>
										<div class = "radio-text"><?=htmlspecialcharsbx($arDelivery["NAME"]);?></div>
										
									</label>
								</div>

							<?endif;?>
						<?endforeach;?>
					</div>
                    <? //debugg($arResult["STORE_LIST"]); ?>
					<div class = "ajorder-current-radio-info">
                        <? //debugg($arResult["DELIVERY"]); ?>
						<?foreach ($arResult["DELIVERY"] as $k => $v):?>
							<?if($v["CHECKED"] == "Y"):?>
								<div class = "ajorder-current-radio-text">
									Ваш выбор: <?=$v["NAME"];?>
									<?if (count($v["STORE"]) > 0):?>
										<?if ($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]):?>
											<br>
											<?=$arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"];?>
                                            <br>
											<?=$arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["ADDRESS"];?>
										<?endif;?>
									<?endif;?>
								</div>
								<div class = "ajorder-current-radio-price">Стоимость: <?=$v["PRICE_FORMATED"];?></div>
								<?break;?>
							<?endif;?>
						<?endforeach;?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>
</div>

