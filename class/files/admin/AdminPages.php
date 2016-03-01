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
 * @version         $Id: AdminPages.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class AdminPages.
 */
class AdminPages extends TDMCreateFile
{
    /*
     * @var string
     */
    private $tf = null;
	
	/*
     * @var string
     */
    private $cc = null;

    /*
     * @var string
     */
    private $xc = null;
	
	/*
     * @var string
     */
    private $axc = null;

    /*
     * @public function constructor
     * @param null    
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->tf = TDMCreateFile::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->cc = ClassXoopsCode::getInstance();
        $this->axc = AdminXoopsCode::getInstance();
    }

    /*
     * @static function &getInstance
     * @param null 
     *
     * @return AdminPages
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
    *  @param $module
    *  @param $table
    */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /*
    *  @private function getAdminPagesHeader
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $fieldId
    *  @return string
    */
    private function getAdminPagesHeader($moduleDirname, $tableName, $fieldId)
    {
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret = $this->getInclude();
        $ret .= $this->phpcode->getPhpCodeCommentLine('It recovered the value of argument op in URL$');
        $ret .= $this->xc->getXoopsCodeXoopsRequest('op', 'op', 'list');
        $ret .= $this->phpcode->getPhpCodeCommentLine("Request {$fieldId}");
        $ret .= $this->xc->getXoopsCodeXoopsRequest($ccFieldId, $fieldId, '', 'Int');

        return $ret;
    }

    /*
     *  @private function getAdminPagesSwitch
     *  @param $cases
     *
     * @return string
     */
    private function getAdminPagesSwitch($cases = array())
    {
        $contentSwitch = $this->phpcode->getPhpCodeCaseSwitch($cases, true);

        return $this->phpcode->getPhpCodeSwitch('op', $contentSwitch);
    }

    /*
    *  @private function getAdminPagesList
    *  @param $moduleDirname
    *  @param $table
    *  @param $tableFieldname
    *  @param $language
    *  @param $fields
    *  @param $fieldId
    *  @param $fieldInForm
    *  @param $fieldMain
    *  @return string
    */
    private function getAdminPagesList($moduleDirname, $table, $language, $fields, $fieldId, $fieldInForm, $fieldMain)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $stuTableName = strtoupper($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);

        $ret = $this->xc->getXoopsCodeXoopsRequest('start', 'start', '0', 'Int');
        $adminpager = $this->xc->getXoopsCodeGetConfig($moduleDirname, 'adminpager');
        $ret .= $this->xc->getXoopsCodeXoopsRequest('limit', 'limit', $adminpager, 'Int');
        $ret .= $this->axc->getAdminTemplateMain($moduleDirname, $tableName);
        $navigation = $this->axc->getAdminAddNavigation($tableName);
        $ret .= $this->xc->getXoopsCodeTplAssign('navigation', $navigation);

        if (in_array(1, $fieldInForm)) {
            $ret .= $this->axc->getAdminItemButton($language, $tableName, $stuTableSoleName, '?op=new', 'add');
            $ret .= $this->xc->getXoopsCodeTplAssign('buttons', '$adminMenu->renderButton()');
        }

        $ret .= $this->xc->getXoopsCodeObjHandlerCount($tableName);
        $ret .= $this->xc->getXoopsCodeObjHandlerAll($tableName, '', '$start', '$limit');
        $ret .= $this->xc->getXoopsCodeTplAssign("{$tableName}_count", "\${$tableName}Count");
        $ret .= $this->xc->getXoopsCodeTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret .= $this->xc->getXoopsCodeTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");

