// ---------------------------  //
// ДОБАВЛЕНИЕ ТОВАРА В КОРЗИНУ //
// -------------------------- //


function addToCartProduct(element_id, quant) {
    $.ajax({
        url: "/katalog/inostrannye/?action=ADD2BASKET&id=" + element_id + "&quantity=" + quant + "&ajax_basket=Y",
        success: function (json) {
            $.ajax({
                type: "POST",
                cache: false,
                url: "/local/include/ajax/addToCartFancybox.php",
                dataType: "html",
                data: {
                    product_id: element_id,
                    quant: quant
                },
                success: function (html) {
                    //Обновляем малую корзину
                    BX.onCustomEvent('OnBasketChange');
                    //Всплывающее окно
                    openProductAdded(element_id);
                }
            });
        }
    });
    return false;
}

// ---------------------------  //
// ФОРМА УЗНАТЬ ЦЕНУ           //
// -------------------------- //
function findPopupSubscription(element_id) {
    openKnowArrivalModal(element_id);
}

function addToDesiered(target, id) {
    const action = !(target.classList.contains('liked'));
    target.classList.toggle('liked');
    debouncedToggleDesired(id, action, console.log);
}

function initInlineGallery(inlineGallery) {
    if (inlineGallery.classList.contains('images__line-galery-init')) return;

    inlineGallery.classList.add('images__line-galery-init');
    const images = inlineGallery
        .querySelectorAll('.images__line-galery-item');
    const quant = images.length;
    const bullets = inlineGallery
        .querySelectorAll('.images__line-galery-bullets span');
    if (!("ontouchstart" in document.documentElement)) {
        inlineGallery.addEventListener('mousemove', (e) => {

            const targetCoords = inlineGallery.getBoundingClientRect();
            const relX = e.clientX - targetCoords.left;
            const inlineGalleryWidth = targetCoords.width;
            const inlineGalleryStep = inlineGalleryWidth / quant;
            let currentStep = relX > 0 ?
                Math.floor(relX / inlineGalleryStep) : 0;

            if (currentStep >= quant - 1) {
                currentStep = quant - 1;
            }

            if (images[currentStep].classList.contains('active')) return;

            inlineGallery
                .querySelector('.images__line-galery-item.active')
                .classList.remove('active');
            inlineGallery
                .querySelector('.images__line-galery-bullets span.active')
                .classList.remove('active');
            images[currentStep].classList.add('active');
            bullets[currentStep].classList.add('active');
        });
        inlineGallery.addEventListener('mouseout', (e) => {
            inlineGallery
                .querySelector('.images__line-galery-item.active')
                .classList.remove('active');
            inlineGallery
                .querySelector('.images__line-galery-bullets span.active')
                .classList.remove('active');
            images[0].classList.add('active');
            bullets[0].classList.add('active');
        });
    } else {
        inlineGallery.classList.add('touch');
        const inlineGalleryInner = inlineGallery.querySelector('.images__line-galery-items-wrapper');
        const inlineGalleryInnerWidth = inlineGalleryInner.clientWidth;
        inlineGalleryInner.addEventListener('scroll', (e) => {
            const currentScroll = inlineGalleryInner.scrollLeft;

            const currentStep = Math.floor(currentScroll / (inlineGalleryInnerWidth + 5));

            if (bullets[currentStep].classList.contains('active')) return;

            inlineGallery
                .querySelector('.images__line-galery-bullets span.active')
                .classList.remove('active');
            bullets[currentStep].classList.add('active');
        })
    }
}

function changeRender(event) {
    const target = event.currentTarget;

    document.querySelector('.render-options .render-options__button.active').classList.remove('active');

    target.classList.add('active');

    document.querySelector('.catalog-coins-items').classList.remove('list');
    document.querySelector('.catalog-coins-items').classList.remove('grid');

    document.querySelector('.catalog-coins-items').classList.add(target.dataset.renderType);
}

function showMore(e) {
    const button = e.currentTarget;
    const baseValue = Number(button.dataset.base);
    const next = button.dataset.next;
    const catalogWrapper = document.querySelector('.catalog-coins-items');
    const quantElement = document.querySelector('.catalog_pagination-quant .catalog_pagination-quant-current');
    button.textContent = 'Загрузка';
    $.ajax({
        url: next,
        success: function (html) {
            const elements = $(html).find('.catalog-coins-items').html();
            const newButton = $(html).find('#show_more');
            const newNext = newButton.data('next');
            if (newNext) {
                button.dataset.next = newNext;
                button.textContent = 'Показать еще';
            } else {
                button.remove();
            }
            $(catalogWrapper).append(elements);
            const allElements = catalogWrapper.querySelectorAll('.catalog-coins-items .catalog-coins__coin-item');
            quantElement.textContent = baseValue + allElements.length;

            const newGalleries = catalogWrapper.querySelectorAll('.catalog-coins-items .catalog-coins__coin-item .images__line-galery:not(.images__line-galery-init)');
            newGalleries.forEach(newGalleriy => initInlineGallery(newGalleriy))
        }
    });
}