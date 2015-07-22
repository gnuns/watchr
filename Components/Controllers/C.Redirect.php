<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

Core::requireModel('URL');
class RedirectController extends Control
{
	private $Tpl;

	public function __construct($params = null)
	{
		parent::loadParamMap(array(), $params);
		$ud = new URL(parent::getParam(0));
		if(!$ud->id)
			header('Location: /');
		else
		{
			$ud->addLog();
			header('Location: ' . $ud->destinationURL);
		}
		exit;
	}
}
