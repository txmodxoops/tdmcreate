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
    private $phpcode = null;
	
	/*
    * @var string
    */
    private $tdmcfile = null;

    /*
    * @var string
    */
    private $formelements = null;

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
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->tdmcfile = TDMCreateFile::getInstance();
        $this->tdmcreate = TDMCreateHelper::getInstance();
        $this->formelements = ClassFormElements::getInstance();
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
    public function write($module, $table, $tables)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
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
        $ret = <<<EOT
        \$this->initVar('{$fieldName}', XOBJ_DTYPE_{$type});\n
EOT;

        return $ret;
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
                $fType = $this->tdmcreate->getHandler('fieldtype')->get($fieldType);
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
    *  @private function getHeadClass
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param array $fields
    */
    /**
     * @param $moduleDirname
     * @param $tableName
     * @param $fields
     *
     * @return string
     */
    private function getHeadClass($moduleDirname, $tableName, $fields)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ret = $this->phpcode->getPhpCodeDefined();
        $ret .= $this->phpcode->getPhpCodeCommentMultiLine(array('Class Object ' => $ucfModuleDirname.$ucfTableName));
        $ret .= <<<EOT
class {$ucfModuleDirname}{$ucfTableName} extends XoopsObject
{
    /*
    * @var mixed
    */
    private \${$moduleDirname} = null;\n\n
EOT;
        $fieldElementId = array();
        $optionsFieldName = array();
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $fieldElements = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');
            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
            if (in_array(5, $fieldElementId)) {
                if (count($rpFieldName) % 5) {
                    $optionsFieldName[] = "'".$rpFieldName."'";
                } else {
                    $optionsFieldName[] = "'".$rpFieldName."'\n";
                }
            }
        }
        if (in_array(5, $fieldElementId) > 1) {
            $optionsElements = implode(', ', $optionsFieldName);
            $ret .= <<<EOT
	/**
     * Options
     */
	public \$options = array({$optionsElements});\n\n
EOT;
        }
        unset($fieldElementId, $optionsFieldName);
        $ret .= <<<EOT
	/*
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        \$this->{$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();
{$this->getInitVars($fields)}\t}\n
    /*
    *  @static function &getInstance
    *  @param null
    */
    public static function &getInstance()
    {
        static \$instance = false;
        if(!\$instance) {
            \$instance = new self();
        }
        return \$instance;
    }\n\n
