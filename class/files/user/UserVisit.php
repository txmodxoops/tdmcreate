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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: UserVisit.php 12258 2014-01-02 09:33:29Z timgno \$
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class UserVisit
 */
class UserVisit extends UserObjects
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
		$this->userobjects = UserObjects::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserVisit
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
    *  @public function getUserVisitHeader
    *  @param null
    */
    /**
     * @param $moduleDirname
     * @return string
     */
    public function getUserVisitHeader($moduleDirname, $tableName, $tableSoleName, $fields, $language)
    {
        $stuModuleName = strtoupper($moduleDirname);
		$fieldId       = $this->userobjects->getUserSaveFieldId($fields);
		$ccFieldId     = $this->tdmcfile->getCamelCase($fieldId, false, true);
		$ret 		   = <<<EOT
include  __DIR__ . '/header.php';
\${$ccFieldId} = XoopsRequest::getInt('{$fieldId}');
\$cid = XoopsRequest::getInt('cid');

if(!isset(\$_GET['return'])) {
	// redirect if there aren't {$tableSoleName}
	\$view_{$tableName} = \${$moduleDirname}_Handler->get(\${$ccFieldId});
	if (count(\$view_{$tableName}) == 0){
		redirect_header('index.php', 3, {$language}SINGLE_NONEXISTENT);
		exit();
	}
	//redirect if there aren't permission (cat)
	\$categories = {$moduleDirname}GetMyItemIds('{$moduleDirname}_view', '{$moduleDirname}');
	if(!in_array(\$view_{$tableName}->getVar('cid'), \$categories)) {
		redirect_header(XOOPS_URL, 2, _NOPERM);
		exit();
	}
	//redirect if there aren't permission ({$tableSoleName})
	if (\$xoopsModuleConfig['permission_{$tableSoleName}'] == 2) {
		\$item = {$moduleDirname}GetMyItemIds('{$moduleDirname}_{$tableSoleName}_item', '{$moduleDirname}');
		if(!in_array(\$view_{$tableName}->getVar('{$fieldId}'), \$item)) {
			redirect_header('single.php?{$fieldId}=' . \$view_{$tableName}->getVar('{$fieldId}'), 2, {$language}SINGLE_NOPERM);
			exit();
		}
	}else{
		\$categories = {$moduleDirname}GetMyItemIds('{$moduleDirname}_{$tableSoleName}', '{$moduleDirname}');
		if(!in_array(\$view_{$tableName}->getVar('cid'), \$categories)) {
			redirect_header('single.php?{$fieldId}=' . \$view_{$tableName}->getVar('{$fieldId}'), 2, {$language}SINGLE_NOPERM);
			exit();
		}
	}
}
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
     * @return string
     */
    public function getUserVisitForm($module, $tableName, $language)
    {
        $stuModuleName = strtoupper($module->getVar('mod_name'));
        $ret           = <<<EOT
    case 'form':
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
    *  @public function getUserVisitSave
    *  @param string $moduleDirname
    *  @param string $tableName
    */
    /**
     * @param $moduleDirname
     * @param $table_id
     * @param $tableName
     * @return string
     */
    public function getUserVisitSave($moduleDirname, $fields, $tableName, $language)
    {
        $fieldId = $this->userobjects->getUserSaveFieldId($fields);
		$ret     = <<<EOT
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
        $ret .= $this->userobjects->getUserSaveElements($moduleDirname, $tableName, $fields);
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
    *  @public function getUserVisitFooter
    *  @param null
    */
    /**
     * @return string
     */
    public function getUserVisitFooter()
    {
        $ret = <<<EOT
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
        $module        = $this->getModule();
        $table         = $this->getTable();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId       = $table->getVar('table_id');
		$tableMid      = $table->getVar('table_mid');
        $tableName     = $table->getVar('table_name');
		$tableSoleName = $table->getVar('table_solename');
		$fields 	   = $this->tdmcfile->getTableFields($tableMid, $tableId);
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserVisitHeader($moduleDirname, $tableName, $tableSoleName, $fields, $language);
        $content .= $this->getUserVisitForm($module, $tableName, $language);
        $content .= $this->getUserVisitSave($moduleDirname, $fields, $tableName, $language);
        $content .= $this->getUserVisitFooter();
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
