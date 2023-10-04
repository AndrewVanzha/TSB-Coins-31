<?
global $USER;

$user = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $USER->GetID()), array("SELECT" => array("UF_DOCUMENTS")));
$user = $user->GetNext();

$hasNoDocuments = false;

if (is_null($user["UF_DOCUMENTS"]) || (count($user["UF_DOCUMENTS"]) == 0)) {
    $hasNoDocuments = true;
}

if ($USER->IsAuthorized() && is_null($_SESSION['docPlank']) && $hasNoDocuments) { ?>
    <div class="doc-plank">
        Вы не сможете оформить заказ, не заполнив <a href="/personal/profile/">профиль</a> и не прикрепив необходимые документы
        <img src="/assets/images/icons/13.png">
    </div>
<? }