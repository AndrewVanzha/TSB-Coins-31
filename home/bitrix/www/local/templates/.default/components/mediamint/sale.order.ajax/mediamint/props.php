<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");
?>
<?php
debugg('props');
//echo '<pre>'; print_r($arResult["DELIVERY"]); echo '</pre>';
?>
<div class = "section">
	<div class = "ajorder-section">
		<h4 class = "ajorder-section_header"><?=GetMessage("SOA_TEMPL_BUYER_INFO");?></h4>
		<div class = "ajorder-section-inner">

			<?//BEGIN USER FIO GROUP?>

			<?$user_fio = array();?>
			<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $k => $v):?>
				<?if($v["PROPS_GROUP_ID"] == 4):?>
					<?$user_fio[$v["ID"]] = $v;?>
					<?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
						<?$user_fio[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
					<?endif;?>
				<?endif;?>
			<?endforeach;?>

			<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $k => $v):?>
				<?if($v["PROPS_GROUP_ID"] == 4):?>
					<?$user_fio[$v["ID"]] = $v;?>
					<?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
						<?$user_fio[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
					<?endif;?>
				<?endif;?>
			<?endforeach;?>

			<?usort($user_fio, function($a, $b){
				return ($a['SORT'] - $b['SORT']);
			});?>

			<?PrintPropsForm($user_fio, $arParams["TEMPLATE_LOCATION"]);?>

			<?//END USER FIO GROUP?>

			<?//BEGIN USER PHOTO GROUP?>

			<?$user_photo = array();?>
			<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $k => $v):?>
				<?if($v["PROPS_GROUP_ID"] == 2):?>
					<?$user_fio[$v["ID"]] = $v;?>
				<?endif;?>
			<?endforeach;?>

			<?//END USER PHOTO GROUP?>

			<?//BEGIN USER CONTACTS GROUP?>

			<?$user_contacts = array();?>
			<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $k => $v):?>
				<?if($v["PROPS_GROUP_ID"] == 1):?>
					<?$user_contacts[$v["ID"]] = $v;?>
					<?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
						<?$user_contacts[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
					<?endif;?>
				<?endif;?>
			<?endforeach;?>

			<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $k => $v):?>
				<?if($v["PROPS_GROUP_ID"] == 1):?>
					<?$user_contacts[$v["ID"]] = $v;?>
					<?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
						<?$user_contacts[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
					<?endif;?>
				<?endif;?>
			<?endforeach;?>

			<?usort($user_contacts, function($a, $b){
				return ($a['SORT'] - $b['SORT']);
			});?>


			<?//END USER CONTACTS GROUP?>

			<div class = "ajorder-flex-inputs">
				<?PrintPropsForm($user_contacts, $arParams["TEMPLATE_LOCATION"]);?>
			</div>

            <?/* if($arResult["DELIVERY"]["3"]["CHECKED"] != "Y"): // не самовывоз?>
                <div class="ajorder-section-after_text big-mt">
                    <h5 class = "ajorder-section-after_text-header">Прикрепить документы <span>(актуально для доставки в пределах МКАДа и по России)</span></h5>
                    Действующее законодательство (ФЗ №115) требует проводить все операции с драгоценными металлами по предъявлению паспорта. Также вы обязаны его показать сотрудникам службы доставки ФГУП Спецсвязь.
                </div>
                <div class = "ajorder-file-inputs-wrapper">

                    <?$user_files = array();?>
                    <?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $k => $v):?>
                        <?if($v["PROPS_GROUP_ID"] == 6):?>
                            <?$user_files[$v["ID"]] = $v;?>
                            <?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
                                <?$user_files[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
                            <?endif;?>
                        <?endif;?>
                    <?endforeach;?>

                    <?foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $k => $v):?>
                        <?if($v["PROPS_GROUP_ID"] == 6):?>
                            <?$user_files[$v["ID"]] = $v;?>
                            <?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
                                <?$user_files[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
                            <?endif;?>
                        <?endif;?>
                    <?endforeach;?>

                    <?usort($user_files, function($a, $b){
                        return ($a['SORT'] - $b['SORT']);
                    });?>

                    <?PrintPropsForm($user_files, $arParams["TEMPLATE_LOCATION"]);?>

                </div>
            <? endif; */?>

			<?//BEGIN USER ADR GROUP?>

			<?if($arResult["DELIVERY"]["3"]["CHECKED"] != "Y"): // не самовывоз?>

				<?$user_adr = array();?>
				<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $k => $v):?>
                    <?/* if ($v['ID'] != 6) {
                        echo '<pre>'; print_r($v); echo '</pre>';
                    } */?>
					<?if($v["PROPS_GROUP_ID"] == 2):?>
						<?$user_adr[$v["ID"]] = $v;?>
						<?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
							<?$user_adr[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
						<?endif;?>
					<?endif;?>
				<?endforeach;?>
                <?// echo '<pre>'; print_r($user_adr); echo '</pre>'; ?>

				<?/*foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $k => $v): // input c Сделка в CRM ?>
					<?if($v["PROPS_GROUP_ID"] == 2):?>
						<?$user_adr[$v["ID"]] = $v;?>
						<?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
							<?$user_adr[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
						<?endif;?>
					<?endif;?>
				<?endforeach;*/?>

				<?usort($user_adr, function($a, $b){
					return ($a['SORT'] - $b['SORT']);
				});?>

				<div class = "ajorder-flex-inputs">
					<?PrintPropsForm($user_adr, $arParams["TEMPLATE_LOCATION"]);?>
				</div>

			<?endif;?>

            <? if($arResult["DELIVERY"]["3"]["CHECKED"] != "Y"): // не самовывоз?>
                <div class="ajorder-section-after_text big-mt">
                    <h5 class = "ajorder-section-after_text-header">Прикрепить документы <span>(актуально для доставки в пределах МКАДа и по России)</span></h5>
                    Действующее законодательство (ФЗ №115) требует проводить все операции с драгоценными металлами по предъявлению паспорта. Также вы обязаны его показать сотрудникам службы доставки ФГУП Спецсвязь.
                </div>
                <div class = "ajorder-file-inputs-wrapper">

                    <?$user_files = array();?>
                    <?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $k => $v):?>
                        <?if($v["PROPS_GROUP_ID"] == 6):?>
                            <?$user_files[$v["ID"]] = $v;?>
                            <?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
                                <?$user_files[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
                            <?endif;?>
                        <?endif;?>
                    <?endforeach;?>

                    <?foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $k => $v):?>
                        <?if($v["PROPS_GROUP_ID"] == 6):?>
                            <?$user_files[$v["ID"]] = $v;?>
                            <?if (array_key_exists($v["ID"], $arResult["ERROR"])):?>
                                <?$user_files[$v["ID"]]["ERROR"] = $arResult["ERROR"][$v["ID"]];?>
                            <?endif;?>
                        <?endif;?>
                    <?endforeach;?>

                    <?usort($user_files, function($a, $b){
                        return ($a['SORT'] - $b['SORT']);
                    });?>

                    <?PrintPropsForm($user_files, $arParams["TEMPLATE_LOCATION"]);?>

                </div>
            <? endif; ?>

			<?//END USER ADR GROUP?>

			<?//BEGIN ORDER COMMENTS GROUP ?>

			<div class = "ajorder-input-wrapper">
				<label for = "">Коментарий к заказу</label>
				<div class = "ajorder-input">
					<textarea name = "ORDER_DESCRIPTION" id = "ORDER_DESCRIPTION" placeholder = "Оставить заказ у двери"></textarea>
				</div>
			</div>

			<?//END ORDER COMMENTS GROUP?>

		</div>
	</div>
	<a class = "ajorder-makeorder" href = "javascript:void();" onclick = "submitForm('Y'); return false;">Оформить заказ</a>
