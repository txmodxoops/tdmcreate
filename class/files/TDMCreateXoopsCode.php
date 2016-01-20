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
 * @version         $Id: TDMCreateXoopsCode.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');
/**
 * Class TDMCreateXoopsCode.
 */
class TDMCreateXoopsCode
{
    /*
    * @var mixed
    */
    private $phpcode = null;

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->phpcode = TDMCreatePhpCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TDMCreateXoopsCode
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
    *  @public function getXoopsCodeEqualsOperator
    *  @param string $left
    *  @param string $right
    *  @param boolean $ref
    *  @return string
    */
    public function getXoopsCodeEqualsOperator($left, $right, $ref = false)
    {
        if (false === $ref) {
            $ret = "{$left} = {$right};\n";
        } else {
            $ret = "{$left} =& {$right};\n";
        }

        return $ret;
    }

    /*
     *  @private function getXoopsCodeSwitch
     *  @param $op
     *  @param $listCases
     *  @param $defaultList
     *
     * @return string
     */
    private function getXoopsCodeSwitch($op = 'op', $listCases = array(), $defaultList = false)
    {
        $switch = $this->phpcode->getPhpCodeCaseSwitch(array($listCases), $defaultList);

        return $this->phpcode->getPhpCodeSwitch($op, $switch);
    }

    /*
    *  @public function getXoopsCodeCPHeader
    *  @param null
    *  @return string
    */
    public function getXoopsCodeCPHeader()
    {
        return "xoops_cp_header();\n";
    }

    /*
    *  @public function getXoopsCodeCPFooter
    *  @param null
    *  @return string
    */
    public function getXoopsCodeCPFooter()
    {
        return "xoops_cp_footer();\n";
    }

    /**
     *  @public function getXoopsCodeLoad
     *
     *  @param $var
     *
     *  @return string
     */
    public function getXoopsCodeLoad($var = '')
    {
        return "xoops_load('{$var}');\n";
    }

    /**
     *  @public function getXoopsCodeLoadLanguage
     *
     *  @param $lang
     *
     *  @return string
     */
    public function getXoopsCodeLoadLanguage($lang)
    {
        return "xoops_loadLanguage('{$lang}');\n";
    }

    /*
    *  @public function getXoopsCodeSetVar
    *  @param string $tableName
    *  @param string $fieldName
    *  @param string $var
    *  @return string
    */
    public function getXoopsCodeSetVar($tableName, $fieldName, $var)
    {
        return "\${$tableName}Obj->setVar('{$fieldName}', {$var});\n";
    }

