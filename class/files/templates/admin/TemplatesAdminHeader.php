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
 * @version         $Id: 1.91 header.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesAdminHeader.
 */
class TemplatesAdminHeader extends TDMCreateFile
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
    *  @static function getInstance
    *  @param string $module
    *  @param string $filename
    */
    /**
     * @return TemplatesAdminHeader
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
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
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
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');

        $navigation = $hc->getSmartySingleVar('navigation');
        $due = $hc->getHtmlTag('span', array('class' => 'left'), $navigation).PHP_EOL;
        $buttons = $hc->getSmartySingleVar('buttons');
        $right = $hc->getHtmlTag('span', array('class' => 'left'), $buttons.'&nbsp;');
        $due .= $hc->getSmartyConditions('buttons', '', '', $right).PHP_EOL;
        $content = $hc->getHtmlTag('div', array('class' => 'top'), $due);

        $this->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
