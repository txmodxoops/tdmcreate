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
 * @version         $Id: 1.91 IncludeFunctions.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class IncludeFunctions.
 */
class IncludeFunctions extends TDMCreateFile
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
        $this->phpcode = TDMCreatePhpCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return IncludeFunctions
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     *
     * @param $module
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /*
    *  @private function getFunctionBlock
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getFunctionBlock($moduleDirname)
    {
        $ret = <<<EOT
\n/***************Blocks***************/
function {$moduleDirname}_block_addCatSelect(\$cats) {
    if(is_array(\$cats))
    {
        \$cat_sql = '('.current(\$cats);
        array_shift(\$cats);
        foreach(\$cats as \$cat)
        {
            \$cat_sql .= ','.\$cat;
        }
        \$cat_sql .= ')';
    }
    return \$cat_sql;
}\n
EOT;

        return $ret;
    }

    /*
    *  @private function getFunctionGetMyItemIds
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    private function getFunctionGetMyItemIds($moduleDirname, $tableName)
    {
        $ret = <<<EOT
\n/**
 *  Get the permissions ids
 */
function {$moduleDirname}GetMyItemIds(\$permtype, \$dirname)
{
    global \$xoopsUser;
    static \$permissions = array();
    if(is_array(\$permissions) && array_key_exists(\$permtype, \$permissions)) {
        return \$permissions[\$permtype];
    }
	\$moduleHandler =& xoops_gethandler('module');
	\${$moduleDirname}Module =& \$moduleHandler->getByDirname(\$dirname);
	\$groups = is_object(\$xoopsUser) ? \$xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	\$gpermHandler =& xoops_gethandler('groupperm');
	\${$tableName} = \$gpermHandler->getItemIds(\$permtype, \$groups, \${$moduleDirname}Module->getVar('mid'));
    return \${$tableName};
}\n
EOT;

        return $ret;
    }

    /*
    *  @private function getFunctionNumbersOfEntries
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     * @param $tableMid
     * @param $tableMid
     * @param $tableName
     *
     * @return string
     */
    private function getFunctionNumbersOfEntries($moduleDirname, $tableMid, $tableId, $tableName)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $ret = <<<EOT
\n/**
 *  Get the number of {$tableName} from the sub categories of a category or sub topics of or topic
 */
function {$moduleDirname}NumbersOfEntries(\$mytree, \${$tableName}, \$entries, \$cid)
{
    \$count = 0;
    if(in_array(\$cid, \${$tableName})) {
        \$child = \$mytree->getAllChild(\$cid);
        foreach (array_keys(\$entries) as \$i) {
            if (\$entries[\$i]->getVar('{$fieldId}') == \$cid){
                \$count++;
            }
            foreach (array_keys(\$child) as \$j) {
                if (\$entries[\$i]->getVar('{$fieldId}') == \$j){
                    \$count++;
                }
            }
        }
    }
    return \$count;
}\n
EOT;

        return $ret;
    }

    /*
    *  @private function getFunctionMetaKeywords
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getFunctionMetaKeywords($moduleDirname)
    {
        $ret = <<<EOT
\nfunction {$moduleDirname}MetaKeywords(\$content)
{
    global \$xoopsTpl, \$xoTheme;
    \$myts =& MyTextSanitizer::getInstance();
    \$content= \$myts->undoHtmlSpecialChars(\$myts->displayTarea(\$content));
    if(isset(\$xoTheme) && is_object(\$xoTheme)) {
        \$xoTheme->addMeta( 'meta', 'keywords', strip_tags(\$content));
    } else {    // Compatibility for old Xoops versions
        \$xoopsTpl->assign('xoops_meta_keywords', strip_tags(\$content));
    }
}\n
EOT;

        return $ret;
    }

    /*
    *  @private function getFunctionDescription
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getFunctionMetaDescription($moduleDirname)
    {
        $ret = <<<EOT
\nfunction {$moduleDirname}MetaDescription(\$content)
{
    global \$xoopsTpl, \$xoTheme;
    \$myts =& MyTextSanitizer::getInstance();
    \$content = \$myts->undoHtmlSpecialChars(\$myts->displayTarea(\$content));
    if(isset(\$xoTheme) && is_object(\$xoTheme)) {
        \$xoTheme->addMeta( 'meta', 'description', strip_tags(\$content));
    } else {    // Compatibility for old Xoops versions
        \$xoopsTpl->assign('xoops_meta_description', strip_tags(\$content));
    }
}\n
EOT;

        return $ret;
    }

    /*
    *  @private function getRewriteUrl
    *  @param string $moduleDirname
    *  @param string $tableName
    */
    /**
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    private function getRewriteUrl($moduleDirname, $tableName)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret = <<<EOT
\n/**
 * Rewrite all url
 *
 * @String  \$module  module name
 * @String  \$array   array
 * @return  \$type    string replacement for any blank case
 */
