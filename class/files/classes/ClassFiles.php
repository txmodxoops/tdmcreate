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
 * @version         $Id: class_files.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
require_once 'ClassFormElements.php';

/**
 * Class ClassFiles
 */
class ClassFiles extends ClassFormElements
{
    /*
    * @var string
    */
    private $formelements;

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
        $this->tdmcfile     = TDMCreateFile::getInstance();
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
                $fType         = $this->tdmcreate->getHandler('fieldtype')->get($fieldType);
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
     * @return string
     */
    private function getHeadClass($moduleDirname, $tableName, $fields)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName     = ucfirst($tableName);
        $ret              = <<<EOT
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/*
 * Class Object {$ucfModuleDirname}{$ucfTableName}
 */
class {$ucfModuleDirname}{$ucfTableName} extends XoopsObject
{
    /*
    * @var mixed
    */
    private \${$moduleDirname} = null;
    /*
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        \$this->{$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();
{$this->getInitVars($fields)}\t}
    /*
    *  @static function &getInstance
    *  @param null
    */
    public static function &getInstance()
    {
        static \$instance = false;
        if (!\$instance) {
            \$instance = new self();
        }
        return \$instance;
    }\n
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
     * @return string
     */
    private function getHeadInForm($module, $table)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName     = $table->getVar('table_name');
        $ucfTableName  = ucfirst($tableName);
        $stuTableName  = strtoupper($tableName);
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $this->formelements->initForm($module, $table);
        $ret = <<<EOT
    /*
     * Get form
     *
     * @param mixed \$action
     */
    public function getForm(\$action = false)
    {
        if (\$action === false) {
            \$action = \$_SERVER['REQUEST_URI'];
        }
        // Title
        \$title = \$this->isNew() ? sprintf({$language}{$stuTableName}_ADD) : sprintf({$language}{$stuTableName}_EDIT);
        // Get Theme Form
        xoops_load('XoopsFormLoader');
        \$form = new XoopsThemeForm(\$title, 'form', \$action, 'post', true);
        \$form->setExtra('enctype="multipart/form-data"');
        // {$ucfTableName} handler
        \${$tableName}Handler =& \$this->{$moduleDirname}->getHandler('{$tableName}');
{$this->formelements->renderElements()}
EOT;

        return $ret;
    }

    /*
    *  @private function getPermissionsInForm
    *  @param string $moduleDirname
    *  @param string $fpif
    */
    /**
     * @param $moduleDirname
     * @param $fpif
     * @return string
     */
    private function getPermissionsInForm($moduleDirname, $fpif)
    {
        $permissionApprove = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_APPROVE');
        $permissionSubmit  = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_SUBMIT');
        $permissionView    = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_VIEW');
        $ret               = <<<EOT
        // Permissions
        \$member_handler = & xoops_gethandler ( 'member' );
        \$group_list = &\$member_handler->getGroupList();
        \$gperm_handler = &xoops_gethandler ( 'groupperm' );
        \$full_list = array_keys ( \$group_list );
        global \$xoopsModule;
        if ( !\$this->isNew() ) {
            \$groups_ids_approve = \$gperm_handler->getGroupIds ( '{$moduleDirname}_approve', \$this->getVar ( '{$fpif}' ), \$xoopsModule->getVar ( 'mid' ) );
            \$groups_ids_submit = \$gperm_handler->getGroupIds ( '{$moduleDirname}_submit', \$this->getVar ( '{$fpif}' ), \$xoopsModule->getVar ( 'mid' ) );
            \$groups_ids_view = \$gperm_handler->getGroupIds ( '{$moduleDirname}_view', \$this->getVar ( '{$fpif}' ), \$xoopsModule->getVar ( 'mid' ) );
            \$groups_ids_approve = array_values ( \$groups_ids_approve );
            \$groups_can_approve_checkbox = new XoopsFormCheckBox ( {$permissionApprove}, 'groups_approve[]', \$groups_ids_approve );
            \$groups_ids_submit = array_values ( \$groups_ids_submit );
            \$groups_can_submit_checkbox = new XoopsFormCheckBox ( {$permissionSubmit}, 'groups_submit[]', \$groups_ids_submit );
            \$groups_ids_view = array_values ( \$groups_ids_view );
            \$groups_can_view_checkbox = new XoopsFormCheckBox ( {$permissionView}, 'groups_view[]', \$groups_ids_view );
        } else {
            \$groups_can_approve_checkbox = new XoopsFormCheckBox ( {$permissionApprove}, 'groups_approve[]', \$full_list );
            \$groups_can_submit_checkbox = new XoopsFormCheckBox ( {$permissionSubmit}, 'groups_submit[]', \$full_list );
            \$groups_can_view_checkbox = new XoopsFormCheckBox ( {$permissionView}, 'groups_view[]', \$full_list );
        }
        // For approve
        \$groups_can_approve_checkbox->addOptionArray ( \$group_list );
        \$form->addElement ( \$groups_can_approve_checkbox );
        // For submit
        \$groups_can_submit_checkbox->addOptionArray ( \$group_list );
        \$form->addElement ( \$groups_can_submit_checkbox );
        // For view
        \$groups_can_view_checkbox->addOptionArray ( \$group_list );
        \$form->addElement ( \$groups_can_view_checkbox );\n
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
    }\n
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
    private function getToArray()
    {
        $ret = <<<EOT
    /**
     * Returns an array representation of the object
     *
     * @return array
     **/
    public function toArray()
    {
        \$ret = array();
        \$vars = \$this->getVars();
        foreach ( array_keys( \$vars ) as \$var ) {
            \$ret[\$var] = \$this->getVar( \$var );
        }
        return \$ret;
    }
}\n
EOT;

