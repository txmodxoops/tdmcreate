<?php

namespace XoopsModules\Tdmcreate\Files\Classes;

use XoopsModules\Tdmcreate;

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
 */

/**
 * Class ClassFormElements.
 */
class ClassFormElements extends Tdmcreate\Files\CreateAbstractClass
{
    /**
     * @static function getInstance
     * @param null
     *
     * @return ClassFormElements
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
     * @public function initForm
     *
     * @param $module
     * @param $table
     */
    public function initForm($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /**
     * @private function getXoopsFormText
     *
     * @param        $language
     * @param        $fieldName
     * @param        $fieldDefault
     * @param string $required
     * @return string
     */
    private function getXoopsFormText($language, $fieldName, $fieldDefault, $required = 'false')
    {
        $tf           = Tdmcreate\Files\CreateFile::getInstance();
        $pc           = Tdmcreate\Files\CreatePhpCode::getInstance();
        $cc           = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName = $tf->getCamelCase($fieldName, true);
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        if ('' != $fieldDefault) {
            $ret      = $pc->getPhpCodeCommentLine('Form Text', $ucfFieldName, "\t\t");
            $ret      .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', "'{$fieldDefault}'", "\$this->getVar('{$fieldName}')", "\t\t");
            $formText = $cc->getClassXoopsFormText('', $language, $fieldName, 20, 150, $ccFieldName, true);
            $ret      .= $cc->getClassAddElement('form', $formText . $required);
        } else {
            $ret      = $pc->getPhpCodeCommentLine('Form Text', $ucfFieldName, "\t\t");
            $formText = $cc->getClassXoopsFormText('', $language, $fieldName, 50, 255, "this->getVar('{$fieldName}')", true);
            $ret      .= $cc->getClassAddElement('form', $formText . $required);
        }

        return $ret;
    }

    /**
     * @private function getXoopsFormText
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTextArea($language, $fieldName, $required = 'false')
    {
        $tf           = Tdmcreate\Files\CreateFile::getInstance();
        $pc           = Tdmcreate\Files\CreatePhpCode::getInstance();
        $cc           = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName = $tf->getCamelCase($fieldName, true);
        $ret          = $pc->getPhpCodeCommentLine('Form Text Area', $ucfFieldName, "\t\t");
        $formTextArea = $cc->getClassXoopsFormTextArea('', $language, $fieldName, 4, 47, true);
        $ret          .= $cc->getClassAddElement('form', $formTextArea . $required);

        return $ret;
    }

    /**
     * @private function getXoopsFormDhtmlTextArea
     *
     * @param $language
     * @param $moduleDirname
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormDhtmlTextArea($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $tf           = Tdmcreate\Files\CreateFile::getInstance();
        $pc           = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc           = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc           = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $rpFieldName  = $tf->getRightString($fieldName);
        $ucfFieldName = $tf->getCamelCase($fieldName, true);

        $ret       = $pc->getPhpCodeCommentLine('Form editor', $ucfFieldName, "\t\t");
        $ret       .= $pc->getPhpCodeArray('editorConfigs', null, false, "\t\t");
        $getConfig = $xc->getXcGetConfig('editor_' . $rpFieldName);
        $configs   = [
            'name'   => "'{$fieldName}'",
            'value'  => "\$this->getVar('{$fieldName}', 'e')",
            'rows'   => 5,
            'cols'   => 40,
            'width'  => "'100%'",
            'height' => "'400px'",
            'editor' => $getConfig,
        ];
        foreach ($configs as $c => $d) {
            $ret .= $xc->getXcEqualsOperator("\$editorConfigs['{$c}']", $d, null, false, "\t\t");
        }
        $formEditor = $cc->getClassXoopsFormEditor('', $language, $fieldName, 'editorConfigs', true);
        $ret        .= $cc->getClassAddElement('form', $formEditor . $required);

        return $ret;
    }

    /**
     * @private function getXoopsFormCheckBox
     *
     * @param        $language
     * @param        $tableSoleName
     * @param        $fieldName
     * @param        $fieldElementId
     * @param string $required
     * @return string
     */
    private function getXoopsFormCheckBox($language, $tableSoleName, $fieldName, $fieldElementId, $required = 'false')
    {
        $tf               = Tdmcreate\Files\CreateFile::getInstance();
        $pc               = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc               = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc               = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $stuTableSoleName = mb_strtoupper($tableSoleName);
        $ucfFieldName     = $tf->getCamelCase($fieldName, true);
        $ccFieldName      = $tf->getCamelCase($fieldName, false, true);
        $t                = "\t\t";
        if (in_array(5, $fieldElementId) > 1) {
            $ret     = $pc->getPhpCodeCommentLine('Form Check Box', 'List Options ' . $ucfFieldName, $t);
            $ret     .= $xc->getXcEqualsOperator('$checkOption', '$this->getOptions()');
            $foreach = $cc->getClassXoopsFormCheckBox('check' . $ucfFieldName, '<hr />', $tableSoleName . '_option', '$checkOption', false, $t);
            $foreach .= $cc->getClassSetDescription('check' . $ucfFieldName, "{$language}{$stuTableSoleName}_OPTIONS_DESC", $t);
            $foreach .= $cc->getClassAddOption('check' . $ucfFieldName, "\$option, {$language}{$stuTableSoleName}_ . strtoupper(\$option)", $t . "\t");
            $ret     .= $pc->getPhpCodeForeach("{$tableName}All", false, false, 'option', $foreach, $t);
            $intElem = "\$check{$ucfFieldName}{$required}";
            $ret     .= $cc->getClassAddElement('form', $intElem, $t);
        } else {
            $ret     = $pc->getPhpCodeCommentLine('Form Check Box', $ucfFieldName, $t);
            $ret     .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
            $ret     .= $cc->getClassXoopsFormCheckBox('check' . $ucfFieldName, (string)$language, $fieldName, "\${$ccFieldName}", false, $t);
            $option  = "1, {$language}";
            $ret     .= $cc->getClassAddOption('check' . $ucfFieldName, $option, $t);
            $intElem = "\$check{$ucfFieldName}{$required}";
            $ret     .= $cc->getClassAddElement('form', $intElem, $t);
        }

        return $ret;
    }

    /**
     * @private function getXoopsFormHidden
     *
     * @param $fieldName
     *
     * @return string
     */
    private function getXoopsFormHidden($fieldName)
    {
        $tf           = Tdmcreate\Files\CreateFile::getInstance();
        $pc           = Tdmcreate\Files\CreatePhpCode::getInstance();
        $cc           = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName = $tf->getCamelCase($fieldName, true);
        $ret          = $pc->getPhpCodeCommentLine('Form Hidden', $ucfFieldName, "\t\t");
        $formHidden   = $cc->getClassXoopsFormHidden('', $fieldName, $fieldName, true, true);
        $ret          .= $cc->getClassAddElement('form', $formHidden);

        return $ret;
    }

    /**
     * @private function getXoopsFormImageList
     *
     * @param $language
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormImageList($language, $moduleDirname, $tableName, $tableSoleName, $fieldName, $required = 'false')
    {
        $tf              = Tdmcreate\Files\CreateFile::getInstance();
        $pc              = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc              = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc              = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $stuTableName    = mb_strtoupper($tableName);
        $rpFieldName     = $tf->getRightString($fieldName);
        $stuSoleName     = mb_strtoupper($tableSoleName . '_' . $rpFieldName);
        $ucfFieldName    = $tf->getCamelCase($fieldName, true);
        $ccFieldName     = $tf->getCamelCase($fieldName, false, true);
        $t               = "\t\t";
        $ret             = $pc->getPhpCodeCommentLine('Form Frameworks', 'Image Files ' . $ucfFieldName, $t);
        $ret             .= $xc->getXcEqualsOperator('$get' . $ucfFieldName, "\$this->getVar('{$fieldName}')", null, false, $t);
        $ret             .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$get' . $ucfFieldName, '$get' . $ucfFieldName, "'blank.gif'", $t);
        $ret             .= $xc->getXcEqualsOperator('$imageDirectory', "'/Frameworks/moduleclasses/icons/32'", null, false, $t);
        $ret             .= $cc->getClassXoopsFormElementTray('imageTray', $language . $stuSoleName, '<br>', $t);
        $sprintf         = $pc->getPhpCodeSprintf($language . 'FORM_IMAGE_PATH', '".{$imageDirectory}/"');
        $ret             .= $cc->getClassXoopsFormSelect('imageSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret             .= $xc->getXcXoopsImgListArray('imageArray', 'XOOPS_ROOT_PATH . $imageDirectory', $t);
        $contForeach     = $cc->getClassAddOption('imageSelect', '"{$image1}", $image1', "\t");
        $ret             .= $pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam   = "\"onchange='showImgSelected(\\\"image1\\\", \\\"{$fieldName}\\\", \\\"\".\$imageDirectory.\"\\\", \\\"\\\", \\\"\".XOOPS_URL.\"\\\")'\"";
        $ret             .= $cc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $ret             .= $cc->getClassAddElement('imageTray', '$imageSelect, false', $t);
        $paramLabel      = "\"<br><img src='\".XOOPS_URL.\"/\".\$imageDirectory.\"/\".\\\${$ccFieldName}.\\\"' name='image1' id='image1' alt='' style='max-width:100px' />\\\"";
        $xoopsFormLabel  = $cc->getClassXoopsFormLabel('', "''", "''", true, '');
        $ret             .= $cc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $ret             .= $pc->getPhpCodeCommentLine('Form', 'File', $t);
        $ret             .= $cc->getClassXoopsFormElementTray('fileSelectTray', "''", '<br>', $t);
        $getConfig       = $xc->getXcGetConfig('maxsize');
        $xoopsFormFile   = $cc->getClassXoopsFormFile('', $language . 'FORM_IMAGE_LIST_' . $stuTableName, 'attachedfile', $getConfig, true, '');
        $ret             .= $cc->getClassAddElement('fileSelectTray', $xoopsFormFile, $t);
        $xoopsFormLabel1 = $cc->getClassXoopsFormLabel('', "''", null, true, $t);
        $ret             .= $cc->getClassAddElement('fileSelectTray', $xoopsFormLabel1, $t);
        $ret             .= $cc->getClassAddElement('imageTray', '$fileSelectTray', $t);
        $ret             .= $cc->getClassAddElement('form', "\$imageTray{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectFile
     *
     * @param $language
     * @param $moduleDirname
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormSelectFile($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $tf             = Tdmcreate\Files\CreateFile::getInstance();
        $pc             = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc             = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc             = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName    = $tf->getCamelCase($fieldName, false, true);
        $ucfFieldName   = $tf->getCamelCase($fieldName, true);
        $t              = "\t\t\t";
        $ret            = $pc->getPhpCodeCommentLine('Form Frameworks', 'Image Files ' . $ucfFieldName, "\t\t");
        $contentIf      = $xc->getXcEqualsOperator('$get' . $ucfFieldName, "\$this->getVar('{$fieldName}')", null, false, $t);
        $contentIf      .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$get' . $ucfFieldName, '$get' . $ucfFieldName, "'blank.gif'", $t);
        $contentIf      .= $xc->getXcEqualsOperator('$uploadDirectory', "'/uploads/{$moduleDirname}/images/shots'", null, false, $t);
        $contentIf      .= $cc->getClassXoopsFormElementTray('imageTray', $language . 'FORM_IMAGE', '<br>', $t);
        $sprintf        = $pc->getPhpCodeSprintf($language . 'FORM_PATH', '".{$uploadDirectory}/"');
        $contentIf      .= $cc->getClassXoopsFormSelect('imageSelect', $sprintf, 'selected_image', "\${$moduleDirname}ShotImage", 5, 'false', false, $t);
        $contentIf      .= $xc->getXcXoopsImgListArray('imageArray', 'XOOPS_ROOT_PATH . $uploadDirectory', $t);
        $contForeach    = $cc->getClassAddOption('imageSelect', '"{$image1}", $image1', "\t");
        $contentIf      .= $pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam  = "\"onchange='showImgSelected(\\\"image3\\\", \\\"selected_image\\\", \\\"\".\$uploadDirectory.\"\\\", \\\"\\\", \\\"\".XOOPS_URL.\"\\\")'\"";
        $contentIf      .= $cc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $contentIf      .= $cc->getClassAddElement('imageTray', '$imageSelect, false', $t);
        $paramLabel     = "\"<br><img src='\".XOOPS_URL.\"/\".\$uploadDirectory.\"/\" . \${$moduleDirname}ShotImage . \"' name='image3' id='image3' alt='' style='max-width:100px' />\\\"";
        $xoopsFormLabel = $cc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');
        $contentIf      .= $cc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $contentIf      .= $pc->getPhpCodeCommentLine('Form', 'File', "\t\t");
        $contentIf      .= $cc->getClassXoopsFormElementTray('fileSelectTray', "''", '<br>', $t);
        $getConfigFile  = $xc->getXcGetConfig('maxuploadsize');
        $xoopsFormFile  = $cc->getClassXoopsFormFile('', $language . '_FORM_UPLOAD', 'attachedimage', $getConfigFile, true, '');
        $contentIf1     = $cc->getClassAddElement('fileSelectTray', $xoopsFormFile . $required, $t . "\t");

        $contentIf .= $cc->getClassAddElement('imageTray', '$fileSelectTray', $t);
        $contentIf .= $cc->getClassAddElement('form', "\$imageTray{$required}", $t);

        $contentIf = $pc->getPhpCodeConditions('$permissionUpload', ' == ', 'true', $contentIf1, false, $t);
        $getConfig = $xc->getXcGetConfig('useshots');
        $ret       .= $pc->getPhpCodeConditions($getConfig, null, null, $contentIf, false, "\t\t");

        return $ret;
    }

    /**
     * @private function getXoopsFormUrlFile
     *
     * @param   $language
     * @param   $moduleDirname
     * @param   $fieldName
     * @param   $fieldDefault
     * @param   $required
     *
     * @return string
     */
    private function getXoopsFormUrlFile($language, $moduleDirname, $fieldName, $fieldDefault, $required = 'false')
    {
        $tf            = Tdmcreate\Files\CreateFile::getInstance();
        $pc            = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc            = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc            = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName  = $tf->getCamelCase($fieldName, true);
        $t             = "\t\t";
        $ret           = $pc->getPhpCodeCommentLine('Form Url', 'Text File ' . $ucfFieldName, $t);
        $ret           .= $cc->getClassXoopsFormElementTray('formUrlFile', '_OPTIONS', '<br><br>', $t);
        $ret           .= $pc->getPhpCodeTernaryOperator('formUrl', '$this->isNew()', "'{$fieldDefault}'", "\$this->getVar('{$fieldName}')", $t);
        $ret           .= $cc->getClassXoopsFormText('formText', $language, $fieldName, 75, 255, 'formUrl', false, $t);
        $ret           .= $cc->getClassAddElement('formUrlFile', '$formText' . $required, $t);
        $getConfig     = $xc->getXcGetConfig('maxsize');
        $xoopsFormFile = $cc->getClassXoopsFormFile('', $language . 'UPLOAD', 'attachedfile', $getConfig, true, '');
        $ret           .= $cc->getClassAddElement('formUrlFile', $xoopsFormFile . $required, $t);
        $ret           .= $cc->getClassAddElement('form', '$formUrlFile', $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormUploadImage
     *
     * @param        $language
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $fieldName
     * @param string $required
     * @return string
     */
    private function getXoopsFormUploadImage($language, $moduleDirname, $tableName, $tableSoleName, $fieldName, $required = 'false')
    {
        $tf              = Tdmcreate\Files\CreateFile::getInstance();
        $pc              = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc              = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc              = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $stuTableName    = mb_strtoupper($tableName);
        $stuSoleName     = mb_strtoupper($tableSoleName);
        $ucfFieldName    = $tf->getCamelCase($fieldName, true);
        $ccFieldName     = $tf->getCamelCase($fieldName, false, true);
        $t               = "\t\t";
        $ret             = $pc->getPhpCodeCommentLine('Form', 'Image ' . $ucfFieldName, $t);
        $ret             .= $pc->getPhpCodeCommentLine("Form Image {$ucfFieldName}:", 'Select Uploaded Image ', $t);
        $ret             .= $xc->getXcEqualsOperator('$get' . $ucfFieldName, "\$this->getVar('{$fieldName}')", null, false, $t);
        $ret             .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$get' . $ucfFieldName, '$get' . $ucfFieldName, "'blank.gif'", $t);
        $ret             .= $xc->getXcEqualsOperator('$imageDirectory', "'/uploads/{$moduleDirname}/images/{$tableName}'", null, false, $t);
        $ret             .= $cc->getClassXoopsFormElementTray('imageTray', $language . 'FORM_UPLOAD', '<br>', $t);
        $sprintf         = $pc->getPhpCodeSprintf($language . 'FORM_IMAGE_PATH', '".{$imageDirectory}/"');
        $ret             .= $cc->getClassXoopsFormSelect('imageSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret             .= $xc->getXcXoopsImgListArray('imageArray', 'XOOPS_ROOT_PATH . $imageDirectory', $t);
        $contForeach     = $cc->getClassAddOption('imageSelect', '"{$image1}", $image1', "\t");
        $ret             .= $pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam   = "\"onchange='showImgSelected(\\\"image1\\\", \\\"{$fieldName}\\\", \\\"\".\$imageDirectory.\"\\\", \\\"\\\", \\\"\".XOOPS_URL.\"\\\")'\"";
        $ret             .= $cc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $ret             .= $cc->getClassAddElement('imageTray', '$imageSelect, false', $t);
        $paramLabel      = "\"<br><img src='\".XOOPS_URL.\"/\".\$imageDirectory.\"/\".\${$ccFieldName}.\"' name='image1' id='image1' alt='' style='max-width:100px' />\"";
        $xoopsFormLabel  = $cc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');
        $ret             .= $cc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $ret             .= $pc->getPhpCodeCommentLine("Form Image {$ucfFieldName}:", 'Upload Image', $t);
        $getConfig       = $xc->getXcGetConfig('maxsize');
        $xoopsFormFile   = $cc->getClassXoopsFormFile('imageTray', $language . 'FORM_UPLOAD_NEW', 'attachedfile', $getConfig, true, '');
        $contIf          = $cc->getClassAddElement('imageTray', $xoopsFormFile, $t . "\t");
        $formHidden      = $cc->getClassXoopsFormHidden('', $fieldName, $ccFieldName, true, true, $t, true);
        $contElse        = $cc->getClassAddElement('imageTray', $formHidden, $t . "\t");
        $ret             .= $pc->getPhpCodeConditions('$permissionUpload', null, null, $contIf, $contElse, "\t\t");
        $ret             .= $cc->getClassAddElement('form', "\$imageTray{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormUploadFile
     *
     * @param $language
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormUploadFile($language, $moduleDirname, $tableName, $fieldName, $required = 'false')
    {
        $tf             = Tdmcreate\Files\CreateFile::getInstance();
        $pc             = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc             = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc             = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName   = $tf->getCamelCase($fieldName, true);
        $stuTableName   = mb_strtoupper($tableName);
        $ccFieldName    = $tf->getCamelCase($fieldName, false, true);

        $t              = "\t\t\t";
        $ret            = $pc->getPhpCodeCommentLine('Form', 'File ' . $ucfFieldName, "\t\t");
        $ret            .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', "''", "\$this->getVar('{$fieldName}')", "\t\t");

        $uForm          = $cc->getClassXoopsFormElementTray('fileUploadTray', $language . 'FORM_UPLOAD', '<br>', $t);
        $xoopsFormLabel = $cc->getClassXoopsFormLabel('', $language . 'FORM_UPLOAD_FILE_' . $stuTableName, $ccFieldName, true, "\t\t", true);
        $condIf         = $cc->getClassAddElement('fileUploadTray', $xoopsFormLabel, $t . "\t");
        $uForm          .= $pc->getPhpCodeConditions('!$this->isNew()', null, null, $condIf, false, "\t\t\t");
        $getConfig      = $xc->getXcGetConfig('maxsize');
        $xoopsFormFile  = $cc->getClassXoopsFormFile('', "''", $fieldName, $getConfig, true, '');
        $uForm          .= $cc->getClassAddElement('fileUploadTray', $xoopsFormFile, $t);
        $uForm          .= $cc->getClassAddElement('form', '$fileUploadTray', $t);
        $formHidden     = $cc->getClassXoopsFormHidden('', $fieldName, $ccFieldName, true, true, "\t\t", true);
        $contElse       = $cc->getClassAddElement('form', $formHidden, $t);

        $ret           .= $pc->getPhpCodeConditions('$permissionUpload', null, null, $uForm, $contElse, "\t\t");

        return $ret;
    }

    /**
     * @private function getXoopsFormColorPicker
     *
     * @param $language
     * @param $moduleDirname
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormColorPicker($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $tf            = Tdmcreate\Files\CreateFile::getInstance();
        $pc            = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc            = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc            = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName  = $tf->getCamelCase($fieldName, true);
        $t             = "\t\t";
        $ret           = $pc->getPhpCodeCommentLine('Form Color', 'Picker ' . $ucfFieldName, $t);
        $getVar        = $xc->getXcGetVar('', 'this', $fieldName, true);
        $xoopsFormFile = $cc->getClassXoopsFormColorPicker('', $language, $fieldName, $getVar, true, '');
        $ret           .= $cc->getClassAddElement('form', $xoopsFormFile, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectBox
     *
     * @param $language
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormSelectBox($language, $moduleDirname, $tableName, $fieldName, $required = 'false')
    {
        $tf           = Tdmcreate\Files\CreateFile::getInstance();
        $pc           = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc           = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc           = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfTableName = ucfirst($tableName);
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        $t            = "\t\t";
        $ret          = $pc->getPhpCodeCommentLine($ucfTableName, 'handler', $t);
        $ret          .= $xc->getXoopsHandlerLine($tableName, $t);
        $ret          .= $pc->getPhpCodeCommentLine('Form', 'Select ' . $ucfTableName, $t);
        $ret          .= $cc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret          .= $cc->getClassAddOption($ccFieldName . 'Select', "'Empty'", $t);
        $ret          .= $cc->getClassAddOptionArray($ccFieldName . 'Select', "\${$tableName}Handler->getList()", $t);
        $ret          .= $cc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectUser
     *
     * @param        $language
     * @param        $moduleDirname
     * @param        $fieldName
     * @param string $required
     * @return string
     */
    private function getXoopsFormSelectUser($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $tf              = Tdmcreate\Files\CreateFile::getInstance();
        $pc              = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc              = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc              = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName    = $tf->getCamelCase($fieldName, true);
        $t               = "\t\t";
        $ret             = $pc->getPhpCodeCommentLine('Form Select', 'User ' . $ucfFieldName, $t);
        $getConfig       = $xc->getXcGetConfig('maxsize');
        $xoopsSelectUser = $cc->getClassXoopsFormSelectUser('', $language, $fieldName, 'false', $fieldName, true, $t);
        $ret             .= $cc->getClassAddElement('form', $xoopsSelectUser . $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormRadioYN
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormRadioYN($language, $fieldName, $required = 'false')
    {
        $tf           = Tdmcreate\Files\CreateFile::getInstance();
        $pc           = Tdmcreate\Files\CreatePhpCode::getInstance();
        $cc           = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        $ucfFieldName = $tf->getCamelCase($fieldName, true);
        $t            = "\t\t";
        $ret          = $pc->getPhpCodeCommentLine('Form Radio', 'Yes/No ' . $ucfFieldName, $t);
        $ret          .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
        $xoopsRadioYN = $cc->getClassXoopsFormRadioYN('', $language, $fieldName, $ccFieldName, true, $t);
        $ret          .= $cc->getClassAddElement('form', $xoopsRadioYN . $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormTextDateSelect
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTextDateSelect($language, $fieldName, $required = 'false')
    {
        $tf                  = Tdmcreate\Files\CreateFile::getInstance();
        $pc                  = Tdmcreate\Files\CreatePhpCode::getInstance();
        $cc                  = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $t                   = "\t\t";
        $ccFieldName         = $tf->getCamelCase($fieldName, false, true);
        $ucfFieldName        = $tf->getCamelCase($fieldName, true);
        $ret                 = $pc->getPhpCodeCommentLine('Form Text', 'Date Select ' . $ucfFieldName, $t);
        $ret                 .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
        $xoopsTextDateSelect = $cc->getClassXoopsFormTextDateSelect('', $language, $fieldName, $fieldName, $ccFieldName, true, $t);
        $ret                 .= $cc->getClassAddElement('form', $xoopsTextDateSelect . $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormTable
     *
     * @param $language
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldName
     * @param $fieldElement
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTable($language, $moduleDirname, $tableName, $fieldName, $fieldElement, $required = 'false')
    {
        $tc           = Tdmcreate\Helper::getInstance();
        $tf           = Tdmcreate\Files\CreateFile::getInstance();
        $pc           = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc           = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc           = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfTableName = ucfirst($tableName);
        $t            = "\t\t";
        if ($fieldElement > 15) {
            $fElement           = $tc->getHandler('fieldelements')->get($fieldElement);
            $rpFieldelementName = mb_strtolower(str_replace('Table : ', '', $fElement->getVar('fieldelement_name')));
            $ret                = $pc->getPhpCodeCommentLine('Form Table', $rpFieldelementName, $t);
            $ccFieldName        = $tf->getCamelCase($fieldName, false, true);
            $ret                .= $xc->getXoopsHandlerLine($rpFieldelementName, $t);
            $ret                .= $cc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
            $ret                .= $cc->getClassAddOptionArray($ccFieldName . 'Select', "\${$rpFieldelementName}Handler->getList()", $t);
            $ret                .= $cc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);
        }

        return $ret;
    }

    /**
     * @private  function getXoopsFormTopic
     *
     * @param        $language
     * @param        $moduleDirname
     * @param        $topicTableName
     * @param        $fieldId
     * @param        $fieldPid
     * @param        $fieldMain
     * @param string $required
     * @return string
     */
    private function getXoopsFormTopic($language, $moduleDirname, $topicTableName, $fieldId, $fieldPid, $fieldMain, $required = 'false')
    {
        $tf                = Tdmcreate\Files\CreateFile::getInstance();
        $pc                = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc                = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc                = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $ucfTopicTableName = ucfirst($topicTableName);
        $stlTopicTableName = mb_strtolower($topicTableName);
        $ccFieldPid        = $tf->getCamelCase($fieldPid, false, true);
        $t                 = "\t\t";
        $ret               = $pc->getPhpCodeCommentLine('Form Table', $ucfTopicTableName, $t);
        $ret               .= $xc->getXoopsHandlerLine($stlTopicTableName, $t);
        $ret               .= $cc->getClassCriteriaCompo('criteria', $t);
        $ret               .= $xc->getXcClearHandlerCount($stlTopicTableName . 'Count', $stlTopicTableName, '$criteria', $t);
        $contIf            = $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/tree', true, false, 'include', $t . "\t");
        $contIf            .= $xc->getXcClearHandlerAll($stlTopicTableName . 'All', $stlTopicTableName, '$criteria', $t . "\t");
        $contIf            .= $cc->getClassXoopsObjectTree($stlTopicTableName . 'Tree', $stlTopicTableName . 'All', $fieldId, $fieldPid, $t . "\t");
        $contIf            .= $cc->getClassXoopsMakeSelBox($ccFieldPid, $stlTopicTableName . 'Tree', $fieldPid, $fieldMain, '--', $fieldPid, $t . "\t");
        $formLabel         = $cc->getClassXoopsFormLabel('', $language, "\${$ccFieldPid}", true, '');
        $contIf            .= $cc->getClassAddElement('form', $formLabel, $t . "\t");
        $ret               .= $pc->getPhpCodeConditions("\${$stlTopicTableName}Count", null, null, $contIf, false, $t);
        $ret               .= $pc->getPhpCodeUnset('criteria', $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormTag
     *
     * @param $moduleDirname
     * @param $fieldId
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTag($moduleDirname, $fieldId, $required = 'false')
    {
        $pc        = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc        = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc        = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $t         = "\t\t";
        $ret       = $pc->getPhpCodeCommentLine('Use tag', 'module', $t);
        $isDir     = $pc->getPhpCodeIsDir("XOOPS_ROOT_PATH . '/modules/tag'");
        $ret       .= $pc->getPhpCodeTernaryOperator('dirTag', $isDir, 'true', 'false', $t);
        $paramIf   = '(' . $xc->getXcGetConfig('usetag') . ' == 1)';
        $condIf    = $pc->getPhpCodeTernaryOperator('tagId', '$this->isNew()', '0', "\$this->getVar('{$fieldId}')", $t . "\t");
        $condIf    .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'modules/tag/include/formtag', true, false, $type = 'include', $t . "\t");
        $paramElem = $cc->getClassXoopsFormTag('', 'tag', 60, 255, 'tagId', 0, true, '');
        $condIf    .= $cc->getClassAddElement('form', $paramElem . $required, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions($paramIf, ' && ', '$dirTag', $condIf, false, $t);

        return $ret;
    }

    /**
     * @public function renderElements
     * @param null
     * @return string
     */
    public function renderElements()
    {
        $tc            = Tdmcreate\Helper::getInstance();
        $tf            = Tdmcreate\Files\CreateFile::getInstance();
        $ttf           = Tdmcreate\Files\CreateTableFields::getInstance();
        $module        = $this->getModule();
        $table         = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName     = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $languageFunct = $tf->getLanguage($moduleDirname, 'AM');
        //$language_table = $languageFunct . strtoupper($tableName);
        $ret            = '';
        $fields         = $ttf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'), 'field_order ASC, field_id');
        $fieldId        = '';
        $fieldIdTopic   = '';
        $fieldPidTopic  = '';
        $fieldMainTopic = '';
        $fieldElementId = [];
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldDefault = $fields[$f]->getVar('field_default');
            $fieldElement = $fields[$f]->getVar('field_element');
            $fieldParent  = $fields[$f]->getVar('field_parent');
            $fieldInForm  = $fields[$f]->getVar('field_inform');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
            $rpFieldName = $tf->getRightString($fieldName);
            $language    = $languageFunct . mb_strtoupper($tableSoleName) . '_' . mb_strtoupper($rpFieldName);
            $required    = (1 == $fields[$f]->getVar('field_required')) ? ', true' : '';

            $fieldElements    = $tc->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');

            if (1 == $fieldInForm) {
                // Switch elements
                switch ($fieldElement) {
                    case 1:
                        break;
                    case 2:
                        $ret .= $this->getXoopsFormText($language, $fieldName, $fieldDefault, $required);
                        break;
                    case 3:
                        $ret .= $this->getXoopsFormTextArea($language, $fieldName, $required);
                        break;
                    case 4:
                        $ret .= $this->getXoopsFormDhtmlTextArea($language, $moduleDirname, $fieldName, $required);
                        break;
                    case 5:
                        $ret .= $this->getXoopsFormCheckBox($language, $tableSoleName, $fieldName, $fieldElementId, $required);
                        break;
                    case 6:
                        $ret .= $this->getXoopsFormRadioYN($language, $fieldName, $required);
                        break;
                    case 7:
                        $ret .= $this->getXoopsFormSelectBox($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case 8:
                        $ret .= $this->getXoopsFormSelectUser($language, $moduleDirname, $fieldName, $required);
                        break;
                    case 9:
                        $ret .= $this->getXoopsFormColorPicker($language, $moduleDirname, $fieldName, $required);
                        break;
                    case 10:
                        $ret .= $this->getXoopsFormImageList($languageFunct, $moduleDirname, $tableName, $tableSoleName, $fieldName, $required);
                        break;
                    case 11:
                        $ret .= $this->getXoopsFormSelectFile($language, $moduleDirname, $fieldName, $required);
                        break;
                    case 12:
                        $ret .= $this->getXoopsFormUrlFile($language, $moduleDirname, $fieldName, $fieldDefault, $required);
                        break;
                    case 13:
                        $ret .= $this->getXoopsFormUploadImage($languageFunct, $moduleDirname, $tableName, $tableSoleName, $fieldName, $required);
                        break;
                    case 14:
                        $ret .= $this->getXoopsFormUploadFile($languageFunct, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case 15:
                        $ret .= $this->getXoopsFormTextDateSelect($language, $fieldName, $required);
                        break;
                    default:
                        // If we use tag module
                        if (1 == $table->getVar('table_tag')) {
                            $ret .= $this->getXoopsFormTag($moduleDirname, $fieldId, $required);
                        }
                        // If we want to hide XoopsFormHidden() or field id
                        if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                            $ret .= $this->getXoopsFormHidden($fieldName);
                        }
                        break;
                }
                if ($fieldElement > 15) {
                    if ((1 == $fieldParent) || 1 == $table->getVar('table_category')) {
                        $fieldElements    = $tc->getHandler('fieldelements')->get($fieldElement);
                        $fieldElementMid  = $fieldElements->getVar('fieldelement_mid');
                        $fieldElementTid  = $fieldElements->getVar('fieldelement_tid');
                        $fieldElementName = $fieldElements->getVar('fieldelement_name');
                        $fieldNameDesc    = mb_substr($fieldElementName, mb_strrpos($fieldElementName, ':'), mb_strlen($fieldElementName));
                        $topicTableName   = str_replace(': ', '', $fieldNameDesc);
                        $fieldsTopics     = $ttf->getTableFields($fieldElementMid, $fieldElementTid);
                        foreach (array_keys($fieldsTopics) as $g) {
                            $fieldNameTopic = $fieldsTopics[$g]->getVar('field_name');
                            if ((0 == $g) && (1 == $table->getVar('table_autoincrement'))) {
                                $fieldIdTopic = $fieldNameTopic;
                            }
                            if (1 == $fieldsTopics[$g]->getVar('field_parent')) {
                                $fieldPidTopic = $fieldNameTopic;
                            }
                            if (1 == $fieldsTopics[$g]->getVar('field_main')) {
                                $fieldMainTopic = $fieldNameTopic;
                            }
                        }
                        $ret .= $this->getXoopsFormTopic($language, $moduleDirname, $topicTableName, $fieldIdTopic, $fieldPidTopic, $fieldMainTopic, $required);
                    } else {
                        $ret .= $this->getXoopsFormTable($language, $moduleDirname, $tableName, $fieldName, $fieldElement, $required);
                    }
                }
            }
        }
        unset($fieldElementId);

        return $ret;
    }
}
