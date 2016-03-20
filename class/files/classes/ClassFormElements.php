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
 * @version         $Id: ClassFormElements.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class ClassFormElements.
 */
class ClassFormElements extends TDMCreateAbstract
{
    /*
    * @var string
    */
    private $tc = null;

    /*
    * @var string
    */
    private $tf = null;

    /*
    * @var string
    */
    private $pc = null;

    /*
    * @var mixed
    */
    private $xc = null;

    /*
    * @var mixed
    */
    private $cc = null;

    /*
    * @var mixed
    */
    private $ttf = null;

    /**
     *  @public function constructor
     *
     *  @param null    
     */
    public function __construct()
    {
        $this->tc = TDMCreateHelper::getInstance();
        $this->tf = TDMCreateFile::getInstance();
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->cc = ClassXoopsCode::getInstance();
        $this->ttf = TDMCreateTableFields::getInstance();
    }

    /*
     *  @static function &getInstance
     *  @param null
     *
     * @return ClassFormElements
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
    *  @public function initForm
     *
     * @param $module
     * @param $table
     */
    public function initForm($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /*
    *  @private function getXoopsFormText
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormText($language, $fieldName, $fieldDefault, $required = 'false')
    {
        $ucfFieldName = $this->tf->getCamelCase($fieldName, true);
        $ccFieldName = $this->tf->getCamelCase($fieldName, false, true);
        if ($fieldDefault != '') {
            $ret = $this->pc->getPhpCodeCommentLine('Form Text', $ucfFieldName);
            $ret .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', "'{$fieldDefault}'", "\$this->getVar('{$fieldName}')");
            $formText = $this->cc->getClassXoopsFormText('', $language, $fieldName, 20, 150, "{$ccFieldName}", true);
            $ret .= $this->cc->getClassAddElement('form', $formText.$required);
        } else {
            $ret = $this->pc->getPhpCodeCommentLine('Form Text', $ucfFieldName);
            $formText = $this->cc->getClassXoopsFormText('', $language, $fieldName, 50, 255, "this->getVar('{$fieldName}')", true);
            $ret .= $this->cc->getClassAddElement('form', $formText.$required);
        }

        return $ret;
    }

    /*
    *  @private function getXoopsFormText
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTextArea($language, $fieldName, $required = 'false')
    {
        $ucfFieldName = $this->tf->getCamelCase($fieldName, true);
        $ret = $this->pc->getPhpCodeCommentLine('Form Text Area', $ucfFieldName, "\t\t");
        $formTextArea = $this->cc->getClassXoopsFormTextArea('', $language, $fieldName, 4, 47, true);
        $ret .= $this->cc->getClassAddElement('form', $formTextArea.$required);

        return $ret;
    }

    /*
    *  @private function getXoopsFormDhtmlTextArea
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
        $rpFieldName = $this->tf->getRightString($fieldName);
        $ucfFieldName = $this->tf->getCamelCase($fieldName, true);

        $ret = $this->pc->getPhpCodeCommentLine('Form editor', $ucfFieldName, "\t\t");
        $ret .= $this->pc->getPhpCodeArray('editorConfigs', null, false, "\t\t");
        $getConfig = $this->xc->getXcGetConfig('this->'.$moduleDirname, $moduleDirname.'_editor_'.$rpFieldName);
        $configs = array('name' => "'{$fieldName}'", 'value' => "\$this->getVar('{$fieldName}', 'e')", 'rows' => 5, 'cols' => 40,
                        'width' => "'100%'", 'height' => "'400px'", 'editor' => $getConfig, );
        foreach ($configs as $c => $d) {
            $ret .= $this->xc->getXcEqualsOperator("\$editorConfigs['{$c}']", $d,  null, false, "\t\t");
        }
        $formEditor = $this->cc->getClassXoopsFormEditor('', $language, $fieldName, 'editorConfigs', true);
        $ret .= $this->cc->getClassAddElement('form', $formEditor.$required);

        return $ret;
    }

    /*
    *  @private function getXoopsFormCheckBox
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormCheckBox($language, $tableSoleName, $fieldName, $fieldElementId, $required = 'false')
    {
        $stuTableSoleName = strtoupper($tableSoleName);
        $ucfFieldName = $this->tf->getCamelCase($fieldName, true);
        $ccFieldName = $this->tf->getCamelCase($fieldName, false, true);
        $t = "\t\t";
        if (in_array(5, $fieldElementId) > 1) {
            $ret = $this->pc->getPhpCodeCommentLine('Form Check Box', 'List Options '.$ucfFieldName, $t);
            $ret .= $this->xc->getXcEqualsOperator('$checkOption', '$this->getOptions()');
            $foreach = $this->cc->getClassXoopsFormCheckBox('check'.$ucfFieldName, '<hr />', $tableSoleName.'_option', '$checkOption', false, $t);
            $foreach .= $this->cc->getClassSetDescription('check'.$ucfFieldName, "{$language}{$stuTableSoleName}_OPTIONS_DESC", $t);
            $foreach .= $this->cc->getClassAddOption('check'.$ucfFieldName, "\$option, {$language}{$stuTableSoleName}_ . strtoupper(\$option)", $t."\t");
            $ret .= $this->pc->getPhpCodeForeach("{$tableName}All", false, false, 'option', $foreach, $t);
            $intElem = "\$check{$ucfFieldName}{$required}";
            $ret .= $this->cc->getClassAddElement('form', $intElem, $t);
        } else {
            $ret = $this->pc->getPhpCodeCommentLine('Form Check Box', $ucfFieldName, $t);
            $ret .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
            $ret .= $this->cc->getClassXoopsFormCheckBox('check'.$ucfFieldName, "{$language}", $fieldName, "\${$ccFieldName}", false, $t);
            $option = "1, {$language}";
            $ret .= $this->cc->getClassAddOption('check'.$ucfFieldName, $option, $t);
            $intElem = "\$check{$ucfFieldName}{$required}";
            $ret .= $this->cc->getClassAddElement('form', $intElem, $t);
        }

        return $ret;
    }

    /*
    *  @private function getXoopsFormHidden
     *
     * @param $fieldName
     *
     * @return string
     */
    private function getXoopsFormHidden($fieldName)
    {
        $ucfFieldName = $this->tf->getCamelCase($fieldName, true);
        $ret = $this->pc->getPhpCodeCommentLine('Form Hidden', $ucfFieldName, "\t\t");
        $formHidden = $this->cc->getClassXoopsFormHidden('', $fieldName, $fieldName, true, true);
        $ret .= $this->cc->getClassAddElement('form', $formHidden);

        return $ret;
    }

    /*
    *  @private function getXoopsFormImageList
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
        $stuTableName = strtoupper($tableName);
        $rpFieldName = $this->tf->getRightString($fieldName);
        $stuSoleName = strtoupper($tableSoleName.'_'.$rpFieldName);
        $ucfFieldName = $this->tf->getCamelCase($fieldName, true);
        $ccFieldName = $this->tf->getCamelCase($fieldName, false, true);
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form Frameworks', 'Image Files', $t);
        $ret .= $this->xc->getXcEqualsOperator('$get'.$ucfFieldName, "\$this->getVar('{$fieldName}')", null, false, $t);
        $ret .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$get'.$ucfFieldName, '$get'.$ucfFieldName, "'blank.gif'", $t);
        $ret .= $this->xc->getXcEqualsOperator('$imageDirectory', "'/Frameworks/moduleclasses/icons/32'", null, false, $t);
        $ret .= $this->cc->getClassXoopsFormElementTray('imageTray', $language.$stuSoleName, '<br />', $t);
        $sprintf = $this->pc->getPhpCodeSprintf($language.'FORM_IMAGE_PATH', '".{$imageDirectory}/"');
        $ret .= $this->cc->getClassXoopsFormSelect('imageSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret .= $this->xc->getXcXoopsImgListArray('imageArray', 'XOOPS_ROOT_PATH . $imageDirectory', $t);
        $contForeach = $this->cc->getClassAddOption('imageSelect', '"{$image1}", $image1', $t."\t");
        $ret .= $this->pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam = "\"onchange='showImgSelected(\\\"image1\\\", \\\"{$fieldName}\\\", \\\"\".\$imageDirectory.\"\\\", \\\"\\\", \\\"\".XOOPS_URL.\"\\\")'\"";
        $ret .= $this->cc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $ret .= $this->cc->getClassAddElement('imageTray', '$imageSelect, false', $t);
        $paramLabel = "\"<br /><img src='\".XOOPS_URL.\"/\".\$imageDirectory.\"/\".\\\${$ccFieldName}.\\\"' name='image1' id='image1' alt='' />\\\"";
        $xoopsFormLabel = $this->cc->getClassXoopsFormLabel('', "''", "''", true, '');
        $ret .= $this->cc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $ret .= $this->pc->getPhpCodeCommentLine('Form', 'File', $t);
        $ret .= $this->cc->getClassXoopsFormElementTray('fileSelectTray', "''", '<br />', $t);
        $getConfig = $this->xc->getXcGetConfig('this->'.$moduleDirname, 'maxsize');
        $xoopsFormFile = $this->cc->getClassXoopsFormFile('', $language.'FORM_IMAGE_LIST_'.$stuTableName, 'attachedfile', $getConfig, true, '');
        $ret .= $this->cc->getClassAddElement('fileSelectTray', $xoopsFormFile, $t);
        $xoopsFormLabel1 = $this->cc->getClassXoopsFormLabel('', "''", null, true, $t);
        $ret .= $this->cc->getClassAddElement('fileSelectTray', $xoopsFormLabel1, $t);
        $ret .= $this->cc->getClassAddElement('imageTray', '$fileSelectTray', $t);
        $ret .= $this->cc->getClassAddElement('form', "\$imageTray{$required}", $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormSelectFile
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
        $ccFieldName = $this->tf->getCamelCase($fieldName, false, true);
        $ucfFieldName = $this->tf->getCamelCase($fieldName, true);
        $t = "\t\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form Frameworks', 'Image Files', $t);
        $contentIf = $this->xc->getXcEqualsOperator('$get'.$ucfFieldName, "\$this->getVar('{$fieldName}')", null, false, $t);
        $contentIf .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$get'.$ucfFieldName, '$get'.$ucfFieldName, "'blank.gif'", $t);
        $contentIf .= $this->xc->getXcEqualsOperator('$uploadDirectory', "'/uploads/{$moduleDirname}/images/shots'", null, false, $t);
        $contentIf .= $this->cc->getClassXoopsFormElementTray('imageTray', $language.'FORM_IMAGE', '<br />', $t);
        $sprintf = $this->pc->getPhpCodeSprintf($language.'FORM_PATH', '".{$uploadDirectory}/"');
        $contentIf .= $this->cc->getClassXoopsFormSelect('imageSelect', $sprintf, 'selected_image', "\${$moduleDirname}ShotImage", 5, 'false', false, $t);
        $contentIf .= $this->xc->getXcXoopsImgListArray('imageArray', 'XOOPS_ROOT_PATH . $uploadDirectory', $t);
        $contForeach = $this->cc->getClassAddOption('imageSelect', '"{$image1}", $image1', $t."\t");
        $contentIf .= $this->pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam = "\"onchange='showImgSelected(\\\"image3\\\", \\\"selected_image\\\", \\\"\".\$uploadDirectory.\"\\\", \\\"\\\", \\\"\".XOOPS_URL.\"\\\")'\"";
        $contentIf .= $this->cc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $contentIf .= $this->cc->getClassAddElement('imageTray', '$imageSelect, false', $t);
        $paramLabel = "\"<br /><img src='\".XOOPS_URL.\"/\".\$uploadDirectory.\"/\" . \${$moduleDirname}ShotImage . \"' name='image3' id='image3' alt='' />\\\"";
        $xoopsFormLabel = $this->cc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');
        $contentIf .= $this->cc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $contentIf .= $this->pc->getPhpCodeCommentLine('Form', 'File', $t);
        $contentIf .= $this->cc->getClassXoopsFormElementTray('fileSelectTray', "''", '<br />', $t);
        $getConfigFile = $this->xc->getXcGetConfig('this->'.$moduleDirname, 'maxuploadsize');
        $xoopsFormFile = $this->cc->getClassXoopsFormFile('', $language.'_FORM_UPLOAD', 'attachedimage', $getConfigFile, true, '');
        $contentIf1 = $this->cc->getClassAddElement('fileSelectTray', $xoopsFormFile.$required, $t);

        $contentIf .= $this->cc->getClassAddElement('imageTray', '$fileSelectTray', $t);
        $contentIf .= $this->cc->getClassAddElement('form', "\$imageTray{$required}", $t);

        $contentIf = $this->pc->getPhpCodeConditions('$permissionUpload', ' == ', 'true', $contentIf1, false, $t."\t");
        $getConfig = $this->xc->getXcGetConfig('this->'.$moduleDirname, 'useshots');
        $ret .= $this->pc->getPhpCodeConditions($getConfig, null, null, $contentIf, false, "\t\t");

        return $ret;
    }

    /*
    *  @private function getXoopsFormUrlFile
     *
     * @param   $language
     * @param   $moduleDirname
     * @param   $fieldName
     * @param   $fieldDefault
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormUrlFile($language, $moduleDirname, $fieldName, $fieldDefault, $required = 'false')
    {
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form Url', 'Text File', $t);
        $ret .= $this->cc->getClassXoopsFormElementTray('formUrlFile', '_OPTIONS', '<br /><br />', $t);
        $ret .= $this->pc->getPhpCodeTernaryOperator('formUrl', '$this->isNew()', "'{$fieldDefault}'", "\$this->getVar('{$fieldName}')", $t);
        $ret .= $this->cc->getClassXoopsFormText('formText', $language, $fieldName, 75, 255, 'formUrl', false, $t);
        $ret .= $this->cc->getClassAddElement('formUrlFile', '$formText'.$required, $t);
        $getConfig = $this->xc->getXcGetConfig('this->'.$moduleDirname, 'maxsize');
        $xoopsFormFile = $this->cc->getClassXoopsFormFile('', $language.'UPLOAD', 'attachedfile', $getConfig, true, '');
        $ret .= $this->cc->getClassAddElement('formUrlFile', $xoopsFormFile.$required, $t);
        $ret .= $this->cc->getClassAddElement('form', '$formUrlFile', $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormUploadImage
     *
     * @param $language
     * @param $moduleDirname
     * @param $tableName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormUploadImage($language, $moduleDirname, $tableName, $tableSoleName, $fieldName, $required = 'false')
    {
        $stuTableName = strtoupper($tableName);
        $stuSoleName = strtoupper($tableSoleName);
        $ucfFieldName = $this->tf->getCamelCase($fieldName, true);
        $ccFieldName = $this->tf->getCamelCase($fieldName, false, true);
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form Upload', 'Image', $t);
        $ret .= $this->xc->getXcEqualsOperator('$get'.$ucfFieldName, "\$this->getVar('{$fieldName}')", null, false, $t);
        $ret .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$get'.$ucfFieldName, '$get'.$ucfFieldName, "'blank.gif'", $t);
        $ret .= $this->xc->getXcEqualsOperator('$imageDirectory', "'/uploads/{$moduleDirname}/images/{$tableName}'", null, false, $t);
        $ret .= $this->cc->getClassXoopsFormElementTray('imageTray', '_OPTIONS', '<br />', $t);
        $sprintf = $this->pc->getPhpCodeSprintf($language.'FORM_IMAGE_PATH', '".{$imageDirectory}/"');
        $ret .= $this->cc->getClassXoopsFormSelect('imageSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret .= $this->xc->getXcXoopsImgListArray('imageArray', 'XOOPS_ROOT_PATH . $imageDirectory', $t);
        $contForeach = $this->cc->getClassAddOption('imageSelect', '"{$image1}", $image1', $t."\t");
        $ret .= $this->pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam = "\"onchange='showImgSelected(\\\"image1\\\", \\\"{$fieldName}\\\", \\\"\".\$imageDirectory.\"\\\", \\\"\\\", \\\"\".XOOPS_URL.\"\\\")'\"";
        $ret .= $this->cc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $ret .= $this->cc->getClassAddElement('imageTray', '$imageSelect, false', $t);
        $paramLabel = "\"<br /><img src='\".XOOPS_URL.\"/\".\$imageDirectory.\"/\".\${$ccFieldName}.\"' name='image1' id='image1' alt='' style='max-width:100px' />\"";
        $xoopsFormLabel = $this->cc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');
        $ret .= $this->cc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $ret .= $this->pc->getPhpCodeCommentLine('Form', 'File', $t);
        $ret .= $this->cc->getClassXoopsFormElementTray('fileSelectTray', "''", '<br />', $t);
        $getConfig = $this->xc->getXcGetConfig('this->'.$moduleDirname, 'maxsize');
        $xoopsFormFile = $this->cc->getClassXoopsFormFile('', $language.'FORM_UPLOAD_IMAGE_'.$stuTableName, 'attachedfile', $getConfig, true, '');
        $ret .= $this->cc->getClassAddElement('fileSelectTray', $xoopsFormFile, $t);
        $xoopsFormLabel1 = $this->cc->getClassXoopsFormLabel('', "''", null, true);
        $ret .= $this->cc->getClassAddElement('fileSelectTray', $xoopsFormLabel1, $t);
        $ret .= $this->cc->getClassAddElement('imageTray', '$fileSelectTray', $t);
        $ret .= $this->cc->getClassAddElement('form', "\$imageTray{$required}", $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormUploadFile
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
        $stuTableName = strtoupper($tableName);
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form', 'File', $t);
        $getConfig = $this->xc->getXcGetConfig('this->'.$moduleDirname, 'maxsize');
        $xoopsFormFile = $this->cc->getClassXoopsFormFile('', $language.'_'.$stuTableName, $fieldName, $getConfig, true, '');
        $ret .= $this->cc->getClassAddElement('form', $xoopsFormFile, $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormColorPicker
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
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form Color', 'Picker', $t);
        $getVar = $this->xc->getXcGetVar('', 'this', $fieldName, true);
        $xoopsFormFile = $this->cc->getClassXoopsFormColorPicker('', $language, $fieldName, $getVar, true, '');
        $ret .= $this->cc->getClassAddElement('form', $xoopsFormFile, $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormSelectBox
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
        $ucfTableName = ucfirst($tableName);
        $ccFieldName = $this->tf->getCamelCase($fieldName, false, true);
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine($ucfTableName, 'handler', $t);
        $ret .= $this->xc->getXoopsHandlerLine('this->'.$moduleDirname, $tableName, $t);
        $ret .= $this->pc->getPhpCodeCommentLine('Form', 'Select', $t);
        $ret .= $this->cc->getClassXoopsFormSelect($ccFieldName.'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret .= $this->cc->getClassAddOption($ccFieldName.'Select', "'Empty'", $t);
        $ret .= $this->cc->getClassAddOptionArray($ccFieldName.'Select', "\${$tableName}Handler->getList()", $t);
        $ret .= $this->cc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormSelectUser
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormSelectUser($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form Select', 'User', $t);
        $getConfig = $this->xc->getXcGetConfig('this->'.$moduleDirname, 'maxsize');
        $xoopsSelectUser = $this->cc->getClassXoopsFormSelectUser('', $language, $fieldName, 'false', $fieldName, true, $t);
        $ret .= $this->cc->getClassAddElement('form', $xoopsSelectUser.$required, $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormRadioYN
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormRadioYN($language, $fieldName, $required = 'false')
    {
        $ccFieldName = $this->tf->getCamelCase($fieldName, false, true);
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form Radio', 'Yes/No', $t);
        $ret .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
        $xoopsRadioYN = $this->cc->getClassXoopsFormRadioYN('', $language, $fieldName, $ccFieldName, true, $t);
        $ret .= $this->cc->getClassAddElement('form', $xoopsRadioYN.$required, $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormTextDateSelect
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTextDateSelect($language, $fieldName, $required = 'false')
    {
        $t = "\t\t";
        $ccFieldName = $this->tf->getCamelCase($fieldName, false, true);
        $ret = $this->pc->getPhpCodeCommentLine('Form Text', 'Date Select', $t);
        $ret .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
        $xoopsTextDateSelect = $this->cc->getClassXoopsFormTextDateSelect('', $language, $fieldName, "''", $fieldName, true, $t);
        $ret .= $this->cc->getClassAddElement('form', $xoopsTextDateSelect.$required, $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormTable
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
        $ucfTableName = ucfirst($tableName);
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form Table', $ucfTableName, $t);
        if ($fieldElement > 15) {
            $fElement = $this->tc->getHandler('fieldelements')->get($fieldElement);
            $rpFieldelementName = strtolower(str_replace('Table : ', '', $fElement->getVar('fieldelement_name')));
            $ccFieldName = $this->tf->getCamelCase($fieldName, false, true);
            $ret .= $this->xc->getXoopsHandlerLine('this->'.$moduleDirname, $rpFieldelementName, $t);
            $ret .= $this->cc->getClassXoopsFormSelect($ccFieldName.'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
            $ret .= $this->cc->getClassAddOptionArray($ccFieldName.'Select', "\${$rpFieldelementName}Handler->getList()", $t);
            $ret .= $this->cc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);
        }

        return $ret;
    }

    /*
    *  @private function getXoopsFormTopic
     *
     * @param $language
     * @param $moduleDirname
     * @param $table
     * @param $fields
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTopic($language, $moduleDirname, $topicTableName, $fieldId, $fieldPid, $fieldMain, $required = 'false')
    {
        $ucfTopicTableName = ucfirst($topicTableName);
        $stlTopicTableName = strtolower($topicTableName);
        $ccFieldPid = $this->tf->getCamelCase($fieldPid, false, true);
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Form Table', $ucfTopicTableName, $t);
        $ret .= $this->xc->getXoopsClearHandler($stlTopicTableName, '', 'this->'.$moduleDirname, $stlTopicTableName, $t);
        $ret .= $this->cc->getClassCriteriaCompo('criteria', $t);
        $ret .= $this->xc->getXcClearHandlerCount($stlTopicTableName.'Count', $stlTopicTableName, '$criteria', $t);
        $contIf = $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/tree', true, false, 'include', $t."\t");
        $contIf .= $this->xc->getXcClearHandlerAll($stlTopicTableName.'All', $stlTopicTableName, '$criteria', $t."\t");
        $contIf .= $this->cc->getClassXoopsObjectTree($stlTopicTableName.'Tree', $stlTopicTableName.'All', $fieldId, $fieldPid, $t."\t");
        $contIf .= $this->cc->getClassXoopsMakeSelBox($ccFieldPid, $stlTopicTableName.'Tree', $fieldPid, $fieldMain, '--', $fieldPid, $t."\t");
        $formLabel = $this->cc->getClassXoopsFormLabel('', $language, "\${$ccFieldPid}", true, '');
        $contIf .= $this->cc->getClassAddElement('form', $formLabel, $t."\t");
        $ret .= $this->pc->getPhpCodeConditions("\${$stlTopicTableName}Count", null, null, $contIf, false, $t);
        $ret .= $this->pc->getPhpCodeUnset('criteria', $t);

        return $ret;
    }

    /*
    *  @private function getXoopsFormTag
     *
     * @param $moduleDirname
     * @param $fieldId
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTag($moduleDirname, $fieldId, $required = 'false')
    {
        $t = "\t\t";
        $ret = $this->pc->getPhpCodeCommentLine('Use tag', 'module', $t);
        $isDir = $this->pc->getPhpCodeIsDir("XOOPS_ROOT_PATH . '/modules/tag'");
        $ret .= $this->pc->getPhpCodeTernaryOperator('dirTag', $isDir, 'true', 'false', $t);
        $paramIf = '('.$this->xc->getXcGetConfig("this->{$moduleDirname}", 'usetag').' == 1)';
        $condIf = $this->pc->getPhpCodeTernaryOperator('tagId', '$this->isNew()', '0', "\$this->getVar('{$fieldId}')", $t."\t");
        $condIf .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'modules/tag/include/formtag', true, false, $type = 'include', $t."\t");
        $paramElem = $this->cc->getClassXoopsFormTag('', 'tag', 60, 255, 'tagId', 0, true, '');
        $condIf .= $this->cc->getClassAddElement('form', $paramElem.$required, $t."\t");
        $ret .= $this->pc->getPhpCodeConditions($paramIf, ' && ', '$dirTag', $condIf, false, $t);

        return $ret;
    }

    /*
    *  @public function renderElements
     *  @param null

     * @return string
     */
    public function renderElements()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $languageFunct = $this->tf->getLanguage($moduleDirname, 'AM');
        //$language_table = $languageFunct . strtoupper($tableName);
        $ret = '';
        $fields = $this->ttf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'), 'field_order ASC, field_id');
        $fieldId = '';
        $fieldIdTopic = '';
        $fieldPidTopic = '';
        $fieldMainTopic = '';
        $fieldElementId = array();
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldDefault = $fields[$f]->getVar('field_default');
            $fieldElement = $fields[$f]->getVar('field_element');
            $fieldParent = $fields[$f]->getVar('field_parent');
            $fieldInForm = $fields[$f]->getVar('field_inform');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
            $rpFieldName = $this->tf->getRightString($fieldName);
            $language = $languageFunct.strtoupper($tableSoleName).'_'.strtoupper($rpFieldName);
            $required = (1 == $fields[$f]->getVar('field_required')) ? ', true' : '';
            //
            $fieldElements = $this->tc->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');
            //
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
                        $ret .= $this->getXoopsFormUploadFile($language, $moduleDirname, $tableName, $fieldName, $required);
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
                    if (1 == $table->getVar('table_category') || (1 == $fieldParent)) {
                        $fieldElements = $this->tc->getHandler('fieldelements')->get($fieldElement);
                        $fieldElementMid = $fieldElements->getVar('fieldelement_mid');
                        $fieldElementTid = $fieldElements->getVar('fieldelement_tid');
                        $fieldElementName = $fieldElements->getVar('fieldelement_name');
                        $fieldNameDesc = substr($fieldElementName, strrpos($fieldElementName, ':'), strlen($fieldElementName));
                        $topicTableName = str_replace(': ', '', $fieldNameDesc);
                        $fieldsTopics = $this->ttf->getTableFields($fieldElementMid, $fieldElementTid);
                        foreach (array_keys($fieldsTopics) as $f) {
                            $fieldNameTopic = $fieldsTopics[$f]->getVar('field_name');
                            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                                $fieldIdTopic = $fieldNameTopic;
                            }
                            if (1 == $fieldsTopics[$f]->getVar('field_parent')) {
                                $fieldPidTopic = $fieldNameTopic;
                            }
                            if (1 == $fieldsTopics[$f]->getVar('field_main')) {
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
