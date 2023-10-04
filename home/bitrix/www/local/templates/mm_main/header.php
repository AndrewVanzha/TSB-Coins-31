<?php

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\HttpContext;
use Bitrix\Main\Page\Asset;

global $APPLICATION;

$context = HttpContext::getCurrent();
$curentPage = $APPLICATION->GetCurPage(false);
$curentPageArray = explode('/', $curentPage);
$isIncelopediaSection = $curentPageArray[1] === 'entsiklopediya' && 
(count($curentPageArray) < 5);
$headerDark = 
    $curentPage === '/' || 
    $curentPage === '/kontakty/' ||
    $curentPage === '/novosti/' || 
    $curentPage === '/entsiklopediya/' || 
    $isIncelopediaSection ||
    $curentPage === '/dostavka-i-oplata/';

$headerTransparent = $curentPage === "/";

$topMargin = $curentPage === '/';

?><!DOCTYPE html>
<html lang="<?= $context->getLanguage(); ?>">
<head>

    <!-- Facebook Pixel Code -->
    <script>
        /*
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '811065969343408');
        fbq('track', 'PageView');
         */
    </script>

    <noscript>
        <?/*?>
        <img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=811065969343408&ev=PageView&noscript=1"/>
        <?*/?>
    </noscript>
    <!-- End Facebook Pixel Code -->

    <script>
        // fbq('track', 'Lead');
        // fbq('track', 'AddToCart');
    </script>

    <!-- VK -->
    <script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?162",t.onload=function(){VK.Retargeting.Init("VK-RTRG-434023-1dtUe"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script>

    <noscript>
        <img src="https://vk.com/rtrg?p=VK-RTRG-434023-1dtUe" style="position:fixed; left:-999px;" alt=""/>
    </noscript>
    <!-- End VK -->

    <meta charset="<?= $context->getCulture()->getCharset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"  content="width=device-width, initial-scale=1">
	<meta name="yandex-verification" content="0c15c0ddeb004525" />

    <title><? $APPLICATION->ShowTitle(); ?></title>

    <?
    $APPLICATION->AddBufferContent('ShowCanonical');

    $APPLICATION->ShowMeta("robots", false, false);
    $APPLICATION->ShowMeta("keywords", false, false);
    $APPLICATION->ShowMeta("description", false, false);

    $assets = Asset::getInstance();

    // Add styles
    // $assets->addCss('/assets/css/normalize.min.css');
    // $assets->addCss('/assets/css/slick.css');
    // $assets->addCss('/assets/css/jquery.fancybox.min.css');
    // $assets->addCss('/assets/css/style.css');
    // $assets->addCss('/assets/css/edit.css');

    // swiper
    $assets->addCss('https://unpkg.com/swiper@8/swiper-bundle.min.css');
    $assets->addJs('https://unpkg.com/swiper@8/swiper-bundle.min.js');
    // swiper

    // input mask
    $assets->addJs("/local/templates/mm_main/assets/js/inputmask.min.js");

    $assets->addCss('/local/templates/mm_main/assets/css/base.css');
    $assets->addCss('/local/templates/mm_main/assets/css/reset-swiper.css');
    $assets->addCss('/local/templates/mm_main/assets/css/header.css');
    $assets->addCss('/local/templates/mm_main/assets/css/footer.css');
    $assets->addCss("/local/templates/mm_main/assets/css/mobile-search.css");

    // $assets->addCss('/local/templates/mm_main/assets/css/footer-middle-menu.css');
    $assets->addCss('/local/templates/mm_main/assets/css/app-footer-navigation.css');
    // $assets->addCss('/local/templates/mm_main/assets/css/footer-up-menu.css');

    // modals
    $assets->addCss("/local/templates/mm_main/assets/css/added-modal.css");
	$assets->addCss("/local/templates/mm_main/assets/css/know-arrival-modal.css");
    $assets->addJs("/local/templates/mm_main/assets/js/modals.js");


    // Add scripts
    $assets->addJs('/assets/js/jquery.min.js');
    $assets->addJs('https://maps.googleapis.com/maps/api/js?key=AIzaSyAqTI_k2NvGWLhGNk0dT3nTLSgZI1Cgurs');

    // $assets->addJs('/assets/js/slick.min.js');

    // $assets->addJs('/assets/js/jquery.cookie.js');
    // $assets->addJs('/assets/js/jquery.maskedinput.min.js');
    // $assets->addJs('/assets/js/jquery.sticky-kit.min.js');
    // $assets->addJs('/assets/js/jquery.fancybox.min.js');
    // $assets->addJs('/assets/js/url.min.js');

    // $assets->addJs('/assets/js/main.js');
    $assets->addJs('/local/templates/mm_main/assets/js/header.js');
    $assets->addJs("/local/templates/mm_main/assets/js/mobile-search.js");

    $assets->addJs("/local/templates/mm_main/assets/js/desired.js");
    $assets->addJs("/local/templates/mm_main/assets/js/mm_global.js");
    $assets->addJs("/local/templates/mm_main/assets/js/basket.js");

    // $assets->addJs('/local/templates/mm_main/assets/js/global.js');

    // Show all style
    $APPLICATION->ShowCSS(true, false);

    // Show all strings and scripts
    $APPLICATION->ShowHeadStrings();
    //$APPLICATION->ShowHeadScripts();
    // sdt code ** Open Graph
    $props = $APPLICATION->GetDirPropertyList();
    //phpinfo();
    ?>
    <meta property="og:title" content="<?$APPLICATION->ShowTitle();?>" />
    <meta property="og:description" content="<?=$props['DESCRIPTION']?>" />
    <meta property="og:url" content="http://coins.tsbnk.ru<?=$APPLICATION->GetCurPage();?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="http://coins.tsbnk.ru/assets/images/logo-main.png" />
    <? // end sdt code?>
    <!-- api map -->
    <script src="https://api-maps.yandex.ru/2.1/?apikey=076946fa-82a2-4bfd-ae1f-6b3db63549be&lang=ru_RU" type="text/javascript">
    </script>
    <!-- api map -->

    <?/*--- Yandex.Metrika counter ---*/?>
    <script type="text/javascript" >
        /*(function (d, w, c) {
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
        })(document, window, "yandex_metrika_callbacks2");*/
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(44820226, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true,
            trackHash:true,
            ecommerce:"dataLayer"
        });
        window.dataLayer = window.dataLayer || [];
    </script>
    <?/*--- /Yandex.Metrika counter ---*/?>