    /*
    *  @public function getXoopsCodeGetVar
    *  @param string $varLeft
    *  @param string $handle
    *  @param string $var
    *  @param string $isParam
    *
    *  @return string
    */
    public function getXoopsCodeGetVar($varLeft = '', $handle = '', $var = '', $isParam = false)
    {
        if ($isParam === false) {
            $ret = "\${$varLeft} = \${$handle}->getVar('{$var}');\n";
        } else {
            $ret = "\${$handle}->getVar('{$var}')";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeGroupPermForm
    *  @param string $varLeft
    *  @param string $formTitle
    *  @param string $moduleId
    *  @param string $permName
    *  @param string $permDesc
    *  @param string $filename
    *
    *  @return string
    */
    public function getXoopsCodeGroupPermForm($varLeft = '', $formTitle = '', $moduleId = '', $permName = '', $permDesc = '', $filename = '')
    {
        return "\${$varLeft} = new XoopsGroupPermForm({$formTitle}, {$moduleId}, {$permName}, {$permDesc}, {$filename});\n";
    }

    /*
    *  @public function getXoopsCodeAddItem
    *  @param string $varLeft
    *  @param string $paramLeft
    *  @param string $paramRight
    *
    *  @return string
    */
    public function getXoopsCodeAddItem($varLeft = '', $paramLeft = '', $paramRight = '')
    {
        return "\${$varLeft}->addItem({$paramLeft}, {$paramRight});\n";
    }

    /*
    *  @public function getXoopsCodeTextDateSelectSetVar
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeTextDateSelectSetVar($tableName, $fieldName)
    {
        return $this->getXoopsCodeSetVar($tableName, $fieldName, "strtotime(\$_POST['{$fieldName}'])");
    }

    /*
    *  @public function getXoopsCodeCheckBoxOrRadioYNSetVar
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName)
    {
        $ret = $this->getXoopsCodeSetVar($tableName, $fieldName, "((1 == \$_REQUEST['{$fieldName}']) ? '1' : '0')");

        return $ret;
    }

    /*
    *  @public function getXoopsCodeGetConfig
    *  @param $moduleDirname
    *  @param $name
    *  @return string
    */
    public function getXoopsCodeGetConfig($moduleDirname, $name)
    {
        return "\${$moduleDirname}->getConfig('{$name}')";
    }

    /*
    *  @public function getXoopsCodeIdGetVar
    *  @param string $lpFieldName
    *  @return string
    */
    public function getXoopsCodeIdGetVar($lpFieldName)
    {
        return "\${$lpFieldName}['id'] = \$i;\n";
    }

    /*
    *  @public function getXoopsCodeGetVarAll
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return "\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n";
    }

    /*
    *  @public function getXoopsHandlerLine
    *  @param $moduleDirname
    *  @param $tableName
    *  @return string
    */
    public function getXoopsHandlerLine($moduleDirname, $tableName)
    {
        return "\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');\n";
    }

    /*
    *  @public function getXoopsSimpleForm
    *  @param $left
    *  @param $element
    *  @param $elementsContent
    *  @param $caption
    *  @param $var
    *  @param $filename
    *  @param $type
    *  
    *  @return string
    */
    public function getXoopsSimpleForm($left = '', $element = '', $elementsContent = '', $caption = '', $var = '', $filename = '', $type = 'post')
    {
        $ret = "\${$left} = new XoopsSimpleForm({$caption}, '{$var}', '{$filename}.php', '{$type}');\n";
        if (!empty($elementsContent)) {
            $ret .= "{$elementsContent}";
        }
        $ret .= "\${$left}->addElement(\${$element});\n";
        $ret .= "\${$left}->display();\n";

        return $ret;
    }

    /*
    *  @public function getXoopsFormSelect
    *  @param $varSelect
    *  @param $caption
    *  @param $var
    *  @param $options
    *  @param $setExtra
    *  
    *  @return string
    */
    public function getXoopsFormSelect($varSelect = '', $caption = '', $var = '', $options = array(), $setExtra = true)
    {
        $ret = "\${$varSelect} = new XoopsFormSelect({$caption}, '{$var}', \${$var});\n";
        if (false !== $setExtra) {
            $ret .= "\${$varSelect}->setExtra('{$setExtra}');\n";
        }
        foreach ($options as $key => $value) {
            $ret .= "\${$varSelect}->addOption('{$key}', {$value});\n";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeTopicGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $tableNameTopic
    *  @param string $fieldNameParent
    *  @param string $fieldNameTopic
    *  @return string
    */
    public function getXoopsCodeTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldNameParent}
\t\t\t\t\${$rpFieldName} =& \${$tableNameTopic}Handler->get(\${$tableName}All[\$i]->getVar('{$fieldNameParent}'));
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$rpFieldName}->getVar('{$fieldNameTopic}');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeParentTopicGetVar
    *  @param string $moduleDirname
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $tableSoleNameTopic
    *  @param string $tableNameTopic
    *  @param string $fieldNameParent
    *  @return string
    */
    public function getXoopsCodeParentTopicGetVar($moduleDirname, $lpFieldName, $rpFieldName, $tableName, $tableSoleNameTopic, $tableNameTopic, $fieldNameParent)
    {
        $ret = <<<EOT
\t\t\t\tif(!isset(\${$tableNameTopic}Handler)) {
\t\t\t\t\t// Get {$tableNameTopic} Handler
\t\t\t\t\t\${$tableNameTopic}Handler =& \${$moduleDirname}->getHandler('{$tableNameTopic}');
\t\t\t\t}
\t\t\t\t// Get Var {$fieldNameParent}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$tableNameTopic}Handler->get{$tableSoleNameTopic}FromId(\${$tableName}All[\$i]->getVar('{$fieldNameParent}'));\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeUploadImageGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$fieldName} = \${$tableName}All[\$i]->getVar('{$fieldName}');
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$fieldName} ? \${$fieldName} : 'blank.gif';\n
EOT;

        return $ret;
    }
    /*
    *  @public function getXoopsCodeUrlFileGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeUrlFileGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return $this->getXoopsCodeGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName);
    }
    /*
    *  @public function getXoopsCodeTextAreaGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeTextAreaGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return "\${$lpFieldName}['{$rpFieldName}'] = strip_tags(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n";
    }

    /*
    *  @public function getXoopsCodeSelectUserGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    * @return string
    */
    public function getXoopsCodeSelectUserGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return "\${$lpFieldName}['{$rpFieldName}'] = XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /*
    *  @public function getXoopsCodeTextDateSelectGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeTextDateSelectGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return "\${$lpFieldName}['{$rpFieldName}'] = formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /*
    *  @public function getXoopsCodeUserHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @return string
    */
    public function getXoopsCodeXoopsOptionTemplateMain($moduleDirname, $tableName)
    {
        return "\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';\n";
    }

    /*
    *  @public function getXoopsCodeUserHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @return string
    */
    public function getXoopsCodeUserHeader($moduleDirname, $tableName)
    {
        $ret = $this->phpcode->getPhpCodeIncludeDir('__DIR__', 'header');
        $ret .= $this->getXoopsCodeXoopsOptionTemplateMain($moduleDirname, $tableName);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);

        return $ret;
    }

