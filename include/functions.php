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
 * @version         $Id: functions.php 11084 2013-02-23 15:44:20Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Get Requests
 *
 * @param       array   $global
 * @param       string  $key
 * @param       string  $default
 * @param       mixed   $type
 * @return      mixed   Returns string, integer or array
 */
function TDMCreate_CleanVars( &$global, $key, $default = '', $type = 'int' ) {
    switch ( $type ) {
        case 'string':
            $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_MAGIC_QUOTES ) : $default;
            break;
        case 'int': default:
            $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_NUMBER_INT ) : $default;
            break;
    }
    if ( $ret === false ) {
        return $default;
    }
    return $ret;
}
/**
 * Clear directory and its contents
 *
 * @param       string   $folder The contents
 * @return      bool     Returns true on success, false on failure
 */
function TDMCreate_clearDir($folder) {
	$opening = @opendir($folder);
	if (!$opening) return;
	while($file = readdir($opening)) {
		if ($file == '.' || $file == '..') continue;
		if (is_dir($folder."/".$file)) {
			$r = TDMCreate_clearDir($folder."/".$file);
			if (!$r) return false;
		} else {
			$r = @unlink($folder."/".$file);
			if (!$r) return false;
		}
	}
	closedir($opening);
	$r = @rmdir($folder);
	if (!$r) return false;
	return true;
}

/**
 * Copy a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.0
 * @param       string   $source    The source
 * @param       string   $dest      The destination
 * @return      bool     Returns true on success, false on failure
 */
function TDMCreate_copyr($source, $dest)
{
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        if (is_dir("$source/$entry") && ($dest !== "$source/$entry")) {
            TDMCreate_copyr("$source/$entry", "$dest/$entry");
        } else {
            copy("$source/$entry", "$dest/$entry");
        }
    }

    // Clean up
    $dir->close();
    return true;
}
//
function UcFirstAndToLower($str)
{
	 return ucfirst(strtolower(trim($str)));
}