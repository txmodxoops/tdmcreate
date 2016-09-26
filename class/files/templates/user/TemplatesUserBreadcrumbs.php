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
 * @version         $Id: TemplatesUserBreadcrumbs.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesUserBreadcrumbs.
 */
class TemplatesUserBreadcrumbs extends TDMCreateFile
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
     * @return TemplatesUserBreadcrumbs
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
    *  @param mixed $tables
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $tables
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
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tf = TDMCreateFile::getInstance();
        $sc = TDMCreateSmartyCode::getInstance();
        $hc = TDMCreateHtmlCode::getInstance();
        $t = "\t";
        $title = $sc->getSmartyDoubleVar('itm', 'title');
        $titleElse = $sc->getSmartyDoubleVar('itm', 'title', $t."\t").PHP_EOL;
        $link = $sc->getSmartyDoubleVar('itm', 'link');
        $glyph = $hc->getHtmlTag('i', array('class' => 'glyphicon glyphicon-home'), '', false, true);
        $anchor = $hc->getHtmlAnchor('<{xoAppUrl index.php}>', $glyph, 'home');
        $into = $hc->getHtmlTag('li', array('class' => 'bc-item'), $anchor, false, true, $t).PHP_EOL;
        $anchorIf = $hc->getHtmlAnchor($link, $title, $title, '', '', '', $t."\t").PHP_EOL;
        $breadcrumb = $sc->getSmartyConditions('itm.link', '', '', $anchorIf, $titleElse, false, false, $t);
        $foreach = $hc->getHtmlTag('li', array('class' => 'bc-item'), $breadcrumb, false, false, $t);
        $into .= $sc->getSmartyForeach('itm', 'xoBreadcrumbs', $foreach, 'bcloop', '', $t);

        $content = $hc->getHtmlTag('ol', array('class' => 'breadcrumb'), $into);

        $tf->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $tf->renderFile();
    }
}
