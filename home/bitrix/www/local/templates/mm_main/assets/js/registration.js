function slideToActive(target) {
    
    const formChangeLine = document.getElementById('form-change-line');

    formChangeLine.style.width = `${target.clientWidth}px`;
    formChangeLine.style.left = `${target.offsetLeft}px`;
}


function changeFilter(e) {
    const button = e.currentTarget;
    if (button.classList.contains('active')) return;
    const activeButton = document.querySelector('.form-change-filter button.form-change-filter__button.active');
    const activeFormId = activeButton.dataset.target;
    const activeForm = document.getElementById(activeFormId);
    const formId = button.dataset.target;
    const form = document.getElementById(formId);


    activeButton.classList.remove('active');
    button.classList.add('active');
    if (activeForm) {
        activeForm.classList.remove('visible');
    }
    
    if (form) {
        form.classList.add('visible');
    }
    

    slideToActive(button);
}

function renderSlideActive() {
    const activeButton = document.querySelector('.form-change-filter button.form-change-filter__button.active');
    slideToActive(activeButton);
}

document.addEventListener('DOMContentLoaded', () => {
    const changeFilterButtons = document.querySelectorAll('.form-change-filter button.form-change-filter__button');

    changeFilterButtons.forEach(
        changeFilterButton => changeFilterButton
            .addEventListener('click', changeFilter)
    );
    renderSlideActive();

})