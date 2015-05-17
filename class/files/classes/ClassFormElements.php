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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: ClassFormElements.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class ClassFormElements
 */
class ClassFormElements extends TDMCreateFile
{
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
        $this->tdmcreate = TDMCreateHelper::getInstance();
		$this->tdmcfile = TDMCreateFile::getInstance();
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
     * @return string
     */
    private function getXoopsFormText($language, $fieldName, $fieldDefault, $required = 'false')
    {
        if($fieldDefault != '') {
			$ret = <<<EOT
        // Form Text {$fieldName}
		\${$fieldName} = \$this->isNew() ? '{$fieldDefault}' : \$this->getVar('{$fieldName}');
        \$form->addElement( new XoopsFormText({$language}, '{$fieldName}', 20, 150, \${$fieldName}){$required} );\n
EOT;
		} else {
			$ret = <<<EOT
        // Form Text {$fieldName}
        \$form->addElement( new XoopsFormText({$language}, '{$fieldName}', 50, 255, \$this->getVar('{$fieldName}')){$required} );\n
EOT;
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
     * @return string
     */
    private function getXoopsFormTextArea($language, $fieldName, $required = 'false')
    {
        $ret = <<<EOT
        // Form Text Area
        \$form->addElement( new XoopsFormTextArea({$language}, '{$fieldName}', \$this->getVar('{$fieldName}'), 4, 47){$required} );\n
EOT;

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
     * @return string
     */
    private function getXoopsFormDhtmlTextArea($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $ret = <<<EOT
        // Form Dhtml Text Area
        \$editor_configs = array();
        \$editor_configs['name'] = '{$fieldName}';
        \$editor_configs['value'] = \$this->getVar('{$fieldName}', 'e');
        \$editor_configs['rows'] = 5;
        \$editor_configs['cols'] = 40;
        \$editor_configs['width'] = '100%';
        \$editor_configs['height'] = '400px';
        \$editor_configs['editor'] = \$this->{$moduleDirname}->getConfig('{$moduleDirname}_editor');
        \$form->addElement( new XoopsFormEditor({$language}, '{$fieldName}', \$editor_configs){$required} );\n
EOT;

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
     * @return string
     */
    private function getXoopsFormCheckBox($language, $fieldName, $required = 'false')
    {
        $ret = <<<EOT
        // Form Check Box
        \${$fieldName} = \$this->isNew() ? 0 : \$this->getVar('{$fieldName}');
        \$check_{$fieldName} = new XoopsFormCheckBox({$language}, '{$fieldName}', \${$fieldName});
        \$check_{$fieldName}->addOption(1, " ");
        \$form->addElement( \$check_{$fieldName}{$required} );\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormHidden
    *  @param string $fieldName
    */
    /**
     * @param $fieldName
     * @return string
     */
    private function getXoopsFormHidden($fieldName)
    {
        $ret = <<<EOT
        // Form Hidden
        \$form->addElement( new XoopsFormHidden('{$fieldName}', \$this->getVar('{$fieldName}')) );\n
EOT;

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
     * @param        $fieldName
     * @param string $required
     * @return string
     */
    private function getXoopsFormImageList($language, $moduleDirname, $tableName, $fieldName, $required = 'false')
    {
        $stuTableName = strtoupper($tableName);
        $stuFieldName = strtoupper($fieldName);
        $ret          = <<<EOT
        // Form image file
        \$get_{$fieldName} = \$this->getVar('{$fieldName}');
        \${$fieldName} = \$get_{$fieldName} ? \$get_{$fieldName} : 'blank.gif';
        \$iconsdir = '/Frameworks/moduleclasses/icons/32';
        \$uploads_dir = '/uploads/'.\$GLOBALS['xoopsModule']->dirname().'/images/{$tableName}';
        \$iconsdirectory = is_dir(XOOPS_ROOT_PATH . \$iconsdir) ? \$iconsdir : \$uploads_dir;
        //
        \$imgtray1 = new XoopsFormElementTray({$language}{$stuFieldName},'<br />');
        \$imgpath = is_dir(XOOPS_ROOT_PATH . \$iconsdir) ? sprintf({$language}FORMIMAGE_PATH, ".{\$iconsdir}/") : sprintf({$language}FORMIMAGE_PATH, \$uploads_dir);
        //\$imgpath1 = sprintf({$language}FORMIMAGE_PATH, ".{\$iconsdirectory}/");
        \$imageselect1 = new XoopsFormSelect(\$imgpath, '{$fieldName}', \${$fieldName}, 10);
        \$image_array1 = XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH . \$iconsdirectory );
        foreach( \$image_array1 as \$image1 ) {
            \$imageselect1->addOption("{\$image1}", \$image1);
        }
        \$imageselect1->setExtra( "onchange='showImgSelected(\"image1\", \"{$fieldName}\", \"".\$iconsdirectory."\", \"\", \"".XOOPS_URL."\")'" );
        \$imgtray1->addElement(\$imageselect1, false);
        \$imgtray1->addElement( new XoopsFormLabel( '', "<br /><img src='".XOOPS_URL."/".\$iconsdirectory."/".\${$fieldName}."' name='image1' id='image1' alt='' />" ) );
        // Form File
        \$fileseltray = new XoopsFormElementTray('','<br />');
        \$fileseltray->addElement(new XoopsFormFile({$language}FORM_UPLOAD_IMAGE_LIST_{$stuTableName} , 'attachedfile', \$this->{$moduleDirname}->getConfig('maxsize')));
        \$fileseltray->addElement(new XoopsFormLabel(''));
        \$imgtray1->addElement(\$fileseltray);
        \$form->addElement( \$imgtray1{$required} );\n
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
     * @param        $fieldElement
     * @param string $required
     * @return string
     */
    private function getXoopsFormSelectFile($language, $moduleDirname, $fieldName, $fieldElement, $required = 'false')
    {
        $ret = <<<EOT
        // Image Select or Upload
        if ( \$this->{$moduleDirname}->getConfig('useshots') ){
            \${$fieldName} = \$this->getVar('{$fieldName}');
            \$uploadirectory = '/uploads/{$moduleDirname}/images/shots';
            \$uploaddir = XOOPS_ROOT_PATH . \$uploadirectory . '/' . \${$fieldName};
            \${$moduleDirname}cat_img = \${$fieldName} ? \${$fieldName} : 'blank.gif';
            if (!is_file(\$uploaddir)){
                \${$moduleDirname}cat_img = 'blank.gif';
            }
            \$imgtray = new XoopsFormElementTray({$language}_FORMIMG,'<br />');
            \$imgpath = sprintf({$language}_FORMPATH, \$uploadirectory );
            \$imageselect = new XoopsFormSelect(\$imgpath, 'selected_img',\${$moduleDirname}cat_img);
            \${$fieldName}_array = XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . \$uploadirectory );
            foreach( \${$fieldName}_array as \$image ) {
                \$imageselect->addOption("\$image", \$image);
            }
            \$imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"selected_img\", \"" . \$uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
            \$imgtray->addElement(\$imageselect,false);
            \$imgtray -> addElement( new XoopsFormLabel( '', "<br /><img src='" . XOOPS_URL . "/" . \$uploadirectory . "/" . \${$moduleDirname}cat_img . "' name='image3' id='image3' alt='' />" ) );
            \$fileseltray= new XoopsFormElementTray('','<br />');
            //if (\$perm_upload == true) {
                \$fileseltray->addElement(new XoopsFormFile({$language}_FORMUPLOAD , 'attachedimage', \$this->{$moduleDirname}->getConfig('maxuploadsize')){$required});
            //}
            \$imgtray->addElement(\$fileseltray);
            \$form->addElement(\$imgtray);
        }\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsFormTextFile
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
     * @param        $fieldDefault
     * @param        $fieldElement
     * @param string $required
     * @return string
     */
    private function getXoopsFormUrlFile($language, $moduleDirname, $fieldName, $fieldDefault, $fieldElement, $required = 'false')
    {
        $ret = <<<EOT
        // Form Text File
        \$formTextFile = new XoopsFormElementTray({$language}FORM_FILE,'<br /><br />');
        \$field_text = \$this->isNew() ? '{$fieldDefault}' : \$this->getVar('{$fieldName}');
        \$formText = new XoopsFormText({$language}FORM_TEXT, '{$fieldName}', 75, 255, \$field_text);
        \$formTextFile->addElement(\$formText{$required} );
        \$formTextFile->addElement(new XoopsFormFile({$language}FORM_UPLOAD , 'attachedfile', \$this->{$moduleDirname}->getConfig('maxsize')){$required});
        \$form->addElement(\$formTextFile);\n
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
     * @return string
     */
    private function getXoopsFormUploadImage($language, $moduleDirname, $tableName, $required = 'false')
    {
        $stuTableName = strtoupper($tableName);
        $ret          = <<<EOT
        // Form Upload Image
        \$formImage = new XoopsFormFile({$language}FORM_UPLOAD_IMAGE_{$stuTableName} , 'attachedfile', \$this->{$moduleDirname}->getConfig('maxsize'));
        \$form->addElement( \$formImage{$required} );\n
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
     * @return string
     */
    private function getXoopsFormUploadFile($language, $moduleDirname, $tableName, $fieldName, $required = 'false')
    {
        $stuTableName = strtoupper($tableName);
        $ret          = <<<EOT
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
     * @param        $language
     * @param        $tableName
     * @param        $fieldName
     * @param string $required
     * @return string
     */
    private function getXoopsFormSelectBox($language, $tableName, $fieldName, $required = 'false')
    {
        $ret = <<<EOT
        // Form Select
        \${$fieldName}_select = new XoopsFormSelect({$language}, '{$fieldName}', \$this->getVar('{$fieldName}'));
        \${$fieldName}_select->addOption('Empty');
        \${$fieldName}_select->addOptionArray(\${$tableName}Handler->getList());
        \$form->addElement( \${$fieldName}_select{$required} );\n
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
     * @return string
     */
    private function getXoopsFormRadioYN($language, $fieldName, $required = 'false')
    {
        $ret = <<<EOT
        // Form Radio Yes/No
        \${$fieldName} = \$this->isNew() ? 0 : \$this->getVar('{$fieldName}');
        \$form->addElement( new XoopsFormRadioYN({$language}, '{$fieldName}', \${$fieldName}){$required} );\n
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
     * @param        $moduleDirname
     * @param        $fieldName
     * @param string $required
     * @return string
     */
    private function getXoopsFormTextDateSelect($language, $moduleDirname, $fieldName, $required = 'false')
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
     * @return string
     */
    private function getXoopsFormTable($language, $moduleDirname, $tableName, $fieldName, $fieldElement, $required = 'false')
    {
        $ucfTableName = ucfirst($tableName);
        if ($fieldElement > 15) {
            $fElement           = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
            $rpFieldelementName = strtolower(str_replace('Table : ', '', $fElement->getVar('fieldelement_name')));
        }
        $ret = <<<EOT
        // Form Table {$ucfTableName}
        \${$rpFieldelementName}Handler =& \$this->{$moduleDirname}->getHandler('{$rpFieldelementName}');
        \${$fieldName}_select = new XoopsFormSelect({$language}, '{$fieldName}', \$this->getVar('{$fieldName}'));
        \${$fieldName}_select->addOptionArray(\${$rpFieldelementName}Handler->getList());
        \$form->addElement( \${$fieldName}_select{$required} );\n
EOT;

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
     * @return string
     */
    private function getXoopsFormTopic($language, $moduleDirname, $table, $fields, $required = 'false')
    {
        $tableName    = $table->getVar('table_name');
        $ucfTableName = ucfirst($tableName);
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
        }
        $ret = <<<EOT
        // Form Topic {$ucfTableName}
        //\${$tableName}Handler = \$this->{$moduleDirname}->getHandler('{$tableName}');
        \$criteria = new CriteriaCompo();
        \${$tableName} = \${$tableName}Handler->getObjects( \$criteria );
        if(\${$tableName}) {
            include_once(XOOPS_ROOT_PATH . '/class/tree.php');
            \${$tableName}_tree = new XoopsObjectTree( \${$tableName}, '{$fieldId}', '{$fieldPid}' );
            \${$fieldPid} = \${$tableName}_tree->makeSelBox( '{$fieldPid}', '{$fieldMain}', '--', \$this->getVar('{$fieldPid}', 'e' ), true );
            \$form->addElement( new XoopsFormLabel ( {$language}, \${$fieldPid} ){$required} );
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
        $module         = $this->getModule();
        $table          = $this->getTable();
        $moduleDirname  = $module->getVar('mod_dirname');
        $tableName      = $table->getVar('table_name');
		$tableSoleName  = $table->getVar('table_solename');
        $language_funct = $this->getLanguage($moduleDirname, 'AM');
        //$language_table = $language_funct . strtoupper($tableName);
        $ret    = '';
        $fields = $this->getTableFields($table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldDefault = $fields[$f]->getVar('field_default');
            $fieldElement = $fields[$f]->getVar('field_element');
            $fieldParent  = $fields[$f]->getVar('field_parent');
            $fieldInForm  = $fields[$f]->getVar('field_inform');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
			$rpFieldName = $this->tdmcfile->getRightString($fieldName);
            $language = $language_funct . strtoupper($tableSoleName) . '_' . strtoupper($rpFieldName);
            $required = (1 == $fields[$f]->getVar('field_required')) ? ', true' : '';
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
                        $ret .= $this->getXoopsFormCheckBox($language, $fieldName, $required);
                        break;
                    case 6:
                        $ret .= $this->getXoopsFormRadioYN($language, $fieldName, $required);
                        break;
                    case 7:
                        $ret .= $this->getXoopsFormSelectBox($language, $tableName, $fieldName, $required);
                        break;
                    case 8:
                        $ret .= $this->getXoopsFormSelectUser($language, $fieldName, $required);
                        break;
                    case 9:
                        $ret .= $this->getXoopsFormColorPicker($language, $moduleDirname, $fieldName, $required);
                        break;
                    case 10:
                        $ret .= $this->getXoopsFormImageList($language_funct, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case 11:
                        $ret .= $this->getXoopsFormSelectFile($language, $moduleDirname, $fieldName, $fieldElement, $required);
                        break;
                    case 12:
                        $ret .= $this->getXoopsFormUrlFile($language, $moduleDirname, $fieldName, $fieldDefault, $fieldElement, $required);
                        break;
                    case 13:
                        $ret .= $this->getXoopsFormUploadImage($language_funct, $moduleDirname, $tableName, $required);
                        break;
                    case 14:
                        $ret .= $this->getXoopsFormUploadFile($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case 15:
                        $ret .= $this->getXoopsFormTextDateSelect($language, $moduleDirname, $fieldName, $required);
                        break;
                    default:
                        // If we want to hide XoopsFormHidden() or field id
                        if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                            $ret .= $this->getXoopsFormHidden($fieldName);
                        }
                        break;
                }
                if ($fieldElement > 15) {
                    if ((1 == $fieldParent) || 1 == $table->getVar('table_category')) {
                        $ret .= $this->getXoopsFormTopic($language, $moduleDirname, $table, $fields, $required);
                    } else {
                        $ret .= $this->getXoopsFormTable($language, $moduleDirname, $tableName, $fieldName, $fieldElement, $required);
                    }
                }
            }
        }

        return $ret;
    }
}
