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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: TDMCreateXoopsCode.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TDMCreateXoopsCode.
 */
class TDMCreateXoopsCode
{
    /**
     *  @static function getInstance
     *  @param null
     */

    /**
     * @return TDMCreateXoopsCode
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
     *  @public function getXcSwitch
     *  @param $op
     *  @param $cases
     *  @param $defaultAfterCase
     *  @param $default
     *  @param $t - Indentation
     *
     * @return string
     */
    public function getXcSwitch($op = '', $cases = [], $defaultAfterCase = false, $default = false, $t = '')
    {
        $pc = TDMCreatePhpCode::getInstance();
        $contentSwitch = $pc->getPhpCodeCaseSwitch($cases, $defaultAfterCase, $default, $t);

        return $pc->getPhpCodeSwitch($op, $contentSwitch, $t);
    }

    /**
     *  @public function getXcEqualsOperator
     *  @param $var
     *  @param $value
     *  @param $interlock
     *  @param $ref
     *  @param $t - Indentation
     *
     *  @return string
     */
    public function getXcEqualsOperator($var, $value, $interlock = null, $ref = false, $t = '')
    {
        if (false === $ref) {
            $ret = "{$t}{$var} {$interlock}= {$value};\n";
        } else {
            $ret = "{$t}{$var} = {$value};\n";
        }

        return $ret;
    }

    /**
     *  @public function getXcCPHeader
     *  @param null
     *  @return string
     */
    public function getXcCPHeader()
    {
        return "xoops_cp_header();\n";
    }

    /**
     *  @public function getXcCPFooter
     *  @param null
     *  @return string
     */
    public function getXcCPFooter()
    {
        return "xoops_cp_footer();\n";
    }

    /**
     *  @public function getXcLoad
     *
     *  @param $var
     *  @param $t
     *  @return string
     */
    public function getXcLoad($var = '', $t = '')
    {
        return "{$t}xoops_load('{$var}');\n";
    }

    /**
     *  @public function getXcLoadLanguage
     *
     *  @param $lang
     *  @param $t
     *  @param $domain
     *
     *  @return string
     */
    public function getXcLoadLanguage($lang, $t = '', $domain = '')
    {
        if ('' === $domain) {
            return "{$t}xoops_loadLanguage('{$lang}');\n";
        }

        return "{$t}xoops_loadLanguage('{$lang}', '{$domain}');\n";
    }

    /**
     *  @public function getXcAnchorFunction
     *  @param $anchor
     *  @param $name
     *  @param $vars
     *  @param $close
     *
     *  @return string
     */
    public function getXcAnchorFunction($anchor, $name, $vars, $close = false)
    {
        $semicolon = false !== $close ? ';' : '';

        return "\${$anchor}->{$name}({$vars}){$semicolon}";
    }

    /**
     *  @public function getXcSetVar
     *  @param $tableName
     *  @param $fieldName
     *  @param $var
     * @param $t
     *  @return string
     */
    public function getXcSetVar($tableName, $fieldName, $var, $t = '')
    {
        return "{$t}\${$tableName}Obj->setVar('{$fieldName}', {$var});\n";
    }

