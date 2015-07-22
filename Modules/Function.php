<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

function getTime() {
	$array_time = explode(" ", microtime());
	return floatval($array_time[1]) + floatval($array_time[0]);
}

$ts = getTime();

//////////////////////// TODO
function escape($i) {
	return preg_replace("/[^a-zA-Z0-9]/", "", $i); // Only Alpha-numeric (é o que tem pra hj, malz :/)
}
function descape($i) {
	return htmlentities($i, ENT_QUOTES);
}
/////////////////////////// //


function genAccessID($in) {
	return strtolower( substr(hash('gost', (rand(0,9999) .  $in . rand(5,545646) . '-wacthr')), 2, 10) );
}

function error($from, $text = '') {
	if (C_DEBUG) {
		$html = '
		<html>
			<head>
				<title>[WATCHR DEBUG] ERRO!</title>
				<meta charset="utf-8" />
				<style>
					body {
						background-color: #282828;
						color: #ccc;
						font-family: Arial, Helvetica, sans-serif;
						font-size: 12px;
					}
					.center {
						width:500px;
						min-height: 50px;
						display:table;
						margin:auto;
						background-color: #222;
						border:2px dotted #212121;
						padding:20px;
						margin-top:10%;
					}
				</style>
			</head>
			<body>
				<div class="center">
					<p style="color:#CD3333; font-weight: bold;margin-top:0px; margin-bottom: 0px;">Putz cara, erro!</p>
					<p style="color:#EFEFEF; font-weight: bold;margin-top:2px;margin-bottom: 0px;">Método: <span style="color:#AAA">' . $from . '</span></p>
					<p style="color:#EFEFEF; font-weight: bold;margin-top:2px">Descrição:  <span style="color:#AAA">' . $text . '</span></p>
				</div>
				' . genStats() . '
			</body>
		</html>';
		echo $html;
		exit ;
	} else {
		header('Location: ./');
		echo "";
		exit ;
	}
}

function genStats() {
	global $ts;
	$tt = round(abs(getTime() - $ts), 4);
	global $Db;
	return '<div style="font-size: 11px;font-family: Consolas, Arial, Helvetica, '.
	'sans-serif;position: fixed; bottom: 0px; right: 0px;padding:5px;line-height:11px;'.
	' background:rgba(0,0,0,0.6); color:white"><span style="font-weight:bold">[WATCHR DEBUG]</span> Script Load Time: ' .
	$tt . 's (' . ($tt * 1000) . 'ms) | Memory Load: ' .
	round((memory_get_usage() / 1024 / 1024), 2) . ' MB' .
	(($Db != null) ? ' | QueryCount: ' . $Db -> queryCount() : '') . (isset($_POST) && (count($_POST)) ? ' | Params posted: ' . count($_POST) : '') . '</div>';

}

// http://stackoverflow.com/a/9826656
function getStringBetween($start, $end, $string){
    /*$string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);*/
    $matches = array();
		$delimiter = '#';
    $regex = $delimiter . preg_quote($start, $delimiter)
                    . '(.*?)'
                    . preg_quote($end, $delimiter)
                    . $delimiter
                    . 's';
    preg_match($regex, $string, $matches);
		//print_r($matches[0]);
		return $matches[1];
}

/*
 * Meio que uma fucking gambiarra.
 * Use com moderação.
 */
function getStringBetweenAll($start, $end, $str){
    $matches = array();
		$delimiter = '#';
    $regex = $delimiter . preg_quote($start, $delimiter)
                    . '(.*?)'
                    . preg_quote($end, $delimiter)
                    . $delimiter
                    . 's';
    preg_match_all($regex, $str, $matches);
    $realResults = array();
    foreach($matches as $k => $v)
		{
			foreach($v as $key => $value) {
				if(!startsWith($value, $start) && !endsWith($value, $end)) {
					$realResults[] = $value;
				}
			}
		}
    return $realResults;
}

function startsWith($haystack, $needle) {
	return $needle === "" || strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle) {
	return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}
