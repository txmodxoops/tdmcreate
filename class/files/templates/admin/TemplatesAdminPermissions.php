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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: pages.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesAdminPermissions.
 */
class TemplatesAdminPermissions extends TDMCreateFile
{
    /**
    *  @public function constructor
    *  @param null
    */

    public function __construct()
    {
        parent::__construct();
    }

    /**
    *  @static function getInstance
    *  @param null
     * @return TemplatesAdminPermissions
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
    *  @public function write
    *  @param string $module
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
    *  @private function getTemplatesAdminPermissionsHeader
    *  @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesAdminPermissionsHeader($moduleDirname)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();

        return $hc->getSmartyIncludeFile($moduleDirname, 'header', true).PHP_EOL;
    }

    /**
    *  @private function getTemplatesAdminPermissions
    *  @param null
     * @return string
     */
    private function getTemplatesAdminPermissions()
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $form = $hc->getSmartySingleVar('form');
        $ret = $hc->getHtmlTag('div', array('class' => 'spacer'), $form).PHP_EOL;
        $ret .= $hc->getHtmlTag('br', array(), '', false).PHP_EOL;

        return $ret;
    }

    /**
    *  @private function getTemplatesAdminPermissionsFooter
    *  @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesAdminPermissionsFooter($moduleDirname)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();

        return $hc->getSmartyIncludeFile($moduleDirname, 'footer', true);
    }

    /**
    *  @public function render
    *  @param null
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'AM');
        $content = $this->getTemplatesAdminPermissionsHeader($moduleDirname);
        $content .= $this->getTemplatesAdminPermissions();
        $content .= $this->getTemplatesAdminPermissionsFooter($moduleDirname);

        $this->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
