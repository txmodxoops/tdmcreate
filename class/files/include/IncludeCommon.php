<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: IncludeCommon.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class IncludeCommon.
 */
class IncludeCommon extends TDMCreateFile
{
    /*
    *  @public function constructor
    *  @param null
    */

    public function __construct()
    {
        parent::__construct();
    }

    /*
    *  @static function getInstance
    *  @param null
    */
    /**
     * @return IncludeCommon
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /*
    *  @public function write
    *  @param string $module
    *  @param object $table
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @param $modDirname
     * @param $const
     * @param $desc
     */
    private function getCommonDefines($modDirname, $const, $desc)
    {
        $stuModDirname = strtoupper($modDirname);

        return "define('{$stuModDirname}_{$const}', {$desc});\n";
    }

    /*
    *  @private function getCommonCode
    *  @param object $module
    */
    /**
     * @param $module
     *
     * @return string
     */
    private function getCommonCode($module)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $table = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $stuModuleDirname = strtoupper($moduleDirname);
        $moduleAuthor = $module->getVar('mod_author');
        $moduleAuthorWebsiteName = $module->getVar('mod_author_website_name');
        $moduleAuthorWebsiteUrl = $module->getVar('mod_author_website_url');
        $moduleAuthorImage = str_replace(' ', '', strtolower($moduleAuthor));
        $ret = <<<'EOT'
if (!defined('XOOPS_ICONS32_PATH')) define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
if (!defined('XOOPS_ICONS32_URL')) define('XOOPS_ICONS32_URL', XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
EOT;
        $ret .= self::getCommonDefines($moduleDirname, 'DIRNAME', "'{$moduleDirname}'");
        $ret .= self::getCommonDefines($moduleDirname, 'PATH', "XOOPS_ROOT_PATH.'/modules/'.{$stuModuleDirname}_DIRNAME");
        $ret .= self::getCommonDefines($moduleDirname, 'URL', "XOOPS_URL.'/modules/'.{$stuModuleDirname}_DIRNAME");
        $ret .= self::getCommonDefines($moduleDirname, 'ICONS_PATH', "{$stuModuleDirname}_PATH.'/assets/icons'");
        $ret .= self::getCommonDefines($moduleDirname, 'ICONS_URL', "{$stuModuleDirname}_URL.'/assets/icons'");
        $ret .= self::getCommonDefines($moduleDirname, 'IMAGE_PATH', "{$stuModuleDirname}_PATH.'/assets/images'");
        $ret .= self::getCommonDefines($moduleDirname, 'IMAGE_URL', "{$stuModuleDirname}_URL.'/assets/images'");
        $ret .= self::getCommonDefines($moduleDirname, 'UPLOAD_PATH', "XOOPS_UPLOAD_PATH.'/'.{$stuModuleDirname}_DIRNAME");
        $ret .= self::getCommonDefines($moduleDirname, 'UPLOAD_URL', "XOOPS_UPLOAD_URL.'/'.{$stuModuleDirname}_DIRNAME");

        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldElement = array();
        foreach (array_keys($fields) as $f) {
            $fieldElement[] = $fields[$f]->getVar('field_element');
        }
        $ret .= self::getCommonDefines($moduleDirname, 'UPLOAD_FILES_PATH', "{$stuModuleDirname}_UPLOAD_PATH.'/files'");
        $ret .= self::getCommonDefines($moduleDirname, 'UPLOAD_FILES_URL', "{$stuModuleDirname}_UPLOAD_URL.'/files'");
        $ret .= self::getCommonDefines($moduleDirname, 'UPLOAD_IMAGE_PATH', "{$stuModuleDirname}_UPLOAD_PATH.'/images'");
        $ret .= self::getCommonDefines($moduleDirname, 'UPLOAD_IMAGE_URL', "{$stuModuleDirname}_UPLOAD_URL.'/images'");
        $ret .= self::getCommonDefines($moduleDirname, 'UPLOAD_SHOTS_PATH', "{$stuModuleDirname}_UPLOAD_PATH.'/images/shots'");
        $ret .= self::getCommonDefines($moduleDirname, 'UPLOAD_SHOTS_URL', "{$stuModuleDirname}_UPLOAD_URL.'/images/shots'");
        $ret .= self::getCommonDefines($moduleDirname, 'ADMIN', "{$stuModuleDirname}_URL . '/admin/index.php'");
        $ret .= $xc->getXcEqualsOperator('$localLogo', "{$stuModuleDirname}_IMAGE_URL . '/{$moduleAuthorImage}_logo.png'");
        $ret .= $pc->getPhpCodeCommentLine('Module Information');
        $htmlCode = TDMCreateHtmlCode::getInstance();
        $img = $htmlCode->getHtmlImage('".$localLogo."', $moduleAuthorWebsiteName);
        $anchor = $htmlCode->getHtmlAnchor($moduleAuthorWebsiteUrl, $img, $moduleAuthorWebsiteName, '_blank');
        $replace = $xc->getXcEqualsOperator('$copyright', '"'.$anchor.'"');
        $ret .= str_replace("\n", '', $replace).PHP_EOL;
        $ret .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsrequest', true);
        $ret .= $pc->getPhpCodeIncludeDir("{$stuModuleDirname}_PATH", 'class/helper', true);
        $ret .= $pc->getPhpCodeIncludeDir("{$stuModuleDirname}_PATH", 'include/functions', true);

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $moduleDirname = $module->getVar('mod_dirname');
        $filename = $this->getFileName();
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getCommonCode($module);
        $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
