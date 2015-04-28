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
 * @version         $Id: autoloader.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access'); 
/**
 * @since 1.91
 */
class TDMCreateAutoload
{
	/**
	 * File where classes index is stored
	 */
	const INDEX_FILE = 'cache/class_index.php';

	/**
	 * @var Autoload
	 */
	protected static $instance;

	/**
	 * @var string module directory
	 */
	protected $mod_dir;

	/**
	 *  @var array array('classname' => 'path/to/filename')
	 */
	public $index = array();

	protected static $class_aliases = array('Autoload' => 'TDMCreateAutoload');

	protected function __construct()
	{
		$this->mod_dir = TDMC_PATH.'/';
		$file = TDMC_CLASSES_PATH.TDMCreateAutoload::INDEX_FILE;
		if (@filemtime($file) && is_readable($file))
			$this->index = include($file);
		else
			$this->generateIndex();
	}

	/**
	 * Get instance of autoload
	 *
	 * @return TDMCreateAutoload
	 */
	public static function getInstance()
	{
		if (!TDMCreateAutoload::$instance)
			TDMCreateAutoload::$instance = new TDMCreateAutoload();

		return TDMCreateAutoload::$instance;
	}

	/**
	 * Retrieve informations about a class in classes index and load it
	 *
	 * @param string $classname
	 */
	public function load($classname)
	{
		// Retrocompatibility 
		if (isset(TDMCreateAutoload::$class_aliases[$classname]) && !interface_exists($classname, false) && !class_exists($classname, false))
			return eval('class '.$classname.' extends '.TDMCreateAutoload::$class_aliases[$classname].' {}');
		// regenerate the class index if the requested file doesn't exists
		if ((isset($this->index[$classname]) && $this->index[$classname]['path'] && !is_file($this->mod_dir.$this->index[$classname]['path'])))
			$this->generateIndex();
		// Call directly class
		if (isset($this->index[$classname]['path']) && $this->index[$classname]['path'])
			require($this->mod_dir.$this->index[$classname]['path']);
	}

	/**
	 * Generate classes index
	 */
	public function generateIndex()
	{
		$classes = array_merge(	$this->getClassesFromDir('class/'), 
								$this->getClassesFromDir('class/files/'), 
								$this->getClassesFromDir('class/files/admin/'), 
								$this->getClassesFromDir('class/files/blocks/'), 
								$this->getClassesFromDir('class/files/classes/'), 
								$this->getClassesFromDir('class/files/css/'), 
								$this->getClassesFromDir('class/files/docs/'), 
								$this->getClassesFromDir('class/files/include/'), 
								$this->getClassesFromDir('class/files/language/'), 
								$this->getClassesFromDir('class/files/sql/'), 
								$this->getClassesFromDir('class/files/templates/'), 
								$this->getClassesFromDir('class/files/user/') );		
		ksort($classes);
		$content = '<?php return '.var_export($classes, true).'; ?>';

		// Write classes index on disc to cache it
		$filename = TDMC_CLASSES_PATH.TDMCreateAutoload::INDEX_FILE;
		$filename_tmp = tempnam(dirname($filename), basename($filename.'.'));
		if ($filename_tmp !== false && file_put_contents($filename_tmp, $content) !== false)
		{
			if (!@rename($filename_tmp, $filename))
				unlink($filename_tmp);
			else
				@chmod($filename, 0644);
		}
		// $filename_tmp couldn't be written . $filename should be there anyway (even if outdated), no need to die.
		else
			error_log('Cannot write temporary file '.$filename_tmp);
		$this->index = $classes;
	}

	/**
	 * Retrieve recursively all classes in a directory and its subdirectories
	 *
	 * @param string $path Relative path from root to the directory
	 * @return array
	 */
	protected function getClassesFromDir($path)
	{
		$classes = array();
		$mod_dir = $this->mod_dir;

		foreach (scandir($mod_dir.$path) as $file)
		{
			if ($file[0] != '.')
			{
				if (is_dir($mod_dir.$path.$file))
					$classes = array_merge($classes, $this->getClassesFromDir($path.$file.'/'));
				else if (substr($file, -4) == '.php')
				{
					$content = file_get_contents($mod_dir.$path.$file);
			 		$pattern = '#\W((abstract\s+)?class|interface)\s+(?P<classname>'.basename($file, '.php').'?)'.'(?:\s+extends\s+[a-z][a-z0-9_]*)?(?:\s+implements\s+[a-z][a-z0-9_]*(?:\s*,\s*[a-z][a-z0-9_]*)*)?\s*\{#i';
			 		if (preg_match($pattern, $content, $m))	{
			 			$classes[$m['classname']] = array('path' => $path.$file);						
			 		}
				}
			}
		}

		return $classes;
	}

	public function getClassPath($classname) {
		return (isset($this->index[$classname]) && isset($this->index[$classname]['path'])) ? $this->index[$classname]['path'] : null;
	}
} 
/*
 function autoLoader($className) {
	// Directories
	$directories = array(
		'', 
		'files/', 
		'files/admin/',
		'files/blocks/',
		'files/classes/',
		'files/css/',
		'files/docs/',
		'files/include/',
		'files/language/',
		'files/sql/',
		'files/templates/user/',
		'files/templates/admin/',
		'files/templates/blocks/',
		'files/user/'
	);
	
	// File naming format
	$fileNameFormats = array( '%s.php' );
	
	foreach($directories as $directory) {
		foreach($fileNameFormats as $fileNameFormat) {
			$path = $directory.sprintf($fileNameFormat, $className);
			if(file_exists($path)) {
				include_once $path;
				return true;
			}
		}
	}
	return false;
}*/
//spl_autoload_register('TDMCreateAutoload');