EOT;

        return $ret;
    }

    /*
    *  @private function getHeadInForm
    *  @param string $module
    *  @param string $table
    */
    /**
     * @param $module
     * @param $table
     *
     * @return string
     */
    private function getHeadInForm($module, $table)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $tablePermissions = $table->getVar('table_permissions');
        $ucfTableName = ucfirst($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);
        $language = $this->getLanguage($moduleDirname, 'AM');
        $this->formelements->initForm($module, $table);
        $ret = <<<EOT
    /*
     * Get form
     *
     * @param mixed \$action
     */
    public function getForm{$ucfTableName}(\$action = false)
    {
        if(\$action === false) {
            \$action = \$_SERVER['REQUEST_URI'];
        }\n
EOT;
        if (1 == $tablePermissions) {
            $ret .= <<<EOT
		global \$xoopsUser, \$xoopsModule;
		// Permissions for uploader
        \$gpermHandler =& xoops_gethandler('groupperm');
        \$groups = is_object(\$xoopsUser) ? \$xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        if(\$xoopsUser) {
            if( !\$xoopsUser->isAdmin(\$xoopsModule->mid()) ) {
                \$permissionUpload =(\$gpermHandler->checkRight('{$moduleDirname}_ac', 32, \$groups, \$xoopsModule->getVar('mid'))) ? true : false ;
            }else{
                \$permissionUpload = true;
            }
        }else{
            \$permissionUpload = (\$gpermHandler->checkRight('{$moduleDirname}_ac', 32, \$groups, \$xoopsModule->getVar('mid'))) ? true : false ;
        }\n
EOT;
        }
        $ret .= <<<EOT
		// Title
        \$title = \$this->isNew() ? sprintf({$language}{$stuTableSoleName}_ADD) : sprintf({$language}{$stuTableSoleName}_EDIT);
        // Get Theme Form
        xoops_load('XoopsFormLoader');
        \$form = new XoopsThemeForm(\$title, 'form', \$action, 'post', true);
        \$form->setExtra('enctype="multipart/form-data"');\n
EOT;

        if (0 == $table->getVar('table_category')) {
            $ret .= <<<EOT
		// {$ucfTableName} handler
		//\${$tableName}Handler =& \$this->{$moduleDirname}->getHandler('{$tableName}');\n
EOT;
        }

        $ret .= <<<EOT
{$this->formelements->renderElements()}
EOT;

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
        $permissionApprove = $this->tdmcfile->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_APPROVE');
        $permissionSubmit = $this->tdmcfile->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_SUBMIT');
        $permissionView = $this->tdmcfile->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_VIEW');
        $ret = <<<EOT
        // Permissions
        \$memberHandler = & xoops_gethandler( 'member' );
        \$groupList     = \$memberHandler->getGroupList();
        \$gpermHandler  = &xoops_gethandler( 'groupperm' );
        \$fullList = array_keys( \$groupList );
        global \$xoopsModule;
        if( !\$this->isNew() ) {
            \$groupsIdsApprove = \$gpermHandler->getGroupIds( '{$moduleDirname}_approve', \$this->getVar( '{$fieldId}' ), \$xoopsModule->getVar( 'mid' ) );
            \$groupsIdsSubmit = \$gpermHandler->getGroupIds( '{$moduleDirname}_submit', \$this->getVar( '{$fieldId}' ), \$xoopsModule->getVar( 'mid' ) );
            \$groupsIdsView = \$gpermHandler->getGroupIds( '{$moduleDirname}_view', \$this->getVar( '{$fieldId}' ), \$xoopsModule->getVar( 'mid' ) );
            \$groupsIdsApprove = array_values( \$groupsIdsApprove );
            \$groupsCanApproveCheckbox = new XoopsFormCheckBox( {$permissionApprove}, 'groups_approve[]', \$groupsIdsApprove );
            \$groupsIdsSubmit = array_values( \$groupsIdsSubmit );
            \$groupsCanSubmitCheckbox = new XoopsFormCheckBox( {$permissionSubmit}, 'groups_submit[]', \$groupsIdsSubmit );
            \$groupsIdsView = array_values( \$groupsIdsView );
            \$groupsCanViewCheckbox = new XoopsFormCheckBox( {$permissionView}, 'groups_view[]', \$groupsIdsView );
        } else {
            \$groupsCanApproveCheckbox = new XoopsFormCheckBox( {$permissionApprove}, 'groups_approve[]', \$fullList );
            \$groupsCanSubmitCheckbox = new XoopsFormCheckBox( {$permissionSubmit}, 'groups_submit[]', \$fullList );
            \$groupsCanViewCheckbox = new XoopsFormCheckBox( {$permissionView}, 'groups_view[]', \$fullList );
        }
        // For approve
        \$groupsCanApproveCheckbox->addOptionArray( \$groupList );
        \$form->addElement( \$groupsCanApproveCheckbox );
        // For submit
        \$groupsCanSubmitCheckbox->addOptionArray( \$groupList );
        \$form->addElement( \$groupsCanSubmitCheckbox );
        // For view
        \$groupsCanViewCheckbox->addOptionArray( \$groupList );
        \$form->addElement( \$groupsCanViewCheckbox );\n\n
EOT;

        return $ret;
    }

    /*
    *  @private function getFootInForm
    *  @param null
    */
    /**
     * @return string
     */
    private function getFootInForm()
    {
        $ret = <<<EOT
        // Send
        \$form->addElement(new XoopsFormHidden('op', 'save'));
        \$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        return \$form;
    }\n\n
