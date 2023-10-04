let arrivalModalTimeout = null;

function openProductAdded(id) {
    if (!id) return;
    fetch('/local/templates/mm_main/assets/php/product_add_body.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id })
    })
        .then(response => response.text())
        .then(html => {
            const productAddedWrapper = document.querySelector(".product-added-modal__inner-wrapper");
            const productAddedBody = productAddedWrapper.querySelector('.coin-info');
            // document.querySelector(".all-page-darkener").classList.add("showed", "product-added-opened");
            document.querySelector(".know-arrival-bg").classList.add("showed", "product-added-opened");
            productAddedBody.innerHTML = html;
            productAddedWrapper.classList.add("showed");

            // if (window.innerWidth <= 992) {
            document.getElementById('mobile-close-modal').classList.add('showed');
            // }
        })
}

function closeAddedProductModal(event) {
    // document.querySelector(".all-page-darkener").classList.remove("showed", "product-added-opened");
    document.querySelector(".know-arrival-bg").classList.remove("showed", "product-added-opened");
    document.querySelector(".product-added-modal__inner-wrapper").classList.remove("showed");

    document.getElementById('mobile-close-modal').classList.remove('showed');
}

// function openKnowArrival(event) {
//     document.querySelector(".know-arrival-bg").classList.add("showed", "product-added-opened");
//     document.querySelector(".know-about-arrival__inner-content").classList.add("showed");
//     // document.querySelector(".all-page-darkener").classList.add("showed", "product-added-opened");

//     if (window.innerWidth <= 992) {
//         document.getElementById('mobile-close-know-arrival').classList.add('showed');
//     }
// }

function closeKnowArrival(event) {
    if (arrivalModalTimeout !== null) clearTimeout(arrivalModalTimeout);

    document.querySelector(".know-about-arrival__inner-content").classList.remove("showed");
    // document.querySelector(".all-page-darkener").classList.remove("showed", "product-added-opened");
    document.querySelector(".know-arrival-bg").classList.remove("showed", "product-added-opened");

    document.querySelector(".modal-know-arrival__title").classList.remove("hidden");
    document.querySelector(".modal-know-arrival__form").classList.remove("hidden");

    document.getElementById('mobile-close-know-arrival').classList.remove('showed');

    document.querySelector(".success-message-wrapper").classList.remove('showed');
}

function openKnowArrivalModal(id) {
    document.querySelector(".know-arrival-bg").classList.add("showed", "product-added-opened");

    const innerContent = document.querySelector(".know-about-arrival__inner-content");
    innerContent.classList.add("showed");

    const modalTitle = innerContent.querySelector('.modal-know-arrival__title');
    modalTitle.textContent = 'Узнать о поступлении';

    const modalForm = document.querySelector('.know-about-arrival__inner-content form');
    const productInput = modalForm.querySelector('input[name="productid"]');
    productInput.value = id;
    modalForm.dataset.action = "";

    // if (window.innerWidth <= 992) {
    document.getElementById('mobile-close-know-arrival').classList.add('showed');
    // }
}

function openSellModal(id) {
    document.querySelector(".know-arrival-bg").classList.add("showed", "product-added-opened");

    const innerContent = document.querySelector(".know-about-arrival__inner-content");
    innerContent.classList.add("showed");

    const modalTitle = innerContent.querySelector('.modal-know-arrival__title');
    modalTitle.textContent = 'Продать монету';

    const modalForm = document.querySelector('.know-about-arrival__inner-content form');
    const productInput = modalForm.querySelector('input[name="productid"]');
    productInput.value = id;
    modalForm.dataset.action = "sell";


    document.getElementById('mobile-close-know-arrival').classList.add('showed');
}

function closeByEscape(event) {
    if (event.key === "Escape") {
        closeAddedProductModal(null);
        closeKnowArrival(null);
    }
}

function closeModalsByClick(event) {
    if (window.innerWidth > 992) {
        closeAddedProductModal(null);
        closeKnowArrival(null);
    }
}


function validateArrivalFields(productInput, userNameInput, userPhoneInput) {
    let result = true;

    if (!productInput.value) {
        //тут не надо это на всякий
        result = false;
    }
    if (!userNameInput.value) {
        //работа с инпутом
        userNameInput.classList.add("invalid");

        result = false;
    }
    else {
        userNameInput.classList.remove("invalid");
    }

    //работа с инпутом телефона
    const telArray = userPhoneInput.value.split('');

    console.log(userPhoneInput);

    if (!telArray.length || telArray.includes('_')) {
        result = false;
        userPhoneInput.classList.add('invalid');
    }
    else {
        userPhoneInput.classList.remove('invalid');
    }

    return result;
}

function sendKnowArrival(event) {
    event.preventDefault();

    const modalForm = event.currentTarget;
    const isSell = modalForm.dataset.action === 'sell';

    const productInput =
        modalForm.querySelector('input[name="productid"]');
    const userNameInput =
        modalForm.querySelector('#arrival-form__form-name');
    const userPhoneInput =
        modalForm.querySelector('#arrival-form__form-tel');

    if (validateArrivalFields(productInput, userNameInput, userPhoneInput)) {

        const subscribeURL = '/local/templates/mm_main/assets/php/productsubscribe.php';
        const sellURL = '/local/templates/mm_main/assets/php/productsell.php';
        const url = isSell ? sellURL : subscribeURL;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'name': userNameInput.value,
                'phone': userPhoneInput.value,
                'id': productInput.value
            })
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                if (data) {
                    document.querySelector(".modal-know-arrival__title").classList.add("hidden");
                    document.querySelector(".modal-know-arrival__form").classList.add("hidden");

                    document.querySelector(".success-message-wrapper").classList.add('showed');

                    userNameInput.value = '';
                    userPhoneInput.value = '';

                    arrivalModalTimeout = setTimeout(() => {
                        clearTimeout(arrivalModalTimeout);
                        closeKnowArrival(null);
                    }, 3000)
                }
            })

    }

}

document.addEventListener("DOMContentLoaded", e => {
    if (document.querySelector("[data-open-added-modal]")) {
        document.querySelector("[data-open-added-modal]").addEventListener('click', openProductAdded)
    }

    if (document.querySelector("[data-close-added-modal]")) {
        document.querySelectorAll("[data-close-added-modal]").forEach(button => button.addEventListener('click', closeAddedProductModal));
    }

    if (document.querySelector("[data-close-know-about-arrival]")) {
        document.querySelectorAll("[data-close-know-about-arrival]").forEach(btn => btn.addEventListener("click", closeKnowArrival));
    }

    // if (document.querySelector("[data-open-know-arrival-modal]")) {
    //     document.querySelectorAll("[data-open-know-arrival-modal]").forEach(btn => btn.addEventListener('click', openKnowArrival))
    // }

    // if (document.querySelector(".know-about-arrival__inner-content")) {
    //у тебя нет этого класса в форме
    document.body.addEventListener('keydown', closeByEscape);

    document.querySelector(".all-page-darkener").addEventListener('click', closeModalsByClick);
    document.querySelector(".know-arrival-bg").addEventListener('click', closeModalsByClick);
    document.querySelector("form.modal-know-arrival__form").addEventListener('submit', sendKnowArrival)
    // }
})