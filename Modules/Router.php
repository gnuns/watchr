<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

class Router {

	private static $routes = array();
	private static $requestURI = null;

	public static function Init()
	{
		self::$requestURI = $_SERVER['REQUEST_URI'];
		self::$requestURI = (startsWith(self::$requestURI, '/')) ? self::$requestURI :
																								 '/' . self::$requestURI;
    self::loadRequestMap();
		self::routeRequests();
	}

  private static function loadRequestMap()
	{
		self::addToRequestMap('/', 'Home');
		self::addToRequestMap('/track', 'Tracker');
		self::addToRequestMap('/help', 'Help');
  }

  private static function addToRequestMap($requestURL, $controller)
  {
		if(!array_key_exists($requestURL, self::$routes))
		{
			self::$routes[$requestURL] = $controller;
		}
	}


	private static function routeRequests()
	{
    //CHECK REQUEST LENGHT. MAX=256
		if(strstr(self::$requestURI, 'index.php') || strlen($_SERVER['REQUEST_URI']) > 256)
		{
			header('Location: /'.Core::readConfig('SITE/WWW'));
			exit;
		}

    self::$requestURI = str_replace(Core::readConfig('SITE/WWW'), '',
                                                self::$requestURI);

    self::$requestURI = explode('?', self::$requestURI);
		self::$requestURI = self::$requestURI[0]; // fuq de ?

    while(strstr(self::$requestURI, '//'))
		{
			self::$requestURI = str_replace('//', '/', self::$requestURI);
		}

		$requestParams = explode('/', self::$requestURI);
		$requestPage = strtolower(startsWith($requestParams[1], '/') ?
                              $requestParams[1] : '/' . $requestParams[1]);
		$requestParams = array_slice($requestParams, 2);

		if(array_key_exists($requestPage, self::$routes))
		{
			if(isset($requestParams[0]) && $requestParams[0] == '')
			{
				$requestParams = null;
			}

			Core::requireController(self::$routes[$requestPage], $requestParams);
		}
		else
		{
			Core::requireController('Redirect', array_slice(explode('/', self::$requestURI), 1));
		}
	}

}
