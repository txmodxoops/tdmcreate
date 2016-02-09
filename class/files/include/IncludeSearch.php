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
 * @version         $Id: IncludeSearch.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class IncludeSearch.
 */
class IncludeSearch extends TDMCreateFile
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
        $this->tdmcfile = TDMCreateFile::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return IncludeSearch
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
    *  @param mixed $table
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
        $this->setTable($table);
    }

    /*
    *  @static function getSearchField
    *  @param string $fieldSearch
    *  @param string $options
    */
    /**
     * @param $fieldSearch
     * @param $options
     *
     * @return string
     */
    public function getSearchField($fieldSearch, $options)
    {
        // fieldSearch = fields parameters search field
        $sql = '';
        if (isset($fieldSearch)) {
            $nb_fieldSearch = count($fieldSearch);
            $sql .= '(';
            for ($i = 0; $i < $nb_fieldSearch; ++$i) {
                if ($i != $nb_fieldSearch - 1) {
                    $sql .= ''.$fieldSearch[$i].' LIKE %$queryarray['.$options.']% OR ';
                } else {
                    $sql .= ''.$fieldSearch[$i].' LIKE %$queryarray[0]%';
                }
            }
            $sql .= ')';
        }

        return $sql;
    }

    /*
    *  @static function getSearchFunction
    *  @param string $fieldSearch
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    public function getSearchFunction($moduleDirname)
    {
        $table = $this->getTable();
        $tableName = $table->getVar('table_name');
        $tableFieldname = $table->getVar('table_fieldname');
        $fieldId = '';
        $fieldSearch = null;
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_search')) {
                $fieldSearch = $fieldName;
            }
        }
        $img_search = 'blank.gif';
        $ret = <<<EOT
\n// search callback functions
function {$moduleDirname}_search(\$queryarray, \$andor, \$limit, \$offset, \$userid)
{
    global \$xoopsDB;
    \$sql = "SELECT '{$fieldId}', '{$fieldMain}' FROM ".\$xoopsDB->prefix('{$moduleDirname}_{$tableName}')." WHERE {$fieldId} != 0";
    if ( \$userid != 0 ) {
        \$sql .= " AND {$tableFieldname}_submitter=".(int) (\$userid);
    }
    if ( is_array(\$queryarray) && \$count = count(\$queryarray) )
    {
        \$sql .= " AND (
EOT;
        $ret .= $this->getSearchField($fieldSearch, 0).'";';
        $ret .= <<<EOT

        for(\$i = 1; \$i < \$count; ++\$i)
        {
            \$sql .= " \$andor ";
            \$sql .= "{$this->getSearchField($fieldSearch, '$i')}";
        }
        \$sql .= ")";
    }
    \$sql .= " ORDER BY '{$fieldId}' DESC";
    \$result = \$xoopsDB->query(\$sql,\$limit,\$offset);
    \$ret = array();
    \$i = 0;
    while(\$myrow = \$xoopsDB->fetchArray(\$result))
    {
        \$ret[\$i]['image'] = 'assets/icons/32/{$img_search}';
        \$ret[\$i]['link'] = '{$tableName}.php?{$fieldId}='.\$myrow['{$fieldId}'];
        \$ret[\$i]['title'] = \$myrow['{$fieldMain}'];
        ++\$i;
    }
    unset(\$i);
}
EOT;

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getSearchFunction($moduleDirname);
        //
        $this->tdmcfile->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