</head>
<body>
    <!-- No script warning -->
    <noscript>
        <div id="noScriptWarning">
            К сожалению, ваш браузер <strong>не поддерживает</strong> JavaScript.
            Пожалуйста, <a href="http://browsehappy.com/" target="_blank" rel="nofollow">обновите</a> ваш браузер или включите поддержку JavaScript для корректного отображения страницы.
        </div>
    </noscript>

    <!-- Outdated browser warning -->
    <!--[if lte IE 10]>
    <div id="outdatedBrowserWarning">
        Вы используете <strong>устаревший</strong> браузер. Пожалуйста,
        <a href="http://browsehappy.com/" target="_blank" rel="nofollow">обновите</a> ваш браузер
        для корректного отображения страницы.
    </div>
    <![endif]-->

    <div id="panel">
        <?php $APPLICATION->ShowPanel(); ?>
    </div>

    <?if (!$topMargin) {?>
        <div class="header-mrb"></div>
    <?}?>

    <header id="page-header" class="<?=($headerDark ? 'dark' : '')?> <?=($headerTransparent ? 'transparent' : '')?>">
        <div class="header-up">
            <div class="content-container">
                <button class="open-shop-menu mobile-button">
                    <span class="open-shop-menu__line"></span>
                    <span class="open-shop-menu__line"></span>
                    <span class="open-shop-menu__line"></span>
                </button>

                <a href="/" class="header-logo-wrapper">
                    <? if ($headerDark)
                    {?>
                        <img 
                            src="/upload/mm_upload/logo-coins-TSBNK-white.svg"
                            alt="Инвестиционные монеты" 
                            class="header-company-logo">
                    <?}
                    else
                    {?>
                        <img 
                            src="/upload/mm_upload/logo-coins-TSBNK-blue.svg" 
                            alt="Логотип компании" 
                            class="header-company-logo">
                    <?}?>
                </a>

                <div class="header-right-container">
                    <button class="open-shop-menu desktop-button">
                        <span class="open-shop-menu__lines-wrapper">
                            <span class="open-shop-menu__line"></span>
                            <span class="open-shop-menu__line"></span>
                            <span class="open-shop-menu__line"></span>
                        </span>

                        <span class="shop-burger-menu__title">Магазин</span>
                    </button>

                    <?/*--- Основное меню в прилипающей шапке ---*/?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "mm_main_menu",
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
                            "USE_EXT" => "N",
                            "IS_STIKY_MENU" => "Y"
                        )
                    );?>

                    <div class="header-search-sticky-bg"></div>

                    <form method="GET" action="/search/" class="header-search-wrapper">
                        <button type="submit"
                            class="search-loupe-wrapper input-left"
                            disabled>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_225_22664)">
                                    <path
                                        d="M19.7293 18.5773L14.0354 12.8899C15.1383 11.5291 15.8024 9.7999 15.8024 7.9166C15.8024 3.55164 12.2467 0 7.87676 0C3.50683 0 -0.0488281 3.55161 -0.0488281 7.91656C-0.0488281 12.2815 3.50687 15.8332 7.8768 15.8332C9.76225 15.8332 11.4934 15.1699 12.8557 14.0682L18.5497 19.7556C18.7124 19.9181 18.9259 19.9998 19.1395 19.9998C19.3531 19.9998 19.5667 19.9181 19.7294 19.7556C20.0556 19.4298 20.0556 18.9031 19.7293 18.5773ZM7.8768 14.1665C4.42623 14.1665 1.61974 11.3632 1.61974 7.91656C1.61974 4.46992 4.42623 1.66662 7.8768 1.66662C11.3274 1.66662 14.1339 4.46992 14.1339 7.91656C14.1339 11.3632 11.3273 14.1665 7.8768 14.1665Z" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_225_22664">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>

                        <input 
                        name="q" 
                        type="text" 
                        id="header-search-input">

                        <div class="input-controls">
                            <button disabled type="submit" class="search-loupe-wrapper input-right">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_225_22664)">
                                        <path
                                            d="M19.7293 18.5773L14.0354 12.8899C15.1383 11.5291 15.8024 9.7999 15.8024 7.9166C15.8024 3.55164 12.2467 0 7.87676 0C3.50683 0 -0.0488281 3.55161 -0.0488281 7.91656C-0.0488281 12.2815 3.50687 15.8332 7.8768 15.8332C9.76225 15.8332 11.4934 15.1699 12.8557 14.0682L18.5497 19.7556C18.7124 19.9181 18.9259 19.9998 19.1395 19.9998C19.3531 19.9998 19.5667 19.9181 19.7294 19.7556C20.0556 19.4298 20.0556 18.9031 19.7293 18.5773ZM7.8768 14.1665C4.42623 14.1665 1.61974 11.3632 1.61974 7.91656C1.61974 4.46992 4.42623 1.66662 7.8768 1.66662C11.3274 1.66662 14.1339 4.46992 14.1339 7.91656C14.1339 11.3632 11.3273 14.1665 7.8768 14.1665Z" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_225_22664">
                                            <rect width="20" height="20" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </button>

                            <button id="close-input" type="button">
                                <svg class="search-close-gold" width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 0.824864L13.1751 0L7 6.17514L0.824864 0L0 0.824864L6.17514 7L0 13.1751L0.824864 14L7 7.82486L13.1751 14L14 13.1751L7.82486 7L14 0.824864Z" />
                                </svg>
                            </button>
                        </div>

                        <div id="header-search-result"></div>
                    </form>

                    <!--
                    <div class="header-search-wrapper">
                        <a href="/search/?query="
                            class="search-loupe-wrapper input-left link-to-all-results">

                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_225_22664)">
                                    <path
                                        d="M19.7293 18.5773L14.0354 12.8899C15.1383 11.5291 15.8024 9.7999 15.8024 7.9166C15.8024 3.55164 12.2467 0 7.87676 0C3.50683 0 -0.0488281 3.55161 -0.0488281 7.91656C-0.0488281 12.2815 3.50687 15.8332 7.8768 15.8332C9.76225 15.8332 11.4934 15.1699 12.8557 14.0682L18.5497 19.7556C18.7124 19.9181 18.9259 19.9998 19.1395 19.9998C19.3531 19.9998 19.5667 19.9181 19.7294 19.7556C20.0556 19.4298 20.0556 18.9031 19.7293 18.5773ZM7.8768 14.1665C4.42623 14.1665 1.61974 11.3632 1.61974 7.91656C1.61974 4.46992 4.42623 1.66662 7.8768 1.66662C11.3274 1.66662 14.1339 4.46992 14.1339 7.91656C14.1339 11.3632 11.3273 14.1665 7.8768 14.1665Z" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_225_22664">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>

                        <input type="text" id="header-search-input">

                        <div class="input-controls">
                            <a href="/all-search-results/?query="
                                class="search-loupe-wrapper input-right link-to-all-results">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_225_22664)">
                                        <path
                                            d="M19.7293 18.5773L14.0354 12.8899C15.1383 11.5291 15.8024 9.7999 15.8024 7.9166C15.8024 3.55164 12.2467 0 7.87676 0C3.50683 0 -0.0488281 3.55161 -0.0488281 7.91656C-0.0488281 12.2815 3.50687 15.8332 7.8768 15.8332C9.76225 15.8332 11.4934 15.1699 12.8557 14.0682L18.5497 19.7556C18.7124 19.9181 18.9259 19.9998 19.1395 19.9998C19.3531 19.9998 19.5667 19.9181 19.7294 19.7556C20.0556 19.4298 20.0556 18.9031 19.7293 18.5773ZM7.8768 14.1665C4.42623 14.1665 1.61974 11.3632 1.61974 7.91656C1.61974 4.46992 4.42623 1.66662 7.8768 1.66662C11.3274 1.66662 14.1339 4.46992 14.1339 7.91656C14.1339 11.3632 11.3273 14.1665 7.8768 14.1665Z" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_225_22664">
                                            <rect width="20" height="20" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>

                            <button id="close-input">
                                <svg class="search-close-gold" width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 0.824864L13.1751 0L7 6.17514L0.824864 0L0 0.824864L6.17514 7L0 13.1751L0.824864 14L7 7.82486L13.1751 14L14 13.1751L7.82486 7L14 0.824864Z" />
                                </svg>
                            </button>
                        </div>
                        <div id="header-search-result"></div>
                    </div>
                    -->

                    <div class="header-personal-wrapper">
                        <button id="open-sticky-search" class="personal-navigation__item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_225_22664)">
                                    <path
                                        d="M19.7293 18.5773L14.0354 12.8899C15.1383 11.5291 15.8024 9.7999 15.8024 7.9166C15.8024 3.55164 12.2467 0 7.87676 0C3.50683 0 -0.0488281 3.55161 -0.0488281 7.91656C-0.0488281 12.2815 3.50687 15.8332 7.8768 15.8332C9.76225 15.8332 11.4934 15.1699 12.8557 14.0682L18.5497 19.7556C18.7124 19.9181 18.9259 19.9998 19.1395 19.9998C19.3531 19.9998 19.5667 19.9181 19.7294 19.7556C20.0556 19.4298 20.0556 18.9031 19.7293 18.5773ZM7.8768 14.1665C4.42623 14.1665 1.61974 11.3632 1.61974 7.91656C1.61974 4.46992 4.42623 1.66662 7.8768 1.66662C11.3274 1.66662 14.1339 4.46992 14.1339 7.91656C14.1339 11.3632 11.3273 14.1665 7.8768 14.1665Z" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_225_22664">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>

                        <?/*--- Избранное ---*/?>
                        <?$APPLICATION->IncludeComponent(
                            "webtu:catalog.liked.line",
                            "mm_liked.line",
                            Array(
                                "PATH_TO_PAGE" => "/personal/otlozhennye-tovary/"
                            )
                        );?>

                        <a href="/personal/" class="personal-navigation__item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.0711 12.9289C15.9819 11.8398 14.6855 11.0335 13.2711 10.5454C14.786 9.50199 15.7812 7.75578 15.7812 5.78125C15.7812 2.59348 13.1878 0 10 0C6.81223 0 4.21875 2.59348 4.21875 5.78125C4.21875 7.75578 5.21402 9.50199 6.72898 10.5454C5.31453 11.0335 4.01813 11.8398 2.92895 12.9289C1.0402 14.8177 0 17.3289 0 20H1.5625C1.5625 15.3475 5.34754 11.5625 10 11.5625C14.6525 11.5625 18.4375 15.3475 18.4375 20H20C20 17.3289 18.9598 14.8177 17.0711 12.9289ZM10 10C7.67379 10 5.78125 8.1075 5.78125 5.78125C5.78125 3.455 7.67379 1.5625 10 1.5625C12.3262 1.5625 14.2188 3.455 14.2188 5.78125C14.2188 8.1075 12.3262 10 10 10Z" />
                            </svg>
                        </a>

                        <div class="personal-navigation__item cart-wrapper">
                            <?$APPLICATION->IncludeComponent(
                                    "bitrix:sale.basket.basket.line",
                                    "basket.line.mm",
                                    Array(
                                        "HIDE_ON_BASKET_PAGES" => "N",
                                        "PATH_TO_AUTHORIZE" => "",
                                        "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
                                        "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
                                        "PATH_TO_PERSONAL" => SITE_DIR."personal/",
                                        "PATH_TO_PROFILE" => SITE_DIR."personal/",
                                        "PATH_TO_REGISTER" => SITE_DIR."login/",
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

                            <div class="header-cart-fixed-bg"></div>
                        </div>
                    </div>
                    
                    <?/*--- Телефон в шапке для планшетов ---*/?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/local/include/phone.php",
                            "CUSTOM_CLASS" => "header-desktop-tel",
                            "CUSTOM_SVG" => "desktop",
                        )
                    );?>

                    <!-- FIXME придумать, как сунуть во включаемую телефон с иконкой -->
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/include/phone.php",
							"CUSTOM_CLASS" => "header-mobile-tel",
                            "CUSTOM_SVG" => "mobile",
						)
					);?>

