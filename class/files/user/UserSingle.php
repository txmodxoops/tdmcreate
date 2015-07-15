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
 * @version         $Id: UserSingle.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class UserSingle.
 */
class UserSingle extends TDMCreateFile
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
        $this->tdmcfile = TDMCreateFile::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserSingle
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
    *  @param mixed $table
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
    *  @public function getUserSingleHeader
    *  @param null
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    public function getUserSingleHeader($moduleDirname)
    {
        return $this->phpcode->getPhpCodeIncludeDir('__DIR__', 'header');
    }

    /*
    *  @public function getUserSingleTop
    *  @param string $tableName
    *  @param string $language
    */
    /**
     * @param $module
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserSingleTop($module, $tableName, $fields, $language)
    {
        $stuModuleName = strtoupper($module->getVar('mod_name'));
		$fieldId = (string) $this->phpcode->getPhpCodeGetFieldId($fields);
		$fieldPid = (string) $this->phpcode->getPhpCodeGetFieldParentId($fields);
        $ccFieldId = (string) $this->tdmcfile->getCamelCase($fieldId, false, true);
		$ccFieldPid = (string) $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ret = $this->phpcode->getPhpCodeXoopsRequest($ccFieldId, $fieldId, '', 'Int');
		$ret .= $this->phpcode->getPhpCodeXoopsRequest($ccFieldPid, $fieldPid, '', 'Int');

        return $ret;
    }

    /*
    *  @public function getUserSingleMiddle
    *  @param string $moduleDirname
    *  @param string $tableName
    */
    /**
     * @param $moduleDirname
     * @param $table_id
     * @param $tableName
     *
     * @return string
     */
    public function getUserSingleMiddle($moduleDirname, $tableName, $fields, $language)
    {
        $fieldId = (string) $this->phpcode->getPhpCodeGetFieldId($fields);
        $ret = <<<EOT
    case 'save':
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
           redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset(\$_REQUEST['{$fieldId}'])) {
           \${$tableName}Obj =& \${$tableName}Handler->get(\$_REQUEST['{$fieldId}']);
        } else {
           \${$tableName}Obj =& \${$tableName}Handler->create();
        }
EOT;
        $ret .= $this->phpcode->getPhpCodeUserSaveElements($moduleDirname, $tableName, $fields);
        $ret .= <<<EOT
        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
            redirect_header('index.php', 2, {$language}FORMOK);
        }

        echo \${$tableName}Obj->getHtmlErrors();
        \$form =& \${$tableName}Obj->getForm();
        \$form->display();
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getUserSingleFooter
    *  @param null
    */
    /**
     * @return string
     */
    public function getUserSingleFooter()
    {
        return $this->phpcode->getPhpCodeIncludeDir('__DIR__', 'footer');
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
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $tableName = $table->getVar('table_name');
		$tableSingle = $table->getVar('table_name');
        $fields = $this->tdmcfile->getTableFields($tableMid, $tableId);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserSingleHeader($moduleDirname);
		if(1 == $tableSingle) {
			$content .= $this->getUserSingleTop($module, $tableName, $fields, $language);
			$content .= $this->getUserSingleMiddle($moduleDirname, $tableName, $fields, $language);
		}
        $content .= $this->getUserSingleFooter();
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
