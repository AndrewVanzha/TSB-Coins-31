function initMasks() {
    let phoneMask = new Inputmask({
        mask: "+7 (999) 999-99-99",
        inputmode: "tel"
    });

    let selectors = "input[type='tel']";
    selectors += " ,input.js-mask";
    selectors += " ,input[name='PERSONAL_PHONE']";
    selectors += " ,#ORDER_PROP_3";
    selectors += " ,input[name='ORDER_PROP_3']";

    document.querySelectorAll(selectors).forEach(telInput => {
        phoneMask.mask(telInput);
    })
}

document.addEventListener("DOMContentLoaded", e => {
    initMasks();
})
	//PREVIEWFOTO
    var previewWidth = 93, // ширина превью
        previewHeight = 93, // высота превью
        maxFileSize = 1 * 1024 * 1024, // (байт) Максимальный размер файла
        selectedFiles = {},// объект, в котором будут храниться выбранные файлы
        queue = [],
        image = new Image(),
        imgLoadHandler,
        isProcessing = false,
        errorMsg, // сообщение об ошибке при валидации файла
        previewPhotoContainer = document.querySelector('#preview-photo'); // контейнер, в котором будут отображаться превью

    // // Когда пользователь выбрал файлы, обрабатываем их
    // $('input[type=file][id=photo]').on('change', function() {
    //     var newFiles = $(this)[0].files; // массив с выбранными файлами
    //     console.log(1);
    //     for (var i = 0; i < newFiles.length; i++) {

    //         var file = newFiles[i];

    //         // В качестве "ключей" в объекте selectedFiles используем названия файлов
    //         // чтобы пользователь не мог добавлять один и тот же файл
    //         // Если файл с текущим названием уже существует в массиве, переходим к следующему файлу
    //         if (selectedFiles[file.name] != undefined) continue;

    //         // Валидация файлов (проверяем формат и размер)
    //         if ( errorMsg = validateFile(file) ) {
    //             alert(errorMsg);
    //             return;
    //         }

    //         // Добавляем файл в объект selectedFiles
    //         selectedFiles[file.name] = file;
    //         queue.push(file);

    //     }

    //     $(this).val('');
    //     processQueue(); // запускаем процесс создания миниатюр
    // });

    // // Валидация выбранного файла (формат, размер)
    // var validateFile = function(file)
    // {
    //     if ( !file.type.match(/image\/(jpeg|jpg|png|gif)/) ) {
    //         return 'Фотография должна быть в формате jpg, png или gif';
    //     }

    //     if ( file.size > maxFileSize ) {
    //         return 'Размер фотографии не должен превышать 1 Мб';
    //     }
    // };

    // var listen = function(element, event, fn) {
    //     return element.addEventListener(event, fn, false);
    // };

    // // Создание миниатюры
    // var processQueue = function()
    // {
    //     // Миниатюры будут создаваться поочередно
    //     // чтобы в один момент времени не происходило создание нескольких миниатюр
    //     // проверяем запущен ли процесс
    //     if (isProcessing) { return; }

    //     // Если файлы в очереди закончились, завершаем процесс
    //     if (queue.length == 0) {
    //         isProcessing = false;
    //         return;
    //     }

    //     isProcessing = true;

    //     var file = queue.pop(); // Берем один файл из очереди

    //     var li = document.createElement('LI');
    //     var span = document.createElement('SPAN');
    //     var spanDel = document.createElement('SPAN');
    //     var canvas = document.createElement('CANVAS');
    //     var ctx = canvas.getContext('2d');

    //     span.setAttribute('class', 'img');
    //     spanDel.setAttribute('class', 'delete');
    //     spanDel.innerHTML = '<i class="fa fa-times"></i>';

    //     li.appendChild(span);
    //     li.appendChild(spanDel);
    //     li.setAttribute('data-id', file.name);

    //     image.removeEventListener('load', imgLoadHandler, false);

    //     // создаем миниатюру
    //     imgLoadHandler = function() {
    //         ctx.drawImage(image, 0, 0, previewWidth, previewHeight);
    //         URL.revokeObjectURL(image.src);
    //         span.appendChild(canvas);
    //         isProcessing = false;
    //         setTimeout(processQueue, 200); // запускаем процесс создания миниатюры для следующего изображения
    //     };

    //     // Выводим миниатюру в контейнере previewPhotoContainer
    //     previewPhotoContainer.appendChild(li);
    //     listen(image, 'load', imgLoadHandler);
    //     image.src = URL.createObjectURL(file);

    //     // Сохраняем содержимое оригинального файла в base64 в отдельном поле формы
    //     // чтобы при отправке формы файл был передан на сервер
    //     var fr = new FileReader();
    //     fr.readAsDataURL(file);
    //     fr.onload = (function (file) {
    //         return function (e) {
    //             $('#preview-photo').append(
    //                 '<input type="hidden" name="PROP[MORE_PHOTO][]" value="' + e.target.result + '" data-id="' + file.name+ '">'
    //             );
    //         }
    //     }) (file);
    // };

function personalActiveScroll() {
    const wrapper = document.querySelector('.personal-nemu-info_nav-wrapper');
    if(!wrapper) return;

    const activeElement = wrapper.querySelector('.personal-nemu-info_nav li.selected');
    wrapper.scrollLeft = activeElement.offsetLeft - activeElement.clientWidth / 2;
}
personalActiveScroll();
// document.addEventListener("DOMContentLoaded", e => {
   
// })