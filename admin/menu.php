<?php
if (!Bitrix\Main\Loader::includeModule('ittower.auto'))
{
    $message = Loc::getMessage('ITTOWER_AUTO_NEED_MODULE_INSTALLED');
    throw new Main\SystemException($message);
}
use Ittower\Auto\Config; //
use Ittower\Auto\Iblock\Iblock;



$iblockID = Config::getOption(Iblock::$iblockIdVarName);

$aMenu = array(
'parent_menu' => 'global_menu_content',
'sort' => 150,
'text' => GetMessage('ITTOWER_AUTO_MENU_HEADER_TEXT'),
'title' => GetMessage('ITTOWER_AUTO_HEADER_TITLE'),
'icon' => 'sale_menu_icon_statisti',
'page_icon' => 'sale_menu_icon_statistic',
'items_id' => 'auto',
'items' => array(
array(
'text' => GetMessage('ITTOWER_AUTO_MENU_TEXT'),
'title' => GetMessage('ITTOWER_AUTO_MENU_TITLE'),
'url' => '/bitrix/admin/autocatalog_list.php?IBLOCK_ID='.$iblockID.'&type=ittowercalculator&lang='.LANGUAGE_ID.'&find_el_y=Y&&apply_filter=Y'
),
)
);

return (!empty($aMenu) ? $aMenu : false);