<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

// Directory Defs
define('DS', DIRECTORY_SEPARATOR);

define('ROOT_DIR', dirname(__FILE__) . DS);
define('MODULES_DIR', ROOT_DIR . 'Modules' . DS);
define('COMPONENTS_DIR', ROOT_DIR . 'Components' . DS);

require_once(ROOT_DIR . 'Configuration.php');

// System charset
define('C_CHARSET', 'utf-8');

// Error reporting TRUE if C_DEBUG is true
error_reporting((C_DEBUG ? E_ALL : FALSE));
ini_set('display_errors', C_DEBUG ? '1' : '0');

// Required Modules
require_once(MODULES_DIR . 'Function.php');
require_once(MODULES_DIR . 'Router.php');

require_once(MODULES_DIR . 'Database.php');;

require_once(MODULES_DIR . 'Control.php');
require_once(MODULES_DIR . 'Template.php');

class Core
{
	private static $Config;

	public static function Init($web = true)
	{
		// Set default charset
		ini_set('default_charset', C_CHARSET);
		// turns on the output compression
		ini_set('zlib.output_compression', 'On');
		// Set default timezone
		date_default_timezone_set('America/Sao_Paulo');
		// internal language
		setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

		self::parseConfig();
		Router::Init();
	}


	public static function connectDB()
	{
		global $Db;

		if($Db == null)
		{
			$Db = new Database(self::$Config['DATABASE']['HOST'],
							self::$Config['DATABASE']['PORT'],
							self::$Config['DATABASE']['USER'], self::$Config['DATABASE']['PASS'],
							self::$Config['DATABASE']['NAME']);
			$Db->startDb();
			unset(self::$Config['DATABASE']);
		}

	}

	public static function requireController($controllerName, $params)
	{
		$fi = COMPONENTS_DIR . 'Controllers' . DS . 'C.'. $controllerName . '.php';

		if (file_exists($fi))
			include_once ($fi);
		else
			error('Core.requireController(' . $controllerName . ', $params)',
						'Required controller not found: <br />' . $fi);
		$cClass = str_replace('.', '', $controllerName) . 'Controller';
		new $cClass($params);
	}

	public static function requireModel($model)
	{
		$fi = COMPONENTS_DIR . 'Models' . DS . 'M.' . $model . '.php';
		if (file_exists($fi))
			include_once($fi);
		else
			error('Core.requireModel(' . $model . ')',
	          'Required Model not found: <br />' . $fi);
	}

	// reads the configuration
	public static function readConfig($cd)
	{
		list($kind, $key) = explode('/', $cd);
		if($kind == 'DATABASE')
			return null;
		return isset(self::$Config[$kind][$key]) ? self::$Config[$kind][$key] : null;
	}

	// transfer configuration to a private array what is accessed via readConfig(key);
	private static function parseConfig()
	{
		global $CCONFIG;
		self::$Config = $CCONFIG;
		unset($CCONFIG);
	}
}
