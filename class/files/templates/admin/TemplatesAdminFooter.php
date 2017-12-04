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
 * @version         $Id: 1.91 TemplatesAdminFooter.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesAdminFooter.
 */
class TemplatesAdminFooter extends TDMCreateFile
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
     * @static   function getInstance
     * @return TemplatesAdminFooter
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
    *  @param string $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
    *  @public function render
    *  @param null
     * @return bool|string
     */
    public function render()
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleName = $module->getVar('mod_name');
        $moduleDirname = $module->getVar('mod_dirname');
        $supportName = $module->getVar('mod_support_name');
        $language = $this->getLanguage($moduleDirname, 'AM');

        $singleNoVar = $hc->getSmartyNoSimbol('xoModuleIcons32 xoopsmicrobutton.gif');
        $img = $hc->getHtmlTag('img', ['src' => $singleNoVar, 'alt' => 'XOOPS'], '', false) . PHP_EOL;
        $anchor = $hc->getHtmlTag('a', ['href' => 'https://xoops.org/', 'title' => 'Visit XOOPS', 'target' => '_blank'], $img) . PHP_EOL;
        $content = $hc->getHtmlTag('div', ['class' => 'center'], $anchor) . PHP_EOL;
        $tree = $hc->getHtmlTag('strong', [], $moduleName);
        $tree .= $hc->getSmartyConst($language, 'MAINTAINEDBY').PHP_EOL;
        $tree .= $hc->getHtmlTag('a', ['href' => '<{$maintainedby}>', 'title' => 'Visit ' . $supportName, 'class' => 'tooltip', 'rel' => 'external'], $supportName);
        $content .= $hc->getHtmlTag('div', ['class' => 'center smallsmall italic pad5'], $tree);

        $this->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
