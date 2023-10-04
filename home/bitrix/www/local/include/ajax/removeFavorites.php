<?
if (isset($_POST["AJAX"]) && $_POST["AJAX"] == "Y"){
    //запускаем сессию
    session_start();

    #удалить из корзины по id
    if(isset($_POST["DELETED_LIKED_ID"]) && $_POST["DELETED_LIKED_ID"] == "Y" && isset($_POST["PRODUCT_ID"]) ){
        $product_id = htmlspecialchars($_POST["PRODUCT_ID"]);
        unset($_SESSION["LIKED_PRODUCTS"][$product_id]);
    }
    #удалить всё из корзины
    else if(isset($_POST["DELETED_LIKED"])){
        unset($_SESSION["LIKED_PRODUCTS"]);
    }

    echo count( $_SESSION["LIKED_PRODUCTS"] );
}
?>