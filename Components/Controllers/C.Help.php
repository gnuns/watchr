<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

class HelpController extends Control
{
	private $Tpl;

	public function __construct($params = null)
	{

		$this->Tpl = new Template('Generic');
		$this->Tpl->SetLabel('PageTitle', 'Help');
		$this->Tpl->setLabelWithPart('Content', 'Help');
		$this->Tpl->WriteOutput();
		exit;
	}
}
