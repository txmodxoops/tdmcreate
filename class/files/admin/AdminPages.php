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
 * @version         $Id: AdminPages.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class AdminPages.
 */
class AdminPages extends TDMCreateFile
{
    /**
     * @public function constructor
     * @param null
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     * @param null
     *
     * @return AdminPages
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

    /**
     * @private function getAdminPagesHeader
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldId
     * @return string
     */
    private function getAdminPagesHeader($moduleDirname, $tableName, $fieldId)
    {
        $pc        = TDMCreatePhpCode::getInstance();
        $xc        = TDMCreateXoopsCode::getInstance();
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $this->getInclude();
        $ret       .= $pc->getPhpCodeCommentLine('It recovered the value of argument op in URL$');
        $ret       .= $xc->getXcXoopsRequest('op', 'op', 'list');
        $ret       .= $pc->getPhpCodeCommentLine("Request {$fieldId}");
        $ret       .= $xc->getXcXoopsRequest($ccFieldId, $fieldId, '', 'Int');

        return $ret;
    }

    /**
     * @private function getAdminPagesSwitch
     * @param $cases
     *
     * @return string
     */
    private function getAdminPagesSwitch($cases = [])
    {
        $pc            = TDMCreatePhpCode::getInstance();
        $contentSwitch = $pc->getPhpCodeCaseSwitch($cases, true, false, "\t");

        return $pc->getPhpCodeSwitch('op', $contentSwitch);
    }

    /**
     * @private  function getAdminPagesList
     * @param        $moduleDirname
     * @param        $table
     * @param        $language
     * @param        $fields
     * @param        $fieldId
     * @param        $fieldInForm
     * @param        $fieldMain
     * @param string $t
     * @return string
     */
    private function getAdminPagesList($moduleDirname, $table, $language, $fields, $fieldId, $fieldInForm, $fieldMain, $t = '')
    {
        $pc  = TDMCreatePhpCode::getInstance();
        $xc  = TDMCreateXoopsCode::getInstance();
        $axc = AdminXoopsCode::getInstance();

        $stuModuleDirname = strtoupper($moduleDirname);
        $tableName        = $table->getVar('table_name');
        $tableSoleName    = $table->getVar('table_solename');
        $stuTableName     = strtoupper($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);

        $ret        = $pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret        .= $xc->getXcAddStylesheet('style', $t);
        $ret        .= $xc->getXcXoopsRequest('start', 'start', '0', 'Int', false, $t);
        $adminpager = $xc->getXcGetConfig($moduleDirname, 'adminpager');
        $ret        .= $xc->getXcXoopsRequest('limit', 'limit', $adminpager, 'Int', false, $t);
        $ret        .= $axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation = $axc->getAdminDisplayNavigation($tableName);
        $ret        .= $xc->getXcTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $axc->getAdminItemButton($language, $tableName, $stuTableSoleName, '?op=new', 'add', $t);
            $ret .= $xc->getXcTplAssign('buttons', '$adminObject->displayButton(\'left\')', true, $t);
        }

        $ret .= $xc->getXcObjHandlerCount($tableName, $t);
        $ret .= $xc->getXcObjHandlerAll($tableName, '', '$start', '$limit', $t);
        $ret .= $xc->getXcTplAssign("{$tableName}_count", "\${$tableName}Count", true, $t);
        $ret .= $xc->getXcTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL", true, $t);
        $ret .= $xc->getXcTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL", true, $t);

