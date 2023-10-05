<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?php
debugg('shops');
//debugg($arResult["DELIVERY"]);
//debugg($arResult["STORE_LIST"]);
// echo '<pre>'; print_r($arResult); echo '</pre>';
?>

<?foreach ($arResult["DELIVERY"] as $key => $value):?>
    <? debugg('ZZZ_'.$key) ?>
    <? //echo '<pre>'; print_r($value); echo '</pre>'; ?>
    <? //debugg($value); ?>
	<?if($value["CHECKED"] == "Y" && !empty($value["STORE"])):?>

		<div class = "ajorder-section">
			<h4 class = "ajorder-section_header"><?=GetMessage("SALE_SHOP");?></h4>
			<div class = "ajorder-section-inner ajorder-pickup-wrapper">
				<div id = "yaMap"></div>
				<div class = "ajorder-pickup-header"><?=GetMessage("SALE_SHOP_SUBTITLE");?></div>
				<div class = "ajorder-pickup-pages-wrapper">
					<div class = "ajorder-pickup-pages">
						<?$page = 0;?>
						<?$pageNum = 1;?>
						<?//foreach ($arResult["STORE_LIST"] as $k => $v):?>
						<?foreach ($arResult["STORE_LIST_MDFD"] as $k => $v):?>
							<?if(!$page):?>
								<div data-page = "<?=$pageNum?>" class = "ajorder-pickup-page active">
							<?endif;?>
							<div class = "ajorder-pickup-item <?if($v['ID'] == $arResult["BUYER_STORE"]):?>active<?endif;?>">
								<div class = "ajorder-pickup-item-header">
									<div class = "name"><?=$v["ADDRESS"];?></div>
									<span class = "ajorder-pickup-btn" data-gpsn = "<?=$v['GPS_N'];?>" data-gpss = "<?=$v['GPS_S'];?>" data-id = "<?=$v['ID'];?>">
										<?if($v['ID'] == $arResult["BUYER_STORE"]):?>
											Выбрано
										<?else:?>
											Выбрать
										<?endif;?>
									</span>
								</div>
								<div class = "ajorder-pickup-item-body">
									<div class = "ajorder-pickup-item-texts">
										<div class = "name-and-type">
											<div class = "name"><?=$v["ADDRESS"];?></div>
											<div class = "type"><?=$v["TITLE"];?></div>
										</div>
										<div class = "info">
											<div class = "info-left">
												<div class = "location-time">
													<div class = "location"><?=$v["ADDRESS"];?></div>
													<div class = "time"><?=$v["SCHEDULE"];?></div>
												</div>
												<span class ="ajorder-pickup-btn" data-id = "<?=$v['ID'];?>">
													<?if($v['ID'] == $arResult["BUYER_STORE"]):?>
														Выбрано
													<?else:?>
														Выбрать
													<?endif;?>
												</span>
											</div>
											<div class ="info-right">
												<a href ="tel:+<?=$v["PHONE"];?>" class ="phone"><?=$v["PHONE"];?></a>
												<div class = "info-text">Описание: <?=$v["DESCRIPTION"]?>.</div>
											</div>
										</div>
									</div>
									<div class = "ajorder-pickup-item-img">
										<?if($v["IMAGE_ID"]):?>
											<img src = "<?=$v['IMAGE_ID']['SRC'];?>" alt = "<?=$v['TITLE'];?>">
										<?endif;?>
									</div>
									<span class = "ajorder-pickup-btn" data-id = "<?=$v['ID'];?>">
										<?if($v['ID'] == $arResult["BUYER_STORE"]):?>
											Выбрано
										<?else:?>
											Выбрать
										<?endif;?>
									</span>
								</div>
							</div>
							<?$page++;?>
							<?//if(count($arResult["STORE_LIST"]) == $page):?>
							<?if(count($arResult["STORE_LIST_MDFD"]) == $page):?>
								</div>
								<?break;?>
							<?endif;?>
							<?if($page % 3 == 0):?>
								<?$pageNum++;?>
								</div>
								<div data-page = "<?=$pageNum?>"class = "ajorder-pickup-page">
							<?endif;?>
						<?endforeach;?>
					</div>
				</div>
				<div class = "ajorder-pickup-nav-wrapper">
					<div class = "ajorder-pickup-prev disabled"></div>
					<div class = "ajorder-pickup-nav">
						<?for (
							//$i = 0; $i < ceil(count($arResult["STORE_LIST"]) / 3); $i++
							$i = 0; $i < ceil(count($arResult["STORE_LIST_MDFD"]) / 3); $i++
						) {?>
							<div 
							data-page = "<?=($i + 1)?>"
							class = "ajorder-pickup-num"></div>
						<?}?>
					</div>
					<div class = "ajorder-pickup-next"></div>
				</div>
			</div>
		</div>
	<?endif;?>
<?endforeach;?>

<script>