    /*
    *  @public function getXoopsCodePermissionsHeader
    *  @param null
    */
    /**
     * @return string
     */
    public function getXoopsCodePermissionsHeader()
    {
        $ret = $this->phpcode->getPhpCodeCommentLine('Permission');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret .= $this->getXoopsCodeEqualsOperator('$gperm_handler', "xoops_gethandler('groupperm')", true);
        $groups = $this->getXoopsCodeEqualsOperator('$groups', '$xoopsUser->getGroups()');
        $elseGroups = $this->getXoopsCodeEqualsOperator('$groups', 'XOOPS_GROUP_ANONYMOUS');
        $ret .= $this->phpcode->getPhpCodeConditions('is_object($xoopsUser)', '', $type = '', $groups, $elseGroups);

        return $ret;
    }

    /**
     *  @public function getXoopsCodeGetFieldId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeGetFieldId($fields)
    {
        $fieldId = 'id';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
        }

        return $fieldId;
    }

    /**
     *  @public function getXoopsCodeGetFieldName
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeGetFieldName($fields)
    {
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
        }

        return $fieldName;
    }

    /**
     *  @public function getXoopsCodeGetFieldParentId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeGetFieldParentId($fields)
    {
        $fieldPid = 'pid';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
        }

        return $fieldPid;
    }

    /**
     *  @public function getXoopsCodeUserSaveElements
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeUserSaveElements($moduleDirname, $tableName, $fields)
    {
        $ret = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $this->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $this->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $this->getXoopsCodeUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= $this->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName);
            } else {
                $ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
            }
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeXoopsRequest
    *  @param $left
    *  @param $var1
    *  @param $var2
    *  @param $type
    *  @param $metod
    *  @return string
    */
    public function getXoopsCodeXoopsRequest($left = '', $var1 = '', $var2 = '', $type = 'String', $metod = false)
    {
        if ($type == 'String') {
            $ret = "\${$left} = XoopsRequest::getString('{$var1}', '{$var2}');\n";
        } elseif ($type == 'Int') {
            $ret = "\${$left} = XoopsRequest::getInt('{$var1}', {$var2});\n";
        } elseif ($type == 'Int' && $metod !== false) {
            $ret = "\${$left} = XoopsRequest::getInt('{$var1}', {$var2}, '{$metod}');\n";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeTplAssign
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getXoopsCodeTplAssign($tplString, $phpRender)
    {
        return "\$GLOBALS['xoopsTpl']->assign('{$tplString}', {$phpRender});\n";
    }

    /**
     *  @public function getXoopsCodeXoopsTplAppend
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getXoopsCodeXoopsTplAppend($tplString, $phpRender)
    {
        return "\$GLOBALS['xoopsTpl']->append('{$tplString}', {$phpRender});\n";
    }

    /**
     *  @public function getXoopsCodeXoopsTplAppendByRef
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getXoopsCodeXoopsTplAppendByRef($tplString, $phpRender)
    {
        return "\$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', {$phpRender});\n";
    }

    /**
     *  @public function getXoopsCodePath
     *
     *  @param $directory
     *  @param $filename
     *  @param $condition
     *
     *  @return string
     */
    public function getXoopsCodePath($directory, $filename, $condition = false)
    {
        if ($condition === false) {
            $ret = "\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php');\n";
        } else {
            $ret = "\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php')";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeTplDisplay
     *
     *  @param null
     *
     *  @return string
     */
    public function getXoopsCodeTplDisplay()
    {
        return "\$GLOBALS['xoopsTpl']->display(\"db:{\$templateMain}\");\n";
    }

    /**
     *  @public function getXoopsCodeGetInfo
     *
     *  @param $string
     *  @param $isParam
     *
     *  @return string
     */
    public function getXoopsCodeGetInfo($string, $isParam = false)
    {
        if ($isParam === false) {
            $ret = "\$GLOBALS['xoopsModule']->getInfo('{$string}');\n";
        } else {
            $ret = "\$GLOBALS['xoopsModule']->getInfo('{$string}')";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeObjHandlerCreate
     *
     *  @param string $tableName
     *
     *  @return string
     */
    public function getXoopsCodeObjHandlerCreate($tableName)
    {
        $ret = "\${$tableName}Obj =& \${$tableName}Handler->create();\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeObjHandlerCount
     *
     *  @param string $tableName
     *
     *  @return string
     */
    public function getXoopsCodeObjHandlerCount($tableName)
    {
        $ucfTableName = ucfirst($tableName);
        $ret = "\${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeObjHandlerAll
     *
     *  @param string $tableName
     *  @param string $fieldMain
     *
     *  @return string
     */
    public function getXoopsCodeObjHandlerAll($tableName, $fieldMain)
    {
        $ucfTableName = ucfirst($tableName);
        $ret = "\${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}(0, 0, '{$fieldMain}');\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeSetVarsObjects
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeSetVarsObjects($moduleDirname, $tableName, $fields)
    {
        $ret = '';

        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $this->getXoopsCodeImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $this->getXoopsCodeFileSetVar($moduleDirname, $tableName, $fieldName, true);
                        break;
                    case 13:
                        $ret .= $this->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $this->getXoopsCodeFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $this->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        $ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeSecurity
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXoopsCodeSecurity($tableName)
    {
        $securityError = $this->getXoopsCodeSecurityGetError();
        $implode = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $content = $this->getXoopsCodeRedirectHeader($tableName, '', 3, $implode);
        $securityCheck = $this->getXoopsCodeSecurityCheck();

        return $this->phpcode->getPhpCodeConditions('!'.$securityCheck, '', '', $content);
    }

    /*
    *  @public function getXoopsCodeInsertData
    *  @param $tableName
    *  @param $language
    *  @return string
    */
    public function getXoopsCodeInsertData($tableName, $language)
    {
        $content = $this->getXoopsCodeRedirectHeader($tableName, '?op=list', 2, "{$language}FORM_OK");
        $handlerInsert = $this->getXoopsCodeHandler($tableName, $tableName, false, true, false, 'Obj');

        return $this->phpcode->getPhpCodeConditions($handlerInsert, '', '', $content);
    }

    /*
    *  @public function getXoopsCodeRedirectHeader
    *  @param $tableName
    *  @param $options
    *  @param $numb
    *  @param $var
    *  @return string
    */
    public function getXoopsCodeRedirectHeader($tableName, $options = '', $numb = '2', $var)
    {
        return "redirect_header('{$tableName}.php{$options}', {$numb}, {$var});\n";
    }

    /*
    *  @public function getXoopsCodeXoopsConfirm
    *  @param $tableName
    *  @param $options
    *  @param $fieldId
    *  @param $fieldMain
    *  @param $language
    *
    *  @return string
    */
    public function getXoopsCodeXoopsConfirm($tableName, $options = 'delete', $fieldId, $fieldMain, $language)
    {
        $stuOptions = strtoupper($options);
        $ret = "xoops_confirm(array('ok' => 1, '{$fieldId}' => \${$fieldId}, 'op' => {$options}), \$_SERVER['REQUEST_URI'], sprintf({$language}FORM_SURE_{$stuOptions}, \${$tableName}Obj->getVar('{$fieldMain}')));\n";

        return $ret;
    }

    /*
    *  @public function getXoopsCodeSecurityCheck
    *  @param null
    *  @return boolean
    */
    public function getXoopsCodeSecurityCheck()
    {
        return "\$GLOBALS['xoopsSecurity']->check()";
    }

    /*
    *  @public function getXoopsCodeSecurityGetError
    *  @param null
    *  @return string
    */
    public function getXoopsCodeSecurityGetError()
    {
        return "\$GLOBALS['xoopsSecurity']->getErrors()";
    }

    /**
     *  @public function getXoopsCodeGetFormError
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXoopsCodeGetFormError($tableName)
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
     *  @public function getXoopsCodeGetFormId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getXoopsCodeGetFormId($tableName, $fieldId)
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
     *  @public function getXoopsCodeHandler
     *
     *  @param string $tableName
     *  @param string $var
     *
     *  @return string
     */
    public function getXoopsCodeHandler($tableName, $var, $get = false, $insert = false, $delete = false, $obj = '')
    {
        if ($get) {
            $ret = "\${$tableName}Handler->get(\${$var});";
        } elseif ($insert && ($obj != '')) {
            $ret = "\${$tableName}Handler->insert(\${$var}{$obj});";
        } elseif ($delete && ($obj != '')) {
            $ret = "\${$tableName}Handler->delete(\${$var}{$obj});";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeCaseDelete
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getXoopsCodeCaseDelete($language, $tableName, $fieldId, $fieldMain)
    {
        $ret = <<<EOT
        \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        if (isset(\$_REQUEST['ok']) && 1 == \$_REQUEST['ok']) {
            if ( !\$GLOBALS['xoopsSecurity']->check() ) {
                redirect_header('{$tableName}.php', 3, implode(', ', \$GLOBALS['xoopsSecurity']->getErrors()));
            }
            if (\${$tableName}Handler->delete(\${$tableName}Obj)) {
                redirect_header('{$tableName}.php', 3, {$language}FORM_DELETE_OK);
            } else {
                echo \${$tableName}Obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, '{$fieldId}' => \${$fieldId}, 'op' => 'delete'), \$_SERVER['REQUEST_URI'], sprintf({$language}FORM_SURE_DELETE, \${$tableName}Obj->getVar('{$fieldMain}')));
        }\n
EOT;
        /*$isset = $this->phpcode->getPhpCodeIsset($fieldId);
        $if1 = $this->phpcode->getPhpCodeConditions($isset, '', '', "\${$tableName}Obj =& ".$get);
        $get = $this->getXoopsCodeHandler($tableName, $fieldId, true);
        $if2 = $this->phpcode->getPhpCodeConditions($isset, '', '', "\${$tableName}Obj =& ".$get);
        $ret = $this->phpcode->getPhpCodeConditions($isset, '', '', "\${$tableName}Obj =& ".$get);
        //$ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
        $handlerInsert = $this->$this->getXoopsCodeHandler($tableName, $tableName, false, true, false, 'Obj');
        $redirect = $this->getXoopsCodeRedirectHeader($tableName, '', 2, "{$language}FORM_DELETE_OK");

        $else = $this->getXoopsCodeTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");

        $ret .= $this->phpcode->getPhpCodeConditions($handlerInsert, '', '', $redirect, $else);*/

        return $this->phpcode->getPhpCodeCaseSwitch('delete', $ret);
    }

    /*
    *  @public function getXoopsCodeUpdate
    *  @param $language
    *  @param $tableName
    *  @param $fieldId
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeUpdate($language, $tableName, $fieldId, $fieldName)
    {
        $isset = $this->phpcode->getPhpCodeIsset($fieldId);
        $get = $this->getXoopsCodeHandler($tableName, $fieldId, true);
        $content = $this->phpcode->getPhpCodeConditions($isset, '', '', "\${$tableName}Obj =& ".$get);
        $content .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
        $handlerInsert = $this->$this->getXoopsCodeHandler($tableName, $tableName, false, true, false, 'Obj');
        $redirect = $this->getXoopsCodeRedirectHeader($tableName, '?op=list', 2, "{$language}FORM_UPDATE_OK");
        $content .= $this->phpcode->getPhpCodeConditions($handlerInsert, '', '', $redirect);

        $content .= $this->getXoopsCodeTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");

        return $this->phpcode->getPhpCodeCaseSwitch('update', $content);
    }
}