EOT;

        return $ret;
    }

    /*
    *  @private function getToArray
    *  @param null
    */
    /**
     * @return string
     */
    private function getValuesInForm($moduleDirname, $table, $fields)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfTableName = ucfirst($table->getVar('table_name'));
        $ret = <<<EOT
	/**
     * Get Values
     */
	public function getValues{$ucfTableName}(\$keys = null, \$format = null, \$maxDepth = null)
    {
		\$ret = parent::getValues(\$keys, \$format, \$maxDepth);\n
EOT;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
            switch ($fieldElement) {
                case 3:
                case 4:
                    $ret .= <<<EOT
		\$ret['{$rpFieldName}'] = strip_tags(\$this->getVar('{$fieldName}'));\n
EOT;
                break;
                case 8:
                    $ret .= <<<EOT
		\$ret['{$rpFieldName}'] = XoopsUser::getUnameFromId(\$this->getVar('{$fieldName}'));\n
EOT;
                break;
                /*case 10:
                    $ret .= <<<EOT
        \$ret['{$rpFieldName}'] = XOOPS_ICONS32_URL .'/'. \$this->getVar('{$fieldName}');\n
EOT;
                break;
                case 13:
                    $ret .= <<<EOT
        \$ret['{$rpFieldName}'] = {$stuModuleDirname}_UPLOAD_IMAGE_URL .'/'. \$this->getVar('{$fieldName}');\n
EOT;
                break;*/
                case 15:
                    $ret .= <<<EOT
		\$ret['{$rpFieldName}'] = formatTimeStamp(\$this->getVar('{$fieldName}'));\n
EOT;
                break;
                default:
                    if ($fieldElement > 15) {
                        $fieldElements = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
                        $fieldElementMid = $fieldElements->getVar('fieldelement_mid');
                        $fieldElementTid = $fieldElements->getVar('fieldelement_tid');
                        $fieldElementName = $fieldElements->getVar('fieldelement_name');
                        $fieldNameDesc = substr($fieldElementName, strrpos($fieldElementName, ':'), strlen($fieldElementName));
                        $topicTableName = str_replace(': ', '', strtolower($fieldNameDesc));
                        $fieldsTopics = $this->tdmcfile->getTableFields($fieldElementMid, $fieldElementTid);
                        foreach (array_keys($fieldsTopics) as $f) {
                            $fieldNameTopic = $fieldsTopics[$f]->getVar('field_name');
                            if (1 == $fieldsTopics[$f]->getVar('field_main')) {
                                $fieldMainTopic = $fieldNameTopic;
                            }
                        }
                        $ret .= <<<EOT
		\$ret['{$rpFieldName}'] = \$this->{$moduleDirname}->getHandler('{$topicTableName}')->get(\$this->getVar('{$fieldName}'))->getVar('{$fieldMainTopic}');\n
EOT;
                    } else {
                        $ret .= <<<EOT
		\$ret['{$rpFieldName}'] = \$this->getVar('{$fieldName}');\n
EOT;
                    }
                break;
            }
        }
        $ret .= <<<EOT

		return \$ret;
    }\n\n
