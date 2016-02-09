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
 * @version         $Id: UserXoopsCode.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserXoopsCode.
 */
class UserXoopsCode
{
    /*
    * @var mixed
    */
    private $phpcode = null;

    /*
    * @var mixed
    */
    private $xoopscode = null;

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserXoopsCode
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
    *  @public function getUserTplMain
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @return string
    */
    public function getUserTplMain($moduleDirname, $tableName = 'index')
    {
        return "\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';\n";
    }

    /*
     * @public function getUserAddMeta
     * @param $type
     * @param $language
     * @param $tableName
     *  
     * @return string
     */
    public function getUserAddMeta($type = '', $language, $tableName)
    {
        $stuTableName = strtoupper($tableName);
        $stripTags = $this->phpcode->getPhpCodeStripTags('', $language.$stuTableName, true);

        return "\$GLOBALS['xoTheme']->addMeta( 'meta', '{$type}', {$stripTags});\n";
    }

    /*
    *  @public function getUserMetaKeywords
    *  @param string $moduleDirname
    *  
    *  @return string
    */
    public function getUserMetaKeywords($moduleDirname)
    {
        $implode = $this->phpcode->getPhpCodeImplode(',', '$keywords');

        return "{$moduleDirname}MetaKeywords(\${$moduleDirname}->getConfig('keywords').', '. {$implode});\n";
    }

    /*
    *  @public function getUserMetaDesc
    *  @param string $moduleDirname    
    *  @param string $language
    *  @param string $file
    *  
    *  @return string
    */
    public function getUserMetaDesc($moduleDirname, $language, $file = 'INDEX')
    {
        return "{$moduleDirname}MetaDescription({$language}{$file}_DESC);\n";
    }

    /*
    *  @public function getUserBreadcrumbs
    *  @param string $language
    *  @param string $moduleDirname    
    *  
    *  @return string
    */
    public function getUserBreadcrumbs($language, $tableName = 'index')
    {
        $stuTableName = strtoupper($tableName);

        return $this->phpcode->getPhpCodeArray('xoBreadcrumbs[]', 'title', $language.$stuTableName, true);
    }

    /*
    *  @public function getUserBreadcrumbs
    *  @param string $moduleDirname
    *  
    *  @return string
    */
    public function getUserBreadcrumbsHeaderFile($moduleDirname)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $this->phpcode->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= "\$xoBreadcrumbs = array();\n";
        $ret .= "\$xoBreadcrumbs[] = array('title' => \$GLOBALS['xoopsModule']->getVar('name'), 'link' => {$stuModuleDirname}_URL . '/');\n";

        return $ret;
    }

    /*
    *  @public function getUserBreadcrumbs
    *  @param null
    *  
    *  @return string
    */
    public function getUserBreadcrumbsFooterFile()
    {
        $cond = $this->xoopscode->getXoopsCodeTplAssign('xoBreadcrumbs', '$xoBreadcrumbs');
        $ret = $this->phpcode->getPhpCodeConditions('count($xoBreadcrumbs)', ' > ', '1', $cond, false, "\t\t");

        return $ret;
    }

    /*
    *  @public function getUserModVersion
    *  @param $eleArray
    *  @param $descriptions    
    *  @param $name
    *  @param $index	
    *  @param $num	
    *  
    *  @return string
    */
    public function getUserModVersion($eleArray = 1, $descriptions, $name = false, $index = false, $num = false)
    {
        $ret = '';
        $mv = '$modversion';
        if (!is_array($descriptions)) {
            $descs = array($descriptions);
        } else {
            $descs = $descriptions;
        }
        foreach ($descs as $key => $desc) {
            $one = (false === $name) ? $key : $name;
            $two = (false === $index) ? $key : $index;
            if ($eleArray === 1) {
                $ret .= $mv."['{$one}'] = {$desc};\n";
            } elseif ($eleArray === 2) {
                $ret .= $mv."['{$one}'][{$two}] = {$desc};\n";
            } elseif ($eleArray === 3) {
                $ret .= $mv."['{$one}'][{$two}]['{$key}'] = {$desc};\n";
            } elseif ($eleArray === 4) {
                $ret .= $mv."['{$one}'][{$two}][{$num}]['{$key}'] = {$desc};\n";
            }
        }

        return $ret;
    }
}
