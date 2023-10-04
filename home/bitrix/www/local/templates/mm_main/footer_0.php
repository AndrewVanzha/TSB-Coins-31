<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>        

    <?if ($showPersonal) {?>
            </div>
        </div>
    <?}?>
    <?/*--- left menu in personal END---*/?>
<!-- 
    <button data-open-added-modal class="mint-btn filled big">Открыть модалку добавлено</button>
    <button data-open-know-arrival-modal class="mint-btn filled big">Открыть модалку узнать о поступлении</button> -->

    </main>

    <footer id="page-footer">
        <div class="content-container">
            <div class="up-menu">            
                <? // Основное меню в подвале ?>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "mm_main-menu__footer",
                    Array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "CHILD_MENU_TYPE" => "",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(""),
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "main_menu",
                        "USE_EXT" => "N"
                    )
                );?>

                <?/*--- Телефон ---*/?>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/local/include/phone.php"
                    )
                );?>
            </div>

            <div class="middle-info">
                <div class="middle-info-left">
                    <div class="middle-nav-mobile-tel-wrapper">
                        <?/*--- Меню "о магазине" ---*/?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "mm_shop-menu__footer",
                            Array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(""),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "bottom_left",
                                "USE_EXT" => "N"
                            )
                        );?>

                        <?/*--- Телефон ---*/?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/phone.php",
                            )
                        );?>
                    </div>


                    <div class="footer__opened-time">
                        <p class="footer__opened-time__title">Время работы</p>

                        <div class="weekdays">
                            <p>Пн. - Пт. 09:00-18:00</p>
                            <p>Сб. - Вс. выходные</p>
                        </div>
                    </div>

                    <div class="footer__payments">
                        <p class="footer__payments__title">Принимаем к оплате</p>

                        <div class="footer__payment-types">
                            <img class="footer__payment-type" src="/upload/mm_upload/icons/payments/visa.svg" alt="VISA logo" />
                            <img class="footer__payment-type" src="/upload/mm_upload/icons/payments/mastercard.svg" alt="MasterCard logo" />
                            <img class="footer__payment-type" src="/upload/mm_upload/icons/payments/mir.svg" alt="MIR logo" />
                        </div>

                        <div class="middle-info__socials-mobile">
                            <p class="middle-info__socials-title">Мы в социальных сетях</p>

                            <div class="middle-info__socials-list">
                                <?/*--- Facebook ---*/?>
                                <?/*$APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    Array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/local/include/socials/facebook_social.php"
                                    )
                                );*/?>

                                <?/*--- Vk ---*/?>
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    Array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/local/include/socials/vk_social.php"
                                    )
                                );?>

                                <?/*--- Instagram ---*/?>
                                <?/*$APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    Array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/local/include/socials/instagram_social.php"
                                    )
                                );*/?>
                            </div>

                            <?/*--- Email ---*/?>
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => "/local/include/email.php"
                                )
                            );?>
                        </div>
                    </div>
                </div>

                <div class="middle-info__socials">
                    <p class="middle-info__socials-title">Мы в социальных сетях</p>

                    <div class="middle-info__socials-list">
                        <?/*--- Facebook ---*/?>
                        <?/*$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/socials/facebook_social.php"
                            )
                        );*/?>

                        <?/*--- Vk ---*/?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/socials/vk_social.php"
                            )
                        );?>

                        <?/*--- Instagram ---*/?>
                        <?/*$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/socials/instagram_social.php"
                            )
                        );*/?>
                    </div>

                    <?/*--- Email ---*/?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/local/include/email.php"
                        )
                    );?>
                </div>
            </div>

            <div class="footer__copyright">
                <div class="copyright-left">
                    <p class="company-copyright">
                        АКБ "Трансстройбанк" (АО)<br>
                        Генеральная лицензия ЦБ РФ №2807 от 02.06.2015
                    </p>

                    <p class="site-copyright">&copy; Интернет-магазин монет <?=date('Y')?></p>
                </div>

                <div class="copyright-right">
                    <p class="mint-copyright">Развитие сайта - <a target="_blank" href="https://mediamint.ru">MediaMint</a>
                    </p>
                </div>
            </div>

            <div class="mobile-footer">
                <div class="mobile-footer__up">

                    <?/*--- Телефон ---*/?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/local/include/phone.php",
                        )
                    );?>

                    <?/*--- Email ---*/?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/local/include/email.php",
                        )
                    );?>
                </div>
        
                <div class="mobile-footer__payments-and-socials">
                    <div class="mobile-footer__payments-list">
                        <img src="/upload/mm_upload/icons/payments/visa.svg" alt="VISA icon">
                        <img src="/upload/mm_upload/icons/payments/mastercard.svg" alt="MasterCard icon">
                        <img src="/upload/mm_upload/icons/payments/mir.svg" alt="МИР icon">
                    </div>
        
                    <div class="mobile-footer__socials">
                        <?/*--- Facebook ---*/?>
                        <?/*$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/socials/facebook_social.php"
                            )
                        );*/?>

                        <?/*--- Vk ---*/?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/socials/vk_social.php"
                            )
                        );?>

                        <?/*--- Instagram ---*/?>
                        <?/*$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/socials/instagram_social.php"
                            )
                        );*/?>
                    </div>
                </div>
        
                <div class="mobile-footer__copyright">
                    <div class="mobile-footer__copyright-company">
                        АКБ "Трансстройбанк" (АО)<br>
                        Генеральная лицензия ЦБ РФ №2807 от 02.06.2015
                    </div>
        
                    <div class="mobile-footer__copyright-site">
                        &copy; Интернет-магазин монет <?=date('Y')?>
                    </div>
        
                    <p class="mobile-footer__copyright-mint">
                        Развитие сайта - <a href="https://mediamint.ru" target="_blank">MediaMint</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <?/*--- Yandex.Metrika counter ---*/?>
    <script type="text/javascript" >
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter44820226 = new Ya.Metrika2({
                        id:44820226,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true,
                        trackHash:true,
                        ecommerce:"dataLayer"
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/tag.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks2");
    </script>
    <?/*--- /Yandex.Metrika counter ---*/?>

    <div id="app-footer">
        <nav class="app-footer__navigation">
            <a href="/personal/otlozhennye-tovary/" class="app-footer__navigation-item">
                <img src="/upload/mm_upload/icons/app-footer/heart.svg" alt="избранное">
            </a>

            <button class="app-footer__navigation-item" id="open-mobile-search">
                <img src="/upload/mm_upload/icons/app-footer/loupe.svg" alt="поиск">
            </button>

                
            <?$APPLICATION->IncludeComponent(
                "bitrix:sale.basket.basket.line",
                "basket.line.footer.mm",
                Array(
                    "PATH_TO_AUTHORIZE" => "",
                    "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
                    "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
                    "PATH_TO_PERSONAL" => SITE_DIR."personal/",
                    "PATH_TO_PROFILE" => SITE_DIR."personal/",
                    "PATH_TO_REGISTER" => SITE_DIR."login/",
                    "HIDE_ON_BASKET_PAGES" => "N",
                    "POSITION_FIXED" => "N",
                    "SHOW_AUTHOR" => "N",
                    "SHOW_DELAY" => "N",
                    "SHOW_EMPTY_VALUES" => "Y",
                    "SHOW_IMAGE" => "Y",
                    "SHOW_NOTAVAIL" => "N",
                    "SHOW_NUM_PRODUCTS" => "Y",
                    "SHOW_PERSONAL_LINK" => "N",
                    "SHOW_PRICE" => "Y",
                    "SHOW_PRODUCTS" => "Y",
                    "SHOW_SUBSCRIBE" => "N",
                    "SHOW_SUMMARY" => "Y",
                    "SHOW_TOTAL_PRICE" => "Y"
                )
            );?>
            

            <a href="/personal" class="app-footer__navigation-item">
                <img src="/upload/mm_upload/icons/app-footer/user.svg" alt="личный кабинет">
            </a>
        </nav>
    </div>

    <div class="all-page-darkener"></div>

    <?/*--- mobile search ---*/?>
    <div class="mobile-search ">
        <button id="close-mobile-search">
            <img src="/upload/mm_upload/icons/header/gold-close.svg" alt="золотой крестик" class="close-mobile-search__icon">
        </button>

        <form method="GET" action="/search/" class="inner-mobile-search-wrapper">
            <input name="q" type="text" id="mobile-search-input">

            <div class="mobile-search-results">

            </div>
        </form>
    </div>

	<?php /*--- товар добавлен в корзину ---*/ ?>
    <div class="know-arrival-bg"></div>

	<button id="mobile-close-modal" data-close-added-modal>
		<img src="/upload/mm_upload/icons/header/gold-close.svg" alt="золотой крестик">
	</button>

	<div class="product-added-modal__inner-wrapper">
		<button id="upper-close-added-modal" data-close-added-modal>
			<img src="/upload/mm_upload/icons/header/gold-close.svg" alt="золотой крестик">
		</button>

		<p class="added-modal-title">Товар успешно добавлен в корзину</p>

		<div class="coin-info">
			<img src="/upload/mm_upload/coins/coin-1.png" alt="монета" class="added-modal__coin__avatar">

			<div class="added-modal__coin-desc">
				<p class="added-modal__coin-name">Российская инвестиционная монета Георгий Победоносец золото 100 рублей 15,5 гр 2021</p>

				<p class="added-modal__coin-price">
					142 000 &#8381;
				</p>
			</div>
		</div>

		<div class="added-modal__buttons-wrapper">
			<button data-close-added-modal class="mint-btn filled big">Продолжить покупки</button>
			<a href="/personal/cart/" title="Перейти в корзину" class="mint-btn filled big">Перейти в корзину</a>
		</div>
	</div>
	<?php /*--- товар добавлен в корзину ---*/ ?>


	<?php /*--- узнать о поступлении товаров ---*/ ?>
	<button data-close-know-about-arrival id="mobile-close-know-arrival">
		<img src="/upload/mm_upload/icons/header/gold-close.svg" alt="золотой крестик">
	</button>

	<div class="know-about-arrival__inner-content">
		<button data-close-know-about-arrival id="upper-mobile-close-know-arrival">
			<img src="/upload/mm_upload/icons/header/gold-close.svg" alt="золотой крестик">
		</button>
        
		<p class="modal-know-arrival__title">Узнать о поступлении</p>

		<form class="modal-know-arrival__form form-white">
			<div class="input-wrapper">
				<label for="arrival-form__form-name" class="input-label">Имя*</label>
				<input type="text" class="input-elem" name="name" autocomplete="name" id="arrival-form__form-name">
			</div>

			<div class="input-wrapper">
				<label for="arrival-form__form-tel" class="input-label">Телефон*</label>
				<input type="tel" class="input-elem" name="tel" autocomplete="tel" id="arrival-form__form-tel">
			</div>

            <input type="hidden" name="productid" value="">

			<button type="submit" class="mint-btn filled big
			full-width know-arrival__submit-btn">Отправить</button>
		</form>

		<div class="success-message-wrapper">
			<img src="/upload/mm_upload/icons/blue-checked-arrival.svg" alt="синяя галочка"
				 class="arrival-success-message__icon">

			<p class="arrival-success-message__text">
				Ваше сообщение отправлено
			</p>
		</div>
	</div>
	<?php /*--- узнать о поступлении товаров ---*/ ?>


</body>

</html>