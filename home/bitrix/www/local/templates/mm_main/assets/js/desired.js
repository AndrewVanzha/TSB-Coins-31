async function toggleDesired(id, add = true, callback = null) {
    const response = await fetch('/local/templates/mm_main/assets/php/desired.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, add })
    });
    const data = await response.text();
    if(callback !== null) callback(data);
}

function debounceFn(fn, timer) {
    let debounceTimerId = null;
    return (...args) => {
        if (debounceTimerId != null) {
            clearTimeout(debounceTimerId);
            debounceTimerId = null;
        }
        debounceTimerId = setTimeout(() => {
            fn(...args)
        }, timer);
    }
}

const debouncedToggleDesired = debounceFn(toggleDesired, 600);


