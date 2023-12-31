<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?php
use Bitrix\Main\Loader,
    Bitrix\Highloadblock as HL,
    Bitrix\Main\Entity;

$arOfficesCashCard = [];
Loader::includeModule("highloadblock");
$hlblock = HL\HighloadBlockTable::getById(2)->fetch(); // highload = 2

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$result = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "DESC"),
    //"filter" => array("UF_STORE_PAYMENT"=>"Значение 1","UF_STORE_PAYMENT"=>'Значение 2') //Фильтрация выборки
));
while ($ar_list = $result->Fetch()) {
    //debugg($ar_list);
    $arOfficesCashCard[] = $ar_list['UF_STORE_PAYMENT'];
}
//debugg($arOfficesCashCard);
?>
<div class = "section">
	<script>
		function changePaySystem(param) {
			//// PAY_CURRENT_ACCOUNT checkbox should act as radio
			if (BX("account_only") && BX("account_only").value == 'Y') {
				if (param == 'account') {
					if (BX("PAY_CURRENT_ACCOUNT")) {
						BX("PAY_CURRENT_ACCOUNT").checked = true;
						BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
						BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');

						// deselect all other
						var el = document.getElementsByName("PAY_SYSTEM_ID");
						for(var i = 0; i < el.length; i++)
							el[i].checked = false;
					}
				} else {
					BX("PAY_CURRENT_ACCOUNT").checked = false;
					BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
					BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
				}
			}
			else if (BX("account_only") && BX("account_only").value == 'N') {
				if (param == 'account') {
					if (BX("PAY_CURRENT_ACCOUNT")) {
						BX("PAY_CURRENT_ACCOUNT").checked = !BX("PAY_CURRENT_ACCOUNT").checked;

						if (BX("PAY_CURRENT_ACCOUNT").checked) {
							BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
							BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
						}
						else {
							BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
							BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
						}
					}
				}
			}

			submitForm();
		}
	</script>

	<div class = "bx_section">
		<div class = "ajorder-section">
			<h4 class = "ajorder-section_header"><?=GetMessage("SOA_TEMPL_PAY_SYSTEM");?></h4>
			<?if ($arResult["PAY_FROM_ACCOUNT"] == "Y"):?>
				<?$accountOnly = ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N";?>
				<input type = "hidden" id = "account_only" value = "<?=$accountOnly?>" />
				<div class = "bx_block w100 vertical">
					<div class = "bx_element">
						<input type = "hidden" name = "PAY_CURRENT_ACCOUNT" value = "N">
						<label for = "PAY_CURRENT_ACCOUNT" id = "PAY_CURRENT_ACCOUNT_LABEL" onclick = "changePaySystem('account');" class = "<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y") echo "selected"?>">
							<input type = "checkbox" name = "PAY_CURRENT_ACCOUNT" id = "PAY_CURRENT_ACCOUNT" value = "Y"<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y") echo " checked=\"checked\"";?>>
							<div class = "bx_logotype">
								<span style = "background-image:url(<?=$templateFolder?>/images/logo-default-ps.gif);"></span>
							</div>
							<div class = "bx_description">
								<strong><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT")?></strong>
								<p>
									<div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT1")." <b>".$arResult["CURRENT_BUDGET_FORMATED"];?></b></div>
									<? if ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y"):?>
										<div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT3")?></div>
									<? else:?>
										<div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT2")?></div>
									<? endif;?>
								</p>
							</div>
						</label>
						<div class = "clear"></div>
					</div>
				</div>
			<?endif;?>

			<?uasort($arResult["PAY_SYSTEM"], "cmpBySort");?>
            <?
            //debugg('$officeCashCard');
            //debugg($arResult['OFFICE_CASH_CARD']);
            //debugg($arResult["PAY_SYSTEM"]);
            if( in_array($arResult['OFFICE_CASH_CARD'] ,$arOfficesCashCard) ) {
                for ($ii=0; $ii<count($arResult["PAY_SYSTEM"]); $ii++) {
                    if ($arResult["PAY_SYSTEM"][$ii]['ID'] == 11) {  // Наличными в кассе банка - не нужна
                        unset($arResult["PAY_SYSTEM"][$ii]);
                    }
                }
            } else {
                for ($ii=0; $ii<count($arResult["PAY_SYSTEM"]); $ii++) {
                    if ($arResult["PAY_SYSTEM"][$ii]['ID'] == 16) {  // Наличный расчет или картой в кассе банка - не нужна
                        unset($arResult["PAY_SYSTEM"][$ii]);
                    }
                }
            }
            /*if ($arResult["DELIVERY"][3]['CHECKED'] == 'Y') {  // Самовывоз
                for ($ii=0; $ii<count($arResult["PAY_SYSTEM"]); $ii++) {
                    debugg($arResult["PAY_SYSTEM"][$ii]['ID']);
                    debugg($arResult["PAY_SYSTEM"][$ii]['NAME']);
                    if ($arResult["PAY_SYSTEM"][$ii]['ID'] == 15) {  // Онлайн оплата на сайте - не нужна
                        unset($arResult["PAY_SYSTEM"][$ii]);
                    }
                }
            }*/
            //debugg($arResult["PAY_SYSTEM"]);
            ?>
			<div class = "ajorder-section-inner">
				<div class = "ajorder-radios-wrapper">
					<div class = "ajorder-radios">
						<?foreach($arResult["PAY_SYSTEM"] as $arPaySystem):?>
							<?if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) > 0 || intval($arPaySystem["PRICE"]) > 0):?>
								<?if (count($arResult["PAY_SYSTEM"]) == 1):?>
									<div class = "bx_element">
										<input type = "hidden" name = "PAY_SYSTEM_ID" value = "<?=$arPaySystem["ID"];?>">
										<div class = "ajorder-radio">
                                            <? debugg('ppp1'); ?>
											<input type = "radio" id = "ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"];?>" name = "PAY_SYSTEM_ID" value = "<?=$arPaySystem["ID"];?>" <?if ($arPaySystem["CHECKED"] == "Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y")) echo " checked=\"checked\"";?> onclick = "changePaySystem();">
											<label for = "ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"];?>" onclick = "BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"];?>').checked = true;changePaySystem();">
												<?if (count($arPaySystem["PSA_LOGOTIP"]) > 0):?>
													<?$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];?>
												<?else:?>
													<?$imgUrl = $templateFolder."/images/logo-default-ps.gif";?>
												<?endif;?>
												<div class = "radio-img-wrapper">
													<img src = "<?=$imgUrl?>" alt = "<?=$arPaySystem['PSA_NAME'];?>">
												</div>
												<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
													<div class = "radio-text">
														<?=$arPaySystem["PSA_NAME"];?>
														<?/*
														<?if (intval($arPaySystem["PRICE"]) > 0):?>
															<?=str_replace("#PAYSYSTEM_PRICE#", SaleFormatCurrency(roundEx($arPaySystem["PRICE"], SALE_VALUE_PRECISION), $arResult["BASE_LANG_CURRENCY"]), GetMessage("SOA_TEMPL_PAYSYSTEM_PRICE"));?>
														<?else:?>
															<?=$arPaySystem["DESCRIPTION"];?>
														<?endif;?>
														*/?>
													</div>
												<?endif;?>
											</label>
										</div>
									</div>
								<?else:?>
									<div class = "bx_element">
										<div class = "ajorder-radio">
                                            <? debugg('ppp2'); ?>
											<input type = "radio" id = "ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"];?>" name = "PAY_SYSTEM_ID" value = "<?=$arPaySystem["ID"];?>" <?if ($arPaySystem["CHECKED"] == "Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y")) echo " checked=\"checked\"";?> onclick = "changePaySystem();" />
											<label for = "ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"];?>" onclick = "BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"];?>').checked = true;changePaySystem();">
												<?if (count($arPaySystem["PSA_LOGOTIP"]) > 0):?>
													<?$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];?>
												<?else:?>
													<?$imgUrl = $templateFolder."/images/logo-default-ps.gif";?>
												<?endif;?>
												<div class = "radio-img-wrapper">
													<img src = "<?=$imgUrl?>" alt = "<?=$arPaySystem['PSA_NAME'];?>">
												</div>
												<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
													<div class = "radio-text">
														<?=$arPaySystem["PSA_NAME"];?>
														<?/*
														<?if (intval($arPaySystem["PRICE"]) > 0):?>
															<?=str_replace("#PAYSYSTEM_PRICE#", SaleFormatCurrency(roundEx($arPaySystem["PRICE"], SALE_VALUE_PRECISION), $arResult["BASE_LANG_CURRENCY"]), GetMessage("SOA_TEMPL_PAYSYSTEM_PRICE"));?>
														<?else:?>
															<?=$arPaySystem["DESCRIPTION"];?>
														<?endif;?>
														*/?>
													</div>
												<?endif;?>
											</label>
										</div>
									</div>
								<?endif;?>
							<?endif;?>

							<?if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) == 0 && intval($arPaySystem["PRICE"]) == 0):?>
								<?if (count($arResult["PAY_SYSTEM"]) == 1):?>
									<div class = "bx_element">
										<input type = "hidden" name = "PAY_SYSTEM_ID" value = "<?=$arPaySystem["ID"];?>">
										<div class = "ajorder-radio">
                                            <? debugg('ppp3'); ?>
											<input type = "radio" id = "ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" name = "PAY_SYSTEM_ID" value = "<?=$arPaySystem["ID"]?>" <?if ($arPaySystem["CHECKED"] == "Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y")) echo " checked=\"checked\"";?> onclick = "changePaySystem();">
											<label for = "ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick = "BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked = true;changePaySystem();">
												<?if (count($arPaySystem["PSA_LOGOTIP"]) > 0):?>
													<?$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];?>
												<?else:?>
													<?$imgUrl = $templateFolder."/images/logo-default-ps.gif";?>
												<?endif;?>
												<div class = "radio-img-wrapper">
													<img src = "<?=$imgUrl?>" alt = "<?=$arPaySystem['PSA_NAME'];?>">
												</div>
												<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
													<div class = "radio-text">
														<?=$arPaySystem["PSA_NAME"];?>
													</div>
												<?endif;?>
											</label>
										</div>
									</div>
								<?else:?>
									<div class = "bx_element">
										<div class = "ajorder-radio">
                                            <? debugg('ppp4'); ?>
											<input type = "radio" id = "ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" name = "PAY_SYSTEM_ID" value = "<?=$arPaySystem["ID"]?>" <?if ($arPaySystem["CHECKED"] == "Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y")) echo " checked=\"checked\"";?> onclick = "changePaySystem();">
											<label for = "ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked = true;changePaySystem();">
												<?if (count($arPaySystem["PSA_LOGOTIP"]) > 0):?>
													<?$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];?>
												<?else:?>
													<?$imgUrl = $templateFolder."/images/logo-default-ps.gif";?>
												<?endif;?>
												<div class = "radio-img-wrapper">
													<img src = "<?=$imgUrl?>" alt = "<?=$arPaySystem['PSA_NAME'];?>">
												</div>
												<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
													<div class = "radio-text">
														<?=$arPaySystem["PSA_NAME"];?>
													</div>
												<?endif;?>
											</label>
										</div>
									</div>
								<?endif;?>
							<?endif;?>
						<?endforeach;?>
					</div>
					<div class = "ajorder-current-radio-info">
						<?foreach ($arResult["PAY_SYSTEM"] as $k => $v):?>
							<?if($v["CHECKED"] == "Y"):?>
								<div class = "ajorder-current-radio-text">
									<span>Ваш выбор:</span> <?=$v["NAME"];?>
                                    <br>
                                    <span>Оплата возможна только после подтверждения заявки</span>
								</div>
								<?break;?>
							<?endif;?>
						<?endforeach;?>
						<div class = "ajorder-current-radio-price"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>