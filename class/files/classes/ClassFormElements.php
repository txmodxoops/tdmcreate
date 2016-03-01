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
 * @version         $Id: ClassFormElements.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class ClassFormElements.
 */
class ClassFormElements extends TDMCreateFile
{
    /*
    * @var string
    */
    private $tdmcreate = null;

    /*
    * @var mixed
    */
    private $xoopscode = null;

    /*
    * @var mixed
    */
    private $classcode = null;

    /**
     *  @public function constructor
     *
     *  @param null    
     */
    public function __construct()
    {
        parent::__construct();
        $this->tdmcreate = TDMCreateHelper::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->classcode = ClassXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
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
    *  @param string $module
    *  @param string $table
    */
    /**
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
    *  @param string $language
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormText($language, $fieldName, $fieldDefault, $required = 'false')
    {
        $ucfFieldName = $this->getCamelCase($fieldName, true);
        $ccFieldName = $this->getCamelCase($fieldName, false, true);
        if ($fieldDefault != '') {
            $ret = $this->phpcode->getPhpCodeCommentLine('Form Text', $ucfFieldName);
            $ret .= $this->phpcode->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', "'{$fieldDefault}'", "\$this->getVar('{$fieldName}')");
            $formText = $this->classcode->getClassXoopsFormText('', $language, $fieldName, 20, 150, "{$ccFieldName}", true);
            $ret .= $this->classcode->getClassAddElement('form', $formText.$required);
        } else {
            $ret = $this->phpcode->getPhpCodeCommentLine('Form Text', $ucfFieldName);
            $formText = $this->classcode->getClassXoopsFormText('', $language, $fieldName, 50, 255, "this->getVar('{$fieldName}')", true);
            $ret .= $this->classcode->getClassAddElement('form', $formText.$required);
        }

        return $ret;
    }

    /*
    *  @private function getXoopsFormText
    *  @param string $language
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormTextArea($language, $fieldName, $required = 'false')
    {
        $ucfFieldName = $this->getCamelCase($fieldName, true);
        $ret = $this->phpcode->getPhpCodeCommentLine('Form Text Area', $ucfFieldName, "\t\t");
        $formTextArea = $this->classcode->getClassXoopsFormTextArea('', $language, $fieldName, 4, 47, true);
        $ret .= $this->classcode->getClassAddElement('form', $formTextArea.$required);

        return $ret;
    }

    /*
    *  @private function getXoopsFormDhtmlTextArea
    *  @param string $language
    *  @param string $moduleDirname
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $moduleDirname
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormDhtmlTextArea($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $rpFieldName = $this->getRightString($fieldName);
        $ucfFieldName = $this->getCamelCase($fieldName, true);
        $ret = $this->phpcode->getPhpCodeCommentLine('Form editor', $ucfFieldName, "\t\t");
        $ret .= $this->phpcode->getPhpCodeArray('editorConfigs', null, false, "\t\t");
        $configs = array('name' => "'{$fieldName}'", 'value' => "\$this->getVar('{$fieldName}', 'e')", 'rows' => 5, 'cols' => 40,
                        'width' => "'100%'", 'height' => "'400px'", 'editor' => "\$this->{$moduleDirname}->getConfig('{$moduleDirname}_editor_{$rpFieldName}')", );
        foreach ($configs as $c => $d) {
            $ret .= "\t\t\$editorConfigs['{$c}'] = {$d};\n";
        }
        $formEditor = $this->classcode->getClassXoopsFormEditor('', $language, $fieldName, 'editorConfigs', true);
        $ret .= $this->classcode->getClassAddElement('form', $formEditor.$required);

        return $ret;
    }

    /*
    *  @private function getXoopsFormCheckBox
    *  @param string $language
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormCheckBox($language, $tableSoleName, $fieldName, $fieldElementId, $required = 'false')
    {
        $stuTableSoleName = strtoupper($tableSoleName);
        $ucfFieldName = $this->getCamelCase($fieldName, true);
        $ccFieldName = $this->getCamelCase($fieldName, false, true);
        if (in_array(5, $fieldElementId) > 1) {
            $ret = <<<EOT
        // Form Check Box List Options
		\$checkOption          = \$this->getOptions();
        \$check{$ucfFieldName} = new XoopsFormCheckbox('<hr />', '{$tableSoleName}_option', \$checkOption, false);
        \$check{$ucfFieldName}->setDescription({$language}{$stuTableSoleName}_OPTIONS_DESC);
        foreach(\$this->options as \$option) {
            \$check{$ucfFieldName}->addOption(\$option, {$language}{$stuTableSoleName}_ . strtoupper(\$option));
        }
		\$form->addElement(\$check{$ucfFieldName}{$required} );\n
EOT;
        } else {
            $ret = <<<EOT
        // Form Check Box
        \${$ccFieldName} = \$this->isNew() ? 0 : \$this->getVar('{$fieldName}');
        \$check{$ucfFieldName} = new XoopsFormCheckBox({$language}, '{$fieldName}', \${$ccFieldName});
        \$check{$ucfFieldName}->addOption(1, " ");
        \$form->addElement( \$check{$ucfFieldName}{$required} );\n
EOT;
        }

        return $ret;
    }

    /*
    *  @private function getXoopsFormHidden
    *  @param string $fieldName
    */
    /**
     * @param $fieldName
     *
     * @return string
     */
    private function getXoopsFormHidden($fieldName)
    {
        $ucfFieldName = $this->getCamelCase($fieldName, true);
        $ret = $this->phpcode->getPhpCodeCommentLine('Form Hidden', $ucfFieldName, "\t\t");
        $formHidden = $this->classcode->getClassXoopsFormHidden('', $fieldName, $fieldName, true, true);
        $ret .= $this->classcode->getClassAddElement('form', $formHidden);

        return $ret;
    }

    /*
    *  @private function getXoopsFormImageList
    *  @param string $language
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormImageList($language, $moduleDirname, $tableName, $tableSoleName, $fieldName, $required = 'false')
    {
        $stuTableName = strtoupper($tableName);
        $rpFieldName = $this->getRightString($fieldName);
        $stuSoleName = strtoupper($tableSoleName.'_'.$rpFieldName);
        $ucfFieldName = $this->getCamelCase($fieldName, true);
        $ccFieldName = $this->getCamelCase($fieldName, false, true);
        $ret = <<<EOT
        // Form Frameworks Image Files
        \$get{$ucfFieldName} = \$this->getVar('{$fieldName}');
        \${$ccFieldName} = \$get{$ucfFieldName} ? \$get{$ucfFieldName} : 'blank.gif';
        \$imageDirectory = '/Frameworks/moduleclasses/icons/32';
        \$imageTray = new XoopsFormElementTray({$language}{$stuSoleName},'<br />');
        \$imageSelect = new XoopsFormSelect(sprintf({$language}FORM_IMAGE_PATH, ".{\$imageDirectory}/"), '{$fieldName}', \${$ccFieldName}, 5);
        \$imageArray = XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH . \$imageDirectory );
        foreach( \$imageArray as \$image1 ) {
            \$imageSelect->addOption("{\$image1}", \$image1);
        }
        \$imageSelect->setExtra( "onchange='showImgSelected(\"image1\", \"{$fieldName}\", \"".\$imageDirectory."\", \"\", \"".XOOPS_URL."\")'" );
        \$imageTray->addElement(\$imageSelect, false);
        \$imageTray->addElement( new XoopsFormLabel( '', "<br /><img src='".XOOPS_URL."/".\$imageDirectory."/".\${$ccFieldName}."' name='image1' id='image1' alt='' />" ) );
        // Form File
        \$fileSelectTray = new XoopsFormElementTray('','<br />');
        \$fileSelectTray->addElement(new XoopsFormFile({$language}FORM_IMAGE_LIST_{$stuTableName} , 'attachedfile', \$this->{$moduleDirname}->getConfig('maxsize')));
        \$fileSelectTray->addElement(new XoopsFormLabel(''));
        \$imageTray->addElement(\$fileSelectTray);
        \$form->addElement( \$imageTray{$required} );\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormSelectFile
    *  @param string $language
    *  @param string $moduleDirname
    *  @param string $fieldName
    *  @param string $fieldDefault
    *  @param string $fieldElement
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $moduleDirname
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormSelectFile($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $ccFieldName = $this->getCamelCase($fieldName, false, true);
        $ret = <<<EOT
        // Image Select or Upload
        if ( \$this->{$moduleDirname}->getConfig('useshots') ) {
            \${$ccFieldName} = \$this->getVar('{$fieldName}');
            \$uploadDirectory = '/uploads/{$moduleDirname}/images/shots';
            \${$moduleDirname}ShotImage = \${$fieldName} ? \${$fieldName} : 'blank.gif';
            //
            \$imageTray = new XoopsFormElementTray({$language}FORM_IMAGE,'<br />');
            \$imageSelect = new XoopsFormSelect(sprintf({$language}_FORM_PATH, \$uploadDirectory ), 'selected_image',\${$moduleDirname}ShotImage);
            \$imageArray = XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH . \$uploadDirectory );
            foreach( \$imageArray as \$image ) {
                \$imageSelect->addOption("{\$image}", \$image);
            }
            \$imageSelect->setExtra( "onchange='showImgSelected(\"image3\", \"selected_image\", \"" . \$uploadDirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
            \$imageTray->addElement(\$imageSelect,false);
            \$imageTray -> addElement( new XoopsFormLabel( '', "<br /><img src='" . XOOPS_URL . "/" . \$uploadDirectory . "/" . \${$moduleDirname}ShotImage . "' name='image3' id='image3' alt='' />" ) );
            \$fileSelectTray= new XoopsFormElementTray('','<br />');
            //if (\$permissionUpload == true) {
                \$fileSelectTray->addElement(new XoopsFormFile({$language}_FORM_UPLOAD , 'attachedimage', \$this->{$moduleDirname}->getConfig('maxuploadsize')){$required});
            //}
            \$imageTray->addElement(\$fileSelectTray);
            \$form->addElement(\$imageTray);
        }\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormUrlFile
    *  @param string $language
    *  @param string $moduleDirname
    *  @param string $fieldName
    *  @param string $fieldDefault
    *
    *  @param string $required
    */
    /**
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
        $ret = <<<EOT
        // Form Url Text File
        \$formUrlFile = new XoopsFormElementTray({$language}FORM_FILE,'<br /><br />');
        \$formUrl     = \$this->isNew() ? '{$fieldDefault}' : \$this->getVar('{$fieldName}');
        \$formText    = new XoopsFormText({$language}FORM_TEXT, '{$fieldName}', 75, 255, \$formUrl);
        \$formUrlFile->addElement(\$formText{$required} );
        \$formUrlFile->addElement(new XoopsFormFile({$language}FORM_UPLOAD , 'attachedfile', \$this->{$moduleDirname}->getConfig('maxsize')){$required});
        \$form->addElement(\$formUrlFile);\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormUploadImage
    *  @param string $language
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $moduleDirname
     * @param        $tableName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormUploadImage($language, $moduleDirname, $tableName, $tableSoleName, $fieldName, $required = 'false')
    {
        $stuTableName = strtoupper($tableName);
        $stuSoleName = strtoupper($tableSoleName);
        $ucfFieldName = $this->getCamelCase($fieldName, true);
        $ccFieldName = $this->getCamelCase($fieldName, false, true);
        $ret = <<<EOT
        // Form Upload Image
        \$get{$ucfFieldName} = \$this->getVar('{$fieldName}');
        \${$ccFieldName} = \$get{$ucfFieldName} ? \$get{$ucfFieldName} : 'blank.gif';
        \$imageDirectory = '/uploads/{$moduleDirname}/images/{$tableName}';
        //
        \$imageTray   = new XoopsFormElementTray({$language}{$stuSoleName}_IMAGE,'<br />');
        \$imageSelect = new XoopsFormSelect(sprintf({$language}FORM_IMAGE_PATH, ".{\$imageDirectory}/"), '{$fieldName}', \${$ccFieldName}, 5);
        \$imageArray  = XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH . \$imageDirectory );
        foreach( \$imageArray as \$image ) {
            \$imageSelect->addOption("{\$image}", \$image);
        }
        \$imageSelect->setExtra( "onchange='showImgSelected(\"image2\", \"{$fieldName}\", \"".\$imageDirectory."\", \"\", \"".XOOPS_URL."\")'" );
        \$imageTray->addElement(\$imageSelect, false);
        \$imageTray->addElement( new XoopsFormLabel( '', "<br /><img src='".XOOPS_URL."/".\$imageDirectory."/".\${$ccFieldName}."' name='image2' id='image2' alt='' />" ) );
        // Form File
        \$fileSelectTray = new XoopsFormElementTray('','<br />');
        \$fileSelectTray->addElement(new XoopsFormFile({$language}FORM_UPLOAD_IMAGE_{$stuTableName} , 'attachedfile', \$this->{$moduleDirname}->getConfig('maxsize')));
        \$fileSelectTray->addElement(new XoopsFormLabel(''));
        \$imageTray->addElement(\$fileSelectTray);
        \$form->addElement( \$imageTray{$required} );\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormUploadFile
    *  @param string $language
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormUploadFile($language, $moduleDirname, $tableName, $fieldName, $required = 'false')
    {
        $stuTableName = strtoupper($tableName);
        $ret = <<<EOT
        // Form file
        \$form->addElement( new XoopsFormFile({$language}FORM_UPLOAD_FILE_{$stuTableName}, '{$fieldName}', \$this->{$moduleDirname}->getConfig('maxsize')){$required} );\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormColorPicker
    *  @param string $language
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $moduleDirname
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormColorPicker($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $ret = <<<EOT
        // Form Color Picker
        \$form->addElement( new XoopsFormColorPicker({$language}, '{$fieldName}', \$this->{$moduleDirname}->getConfig('maxsize')){$required} );\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormSelectBox
    *  @param string $language
    *  @param string $tableName
    *  @param string $fieldName
    *  @param string $required
    */
    /**
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
        $ccFieldName = $this->getCamelCase($fieldName, false, true);
        $ret = <<<EOT
        // {$ucfTableName} handler
		\${$tableName}Handler =& \$this->{$moduleDirname}->getHandler('{$tableName}');
		// Form Select
        \${$ccFieldName}Select = new XoopsFormSelect({$language}, '{$fieldName}', \$this->getVar('{$fieldName}'));
        \${$ccFieldName}Select->addOption('Empty');
        \${$ccFieldName}Select->addOptionArray(\${$tableName}Handler->getList());
        \$form->addElement( \${$ccFieldName}Select{$required} );\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormSelectUser
    *  @param string $language
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormSelectUser($language, $fieldName, $required = 'false')
    {
        $ret = <<<EOT
        // Form Select User
        \$form->addElement( new XoopsFormSelectUser({$language}, '{$fieldName}', false, \$this->getVar('{$fieldName}'), 1, false){$required} );\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormRadioYN
    *  @param string $language
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormRadioYN($language, $fieldName, $required = 'false')
    {
        $ccFieldName = $this->getCamelCase($fieldName, false, true);
        $ret = <<<EOT
        // Form Radio Yes/No
        \${$ccFieldName} = \$this->isNew() ? 0 : \$this->getVar('{$fieldName}');
        \$form->addElement( new XoopsFormRadioYN({$language}, '{$fieldName}', \${$ccFieldName}){$required} );\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormTextDateSelect
    *  @param string $language
    *  @param string $fieldName
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormTextDateSelect($language, $fieldName, $required = 'false')
    {
        $ret = <<<EOT
        // Form Text Date Select
        \$form->addElement( new XoopsFormTextDateSelect({$language}, '{$fieldName}', '', \$this->getVar('{$fieldName}')){$required} );\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormTable
    *  @param string $language
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $fields
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $fieldName
     * @param        $fieldElement
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormTable($language, $moduleDirname, $tableName, $fieldName, $fieldElement, $required = 'false')
    {
        $ucfTableName = ucfirst($tableName);
        $ret = '';
        if ($fieldElement > 15) {
            $fElement = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
            $rpFieldelementName = strtolower(str_replace('Table : ', '', $fElement->getVar('fieldelement_name')));
            $ccFieldName = $this->getCamelCase($fieldName, false, true);
            $ret .= <<<EOT
			// Form Table {$ucfTableName}
			\${$rpFieldelementName}Handler =& \$this->{$moduleDirname}->getHandler('{$rpFieldelementName}');
			\${$ccFieldName}Select = new XoopsFormSelect({$language}, '{$fieldName}', \$this->getVar('{$fieldName}'));
			\${$ccFieldName}Select->addOptionArray(\${$rpFieldelementName}Handler->getList());
			\$form->addElement( \${$ccFieldName}Select{$required} );\n		
EOT;
        }

        return $ret;
    }

    /*
    *  @private function getXoopsFormTopic
    *  @param string $language
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $fields
    *  @param string $required
    */
    /**
     * @param        $language
     * @param        $moduleDirname
     * @param        $table
     * @param        $fields
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormTopic($language, $moduleDirname, $topicTableName, $fieldId, $fieldPid, $fieldMain, $required = 'false')
    {
        $ucfTopicTableName = ucfirst($topicTableName);
        $stlTopicTableName = strtolower($topicTableName);
        $ccFieldPid = $this->getCamelCase($fieldPid, false, true);
        $ret = <<<EOT
        // Form Topic {$ucfTopicTableName}
        \${$stlTopicTableName}Handler = \$this->{$moduleDirname}->getHandler('{$stlTopicTableName}');
        \$criteria = new CriteriaCompo();
        \${$stlTopicTableName}Count = \${$stlTopicTableName}Handler->getCount( \$criteria );
        if(\${$stlTopicTableName}Count) {
            include_once(XOOPS_ROOT_PATH . '/class/tree.php');
			\${$stlTopicTableName}All = \${$stlTopicTableName}Handler->getAll(\$criteria);
            \${$stlTopicTableName}Tree = new XoopsObjectTree( \${$stlTopicTableName}All, '{$fieldId}', '{$fieldPid}' );
            \${$ccFieldPid} = \${$stlTopicTableName}Tree->makeSelBox( '{$fieldPid}', '{$fieldMain}', '--', \$this->getVar('{$fieldPid}', 'e' ), true );
            \$form->addElement( new XoopsFormLabel ( {$language}, \${$ccFieldPid} ){$required} );
        }
		unset(\$criteria);\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormTag
    *  @param string $moduleDirname
    *  @param string $fieldId
    *  @param string $required
    */
    /**
     * @param        $moduleDirname
     * @param        $fieldId
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormTag($moduleDirname, $fieldId, $required = 'false')
    {
        $ret = <<<EOT
		// Use tag module
		\$dirTag = is_dir(XOOPS_ROOT_PATH . '/modules/tag') ? true : false;
        if ((\$this->{$moduleDirname}->getConfig('usetag') == 1) && \$dirTag){
            \$tagId = \$this->isNew() ? 0 : \$this->getVar('{$fieldId}');
            include_once XOOPS_ROOT_PATH.'/modules/tag/include/formtag.php';
            \$form->addElement(new XoopsFormTag('tag', 60, 255, \$tagId, 0){$required});
        }\n
EOT;

        return $ret;
    }

    /*
    *  @public function renderElements
    *  @param null
    */
    /**
     * @return string
     */
    public function renderElements()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $languageFunct = $this->getLanguage($moduleDirname, 'AM');
        //$language_table = $languageFunct . strtoupper($tableName);
        $ret = '';
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'), 'field_order ASC, field_id');
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
            $rpFieldName = $this->getRightString($fieldName);
            $language = $languageFunct.strtoupper($tableSoleName).'_'.strtoupper($rpFieldName);
            $required = (1 == $fields[$f]->getVar('field_required')) ? ', true' : '';
            //
            $fieldElements = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
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
                        $ret .= $this->getXoopsFormSelectUser($language, $fieldName, $required);
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
                        $ret .= $this->getXoopsFormUrlFile($language, $moduleDirname, $fieldName, $fieldDefault, $fieldElement, $required);
                        break;
                    case 13:
                        $ret .= $this->getXoopsFormUploadImage($languageFunct, $moduleDirname, $tableName, $tableSoleName, $fieldName, $required);
                        break;
                    case 14:
                        $ret .= $this->getXoopsFormUploadFile($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case 15:
                        $ret .= $this->getXoopsFormTextDateSelect($language, $moduleDirname, $fieldName, $required);
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
                        $fieldElements = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
                        $fieldElementMid = $fieldElements->getVar('fieldelement_mid');
                        $fieldElementTid = $fieldElements->getVar('fieldelement_tid');
                        $fieldElementName = $fieldElements->getVar('fieldelement_name');
                        $fieldNameDesc = substr($fieldElementName, strrpos($fieldElementName, ':'), strlen($fieldElementName));
                        $topicTableName = str_replace(': ', '', $fieldNameDesc);
                        $fieldsTopics = $this->getTableFields($fieldElementMid, $fieldElementTid);
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