function {$moduleDirname}_RewriteUrl(\$module, \$array, \$type = 'content')
{
    \$comment = '';
    \${$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();
    \${$tableName} = \${$moduleDirname}->getHandler('{$tableName}');
    \$lenght_id = \${$moduleDirname}->getConfig('lenght_id');
    \$rewrite_url = \${$moduleDirname}->getConfig('rewrite_url');

    if (\$lenght_id != 0) {
        \$id = \$array['content_id'];
        while (strlen(\$id) < \$lenght_id)
            \$id = "0" . \$id;
    } else {
        \$id = \$array['content_id'];
    }

    if (isset(\$array['topic_alias']) && \$array['topic_alias']) {
        \$topic_name = \$array['topic_alias'];
    } else {
        \$topic_name = {$moduleDirname}_Filter(xoops_getModuleOption('static_name', \$module));
    }

    switch (\$rewrite_url) {

        case 'none':
            if(\$topic_name) {
                 \$topic_name = 'topic=' . \$topic_name . '&amp;';
            }
            \$rewrite_base = '/modules/';
            \$page = 'page=' . \$array['content_alias'];
            return XOOPS_URL . \$rewrite_base . \$module . '/' . \$type . '.php?' . \$topic_name . 'id=' . \$id . '&amp;' . \$page . \$comment;
            break;

        case 'rewrite':
            if(\$topic_name) {
                \$topic_name = \$topic_name . '/';
            }
            \$rewrite_base = xoops_getModuleOption('rewrite_mode', \$module);
            \$rewrite_ext = xoops_getModuleOption('rewrite_ext', \$module);
            \$module_name = '';
            if(xoops_getModuleOption('rewrite_name', \$module)) {
                \$module_name = xoops_getModuleOption('rewrite_name', \$module) . '/';
            }
            \$page = \$array['content_alias'];
            \$type = \$type . '/';
            \$id = \$id . '/';
            if (\$type == 'content/') \$type = '';

            if (\$type == 'comment-edit/' || \$type == 'comment-reply/' || \$type == 'comment-delete/') {
                return XOOPS_URL . \$rewrite_base . \$module_name . \$type . \$id . '/';
            }

            return XOOPS_URL . \$rewrite_base . \$module_name . \$type . \$topic_name  . \$id . \$page . \$rewrite_ext;
            break;

         case 'short':
            if(\$topic_name) {
                \$topic_name = \$topic_name . '/';
            }
            \$rewrite_base = xoops_getModuleOption('rewrite_mode', \$module);
            \$rewrite_ext = xoops_getModuleOption('rewrite_ext', \$module);
            \$module_name = '';
            if(xoops_getModuleOption('rewrite_name', \$module)) {
                \$module_name = xoops_getModuleOption('rewrite_name', \$module) . '/';
            }
            \$page = \$array['content_alias'];
            \$type = \$type . '/';
            if (\$type == 'content/') \$type = '';

            if (\$type == 'comment-edit/' || \$type == 'comment-reply/' || \$type == 'comment-delete/') {
                return XOOPS_URL . \$rewrite_base . \$module_name . \$type . \$id . '/';
            }

            return XOOPS_URL . \$rewrite_base . \$module_name . \$type . \$topic_name . \$page . \$rewrite_ext;
            break;
    }
}
EOT;

        return $ret;
    }

    /*
    *  @private function getRewriteFilter
    *  @param string $moduleDirname
    *  @param string $tableName
    */
    /**
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    private function getRewriteFilter($moduleDirname, $tableName)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret = <<<EOT
\n/**
 * Replace all escape, character, ... for display a correct url
 *
 * @String  \$url    string to transform
 * @String  \$type   string replacement for any blank case
 * @return  \$url
 */
function {$moduleDirname}_Filter(\$url, \$type = '', \$module = '{$moduleDirname}') {

    // Get regular expression from module setting. default setting is : `[^a-z0-9]`i
    \${$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();
    \${$tableName} = \${$moduleDirname}->getHandler('{$tableName}');
    \$regular_expression = \${$moduleDirname}->getConfig('regular_expression');

    \$url = strip_tags(\$url);
    \$url = preg_replace("`\[.*\]`U", "", \$url);
    \$url = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', \$url);
    \$url = htmlentities(\$url, ENT_COMPAT, 'utf-8');
    \$url = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", \$url);
    \$url = preg_replace(array(\$regular_expression, "`[-]+`"), "-", \$url);
    \$url = (\$url == "") ? \$type : strtolower(trim(\$url, '-'));
    return \$url;
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
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $tableId = null;
        $tableMid = null;
        $tableName = null;
        $tableBlocks = null;
        $tablePermissions = null;
        $tableCategory = null;
        foreach (array_keys($tables) as $i) {
            $tableId = $tables[$i]->getVar('table_id');
            $tableMid = $tables[$i]->getVar('table_mid');
            $tableName = $tables[$i]->getVar('table_name');
            $tableBlocks[] = $tables[$i]->getVar('table_blocks');
            $tablePermissions[] = $tables[$i]->getVar('table_permissions');
            $tableCategory[] = $tables[$i]->getVar('table_category');
        }
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content = $this->getHeaderFilesComments($module, $filename);
        if (in_array(1, $tableBlocks)) {
            $content .= $this->getFunctionBlock($moduleDirname);
        }
        if (in_array(1, $tablePermissions)) {
            $content .= $this->getFunctionGetMyItemIds($moduleDirname, $tableName);
        }
        if (in_array(1, $tableCategory)) {
            $content .= $this->getFunctionNumbersOfEntries($moduleDirname, $tableMid, $tableId, $tableName);
        }
        $content .= $this->getFunctionMetaKeywords($moduleDirname);
        $content .= $this->getFunctionMetaDescription($moduleDirname);
        $content .= $this->getRewriteUrl($moduleDirname, $tableName);
        $content .= $this->getRewriteFilter($moduleDirname, $tableName);
        //
        $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
