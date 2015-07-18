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
 * @version         $Id: TDMCreatePhpCode.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class TDMCreatePhpCode.
 */
class TDMCreatePhpCode
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
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TDMCreatePhpCode
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
    *  @public function getPhpCodeCommentLine
    *  @param $var
    *  @return string
    */
    public function getPhpCodeCommentLine($comment, $var = '')
    {
        return "// {$comment} {$var}\n";
    }

    /*
    *  @public function getPhpCodeVariables
    *  @param $type
    *  @param $var
    *  @return string
    */
    public function getPhpCodeVariables($type = 'REQUEST', $var = '')
    {
        $type = strtoupper($type);
        switch ($type) {
            case 'GET':
                $ret = "\$_GET['{$var}']";
                break;
            case 'POST':
                $ret = "\$_POST['{$var}']";
                break;
            case 'FILES':
                $ret = "\$_FILES['{$var}']";
                break;
            case 'COOKIE':
                $ret = "\$_COOKIE['{$var}']";
                break;
            case 'ENV':
                $ret = "\$_ENV['{$var}']";
                break;
            case 'SERVER':
                $ret = "\$_SERVER['{$var}']";
                break;
            default:
                $ret = "\$_REQUEST['{$var}']";
                break;
        }

        return $ret;
    }

    /*
    *  @public function getPhpCodeIncludeDir
    *  @param $directory
    *  @param $filename
    *  @param $once
    *  @return string
    */
    public function getPhpCodeIncludeDir($directory = '', $filename = '', $once = false)
    {
        if (!$once) {
            $ret = "include {$directory} .'/{$filename}.php';\n";
        } else {
            $ret = "include_once {$directory} .'/{$filename}.php';\n";
        }

        return $ret;
    }

    /*
     * @public function getPhpCodeConditions
     * @param string $condition
     * @param string $operator
     * @param string $type
     * @param string $contentIf
     * @param mixed  $contentElse
     *
     * @return string
     */
    public function getPhpCodeConditions($condition = '', $operator = '', $type = '', $contentIf = '', $contentElse = false)
    {
        if (false === $contentElse) {
            $ret = <<<EOT
	if ({$condition}{$operator}{$type}) {
		{$contentIf}
	}\n
EOT;
        } else {
            $ret = <<<EOT
	if ({$condition}{$operator}{$type}) {
		{$contentIf}
	} else {
		{$contentElse}
    }\n
EOT;
        }

        return $ret;
    }

    /*
     * @public function getPhpCodeForeach
     * @param string $array
     * @param string $content
     * @param string $value
     * @param string $arrayKey
     * @param string $key
     *
     * @return string
     */
    public function getPhpCodeForeach($array = '', $content = '', $value = false, $arrayKey = false, $key = false)
    {
        if ((false === $arrayKey) && (false === $key)) {
            $vars = "{$array} as {$value}";
        } elseif ((false === $arrayKey) && (false !== $key)) {
            $vars = "{$array} as {$key} => {$value}";
        } elseif ((false !== $arrayKey) && (false === $key)) {
            $vars = "array_key({$array}) as {$value}";
        }

        $ret = <<<EOT
	foreach({$vars}) {
		{$content}
	}\n
EOT;

        return $ret;
    }

    /*
     * @public function getPhpCodeFor
     * @param $var
     * @param $content
     * @param $value
     * @param $initVal
     * @param $operator
     *
     * @return string
     */
    public function getPhpCodeFor($var = '', $content = '', $value = '', $initVal = '', $operator = '')
    {
        $ret = <<<EOT
	for(\${$var} = {$initVal}; \${$var} {$operator} \${$value}; \${$var}++) {
		{$content}
	}\n
EOT;

        return $ret;
    }

    /*
     * @public function getPhpCodeWhile
     * @param $var
     * @param $content
     * @param $value
     * @param $operator
     *
     * @return string
     */
    public function getPhpCodeWhile($var = '', $content = '', $value = '', $operator = '')
    {
        $ret = <<<EOT
	while(\${$var} {$operator} {$value}) {
		{$content}
	}\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeSwitch
     *
     *  @param $op
     *  @param $content
     *
     *  @return string
     */
    public function getPhpCodeSwitch($op = '', $content = '')
    {
        $ret = <<<EOT
// Switch options
switch (\${$op}){
	{$content}
}\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeCaseSwitch
     *
     *  @param $case
     *  @param $content
     *
     *  @return string
     */
    public function getPhpCodeCaseSwitch($case = 'list', $content, $defaultAfterCase = false, $default = false)
    {
        if (is_string($case)) {
            $ret = "\tcase '{$case}':\n";
        } else {
            $ret = "\tcase {$case}:\n";
        }
        if ($defaultAfterCase) {
            $ret .= <<<EOT
    default:
		{$content}
	break;\n
EOT;
        } else {
            $ret .= <<<EOT
		{$content}
	break;\n
EOT;
        }
        if ($default !== false) {
            $ret = <<<EOT
    default:
		{$default}
	break;\n
EOT;
        }

        return $ret;
    }

    /*
    *  @public function getPhpCodeIsset
    *  @param $var
    *  @return string
    */
    public function getPhpCodeIsset($var)
    {
        return "isset(\${$var})";
    }

    /*
    *  @public function getPhpCodeImplode
    *  @param $left
    *  @param $right
    *  @return string
    */
    public function getPhpCodeImplode($left, $right)
    {
        return "implode('{$left} ', {$right})";
    }

    /*
    *  @public function getPhpCodeSprintf
    *  @param $left
    *  @param $right
    *  @return string
    */
    public function getPhpCodeSprintf($left, $right)
    {
        return "sprintf({$left}, {$right})";
    }

    /*
    *  @public function getPhpCodeEmpty
    *  @param $var
    *  @return string
    */
    public function getPhpCodeEmpty($var)
    {
        return "empty({$var})";
    }

    /*
    *  @public function getPhpCodeHeader
    *  @param $var
    *  @return string
    */
    public function getPhpCodeHeader($var)
    {
        return "header({$var})";
    }

    /*
    *  @public function getPhpCodeRawurlencode
    *  @param $var
    *  @return string
    */
    public function getPhpCodeRawurlencode($var)
    {
        return "rawurlencode({$var})";
    }

    /*
    *  @public function getPhpCodeHtmlentities
    *  @param $entitiesVar
    *  @param $entitiesQuote
    *  @return string
    */
    public function getPhpCodeHtmlentities($entitiesVar, $entitiesQuote = false)
    {
        $entitiesVar = $entitiesQuote !== false ? $entitiesVar.', '.$entitiesQuote : $entitiesVar;
        $entities = "htmlentities({$entitiesVar})";

        return $entities;
    }

    /*
    *  @public function getPhpCodeHtmlspecialchars
    *  @param $specialVar
    *  @param $specialQuote
    *  @return string
    */
    public function getPhpCodeHtmlspecialchars($specialVar, $specialQuote = false)
    {
        $specialVar = $specialQuote !== false ? $specialVar.', '.$specialQuote : $specialVar;
        $specialchars = "htmlspecialchars({$specialVar})";

        return $specialchars;
    }
}