</div>

<script>
	function fGetBuyerProps(el)
	{
		var show = '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW')?>';
		var hide = '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE')?>';
		var status = BX('sale_order_props').style.display;
		var startVal = 0;
		var startHeight = 0;
		var endVal = 0;
		var endHeight = 0;
		var pFormCont = BX('sale_order_props');
		pFormCont.style.display = "block";
		pFormCont.style.overflow = "hidden";
		pFormCont.style.height = 0;
		var display = "";

		if (status == 'none')
		{
			el.text = '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE');?>';

			startVal = 0;
			startHeight = 0;
			endVal = 100;
			endHeight = pFormCont.scrollHeight;
			display = 'block';
			BX('showProps').value = "Y";
			el.innerHTML = hide;
		}
		else
		{
			el.text = '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW');?>';

			startVal = 100;
			startHeight = pFormCont.scrollHeight;
			endVal = 0;
			endHeight = 0;
			display = 'none';
			BX('showProps').value = "N";
			pFormCont.style.height = startHeight+'px';
			el.innerHTML = show;
		}

		(new BX.easing({
			duration : 700,
			start : { opacity : startVal, height : startHeight},
			finish : { opacity: endVal, height : endHeight},
			transition : BX.easing.makeEaseOut(BX.easing.transitions.quart),
			step : function(state){
				pFormCont.style.height = state.height + "px";
				pFormCont.style.opacity = state.opacity / 100;
			},
			complete : function(){
					BX('sale_order_props').style.display = display;
					BX('sale_order_props').style.height = '';

					pFormCont.style.overflow = "visible";
			}
		})).animate();
	}
	// uplodingFiles
    function fileInputsInit() {
        function updateFileInputs() {
            function validateFile(file)
            {
                if ( !file.type.match(/image\/(jpeg|jpg|png)/) && !file.type.match(/pdf/)) {
                    return 'Фотография должна быть в формате jpg, png, pdf';
                }

                if ( file.size > maxFileSize ) {
                    return 'Размер фотографии не должен превышать 1 Мб';
                }
            };
            const fileInputs = document.querySelectorAll('input[name="ORDER_PROP_24[0]"], input[name="ORDER_PROP_23[0]"]');
            fileInputs.forEach(fileInput => {
                const file = fileInput.files[0];
                if (!file || !file.type) return;
                const error = validateFile(file);
                if (error) {
                    alert(error)
                    return;
                }
                const parentWrapper = fileInput.closest('.ajorder-file-input-wrapper');
                const curentImg = parentWrapper.querySelector('.ajorder-file-input-img img');

                if (file.type.match(/pdf/))
                {
                    curentImg.src = "/upload/mm_upload/pdf_file.svg";
                }
                else
                {
                    curentImg.src = URL.createObjectURL(file);
                }
            })
        }
        const fileInputs = document.querySelectorAll('input[name="ORDER_PROP_24[0]"], input[name="ORDER_PROP_23[0]"]');
        fileInputs.forEach(fileInput => {
            fileInput.addEventListener('change', updateFileInputs)
        });
		updateFileInputs();
    }
	fileInputsInit();
	BX.addCustomEvent('onAjaxSuccess', fileInputsInit);

</script>
