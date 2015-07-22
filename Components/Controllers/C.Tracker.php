<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

Core::requireModel('URL');
class TrackerController extends Control
{
	private $Tpl;

	public function __construct($params = null)
	{
		parent::loadParamMap(array(), $params);
		$ud = new URL(parent::getParam(0));

		if(!$ud->id)
			header('Location: /');

		$this->Tpl = new Template('Generic');
		$this->Tpl->SetLabel('PageTitle', ' Track ' . $ud->accessCode);
		$this->Tpl->setLabelWithPart('Content', 'Tracking');
		$this->Tpl->SetLabel('accessCode', $ud->accessCode);
		$this->Tpl->SetLabel('createdDate', date("j F Y, H:i:s", $ud->creationTimestamp));
		$this->Tpl->SetLabel('destinationURL', $ud->destinationURL);
		$this->Tpl->SetLabel('shortLink', Core::readConfig('SITE/SHORT_URL'));
		$this->Tpl->SetLabel('jsonLogs', $ud->getURLLogs());

		$this->Tpl->WriteOutput();
		exit;
	}
}
