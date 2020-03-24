<?php

namespace XoopsModules\Tdmcreate\Files\Language;

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
 * Class LanguageBlocks.
 */
class LanguageBlocks extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->defines = LanguageDefines::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return LanguageBlocks
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
     * @param mixed  $tables
     * @param string $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
        $this->setTables($tables);
    }

    /**
     * @private function getLanguageBlock
     * @param string $language
     * @param string $module
     *
     * @return string
     */
    private function getLanguageBlock($module, $language)
    {
        $tables = $this->getTables();
        $ret    = $this->defines->getAboveDefines('Admin Edit');
        $ret    .= $this->defines->getDefine($language, 'DISPLAY', 'How Many Tables to Display');
        $ret    .= $this->defines->getDefine($language, 'TITLE_LENGTH', 'Title Length');
        $ret    .= $this->defines->getDefine($language, 'CATTODISPLAY', 'Categories to Display');
        $ret    .= $this->defines->getDefine($language, 'ALLCAT', 'All Categories');
        foreach (array_keys($tables) as $t) {
            $tableName        = $tables[$t]->getVar('table_name');
            $ucfTableName     = ucfirst($tableName);
            $ret             .= $this->defines->getAboveDefines($ucfTableName);
            $fields           = $this->getTableFields($tables[$t]->getVar('table_mid'), $tables[$t]->getVar('table_id'));
            $stuTableName = mb_strtoupper($tableName);
            $ret              .= $this->defines->getDefine($language, $stuTableName . '_TO_DISPLAY', $ucfTableName . ' to Display');
            $ret              .= $this->defines->getDefine($language, 'ALL_' . $stuTableName, 'All ' . $ucfTableName);
            foreach (array_keys($fields) as $f) {
                $fieldName    = $fields[$f]->getVar('field_name');
                $stuFieldName = mb_strtoupper($fieldName);

                $rpFieldName = $this->getRightString($fieldName);
                $lpFieldName = mb_substr($fieldName, 0, mb_strpos($fieldName, '_'));

                $fieldNameDesc = ucfirst($rpFieldName);

                $ret .= $this->defines->getDefine($language, $stuFieldName, $fieldNameDesc);
            }
        }

        return $ret;
    }

    /**
     * @private function getFooter
     * @param null
     * @return string
     */
    private function getLanguageFooter()
    {
        $ret = $this->defines->getBelowDefines('End');
        $ret .= $this->defines->getBlankLine();

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MB');
        $content       = $this->getHeaderFilesComments($module, $filename);
        $content       .= $this->getLanguageBlock($module, $language);
        $content       .= $this->getLanguageFooter();

        $this->create($moduleDirname, 'language/' . $GLOBALS['xoopsConfig']['language'], $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