    /**
     *  @public function getXcGetVar
     *  @param $varLeft
     *  @param $handle
     *  @param $var
     *  @param $isParam
     * @param $t
     *
     *  @return string
     */
    public function getXcGetVar($varLeft = '', $handle = '', $var = '', $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$varLeft} = \${$handle}->getVar('{$var}');\n";
        } else {
            $ret = "\${$handle}->getVar('{$var}')";
        }

        return $ret;
    }

    /**
     *  @public function getXcGroupPermForm
     *  @param $varLeft
     *  @param $formTitle
     *  @param $moduleId
     *  @param $permName
     *  @param $permDesc
     *  @param $filename
     * @param $t
     *
     *  @return string
     */
    public function getXcGroupPermForm($varLeft = '', $formTitle = '', $moduleId = '', $permName = '', $permDesc = '', $filename = '', $t = '')
    {
        return "{$t}\${$varLeft} = new XoopsGroupPermForm({$formTitle}, {$moduleId}, {$permName}, {$permDesc}, {$filename});\n";
    }

    /**
     *  @public function getXcAddItem
     *  @param $varLeft
     *  @param $paramLeft
     *  @param $paramRight
     * @param $t
     *
     *  @return string
     */
    public function getXcAddItem($varLeft = '', $paramLeft = '', $paramRight = '', $t = '')
    {
        return "{$t}\${$varLeft}->addItem({$paramLeft}, {$paramRight});\n";
    }

    /**
     * @public function getXcGetGroupIds
     * @param string $var
     * @param string $anchor
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param string $t
     * @return string
     */
    public function getXcGetGroupIds($var = '', $anchor = '', $param1 = null, $param2 = null, $param3 = null, $t = '')
    {
        return "{$t}\${$var} = \${$anchor}->getGroupIds({$param1}, {$param2}, {$param3});\n";
    }

    /**
     * @public function getXcGetItemIds
     * @param string $var
     * @param string $anchor
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param string $t
     * @return string
     */
    public function getXcGetItemIds($var = '', $anchor = '', $param1 = null, $param2 = null, $param3 = null, $t = '')
    {
        return "{$t}\${$var} = \${$anchor}->getItemIds({$param1}, {$param2}, {$param3});\n";
    }

    /**
     * @public function getXcTextDateSelectSetVar
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcTextDateSelectSetVar($tableName, $tableSoleName, $fieldName, $t = '')
    {
        $tf = TDMCreateFile::getInstance();
        $rightField = $tf->getRightString($fieldName);
        $ucfRightFiled = ucfirst($rightField);
        $value = "date_create_from_format(_SHORTDATESTRING, \$_POST['{$fieldName}'])";
        $ret = $this->getXcEqualsOperator("\${$tableSoleName}{$ucfRightFiled}", $value, null, false, $t);
        $ret .= $this->getXcSetVar($tableName, $fieldName, "\${$tableSoleName}{$ucfRightFiled}->getTimestamp()", $t);

        return $ret;
    }

    /**
     *  @public function getXcCheckBoxOrRadioYNSetVar
     *  @param $tableName
     *  @param $fieldName
     * @param $t
     *  @return string
     */
    public function getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName, $t = '')
    {
        return $this->getXcSetVar($tableName, $fieldName, "((1 == \$_REQUEST['{$fieldName}']) ? '1' : '0')", $t);
    }

    /**
     *  @public function getXcMediaUploader
     *  @param $var
     *  @param $dirPath
     *  @param $moduleDirname
     * @param $t
     *  @return string
     */
    public function getXcMediaUploader($var, $dirPath, $moduleDirname, $t = '')
    {
        $mimetypes = $this->getXcGetConfig($moduleDirname, 'mimetypes');
        $maxsize = $this->getXcGetConfig($moduleDirname, 'maxsize');

        return "{$t}\${$var} = new XoopsMediaUploader({$dirPath}, 
													{$mimetypes}, 
													{$maxsize}, null, null);\n";
    }

    /**
     *  @public function getXcXoopsCaptcha
     *  @param $var
     *  @param $instance
     * @param $t
     *
     *  @return string
     */
    public function getXcGetInstance($var = '', $instance = '', $t = '')
    {
        return "{$t}\${$var} = {$instance}::getInstance();\n";
    }

    /**
     *  @public function getXcXoopsCaptcha
     *  @param $t
     *  @return string
     */
    public function getXcXoopsCaptcha($t = '')
    {
        return "{$t}\$xoopsCaptcha = XoopsCaptcha::getInstance();\n";
    }

    /**
     *  @public function getXcXoopsImgListArray
     *  @param $return
     *  @param $var
     *  @param $t
     *
     *  @return string
     */
    public function getXcXoopsImgListArray($return, $var, $t = '')
    {
        return "{$t}\${$return} = XoopsLists::getImgListAsArray( {$var} );\n";
    }

    /**
     *  @public function getXcGetConfig
     *  @param $moduleDirname
     *  @param $name
     *  @return string
     */
    public function getXcGetConfig($moduleDirname, $name)
    {
        return "\${$moduleDirname}->getConfig('{$name}')";
    }

    /**
     *  @public function getXcIdGetVar
     *  @param $lpFieldName
     * @param $t
     *  @return string
     */
    public function getXcIdGetVar($lpFieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['id'] = \$i;\n";
    }

    /**
     *  @public function getXcGetVarAll
     *  @param $lpFieldName
     *  @param $rpFieldName
     *  @param $tableName
     *  @param $fieldName
     * @param $t
     *  @return string
     */
    public function getXcGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n";
    }

    /**
     * @public function getXoopsHandlerInstance
     * @param        $moduleDirname
     *
     * @param string $t
     * @return string
     */
    public function getXoopsHandlerInstance($moduleDirname, $t = '')
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret = "{$t}// Get instance of module\n";
        $ret .= "{$t}\${$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();\n";

        return $ret;
    }

    /**
     * @public function getXoopsHandlerLine
     * @param $moduleDirname
     * @param $tableName
     * @param $t
     * @return string
     */
    public function getXoopsHandlerLine($moduleDirname, $tableName, $t = '')
    {
        return "{$t}\${$tableName}Handler = \${$moduleDirname}->getHandler('{$tableName}');\n";
    }

    /**
     *  @public function getXoopsClearHandler
     *  @param $left
     *  @param $anchor
     *  @param $var
     * @param $t
     *
     *  @return string
     */
    public function getXoopsClearHandler($left, $anchor, $var, $t = '')
    {
        return "{$t}\${$left}Handler = \${$anchor}->getHandler('{$var}');\n";
    }

    /**
     * @public function getXoopsFormSelectExtraOptions
     * @param string $varSelect
     * @param string $caption
     * @param string $var
     * @param array  $options
     * @param bool   $setExtra
     *
     * @param string $t
     * @return string
     */
    public function getXoopsFormSelectExtraOptions($varSelect = '', $caption = '', $var = '', $options = [], $setExtra = true, $t = '')
    {
        $ret = "{$t}\${$varSelect} = new XoopsFormSelect({$caption}, '{$var}', \${$var});\n";
        if (false !== $setExtra) {
            $ret .= "{$t}\${$varSelect}->setExtra('{$setExtra}');\n";
        }
        foreach ($options as $key => $value) {
            $ret .= "{$t}\${$varSelect}->addOption('{$key}', {$value});\n";
        }

        return $ret;
    }

    /**
     *  @public function getXcUnameFromId
     * @param        $left
     * @param        $value
     * @param string $t
     *
     * @return string
     */
    public function getXcUnameFromId($left, $value, $t = '')
    {
        return "{$t}\${$left} = XoopsUser::getUnameFromId({$value});\n";
    }

    /**
     *  @public function getXcFormatTimeStamp
     * @param        $left
     * @param        $value
     * @param string $format
     * @param string $t
     * @return string
     */
    public function getXcFormatTimeStamp($left, $value, $format = 's', $t = '')
    {
        return "{$t}\${$left} = formatTimeStamp({$value}, '{$format}');\n";
    }

    /**
     * @public function getXcTopicGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $tableNameTopic
     * @param        $fieldNameParent
     * @param        $fieldNameTopic
     * @param string $t
     * @return string
     */
    public function getXcTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic, $t = '')
    {
        $pTopic = TDMCreatePhpCode::getInstance();
        $ret = $pTopic->getPhpCodeCommentLine('Get Var', $fieldNameParent, $t);
        $fieldParent = $this->getXcGetVar('', "\${$tableName}All[\$i]", $fieldNameParent, true, '');
        $ret .= $this->getXcGet($rpFieldName, $fieldParent, '', $tableNameTopic . 'Handler', false, $t);
        $ret .= $this->getXcGetVar("\${$lpFieldName}['{$rpFieldName}']", "\${$rpFieldName}", $fieldNameTopic, false, $t);

        return $ret;
    }

    /**
     * @public function getXcParentTopicGetVar
     * @param        $moduleDirname
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $tableSoleNameTopic
     * @param        $tableNameTopic
     * @param        $fieldNameParent
     * @param string $t
     * @return string
     */
    public function getXcParentTopicGetVar($moduleDirname, $lpFieldName, $rpFieldName, $tableName, $tableSoleNameTopic, $tableNameTopic, $fieldNameParent, $t = '')
    {
        $pParentTopic = TDMCreatePhpCode::getInstance();
        $parentTopic = $pParentTopic->getPhpCodeCommentLine('Get', $tableNameTopic . ' Handler', $t . "\t");
        $parentTopic .= $this->getXoopsHandlerLine($moduleDirname, $tableNameTopic, $t . "\t");
        $elseGroups = $this->getXcEqualsOperator('$groups', 'XOOPS_GROUP_ANONYMOUS');
        $ret = $pParentTopic->getPhpCodeConditions("!isset(\${$tableNameTopic}Handler", '', '', $parentTopic, $elseGroups);
        $ret .= $this->getXcGetVarFromID("\${$lpFieldName}['{$rpFieldName}']", $tableNameTopic, $tableSoleNameTopic, $tableName, $fieldNameParent, $t);

        return $ret;
    }

    /**
     * @public function getXcGetVarFromID
     * @param        $left
     * @param        $anchor
     * @param        $var
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcGetVarFromID($left, $anchor, $var, $tableName, $fieldName, $t = '')
    {
        $pVarFromID = TDMCreatePhpCode::getInstance();
        $ret = $pVarFromID->getPhpCodeCommentLine('Get Var', $fieldName, $t);
        $getVarFromID = $this->getXcGetVar('', "\${$tableName}All[\$i]", $fieldName, true, '');
        $rightGet = $this->getXcAnchorFunction($anchor . 'Handler', 'get' . $var . 'FromId', $getVarFromID);
        $ret .= $this->getXcEqualsOperator($left, $rightGet, null, false, $t);

        return $ret;
    }

    /**
     * @public function getXcUploadImageGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        $pUploadImage = TDMCreatePhpCode::getInstance();
        $ret = $pUploadImage->getPhpCodeCommentLine('Get Var', $fieldName, $t);
        $ret .= $this->getXcGetVar($fieldName, "\${$tableName}All[\$i]", $fieldName, false, '');
        $ret .= $pUploadImage->getPhpCodeTernaryOperator("{$lpFieldName}['{$rpFieldName}']", "\${$fieldName}", "\${$fieldName}", "'blank.gif'", $t);

        return $ret;
    }

    /**
     *  @public function getXcUrlFileGetVar
     *  @param $lpFieldName
     *  @param $rpFieldName
     *  @param $tableName
     *  @param $fieldName
     *  @return string
     */
    public function getXcUrlFileGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return $this->getXcGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName);
    }

    /**
     * @public function getXcTextAreaGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcTextAreaGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        $phpCodeTextArea = TDMCreatePhpCode::getInstance();
        $getVar = $this->getXcGetVar('', "\${$tableName}All[\$i]", $fieldName, true, '');

        return "{$t}" . $phpCodeTextArea->getPhpCodeStripTags("{$lpFieldName}['{$rpFieldName}']", $getVar, false, $t);
    }

    /**
     * @public function getXcSelectUserGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcSelectUserGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['{$rpFieldName}'] = XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /**
     * @public function getXcTextDateSelectGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcTextDateSelectGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['{$rpFieldName}'] = formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /**
     * @public function getXcUserHeader
     * @param        $moduleDirname
     * @param        $tableName
     * @param string $t
     * @return string
     */
    public function getXcXoopsOptionTemplateMain($moduleDirname, $tableName, $t = '')
    {
        return "{$t}\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';\n";
    }

    /**
     *  @public function getXcUserHeader
     *  @param $moduleDirname
     *  @param $tableName
     *  @return string
     */
    public function getXcUserHeader($moduleDirname, $tableName)
    {
        $phpCodeUserHeader = TDMCreatePhpCode::getInstance();
        $ret = $phpCodeUserHeader->getPhpCodeIncludeDir('__DIR__', 'header');
        $ret .= $this->getXcXoopsOptionTemplateMain($moduleDirname, $tableName);
        $ret .= $phpCodeUserHeader->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);

        return $ret;
    }

    /**
     *  @public function getXcPermissionsHeader
     *  @param null
     * @return string
     */
    public function getXcPermissionsHeader()
    {
        $phpCodePHeader = TDMCreatePhpCode::getInstance();
        $ret = $phpCodePHeader->getPhpCodeCommentLine('Permission');
        $ret .= $phpCodePHeader->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret .= $this->getXcEqualsOperator('$gpermHandler', "xoops_getHandler('groupperm')", true);
        $groups = $this->getXcEqualsOperator('$groups', '$xoopsUser->getGroups()');
        $elseGroups = $this->getXcEqualsOperator('$groups', 'XOOPS_GROUP_ANONYMOUS');
        $ret .= $phpCodePHeader->getPhpCodeConditions('is_object($xoopsUser)', '', $type = '', $groups, $elseGroups);

        return $ret;
    }

    /**
     *  @public function getXcGetFieldId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcGetFieldId($fields)
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
     *  @public function getXcGetFieldName
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcGetFieldName($fields)
    {
        $fieldName = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
        }

        return $fieldName;
    }

    /**
     *  @public function getXcGetFieldParentId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcGetFieldParentId($fields)
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
     * @public function getXcUserSaveElements
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $fields
     * @return string
     */
    public function getXcUserSaveElements($moduleDirname, $tableName, $tableSoleName, $fields)
    {
        $axCodeUserSave = AdminXoopsCode::getInstance();
        $ret = '';
        $fieldMain = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $this->getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $axCodeUserSave->getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $axCodeUserSave->getXcUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= $this->getXcTextDateSelectSetVar($tableName, $tableSoleName, $fieldName);
            } else {
                $ret .= $this->getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
            }
        }

        return $ret;
    }

    /**
     * @public function getXcXoopsRequest
     * @param string $left
     * @param string $var1
     * @param string $var2
     * @param string $type
     * @param bool   $metod
     * @param string $t
     * @return string
     */
    public function getXcXoopsRequest($left = '', $var1 = '', $var2 = '', $type = 'String', $metod = false, $t = '')
    {
        $ret = '';
        $intVars = ('' != $var2) ? "'{$var1}', {$var2}" : "'{$var1}'";
        if ('String' === $type) {
            $ret .= "{$t}\${$left} = XoopsRequest::getString('{$var1}', '{$var2}');\n";
        } elseif ('Int' === $type) {
            $ret .= "{$t}\${$left} = XoopsRequest::getInt({$intVars});\n";
        } elseif ('Int' === $type && false !== $metod) {
            $ret .= "{$t}\${$left} = XoopsRequest::getInt({$intVars}, '{$metod}');\n";
        }

        return $ret;
    }

    /**
     * @public function getXcTplAssign
     *
     * @param        $tplString
     * @param        $phpRender
     * @param bool   $leftIsString
     *
     * @param string $t
     * @return string
     */
    public function getXcTplAssign($tplString, $phpRender, $leftIsString = true, $t = '')
    {
        $assign = "{$t}\$GLOBALS['xoopsTpl']->assign(";
        if (false === $leftIsString) {
            $ret = $assign . "{$tplString}, {$phpRender});\n";
        } else {
            $ret = $assign . "'{$tplString}', {$phpRender});\n";
        }

        return $ret;
    }

    /**
     * @public function getXcXoopsTplAppend
     *
     * @param        $tplString
     * @param        $phpRender
     *
     * @param string $t
     * @return string
     */
    public function getXcXoopsTplAppend($tplString, $phpRender, $t = '')
    {
        return "{$t}\$GLOBALS['xoopsTpl']->append('{$tplString}', {$phpRender});\n";
    }

    /**
     * @public function getXcXoopsTplAppendByRef
     *
     * @param        $tplString
     * @param        $phpRender
     *
     * @param string $t
     * @return string
     */
    public function getXcXoopsTplAppendByRef($tplString, $phpRender, $t = '')
    {
        return "{$t}\$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', {$phpRender});\n";
    }

    /**
     * @public function getXcPath
     *
     * @param        $directory
     * @param        $filename
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcPath($directory, $filename, $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php');\n";
        } else {
            $ret = "\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php')";
        }

        return $ret;
    }

    /**
     * @public function getXcTplDisplay
     *
     * @param string $displayTpl
     * @param string $t
     * @param bool   $usedoublequotes
     * @return string
     */
    public function getXcTplDisplay($displayTpl = '{$templateMain}', $t = '', $usedoublequotes = true)
    {
        if ($usedoublequotes) {
            return "{$t}\$GLOBALS['xoopsTpl']->display(\"db:{$displayTpl}\");\n";
        }

        return "{$t}\$GLOBALS['xoopsTpl']->display('db:" . $displayTpl . "');\n";
    }

    /**
     * @public function getXcGetInfo
     *
     * @param        $left
     * @param        $string
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcGetInfo($left, $string, $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$left} = \$GLOBALS['xoopsModule']->getInfo('{$string}');\n";
        } else {
            $ret = "\$GLOBALS['xoopsModule']->getInfo('{$string}')";
        }

        return $ret;
    }

    /**
     * @public function getXcAddRight
     *
     * @param        $anchor
     * @param string $permString
     * @param string $var
     * @param string $groups
     * @param string $mid
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcAddRight($anchor, $permString = '', $var = '', $groups = '', $mid = '', $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$anchor}->addRight('{$permString}', {$var}, {$groups}, {$mid});\n";
        } else {
            $ret = "\${$anchor}->addRight('{$permString}', {$var}, {$groups}, {$mid})";
        }

        return $ret;
    }

    /**
     * @public function getXcCheckRight
     *
     * @param        $anchor
     * @param string $permString
     * @param string $var
     * @param string $groups
     * @param string $mid
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcCheckRight($anchor, $permString = '', $var = '', $groups = '', $mid = '', $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}{$anchor}->checkRight('{$permString}', {$var}, {$groups}, {$mid});\n";
        } else {
            $ret = "{$anchor}->checkRight('{$permString}', {$var}, {$groups}, {$mid})";
        }

        return $ret;
    }

    /**
     * @public function getXcObjHandlerCreate
     *
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getXcObjHandlerCreate($tableName, $t = '')
    {
        return "{$t}\${$tableName}Obj = \${$tableName}Handler->create();\n";
    }

    /**
     * @public function getXcObjHandlerCount
     *
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getXcObjHandlerCount($tableName, $t = '')
    {
        $ucfTableName = ucfirst($tableName);
        $ret = "{$t}\${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();\n";

        return $ret;
    }

    /**
     *  @public function getXcClearCount
     * @param  $left
     *  @param $anchor
     *  @param  $params
     *  @param $t
     *
     *  @return string
     */
    public function getXcClearHandlerCount($left, $anchor = '', $params = '', $t = '')
    {
        $ret = "{$t}\${$left} = \${$anchor}Handler->getCount({$params});\n";

        return $ret;
    }

    /**
     * @public function getXcObjHandlerAll
     *
     * @param        $tableName
     * @param string $fieldMain
     * @param string $start
     * @param string $limit
     *
     * @param string $t
     * @return string
     */
    public function getXcObjHandlerAll($tableName, $fieldMain = '', $start = '0', $limit = '0', $t = '')
    {
        $ucfTableName = ucfirst($tableName);
        $startLimit = ('0' != $limit) ? "{$start}, {$limit}" : '0';
        $params = ('' != $fieldMain) ? "{$startLimit}, '{$fieldMain}'" : $startLimit;
        $ret = "{$t}\${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}({$params});\n";

        return $ret;
    }

    /**
     * @public function getXcClearHandlerAll
     * @param        $left
     * @param string $anchor
     * @param string $params
     * @param string $t
     * @return string
     */
    public function getXcClearHandlerAll($left, $anchor = '', $params = '', $t = '')
    {
        $ret = "{$t}\${$left} = \${$anchor}Handler->getAll({$params});\n";

        return $ret;
    }

    /**
     * @public function getXcGetValues
     *
     * @param        $tableName
     * @param        $tableSoleName
     *
     * @param string $index
     * @param bool   $noArray
     * @param string $t
     * @return string
     */
    public function getXcGetValues($tableName, $tableSoleName, $index = 'i', $noArray = false, $t = '')
    {
        $index = '' !== $index ? $index : 'i';
        $ucfTableName = ucfirst($tableName);
        if (!$noArray) {
            $ret = "{$t}\${$tableSoleName} = \${$tableName}All[\${$index}]->getValues{$ucfTableName}();\n";
        } else {
            $ret = "{$t}\${$tableSoleName} = \${$tableName}->getValues{$ucfTableName}();\n";
        }

        return $ret;
    }

    /**
     * @public function getXcSetVarsObjects
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $fields
     * @return string
     */
    public function getXcSetVarsObjects($moduleDirname, $tableName, $tableSoleName, $fields)
    {
        $axCode = AdminXoopsCode::getInstance();
        $ret = '';
        $fieldMain = '';
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
                        $ret .= $this->getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $axCode->getAxcImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $axCode->getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName, true);
                        break;
                    case 13:
                        $ret .= $axCode->getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $axCode->getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $this->getXcTextDateSelectSetVar($tableName, $tableSoleName, $fieldName);
                        break;
                    default:
                        $ret .= $this->getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     * @public function getXcSecurity
     *
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getXcSecurity($tableName, $t = '')
    {
        $phpCodeSecurity = TDMCreatePhpCode::getInstance();
        $securityError = $this->getXcSecurityErrors();
        $implode = $phpCodeSecurity->getPhpCodeImplode(',', $securityError);
        $content = "{$t}\t" . $this->getXcRedirectHeader($tableName, '', 3, $implode, $t);
        $securityCheck = $this->getXcSecurityCheck();

        return $phpCodeSecurity->getPhpCodeConditions('!' . $securityCheck, '', '', $content, $t);
    }

    /**
     * @public function getXcInsertData
     * @param        $tableName
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getXcInsertData($tableName, $language, $t = '')
    {
        $phpCodeInsertData = TDMCreatePhpCode::getInstance();
        $content = "{$t}\t" . $this->getXcRedirectHeader($tableName, '?op=list', 2, "{$language}FORM_OK");
        $handlerInsert = $this->getXcHandler($tableName, $tableName, false, true, false, 'Obj');

        return $phpCodeInsertData->getPhpCodeConditions($handlerInsert, '', '', $content, $t);
    }

    /**
     * @public function getXcRedirectHeader
     * @param        $directory
     * @param        $options
     * @param        $numb
     * @param        $var
     * @param bool   $isString
     *
     * @param string $t
     * @return string
     */
    public function getXcRedirectHeader($directory, $options, $numb, $var, $isString = true, $t = '')
    {
        $ret = '';
        if (!$isString) {
            $ret = "{$t}redirect_header({$directory}, {$numb}, {$var});\n";
        } else {
            $ret = "{$t}redirect_header('{$directory}.php{$options}', {$numb}, {$var});\n";
        }

        return $ret;
    }

    /**
     * @public function getXcXoopsConfirm
     * @param        $tableName
     * @param        $language
     * @param        $fieldId
     * @param        $fieldMain
     * @param string $options
     *
     * @param string $t
     * @return string
     */
    public function getXcXoopsConfirm($tableName, $language, $fieldId, $fieldMain, $options = 'delete', $t = '')
    {
        $stuOptions = mb_strtoupper($options);
        $ccFieldId = TDMCreateFile::getInstance()->getCamelCase($fieldId, false, true);
        $phpXoopsConfirm = TDMCreatePhpCode::getInstance();
        $array = "array('ok' => 1, '{$fieldId}' => \${$ccFieldId}, 'op' => '{$options}')";
        $server = $phpXoopsConfirm->getPhpCodeGlobalsVariables('REQUEST_URI', 'SERVER');
        $getVar = $this->getXcGetVar('', $tableName . 'Obj', $fieldMain, true, '');
        $sprintf = $phpXoopsConfirm->getPhpCodeSprintf($language . 'FORM_SURE_' . $stuOptions, $getVar);
        $ret = "{$t}xoops_confirm({$array}, {$server}, {$sprintf});\n";

        return $ret;
    }

    /**
     * @public function getXcAddStylesheet
     * @param string $style
     *
     * @param string $t
     * @return string
     */
    public function getXcAddStylesheet($style = 'style', $t = '')
    {
        return "{$t}\$GLOBALS['xoTheme']->addStylesheet( \${$style}, null );\n";
    }

    /**
     *  @public function getXcSecurityCheck
     *  @param $denial
     *  @return bool
     */
    public function getXcSecurityCheck($denial = '')
    {
        return "{$denial}\$GLOBALS['xoopsSecurity']->check()";
    }

    /**
     *  @public function getXcSecurityErrors
     *  @param null
     *  @return string
     */
    public function getXcSecurityErrors()
    {
        return "\$GLOBALS['xoopsSecurity']->getErrors()";
    }

    /**
     * @public function getXcHtmlErrors
     *
     * @param        $tableName
     * @param bool   $isParam
     * @param string $obj
     *
     * @param string $t
     * @return string
     */
    public function getXcHtmlErrors($tableName, $isParam = false, $obj = 'Obj', $t = '')
    {
        $ret = '';
        if ($isParam) {
            $ret = "\${$tableName}{$obj}->getHtmlErrors()";
        } else {
            $ret = "{$t}\${$tableName}{$obj} = \${$tableName}->getHtmlErrors();";
        }

        return $ret;
    }

    /**
     * @public function getXcObjHandlerCount
     *
     * @param        $left
     * @param        $tableName
     * @param string $obj
     *
     * @param string $t
     * @return string
     */
    public function getXcGetForm($left, $tableName, $obj = '', $t = '')
    {
        $ucfTableName = ucfirst($tableName);

        return "{$t}\${$left} = \${$tableName}{$obj}->getForm{$ucfTableName}();\n";
    }

    /**
     * @public function getXcGet
     *
     * @param        $left
     * @param        $var
     * @param string $obj
     * @param string $handler
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcGet($left, $var, $obj = '', $handler = 'Handler', $isParam = false, $t = '')
    {
        $ret = '';
        if ($isParam) {
            $ret = "\${$left}{$handler}->get(\${$var})";
        } else {
            $ret = "{$t}\${$left}{$obj} = \${$handler}->get(\${$var});\n";
        }

        return $ret;
    }

    /**
     *  @public function getXcHandler
     *
     *  @param $left
     *  @param $var
     *  @param $obj
     *  @param $handler
     *
     *  @return string
     */
    public function getXcInsert($left, $var, $obj = '', $handler = 'Handler')
    {
        return "\${$left}{$handler}->insert(\${$var}{$obj})";
    }

    /**
     * @public   function getXcDelete
     *
     * @param        $left
     * @param        $var
     * @param string $obj
     * @param string $handler
     * @return string
     */
    public function getXcDelete($left, $var, $obj = '', $handler = 'Handler')
    {
        return "\${$left}{$handler}->delete(\${$var}{$obj})";
    }

    /**
     * @public function getXcHandler
     *
     * @param        $left
     * @param        $var
     *
     * @param bool   $get
     * @param bool   $insert
     * @param bool   $delete
     * @param string $obj
     * @param string $t
     * @return string
     */
    public function getXcHandler($left, $var, $get = false, $insert = false, $delete = false, $obj = '', $t = '')
    {
        $ret = '';
        if ($get) {
            $ret = "{$t}\${$left}Handler->get(\${$var});";
        } elseif ($insert && ('' != $obj)) {
            $ret = "{$t}\${$left}Handler->insert(\${$var}{$obj});";
        } elseif ($delete && ('' != $obj)) {
            $ret = "{$t}\${$left}Handler->delete(\${$var}{$obj});";
        }

        return $ret;
    }

    /**
     * @public function getTopicGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $tableNameTopic
     * @param        $fieldNameParent
     * @param        $fieldNameTopic
     * @param string $t
     * @return string
     */
    public function getTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic, $t = '')
    {
        $ret = TDMCreatePhpCode::getInstance()->getPhpCodeCommentLine('Get Var', $fieldNameParent, $t);
        $paramGet = $this->getXcGetVar('', "\${$tableName}All[\$i]", $fieldNameParent, true, '');
        $ret .= $this->getXcGet($rpFieldName, $paramGet, '', $tableNameTopic . 'Handler', false, $t);
        $ret .= $this->getXcGetVar("\${$lpFieldName}['{$rpFieldName}']", "\${$rpFieldName}", $fieldNameTopic, false, $t);

        return $ret;
    }

    /**
     * @public function getUploadImageGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        $pImageGetVar = TDMCreatePhpCode::getInstance();
        $ret = $pImageGetVar->getPhpCodeCommentLine('Get Var', $fieldName, $t);
        $ret .= $this->getXcGetVar($fieldName, "\${$tableName}All[\$i]", $fieldName, false, '');
        $ret .= $pImageGetVar->getPhpCodeTernaryOperator('uploadImage', "\${$fieldName}", "\${$fieldName}", "'blank.gif'", $t);
        $ret .= $this->getXcEqualsOperator("${$lpFieldName}['{$rpFieldName}']", '$uploadImage', null, false, $t);

        return $ret;
    }

    /**
     *  @public function getXcSaveFieldId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcSaveFieldId($fields)
    {
        $fieldId = '';
        foreach (array_keys($fields) as $f) {
            if (0 == $f) {
                $fieldId = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldId;
    }

    /**
     *  @public function getXcSaveFieldMain
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcSaveFieldMain($fields)
    {
        $fieldMain = '';
        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldMain;
    }

    /**
     * @public function getXcSaveElements
     *
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param        $fields
     *
     * @param string $t
     * @return string
     */
    public function getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $t = '')
    {
        $axCodeSaveElements = AdminXoopsCode::getInstance();
        $ret = '';
        $fieldMain = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $t . $this->getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $axCodeSaveElements->getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $axCodeSaveElements->getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= $t . $this->getXcTextDateSelectSetVar($tableName, $tableSoleName, $fieldName);
            } else {
                if ((0 != $f) && 1 == $tableAutoincrement) {
                    $ret .= $t . $this->getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
                } elseif ((0 == $f) && 0 == $tableAutoincrement) {
                    $ret .= $t . $this->getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
                }
            }
        }

        return $ret;
    }

    /**
     * @public function getXcPageNav
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getXcPageNav($tableName, $t = '')
    {
        $phpCodePageNav = TDMCreatePhpCode::getInstance();
        $classXCode = ClassXoopsCode::getInstance();
        $ret = $phpCodePageNav->getPhpCodeCommentLine('Display Navigation', null, $t);
        $condition = $phpCodePageNav->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/pagenav', true, false, 'include', $t . "\t");
        $condition .= $classXCode->getClassXoopsPageNav('pagenav', $tableName . 'Count', 'limit', 'start', 'start', "'op=list&limit=' . \$limit", false, $t . "\t");
        $condition .= $this->getXcTplAssign('pagenav', '$pagenav->renderNav(4)', true, $t . "\t");
        $ret .= $phpCodePageNav->getPhpCodeConditions("\${$tableName}Count", ' > ', '$limit', $condition, false, $t);

        return $ret;
    }
}
