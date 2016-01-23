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
defined('XOOPS_ROOT_PATH') || die('Restricted access');
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
    *  @param $comment
    *  @param $var
    *  @return string
    */
    public function getPhpCodeCommentLine($comment = '', $var = '')
    {
        return "// {$comment} {$var}\n";
    }

    /*
    *  @public function getPhpCodeGlobalsVariables
    *  @param $type
    *  @param $var
	*
    *  @return string
    */
    public function getPhpCodeGlobalsVariables($type = 'REQUEST', $var = '')
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
     * @public function getPhpCodeRemoveCarriageReturn
     * @param $string     
     *
     * @return string
     */
    public function getPhpCodeRemoveCarriageReturn($string)
    {
        return str_replace(array("\n", "\r"), '', $string);
    }

    /*
    *  @public function getPhpCodeFileExists
    *  @param $filename
    *
    *  @return string
    */
    public function getPhpCodeFileExists($filename)
    {
        return "file_exists({$filename})";
    }

    /*
    *  @public function getPhpCodeIncludeDir
    *  @param $directory
    *  @param $filename
    *  @param $once
    *  @param $isPath
    *
    *  @return string
    */
    public function getPhpCodeIncludeDir($directory = '', $filename = '', $once = false, $isPath = false)
    {
        if ($once === false) {
            if ($isPath === false) {
                $ret = "include {$directory} .'/{$filename}.php';\n";
            } else {
                $ret = "include {$directory};\n";
            }
        } else {
            if ($isPath === false) {
                $ret = "include_once {$directory} .'/{$filename}.php';\n";
            } else {
                $ret = "include_once {$directory};\n";
            }
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
    public function getPhpCodeConditions($condition = '', $operator = '', $type = '', $contentIf = '', $contentElse = false, $t = '')
    {
        if (false === $contentElse) {
            $ret = <<<EOT
{$t}if ({$condition}{$operator}{$type}) {
{$t}{$t}{$contentIf}
{$t}}\n
EOT;
        } else {
            $ret = <<<EOT
{$t}if ({$condition}{$operator}{$type}) {
{$t}{$t}{$contentIf}
{$t}} else {
{$t}{$t}{$contentElse}
{$t}}\n
EOT;
        }

        return $ret;
    }

    /*
     * @public function getPhpCodeForeach
     * @param string $array
     * @param string $arrayKey     
     * @param string $key
     * @param string $value     
     * @param string $content
     *
     * @return string
     */
    public function getPhpCodeForeach($array = '', $arrayKey = false, $key = false, $value = false, $content = '', $t = '')
    {
        $vars = '';
        if ((false === $arrayKey) && (false === $key)) {
            $vars = "\${$array} as \${$value}";
        } elseif ((false === $arrayKey) && (false !== $key)) {
            $vars = "\${$array} as \${$key} => \${$value}";
        } elseif ((false !== $arrayKey) && (false === $key)) {
            $vars = "array_keys(\${$array}) as \${$value}";
        }

        $ret = <<<EOT
{$t}foreach({$vars}) {
{$t}{$t}{$content}
{$t}}\n
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
    public function getPhpCodeFor($var = '', $content = '', $value = '', $initVal = '', $operator = '', $t = '')
    {
        $ret = <<<EOT
{$t}for(\${$var} = {$initVal}; \${$var} {$operator} \${$value}; \${$var}++) {
{$t}{$t}{$content}
{$t}}\n
EOT;

        return $ret;
    }

    /*
     * @public function getPhpCodeWhile
     * @param $var
     * @param $content
     * @param $value
     * @param $operator
	 *  @param $t
     *
     * @return string
     */
    public function getPhpCodeWhile($var = '', $content = '', $value = '', $operator = '', $t = '')
    {
        $ret = <<<EOT
{$t}while(\${$var} {$operator} {$value}) {
{$t}{$t}{$content}
{$t}}\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeSwitch
     *
     *  @param $op
     *  @param $content
	 *  @param $t
     *
     *  @return string
     */
    public function getPhpCodeSwitch($op = '', $content = '', $t = '')
    {
        $ret = <<<EOT
// Switch options
{$t}switch (\${$op}){
{$t}{$t}{$content}
{$t}}\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeCaseSwitch     
     *
     *  @param $cases
     *  @param $defaultAfterCase
     *  @param $default
	 *  @param $t
     *
     *  @return string
     */
    public function getPhpCodeCaseSwitch($cases = array(), $defaultAfterCase = false, $default = false, $t = "\t")
    {
        $ret = '';
        $def = "{$t}default:\n";
        foreach ($cases as $case => $value) {
            $case = is_string($case) ? "'{$case}'" : $case;
            if (!empty($case)) {
                $ret .= "{$t}case {$case}:\n";
                if ($defaultAfterCase != false) {
                    $ret .= $def;
                }
                if (is_array($value)) {
                    foreach ($value as $content) {
                        $ret .= "{$t}{$t}{$content}\n";
                    }
                }
                $ret .= "{$t}break;\n";
            }
            $defaultAfterCase = false;
        }
        if ($default !== false) {
            $ret .= $def;
            $ret .= "{$t}{$default}\n";
            $ret .= "{$t}break;\n";
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
    *  @public function getPhpCodeUnset
    *  @param $var
    *  @return string
    */
    public function getPhpCodeUnset($var = '')
    {
        return "unset(\${$var});\n";
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
    *  @public function getPhpCodePregReplace
    *  @param $return
    *  @param $exp
    *  @param $str
    *  @param $val
    *  @param $isParam
    *
    *  @return string
    */
    public function getPhpCodePregReplace($return, $exp, $str, $val, $isParam = false)
    {
        if ($isParam === false) {
            $ret = "\${$return} = preg_replace( '{$exp}' , '{$str}' , {$val});\n";
        } else {
            $ret = "preg_replace( '{$exp}' , '{$str}' , {$val})";
        }

        return $ret;
    }

    /*
    *  @public function getPhpCodeStrReplace
    *  @param $left
    *  @param $var
    *  @param $str
    *  @param $value
    *  @param $isParam
    *
    *  @return string
    */
    public function getPhpCodeStrReplace($left, $var, $str, $value, $isParam = false)
    {
        if ($isParam === false) {
            $ret = "\${$left} = str_replace( '{$var}' , '{$str}' , {$value});\n";
        } else {
            $ret = "str_replace( '{$var}' , '{$str}' , {$value})";
        }

        return $ret;
    }

    /*
    *  @public function getPhpCodeStripTags
    *  @param $left
    *  @param $value
    *  @param $isParam
    *
    *  @return string
    */
    public function getPhpCodeStripTags($left, $value, $isParam = false)
    {
        if ($isParam === false) {
            $ret = "\${$left} = strip_tags({$value});\n";
        } else {
            $ret = "strip_tags({$value})";
        }

        return $ret;
    }

    /*
    *  @public function getPhpCodeHtmlentities
    *  @param $entitiesVar
    *  @param $entitiesQuote
    *  @return string
    */
    public function getPhpCodeHtmlentities($entitiesVar, $entitiesQuote = false)
    {
        $entitiesVar = ($entitiesQuote !== false) ? $entitiesVar.', '.$entitiesQuote : $entitiesVar;
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
        $specialVar = ($specialQuote !== false) ? $specialVar.', '.$specialQuote : $specialVar;
        $specialchars = "htmlspecialchars({$specialVar})";

        return $specialchars;
    }
}
