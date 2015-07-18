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
    * @var mixed
    */
    private $phpcode = null;

    /*
    * @var mixed
    */
    private $xoopscode = null;

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
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->tdmcreate = TDMCreateHelper::getInstance();
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
     * @public function getUserSingleTop
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $fields
     * @param $language
     *
     * @return string
     */
    public function getUserSingleTop($moduleDirname, $tableName, $fields, $language)
    {
        $stuModuleName = strtoupper($moduleDirname);
        $stuTableName = ucfirst($tableName);
        $fieldId = $this->xoopscode->getXoopsCodeGetFieldId($fields);
        $fieldPid = $this->xoopscode->getXoopsCodeGetFieldParentId($fields);
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ccFieldPid = $this->getCamelCase($fieldPid, false, true);
        $ret = $this->xoopscode->getXoopsCodeXoopsRequest($ccFieldId, $fieldId, '0', 'Int');
        $ret .= $this->xoopscode->getXoopsCodeXoopsRequest($ccFieldPid, $fieldPid, '0', 'Int');
        $ret .= "\$view{$stuTableName} = ".$this->xoopscode->getXoopsCodeHandler($tableName, $fieldId, true);

        $ret .= <<<EOT
\n// Template
\$xoopsOption['template_main'] = '{$moduleDirname}_single.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';
\$xoTheme->addStylesheet( XOOPS_URL . '/modules/' . \$xoopsModule->getVar('dirname', 'n') . '/assets/css/styles.css', null );

// redirect if not exist
if (count(\$view{$stuTableName}) == 0 || \$view{$stuTableName}->getVar('status') == 0){
    redirect_header('index.php', 3, {$language}SINGLE_NOT_EXIST);
    exit();
}

// For the permissions
\$categories = {$moduleDirname}GetMyItemIds('{$moduleDirname}_view', '{$moduleDirname}');
if(!in_array(\$view{$stuTableName}->getVar('{$fieldPid}'), \$categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
    exit();
}

EOT;

        return $ret;
    }

    /*
     * @public function getUserSingleMiddle
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $fields
     * @param $language
     *
     * @return string
     */
    public function getUserSingleMiddle($moduleDirname, $tableName, $fields, $language)
    {
        $fieldId = (string) $this->xoopscode->getXoopsCodeGetFieldId($fields);
        $ret = <<<EOT
    \n
EOT;

        return $ret;
    }

    /*
     * @public function getUserSingleBottom
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $fields
     * @param $language
     *
     * @return string
     */
    public function getUserSingleBottom($moduleDirname, $tableName, $fields, $language)
    {
        $fieldId = (string) $this->xoopscode->getXoopsCodeGetFieldId($fields);
        $ret = <<<EOT
    \n
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
        $tables = $this->tdmcreate->getHandler('tables')->getAllTablesByModuleId($module->getVar('mod_id'));
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        foreach (array_keys($tables) as $t) {
            $tableId[] = $tables[$t]->getVar('table_id');
            $tableMid[] = $tables[$t]->getVar('table_mid');            
            $tableSingle[] = $tables[$t]->getVar('table_single');
			if (in_array(1, $tableSingle)) {
				$tableName = $tables[$t]->getVar('table_name');
			}
        }
        $fields = $this->getTableFields($tableMid, $tableId);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserSingleHeader($moduleDirname);
        if (in_array(1, $tableSingle)) {
            $content .= $this->getUserSingleTop($moduleDirname, $tableName, $fields, $language);
            $content .= $this->getUserSingleMiddle($moduleDirname, $tableName, $fields, $language);
            $content .= $this->getUserSingleBottom($moduleDirname, $tableName, $fields, $language);
        }
        $content .= $this->getUserSingleFooter();

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
