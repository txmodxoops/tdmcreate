<?php

namespace XoopsModules\Tdmcreate\Files\Blocks;

use XoopsModules\Tdmcreate\Files;

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
 */

/**
 * Class BlocksFiles.
 */
class BlocksFiles extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     * @param null
     * @return BlocksFiles
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
     * @param string $module
     * @param mixed  $table
     * @param        $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private  function getBlocksShow
     * @param     $moduleDirname
     * @param     $tableName
     * @param     $tableFieldname
     * @param     $fields
     * @param     $fieldId
     * @param int $fieldParent
     * @return string
     */
    private function getBlocksShow($moduleDirname, $tableName, $tableFieldname, $fields, $fieldId, $fieldParent = 0)
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret              = <<<EOT
include_once XOOPS_ROOT_PATH.'/modules/{$moduleDirname}/include/common.php';
// Function show block
function b_{$moduleDirname}_{$tableName}_show(\$options)
{
    include_once XOOPS_ROOT_PATH.'/modules/{$moduleDirname}/class/{$tableName}.php';
    \$myts = MyTextSanitizer::getInstance();
    \$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_upload_url', {$stuModuleDirname}_UPLOAD_URL);
    \$block       = array();
    \$typeBlock   = \$options[0];
    \$limit       = \$options[1];
    \$lenghtTitle = \$options[2];
    \$helper = \XoopsModules\\{$ucfModuleDirname}\Helper::getInstance();
    \${$tableName}Handler = \$helper->getHandler('{$tableName}');
    \$criteria = new \CriteriaCompo();
    array_shift(\$options);
    array_shift(\$options);
    array_shift(\$options);\n
EOT;
        if (1 == $fieldParent) {
            $ret .= <<<EOT
	\${$tableName} = {$moduleDirname}_getMyItemIds('{$moduleDirname}_view', '{$moduleDirname}');
    \$criteria->add(new \Criteria('cid', '(' . implode(',', \${$tableName}) . ')','IN'));
    if (1 != (count(\$options) && 0 == \$options[0])) {
        \$criteria->add(new \Criteria('{$fieldId}', {$moduleDirname}_block_addCatSelect(\$options), 'IN'));
    }

    if (\$typeBlock)
    {
        \$criteria->add(new \Criteria('{$fieldId}', 0, '!='));
        \$criteria->setSort('{$fieldId}');
        \$criteria->setOrder('ASC');
    }\n
EOT;
        } else {
            $ret .= <<<EOT
	switch(\$typeBlock)
	{
		// For the block: {$tableName} last
		case 'last':
			//\$criteria->add(new \Criteria('{$tableFieldname}_display', 1));
			\$criteria->setSort('{$tableFieldname}_created');
			\$criteria->setOrder('DESC');
		break;
		// For the block: {$tableName} new
		case 'new':
			//\$criteria->add(new \Criteria('{$tableFieldname}_display', 1));
			\$criteria->add(new \Criteria('{$tableFieldname}_created', strtotime(date(_SHORTDATESTRING)), '>='));
			\$criteria->add(new \Criteria('{$tableFieldname}_created', strtotime(date(_SHORTDATESTRING))+86400, '<='));
			\$criteria->setSort('{$tableFieldname}_created');
			\$criteria->setOrder('ASC');
		break;
		// For the block: {$tableName} hits
		case 'hits':
            \$criteria->setSort('{$tableFieldname}_hits');
            \$criteria->setOrder('DESC');
        break;
		// For the block: {$tableName} top
		case 'top':
            \$criteria->setSort('{$tableFieldname}_top');
            \$criteria->setOrder('ASC');
        break;
		// For the block: {$tableName} random
		case 'random':
			//\$criteria->add(new \Criteria('{$tableFieldname}_display', 1));
			\$criteria->setSort('RAND()');
		break;
	}\n
EOT;
        }
        $ret .= <<<EOT
    \$criteria->setLimit(\$limit);
    \${$tableName}All = \${$tableName}Handler->getAll(\$criteria);
	unset(\$criteria);
	if (count(\${$tableName}All) > 0) {
        foreach(array_keys(\${$tableName}All) as \$i)
        {\n
EOT;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            // Verify if table_fieldname is not empty
            //$lpFieldName = !empty($tableFieldname) ? substr($fieldName, 0, strpos($fieldName, '_')) : $tableName;
            $rpFieldName  = $this->getRightString($fieldName);
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_block')) {
                switch ($fieldElement) {
                    case 2:
                        $ret .= <<<EOT
            \$block[\$i]['{$rpFieldName}'] = \$myts->htmlSpecialChars(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n
EOT;
                        break;
                    case 3:
                    case 4:
                        $ret .= <<<EOT
		    \$block[\$i]['{$rpFieldName}'] = strip_tags(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n
EOT;
                        break;
                    case 8:
                        $ret .= <<<EOT
		    \$block[\$i]['{$rpFieldName}'] = \XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n
EOT;
                        break;
                    case 15:
                        $ret .= <<<EOT
            \$block[\$i]['{$rpFieldName}'] = formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n
EOT;
                        break;
                    default:
                        $ret .= <<<EOT
            \$block[\$i]['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n
EOT;
                        break;
                }
            }
        }
        $ret .= <<<EOT
        }
    }
    return \$block;
}\n\n
EOT;

        return $ret;
    }

    /**
     * @public function getBlocksEdit
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldId
     * @param string $fieldMain
     * @param string $language
     *
     * @return string
     */
    private function getBlocksEdit($moduleDirname, $tableName, $fieldId, $fieldMain, $language)
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $stuTableName     = mb_strtoupper($tableName);
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret              = <<<EOT
// Function edit block
function b_{$moduleDirname}_{$tableName}_edit(\$options)
{
    include_once XOOPS_ROOT_PATH.'/modules/{$moduleDirname}/class/{$tableName}.php';
    \$helper = \XoopsModules\\{$ucfModuleDirname}\Helper::getInstance();
    \${$tableName}Handler = \$helper->getHandler('{$tableName}');
    \$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_upload_url', {$stuModuleDirname}_UPLOAD_URL);
    \$form  = {$language}DISPLAY;
    \$form .= "<input type='hidden' name='options[0]' value='".\$options[0]."' />";
    \$form .= "<input type='text' name='options[1]' size='5' maxlength='255' value='" . \$options[1] . "' />&nbsp;<br>";
    \$form .= {$language}TITLE_LENGTH." : <input type='text' name='options[2]' size='5' maxlength='255' value='" . \$options[2] . "' /><br><br>";
    array_shift(\$options);
    array_shift(\$options);
    array_shift(\$options);
    \$criteria = new \CriteriaCompo();
    \$criteria->add(new \Criteria('{$fieldId}', 0, '!='));
    \$criteria->setSort('{$fieldId}');
    \$criteria->setOrder('ASC');
    \${$tableName}All = \${$tableName}Handler->getAll(\$criteria);
    unset(\$criteria);
    \$form .= {$language}{$stuTableName}_TO_DISPLAY."<br><select name='options[]' multiple='multiple' size='5'>";
    \$form .= "<option value='0' " . (in_array(0, \$options) === false ? '' : "selected='selected'") . '>' . {$language}ALL_{$stuTableName} . '</option>';
    foreach (array_keys(\${$tableName}All) as \$i) {
        \${$fieldId} = \${$tableName}All[\$i]->getVar('{$fieldId}');
        \$form .= "<option value='" . \${$fieldId} . "' " . (in_array(\${$fieldId}, \$options) === false ? '' : "selected='selected'") . '>' . \${$tableName}All[\$i]->getVar('{$fieldMain}') . '</option>';
    }
    \$form .= '</select>';
    return \$form;
}
EOT;

        return $ret;
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module         = $this->getModule();
        $filename       = $this->getFileName();
        $table          = $this->getTable();
        $moduleDirname  = $module->getVar('mod_dirname');
        $tableName      = $table->getVar('table_name');
        $tableFieldname = $table->getVar('table_fieldname');
        $tableCategory  = $table->getVar('table_category');
        $language       = $this->getLanguage($moduleDirname, 'MB');
        $fields         = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName   = $fields[$f]->getVar('field_name');
            $fieldParent = $fields[$f]->getVar('field_parent');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
        }
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getBlocksShow($moduleDirname, $tableName, $tableFieldname, $fields, $fieldId, $fieldParent);
        $content .= $this->getBlocksEdit($moduleDirname, $tableName, $fieldId, $fieldMain, $language);

        $this->create($moduleDirname, 'blocks', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
