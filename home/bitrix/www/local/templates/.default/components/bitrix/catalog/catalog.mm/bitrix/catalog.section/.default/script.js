






// mouseover_gallery


function openMobileFilters(event) {
    const target = event.currentTarget;

    target.classList.toggle("filters-opened");

    const catalogFilter = document.querySelector(".catalog-filter");
    const catalogFilterFormWrapper = catalogFilter.querySelector(".catalog-form-wrapper");

    if (target.classList.contains("filters-opened")) {
        catalogFilter.classList.add("opened");

        catalogFilterFormWrapper.dataset.initialHeight = `${catalogFilter.querySelector('form').clientHeight}`;
        catalogFilterFormWrapper.style.maxHeight = `${catalogFilter.querySelector('form').clientHeight}px`;

    }
    else {
        catalogFilter.classList.remove("opened");

        catalogFilterFormWrapper.dataset.initialHeight = `0`;
        catalogFilterFormWrapper.style.maxHeight = `0`;
    }
}

function onResizeFilters(event) {
    const catalogFilter = document.querySelector(".catalog-filter");
    const catalogFilterFormWrapper = catalogFilter.querySelector(".catalog-form-wrapper");

    if (catalogFilter.classList.contains("opened")) {
        const initialHeight = parseInt(catalogFilterFormWrapper.dataset.initialHeight);
        const currentHeight = catalogFilter.querySelector('form').clientHeight;

        if (currentHeight > initialHeight || currentHeight < initialHeight) {
            catalogFilterFormWrapper.dataset.initialHeight = `${currentHeight}`;
            catalogFilterFormWrapper.style.maxHeight = `${currentHeight}px`;
        }
    }
}




document.addEventListener('DOMContentLoaded', () => {
    const inlineGalleries = document.querySelectorAll('.catalog-coins__coin-item .images__line-galery');

    inlineGalleries.forEach(inlineGallery => initInlineGallery(inlineGallery));

    const showMoreButton = document.querySelector('#show_more');
    showMoreButton?.addEventListener('click', showMore);

    document.querySelectorAll('.render-options .render-options__button').forEach(button => {
        button.addEventListener('click', changeRender);
    })

    document.getElementById("open-mobile-filters")?.addEventListener('click', openMobileFilters);

    if(document.querySelector(".catalog-filter"))
     window.addEventListener("resize", onResizeFilters);
});