EOT;

        return $ret;
    }
    /*
    *  @private function getToArray
    *  @param $table
    */
    /**
     * @return string
     */
    private function getToArrayInForm($table)
    {
        $tableName = $table->getVar('table_name');
        $ucfTableName = ucfirst($tableName);
        $ret = <<<EOT
    /**
     * Returns an array representation of the object
     *
     * @return array
     **/
    public function toArray{$ucfTableName}()
    {
        \$ret = array();
        \$vars = \$this->getVars();
        foreach( array_keys( \$vars ) as \$var ) {
            \$ret[\$var] = \$this->getVar( \$var );
        }
        return \$ret;
    }
}\n\n
EOT;

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
        $ret = <<<EOT
    /**
     * Get Options
     */
	public function getOptions{$ucfTableName}()
    {
        \$ret = array();\n
EOT;
        $fields = $this->tdmcfile->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            //
            $fieldElements = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId = $fieldElements->getVar('fieldelement_id');
            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
            if (5 == $fieldElementId) {
                $ret .= <<<EOT
		if(1 == \$this->getVar('{$fieldName}')) {
            array_push(\$ret, '{$rpFieldName}');
        }\n
EOT;
            }
        }
        $ret .= <<<EOT
        return \$ret;
    }
}\n\n
EOT;

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
    private function getClassObjectHandler($moduleDirname, $table, $fieldId, $fieldMain)
    {
        $tableName = $table->getVar('table_name');
        $tableCategory = $table->getVar('table_category');
        $tableSoleName = $table->getVar('table_solename');
        $tableFieldname = $table->getVar('table_fieldname');
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ucfTableSoleName = ucfirst($tableSoleName);
        $ucfModuleTable = $ucfModuleDirname.$ucfTableName;
        $ret = <<<EOT
/*
 * Class Object Handler {$ucfModuleDirname}{$ucfTableName}
 */
class {$ucfModuleTable}Handler extends XoopsPersistableObjectHandler
{
    /*
    * @var mixed
    */
    private \${$moduleDirname} = null;
	/*
     * Constructor
     *
     * @param string \$db
     */
    public function __construct(&\$db)
    {
        parent::__construct(\$db, '{$moduleDirname}_{$tableName}', '{$moduleDirname}{$tableName}', '{$fieldId}', '{$fieldMain}');
		\$this->{$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();
    }\n\n
EOT;

        return $ret;
    }

    /**
     *  @public function getClassCreate
     *
     *  @return string
     */
    private function getClassCreate()
    {
        $ret = <<<EOT
	/**
     * @param bool \$isNew
     *
     * @return object
     */
    public function &create(\$isNew = true)
    {
        return parent::create(\$isNew);
    }\n\n
EOT;

        return $ret;
    }

    /**
     *  @public function getClassGet
     *
     *  @return string
     */
    private function getClassGet()
    {
        $ret = <<<EOT
	/**
     * retrieve a field
     *
     * @param int \$i field id
     * @return mixed reference to the {@link TDMCreateFields} object
     */
    public function &get(\$i = null, \$fields = null)
    {
        return parent::get(\$i, \$fields);
    }\n\n
EOT;

        return $ret;
    }

    /**
     *  @public function getClassGetInsertId
     *
     *  @return string
     */
    private function getClassGetInsertId()
    {
        $ret = <<<EOT
    /**
     * get inserted id
     *
     * @param null
     * @return integer reference to the {@link TDMCreateFields} object
     */
    public function &getInsertId()
    {
        return \$this->db->getInsertId();
    }\n\n
EOT;

        return $ret;
    }

    /**
     *  @public function getClassGetIds
     *
     *  @return string
     */
    private function getClassGetIds()
    {
        $ret = <<<EOT
	/**
     * get IDs of objects matching a condition
     *
     * @param object \$criteria {@link CriteriaElement} to match
     * @return array of object IDs
     */
    public function &getIds(\$criteria)
    {
        return parent::getIds(\$criteria);
    }\n\n
EOT;

        return $ret;
    }

    /**
     *  @public function getClassInsert
     *
     *  @return string
     */
    private function getClassInsert()
    {
        $ret = <<<EOT
	/**
     * insert a new field in the database
     *
     * @param object \$field reference to the {@link TDMCreateFields} object
     * @param bool \$force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function &insert(&\$field, \$force = false)
    {
        if(!parent::insert(\$field, \$force)) {
            return false;
        }
        return true;
    }\n\n
EOT;

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
        $ret = <<<EOT
    /**
     * Get Count {$ucfTableName}
     */
    public function getCount{$ucfTableName}(\$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC')
    {
        \$criteriaCount{$ucfTableName} = new CriteriaCompo();
        \$criteriaCount{$ucfTableName} = \$this->get{$ucfTableName}Criteria(\$criteriaCount{$ucfTableName}, \$start, \$limit, \$sort, \$order);
		return parent::getCount(\$criteriaCount{$ucfTableName});
    }\n\n
EOT;

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
        $ret = <<<EOT
	/**
     * Get All {$ucfTableName}
     */
	public function getAll{$ucfTableName}(\$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC')
    {
        \$criteriaAll{$ucfTableName} = new CriteriaCompo();
        \$criteriaAll{$ucfTableName} = \$this->get{$ucfTableName}Criteria(\$criteriaAll{$ucfTableName}, \$start, \$limit, \$sort, \$order);
        return parent::getAll(\$criteriaAll{$ucfTableName});
    }\n\n
EOT;

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
        $fieldElements = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
        $fieldElementMid = $fieldElements->getVar('fieldelement_mid');
        $fieldElementTid = $fieldElements->getVar('fieldelement_tid');
        $fieldElementName = $fieldElements->getVar('fieldelement_name');
        $fieldNameDesc = ucfirst(substr($fieldElementName, strrpos($fieldElementName, ':'), strlen($fieldElementName)));
        $topicTableName = str_replace(': ', '', $fieldNameDesc);
        $lcfTopicTableName = lcfirst($topicTableName);
        $ret = <<<EOT
	/**
     * Get All {$ucfTableName} By {$fieldNameDesc} Id
     */
	public function getAll{$ucfTableName}By{$fieldNameDesc}Id(\${$tableFieldName}Id, \$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC')
    {
        \$gpermHandler =& xoops_gethandler('groupperm');
		\${$lcfTopicTableName} = \$gpermHandler->getItemIds('{$moduleDirname}_view', \$GLOBALS['xoopsUser']->getGroups(), \$GLOBALS['xoopsModule']->getVar('mid') );

		\$criteriaAll{$ucfTableName} = new CriteriaCompo();\n
EOT;
        if (strstr($fieldName, 'status')) {
            $ret .= <<<EOT
		\$criteriaAll{$ucfTableName}->add(new Criteria('{$fieldName}', 0, '!='));\n
EOT;
        }
        $ret .= <<<EOT
		\$criteriaAll{$ucfTableName}->add(new Criteria('{$fieldParent}', \${$tableFieldName}Id));
		\$criteriaAll{$ucfTableName}->add(new Criteria('{$fieldId}', '(' . implode(',', \${$lcfTopicTableName}) . ')','IN'));
        \$criteriaAll{$ucfTableName} = \$this->get{$ucfTableName}Criteria(\$criteriaAll{$ucfTableName}, \$start, \$limit, \$sort, \$order);
        return parent::getAll(\$criteriaAll{$ucfTableName});
    }\n\n
EOT;

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
        $ret = <<<EOT
	/**
     * Get {$ucfTableName} Criteria.
     */
    private function get{$ucfTableName}Criteria(\$criteria{$ucfTableName}, \$start, \$limit, \$sort, \$order)
    {
        \$criteria{$ucfTableName}->setStart(\$start);
        \$criteria{$ucfTableName}->setLimit(\$limit);
        \$criteria{$ucfTableName}->setSort(\$sort);
        \$criteria{$ucfTableName}->setOrder(\$order);

        return \$criteria{$ucfTableName};
    }\n\n
EOT;

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
        $ret = <<<EOT
	/**
     * Returns the {$ucfTableSoleName} from id
     *
     * @return string
     **/
    public function get{$ucfTableSoleName}FromId(\${$tableSoleName}Id)
    {
        \${$tableSoleName}Id = (int) ( \${$tableSoleName}Id );
        \${$tableSoleName} = '';
        if(\${$tableSoleName}Id > 0) {
            \${$tableName}Handler = \$this->{$moduleDirname}->getHandler( '{$tableName}' );
            \${$tableSoleName}Obj = & \${$tableName}Handler->get( \${$tableSoleName}Id );
            if(is_object( \${$tableSoleName}Obj )) {
                \${$tableSoleName} = \${$tableSoleName}Obj->getVar( '{$fieldMain}' );
            }
        }
        return \${$tableSoleName};
    }\n
EOT;

        return $ret;
    }

    /**
     *  @public function getClassEnd
     */
    private function getClassEnd()
    {
        $ret = <<<EOT
}\n
EOT;

        return $ret;
    }

    /*
    *  @public function renderFile
    *  @param string $filename
    */
    /**
     * @param $filename
     *
     * @return bool|string
     */
    public function renderFile($filename)
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $tableName = $table->getVar('table_name');
        $tableFieldName = $table->getVar('table_fieldname');
        $tableCategory = $table->getVar('table_category');
        $moduleDirname = $module->getVar('mod_dirname');
        $fields = $this->tdmcfile->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldInForm = array();
        $fieldParentId = array();
        $fieldElementId = array();
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
            $fieldElements = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');
        }
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getHeadClass($moduleDirname, $tableName, $fields);
        if (in_array(1, $fieldInForm)) {
            $content .= $this->getHeadInForm($module, $table);
            if (1 == $table->getVar('table_permissions')) {
                $content .= $this->getPermissionsInForm($moduleDirname, $fieldId);
            }
            $content .= $this->getFootInForm();
        }
        $content .= $this->getValuesInForm($moduleDirname, $table, $fields);
        $content .= $this->getToArrayInForm($table);
        if (in_array(5, $fieldElementId) > 1) {
            $content .= $this->getOptionsCheck($table);
        }
        $content .= $this->getClassObjectHandler($moduleDirname, $table, $fieldId, $fieldMain);
        $content .= $this->getClassCreate();
        $content .= $this->getClassGet();
        $content .= $this->getClassGetInsertId();
        $content .= $this->getClassGetIds();
        $content .= $this->getClassInsert();
        $content .= $this->getClassCounter($tableName, $fieldId, $fieldMain);
        $content .= $this->getClassAll($tableName, $fieldId, $fieldMain);
        $content .= $this->getClassCriteria($tableName);
        if (in_array(1, $fieldParentId) && $fieldElement > 15) {
            $content .= $this->getClassByCategory($moduleDirname, $tableName, $tableFieldName, $fieldId, $fieldName, $fieldMain, $fieldParent, $fieldElement);
            $content .= $this->getClassGetTableSolenameById($moduleDirname, $table, $fieldMain);
        }
        $content .= $this->getClassEnd();
        $this->tdmcfile->create($moduleDirname, 'class', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
