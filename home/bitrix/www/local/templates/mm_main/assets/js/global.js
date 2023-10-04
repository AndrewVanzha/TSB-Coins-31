$(document).ready(function () {

    $('.doc-plank img').click(function () {
        $('.doc-plank').hide();

        $.ajax({
            url: "/local/include/ajax/docPlank.php",
        });
    });

    $('.fancybox').fancybox();

    $('.slider-main').slick({
        autoplay: true,
        fade: true,
        cssEase: 'linear',
        dots: true,
        prevArrow: '<div class="slick-prev arrows-1"></div>',
        nextArrow: '<div class="slick-next arrows-1"></div>',
        appendArrows: $('.nav_slider')
    });

    $('.carousel').slick({
        // autoplay: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        prevArrow: '<div class="slick-prev arrows-2"></div>',
        nextArrow: '<div class="slick-next arrows-2"></div>',
        responsive: [
            {
                breakpoint: 1220,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 920,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 620,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    $('.slider-content').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-thumbs'
    });
    $('.slider-thumbs').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.slider-content',
        prevArrow: '<div class="slick-prev arrows-2"></div>',
        nextArrow: '<div class="slick-next arrows-2"></div>',
        focusOnSelect: true
    });

    //fixed header
    $(window).scroll(function () {
        var scr = $(this).scrollTop();
        var height = $('.header-main').innerHeight();

        if (scr >= height) {
            $('.header-main').addClass('fixed');
        } else {
            $('.header-main').removeClass('fixed');
        }
    });

    //MOBILE MENU 
    $('.hamburger').click(function () {
        $(this).toggleClass('is-active');

        $('.header-nav ul').slideToggle(function () {
            if ($(this).css('display') === 'none') {
                $(this).removeAttr('style');
            }
        });
    });

    $('.toggle-btn').click(function () {
        $('.mobile-toggle-wrap').css('left', '0');
        $('body').addClass('menu');
    });
    $('.mobile-toggle').click(function () {
        $('.mobile-toggle-wrap').css('left', '');
        $('body').removeClass('menu');
    });

    //FILTER TOGGLE
    $('.mobile-filter').click(function () {
        $('.filter-toggle').slideToggle(function () {
            if ($(this).css('display') === 'none') {
                $(this).removeAttr('style');
            }
        });
    });

    //VIEW
    $('.controls-view').click(function (event) {
        $('.controls-view').removeClass('active');
        $(this).addClass('active');
        if ($('.controls-view.active').hasClass('table')) {
            $('.product-wrap .product-box').removeClass('list').addClass('table');
            $.cookie('view', 'table', { expires: 1, path: '/' });

        } else {
            $('.product-wrap .product-box').removeClass('table').addClass('list');
            $.cookie('view', 'list', { expires: 1, path: '/' });
        }
    });

    $('.reviews-link a').click(function () {
        var target = $(this).attr('href');
        if ($(window).width() >= 904) {
            $('html, body').animate({ scrollTop: $(target).offset().top - 142 }, 800);
        } else {
            $('html, body').animate({ scrollTop: $(target).offset().top }, 800);
        }
        return false;
    });

    $('.history-bid-all').click(function () {
        $(this).hide();
        $('.history-bid-hidden').slideToggle();
    });

    $('.content-table-all').click(function () {
        $(this).hide();
        $('.table-hidden').slideToggle();
    });

    //mask 
    $(".js-mask").mask("+7(999) 999-9999");

    $(".sidebar-result").stick_in_parent({
        offset_top: 145
    });


    //Tabs
    $('ul.tabs-list').on('click', 'li:not(.current)', function () {
        $(this).addClass('current').siblings().removeClass('current')
            .parents('div.tabs-wrap').find('div.box').eq($(this).index()).fadeIn(100).addClass('box-visible').siblings('div.box').hide().removeClass('box-visible');
        $(this).parents('.tabs-wrap').find('.slick-slider').slick('setPosition');

    });

    //PREVIEWFOTO
    // var previewWidth = 93, // ширина превью
    //     previewHeight = 93, // высота превью
    //     maxFileSize = 1 * 1024 * 1024, // (байт) Максимальный размер файла
    //     selectedFiles = {},// объект, в котором будут храниться выбранные файлы
    //     queue = [],
    //     image = new Image(),
    //     imgLoadHandler,
    //     isProcessing = false,
    //     errorMsg, // сообщение об ошибке при валидации файла
    //     previewPhotoContainer = document.querySelector('#preview-photo'); // контейнер, в котором будут отображаться превью

    // Когда пользователь выбрал файлы, обрабатываем их
    $('input[type=file][id=photo]').on('change', function () {
        var newFiles = $(this)[0].files; // массив с выбранными файлами

        for (var i = 0; i < newFiles.length; i++) {

            var file = newFiles[i];

            // В качестве "ключей" в объекте selectedFiles используем названия файлов
            // чтобы пользователь не мог добавлять один и тот же файл
            // Если файл с текущим названием уже существует в массиве, переходим к следующему файлу
            if (selectedFiles[file.name] != undefined) continue;

            // Валидация файлов (проверяем формат и размер)
            if (errorMsg = validateFile(file)) {
                alert(errorMsg);
                return;
            }

            // Добавляем файл в объект selectedFiles
            selectedFiles[file.name] = file;
            queue.push(file);

        }

        $(this).val('');
        processQueue(); // запускаем процесс создания миниатюр
    });

    // Валидация выбранного файла (формат, размер)
    var validateFile = function (file) {
        if ( !file.type.match(/image\/(jpeg|jpg|png)/) && !file.type.match(/pdf/)) {
            return 'Фотография должна быть в формате jpg, png, pdf';
        }

        if ( file.size > maxFileSize ) {
            return 'Размер фотографии не должен превышать 1 Мб';
        }
    };

    var listen = function (element, event, fn) {
        return element.addEventListener(event, fn, false);
    };

    // Создание миниатюры
    var processQueue = function () {
        // Миниатюры будут создаваться поочередно
        // чтобы в один момент времени не происходило создание нескольких миниатюр
        // проверяем запущен ли процесс
        if (isProcessing) { return; }

        // Если файлы в очереди закончились, завершаем процесс
        if (queue.length == 0) {
            isProcessing = false;
            return;
        }

        isProcessing = true;

        var file = queue.pop(); // Берем один файл из очереди

        var li = document.createElement('LI');
        var span = document.createElement('SPAN');
        var spanDel = document.createElement('SPAN');
        var canvas = document.createElement('CANVAS');
        var ctx = canvas.getContext('2d');

        span.setAttribute('class', 'img');
        spanDel.setAttribute('class', 'delete');
        spanDel.innerHTML = '<i class="fa fa-times"></i>';

        li.appendChild(span);
        li.appendChild(spanDel);
        li.setAttribute('data-id', file.name);

        image.removeEventListener('load', imgLoadHandler, false);

        // создаем миниатюру
        imgLoadHandler = function () {
            ctx.drawImage(image, 0, 0, previewWidth, previewHeight);
            URL.revokeObjectURL(image.src);
            span.appendChild(canvas);
            isProcessing = false;
            setTimeout(processQueue, 200); // запускаем процесс создания миниатюры для следующего изображения
        };

        // Выводим миниатюру в контейнере previewPhotoContainer
        previewPhotoContainer.appendChild(li);
        listen(image, 'load', imgLoadHandler);

        if (file.type.match(/pdf/))
        {
            image.src = "/upload/mm_upload/pdf_file.svg";
        }
        else
        {
            image.src = URL.createObjectURL(file);
        }

        // Сохраняем содержимое оригинального файла в base64 в отдельном поле формы
        // чтобы при отправке формы файл был передан на сервер
        var fr = new FileReader();
        fr.readAsDataURL(file);
        fr.onload = (function (file) {
            return function (e) {
                $('#preview-photo').append(
                    '<input type="hidden" name="PROP[MORE_PHOTO][]" value="' + e.target.result + '" data-id="' + file.name + '">'
                );
            }
        })(file);
    };

    //регистрационная форма
    $('#regform').submit(function (e) {
        if ($(this).find('input[name="agreements"]').prop("checked")) {
            return true;
        } else {
            if ($('.form-contacts').find('.message_err').length) {

                var parent_block = $(this).find('.message_err');
                var error_text = parent_block.find('.errortext').html();

                if (!parent_block.find('.errortext').find('.error_agreement').length) {
                    error_text += '<div class="error_agreement">Подтвердите свое согласие с условиями политики конфиденциальности</div>';

                    $(this).find('.message_err').find('.errortext').html(error_text);
                }

            }
            else {
                var error_text = '<div class="message_err"><font class="errortext"><div class="error_agreement">Подтвердите свое согласие с условиями политики конфиденциальности</div></font></div>';
                $(this).before(error_text);
            }
            return false;
        }
    });

    //регистрационная форма
    $('#regform').submit(function (e) {
        if ($(this).find('input[name="agreements"]').prop("checked")) {
            return true;
        } else {
            if ($('.form-contacts').find('.message_err').length) {

                var parent_block = $(this).find('.message_err');
                var error_text = parent_block.find('.errortext').html();

                if (!parent_block.find('.errortext').find('.error_agreement').length) {
                    error_text += '<div class="error_agreement">Подтвердите свое согласие с условиями политики конфиденциальности</div>';

                    $(this).find('.message_err').find('.errortext').html(error_text);
                }

            }
            else {
                var error_text = '<div class="message_err"><font class="errortext"><div class="error_agreement">Подтвердите свое согласие с условиями политики конфиденциальности</div></font></div>';
                $(this).before(error_text);
            }
            return false;
        }
    });

    //всплывающее окно с подпиской
    /*
        if (!$.cookie('displayed')) {
    
            function show_popup() {
    
                $.fancybox.open({
                    src  : '#popup-subs',
                    type : 'inline',
                    opts : {
                        onComplete : function() {
                            $.cookie('displayed', true, { expires: 7, path: '/' });
                        }
                    }
                });
            }       
    
            function show_popup() {
    
                $.fancybox.open({
                    src  : '#popup-get',
                    type : 'inline',
                    opts : {
                        onComplete : function() {
                            $.cookie('displayed', true, { expires: 7, path: '/' });
                        }
                    }
                });
            }
    
            // показать окно через 30 сек.
            setTimeout(show_popup, 30000);
        }
    */
});