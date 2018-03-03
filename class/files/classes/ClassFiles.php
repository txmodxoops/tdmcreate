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
 * tc module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: ClassFiles.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class ClassFiles.
 */
class ClassFiles extends TDMCreateFile
{
    /**
     *  @public function constructor
     *
     *  @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  @static function getInstance
     *
     *  @param null
     *
     * @return ClassFiles
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
     *
     * @param string $module
     * @param string $table
     * @param mixed  $tables
     * @param        $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     *  @private function getInitVar
     *
     *  @param string $fieldName
     *  @param string $type
     *
     * @return string
     */
    private function getInitVar($fieldName, $type = 'INT')
    {
        $cc = ClassXoopsCode::getInstance();

        return $cc->getClassInitVar($fieldName, $type);
    }

    /**
     *  @private function getInitVars
     *
     *  @param array $fields
     *
     * @return string
     */
    private function getInitVars($fields)
    {
        $tc = TDMCreateHelper::getInstance();
        $ret = '';
        // Creation of the initVar functions list
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldType = $fields[$f]->getVar('field_type');
            if ($fieldType > 1) {
                $fType = $tc->getHandler('fieldtype')->get($fieldType);
                $fieldTypeName = $fType->getVar('fieldtype_name');
            } else {
                $fieldType = null;
            }
            switch ($fieldType) {
                case 2:
                case 3:
                case 4:
                case 5:
                    $ret .= $this->getInitVar($fieldName, 'INT');
                    break;
                case 6:
                    $ret .= $this->getInitVar($fieldName, 'FLOAT');
                    break;
                case 7:
                case 8:
                    $ret .= $this->getInitVar($fieldName, 'DECIMAL');
                    break;
                case 10:
                    $ret .= $this->getInitVar($fieldName, 'ENUM');
                    break;
                case 11:
                    $ret .= $this->getInitVar($fieldName, 'EMAIL');
                    break;
                case 12:
                    $ret .= $this->getInitVar($fieldName, 'URL');
                    break;
                case 13:
                case 14:
                    $ret .= $this->getInitVar($fieldName, 'TXTBOX');
                    break;
                case 15:
                case 16:
                case 17:
                case 18:
                    $ret .= $this->getInitVar($fieldName, 'TXTAREA');
                    break;
                case 19:
                case 20:
                case 21:
                case 22:
                case 23:
                    $ret .= $this->getInitVar($fieldName, 'LTIME');
                    break;
            }
        }

