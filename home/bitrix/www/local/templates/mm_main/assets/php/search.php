<?php
header('Content-Type: application/json');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("search");
CModule::IncludeModule('iblock');

function getData($currQuery) {
  
  $obSearch = new CSearch;
  $obSearch->SetOptions(array(
    'ERROR_ON_EMPTY_STEM' => false,
  ));
  $searchParams = [
    'QUERY' => $currQuery,
    'SITE_ID' => SITE_ID,
    'MODULE_ID' => 'iblock',
    'PARAM2' => 6
  ];
  $obSearch->Search($searchParams, [], ["LIMIT" => 3]);
  $resultIDs = [];
  while ($row = $obSearch->fetch()) {  
    $resultIDs[$row['ITEM_ID']] = $row['ITEM_ID'];
  }
  $obSearch->Search($searchParams, array(), array('STEMMING' => false, "LIMIT" => 3));
  while ($row = $obSearch->fetch()) {  
    $resultIDs[$row['ITEM_ID']] = $row['ITEM_ID'];
  }
  if (count($resultIDs) < 1) return false;

  $rsElem = CIBlockElement::GetList(
    array(
      'id' => $resultIDs
    ),
    array(
      'SECTION_GLOBAL_ACTIVE' => 'Y',
      'IBLOCK_ID' => 6,
      'ID' => $resultIDs,
    ),
    false,
    false,
    array(
      'NAME',
      'IBLOCK_ID',
      'ID',
      'DETAIL_PAGE_URL',
      'PREVIEW_PICTURE',
      'CATALOG_GROUP_1',
    )
  );
  $result = [];
  while ($elem = $rsElem->GetNext()) {
    $result[] = $elem;
    if(count($result) === 3) break;
  }
  return $result;
}

// выплевывать результат
if ($data = file_get_contents('php://input')) {
if ($query = (json_decode($data, true)['query'])) {
  if ($result = getData($query)): ?>
    <div class="search-results">
    <?foreach ($result as $arElement):?>
      <?
      $price = (float)$arElement['CATALOG_PRICE_1'];
      $showPrice = $price > 0;
      $price = number_format($price, 0, '.', ' ');
      ?>
      <a 
      href="<?=$arElement['DETAIL_PAGE_URL']?>"
      class="search-results-element">
        <img 
        class="search-results-img"
        src="<?=CFile::GetPath($arElement['PREVIEW_PICTURE']);?>" 
        alt="<?=$arElement['NAME']?>">
        <div class="search-results-info">
          <div class="search-results-name"><?=$arElement['NAME']?></div>
          <?if ($showPrice):?>
            <div class="search-results-price"><?=$price;?>&nbsp;₽</div>
          <?endif;?>
        </div>
      </a>
    <?endforeach?>
    </div>
    <div class="search-footer">
      <button class="mint-btn blue" type='submit'>Все результаты</button>
    </div>
  <?else:?>
    <div class="no_results">
      <div class="no_results-header">
        К сожалению, по вашему запросу ничего не нашлось
      </div>
      <div class="search-footer">
        <button class="mint-btn blue" type='submit'>Все результаты</button>
      </div>
    </div>
  <?endif;
}}
