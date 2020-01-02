<?php namespace XoopsModules\Tdmcreate\Files\Language;

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
 * @version         $Id: LanguageMain.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class LanguageMain.
 */
class LanguageMain extends Files\CreateFile
{
    /**
     *  @public function constructor
     *  @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->defines = LanguageDefines::getInstance();
    }

    /**
     *  @static function getInstance
     *  @param null
     * @return LanguageMain
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
     *  @param mixed $tables
     *  @param string $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
        $this->setTables($tables);
    }

    /**
     *  @private function geLanguagetMain
     *  @param string $module
     *  @param string $language
     *
     * @return string
     */
    private function geLanguagetMain($module, $language)
    {
        /** @var \XoopsModules\Tdmcreate\Utility $utility */
        $utility = new \XoopsModules\Tdmcreate\Utility();

        $moduleName = $module->getVar('mod_name');
        $tables = $this->getTables();
        $ret = $this->defines->getAboveHeadDefines('Main');
        $ret .= $this->defines->getDefine($language, 'INDEX', 'Home');
        $ret .= $this->defines->getDefine($language, 'TITLE', (string)$module->getVar('mod_name'));
        $ret .= $this->defines->getDefine($language, 'DESC', (string)$module->getVar('mod_description'));
        $ret .= $this->defines->getDefine($language, 'INDEX_DESC', "Welcome to the homepage of your new module {$moduleName}!<br>
As you can see, you have created a page with a list of links at the top to navigate between the pages of your module. This description is only visible on the homepage of this module, the other pages you will see the content you created when you built this module with the module TDMCreate, and after creating new content in admin of this module. In order to expand this module with other resources, just add the code you need to extend the functionality of the same. The files are grouped by type, from the header to the footer to see how divided the source code.<br><br>If you see this message, it is because you have not created content for this module. Once you have created any type of content, you will not see this message.<br><br>If you liked the module TDMCreate and thanks to the long process for giving the opportunity to the new module to be created in a moment, consider making a donation to keep the module TDMCreate and make a donation using this button <a href='http://www.txmodxoops.org/modules/xdonations/index.php' title='Donation To Txmod Xoops'><img src='https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif' alt='Button Donations' /></a><br>Thanks!<br><br>Use the link below to go to the admin and create content.", true);
        $ret .= $this->defines->getDefine($language, 'NO_PDF_LIBRARY', 'Libraries TCPDF not there yet, upload them in root/Frameworks');
        $ret .= $this->defines->getDefine($language, 'NO', 'No');
        $ret .= $this->defines->getAboveHeadDefines('Contents');
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $tableSoleName = $tables[$i]->getVar('table_solename');
            $stuTableName = mb_strtoupper($tableName);
            $stuTableSoleName = mb_strtoupper($tableSoleName);
            $ucfTableName = $utility::UcFirstAndToLower($tableName);
            $ucfTableSoleName = $utility::UcFirstAndToLower($tableSoleName);
            $ret .= $this->defines->getAboveDefines($ucfTableSoleName);
            $ret .= $this->defines->getDefine($language, $stuTableSoleName, $ucfTableSoleName);
            $ret .= $this->defines->getDefine($language, $stuTableName, $ucfTableName);
            $ret .= $this->defines->getDefine($language, "{$stuTableName}_TITLE", "{$ucfTableName} title");
            $ret .= $this->defines->getDefine($language, "{$stuTableName}_DESC", "{$ucfTableName} description");
            $ret .= $this->defines->getAboveDefines("Caption of {$ucfTableSoleName}");
            $fields = $this->getTableFields($tables[$i]->getVar('table_mid'), $tables[$i]->getVar('table_id'));
            foreach (array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                $rpFieldName = $this->getRightString($fieldName);
                $fieldNameDesc = ucfirst($rpFieldName);
                $ret .= $this->defines->getDefine($language, $stuTableSoleName . '_' . $rpFieldName, $fieldNameDesc);
            }
        }
        $ret .= $this->defines->getDefine($language, 'INDEX_THEREARE', "There are %s {$ucfTableName}");
        $ret .= $this->defines->getDefine($language, 'INDEX_LATEST_LIST', "Last {$module->getVar('mod_name')}");
        $ret .= $this->defines->getAboveDefines('Submit');
        $ret .= $this->defines->getDefine($language, 'SUBMIT', 'Submit');
        $ret .= $this->defines->getDefine($language, "SUBMIT_{$stuTableSoleName}", "Submit {$ucfTableSoleName}");
        $ret .= $this->defines->getDefine($language, 'SUBMIT_ALLPENDING', "All {$tableSoleName}/script information are posted pending verification.");
        $ret .= $this->defines->getDefine($language, 'SUBMIT_DONTABUSE', 'Username and IP are recorded, so please do not abuse the system.');
        $ret .= $this->defines->getDefine($language, 'SUBMIT_ISAPPROVED', "Your {$tableSoleName} has been approved");
        $ret .= $this->defines->getDefine($language, 'SUBMIT_PROPOSER', "Submit a {$tableSoleName}");
        $ret .= $this->defines->getDefine($language, 'SUBMIT_RECEIVED', "We have received your {$tableSoleName} info. Thank you !");
        $ret .= $this->defines->getDefine($language, 'SUBMIT_SUBMITONCE', "Submit your {$tableSoleName}/script only once.");
        $ret .= $this->defines->getDefine($language, 'SUBMIT_TAKEDAYS', "This will take many days to see your {$tableSoleName}/script added successfully in our database.");

        return $ret;
    }

    /**
     *  @private function geLanguagetMainFooter
     *  @param string $language
     *
     * @return string
     */
    private function geLanguagetMainFooter($language)
    {
        $ret = $this->defines->getAboveDefines('Admin link');
        $ret .= $this->defines->getDefine($language, 'ADMIN', 'Admin');
        $ret .= $this->defines->getBelowDefines('End');

        return $ret;
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
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->geLanguagetMain($module, $language);
        $content .= $this->geLanguagetMainFooter($language);

        $this->create($moduleDirname, 'language/' . $GLOBALS['xoopsConfig']['language'], $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
