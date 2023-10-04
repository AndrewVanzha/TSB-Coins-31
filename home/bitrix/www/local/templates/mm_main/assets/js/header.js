let mouseleaveTimerId = null;
let changeInputTimerId = null;
let mouseInTimer = null;

function openShopMenu(event) {
    const header = document.getElementById("page-header");

    const openShopBtns = document.querySelectorAll(".open-shop-menu")

    const shopMenuWrapper = document.querySelector(".shop-menu-wrapper");

    const headerCatalogMenuWrapper = document.querySelector(".header-bottom-navigation");

    if (openShopBtns[0].classList.contains('opened')) {
        header.classList.remove('filled-by-shop-menu')

        shopMenuWrapper.classList.add("hidden");
        headerCatalogMenuWrapper.classList.remove("hidden");

        openShopBtns.forEach(btn => btn.classList.remove("opened"));

        // если каталог закрыт
        if (!document.getElementById("open-mobile-catalog").classList.contains("opened")) {
            document.querySelector('.all-page-darkener').classList.remove("showed");
        }

        if (window.innerWidth <= 992) {
            shopMenuWrapper.style.height = "0";
        }
    }
    else {
        // FIXME сюда прям функция closeMobileCatalog просится

        //close mobile catalog
        document.getElementById("open-mobile-catalog").classList.remove("opened");
        const mobileCatalogMenu = document.querySelector("#page-header .mobile-catalog-navigation-wrapper");
        mobileCatalogMenu.classList.add("hidden");
        // remove dark bg 
        document.querySelector('.all-page-darkener').classList.remove("showed");
        mobileCatalogMenu.style.maxHeight = 0;
        setTimeout(() => {
            header.classList.remove("catalog-opened");
        }, 550)

        // if (header.classList.contains('transparent') && window.innerWidth <= 992)
        if (header.classList.contains('transparent')) {
            header.classList.add("filled-by-shop-menu");
        }

        // open shop menu
        shopMenuWrapper.classList.remove("hidden");
        headerCatalogMenuWrapper.classList.add("hidden");

        openShopBtns.forEach(btn => btn.classList.add("opened"));

        if (document.body.clientWidth <= 992) {
            document.querySelector('.all-page-darkener').classList.add("showed");

            // open in mobile
            const contentHeight = shopMenuWrapper.querySelector('.content-container').clientHeight;

            shopMenuWrapper.style.height = `${contentHeight}px`;
            //shopMenuWrapper.style.maxHeight = `${contentHeight}px`;
        }
    }
}

async function onChangeInput(event) {
    const query = event.currentTarget.value;

    const searchWrapper = document.querySelector('.header-search-wrapper');
    const searcResultsWrapper = searchWrapper.querySelector('#header-search-result');

    // searchWrapper.action = `/ search /? q = ${query} `;

    if (changeInputTimerId !== null) {
        clearTimeout(changeInputTimerId);
        // console.log('stoped ' + changeInputTimerId);
        changeInputTimerId = null;
    }
    if (query.length > 3) {
        // enable submit buttons
        searchWrapper.querySelectorAll("[type='submit']").forEach(el => el.disabled = false);

        changeInputTimerId = setTimeout(async () => {
            const resultsHTML = await onChangeSearch(query);
            // console.log('searched ' + changeInputTimerId);
            if (resultsHTML.length) {
                searcResultsWrapper.innerHTML = resultsHTML;
                searchWrapper.classList.add('got-results');
            } else {
                searcResultsWrapper.innerHTML = '';
                searchWrapper.classList.remove('got-results');
            }
        }, 600);
    } else {
        // disable submit buttons
        searchWrapper.querySelectorAll("[type='submit']").forEach(el => el.disabled = true);

        searcResultsWrapper.innerHTML = '';
        searchWrapper.classList.remove('got-results');
    }
}

function clearShopMenuStyles(event) {
    if (window.innerWidth > 992) {
        document.querySelector('.shop-menu-wrapper').style = "";
        document.querySelector('.all-page-darkener').classList.remove('showed');
    }
    else {
        if (document.querySelector('.open-shop-menu.mobile-button').classList.contains("opened")) {
            document.querySelector('.shop-menu-wrapper').style.height =
                document.querySelector('.shop-menu-wrapper .content-container').clientHeight + "px";

            document.querySelector('.all-page-darkener').classList.add('showed');
        }
    }
}

// top-search
async function onChangeSearch(query) {
    const response = await fetch('/local/templates/mm_main/assets/php/search.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ query })
    })
    const html = await response.text();
    return html;
}

function onInputBlur(event) {
    console.log("blur");

    document.getElementById("open-sticky-search").classList.remove('hidden');

    const searchWrapper = document.querySelector('header .header-search-wrapper');
    searchWrapper.classList.remove('active');

    const headerUp = document.querySelector("#page-header .header-up");
    headerUp.classList.remove("search-opened");

    if (event === null) {
        searchWrapper.querySelector('input').blur();
    }
}

function onInputKeydown(event) {
    if (event.key === "Escape") {
        onInputBlur(null);
    }
    // else if (event.key === "Enter") {
    //     const searchWrapper = document.querySelector('.header-search-wrapper');
    //     const link = searchWrapper.querySelector('.link-to-all-results');
    //     if (event.currentTarget.value.trim() !== "") {
    //         window.location = link.href;
    //     }
    // }
}

