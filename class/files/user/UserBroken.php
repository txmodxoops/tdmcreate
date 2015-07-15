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
 * @version         $Id: UserBroken.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class UserBroken.
 */
class UserBroken extends TDMCreateFile
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
        $this->tdmcfile = TDMCreateFile::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
		$this->xoopscode = TDMCreateXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserBroken
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
    *  @public function getUserBrokenHeader
    *  @param null
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    public function getUserBrokenHeader($moduleDirname, $fields)
    {
        $fieldId = $this->xoopscode->getXoopsCodeGetFieldId($fields);
        $ret = <<<EOT
include  __DIR__ . '/header.php';
\$op = XoopsRequest::getString('op', 'list');
\${$fieldId} = XoopsRequest::getInt('{$fieldId}');
// Template
\$xoopsOption['template_main'] = '{$moduleDirname}_broken.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';
\$xoTheme->addStylesheet( XOOPS_URL . '/modules/' . \$xoopsModule->getVar('dirname', 'n') . '/assets/css/style.css', null );

// redirection if not permissions
if (\$permSubmit == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}
//
switch (\$op)
{\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminPagesList
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
    public function getUserBrokenForm($module, $tableName, $language)
    {
        $stuModuleName = strtoupper($module->getVar('mod_name'));
        $ret = <<<EOT
    case 'list':
    default:
        //navigation
        \$navigation = {$language}SUBMIT_PROPOSER;
        \$GLOBALS['xoopsTpl']->assign('navigation', \$navigation);
        // reference
        // title of page
        \$title = {$language}SUBMIT_PROPOSER . '&nbsp;-&nbsp;';
        \$title .= \$GLOBALS['xoopsModule']->name();
        \$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', \$title);
        //description
        \$GLOBALS['xoTheme']->addMeta( 'meta', 'description', strip_tags({$language}SUBMIT_PROPOSER));
        // Description
        \$GLOBALS['xoTheme']->addMeta( 'meta', 'description', strip_tags({$language}SUBMIT));

        // Create
        \${$tableName}Obj =& \${$tableName}Handler->create();
        \$form = \${$tableName}Obj->getForm();
        \$xoopsTpl->assign('form', \$form->render());\n
EOT;

        return $ret;
    }

    /*
    *  @public function getUserBrokenSave
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
    public function getUserBrokenSave($moduleDirname, $fields, $tableName, $language)
    {
        $fieldId = $this->xoopscode->getXoopsCodeGetFieldId($fields);
        $ret = <<<EOT
    case 'save':
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
           redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }
        \${$tableName}Obj =& \${$tableName}Handler->create();
		\$error = false;
        \$errorMessage = '';
        // Test first the validation
        xoops_load("captcha");
        \$xoopsCaptcha = XoopsCaptcha::getInstance();
        if ( !\$xoopsCaptcha->verify() ) {
            \$errorMessage .= \$xoopsCaptcha->getMessage().'<br>';
            \$error = true;
        }\n
EOT;
        $ret .= $this->xoopscode->getXoopsCodeUserSaveElements($moduleDirname, $tableName, $fields);
        $ret .= <<<EOT

        if (\$error == true){
            \$xoopsTpl->assign('error_message', \$errorMessage);
        } else {
			if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
				redirect_header('index.php', 2, {$language}FORM_OK);
			}
		}
        echo \${$tableName}Obj->getHtmlErrors();
        \$form =& \${$tableName}Obj->getForm();
        \$xoopsTpl->assign('form', \$form->display());
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getUserBrokenFooter
    *  @param null
    */
    /**
     * @return string
     */
    public function getUserBrokenFooter()
    {
        $ret = <<<EOT
}
include  __DIR__ . '/footer.php';
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
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $tableName = $table->getVar('table_name');
        $fields = $this->tdmcfile->getTableFields($tableMid, $tableId);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserBrokenHeader($moduleDirname, $fields);
        $content .= $this->getUserBrokenForm($module, $tableName, $language);
        $content .= $this->getUserBrokenSave($moduleDirname, $fields, $tableName, $language);
        $content .= $this->getUserBrokenFooter();
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