        return $ret;
    }

    /*
    *  @public function getClassHandler
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fpif
    *  @param string $fpmf
    */
    /**
     * @param $moduleDirname
     * @param $tableName
     * @param $tableCategory
     * @param $tableFieldname
     * @param $fpif
     * @param $fpmf
     * @return string
     */
    private function getClassHandler($moduleDirname, $tableName, $tableCategory, $tableFieldname, $fpif, $fpmf)
    {
        $ucfModuleDirname  = ucfirst($moduleDirname);
        $ucfTableName      = ucfirst($tableName);
        $ucfTableFieldname = ucfirst($tableFieldname);
        $ucfModuleTable    = $ucfModuleDirname . $ucfTableName;
        $ret               = <<<EOT
/*
 * Class Object Handler {$ucfModuleDirname}{$ucfTableName}
 */
class {$ucfModuleTable}Handler extends XoopsPersistableObjectHandler
{
    /*
     * Constructor
     *
     * @param string \$db
     */
    public function __construct(&\$db)
    {
        parent::__construct(\$db, '{$moduleDirname}_{$tableName}', '{$moduleDirname}{$tableName}', '{$fpif}', '{$fpmf}');
    }

    /**
     * @param bool \$isNew
     *
     * @return object
     */
    public function &create(\$isNew = true)
    {
        return parent::create(\$isNew);
    }

    /**
     * retrieve a field
     *
     * @param int \$i field id
     * @return mixed reference to the {@link TDMCreateFields} object
     */
    public function &get(\$i = null, \$fields = null)
    {
        return parent::get(\$i, \$fields);
    }

    /**
     * get inserted id
     *
     * @param null
     * @return integer reference to the {@link TDMCreateFields} object
     */
    public function &getInsertId()
    {
        return \$this->db->getInsertId();
    }

    /**
     * get IDs of objects matching a condition
     *
     * @param object \$criteria {@link CriteriaElement} to match
     * @return array of object IDs
     */
    function &getIds(\$criteria)
    {
        return parent::getIds(\$criteria);
    }

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
        if (!parent::insert(\$field, \$force)) {
            return false;
        }
        return true;
    }\n
EOT;
        if (1 == $tableCategory) {
            $ret .= <<<EOT
    \n\t/**
     * Returns the {$ucfTableFieldname} from id
     *
     * @return string
     **/
    public function get{$ucfTableFieldname}FromId(\${$tableFieldname}Id)
    {
        \${$tableFieldname}Id = (int) ( \${$tableFieldname}Id );
        \${$fpmf} = '';
        if (\${$tableFieldname}id > 0) {
            \${$tableFieldname}Handler = \$this->{$moduleDirname}->getHandler( '{$tableName}' );
            \${$tableFieldname} = & \${$tableFieldname}Handler->get( \${$tableFieldname}Id );
            if (is_object( \${$tableFieldname} )) {
                \${$fpmf} = \${$tableFieldname}->getVar( '{$fpmf}' );
            }
        }
        return \${$fpmf};
    }\n
EOT;
        }
        $ret .= <<<EOT
}
EOT;

        return $ret;
    }

    /*
    *  @public function renderFile
    *  @param string $filename
    */
    /**
     * @param $filename
     * @return bool|string
     */
    public function renderFile($filename)
    {
        $module         = $this->getModule();
        $table          = $this->getTable();
        $tableName      = $table->getVar('table_name');
        $tableCategory  = $table->getVar('table_category');
        $tFieldname 	   = $table->getVar('table_fieldname');
        $tableFieldname = empty($tFieldname) ? $tableName : $tFieldname;
        $moduleDirname  = $module->getVar('mod_dirname');
        $fields         = $this->getTableFields($table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName   = $fields[$f]->getVar('field_name');
            $fieldInForm = $fields[$f]->getVar('field_inform');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fpif = $fieldName; // $fpif = fields parameter index field
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fpmf = $fieldName; // $fpmf = fields parameter main field
            }
        }
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getHeadClass($moduleDirname, $tableName, $fields);
        if (1 == $fieldInForm) {
            $content .= $this->getHeadInForm($module, $table);
            if (1 == $table->getVar('table_permissions')) {
                $content .= $this->getPermissionsInForm($moduleDirname, $fpif);
            }
            $content .= $this->getFootInForm();
        }
        $content .= $this->getToArray();
        $content .= $this->getClassHandler($moduleDirname, $tableName, $tableCategory, $tableFieldname, $fpif, $fpmf);

        $this->tdmcfile->create($moduleDirname, 'class', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