        return $ret;
    }

    /**
     * @private  function getClassObject
     * @param $module
     * @param $table
     * @param $fields
     * @return string
     */
    private function getClassObject($module, $table, $fields)
    {
        $tc = TDMCreateHelper::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ret = $pc->getPhpCodeDefined();
        $ret .= $pc->getPhpCodeCommentMultiLine(['Class Object' => $ucfModuleDirname . $ucfTableName]);
        $cCl = '';

        $fieldInForm = [];
        $fieldElementId = [];
        $optionsFieldName = [];
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $fieldInForm[] = $fields[$f]->getVar('field_inform');
            $fieldElements = $tc->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');
            $rpFieldName = $this->getRightString($fieldName);
            if (in_array(5, $fieldElementId)) {
                if (count($rpFieldName) % 5) {
                    $optionsFieldName[] = "'".$rpFieldName."'";
                } else {
                    $optionsFieldName[] = "'".$rpFieldName."'\n";
                }
            }
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
        }
        if (in_array(5, $fieldElementId) > 1) {
            $optionsElements = implode(', ', $optionsFieldName);
            $cCl .= $pc->getPhpCodeCommentMultiLine(['Options' => '']);
            $options = $pc->getPhpCodeArray('', $optionsFieldName, true);
            $cCl .= $pc->getPhpCodeVariableClass('private', 'options', $options);
        }
        unset($optionsFieldName);

        $cCl .= $pc->getPhpCodeCommentMultiLine(['Constructor' => '', '' => '', '@param' => 'null'], "\t");
        $constr = $this->getInitVars($fields);
        $cCl .= $pc->getPhpCodeFunction('__construct', '', $constr, 'public ', false, "\t");
        $arrayGetInstance = ['@static function' => '&getInstance', '' => '', '@param' => 'null'];
        $cCl .= $pc->getPhpCodeCommentMultiLine($arrayGetInstance, "\t");
        $getInstance = $pc->getPhpCodeVariableClass('static', 'instance', 'false', "\t\t");
        $instance = $xc->getXcEqualsOperator('$instance', 'new self()', null, false, "\t\t\t");
        $getInstance .= $pc->getPhpCodeConditions('!$instance', '', '', $instance, false, "\t\t");
        $cCl .= $pc->getPhpCodeFunction('getInstance', '', $getInstance, 'public static ', false, "\t");

        $cCl .= $this->getNewInsertId($table);
        $cCl .= $this->getFunctionForm($module, $table, $fieldId, $fieldInForm);
        $cCl .= $this->getValuesInObject($moduleDirname, $table, $fields);
        $cCl .= $this->getToArrayInObject($table);

        if (in_array(5, $fieldElementId) > 1) {
            $cCl .= $this->getOptionsCheck($table);
        }
        unset($fieldElementId);

        $ret .= $pc->getPhpCodeClass($ucfModuleDirname.$ucfTableName, $cCl, 'XoopsObject');

        return $ret;
    }

    /**
     *  @private function getNewInsertId
     *
     *  @param $table
     *
     * @return string
     */
    private function getNewInsertId($table)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $tableName = $table->getVar('table_name');
        $ucfTableName = ucfirst($tableName);
        $ret = $pc->getPhpCodeCommentMultiLine(['The new inserted' => '$Id', '@return' => 'inserted id'], "\t");
        $getInsertedId = $xc->getXcEqualsOperator('$newInsertedId', "\$GLOBALS['xoopsDB']->getInsertId()", null, false, "\t\t");
        $getInsertedId .= $this->getSimpleString('return $newInsertedId;', "\t\t");

        $ret .= $pc->getPhpCodeFunction('getNewInsertedId'.$ucfTableName, '', $getInsertedId, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @private function getFunctionForm
     *
     * @param string $module
     * @param string $table
     *
     * @param        $fieldId
     * @param        $fieldInForm
     * @return string
     */
    private function getFunctionForm($module, $table, $fieldId, $fieldInForm)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $cc = ClassXoopsCode::getInstance();
        $fe = ClassFormElements::getInstance();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $tableCategory = $table->getVar('table_category');
        $tablePermissions = $table->getVar('table_permissions');
        $ucfTableName = ucfirst($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);
        $language = $this->getLanguage($moduleDirname, 'AM');
        $fe->initForm($module, $table);
        $ret = $pc->getPhpCodeCommentMultiLine(['@public function' => 'getForm', '@param bool' => '$action', '@return' => 'XoopsThemeForm'], "\t");
        $action = $xc->getXcEqualsOperator('$action', "\$_SERVER['REQUEST_URI']", null, false, "\t\t\t");
        $ucfModuleDirname = ucfirst($moduleDirname);
        $getForm = $xc->getXcGetInstance("{$moduleDirname}", "{$ucfModuleDirname}Helper", "\t\t");
        //$getForm .= $pc->getPhpCodeConditions('$action', ' === ', 'false', $action, false, "\t\t");
		$getForm .= $pc->getPhpCodeConditions('false', ' === ', '$action', $action, false, "\t\t");
        $xUser = $pc->getPhpCodeGlobals('xoopsUser');
        $xModule = $pc->getPhpCodeGlobals('xoopsModule');
        if (1 != $tableCategory/* && (1 == $tablePermissions)*/) {
            $getForm .= $pc->getPhpCodeCommentLine('Permissions for', 'uploader', "\t\t");
            $getForm .= $xc->getXcEqualsOperator('$gpermHandler', "xoops_getHandler('groupperm')", null, true, "\t\t");
            $getForm .= $pc->getPhpCodeTernaryOperator('groups', 'is_object('.$xUser.')', $xUser.'->getGroups()', 'XOOPS_GROUP_ANONYMOUS', "\t\t");
            $checkRight = $xc->getXcCheckRight('$gpermHandler', $permString = '', 32, '$groups', $xModule.'->getVar(\'mid\')', true);
            $ternaryOperator = $pc->getPhpCodeTernaryOperator('permissionUpload', $checkRight, 'true', 'false', "\t\t\t\t");
            $permissionUpload = $xc->getXcEqualsOperator('$permissionUpload', 'true', null, false, "\t\t\t\t");
            $ternOperator = $pc->getPhpCodeRemoveCarriageReturn($ternaryOperator, '', "\r");
            $if = $pc->getPhpCodeConditions('!'.$xUser.'->isAdmin('.$xModule.'->mid())', '', '', $ternaryOperator, $permissionUpload, "\t\t\t");
            $getForm .= $pc->getPhpCodeConditions($xUser, '', '', $if, $ternOperator, "\t\t");
        }
        $getForm .= $pc->getPhpCodeCommentLine('Title', '', "\t\t");
        $getForm .= $pc->getPhpCodeTernaryOperator('title', '$this->isNew()', "sprintf({$language}{$stuTableSoleName}_ADD)", "sprintf({$language}{$stuTableSoleName}_EDIT)", "\t\t");
        $getForm .= $pc->getPhpCodeCommentLine('Get Theme', 'Form', "\t\t");
        $getForm .= $xc->getXcLoad('XoopsFormLoader', "\t\t");
        $getForm .= $cc->getClassXoopsThemeForm('form', 'title', 'form', 'action', 'post');
        $getForm .= $cc->getClassSetExtra('form', "'enctype=\"multipart/form-data\"'");
        $getForm .= $fe->renderElements();

        if (in_array(1, $fieldInForm)) {
            if (1 == $table->getVar('table_permissions')) {
                $getForm .= $this->getPermissionsInForm($moduleDirname, $fieldId);
            }
        }
        $getForm .= $pc->getPhpCodeCommentLine('To Save', '', "\t\t");
        //$hiddenSave = $cc->getClassXoopsFormHidden('', "'op'", "'save'", true, false);
        $getForm .= $cc->getClassAddElement('form', "new XoopsFormHidden('op', 'save')");
        //$buttonSend = $cc->getClassXoopsFormButton('', '', 'submit', '_SUBMIT', 'submit', true);
        $getForm .= $cc->getClassAddElement('form', "new XoopsFormButton('', 'submit', _SUBMIT, 'submit')");
        $getForm .= $this->getSimpleString('return $form;', "\t\t");

        $ret .= $pc->getPhpCodeFunction('getForm'.$ucfTableName, '$action = false', $getForm, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @private function getPermissionsInForm
     *
     *  @param string $moduleDirname
     *  @param string $fieldId
     *
     * @return string
     */
    private function getPermissionsInForm($moduleDirname, $fieldId)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $cc = ClassXoopsCode::getInstance();
        $permissionApprove = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_APPROVE');
        $permissionSubmit = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_SUBMIT');
        $permissionView = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_VIEW');
        $ret = $pc->getPhpCodeCommentLine('Permissions', '', "\t\t");
        $ret .= $xc->getXcEqualsOperator('$memberHandler', "xoops_getHandler('member')", null, false, "\t\t");
        $ret .= $xc->getXcEqualsOperator('$groupList', '$memberHandler->getGroupList()', null, false, "\t\t");
        $ret .= $xc->getXcEqualsOperator('$gpermHandler', "xoops_getHandler('groupperm')", null, false, "\t\t");
        $ret .= $pc->getPhpCodeArrayType('fullList', 'keys', 'groupList', null, false, "\t\t");
        $fId = $xc->getXcGetVar('', 'this', $fieldId, true);
        $mId = $xc->getXcGetVar('', "GLOBALS['xoopsModule']", 'mid', true);
        $ifGroups = $xc->getXcGetGroupIds('groupsIdsApprove', 'gpermHandler', "'{$moduleDirname}_approve'", $fId, $mId, "\t\t\t");
        $ifGroups .= $pc->getPhpCodeArrayType('groupsIdsApprove', 'values', 'groupsIdsApprove', null, false, "\t\t\t");
        $ifGroups .= $cc->getClassXoopsFormCheckBox('groupsCanApproveCheckbox', "{$permissionApprove}", 'groups_approve[]', '$groupsIdsApprove', false, "\t\t\t");
        $ifGroups .= $xc->getXcGetGroupIds('groupsIdsSubmit', 'gpermHandler', "'{$moduleDirname}_submit'", $fId, $mId, "\t\t\t");
        $ifGroups .= $pc->getPhpCodeArrayType('groupsIdsSubmit', 'values', 'groupsIdsSubmit', null, false, "\t\t\t");
        $ifGroups .= $cc->getClassXoopsFormCheckBox('groupsCanSubmitCheckbox', "{$permissionSubmit}", 'groups_submit[]', '$groupsIdsSubmit', false, "\t\t\t");
        $ifGroups .= $xc->getXcGetGroupIds('groupsIdsView', 'gpermHandler', "'{$moduleDirname}_view'", $fId, $mId, "\t\t\t");
        $ifGroups .= $pc->getPhpCodeArrayType('groupsIdsView', 'values', 'groupsIdsView', null, false, "\t\t\t");
        $ifGroups .= $cc->getClassXoopsFormCheckBox('groupsCanViewCheckbox', "{$permissionView}", 'groups_view[]', '$groupsIdsView', false, "\t\t\t");

        $else = $cc->getClassXoopsFormCheckBox('groupsCanApproveCheckbox', "{$permissionApprove}", 'groups_approve[]', '$fullList', false, "\t\t\t");
        $else .= $cc->getClassXoopsFormCheckBox('groupsCanSubmitCheckbox', "{$permissionSubmit}", 'groups_submit[]', '$fullList', false, "\t\t\t");
        $else .= $cc->getClassXoopsFormCheckBox('groupsCanViewCheckbox', "{$permissionView}", 'groups_view[]', '$fullList', false, "\t\t\t");

        $ret .= $pc->getPhpCodeConditions('!$this->isNew()', null, null, $ifGroups, $else, "\t\t");
        $ret .= $pc->getPhpCodeCommentLine('To Approve', '', "\t\t");
        $ret .= $cc->getClassAddOptionArray('groupsCanApproveCheckbox', '$groupList');
        $ret .= $cc->getClassAddElement('form', '$groupsCanApproveCheckbox');
        $ret .= $pc->getPhpCodeCommentLine('To Submit', '', "\t\t");
        $ret .= $cc->getClassAddOptionArray('groupsCanSubmitCheckbox', '$groupList');
        $ret .= $cc->getClassAddElement('form', '$groupsCanSubmitCheckbox');
        $ret .= $pc->getPhpCodeCommentLine('To View', '', "\t\t");
        $ret .= $cc->getClassAddOptionArray('groupsCanViewCheckbox', '$groupList');
        $ret .= $cc->getClassAddElement('form', '$groupsCanViewCheckbox');

        return $ret;
    }

    /**
     * @private  function getValuesInObject
     *
     * @param $moduleDirname
     * @param $table
     * @param $fields
     * @return string
     * @internal param $null
     */
    private function getValuesInObject($moduleDirname, $table, $fields)
    {
        $tc = TDMCreateHelper::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfTableName = ucfirst($table->getVar('table_name'));
        $ret = $pc->getPhpCodeCommentMultiLine(['Get' => 'Values', '@param null $keys' => '', '@param null $format' => '', '@param null$maxDepth' => '', '@return' => 'array'], "\t");
        $ucfModuleDirname = ucfirst($moduleDirname);
        $getValues = $xc->getXcGetInstance("{$moduleDirname}", "{$ucfModuleDirname}Helper", "\t\t");
        $getValues .= $xc->getXcEqualsOperator('$ret', '$this->getValues($keys, $format, $maxDepth)', null, false, "\t\t");

        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName = $this->getRightString($fieldName);
            switch ($fieldElement) {
                case 3:
                case 4:
                    $getValues .= $pc->getPhpCodeStripTags("ret['{$rpFieldName}']", "\$this->getVar('{$fieldName}')", false, "\t\t");
                break;
                case 8:
                    $getValues .= $xc->getXcUnameFromId("ret['{$rpFieldName}']", "\$this->getVar('{$fieldName}')", "\t\t");
                break;
                case 15:
                    $getValues .= $xc->getXcFormatTimeStamp("ret['{$rpFieldName}']", "\$this->getVar('{$fieldName}')", 's', "\t\t");
                break;
                default:
                    if ($fieldElement > 15) {
                        $fieldElements = $tc->getHandler('fieldelements')->get($fieldElement);
                        $fieldElementMid = $fieldElements->getVar('fieldelement_mid');
                        $fieldElementTid = $fieldElements->getVar('fieldelement_tid');
                        $fieldElementName = $fieldElements->getVar('fieldelement_name');
                        $fieldNameDesc = substr($fieldElementName, strrpos($fieldElementName, ':'), strlen($fieldElementName));
                        $topicTableName = str_replace(': ', '', strtolower($fieldNameDesc));
                        $fieldsTopics = $this->getTableFields($fieldElementMid, $fieldElementTid);
                        foreach (array_keys($fieldsTopics) as $g) {
                            $fieldNameTopic = $fieldsTopics[$g]->getVar('field_name');
                            if (1 == $fieldsTopics[$g]->getVar('field_main')) {
                                $fieldMainTopic = $fieldNameTopic;
                            }
                        }
                        $getHandlerVar = "\${$moduleDirname}->getHandler('{$topicTableName}')";
                        $getValues .= $xc->getXcEqualsOperator("\${$topicTableName}", $getHandlerVar, null, false, "\t\t");
                        $getTopicTable = "\${$topicTableName}->get(\$this->getVar('{$fieldName}'))";
                        $getValues .= $xc->getXcEqualsOperator("\${$topicTableName}Obj", $getTopicTable, null, false, "\t\t");
                        $fMainTopic = "\${$fieldName}->getVar('{$fieldMainTopic}')";
                        $getValues .= $xc->getXcEqualsOperator("\$ret['{$rpFieldName}']", $fMainTopic, null, false, "\t\t");
                    } else {
                        $getValues .= $xc->getXcGetVar("ret['{$rpFieldName}']", 'this', $fieldName, false, "\t\t");
                    }
                break;
            }
        }
        $getValues .= $this->getSimpleString('return $ret;', "\t\t");

        $ret .= $pc->getPhpCodeFunction('getValues'.$ucfTableName, '$keys = null, $format = null, $maxDepth = null', $getValues, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @private function getToArrayInObject
     *
     *  @param $table
     *
     * @return string
     */
    private function getToArrayInObject($table)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $tableName = $table->getVar('table_name');
        $ucfTableName = ucfirst($tableName);
        $multiLineCom = ['Returns an array representation' => 'of the object', '' => '', '@return' => 'array'];
        $ret = $pc->getPhpCodeCommentMultiLine($multiLineCom, "\t");

        $getToArray = $pc->getPhpCodeArray('ret', [], false, "\t\t");
        $getToArray .= $xc->getXcEqualsOperator('$vars', '$this->getVars()', null, false, "\t\t");
        $foreach = $xc->getXcGetVar('ret[$var]', 'this', '"{$var}"', false, "\t");
        $getToArray .= $pc->getPhpCodeForeach('vars', true, false, 'var', $foreach, "\t\t");
        $getToArray .= $this->getSimpleString('return $ret;', "\t\t");

        $ret .= $pc->getPhpCodeFunction('toArray'.$ucfTableName, '', $getToArray, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @private function getOptionsCheck
     *
     *  @param $table
     *
     * @return string
     */
    private function getOptionsCheck($table)
    {
        $tc = TDMCreateHelper::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $tableName = $table->getVar('table_name');
        $ucfTableName = ucfirst($tableName);
        $ret = $pc->getPhpCodeCommentMultiLine(['Get' => 'Options'], "\t");
        $getOptions = $pc->getPhpCodeArray('ret', [], false, "\t");

        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');

            $fieldElements = $tc->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId = $fieldElements->getVar('fieldelement_id');
            $rpFieldName = $this->getRightString($fieldName);
            if (5 == $fieldElementId) {
                $arrayPush = $pc->getPhpCodeArrayType('ret', 'push', "'{$rpFieldName}'", null, false, "\t\t\t");
                $getOptions .= $pc->getPhpCodeConditions(1, ' == ', "\$this->getVar('{$fieldName}')", $arrayPush, false, "\t\t");
            }
        }

        $getOptions .= $this->getSimpleString('return $ret;', "\t\t");

        $ret .= $pc->getPhpCodeFunction('getOptions'.$ucfTableName, '', $getOptions, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassHandler
     *
     * @param string $moduleDirname
     * @param string $table
     * @param string $fieldId
     * @param        $fieldName
     * @param string $fieldMain
     *
     * @param        $fieldParent
     * @param        $fieldParentId
     * @param        $fieldElement
     * @return string
     */
    private function getClassObjectHandler($moduleDirname, $table, $fieldId, $fieldName, $fieldMain, $fieldParent, $fieldParentId, $fieldElement)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $tableName = $table->getVar('table_name');
        $tableCategory = $table->getVar('table_category');
        $tableSoleName = $table->getVar('table_solename');
        $tableFieldname = $table->getVar('table_fieldname');
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ucfTableSoleName = ucfirst($tableSoleName);
        $ucfModuleTable = $ucfModuleDirname.$ucfTableName;
        $multiLineCom = ['Class Object Handler' => $ucfModuleDirname . $ucfTableName];
        $ret = $pc->getPhpCodeCommentMultiLine($multiLineCom);

        $cClh = $pc->getPhpCodeCommentMultiLine(['Constructor' => '', '' => '', '@param' => 'null|XoopsDatabase $db'], "\t");
        $constr = "\t\tparent::__construct(\$db, '{$moduleDirname}_{$tableName}', '{$moduleDirname}{$tableName}', '{$fieldId}', '{$fieldMain}');\n";

        $cClh .= $pc->getPhpCodeFunction('__construct', 'XoopsDatabase $db', $constr, 'public ', false, "\t");
        $cClh .= $this->getClassCreate();
        $cClh .= $this->getClassGet();
        $cClh .= $this->getClassGetInsertId();
        $cClh .= $this->getClassCounter($tableName, $fieldId, $fieldMain);
        $cClh .= $this->getClassAll($tableName, $fieldId, $fieldMain);
        $cClh .= $this->getClassCriteria($tableName);
        if ($fieldElement > 15 && in_array(1, $fieldParentId)) {
            $cClh .= $this->getClassByCategory($moduleDirname, $tableName, $tableFieldName, $fieldId, $fieldName, $fieldMain, $fieldParent, $fieldElement);
            $cClh .= $this->getClassGetTableSolenameById($moduleDirname, $table, $fieldMain);
        }

        $ret .= $pc->getPhpCodeClass("{$ucfModuleTable}Handler", $cClh, 'XoopsPersistableObjectHandler');

        return $ret;
    }

    /**
     *  @public function getClassCreate
     *
     *  @return string
     */
    private function getClassCreate()
    {
        $pc = TDMCreatePhpCode::getInstance();
        $ret = $pc->getPhpCodeCommentMultiLine(['@param bool' => '$isNew', '' => '', '@return' => 'object'], "\t");
        $cClhc = $this->getSimpleString('return parent::create($isNew);', "\t\t");

        $ret .= $pc->getPhpCodeFunction('create', '$isNew = true', $cClhc, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassGet
     *
     *  @return string
     */
    private function getClassGet()
    {
        $pc = TDMCreatePhpCode::getInstance();
        $ret = $pc->getPhpCodeCommentMultiLine(['retrieve a' => 'field', '' => '', '@param int' => '$i field id', '@param null' => 'fields', '@return mixed reference to the' => '{@link Get} object'], "\t");
        $cClhg = $this->getSimpleString('return parent::get($i, $fields);', "\t\t");

        $ret .= $pc->getPhpCodeFunction('get', '$i = null, $fields = null', $cClhg, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassGetInsertId
     *
     *  @return string
     */
    private function getClassGetInsertId()
    {
        $pc = TDMCreatePhpCode::getInstance();
        $ret = $pc->getPhpCodeCommentMultiLine(['get inserted' => 'id', '' => '', '@param' => 'null', '@return integer reference to the' => '{@link Get} object'], "\t");
        $cClhgid = $this->getSimpleString('return $this->db->getInsertId();', "\t\t");

        $ret .= $pc->getPhpCodeFunction('getInsertId', '', $cClhgid, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassCounter
     *
     *  @param $tableName
     *  @param $fieldId
     *  @param $fieldMain
     *
     *  @return string
     */
    private function getClassCounter($tableName, $fieldId, $fieldMain)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $cc = ClassXoopsCode::getInstance();
        $ucfTableName = ucfirst($tableName);
        $ret = $pc->getPhpCodeCommentMultiLine(['Get Count ' . $ucfTableName => 'in the database', '@param int    $start' => '', '@param int    $limit' => '', '@param string $sort' => '', '@param string $order' => '', '@return' => 'int'], "\t");

        $critCount = $cc->getClassCriteriaCompo('crCount'.$ucfTableName, "\t\t");
        $paramsCrit = "\$this->get{$ucfTableName}Criteria(\$crCount{$ucfTableName}, \$start, \$limit, \$sort, \$order)";
        $critCount .= $xc->getXcEqualsOperator('$crCount'.$ucfTableName, $paramsCrit, null, false, "\t\t");
        $critCount .= $this->getSimpleString("return parent::getCount(\$crCount{$ucfTableName});", "\t\t");
        $params = "\$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC'";

        $ret .= $pc->getPhpCodeFunction('getCount'.$ucfTableName, $params, $critCount, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassAll
     *
     *  @param $tableName
     *  @param $fieldId
     *  @param $fieldMain
     *
     *  @return string
     */
    private function getClassAll($tableName, $fieldId, $fieldMain)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $cc = ClassXoopsCode::getInstance();
        $ucfTableName = ucfirst($tableName);
        $ret = $pc->getPhpCodeCommentMultiLine(['Get All ' . $ucfTableName => 'in the database', '@param int    $start' => '', '@param int    $limit' => '', '@param string $sort' => '', '@param string $order' => '', '@return' => 'array'], "\t");

        $critAll = $cc->getClassCriteriaCompo('crAll'.$ucfTableName, "\t\t");
        $paramsCrit = "\$this->get{$ucfTableName}Criteria(\$crAll{$ucfTableName}, \$start, \$limit, \$sort, \$order)";
        $critAll .= $xc->getXcEqualsOperator('$crAll'.$ucfTableName, $paramsCrit, null, false, "\t\t");
        $critAll .= $this->getSimpleString("return parent::getAll(\$crAll{$ucfTableName});", "\t\t");
        $params = "\$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC'";

        $ret .= $pc->getPhpCodeFunction('getAll'.$ucfTableName, $params, $critAll, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassByCategory
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $tableFieldName
     * @param $fieldId
     * @param $fieldName
     * @param $fieldMain
     * @param $fieldParent
     *
     * @param $fieldElement
     * @return string
     */
    private function getClassByCategory($moduleDirname, $tableName, $tableFieldName, $fieldId, $fieldName, $fieldMain, $fieldParent, $fieldElement)
    {
        $tc = TDMCreateHelper::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $cc = ClassXoopsCode::getInstance();
        $ucfTableName = ucfirst($tableName);
        $fieldElements = $tc->getHandler('fieldelements')->get($fieldElement);
        $fieldElementMid = $fieldElements->getVar('fieldelement_mid');
        $fieldElementTid = $fieldElements->getVar('fieldelement_tid');
        $fieldElementName = $fieldElements->getVar('fieldelement_name');
        $fieldNameDesc = ucfirst(substr($fieldElementName, strrpos($fieldElementName, ':'), strlen($fieldElementName)));
        $topicTableName = str_replace(': ', '', $fieldNameDesc);
        $lcfTopicTableName = lcfirst($topicTableName);

        $ret = $pc->getPhpCodeCommentMultiLine(["Get All {$ucfTableName} By" => "{$fieldNameDesc} Id", '@param int    $start' => '', '@param int    $limit' => '', '@param string $sort' => '', '@param string $order' => '', '@return' => 'array'], "\t");

        $critAll = $xc->getXcEqualsOperator('$gpermHandler', "xoops_getHandler('groupperm')", null, true, "\t\t");
        $param1 = "'{$moduleDirname}_view'";
        $param2 = "\$GLOBALS['xoopsUser']->getGroups()";
        $param3 = "\$GLOBALS['xoopsModule']->getVar('mid')";
        $critAll .= $xc->getXcGetItemIds($lcfTopicTableName, 'gpermHandler', $param1, $param2, $param3, "\t\t");
        $critAll .= $cc->getClassCriteriaCompo('crAll'.$ucfTableName, "\t\t");

        if (false !== strpos($fieldName, 'status')) {
            $crit = $cc->getClassCriteria('', "'{$fieldName}'", '0', "'!='", true);
            $critAll .= $cc->getClassAdd('crAll'.$ucfTableName, $crit, "\t\t");
        }
        $paramsCritAll = "\$this->get{$ucfTableName}Criteria(\$crAll{$ucfTableName}, \$start, \$limit, \$sort, \$order)";
        $critAll .= $xc->getXcEqualsOperator('$crAll'.$ucfTableName, $paramsCritAll, null, false, "\t\t");

        $critAll .= $this->getSimpleString("return parent::getAll(\$crAll{$ucfTableName});", "\t\t");
        $params = "\${$tableFieldName}Id, \$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC'";

        $ret .= $pc->getPhpCodeFunction("getAll{$ucfTableName}By{$fieldNameDesc}Id".$ucfTableName, $params, $critAll, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassCriteria
     *
     *  @param $tableName
     *  @return string
     */
    private function getClassCriteria($tableName)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $cc = ClassXoopsCode::getInstance();
        $ucfTableName = ucfirst($tableName);
        $ret = $pc->getPhpCodeCommentMultiLine(['Get' => 'Criteria ' . $ucfTableName, '@param       ' => "\$cr{$ucfTableName}", '@param int    $start' => '', '@param int    $limit' => '', '@param string $sort' => '', '@param string $order' => '', '@return' => 'int'], "\t");

        $paramsAllCriteria = "\$cr{$ucfTableName}, \$start, \$limit, \$sort, \$order";

        $critSets = $cc->getClassSetStart('cr'.$ucfTableName, 'start', "\t\t");
        $critSets .= $cc->getClassSetLimit('cr'.$ucfTableName, 'limit', "\t\t");
        $critSets .= $cc->getClassSetSort('cr'.$ucfTableName, 'sort', "\t\t");
        $critSets .= $cc->getClassSetOrder('cr'.$ucfTableName, 'order', "\t\t");
        $critSets .= $this->getSimpleString("return \$cr{$ucfTableName};", "\t\t");

        $ret .= $pc->getPhpCodeFunction("get{$ucfTableName}Criteria", $paramsAllCriteria, $critSets, 'private ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassGetTableSolenameById
     *
     * @param $moduleDirname
     * @param $table
     *
     * @param $fieldMain
     * @return string
     */
    private function getClassGetTableSolenameById($moduleDirname, $table, $fieldMain)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $ucfTableSoleName = ucfirst($tableSoleName);
        $ccTableSoleName = $this->getCamelCase($tableSoleName, true);
        $ret = $pc->getPhpCodeCommentMultiLine(['Returns the' => $ucfTableSoleName . ' from id', '' => '', '@return' => 'string'], "\t");
        $soleName = $xc->getXcEqualsOperator("\${$tableSoleName}Id", "(int)( \${$tableSoleName}Id )", null, false, "\t\t");
        $soleName .= $xc->getXcEqualsOperator("\${$tableSoleName}", "''", null, false, "\t\t");

        $contentIf = $xc->getXoopsHandlerLine($moduleDirname, $tableName, "\t\t\t");
        $contentIf .= $xc->getXcGet($tableName, "\${$tableSoleName}Id", 'Obj', true, false, "\t\t\t");
        $getVar = $xc->getXcGetVar($ccTableSoleName, "{$tableSoleName}Obj", $fieldMain, false, "\t\t\t\t");
        $contentIf .= $pc->getPhpCodeConditions("is_object( \${$tableSoleName}Obj )", '', '', $getVar, false, "\t\t\t");

        $soleName .= $pc->getPhpCodeConditions("\${$tableSoleName}Id", ' > ', '0', $contentIf = null, false, "\t\t");
        $soleName .= $this->getSimpleString("return \${$tableSoleName};", "\t\t");

        $ret .= $pc->getPhpCodeFunction("get{$ucfTableSoleName}FromId", "\${$tableSoleName}Id", $soleName, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $tc = TDMCreateHelper::getInstance();
        $module = $this->getModule();
        $table = $this->getTable();
        $filename = $this->getFileName();
        $tableName = $table->getVar('table_name');
        $tableFieldName = $table->getVar('table_fieldname');
        $tableCategory = $table->getVar('table_category');
        $moduleDirname = $module->getVar('mod_dirname');
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldInForm = [];
        $fieldParentId = [];
        $fieldElementId = [];
        $fieldParent = null;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldInForm[] = $fields[$f]->getVar('field_inform');
            $fieldParentId[] = $fields[$f]->getVar('field_parent');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName; // $fieldId = fields parameter index field
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // $fieldMain = fields parameter main field
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldParent = $fieldName; // $fieldParent = fields parameter parent field
            }
            $fieldElement = $fields[$f]->getVar('field_element');

            $fieldElements = $tc->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');
        }
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getClassObject($module, $table, $fields);
        $content .= $this->getClassObjectHandler($moduleDirname, $table, $fieldId, $fieldName, $fieldMain, $fieldParent, $fieldParentId, $fieldElement);

        $this->create($moduleDirname, 'class', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
