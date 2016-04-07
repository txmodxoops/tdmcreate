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
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();        
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return IncludeCommon
     */
    public static function &getInstance()
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
        $table = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $stuModuleDirname = strtoupper($moduleDirname);
        $moduleAuthor = $module->getVar('mod_author');
        $moduleAuthorWebsiteName = $module->getVar('mod_author_website_name');
        $moduleAuthorWebsiteUrl = $module->getVar('mod_author_website_url');
        $moduleAuthorImage = str_replace(' ', '', strtolower($moduleAuthor));
        $ret = <<<EOT
if (!defined('XOOPS_ICONS32_PATHH')) define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
if (!defined('XOOPS_ICONS32_URL')) define('XOOPS_ICONS32_URL', XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
define('{$stuModuleDirname}_DIRNAME', '{$moduleDirname}');
define('{$stuModuleDirname}_PATH', XOOPS_ROOT_PATH.'/modules/'.{$stuModuleDirname}_DIRNAME);
define('{$stuModuleDirname}_URL', XOOPS_URL.'/modules/'.{$stuModuleDirname}_DIRNAME);
define('{$stuModuleDirname}_ICONS_PATH', {$stuModuleDirname}_PATH.'/assets/icons');
define('{$stuModuleDirname}_ICONS_URL', {$stuModuleDirname}_URL.'/assets/icons');
define('{$stuModuleDirname}_IMAGE_PATH', {$stuModuleDirname}_PATH.'/assets/images');
define('{$stuModuleDirname}_IMAGE_URL', {$stuModuleDirname}_URL.'/assets/images');
define('{$stuModuleDirname}_UPLOAD_PATH', XOOPS_UPLOAD_PATH.'/'.{$stuModuleDirname}_DIRNAME);
define('{$stuModuleDirname}_UPLOAD_URL', XOOPS_UPLOAD_URL.'/'.{$stuModuleDirname}_DIRNAME);\n
EOT;
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldElement = array();
        foreach (array_keys($fields) as $f) {
            $fieldElement[] = $fields[$f]->getVar('field_element');
        }
        if (in_array(10, $fieldElement)) {
            $ret .= <<<EOT
define('{$stuModuleDirname}_UPLOAD_SHOTS_PATH', {$stuModuleDirname}_UPLOAD_PATH.'/images/shots');
define('{$stuModuleDirname}_UPLOAD_SHOTS_URL', {$stuModuleDirname}_UPLOAD_PATH.'/images/shots');\n
EOT;
        }
        if (in_array(array(11, 12), $fieldElement)) {
            $ret .= <<<EOT
define('{$stuModuleDirname}_UPLOAD_FILES_PATH', {$stuModuleDirname}_UPLOAD_PATH.'/files');
define('{$stuModuleDirname}_UPLOAD_FILES_URL', {$stuModuleDirname}_UPLOAD_PATH.'/files');\n
EOT;
        }
        if (in_array(13, $fieldElement)) {
            $ret .= <<<EOT
define('{$stuModuleDirname}_UPLOAD_IMAGE_PATH', {$stuModuleDirname}_UPLOAD_PATH.'/images');
define('{$stuModuleDirname}_UPLOAD_IMAGE_URL', {$stuModuleDirname}_UPLOAD_PATH.'/images');\n
EOT;
        }
        $ret .= <<<EOT
define('{$stuModuleDirname}_ADMIN', {$stuModuleDirname}_URL . '/admin/index.php');
\$local_logo = {$stuModuleDirname}_IMAGE_URL . '/{$moduleAuthorImage}_logo.gif';

// module information
\$copyright = "<a href='{$moduleAuthorWebsiteUrl}' title='{$moduleAuthorWebsiteName}' target='_blank'>
                     <img src='".\$local_logo."' alt='{$moduleAuthorWebsiteName}' /></a>";

include_once XOOPS_ROOT_PATH.'/class/xoopsrequest.php';
include_once {$stuModuleDirname}_PATH.'/class/helper.php';
include_once {$stuModuleDirname}_PATH.'/include/functions.php';
EOT;

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
