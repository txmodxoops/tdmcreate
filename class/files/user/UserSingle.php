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
 * @version         $Id: UserSingle.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class UserSingle
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
        $this->tdmcfile = TDMCreateFile::getInstance();
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
     * @return string
     */
    public function getUserSingleHeader($moduleDirname)
    {
        $ret = <<<EOT
include  __DIR__ . '/header.php';
\$op = {$moduleDirname}_CleanVars(\$_REQUEST, 'op', 'form', 'string');
// Template
\$xoopsOption['template_main'] = '{$moduleDirname}_single.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';
\$xoTheme->addStylesheet( XOOPS_URL . '/modules/' . \$xoopsModule->getVar('dirname', 'n') . '/assets/css/style.css', null );
//On recupere la valeur de l'argument op dans l'URL$
// redirection if not permissions
if (\$perm_submit == false) {
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
     * @return string
     */
    public function getUserSingleForm($module, $tableName, $language)
    {
        $stuModuleName = strtoupper($module->getVar('mod_name'));
        $ret           = <<<EOT
    case 'form':
    default:
        //navigation
        \$navigation = _MD_{$stuModuleName}_SUBMIT_PROPOSER;
        \$GLOBALS['xoopsTpl']->assign('navigation', \$navigation);
        // reference
        // title of page
        \$title = _MD_{$stuModuleName}_SUBMIT_PROPOSER . '&nbsp;-&nbsp;';
        \$title .= \$GLOBALS['xoopsModule']->name();
        \$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', \$title);
        //description
        \$GLOBALS['xoTheme']->addMeta( 'meta', 'description', strip_tags(_MD_{$stuModuleName}_SUBMIT_PROPOSER));
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
    *  @public function getUserSingleSave
    *  @param string $moduleDirname
    *  @param string $tableName
    */
    /**
     * @param $moduleDirname
     * @param $table_id
     * @param $tableName
     * @return string
     */
    public function getUserSingleSave($moduleDirname, $table_id, $tableName)
    {
        $ret    = <<<EOT
    case 'save':
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
           redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset(\$_REQUEST['{$fpif}'])) {
           \${$tableName}Obj =& \${$tableName}Handler->get(\$_REQUEST['{$fpif}']);
        } else {
           \${$tableName}Obj =& \${$tableName}Handler->create();
        }
EOT;
        $fields = $this->getTableFields($table_id);
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $this->adminobjects->getCheckBoxOrRadioYN($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $this->adminobjects->getUploadImage($moduleDirname, $tableName, $fieldName);
            } elseif (14 == $fieldElement) {
                $ret .= $this->adminobjects->getUploadFile($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= $this->adminobjects->getTextDateSelect($tableName, $fieldName);
            } else {
                $ret .= $this->adminobjects->getSimpleSetVar($tableName, $fieldName);
            }
        }

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
        $table_id      = $table->getVar('table_id');
        $tableName     = $table->getVar('table_name');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserSingleHeader($moduleDirname);
        $content .= $this->getUserSingleForm($module, $tableName, $language);
        $content .= $this->getUserSingleSave($moduleDirname, $table_id, $tableName);
        $content .= $this->getUserSingleFooter();
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