<!--                    <a href="tel:8 800 505 04 76" class="header-mobile-tel">-->
<!--                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                            <g clip-path="url(#clip0_279_7924)">-->
<!--                                <path-->
<!--                                    d="M21.5899 30C16.3307 30 10.7146 28.0675 6.32344 23.6765C1.93963 19.2926 0 13.6802 0 8.41014C0 3.76547 3.75773 0 8.41014 0C8.76955 0 9.0927 0.218789 9.22617 0.55248L12.9918 9.96656C13.1721 10.4173 12.9529 10.9287 12.5023 11.109L8.36572 12.7636C8.65834 17.5225 12.4783 21.3422 17.2364 21.6344L18.891 17.4979C19.0709 17.0479 19.5822 16.8279 20.0335 17.0082L29.4475 20.7738C29.7812 20.9073 30 21.2304 30 21.5899C30 26.2345 26.2423 30 21.5899 30ZM7.8252 1.78301C4.4591 2.07428 1.75781 4.89697 1.75781 8.41014C1.75781 13.7075 3.82066 18.6878 7.56645 22.4335C11.3122 26.1793 16.2925 28.2422 21.5899 28.2422C25.1019 28.2422 27.9255 25.5424 28.2171 22.1748L20.1967 18.9667L18.6403 22.8577C18.5068 23.1913 18.1836 23.4101 17.8242 23.4101C11.6292 23.4101 6.58986 18.3708 6.58986 12.1767C6.58986 11.8174 6.80865 11.4932 7.14234 11.3597L11.0333 9.80332L7.8252 1.78301Z" />-->
<!--                            </g>-->
<!--                            <defs>-->
<!--                                <clipPath id="clip0_279_7924">-->
<!--                                    <rect width="30" height="30" fill="white" />-->
<!--                                </clipPath>-->
<!--                            </defs>-->
<!--                        </svg>-->
<!--                    </a>-->
                </div>
            </div>
        </div>

        <div class="header-bottom">
            <div class="content-container">
                <div class="header-right-container">

                    <div class="header-bottom-navigation">
                        <?/*--- Меню каталога (скидки, новинки) ---*/?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "mm_main_menu",
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
                                "PATH" => "/local/include/phone.php",
                                "CUSTOM_SVG" => "main",
                            )
                        );?>
                    </div>

                    <div class="mobile-catalog-wrapper">
                        <button id="open-mobile-catalog">
                            <span class="open-mobile-catalog__text">Каталог</span>

                            <svg class="open-mobile-catalog__arrow" width="15" height="15" viewBox="0 0 15 15"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_2_13168)">
                                    <path
                                        d="M11.777 7.50004C11.777 7.76887 11.6743 8.03767 11.4695 8.24263L5.01975 14.6923C4.60947 15.1026 3.94427 15.1026 3.53415 14.6923C3.12403 14.2822 3.12403 13.6171 3.53415 13.2068L9.24123 7.50004L3.53435 1.79327C3.12423 1.38298 3.12423 0.717979 3.53435 0.307897C3.94447 -0.102586 4.60967 -0.102586 5.01995 0.307897L11.4697 6.75746C11.6746 6.96251 11.777 7.23131 11.777 7.50004Z" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_2_13168">
                                        <rect width="15" height="15" fill="white"
                                            transform="translate(0 15) rotate(-90)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>

                        <div class="mobile-catalog-navigation-wrapper hidden">
                            <?/*--- Меню каталога (скидки, новинки) ---*/?>
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "mm_header-mobile-catalog-menu",
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
                        </div>
                    </div>

                    <div class="shop-menu-wrapper hidden">
                        <div class="content-container">
                            <div class="header-right-container">
                                <?/*--- Меню "о магазине" ---*/?>
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:menu",
                                    "mm_top",
                                    Array(
                                        "ALLOW_MULTI_SELECT" => "N",
                                        "CHILD_MENU_TYPE" => "",
                                        "DELAY" => "N",
                                        "MAX_LEVEL" => "1",
                                        "MENU_CACHE_GET_VARS" => array(""),
                                        "MENU_CACHE_TIME" => "3600",
                                        "MENU_CACHE_TYPE" => "N",
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "ROOT_MENU_TYPE" => "top",
                                        "USE_EXT" => "N"
                                    )
                                );?>
                                <div class="shop-menu-background"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>

    <?/*--- Хлебные крошки ---*/?>
    <?
        $darkBreadcrubs = $headerDark ? "gold" : "";
        
        if ($curentPage != '/' && $curentPage != '/personal/order/make/') {
            if ($headerDark) {
                $APPLICATION->IncludeComponent(
                    "bitrix:breadcrumb", 
                    "mm_breadcrumb__gold", 
                    array(
                        "PATH" => "",
                        "SITE_ID" => "s1",
                        "START_FROM" => "0",
                        "COMPONENT_TEMPLATE" => "mm_breadcrumb__gold",
                    ),
                    false
                );   
            } else {
                $APPLICATION->IncludeComponent(
                    "bitrix:breadcrumb",
                    "mm_breadcrumb",
                    Array(
                        "PATH" => "",
                        "SITE_ID" => "s1",
                        "START_FROM" => "0",
                    )
                );
            }
        }

    ?>
    <?/*--- left menu in personal START---*/?>
    <?
        $regexp = '/.*\/personal\/.*/';
        $showPersonal = preg_match($regexp , $curentPage) === 1 &&
            $curentPage !== '/personal/cart/' && 
            $curentPage !== '/personal/order/make/' &&
            $curentPage !== '/personal/order/payment/';
        $whithoutRegister = $curentPage === '/personal/otlozhennye-tovary/';
        if ($showPersonal) {?>
            <?
            global $USER;
            if (!$USER->IsAuthorized() && !$whithoutRegister ) {
                LocalRedirect('/login/');
            }
            $assets->addCss(
                '/local/templates/mm_main/assets/css/personal.css');?>
            <div class="content-container personal-container">
                <div class="personal-menu-wrapper">
                    
                    <?$APPLICATION->IncludeComponent(
                    "bitrix:menu", "personal_menu.mm", Array(
                        "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                        "CHILD_MENU_TYPE" => "personal_menu",	// Тип меню для остальных уровней
                        "DELAY" => "N",	// Откладывать выполнение шаблона меню
                        "MAX_LEVEL" => "1",	// Уровень вложенности меню
                        "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                        "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
                        "MENU_CACHE_TYPE" => "A",	// Тип кеширования
                        "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                        "ROOT_MENU_TYPE" => "personal_menu",	// Тип меню для первого уровня
                        "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                        "COMPONENT_TEMPLATE" => "personal_menu"
                    ),
                    false
                    );?>
                   
                </div>
                <div class="personal-content-wrapper">
       <? }