let mobile_changeInputTimerId = null;

function openMobileSearch(event) {
    document.querySelector(".mobile-search").classList.add("shown");
}

function closeMobileSearch(event) {
    document.querySelector(".mobile-search").classList.remove("shown");
}

async function onMobileSearchInput(event) {
    const target = event.currentTarget;

    const query = target.value.trim();

    const form = document.querySelector(".inner-mobile-search-wrapper");

    // form.action = `/search?q=${query}`;

    if (mobile_changeInputTimerId !== null) {
        clearTimeout(mobile_changeInputTimerId);
        console.log('footer stoped ' + mobile_changeInputTimerId);
        mobile_changeInputTimerId = null;
    }

    if (query.length >= 3) {
        mobile_changeInputTimerId = setTimeout(async () => {
            const response = await fetch('/local/templates/mm_main/assets/php/search.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ query })
            })
            const html = await response.text();

            form.querySelector(".mobile-search-results").innerHTML = html;
        }, 600)
    }
    else {
        form.querySelector(".mobile-search-results").innerHTML = '';
    }
}

document.addEventListener("DOMContentLoaded", e => {
    document.getElementById("close-mobile-search").addEventListener("click", closeMobileSearch);
    document.getElementById("open-mobile-search").addEventListener("click", openMobileSearch);

    document.getElementById("mobile-search-input").addEventListener("input", onMobileSearchInput);
})