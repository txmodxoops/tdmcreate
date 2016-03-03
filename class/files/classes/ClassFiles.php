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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
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
    /*
    * @var string
    */
    private $cc = null;

    /*
    * @var string
    */
    private $pc = null;

    /*
    * @var string
    */
    private $xc = null;

    /*
    * @var string
    */
    private $tf = null;

    /*
    * @var string
    */
    private $fe = null;

    /*
    * @var string
    */
    private $tc = null;

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
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->tf = TDMCreateFile::getInstance();
        $this->tc = TDMCreateHelper::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->cc = ClassXoopsCode::getInstance();
        $this->fe = ClassFormElements::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return ClassFiles
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
    *  @param string $table
    *  @param mixed $tables
    */
    /**
     * @param $module
     * @param $table
     * @param $tables
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /*
    *  @private function getInitVar
    *  @param string $fieldName
    *  @param string $type
    */
    /**
     * @param        $fieldName
     * @param string $type
     *
     * @return string
     */
    private function getInitVar($fieldName, $type = 'INT')
    {
        return $this->cc->getClassInitVar($fieldName, $type);
    }

    /*
    *  @private function getInitVars
    *  @param array $fields
    */
    /**
     * @param $fields
     *
     * @return string
     */
    private function getInitVars($fields)
    {
        $ret = '';
        // Creation of the initVar functions list
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldType = $fields[$f]->getVar('field_type');
            if ($fieldType > 1) {
                $fType = $this->tc->getHandler('fieldtype')->get($fieldType);
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

    /*
    *  @private function getClassObject
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $fields   
    *
    *  @return string
    */
    private function getClassObject($module, $table, $fields)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ret = $this->pc->getPhpCodeDefined();
        $ret .= $this->pc->getPhpCodeCommentMultiLine(array('Class Object' => $ucfModuleDirname.$ucfTableName));
        $cCl = $this->pc->getPhpCodeCommentMultiLine(array('@var' => 'mixed'), "\t");
        $cCl .= $this->pc->getPhpCodeVariableClass('private', $moduleDirname, 'null', "\t");

        $fieldInForm = array();
        $fieldElementId = array();
        $optionsFieldName = array();
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $fieldInForm[] = $fields[$f]->getVar('field_inform');
            $fieldElements = $this->tc->getHandler('fieldelements')->get($fieldElement);
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
            $cCl .= $this->pc->getPhpCodeCommentMultiLine(array('Options' => ''));
            $options = $this->pc->getPhpCodeArray('', $optionsFieldName, true);
            $cCl .= $this->pc->getPhpCodeVariableClass('private', 'options', $options);
        }
        unset($optionsFieldName);

        $cCl .= $this->pc->getPhpCodeCommentMultiLine(array('Constructor' => '', '' => '', '@param' => 'null'), "\t");
        $constr = $this->xc->getXoopsCodeGetInstance("this->{$moduleDirname}", "{$ucfModuleDirname}Helper", "\t\t");
        $constr .= $this->getInitVars($fields);
        $cCl .= $this->pc->getPhpCodeFunction('__construct', '', $constr, 'public ', false, "\t");
        $arrayGetInstance = array('@static function' => '&getInstance', '' => '', '@param' => 'null');
        $cCl .= $this->pc->getPhpCodeCommentMultiLine($arrayGetInstance, "\t");
        $getInstance = $this->pc->getPhpCodeVariableClass('static', 'instance', 'false', "\t\t");
        $instance = $this->xc->getXoopsCodeEqualsOperator('$instance', 'new self()', null, false, "\t\t\t");
        $getInstance .= $this->pc->getPhpCodeConditions('!$instance', '', '', $instance, false, "\t\t");
        $cCl .= $this->pc->getPhpCodeFunction('getInstance', '', $getInstance, 'public static ', true, "\t");

        $cCl .= $this->getNewInsertId($table);
        $cCl .= $this->getFunctionForm($module, $table, $fieldId, $fieldInForm);
        $cCl .= $this->getValuesInObject($moduleDirname, $table, $fields);
        $cCl .= $this->getToArrayInObject($table);

        if (in_array(5, $fieldElementId) > 1) {
            $cCl .= $this->getOptionsCheck($table);
        }
        unset($fieldElementId);

        $ret .= $this->pc->getPhpCodeClass($ucfModuleDirname.$ucfTableName, $cCl, 'XoopsObject');

        return $ret;
    }

    /*
     *  @private function getNewInsertId
     *  @param $table
     *
     * @return string
     */
    private function getNewInsertId($table)
    {
        $tableName = $table->getVar('table_name');
        $ucfTableName = ucfirst($tableName);
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('The new inserted' => '$Id'), "\t");
        $getInsertedId = $this->xc->getXoopsCodeEqualsOperator('$newInsertedId', "\$GLOBALS['xoopsDB']->getInsertId()", null, false, "\t\t");
        $getInsertedId .= $this->getSimpleString('return $newInsertedId;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('getNewInsertedId'.$ucfTableName, '', $getInsertedId, 'public ', false, "\t");

        return $ret;
    }

    /*
    *  @private function getFunctionForm
    *  @param string $module
    *  @param string $table
    */
    /**
     * @param $module
     * @param $table
     *
     * @return string
     */
    private function getFunctionForm($module, $table, $fieldId, $fieldInForm)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $tableCategory = $table->getVar('table_category');
        $tablePermissions = $table->getVar('table_permissions');
        $ucfTableName = ucfirst($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);
        $language = $this->getLanguage($moduleDirname, 'AM');
        $this->fe->initForm($module, $table);
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('Get' => 'form', '' => '', '@param mixed' => '$action'), "\t");
        $action = $this->xc->getXoopsCodeEqualsOperator('$action', "\$_SERVER['REQUEST_URI']", null, false, "\t\t\t");
        $getForm = $this->pc->getPhpCodeConditions('$action', ' === ', 'false', $action, false, "\t\t");
        $xUser = $this->pc->getPhpCodeGlobals('xoopsUser');
        $xModule = $this->pc->getPhpCodeGlobals('xoopsModule');
        if ((1 != $tableCategory)/* && (1 == $tablePermissions)*/) {
            $getForm .= $this->pc->getPhpCodeCommentLine('Permissions for', 'uploader', "\t\t");
            $getForm .= $this->xc->getXoopsCodeEqualsOperator('$gpermHandler', "xoops_gethandler('groupperm')", null, true, "\t\t");
            $getForm .= $this->pc->getPhpCodeTernaryOperator('groups', 'is_object('.$xUser.')', $xUser.'->getGroups()', 'XOOPS_GROUP_ANONYMOUS', "\t\t");
            $checkRight = $this->xc->getXoopsCodeCheckRight('$gpermHandler', $permString = '', 32, '$groups', $xModule.'->getVar(\'mid\')', true);
            $ternaryOperator = $this->pc->getPhpCodeTernaryOperator('permissionUpload', $checkRight, 'true', 'false', "\t\t\t\t");
            $permissionUpload = $this->xc->getXoopsCodeEqualsOperator('$permissionUpload', 'true', null, false, "\t\t\t\t");
            $ternOperator = $this->pc->getPhpCodeRemoveCarriageReturn($ternaryOperator, '', "\r");
            $if = $this->pc->getPhpCodeConditions('!'.$xUser.'->isAdmin('.$xModule.'->mid())', '', '', $ternaryOperator, $permissionUpload, "\t\t\t");
            $getForm .= $this->pc->getPhpCodeConditions($xUser, '', '', $if, $ternOperator, "\t\t");
        }
        $getForm .= $this->pc->getPhpCodeCommentLine('Title', '', "\t\t");
        $getForm .= $this->pc->getPhpCodeTernaryOperator('title', '$this->isNew()', "sprintf({$language}{$stuTableSoleName}_ADD)", "sprintf({$language}{$stuTableSoleName}_EDIT)", "\t\t");
        $getForm .= $this->pc->getPhpCodeCommentLine('Get Theme', 'Form', "\t\t");
        $getForm .= $this->xc->getXoopsCodeLoad('XoopsFormLoader', "\t\t");
        $getForm .= $this->cc->getClassXoopsThemeForm('form', 'title', 'form', 'action', 'post');
        $getForm .= $this->cc->getClassSetExtra('form', "'enctype=\"multipart/form-data\"'");
        $getForm .= $this->fe->renderElements();

        if (in_array(1, $fieldInForm)) {
            if (1 == $table->getVar('table_permissions')) {
                $getForm .= $this->getPermissionsInForm($moduleDirname, $fieldId);
            }
        }
        $getForm .= $this->pc->getPhpCodeCommentLine('To Save', '', "\t\t");
        //$hiddenSave = $this->cc->getClassXoopsFormHidden('', "'op'", "'save'", true, false);
        $getForm .= $this->cc->getClassAddElement('form', "new XoopsFormHidden('op', 'save')");
        //$buttonSend = $this->cc->getClassXoopsFormButton('', '', 'submit', '_SUBMIT', 'submit', true);
        $getForm .= $this->cc->getClassAddElement('form', "new XoopsFormButton('', 'submit', _SUBMIT, 'submit')");
        $getForm .= $this->getSimpleString('return $form;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('getForm'.$ucfTableName, '$action = false', $getForm, 'public ', false, "\t");

        return $ret;
    }

    /*
    *  @private function getPermissionsInForm
    *  @param string $moduleDirname
    *  @param string $fieldId
    */
    /**
     * @param $moduleDirname
     * @param $fieldId
     *
     * @return string
     */
    private function getPermissionsInForm($moduleDirname, $fieldId)
    {
        $permissionApprove = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_APPROVE');
        $permissionSubmit = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_SUBMIT');
        $permissionView = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_VIEW');
        $ret = $this->pc->getPhpCodeCommentLine('Permissions', '', "\t\t");
        $ret .= $this->xc->getXoopsCodeEqualsOperator('$memberHandler', "xoops_gethandler('member')", null, true, "\t\t");
        $ret .= $this->xc->getXoopsCodeEqualsOperator('$groupList', '$memberHandler->getGroupList()', null, false, "\t\t");
        $ret .= $this->xc->getXoopsCodeEqualsOperator('$gpermHandler', "xoops_gethandler('groupperm')", null, true, "\t\t");
        $ret .= $this->pc->getPhpCodeArrayType('fullList', 'keys', 'groupList', null, false, "\t\t");
        $fId = $this->xc->getXoopsCodeGetVar('', 'this', $fieldId, true);
        $mId = $this->xc->getXoopsCodeGetVar('', "GLOBALS['xoopsModule']", 'mid', true);
        $ifGroups = $this->xc->getXoopsCodeGetGroupIds('groupsIdsApprove', 'gpermHandler', "'{$moduleDirname}_approve'", $fId, $mId, "\t\t\t");
        $ifGroups .= $this->pc->getPhpCodeArrayType('groupsIdsApprove', 'values', 'groupsIdsApprove', null, false, "\t\t\t");
        $ifGroups .= $this->cc->getClassXoopsFormCheckBox('groupsCanApproveCheckbox', "{$permissionApprove}", 'groups_approve[]', '$groupsIdsApprove', false, "\t\t\t");
        $ifGroups .= $this->xc->getXoopsCodeGetGroupIds('groupsIdsSubmit', 'gpermHandler', "'{$moduleDirname}_submit'", $fId, $mId, "\t\t\t");
        $ifGroups .= $this->pc->getPhpCodeArrayType('groupsIdsSubmit', 'values', 'groupsIdsSubmit', null, false, "\t\t\t");
        $ifGroups .= $this->cc->getClassXoopsFormCheckBox('groupsCanSubmitCheckbox', "{$permissionSubmit}", 'groups_submit[]', '$groupsIdsSubmit', false, "\t\t\t");
        $ifGroups .= $this->xc->getXoopsCodeGetGroupIds('groupsIdsView', 'gpermHandler', "'{$moduleDirname}_view'", $fId, $mId, "\t\t\t");
        $ifGroups .= $this->pc->getPhpCodeArrayType('groupsIdsView', 'values', 'groupsIdsView', null, false, "\t\t\t");
        $ifGroups .= $this->cc->getClassXoopsFormCheckBox('groupsCanViewCheckbox', "{$permissionView}", 'groups_view[]', '$groupsIdsView', false, "\t\t\t");

        $else = $this->cc->getClassXoopsFormCheckBox('groupsCanApproveCheckbox', "{$permissionApprove}", 'groups_approve[]', '$fullList', false, "\t\t\t");
        $else .= $this->cc->getClassXoopsFormCheckBox('groupsCanSubmitCheckbox', "{$permissionSubmit}", 'groups_submit[]', '$fullList', false, "\t\t\t");
        $else .= $this->cc->getClassXoopsFormCheckBox('groupsCanViewCheckbox', "{$permissionView}", 'groups_view[]', '$fullList', false, "\t\t\t");

        $ret .= $this->pc->getPhpCodeConditions('!$this->isNew()', null, null, $ifGroups, $else, "\t\t");
        $ret .= $this->pc->getPhpCodeCommentLine('To Approve', '', "\t\t");
        $ret .= $this->cc->getClassAddOptionArray('groupsCanApproveCheckbox', '$groupList');
        $ret .= $this->cc->getClassAddElement('form', '$groupsCanApproveCheckbox');
        $ret .= $this->pc->getPhpCodeCommentLine('To Submit', '', "\t\t");
        $ret .= $this->cc->getClassAddOptionArray('groupsCanSubmitCheckbox', '$groupList');
        $ret .= $this->cc->getClassAddElement('form', '$groupsCanSubmitCheckbox');
        $ret .= $this->pc->getPhpCodeCommentLine('To View', '', "\t\t");
        $ret .= $this->cc->getClassAddOptionArray('groupsCanViewCheckbox', '$groupList');
        $ret .= $this->cc->getClassAddElement('form', '$groupsCanViewCheckbox');

        return $ret;
    }

    /*
    *  @private function getValuesInObject
    *  @param null
    */
    /**
     * @return string
     */
    private function getValuesInObject($moduleDirname, $table, $fields)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfTableName = ucfirst($table->getVar('table_name'));
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('Get' => 'Values'), "\t");
        $getValues = $this->xc->getXoopsCodeEqualsOperator('$ret', 'parent::getValues($keys, $format, $maxDepth)', null, true, "\t\t");

        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName = $this->tf->getRightString($fieldName);
            switch ($fieldElement) {
                case 3:
                case 4:
                    $getValues .= $this->pc->getPhpCodeStripTags("ret['{$rpFieldName}']", "\$this->getVar('{$fieldName}')", false, "\t\t");
                break;
                case 8:
                    $getValues .= $this->xc->getXoopsCodeUnameFromId("ret['{$rpFieldName}']", "\$this->getVar('{$fieldName}')", "\t\t");
                break;
                case 15:
                    $getValues .= $this->xc->getXoopsCodeFormatTimeStamp("ret['{$rpFieldName}']", "\$this->getVar('{$fieldName}')", 's', "\t\t");
                break;
                default:

                    if ($fieldElement > 15) {
                        $fieldElements = $this->tc->getHandler('fieldelements')->get($fieldElement);
                        $fieldElementMid = $fieldElements->getVar('fieldelement_mid');
                        $fieldElementTid = $fieldElements->getVar('fieldelement_tid');
                        $fieldElementName = $fieldElements->getVar('fieldelement_name');
                        $fieldNameDesc = substr($fieldElementName, strrpos($fieldElementName, ':'), strlen($fieldElementName));
                        $topicTableName = str_replace(': ', '', strtolower($fieldNameDesc));
                        $fieldsTopics = $this->tf->getTableFields($fieldElementMid, $fieldElementTid);
                        foreach (array_keys($fieldsTopics) as $f) {
                            $fieldNameTopic = $fieldsTopics[$f]->getVar('field_name');
                            if (1 == $fieldsTopics[$f]->getVar('field_main')) {
                                $fieldMainTopic = $fieldNameTopic;
                            }
                        }
                        $getHandlerVar = "\$this->{$moduleDirname}->getHandler('{$topicTableName}')->get(\$this->getVar('{$fieldName}'))->getVar('{$fieldMainTopic}')";
                        $getValues .= $this->xc->getXoopsCodeEqualsOperator("\$ret['{$rpFieldName}']", $getHandlerVar, null, false, "\t\t");
                    } else {
                        $getValues .= $this->xc->getXoopsCodeGetVar("ret['{$rpFieldName}']", 'this', $fieldName, false, "\t\t");
                    }
                break;
            }
        }
        $getValues .= $this->getSimpleString('return $ret;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('getValues'.$ucfTableName, '$keys = null, $format = null, $maxDepth = null', $getValues, 'public ', false, "\t");

        return $ret;
    }

    /*
     *  @private function getToArrayInObject
     *  @param $table
     *
     * @return string
     */
    private function getToArrayInObject($table)
    {
        $tableName = $table->getVar('table_name');
        $ucfTableName = ucfirst($tableName);
        $multiLineCom = array('Returns an array representation' => 'of the object', '' => '', '@return' => 'array');
        $ret = $this->pc->getPhpCodeCommentMultiLine($multiLineCom, "\t");

        $getToArray = $this->pc->getPhpCodeArray('ret', array(), false, "\t\t");
        $getToArray .= $this->xc->getXoopsCodeEqualsOperator('$vars', '$this->getVars()', null, false, "\t\t");
        $foreach = $this->xc->getXoopsCodeGetVar('ret[$var]', 'this', '$var', false, "\t");
        $getToArray .= $this->pc->getPhpCodeForeach('vars', true, false, 'var', $foreach, "\t\t");
        $getToArray .= $this->getSimpleString('return $ret;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('toArray'.$ucfTableName, '', $getToArray, 'public ', false, "\t");

        return $ret;
    }

    /*
    *  @private function getOptionsCheck
    *  @param $table
    */
    /**
     * @return string
     */
    private function getOptionsCheck($table)
    {
        $tableName = $table->getVar('table_name');
        $ucfTableName = ucfirst($tableName);
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('Get' => 'Options'), "\t");
        $getOptions = $this->pc->getPhpCodeArray('ret', array(), false, "\t");

        $fields = $this->tf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            //
            $fieldElements = $this->tc->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId = $fieldElements->getVar('fieldelement_id');
            $rpFieldName = $this->tf->getRightString($fieldName);
            if (5 == $fieldElementId) {
                $arrayPush = $this->pc->getPhpCodeArrayType('ret', 'push', "'{$rpFieldName}'", null, false, "\t\t\t");
                $getOptions .= $this->pc->getPhpCodeConditions(1, ' == ', "\$this->getVar('{$fieldName}')", $arrayPush, false, "\t\t");
            }
        }

        $getOptions .= $this->getSimpleString('return $ret;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('getOptions'.$ucfTableName, '', $getOptions, 'public ', false, "\t");

        return $ret;
    }

    /*
    *  @public function getClassHandler
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldId
    *  @param string $fieldMain
    */
    /**
     * @param $moduleDirname
     * @param $tableName
     * @param $tableCategory
     * @param $tableFieldname
     * @param $fieldId
     * @param $fieldMain
     *
     * @return string
     */
    private function getClassObjectHandler($moduleDirname, $table, $fieldId, $fieldName, $fieldMain, $fieldParent, $fieldParentId, $fieldElement)
    {
        $tableName = $table->getVar('table_name');
        $tableCategory = $table->getVar('table_category');
        $tableSoleName = $table->getVar('table_solename');
        $tableFieldname = $table->getVar('table_fieldname');
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ucfTableSoleName = ucfirst($tableSoleName);
        $ucfModuleTable = $ucfModuleDirname.$ucfTableName;
        $multiLineCom = array('Class Object Handler' => $ucfModuleDirname.$ucfTableName);
        $ret = $this->pc->getPhpCodeCommentMultiLine($multiLineCom);

        $cClh = $this->pc->getPhpCodeCommentMultiLine(array('@var' => 'mixed'), "\t");
        $cClh .= $this->pc->getPhpCodeVariableClass('private', $moduleDirname, 'null', "\t");

        $cClh .= $this->pc->getPhpCodeCommentMultiLine(array('Constructor' => '', '' => '', '@param' => 'string $db'), "\t");
        $constr = "\t\tparent::__construct(\$db, '{$moduleDirname}_{$tableName}', '{$moduleDirname}{$tableName}', '{$fieldId}', '{$fieldMain}');\n";
        $constr .= $this->xc->getXoopsCodeGetInstance("this->{$moduleDirname}", "{$ucfModuleDirname}Helper", "\t\t");
        $constr .= $this->xc->getXoopsCodeEqualsOperator('$this->db', '$db', null, false, "\t\t");

        $cClh .= $this->pc->getPhpCodeFunction('__construct', '$db', $constr, 'public ', false, "\t");

        $cClh .= $this->getClassCreate();
        $cClh .= $this->getClassGet();
        $cClh .= $this->getClassGetInsertId();
        $cClh .= $this->getClassGetIds();
        $cClh .= $this->getClassInsert();
        $cClh .= $this->getClassCounter($tableName, $fieldId, $fieldMain);
        $cClh .= $this->getClassAll($tableName, $fieldId, $fieldMain);
        $cClh .= $this->getClassCriteria($tableName);
        if (in_array(1, $fieldParentId) && $fieldElement > 15) {
            $cClh .= $this->getClassByCategory($moduleDirname, $tableName, $tableFieldName, $fieldId, $fieldName, $fieldMain, $fieldParent, $fieldElement);
            $cClh .= $this->getClassGetTableSolenameById($moduleDirname, $table, $fieldMain);
        }

        $ret .= $this->pc->getPhpCodeClass("{$ucfModuleTable}Handler", $cClh, 'XoopsPersistableObjectHandler');

        return $ret;
    }

    /**
     *  @public function getClassCreate
     *
     *  @return string
     */
    private function getClassCreate()
    {
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('@param bool' => '$isNew', '' => '', '@return' => 'object'), "\t");
        $cClhc = $this->getSimpleString('return parent::create($isNew);', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('&create', '$isNew = true', $cClhc, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassGet
     *
     *  @return string
     */
    private function getClassGet()
    {
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('retrieve a' => 'field', '' => '', '@param int' => '$i field id', '@return mixed reference to the' => '{@link Get} object'), "\t");
        $cClhg = $this->getSimpleString('return parent::get($i, $fields);', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('&get', '$i = null, $fields = null', $cClhg, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassGetInsertId
     *
     *  @return string
     */
    private function getClassGetInsertId()
    {
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('get inserted' => 'id', '' => '', '@param' => 'null', '@return integer reference to the' => '{@link Get} object'), "\t");
        $cClhgid = $this->getSimpleString('return $this->db->getInsertId();', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('&getInsertId', '', $cClhgid, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassGetIds
     *
     *  @return string
     */
    private function getClassGetIds()
    {
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('get IDs of objects' => 'matching a condition', '' => '',
                                                            '@param object \$criteria' => '{@link CriteriaElement} to match',
                                                            '@return array of' => 'object IDs', ), "\t");
        $cClhgids = $this->getSimpleString('return parent::getIds($criteria);', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('&getIds', '$criteria', $cClhgids, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassInsert
     *
     *  @return string
     */
    private function getClassInsert()
    {
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('insert a new field' => 'in the database', '' => '',
                                                            '@param object \$field reference to the' => '{@link insert} object',
                                                            '@param bool' => '$force', 'return bool FALSE if failed,' => 'TRUE if already present and unchanged or successful', ), "\t");
        $cClhinsert = $this->getSimpleString('return false;', "\t\t\t");

        $if = $this->pc->getPhpCodeConditions('!parent::insert($field, $force)', '', '', $cClhinsert, false, "\t\t");
        $if .= $this->getSimpleString('return true;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('&insert', '&$field, $force = false', $if, 'public ', false, "\t");

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
        $ucfTableName = ucfirst($tableName);
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('Get Count '.$ucfTableName => 'in the database'), "\t");

        $critCount = $this->cc->getClassCriteriaCompo('criteriaCount'.$ucfTableName, "\t\t");
        $paramsCrit = "\$this->get{$ucfTableName}Criteria(\$criteriaCount{$ucfTableName}, \$start, \$limit, \$sort, \$order)";
        $critCount .= $this->xc->getXoopsCodeEqualsOperator('$criteriaCount'.$ucfTableName, $paramsCrit, null, false, "\t\t");
        $critCount .= $this->getSimpleString("return parent::getCount(\$criteriaCount{$ucfTableName});", "\t\t");
        $params = "\$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC'";

        $ret .= $this->pc->getPhpCodeFunction('getCount'.$ucfTableName, $params, $critCount, 'public ', false, "\t");

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
        $ucfTableName = ucfirst($tableName);
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('Get All '.$ucfTableName => 'in the database'), "\t");

        $critAll = $this->cc->getClassCriteriaCompo('criteriaAll'.$ucfTableName, "\t\t");
        $paramsCrit = "\$this->get{$ucfTableName}Criteria(\$criteriaAll{$ucfTableName}, \$start, \$limit, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC')";
        $critAll .= $this->xc->getXoopsCodeEqualsOperator('$criteriaAll'.$ucfTableName, $paramsCrit, null, false, "\t\t");
        $critAll .= $this->getSimpleString("return parent::getAll(\$criteriaAll{$ucfTableName});", "\t\t");
        $params = "\$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC'";

        $ret .= $this->pc->getPhpCodeFunction('getAll'.$ucfTableName, $params, $critAll, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassByCategory
     *
     *  @param $tableName
     *  @param $fieldId
     *  @param $fieldMain
     *  @param $fieldParent
     *
     *  @return string
     */
    private function getClassByCategory($moduleDirname, $tableName, $tableFieldName, $fieldId, $fieldName, $fieldMain, $fieldParent, $fieldElement)
    {
        $ucfTableName = ucfirst($tableName);
        $fieldElements = $this->tc->getHandler('fieldelements')->get($fieldElement);
        $fieldElementMid = $fieldElements->getVar('fieldelement_mid');
        $fieldElementTid = $fieldElements->getVar('fieldelement_tid');
        $fieldElementName = $fieldElements->getVar('fieldelement_name');
        $fieldNameDesc = ucfirst(substr($fieldElementName, strrpos($fieldElementName, ':'), strlen($fieldElementName)));
        $topicTableName = str_replace(': ', '', $fieldNameDesc);
        $lcfTopicTableName = lcfirst($topicTableName);

        $ret = $this->pc->getPhpCodeCommentMultiLine(array("Get All {$ucfTableName} By" => "{$fieldNameDesc} Id"), "\t");

        $critAll = $this->xc->getXoopsCodeEqualsOperator('$gpermHandler', "xoops_gethandler('groupperm')", null, true, "\t\t");
        $param1 = "'{$moduleDirname}_view'";
        $param2 = "\$GLOBALS['xoopsUser']->getGroups()";
        $param3 = "\$GLOBALS['xoopsModule']->getVar('mid')";
        $critAll .= $this->xc->getXoopsCodeGetItemIds($lcfTopicTableName, 'gpermHandler', $param1, $param2, $param3, "\t\t");
        $critAll .= $this->cc->getClassCriteriaCompo('criteriaAll'.$ucfTableName, "\t\t");

        if (strstr($fieldName, 'status')) {
            $crit = $this->cc->getClassCriteria('', "'{$fieldName}'", '0', "'!='", true);
            $critAll .= $this->cc->getClassAdd('criteriaAll'.$ucfTableName, $crit, "\t\t");
        }
        $paramsCritAll = "\$this->get{$ucfTableName}Criteria(\$criteriaAll{$ucfTableName}, \$start, \$limit, \$sort, \$order)";
        $critAll .= $this->xc->getXoopsCodeEqualsOperator('$criteriaAll'.$ucfTableName, $paramsCritAll, null, false, "\t\t");

        $critAll .= $this->getSimpleString("return parent::getAll(\$criteriaAll{$ucfTableName});", "\t\t");
        $params = "\${$tableFieldName}Id, \$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC'";

        $ret .= $this->pc->getPhpCodeFunction("getAll{$ucfTableName}By{$fieldNameDesc}Id".$ucfTableName, $params, $critAll, 'public ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassCriteria
     *
     *  @param $tableName
     *  @param $fieldId
     *  @param $fieldMain
     *
     *  @return string
     */
    private function getClassCriteria($tableName)
    {
        $ucfTableName = ucfirst($tableName);
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('Get' => 'Criteria '.$ucfTableName), "\t");

        $paramsAllCriteria = "\$criteria{$ucfTableName}, \$start, \$limit, \$sort, \$order";

        $critSets = $this->cc->getClassSetStart('criteria'.$ucfTableName, 'start', "\t\t");
        $critSets .= $this->cc->getClassSetLimit('criteria'.$ucfTableName, 'limit', "\t\t");
        $critSets .= $this->cc->getClassSetSort('criteria'.$ucfTableName, 'sort', "\t\t");
        $critSets .= $this->cc->getClassSetOrder('criteria'.$ucfTableName, 'order', "\t\t");
        $critSets .= $this->getSimpleString("return \$criteria{$ucfTableName};", "\t\t");

        $ret .= $this->pc->getPhpCodeFunction("get{$ucfTableName}Criteria", $paramsAllCriteria, $critSets, 'private ', false, "\t");

        return $ret;
    }

    /**
     *  @public function getClassGetTableSolenameById
     *
     *  @param $moduleDirname
     *  @param $table
     *
     *  @return string
     */
    private function getClassGetTableSolenameById($moduleDirname, $table, $fieldMain)
    {
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $ucfTableSoleName = ucfirst($tableSoleName);
        $ccTableSoleName = $this->getCamelCase($tableSoleName, true);
        $ret = $this->pc->getPhpCodeCommentMultiLine(array('Returns the' => $ucfTableSoleName.' from id', '' => '', '@return' => 'string'), "\t");
        $soleName = $this->xc->getXoopsCodeEqualsOperator("\${$tableSoleName}Id", "(int)( \${$tableSoleName}Id )", null, false, "\t\t");
        $soleName .= $this->xc->getXoopsCodeEqualsOperator("\${$tableSoleName}", "''", null, false, "\t\t");

        $contentIf = $this->xc->getXoopsHandlerLine('this->'.$moduleDirname, $tableName, "\t\t\t");
        $contentIf .= $this->xc->getXoopsCodeGet($tableName, "\${$tableSoleName}Id", 'Obj', true, false, "\t\t\t");
        $getVar = $this->xc->getXoopsCodeGetVar($ccTableSoleName, "{$tableSoleName}Obj", $fieldMain, false, "\t\t\t\t");
        $contentIf .= $this->pc->getPhpCodeConditions("is_object( \${$tableSoleName}Obj )", '', '', $getVar, false, "\t\t\t");

        $soleName .= $this->pc->getPhpCodeConditions("\${$tableSoleName}Id", ' > ', '0', $contentIf = null, false, "\t\t");
        $soleName .= $this->getSimpleString("return \${$tableSoleName};", "\t\t");

        $ret .= $this->pc->getPhpCodeFunction("get{$ucfTableSoleName}FromId", "\${$tableSoleName}Id", $soleName, 'public ', false, "\t");

        return $ret;
    }

    /*
     * @public function render    
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $filename = $this->getFileName();
        $tableName = $table->getVar('table_name');
        $tableFieldName = $table->getVar('table_fieldname');
        $tableCategory = $table->getVar('table_category');
        $moduleDirname = $module->getVar('mod_dirname');
        $fields = $this->tf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldInForm = array();
        $fieldParentId = array();
        $fieldElementId = array();
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
            //
            $fieldElements = $this->tc->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');
        }
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getClassObject($module, $table, $fields);
        $content .= $this->getClassObjectHandler($moduleDirname, $table, $fieldId, $fieldName, $fieldMain, $fieldParent, $fieldParentId, $fieldElement);

        $this->tf->create($moduleDirname, 'class', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tf->renderFile();
    }
}