        $ret            .= $pc->getPhpCodeCommentLine('Table view', $tableName, $t);
        $contentForeach = $xc->getXcGetValues($tableName, $tableSoleName, 'i', false, "\t");
        $contentForeach .= $xc->getXcXoopsTplAppend("{$tableName}_list", "\${$tableSoleName}", $t . "\t\t");
        $contentForeach .= $pc->getPhpCodeUnset($tableSoleName, $t . "\t\t");
        $condIf         = $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, $t . "\t");
        $condIf         .= $xc->getXcPageNav($tableName, $t . "\t");
        $condElse       = $xc->getXcTplAssign('error', "{$language}THEREARENT_{$stuTableName}", true, $t . "\t");
        $ret            .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf, $condElse, $t);

        return $ret;
    }

    /**
     * @private function getAdminPagesNew
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $fieldInForm
     * @param        $language
     * @param string $t
     * @return string
     */
    private function getAdminPagesNew($moduleDirname, $tableName, $fieldInForm, $language, $t = '')
    {
        $pc  = TDMCreatePhpCode::getInstance();
        $xc  = TDMCreateXoopsCode::getInstance();
        $axc = AdminXoopsCode::getInstance();

        $stuTableName = strtoupper($tableName);
        $ret          = $axc->getAdminTemplateMain($moduleDirname, $tableName);
        $navigation   = $axc->getAdminDisplayNavigation($tableName);
        $ret          .= $xc->getXcTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $axc->getAdminItemButton($language, $tableName, $stuTableName, '', 'list', $t);
            $ret .= $xc->getXcTplAssign('buttons', '$adminObject->displayButton(\'left\')', true, $t);
        }
        $ret .= $pc->getPhpCodeCommentLine('Get Form', null, $t);
        $ret .= $xc->getXcObjHandlerCreate($tableName, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /**
     * @private function getPermissionsSave
     * @param $moduleDirname
     * @param $fieldId
     * @param $ccFieldId
     * @param $newFieldId
     * @param $perm
     *
     * @return string
     */
    private function getPermissionsSave($moduleDirname, $fieldId, $ccFieldId, $newFieldId, $perm = 'view')
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();

        $ret     = $pc->getPhpCodeCommentLine('Permission to', $perm, "\t\t\t");
        $content = $xc->getXcAddRight('gpermHandler', "{$moduleDirname}_{$perm}", '$permId', '$onegroupId', "\$GLOBALS['xoopsModule']->getVar('mid')", false, "\t");
        $foreach = $pc->getPhpCodeForeach("_POST['groups_{$perm}']", false, false, 'onegroupId', $content, "\t\t\t\t");
        $ret     .= $pc->getPhpCodeConditions("isset(\$_POST['groups_{$perm}'])", null, null, $foreach, false, "\t\t\t");

        return $ret;
    }

    /**
     * @private function getAdminPagesSave
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $tableCategory
     * @param        $tableSoleName
     * @param        $language
     * @param        $fields
     * @param        $fieldId
     * @param        $fieldMain
     * @param string $t
     * @return string
     */
    private function getAdminPagesSave($moduleDirname, $tableName, $tableCategory, $tableSoleName, $language, $fields, $fieldId, $fieldMain, $t = '')
    {
        $pc  = TDMCreatePhpCode::getInstance();
        $xc  = TDMCreateXoopsCode::getInstance();
        $axc = AdminXoopsCode::getInstance();

        $ccFieldId          = $this->getCamelCase($fieldId, false, true);
        $ret                = $pc->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $xc->getXcSecurityCheck('!');
        $securityError      = $xc->getXcSecurityErrors();
        $implode            = $pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t");
        $ret                .= $pc->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, $t);

        $isset       = $pc->getPhpCodeIsset($ccFieldId);
        $contentIf   = $xc->getXcGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t . "\t");
        $contentElse = $xc->getXcObjHandlerCreate($tableName, "\t\t\t");
        $ret         .= $pc->getPhpCodeConditions($isset, '', '', $contentIf, $contentElse, $t);
        $ret         .= $pc->getPhpCodeCommentLine('Set Vars', null, "\t\t");
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldType    = $fields[$f]->getVar('field_type');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $xc->getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName, $t);
                        break;
                    case 10:
                        $ret .= $axc->getAxcImageListSetVar($moduleDirname, $tableName, $fieldName, $t);
                        break;
                    case 12:
                        $ret .= $axc->getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName, true, $t);
                        break;
                    case 13:
                        if (1 == $fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        $ret .= $axc->getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain, $t);
                        break;
                    case 14:
                        $ret .= $axc->getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName, false, $t);
                        break;
                    case 15:
                        $ret .= $xc->getXcTextDateSelectSetVar($tableName, $tableSoleName, $fieldName, $t);
                        break;
                    default:
                        if (2 == $fieldType || 7 == $fieldType || 8 == $fieldType) {
                            $ret .= $xc->getXcSetVar($tableName, $fieldName, "isset(\$_POST['{$fieldName}']) ? \$_POST['{$fieldName}'] : 0", $t);
                        } else {
                            $ret .= $xc->getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']", $t);
                        }
                        break;
                }
            }
        }
        $ret           .= $pc->getPhpCodeCommentLine('Insert Data', null, "\t\t");
        $insert        = $xc->getXcInsert($tableName, $tableName, 'Obj');
        $contentInsert = '';
        if (1 == $tableCategory) {
            $ucfTableName  = ucfirst($tableName);
            $contentInsert = $xc->getXcEqualsOperator('$newCatId', "\${$tableName}Obj->getNewInsertedId{$ucfTableName}()", null, false, $t . "\t");
            $ucfFieldId    = $this->getCamelCase($fieldId, true);
            $contentInsert .= $pc->getPhpCodeTernaryOperator('permId', "isset(\$_REQUEST['{$fieldId}'])", "\${$ccFieldId}", "\$new{$ucfFieldId}", $t . "\t");
            $contentInsert .= $xc->getXcEqualsOperator('$gpermHandler', "xoops_getHandler('groupperm')", null, false, $t . "\t");
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $fieldId, $ccFieldId, 'new' . $ucfFieldId);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $fieldId, $ccFieldId, 'new' . $ucfFieldId, 'submit');
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $fieldId, $ccFieldId, 'new' . $ucfFieldId, 'approve');
        }
        $contentInsert .= $xc->getXcRedirectHeader($tableName . '', '?op=list', '2', "{$language}FORM_OK", true, $t . "\t");
        $ret           .= $pc->getPhpCodeConditions($insert, '', '', $contentInsert, false, $t);
        $ret           .= $pc->getPhpCodeCommentLine('Get Form', null, "\t\t");
        $ret           .= $xc->getXcTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret           .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret           .= $xc->getXcTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /**
     * @private  function getAdminPagesEdit
     * @param        $moduleDirname
     * @param        $table
     * @param        $language
     * @param        $fieldId
     * @param        $fieldInForm
     * @param string $t
     * @return string
     */
    private function getAdminPagesEdit($moduleDirname, $table, $language, $fieldId, $fieldInForm, $t = '')
    {
        $pc  = TDMCreatePhpCode::getInstance();
        $xc  = TDMCreateXoopsCode::getInstance();
        $axc = AdminXoopsCode::getInstance();

        $tableName         = $table->getVar('table_name');
        $tableSoleName     = $table->getVar('table_solename');
        $tableFieldname    = $table->getVar('table_fieldname');
        $stuTableName      = strtoupper($tableName);
        $ucfTableName      = ucfirst($tableName);
        $stuTableSoleName  = strtoupper($tableSoleName);
        $stuTableFieldname = strtoupper($tableFieldname);
        $ccFieldId         = $this->getCamelCase($fieldId, false, true);

        $ret        = $axc->getAdminTemplateMain($moduleDirname, $tableName);
        $navigation = $axc->getAdminDisplayNavigation($tableName);
        $ret        .= $xc->getXcTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $axc->getAdminItemButton($language, $tableName, $stuTableSoleName, '?op=new', 'add', $t);
            $ret .= $axc->getAdminItemButton($language, $tableName, $stuTableName, '', 'list', $t);
            $ret .= $xc->getXcTplAssign('buttons', '$adminObject->displayButton(\'left\')', true, $t);
        }
        $ret .= $pc->getPhpCodeCommentLine('Get Form', null, "\t\t");
        $ret .= $xc->getXcGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /**
     * @private function getAdminPagesDelete
     * @param        $tableName
     * @param        $language
     * @param        $fieldId
     * @param        $fieldMain
     * @param string $t
     * @return string
     */
    private function getAdminPagesDelete($tableName, $language, $fieldId, $fieldMain, $t = '')
    {
        $axc = AdminXoopsCode::getInstance();

        return $axc->getAdminCodeCaseDelete($language, $tableName, $fieldId, $fieldMain, $t);
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $tf  = TDMCreateFile::getInstance();
        $new = $save = $edit = '';

        $module        = $this->getModule();
        $table         = $this->getTable();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName     = $table->getVar('table_name');
        $tableCategory = $table->getVar('table_category');
        $tableSoleName = $table->getVar('table_solename');
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $fields        = $tf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldInForm   = null;
        foreach (array_keys($fields) as $f) {
            $fieldName     = $fields[$f]->getVar('field_name');
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
        $list    = $this->getAdminPagesList($moduleDirname, $table, $language, $fields, $fieldId, $fieldInForm, $fieldMain, "\t\t");
        if (in_array(1, $fieldInForm)) {
            $new  = $this->getAdminPagesNew($moduleDirname, $tableName, $fieldInForm, $language, "\t\t");
            $save = $this->getAdminPagesSave($moduleDirname, $tableName, $tableCategory, $tableSoleName, $language, $fields, $fieldId, $fieldMain, "\t\t");
            $edit = $this->getAdminPagesEdit($moduleDirname, $table, $language, $fieldId, $fieldInForm, "\t\t");
        }
        $delete = $this->getAdminPagesDelete($tableName, $language, $fieldId, $fieldMain, "\t\t");

        $cases   = [
            'list'   => [$list],
            'new'    => [$new],
            'save'   => [$save],
            'edit'   => [$edit],
            'delete' => [$delete],
        ];
        $content .= $this->getAdminPagesSwitch($cases);
        $content .= $this->getInclude('footer');

        $tf->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $tf->renderFile();
    }
}
