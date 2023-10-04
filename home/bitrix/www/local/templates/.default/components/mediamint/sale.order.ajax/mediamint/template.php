<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?php use Bitrix\Sale; ?>
<?//BEGIN CSS?>
<?$APPLICATION->SetAdditionalCSS($templateFolder."/style.css");?>
<?$APPLICATION->SetAdditionalCSS($templateFolder."/custom.css");?>
<?//END CSS?>
<?//php debugg("REDIRECT_URL ");?>
<?//php debugg($arResult["REDIRECT_URL"]);?>

<?if ($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y"):?>
	<?if ($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y"):?>
		<?if (strlen($arResult["REDIRECT_URL"]) > 0):?>
            <? file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/a_sales_redirect.log', json_encode($arResult)); ?>
			<?$APPLICATION->RestartBuffer();?>
			<script>
				window.top.location.href = '<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			</script>
			<?die();?>
		<?endif;?>
	<?endif;?>
<?endif;?>

<script>
    let local_dataLayer = [];
    function makeDataLayer(id, ar_product) {
        window.dataLayer.push({
        //dataLayer.push({
        //local_dataLayer.push({
            "ecommerce": {
                "currencyCode": "RUB",
                "purchase": {
                    "actionField": {
                        "id" : id
                    },
                    "products": ar_product,
                }
            }
        });
    }
</script>

<? if (isset($_GET["ORDER_ID"])) :
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/a_sales_request.log', json_encode($_REQUEST));
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/a_sales_session.log', json_encode($_SESSION));
    $order = Sale\Order::load($_GET["ORDER_ID"]);
    //$order = Sale\Order::loadByAccountNumber($_GET["ORDER_ID"]);
    $arOrder['ORDER_ID'] = $order->getId(); // ID заказа
    $arOrder['USER_ID'] = $order->getUserId(); // ID пользователя
    $arOrder['ORDER_SUM'] = $order->getPrice(); // Сумма заказа
    $arOrder['BASKET'] = $order->getBasket()->getBasketItems();
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/a_sales_$order.log', json_encode($arOrder));

    $orderedItems = [];
    $dbResItems = \Bitrix\Sale\Basket::getList([
        'select' => [
            'ORDER_ID',
            'PRODUCT_ID',
            'NAME',
            'PRICE',
            'QUANTITY',
            'DETAIL_PAGE_URL',
            'XML_ID',
            //'*'
        ],
        'filter' => [
            //'=ORDER_ID' => $recentOrder['ID'],
            '=ORDER_ID' => $_GET["ORDER_ID"],
        ],
    ]);
    while ($item = $dbResItems->fetch()) {
        //$orderedItems[] = $item['PRODUCT_ID'];
        $orderedItems[] = $item;
    }
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/a_sales_$orderedItems.log', json_encode($orderedItems));

    /*$dbRes = \Bitrix\Sale\Order::getList([
        'select' => ['ID'],
        'filter' => [
            "USER_ID" => $USER->GetID(),
            //"ORDER_ID" => $_GET["ORDER_ID"], // последний заказ
        ],
    ]);
    while ($recentOrder = $dbRes->fetch()) {
        $dbResItems = \Bitrix\Sale\Basket::getList([
            'select' => [
                'ORDER_ID',
                'PRODUCT_ID',
                'NAME',
                'PRICE',
                'QUANTITY',
                'DETAIL_PAGE_URL',
                'XML_ID',
                //'*'
            ],
            'filter' => [
                //'=ORDER_ID' => $recentOrder['ID'],
                '=ORDER_ID' => $_GET["ORDER_ID"],
            ],
        ]);
        while ($item = $dbResItems->fetch()) {
            //$orderedItems[] = $item['PRODUCT_ID'];
            $orderedItems[] = $item;
        }
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/a_sales_$orderedItems.log', json_encode($orderedItems));
        //file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/a_sales_$order.log', json_encode($order));
    }*/
?>
    <script>
        let order_id = '<?= $_GET["ORDER_ID"] ?>';
        let ar_ordered_items = <?php echo json_encode($orderedItems) ?>;
        let ar_product = [];

        console.log('ORDER=' + order_id);
        if(ar_ordered_items) {
            let pos = 1;
            ar_ordered_items.forEach(function (entry) {
                ar_product.push(
                    {
                        "id": entry.PRODUCT_ID,
                        "name": entry.NAME,
                        "price": entry.PRICE,
                        "category": entry.DETAIL_PAGE_URL,
                        "quantity": entry.QUANTITY,
                        "position": pos++,
                        "xml": entry.XML_ID,
                    },
                );
                //pos += 1;
            });
            makeDataLayer(order_id, ar_product);
            console.log(window.dataLayer);
            //console.log(local_dataLayer);
            /*
                window.dataLayer.push({
                    "ecommerce": {
                        "currencyCode": "RUB",
                        "purchase": {
                            "actionField": {
                                "id" : entry.ORDER_ID
                            },
                            "products": [
                                {
                                    "id": entry.ORDER_ID,
                                    "name": entry.NAME,
                                    "price": entry.PRICE,
                                    //"brand": "Яндекс / Яndex",
                                    "category": entry.DETAIL_PAGE_URL,
                                    //"variant": "Оранжевый цвет",
                                    "quantity": entry.QUANTITY,
                                    //"list": "Одежда",
                                    "position": pos++,
                                    "xml": entry.XML_ID
                                },
                            ]
                        }
                    }
                });

            */
        }
    </script>
<?php endif; ?>

<div id = "order_form_div" class = "order-checkout">

	<?//BEGIN DEFAULT FUNCTION?>
	<?if (!function_exists("getColumnName")):?>
		<?
			function getColumnName($arHeader) {
				return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
			}
		?>
	<?endif;?>

	<?if (!function_exists("cmpBySort")):?>
		<?
			function cmpBySort($array1, $array2) {
				if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
					return -1;

				if ($array1["SORT"] > $array2["SORT"])
					return 1;

				if ($array1["SORT"] < $array2["SORT"])
					return -1;

				if ($array1["SORT"] == $array2["SORT"])
					return 0;
			}
		?>
	<?endif;?>
	<?//END DEFAULT FUCNTION?>

	<div class = "bx_order_make">

		<?if (!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N"):?>
			<?if (!empty($arResult["ERROR"])):?>
				<?foreach($arResult["ERROR"] as $v):?>
					<?=ShowError($v);?>
				<?endforeach;?>
			<?elseif (!empty($arResult["OK_MESSAGE"])):?>
				<?foreach($arResult["OK_MESSAGE"] as $v):?>
					<?=ShowNote($v);?>
				<?endforeach;?>
			<?endif;?>

			<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");?>

		<?else:?>

			<?if ($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y"):?>

				<?//BEGIN BLOCK CONFIRM?>
				<?if (strlen($arResult["REDIRECT_URL"]) == 0):?>
					<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");?>
				<?endif;?>
				<?//END BLOCK CONFIRM?>

			<?else:?>

				<?//BEGIN BLOCK FUNCTION?>
				<script>

					<?if(CSaleLocation::isLocationProEnabled()):?>
						<?
						// spike: for children of cities we place this prompt
						$city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
                        file_put_contents('/personal/a_$city.json', json_encode($city));
						?>
                        //alert('1 if CSaleLocation::isLocationProEnabled');
						BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
							'source' => $this->__component->getPath().'/get.php',
							'cityTypeId' => intval($city['ID']),
							'messages' => array(
								'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
								'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
								'notFoundPrompt' => '<div class = "-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
									'#ANCHOR#' => '<a href = "javascript:void(0)" class = "-bx-popup-set-mode-add-loc">',
									'#ANCHOR_END#' => '</a>'
								)).'</div>'
							)
						))?>);
					<?endif?>

					var BXFormPosting = false;
					function submitForm(val) {
						if (BXFormPosting === true)
							return true;

						BXFormPosting = true;
						if(val != 'Y') {
                            //console.log('confirmorder ');
                            BX('confirmorder').value = 'N';
                        }

						var orderForm = BX('ORDER_FORM');
						BX.showWait();

						<?if(CSaleLocation::isLocationProEnabled()):?>
                            //console.log('isLocationProEnabled ');
                            //alert('2 if CSaleLocation::isLocationProEnabled');
							BX.saleOrderAjax.cleanUp();
						<?endif?>

                        console.log('submit done ');
                        console.log(orderForm);
                        //alert('QQ');
                        /*let docs_block = document.querySelectorAll('.docs-block');
                        if(val != undefined) {
                            console.log('val');
                            console.log(val);
                            for(let elem of docs_block) {
                                console.log(elem);
                                elem.style.display = 'none';
                                //elem.classList.add('docs-block-hide');
                            }
                        } else {
                            for(let elem of docs_block) {
                                console.log(elem);
                                elem.style.display = 'block';
                                //elem.classList.remove('docs-block-hide');
                            }
                        }*/
                        // https://dev.1c-bitrix.ru/community/webdev/user/64008/blog/5942/?commentId=50837
						BX.ajax.submit(orderForm, ajaxResult);
                        //BX.addCustomEvent('onAjaxSuccess', function(){ alert('ajaxResult'); });

						return true;
					}

					function ajaxResult(res) {
						var orderForm = BX('ORDER_FORM');
                        console.log('ajaxResult orderForm');
                        //console.log(orderForm);
                        console.log(res);
						try{
							// if json came, it obviously a successfull order submit

							var json = JSON.parse(res);
                            //alert(json);
							BX.closeWait();

							if (json.error) {
								BXFormPosting = false;
								return;
							}
							else if (json.redirect) {
								window.top.location.href = json.redirect;
							}
						}
						catch (e) {
							// json parse failed, so it is a simple chunk of html

							BXFormPosting = false;
							BX('order_form_content').innerHTML = res;

							<?if(CSaleLocation::isLocationProEnabled()):?>
								BX.saleOrderAjax.initDeferredControl();
							<?endif?>
						}

						BX.closeWait();
						BX.onCustomEvent(orderForm, 'onAjaxSuccess');
					}

					function SetContact(profileId) {
						BX("profile_change").value = "Y";
						submitForm();
					}
				</script>

				<?//END BLOCK FUNCTION?>

				<?//BEGIN BLOCK ORDER FORM?>

				<?if($_POST["is_ajax_post"] != "Y"):?>
					<form action = "<?=$APPLICATION->GetCurPage();?>" method = "POST" name = "ORDER_FORM" id = "ORDER_FORM" enctype = "multipart/form-data">
						<?=bitrix_sessid_post()?>
						<div id = "order_form_content">

				<?else:?>
					<?$APPLICATION->RestartBuffer();?>
				<?endif;?>

				<?if($_REQUEST['PERMANENT_MODE_STEPS'] == 1):?>
					<input type = "hidden" name = "PERMANENT_MODE_STEPS" value = "1">
				<?endif;?>

				<?//BEGIN BLOCK ORDER CONTENT?>

				<div class = "content-container">
                    <? file_put_contents("/home/bitrix/www" . '/logs/a_sales_result.json', json_encode($arResult)); ?>
                    <? file_put_contents("/home/bitrix/www" . '/logs/a_sales_request_in_order_form.json', json_encode($_REQUEST)); ?>

					<?//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/info.php");?>

					<div id = "ajorder">

						<div class = "ajorder_left">

							<div id = "sale_order_props" <?=($bHideProps && $_POST["showProps"] != "Y") ? "style='display:none;'":''?>>

								<NOSCRIPT>
									<div class = "errortext"><?=GetMessage("SOA_NO_JS");?></div>
								</NOSCRIPT>

								<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");?>

								<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/location.php");?>

								<?if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d"):?>
									<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");?>
									<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");?>
									<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/shops.php");?>
								<?else:?>
									<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");?>
									<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/shops.php");?>
									<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");?>
								<?endif;?>

								<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");?>
								<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");?>
								<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");?>

								<?/*
								<!-- <pre>
									<?print_r($arResult["ERROR"]);?>
								</pre> -->
								*/?>

							</div>

						</div>
						<div class = "ajorder_right">
							<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/block_price.php");?>
						</div>
					</div>
				</div>
				<?//END BLOCK ORDER CONTENT?>

				<?if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0):?>
					<?=$arResult["PREPAY_ADIT_FIELDS"];?>
				<?endif;?>

				<?if($_POST["is_ajax_post"] != "Y"):?>

						</div>
						<input type = "hidden" name = "confirmorder" id = "confirmorder" value = "Y">
						<input type = "hidden" name = "profile_change" id = "profile_change" value = "N">
						<input type = "hidden" name = "is_ajax_post" id = "is_ajax_post" value = "Y">
						<input type = "hidden" name = "json" value = "Y">
						<div class = "bx_ordercart_order_pay_center" style = "display: none;">
							<a href = "javascript:void();" onclick = "submitForm('Y'); return false;" id = "ORDER_CONFIRM_BUTTON" class = "checkout"><?=GetMessage("SOA_TEMPL_BUTTON")?></a>
						</div>
					</form>

					<?if($arParams["DELIVERY_NO_AJAX"] == "N"):?>
						<div style = "display:none;">
							<?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y'));?>
						</div>
					<?endif;?>

				<?else:?>
					<script>
						top.BX('confirmorder').value = 'Y';
						top.BX('profile_change').value = 'N';
					</script>
					<?die();?>
				<?endif;?>

				<?//END BLOCK ORDER FORM?>

			<?endif;?>

		<?endif;?>

	</div>

</div>

<?if (CSaleLocation::isLocationProEnabled()):?>

	<div style = "display: none">
		<?// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.steps", 
			".default", 
			array(
			),
			false
		);?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.search", 
			".default", 
			array(
			),
			false
		);?>
	</div>

<?endif?>