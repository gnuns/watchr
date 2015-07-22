<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

class Template {

	private $Template;

	public function __construct($Pag)
	{
  	$this->Template = $this->getFile('T.' . $Pag);
		$this->parseAndIncludeParts();
	}

	private function getFile($file)
	{
		$tplFile = COMPONENTS_DIR . 'Templates' . DS . $file . '.html';
		if (is_file($tplFile)) {
			ob_start();
			readfile($tplFile);
			$fCont = ob_get_contents();
			ob_end_clean();
			return $fCont;
		} else {
			error(__METHOD__, "Unable to add the template file: " . $tplFile);
		}
	}

	private function parseAndIncludeParts()
	{
		$parts = array_filter(getStringBetweenAll('{:', ':}', $this->Template));
		//print_r($parts);
		foreach($parts as $k => $v)
		{
			$this->Template = str_replace('{:' .$v. ':}',
																		$this->getFile('P.' . $v) , $this->Template);
		}
	}

	public function setLabelWithPart($label, $part)
	{
		if(strstr($this->Template, "{" . $label . "}") !== false)
			$this->Template = str_replace("{" . $label . "}",
												$this->getFile('P.' . $part), $this->Template);
	}

	public function parseLoop($loopLabel, $toParse)
	{
		$loopContent = getStringBetween('{loop-' . $loopLabel . '}' ,
																		'{/loop-' . $loopLabel . '}', $this->Template);
		$finalRes = '';
		foreach ($toParse as $k => $a)
		{
			$lc = $loopContent;
			foreach ($a as $k => $v)
			{
				$lc = str_replace('{-'.$k.'-}', $v, $lc);
			}
			$finalRes .= $lc;
		}
		$this->Template = str_replace('{loop-' . $loopLabel . '}', '', str_replace('{/loop-' . $loopLabel . '}', '', $this->Template));
		$this->Template = str_replace($loopContent, $finalRes, $this->Template);
	}
	// replaces {label} to $data
	public function setLabel($label, $data)
	{
		if(strstr($this->Template, "{" . $label . "}") !== false)
			$this->Template = str_replace("{" . $label . "}", $data, $this->Template);
	}

	// get the html page as a string
	public function getOutput()
	{
		// replace Default Vars values
		$dv = array(
					'title' => Core::readConfig('SITE/TITLE'),
					'static' => Core::readConfig('SITE/STATIC'),
					'www' => Core::readConfig('SITE/WWW'));
		foreach ($dv as $k => $v)
		{
			$this->Template = str_replace("[" . $k . "]", $v, $this->Template);
		}

		return $this->Template;
	}

	// prints the html page
	public function writeOutput()
	{
		echo C_DEBUG ? str_replace('</body>', genStats() . '</body>',
															 $this->GetOutput()) : $this->GetOutput();
	}

}
