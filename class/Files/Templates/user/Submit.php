<?php

namespace XoopsModules\Tdmcreate\Files\Templates\User;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;

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
 */

/**
 * class Submit.
 */
class Submit extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     * @param null
     * @return Submit
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
     * @public function write
     * @param string $module
     * @param string $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplatesUserSubmitHeader
     * @param $moduleDirname
     * @return string
     */
    private function getTemplatesUserSubmitHeader($moduleDirname)
    {
        $hc = Tdmcreate\Files\CreateHtmlSmartyCodes::getInstance();

        return $hc->getSmartyIncludeFile($moduleDirname, 'header') . PHP_EOL;
    }

    /**
     * @private function getTemplatesUserSubmit
     * @param string $moduleDirname
     * @param string $language
     *
     * @return string
     */
    private function getTemplatesUserSubmit($moduleDirname, $language)
    {
        $hc    = Tdmcreate\Files\CreateHtmlSmartyCodes::getInstance();
        $const = $hc->getSmartyConst($language, 'SUBMIT_SUBMITONCE');
        $li    = $hc->getHtmlLi($const) . PHP_EOL;
        $const = $hc->getSmartyConst($language, 'SUBMIT_ALLPENDING');
        $li    .= $hc->getHtmlLi($const) . PHP_EOL;
        $const = $hc->getSmartyConst($language, 'SUBMIT_DONTABUSE');
        $li    .= $hc->getHtmlLi($const) . PHP_EOL;
        $const = $hc->getSmartyConst($language, 'SUBMIT_TAKEDAYS');
        $li    .= $hc->getHtmlLi($const) . PHP_EOL;
        $ul    = $hc->getHtmlUl($li) . PHP_EOL;
        $ret   = $hc->getHtmlDiv($ul, $moduleDirname . '-tips') . PHP_EOL;

        $single   = $hc->getSmartySingleVar('message_error') . PHP_EOL;
        $divError = $hc->getHtmlDiv($single, 'errorMsg') . PHP_EOL;
        $ret      .= $hc->getSmartyConditions('message_error', ' != ', '\'\'', $divError) . PHP_EOL;
        $single   = $hc->getSmartySingleVar('form') . PHP_EOL;
        $ret      .= $hc->getHtmlDiv($single, $moduleDirname . '-submitform') . PHP_EOL;

        return $ret;
    }

    /**
     * @private function getTemplatesUserSubmitFooter
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserSubmitFooter($moduleDirname)
    {
        $hc = Tdmcreate\Files\CreateHtmlSmartyCodes::getInstance();

        return $hc->getSmartyIncludeFile($moduleDirname, 'footer');
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module         = $this->getModule();
        $table          = $this->getTable();
        $filename       = $this->getFileName();
        $moduleDirname  = $module->getVar('mod_dirname');
        $tableFieldname = $table->getVar('table_fieldname');
        $language       = $this->getLanguage($moduleDirname, 'MA');
        $content        = $this->getTemplatesUserSubmitHeader($moduleDirname);
        $content        .= $this->getTemplatesUserSubmit($moduleDirname, $language);
        $content        .= $this->getTemplatesUserSubmitFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
