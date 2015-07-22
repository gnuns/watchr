<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

class Control
{
  private static $params = array();
  private static $rawParams = array();

  public static function loadParamMap(array $mapedParams, $slashParams)
  {
		self::$rawParams = $slashParams;
		if(self::$rawParams != null)
		{
			$lastRequiredInput = false;
			foreach(self::$rawParams as $pNumber => $pName)
			{
				if(array_key_exists($pName, $mapedParams) &&
					!$lastRequiredInput &&
					!array_key_exists($pName, self::$params))
				{
					if($mapedParams[$pName] == true && isset(self::$rawParams[intval($pNumber) + 1]))
					{
						self::$params[$pName] = escape(self::$rawParams[intval($pNumber) + 1]);
						$lastRequiredInput = true;
					} else {
						self::$params[$pName] = true;
						$lastRequiredInput = false;
					}
				}
				else {
					$lastRequiredInput = false;
				}
			}
		}
  }

  public static function getParam($param)
  {
	if(is_int($param) && isset(self::$rawParams[$param])) {
		return self::$rawParams[$param];
	}
    else if(array_key_exists($param, self::$params)) {
      return self::$params[$param];
    }

    return false;
  }
}
