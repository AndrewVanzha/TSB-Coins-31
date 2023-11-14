// ---------------------------  //
// ДОБАВЛЕНИЕ ТОВАРА В КОРЗИНУ //
// -------------------------- //
if(typeof addToCartProduct != 'function') {
    function addToCartProduct(element_id, quant) {
        $.ajax({
            url: "/katalog/inostrannye/?action=ADD2BASKET&id="+element_id+"&quantity="+quant+"&ajax_basket=Y",
            success: function(json){
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: "/local/include/ajax/addToCartFancybox.php",
                    dataType: "html",
                    data: {
                        product_id : element_id,
                        quant : quant
                    },
                    success: function(html){
                        //Обновляем малую корзину
                        BX.onCustomEvent('OnBasketChange');
                        //Всплывающее окно
                        $.fancybox.open(html);
                    }
                });
            }
        });
        return false;
    }
}

// ---------------------------  //
// ИЗМЕНЕНИЕ КОЛ-ВА ТОВАРА     //
// -------------------------- //
if(typeof validCountVal != 'function') {
    function validCountVal(val){
        if ( (val <= 0) || ( isNaN(val) ) ) {
            return 1;
        } else if( (val > 999) ){
            return 999;
        } else {
            return val;
        };
    }
}

if(typeof changeCountProduct != 'function') {
    function changeCountProduct(el, arJSParams) {
        var count_val_old = $(el).val();
        count_val = validCountVal(count_val_old);
        $("#cart_"+arJSParams['ID']).attr("onclick","addToCartProduct("+arJSParams['ID']+","+count_val+");");
        $("#cartMobile_"+arJSParams['ID']).attr("onclick","addToCartProduct("+arJSParams['ID']+","+count_val+");");

        if (count_val_old != count_val) {
            $(el).val(count_val);
        }

        return false;
    }
}

if(typeof countMinusProduct != 'function') {
    function countMinusProduct(el, arJSParams) {
        var currentInput = $(el).next();
        var currentValue = parseInt( $(el).next().val() );

        $(el).next().val( validCountVal(currentValue) );
        if ( $(el).next().val() == 1 ) return false;
        $(el).next().val( +$(el).next().val() - 1 );

        changeCountProduct(currentInput,arJSParams);

        return false;
    }
}

if(typeof countPlusProduct != 'function') {
    function countPlusProduct(el, arJSParams) {
        var currentInput = $(el).prev();
        var currentValue = parseInt( $(el).prev().val() );

        $(el).prev().val( validCountVal(currentValue) );
        if ( $(el).prev().val() == 999 ) return false;
        $(el).prev().val( +$(el).prev().val() + 1 );

        changeCountProduct(currentInput,arJSParams);

        return false;
    }
}

// ---------------------------  //
// ФОРМА УЗНАТЬ ЦЕНУ           //
// -------------------------- //
if(typeof findPopupSubscription != 'function') {
    function findPopupSubscription(element_id) {
        $('#popup-subscription').find('.message_send').remove();
        $('#popup-subscription').find('input[name="PROP[PRODUCT_ID]"]').remove();
        $('#popup-subscription').find('.submit').before('<input type="hidden" name="PROP[PRODUCT_ID]" value="'+element_id+'">');


        $.fancybox.open({
            src  : '#popup-subscription',
            type : 'inline',
            opts : {
                onComplete : function() {

                }
            }
        });

    }
}
