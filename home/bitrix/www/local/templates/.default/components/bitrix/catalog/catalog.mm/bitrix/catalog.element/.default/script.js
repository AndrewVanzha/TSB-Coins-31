function previewsPictures(event) {
    const target = event.currentTarget;

    // clear active image
    target.closest(".coin-preview-pictures").querySelector(".coin__preview-picture.active").classList.remove('active');

    // set clicked image active
    target.classList.add("active");

    const targetSrc = target.src;
    const targetAlt = target.alt;

    const bigCoinImage = document.querySelector('.coin-preview-big');
    bigCoinImage.src = targetSrc;
    bigCoinImage.alt = targetAlt;
}

function showMoreSpecs(event) {
    const tableWrapper = document.querySelector('.mobile-description-table .coin__description-table.mobile-specs');

    if (window.innerWidth <= 568) {
        const target = event ? event.currentTarget : document.getElementById("show-more-specs");

        const properties = tableWrapper.querySelectorAll('.coin__description-table__row');

        const isOpened = JSON.parse(tableWrapper.dataset.opened.toLowerCase());

        if (isOpened) {
            tableWrapper.style.maxHeight = `${(20 * 2) +
                properties[0].clientHeight + properties[1].clientHeight + properties[2].clientHeight}px`;

            tableWrapper.dataset.initialHeight = `${(20 * 2) +
                properties[0].clientHeight + properties[1].clientHeight + properties[2].clientHeight}px`;

            target.textContent = "Показать больше";
        }
        else {
            let maxHeight = 0;

            properties.forEach(elem => {
                maxHeight += elem.clientHeight + 20;
            });

            maxHeight = `${maxHeight - 20}px`;

            tableWrapper.style.maxHeight = maxHeight;

            target.textContent = "Показать меньше";
        }

        tableWrapper.dataset.opened = JSON.stringify(!isOpened);
    }
}

function showMoreSpecsResize(event) {
    const tableWrapper = document.querySelector('.mobile-description-table .coin__description-table.mobile-specs');
    const properties = tableWrapper.querySelectorAll('.coin__description-table__row');
    const isOpened = JSON.parse(tableWrapper.dataset.opened.toLowerCase());

    const target = document.getElementById("show-more-specs");

    if (window.innerWidth <= 568) {
        // список открыт
        if (isOpened) {
            let maxHeight = 0;

            properties.forEach(elem => {
                maxHeight += elem.clientHeight + 20;
            });

            maxHeight = `${maxHeight - 20}px`;

            tableWrapper.style.maxHeight = maxHeight;

            target.textContent = "Показать меньше";
        }
        else {
            tableWrapper.style.maxHeight = `${(20 * 2) +
                properties[0].clientHeight + properties[1].clientHeight + properties[2].clientHeight}px`;

            tableWrapper.dataset.initialHeight = `${(20 * 2) +
                properties[0].clientHeight + properties[1].clientHeight + properties[2].clientHeight}px`;

            target.textContent = "Показать больше";
        }
    }
    else {
        tableWrapper.style.maxHeight = 'max-content';
    }
}

document.addEventListener("DOMContentLoaded", e => {
    document.getElementById("share").addEventListener('click', share);

    document.querySelectorAll('.coin__preview-picture')
        .forEach(image => image.addEventListener('click', previewsPictures))

    showMoreSpecs();
    document.getElementById("show-more-specs").addEventListener("click", showMoreSpecs);

    window.addEventListener("resize", showMoreSpecsResize)
})
