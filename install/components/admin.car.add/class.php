<?php
namespace Ittower\Auto\Components;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

if (!Main\Loader::includeModule('ittower.auto'))
{
    $message = Loc::getMessage('ITTOWER_AUTO_NEED_MODULE_INSTALLED');
    throw new Main\SystemException($message);
}


if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class AdminCarAdd extends \CBitrixComponent
{
    

    public function executeComponent()
    {
       
        $this->includeComponentTemplate();
    }
}