function onInputFocus(event) {
    // console.log("input focus");

    const searchWrapper = document.querySelector('#page-header .header-search-wrapper');
    searchWrapper.classList.add('active');

    // document.getElementById('header-search-input').focus();

    const headerUp = document.querySelector("#page-header .header-up");
    headerUp.classList.add("search-opened");
}
let prevY = window.scrollY;
function onPageScroll(event) {
    const header = document.querySelector("header#page-header");
    const headerUp = header.querySelector(".header-up");
    console.log(event.deltaY);
    if (window.scrollY > headerUp.clientHeight) {
        header.classList.add('sticky');
        const deltaY = prevY - window.scrollY;
        if (deltaY < 0) {
            header.classList.add('mobile-hidden');
        } else {
            header.classList.remove('mobile-hidden');
        }
        prevY = window.scrollY;
    }
    else {
        header.classList.remove('sticky');
    }
}

function openStickyHeaderSearch(event) {
    event.currentTarget.classList.add('hidden');

    // const input = document.getElementById('header-search-input');

    // input.focus();

    // document.getElementById('header-search-input').focus();

    onInputFocus();

    setTimeout(() => {
        document.getElementById('header-search-input').focus();
    }, 0)
}

function openMobileCatalog(event) {
    // button elem
    const target = event.currentTarget;

    // header elem
    const header = document.getElementById("page-header");

    // <nav> wrapper elem, not whole block
    const mobileCatalogMenu = target.closest(".mobile-catalog-wrapper")
        .querySelector(".mobile-catalog-navigation-wrapper");

    // if menu opened
    if (target.classList.contains("opened")) {
        setTimeout(() => {
            header.classList.remove("catalog-opened");
        }, 550)

        mobileCatalogMenu.classList.add("hidden");
        header.classList.remove('filled-by-shop-menu')

        // remove dark bg 
        document.querySelector('.all-page-darkener').classList.remove("showed");

        mobileCatalogMenu.style.maxHeight = 0;
    }
    // if menu closed
    else {
        if (header.classList.contains('transparent')) {
            header.classList.add("filled-by-shop-menu");
        }

        header.classList.add("catalog-opened");

        mobileCatalogMenu.classList.remove("hidden");

        const mobileCatalogNavElem = mobileCatalogMenu.querySelector(".mobile-catalog-navigation");

        // show dark bg
        document.querySelector('.all-page-darkener').classList.add("showed");

        mobileCatalogMenu.style.maxHeight = `${mobileCatalogNavElem.clientHeight}px`;
    }

    target.classList.toggle("opened");
}

// cart block
function closeHeaderCartByEsc(event) {
    if (event.key === "Escape") {
        closeHeaderCart(null);
    }
}

function openHeaderCart(event) {
    clearMouseleaveTimeout();
    clearTimeout(mouseInTimer);
    const target = document.getElementById("open-header-cart");
    const parentWrapper = target.closest(".cart-wrapper");
    const itemsWrapper = parentWrapper.querySelector('.header-cart-items-wrapper');

    if (!itemsWrapper) return;
    mouseInTimer = setTimeout(() => {
        parentWrapper.classList.add("opened");
        document.addEventListener('keydown', closeHeaderCartByEsc);
    }, 300)


}
//set timeout to close, if we are leaving
function mouseleaveClosing() {
    clearTimeout(mouseInTimer);
    const itemsWrapper = document.querySelector('.personal-navigation__item.cart-wrapper .header-cart-items-wrapper');
    if (!itemsWrapper) return;
    mouseleaveTimerId = setTimeout(() => {
        closeHeaderCart(null);
    },
        1000);
}

//clear timeout, if we back
function clearMouseleaveTimeout() {
    if (mouseleaveTimerId !== null) {
        clearTimeout(mouseleaveTimerId);
        mouseleaveTimerId = null;
    }
}

function closeHeaderCart(event) {
    clearMouseleaveTimeout();
    clearTimeout(mouseInTimer);
    const wrapper = document.getElementById("open-header-cart").closest(".cart-wrapper");

    wrapper.classList.remove("opened");

    document.removeEventListener("keydown", closeHeaderCartByEsc);
}

document.addEventListener("DOMContentLoaded", e => {
    document.querySelectorAll(".open-shop-menu")
        .forEach(button => button.addEventListener('click', openShopMenu));

    document.getElementById('header-search-input').addEventListener('focus', onInputFocus);
    // document.getElementById('header-search-input').addEventListener('blur', onInputBlur);

    // input blur;
    const elems = [];

    const bg = document.querySelector('.header-search-sticky-bg');
    elems.push(bg);

    const goldX = document.getElementById("close-input");
    elems.push(goldX);

    // console.log(elems);

    elems.forEach(item => {
        item.addEventListener('click', onInputBlur);
    })

    document.getElementById('header-search-input').addEventListener('input', onChangeInput);
    document.getElementById('header-search-input').addEventListener("keydown", onInputKeydown);

    window.addEventListener('scroll', onPageScroll);

    document.getElementById('open-sticky-search').addEventListener('click', openStickyHeaderSearch);

    document.getElementById("open-mobile-catalog").addEventListener("click", openMobileCatalog);

    // clear shop-menu-styles
    window.addEventListener('resize', clearShopMenuStyles);

    // cart
    //mouseover cart
    document.querySelector(".personal-navigation__item.cart-wrapper .header-cart-wrap").addEventListener('mouseover', openHeaderCart);
    //mouseleave cart
    document.querySelector(".personal-navigation__item.cart-wrapper .header-cart-wrap").addEventListener('mouseleave', mouseleaveClosing);
    document.querySelector(".personal-navigation__item.cart-wrapper .header-cart-fixed-bg").addEventListener('click', closeHeaderCart);
})