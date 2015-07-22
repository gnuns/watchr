<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

Core::requireModel('URL');
class HomeController extends Control
{
	private $Tpl;

	public function __construct($params = null)
	{

		if(isset($_POST['url-or-code']))
		{
			$uoc = $_POST['url-or-code'];
			if (filter_var($uoc, FILTER_VALIDATE_URL) === FALSE) {
    		header('Location: /track/' . $uoc);
			}
			else {
				$u = URL::addURL($uoc);
				header('Location: /track/' . $u->accessCode);
			}
		}

		$this->Tpl = new Template('Home');
		$this->Tpl->SetLabel('PageTitle', 'Home');
		$this->Tpl->WriteOutput();
		exit;
	}
}