function initTabs() {
		const pages_wrapper = document.querySelector('.ajorder-pickup-pages')

		if (!pages_wrapper || pages_wrapper.classList.contains('ajorder-pickup-pages-active')) return;


		const pickupItems = document.querySelectorAll('.ajorder-pickup-item');
		pickupItems.forEach(pickupItem => pickupItem.addEventListener('click',(e) => {
				if (pickupItem.classList.contains('active')) return;

				const activePickupItem = document.querySelector('.ajorder-pickup-item.active');
				activePickupItem.classList.remove('active');

				pickupItem.classList.add('active');
		}))

		
	const pikupButtons = pages_wrapper.querySelectorAll('.ajorder-pickup-btn');
		pikupButtons.forEach(
				pikupButton => pikupButton.addEventListener('click', (e) => {
						const input = document.querySelector("input[name='BUYER_STORE']");
						input.value = pikupButton.dataset.id;
						//setChangeStore(pikupButton.dataset.id)
						submitForm();
				}
		));

		const pages = document.querySelectorAll('.ajorder-pickup-pages .ajorder-pickup-page')
		const navWrapper = document.querySelector('.ajorder-pickup-nav');
		const prevButtom = document.querySelector('.ajorder-pickup-prev');
		const nextButton = document.querySelector('.ajorder-pickup-next');

		const prevAndNext = [
				{
						button: prevButtom,
						inc: -1
				},
				{
						button: nextButton,
						inc: 1
				}
		];
		prevAndNext.forEach(({button, inc}) => {
				button.addEventListener('click', (e) => {
						if(button.classList.contains('disabled')) return;
						const activeNav = document.querySelector('.ajorder-pickup-nav .ajorder-pickup-num.active');
						const currPage = Number(activeNav.dataset.page);
						changePage(currPage + inc);
				});
		})


		// createNavigation
		// for (let i = 0; i < pages.length; i++) {
		//     const navigation = document.createElement('div');
		//     navigation.classList.add('ajorder-pickup-num');
		//     navigation.dataset.page = i + 1;
		//     navWrapper.appendChild(navigation);
		// }


		const navigations = document.querySelectorAll('.ajorder-pickup-nav .ajorder-pickup-num');
		function changePage(num) {
				localStorage.setItem('currpage', num);
				const activePage = document.querySelector('.ajorder-pickup-pages .ajorder-pickup-page.active');

				activePage?.classList?.remove('active');
				pages[num - 1].classList.add('active');
				
				const activeNav = document.querySelector('.ajorder-pickup-nav .ajorder-pickup-num.active');
				activeNav?.classList?.remove('active');
				navigations[num - 1].classList.add('active');

				if (num == 1) {
						prevButtom.classList.add('disabled');
				} else {
						prevButtom.classList.remove('disabled');
				}
				if (num == pages.length) {
						nextButton.classList.add('disabled');
				} else {
						nextButton.classList.remove('disabled');
				}
		}
		

		navigations.forEach(
				(navigation, index) => navigation.addEventListener('click', (e) => {
						const pageNum = index + 1;
						if (navigation.classList.contains('active')) return;
						changePage(pageNum);
				}
		))

		// init
		const activePickupElement =  document.querySelector('.ajorder-pickup-item.active');
		const activePage = activePickupElement.closest('.ajorder-pickup-page');
		const activePageNum = activePage.dataset.page;
		const numFromLocal = localStorage.getItem('currpage');
		if (activePageNum) {
				changePage(Number(activePageNum));
		} else {
				changePage(1)
		}

}

function initMap() {

	const mapWrapper = document.getElementById('yaMap');
	
	if(mapWrapper) {

		document.getElementById('yaMap').innerHTML = '';

		//получение центра
		const input = document.querySelector("input[name='BUYER_STORE']");
		const pickupId = input.value;

		const pages_wrapper = document.querySelector('.ajorder-pickup-pages');
		const activePikupButton = pages_wrapper.querySelector(
			`.ajorder-pickup-btn[data-id="${pickupId}"]`
		);
		
		var pointeCenter = [
			activePikupButton.dataset.gpsn,
			activePikupButton.dataset.gpss
		];
		

		ymaps.ready(function(){
			var yaMap = new ymaps.Map("yaMap", {
				center: pointeCenter,
				zoom: 18,
				controls: [],
			});

			const pickupBtns = document.querySelectorAll('.ajorder-pickup-btn');

			pickupBtns.forEach(pickupBtn => {
				var gpsN = pickupBtn.dataset.gpsn;
				var gpsS = pickupBtn.dataset.gpss;

				if (gpsN && gpsS) {

					yaMap.geoObjects.add(new ymaps.Placemark([gpsN, gpsS], {
						}, {
							cursor: 'pointer',
							iconLayout: 'default#image',
							iconImageHref: '/local/templates/.default/components/mediamint/sale.order.ajax/mediamint/images/pin.png',
							iconImageSize: [41, 41],
							iconImageOffset: [-41, -41],
							balloonContent: 'цвет <strong>красный</strong>'
						}
					));

					yaMap.behaviors.disable('scrollZoom');
					yaMap.behaviors.disable('drag');

				}
			});

		});

	}

}

// function setChangeStore(id) {
// 	var store = id;
// 	var inputStore = document.querySelector("input[name='BUYER_STORE']");
// 			inputStore.value = store;
// 	submitForm();
// }

initMap();

initTabs();

BX.addCustomEvent('onAjaxSuccess', initTabs);

BX.addCustomEvent('onAjaxSuccess', initMap);


</script>