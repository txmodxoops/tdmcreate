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
 * @version         $Id: AdminPhpCode.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class AdminPhpCode.
 */
class AdminPhpCode extends AdminObjects
{
    /*
    * @var string
    */
    protected $adminphpcode;

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
        $this->adminobjects = AdminObjects::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return AdminPhpCode
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
    *  @public function getAdminIncludeHeader
    *  @param null
    *  @return string
    */
    public function getAdminIncludeHeader()
    {
        return "include __DIR__ . '/header.php';\n";
    }

    /**
     *  @public function getAdminSwitch
     *
     *  @param $content
     *
     *  @return string
     */
    public function getAdminSwitch($content)
    {
        $ret = <<<EOT
switch {\n
	\t{$content}
}\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminStringCaseDefaultSwitch
     *
     *  @param $case
     *  @param $content
     *
     *  @return string
     */
    public function getAdminStringCaseDefaultSwitch($case = 'list', $content)
    {
        $ret = <<<EOT
    case '{$case}':
    default:\n
	\t\t{$content}
    break;\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminStringCaseSwitch
     *
     *  @param $case
     *  @param $content
     *
     *  @return string
     */
    public function getAdminStringCaseSwitch($case = 'list', $content)
    {
        $ret = <<<EOT
    case '{$case}':\n
	\t\t{$content}
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminNumericCaseDefaultSwitch
    *  @param $case
    *  @return string
    */
    public function getAdminNumericCaseDefaultSwitch($case = 1)
    {
        $ret = <<<EOT
    case {$case}:
    default:\n
	\t\t{$content}
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminNumericCaseSwitch
    *  @param $case
    *  @return string
    */
    public function getAdminNumericCaseSwitch($case = 1)
    {
        $ret = <<<EOT
    case {$case}:\n
	\t\t{$content}
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminTemplateMain
    *  @param $moduleDirname
    *  @param $tableName
    *  @return string
    */
    public function getAdminTemplateMain($moduleDirname, $tableName)
    {
        $ret = <<<EOT
        \$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminXoopsTplAssign
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getAdminXoopsTplAssign($tplString, $phpRender)
    {
        $ret = <<<EOT
        \$GLOBALS['xoopsTpl']->assign('{$tplString}', \${$phpRender});\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminXoopsTplAppend
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getAdminXoopsTplAppend($tplString, $phpRender)
    {
        $ret = <<<EOT
        \$GLOBALS['xoopsTpl']->append('{$tplString}', \${$phpRender});\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminXoopsTplAppendByRef
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getAdminXoopsTplAppendByRef($tplString, $phpRender)
    {
        $ret = <<<EOT
        \$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', \${$phpRender});\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminTemplateMain
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $admin
    *  @return string
    */
    public function getAdminItemButton($moduleDirname, $tableName, $admin = false)
    {
        $ret = <<<EOT
        \$adminMenu->addItemButton({$language}ADD_{$stuTableSoleName}, '{$tableName}.php?op=new', 'add');
        \$adminMenu->addItemButton({$language}{$stuTableName}_LIST, '{$tableName}.php', 'list');
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));
        \$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminAddNavigation
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getAdminAddNavigation($tableName)
    {
        $ret = <<<EOT
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminObjHandlerCreate
     *
     *  @param string $tableName
     *
     *  @return string
     */
    public function getAdminObjHandlerCreate($tableName)
    {
        $ret = <<<EOT
        \${$tableName}Obj =& \${$tableName}Handler->create();\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminPhpCodeSetVarsObjects
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getAdminPhpCodeSetVarsObjects($moduleDirname, $tableName, $fields)
    {
        $ret = <<<EOT
        // Set Vars\n
EOT;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->adminobjects->getCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $this->adminobjects->getImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $this->adminobjects->getUrlFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 13:
                        if (1 == $fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        $ret .= $this->adminobjects->getUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $this->adminobjects->getUploadFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $this->adminobjects->getTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        $ret .= $this->adminobjects->getSimpleSetVar($tableName, $fieldName);
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     *  @public function getAdminPhpCodeXoopsSecurity
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getAdminPhpCodeXoopsSecurity($tableName)
    {
        $ret = <<<EOT
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
			redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminPhpCodeInsertData
    *  @param $tableName
    *  @param $language
    *  @return string
    */
    public function getAdminPhpCodeInsertData($tableName, $language)
    {
        $ret = <<<EOT
        // Insert Data
        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
			redirect_header('{$tableName}.php?op=list', 2, {$language}FORM_OK);
        }\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminPhpCodeGetFormError
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getAdminPhpCodeGetFormError($tableName)
    {
        $ret = <<<EOT
        // Get Form
        \$GLOBALS['xoopsTpl']->assign('error', \${$tableName}Obj->getHtmlErrors());
        \$form =& \${$tableName}Obj->getForm();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminPhpCodeGetFormId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getAdminPhpCodeGetFormId($tableName, $fieldId)
    {
        $ret = <<<EOT
        // Get Form
        \${$tableName}Obj = \${$tableName}Handler->get(\${$fieldId});
        \$form = \${$tableName}Obj->getForm();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminPhpCodeGetObjHandlerId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getAdminPhpCodeGetObjHandlerId($tableName, $fieldId)
    {
        $ret = <<<EOT
        \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminPhpCodeDelete
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getAdminPhpCodeDelete($tableName, $language, $fieldId, $fieldMain)
    {
        $ret = <<<EOT
    case 'delete':
        \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        if (isset(\$_REQUEST['ok']) && 1 == \$_REQUEST['ok']) {
            if ( !\$GLOBALS['xoopsSecurity']->check() ) {
                redirect_header('{$tableName}.php', 3, implode(', ', \$GLOBALS['xoopsSecurity']->getErrors()));
            }
            if (\${$tableName}Handler->delete(\${$tableName}Obj)) {
                redirect_header('{$tableName}.php', 3, {$language}FORMDELOK);
            } else {
                echo \${$tableName}Obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, '{$fieldId}' => \${$fieldId}, 'op' => 'delete'), \$_SERVER['REQUEST_URI'], sprintf({$language}FORMSUREDEL, \${$tableName}Obj->getVar('{$fieldMain}')));
        }
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminPhpCodeUpdate
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getAdminPhpCodeUpdate($moduleDirname, $tableName, $language, $fieldId, $fieldMain)
    {
        $upModuleName = strtoupper($moduleDirname);
        $ret = <<<EOT
    case 'update':
        if (isset(\${$fieldId})) {
            \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        }
        \${$tableName}Obj->setVar("\${$tableName}_display", \$_POST["\${$tableName}_display"]);

        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
            redirect_header("\${$tableName}.php", 3, _AM_{$upModuleName}_FORMOK);
        }
        echo \${$tableName}Obj->getHtmlErrors();
    break;\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminPhpCodeGetDisplayTpl
     *
     *  @param null
     *
     *  @return string
     */
    public function getAdminPhpCodeGetDisplayTpl()
    {
        return "\t\t\$GLOBALS['xoopsTpl']->display(\"db:{\$templateMain}\");\n";
    }

    /*
    *  @public function getAdminIncludeFooter
    *  @param null
    */
    /**
     * @return string
     */
    public function getAdminIncludeFooter()
    {
        return "include __DIR__ . '/footer.php';";
    }
}
