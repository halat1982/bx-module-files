<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;  
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Config as Conf;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Entity\Base;

 
Class Ittower_auto extends CModule
{
    private $exclusionAdminFiles;
    

    function __construct()
    {
        $this->exclusionAdminFiles=array(
            '..',
            '.',
            'menu.php',
        );

    	$arModuleVersion = array();
        include(__DIR__."/version.php");

        $this->MODULE_ID = "ittower.auto";
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_DESCRIPTION = Loc::getMessage("ITTOWER_AUTO_MODULE_DESC");
        $this->MODULE_NAME = Loc::getMessage("ITTOWER_AUTO_MODULE_NAME");

        $this->PARTNER_NAME = Loc::getMessage("ITTOWER_AUTO_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("ITTOWER_AUTO_PARTNER_URI");

        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS='Y'; // ?
        $this->MODULE_GROUP_RIGHTS = "Y"; // ?
    }

    //defining path to module
    public function GetPath($notDocumentRoot=false)
    {
        if($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(),'',dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    //checking if d7 supporting
    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
    }

    function InstallDB()
    {
        
    }

    



    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);        

        Option::delete($this->MODULE_ID);
    }
    
 
    function DoInstall()
    {
    	global $APPLICATION;
        if($this->isVersionD7())
        {
            ModuleManager::registerModule($this->MODULE_ID);
	        $this->InstallFiles();
            //$this->InstallDB();           
	                   
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("ITTOWER_AUTO_INSTALL_ERROR_VERSION"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("ITTOWER_AUTO_INSTALL_TITLE"), $this->GetPath()."/install/step.php");
    }

    

    function DoUninstall()
    {
        //$this->UnInstallDB();
        $this->UnInstallFiles();       
        UnRegisterModule("ittower.auto");
    }



 
    function InstallFiles()
    {
        $pathToComponents=$this->GetPath()."/install/components";

        if(\Bitrix\Main\IO\Directory::isDirectoryExists($pathToComponents))
            CopyDirFiles($pathToComponents, $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/ittower", true, true);
        else
            throw new \Bitrix\Main\IO\InvalidPathException($pathToComponents);
        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin'))
        {
            CopyDirFiles($this->GetPath() . "/install/admin/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/"); //if files exists to copy
            if ($dir = opendir($path))
            {
                while (false !== $item = readdir($dir))
                {
                    if (in_array($item,$this->exclusionAdminFiles))
                        continue;
                    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$item,
                        '<'.'? require($_SERVER["DOCUMENT_ROOT"]."'.$this->GetPath(true).'/admin/'.$item.'");?'.'>');
                }
                closedir($dir);
            }
        }

        return true;
    }
 
    function UnInstallFiles()
    {
        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin')) {
            DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . '/install/admin/', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin');
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles))
                        continue;
                    \Bitrix\Main\IO\File::deleteFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/'. $item);
                }
                closedir($dir);
            }
        }

        return true;
    }

} 