        $ret .= $this->phpcode->getPhpCodeCommentLine('Table view', $tableName, "\t\t");
        $contentForeach = $this->xc->getXoopsCodeGetValues($tableName, $tableSoleName);
        $contentForeach .= $this->xc->getXoopsCodeXoopsTplAppend("{$tableName}_list", "\${$tableSoleName}");
        $contentForeach .= $this->phpcode->getPhpCodeUnset($tableSoleName, "\t\t");
        $condIf = $this->phpcode->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, "\t\t\t");
        $condIf .= $this->xc->getXoopsCodePageNav($tableName, "\t\t\t");
        $condElse = $this->xc->getXoopsCodeTplAssign('error', "{$language}THEREARENT_{$stuTableName}");
        $ret .= $this->phpcode->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf, $condElse, "\t\t");

        return $ret;
    }

    /*
    *  @private function getAdminPagesNew
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $fieldInForm
    *  @param $language
    *  @return string
    */
    private function getAdminPagesNew($moduleDirname, $tableName, $fieldInForm, $language, $t = '')
    {
        $stuTableName = strtoupper($tableName);
        $ret = $this->axc->getAdminTemplateMain($moduleDirname, $tableName);
        $navigation = $this->axc->getAdminAddNavigation($tableName, $t);
        $ret .= $this->xc->getXoopsCodeTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $this->axc->getAdminItemButton($language, $tableName, $stuTableName, '', 'list', $t);
            $ret .= $this->xc->getXoopsCodeTplAssign('buttons', '$adminMenu->renderButton()', true, $t);
        }
        $ret .= $this->phpcode->getPhpCodeCommentLine('Get Form');
        $ret .= $this->xc->getXoopsCodeObjHandlerCreate($tableName, $t);
        $ret .= $this->xc->getXoopsCodeGetForm('form', $tableName, 'Obj', $t);
        $ret .= $this->xc->getXoopsCodeTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /*
    *  @private function getPermissionsSave
    *  @param $moduleDirname
    *  @param $fieldId
    *  @param $ccFieldId
    *  @param $newFieldId
    *  @param $perm
    *
    *  @return string
    */
    private function getPermissionsSave($moduleDirname, $fieldId, $ccFieldId, $newFieldId, $perm = 'view')
    {
        $ucfPerm = ucfirst($perm);
        $ret = $this->phpcode->getPhpCodeCommentLine('Permission to', $perm, "\t\t\t");
        $varCrit = "criteria{$ucfPerm}";
        $ret .= $this->cc->getClassCriteriaCompo($varCrit, "\t\t\t");
        $arrayCrit = array("'gperm_itemid'" => '$permId', "'gperm_modid'" => "\$GLOBALS['xoopsModule']->getVar('mid')", "'gperm_name'" => "'{$moduleDirname}_{$perm}'");
        foreach ($arrayCrit as $k => $v) {
            $ret .= $this->cc->getClassCriteria($varCrit, $k, $v, "'='", false, "\t\t\t");
        }
        $ret .= $this->getSimpleString("\$gpermHandler->deleteAll(\${$varCrit});", "\t\t\t");
        $ret .= $this->phpcode->getPhpCodeUnset($varCrit, "\t\t\t");
        $content = $this->xc->getXoopsCodeAddRight('gpermHandler', "{$moduleDirname}_{$perm}", '$permId', '$onegroupId', "\$GLOBALS['xoopsModule']->getVar('mid')", false, "\t");
        $foreach = $this->phpcode->getPhpCodeForeach("_POST['groups_{$perm}']", false, false, 'onegroupId', $content, "\t\t\t\t");
        $ret .= $this->phpcode->getPhpCodeConditions("isset(\$_POST['groups_{$perm}'])", null, null, $foreach, false, "\t\t\t");

        return $ret;
    }

    /*
    *  @private function getAdminPagesSave
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $tableCategory
    *  @param $language
    *  @param $fields
    *  @param $fieldId
    *  @param $fieldMain
    *  @return string
    */
    private function getAdminPagesSave($moduleDirname, $tableName, $tableCategory, $language, $fields, $fieldId, $fieldMain, $t = '')
    {
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret = $this->phpcode->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $this->xc->getXoopsCodeSecurityCheck('!');
        $securityError = $this->xc->getXoopsCodeSecurityErrors();
        $implode = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $redirectError = $this->xc->getXoopsCodeRedirectHeader($tableName, '.php', '3', $implode, true, $t."\t");
        $ret .= $this->phpcode->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, $t);

        $isset = $this->phpcode->getPhpCodeIsset($ccFieldId);
        $contentIf = $this->xc->getXoopsCodeGet($tableName, $ccFieldId, 'Obj', true, false, $t."\t");
        $contentElse = $this->xc->getXoopsCodeObjHandlerCreate($tableName, "\t\t\t");
        $ret .= $this->phpcode->getPhpCodeConditions($isset, '', '', $contentIf, $contentElse, $t);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Set Vars');
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldType = $fields[$f]->getVar('field_type');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->xc->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName, $t);
                        break;
                    case 10:
                        $ret .= $this->xc->getXoopsCodeImageListSetVar($moduleDirname, $tableName, $fieldName, $t);
                        break;
                    case 12:
                        $ret .= $this->xc->getXoopsCodeUrlFileSetVar($moduleDirname, $tableName, $fieldName, $t);
                        break;
                    case 13:
                        if (1 == $fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        $ret .= $this->xc->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain, $t);
                        break;
                    case 14:
                        $ret .= $this->xc->getXoopsCodeUploadFileSetVar($moduleDirname, $tableName, $fieldName, $t);
                        break;
                    case 15:
                        $ret .= $this->xc->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName, $t);
                        break;
                    default:
                        if ($fieldType == 2 || $fieldType == 7 || $fieldType == 8) {
                            $ret .= $this->xc->getXoopsCodeSetVar($tableName, $fieldName, "isset(\$_POST['{$fieldName}']) ? \$_POST['{$fieldName}'] : 0", $t);
                        } else {
                            $ret .= $this->xc->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']", $t);
                        }
                        break;
                }
            }
        }
        $ret .= $this->phpcode->getPhpCodeCommentLine('Insert Data');
        $insert = $this->xc->getXoopsCodeInsert($tableName, $tableName, 'Obj', true);
        $contentInsert = '';
        if ($tableCategory == 1) {
            $contentInsert = $this->xc->getXoopsCodeEqualsOperator('$newCatId', "\${$tableName}Obj->getInsertId()", null, false, $t."\t");
            $ucfFieldId = $this->getCamelCase($fieldId, true);
            $contentInsert .= $this->phpcode->getPhpCodeTernaryOperator('permId', "isset(\$_REQUEST['{$fieldId}'])", "\${$ccFieldId}", "\$new{$ucfFieldId}", $t."\t");
            $contentInsert .= $this->xc->getXoopsCodeEqualsOperator('$gpermHandler', "xoops_gethandler('groupperm')", null, true, $t."\t");
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $fieldId, $ccFieldId, 'new'.$ucfFieldId);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $fieldId, $ccFieldId, 'new'.$ucfFieldId, 'submit');
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $fieldId, $ccFieldId, 'new'.$ucfFieldId, 'approve');
        }
        $contentInsert .= $this->xc->getXoopsCodeRedirectHeader($tableName.'.php', '?op=list', '2', "{$language}FORM_OK", true, $t."\t");
        $ret .= $this->phpcode->getPhpCodeConditions($insert, '', '', $contentInsert, false, $t);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Get Form');
        $ret .= $this->xc->getXoopsCodeTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret .= $this->xc->getXoopsCodeGetForm('form', $tableName, 'Obj', $t);
        $ret .= $this->xc->getXoopsCodeTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /*
    *  @private function getAdminPagesEdit
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $tableFieldname
    *  @param $language
    *  @param $fieldId
    *  @param $fieldInForm
    *  @return string
    */
    private function getAdminPagesEdit($moduleDirname, $table, $language, $fieldId, $fieldInForm, $t = '')
    {
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $tableFieldname = $table->getVar('table_fieldname');
        $stuTableName = strtoupper($tableName);
        $ucfTableName = ucfirst($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);
        $stuTableFieldname = strtoupper($tableFieldname);
        $ccFieldId = $this->getCamelCase($fieldId, false, true);

        $ret = $this->axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation = $this->axc->getAdminAddNavigation($tableName, $t);
        $ret .= $this->xc->getXoopsCodeTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $this->axc->getAdminItemButton($language, $tableName, $stuTableSoleName, '?op=new', 'add', $t);
            $ret .= $this->axc->getAdminItemButton($language, $tableName, $stuTableName, '', 'list', $t);
            $ret .= $this->xc->getXoopsCodeTplAssign('buttons', '$adminMenu->renderButton()', true, $t);
        }
        $ret .= $this->phpcode->getPhpCodeCommentLine('Get Form');
        $ret .= $this->xc->getXoopsCodeGet($tableName, $ccFieldId, 'Obj', false, false, $t);
        $ret .= $this->xc->getXoopsCodeGetForm('form', $tableName, 'Obj', $t);
        $ret .= $this->xc->getXoopsCodeTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /*
    *  @private function getAdminPagesDelete
    *  @param $tableName
    *  @param $language
    *  @param $fieldId
    *  @param $fieldMain
    *  @return string
    */
    private function getAdminPagesDelete($tableName, $language, $fieldId, $fieldMain, $t = '')
    {
        return $this->axc->getAdminCodeCaseDelete($language, $tableName, $fieldId, $fieldMain, $t);
    }

    /*
    *  @private function getAdminPagesUpdate
    *  @param $language
    *  @param $tableName
    *  @param $fieldId
    *  @param $fieldName
    *  @return string
    */
    private function getAdminPagesUpdate($language, $tableName, $fieldId, $fieldName, $t = '')
    {
        return $this->axc->getAdminCodeCaseUpdate($language, $tableName, $fieldId, $fieldName, $t);
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
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableCategory = $table->getVar('table_category');
        $language = $this->getLanguage($moduleDirname, 'AM');
        $fields = $this->tf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldInForm = null;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldInForm[] = $fields[$f]->getVar('field_inform');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
        }
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getAdminPagesHeader($moduleDirname, $tableName, $fieldId);
        $list = $this->getAdminPagesList($moduleDirname, $table, $language, $fields, $fieldId, $fieldInForm, $fieldMain);
        if (in_array(1, $fieldInForm)) {
            $new = $this->getAdminPagesNew($moduleDirname, $tableName, $fieldInForm, $language, "\t\t");
            $save = $this->getAdminPagesSave($moduleDirname, $tableName, $tableCategory, $language, $fields, $fieldId, $fieldMain, "\t\t");
            $edit = $this->getAdminPagesEdit($moduleDirname, $table, $language, $fieldId, $fieldInForm, "\t\t");
        }
        $delete = $this->getAdminPagesDelete($tableName, $language, $fieldId, $fieldMain, "\t\t");

        $cases = array('list' => array($list),
                        'new' => array($new),
                        'save' => array($save),
                        'edit' => array($edit),
                        'delete' => array($delete));
        $content .= $this->getAdminPagesSwitch($cases);
        $content .= $this->getInclude('footer');
        //
        $this->tf->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tf->renderFile();
    }
}
