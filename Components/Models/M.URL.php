<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

class URL
{
	public $id = false;

	public $accessCode;
	public $creationTimestamp;
	public $destinationURL;

	public $logs;

	public function __construct($accessCode)
	{
		$uData = self::getURLData($accessCode);

		if(!$uData) {
			return false;
		}

		$this->id = $uData['id'];

		$this->accessCode = $uData['access_code'];
		$this->creationTimestamp = $uData['creation_timestamp'];
		$this->destinationURL = $uData['destination_url'];

		$this->logs = $this->getURLLogs();

	}

	public function getURLData($ac)
	{
		Core::connectDB();
		global $Db;
		$e = $Db->select("
			SELECT *
			FROM urls
			WHERE access_code = :ac LIMIT 1",
			array(':ac' => $ac)
		);

		return isset($e[0]) ? (isset($e[0]['id']) ? $e[0] : false) : false;

	}

	public function getURLLogs()
	{
		Core::connectDB();
		global $Db;
		$e = $Db->select("SELECT timestamp AS date, ip_address AS ipAddress, user_agent AS userAgent,
			referring_url AS refURL
			FROM url_logs
			WHERE url_id = :uid",
			array(':uid' => $this->id)
		);

		return json_encode($e);
	}

	public function addLog()
	{
		Core::connectDB();
		global $Db;

		$Db->insert('url_logs',
			array(
				'url_id' => $this->id,
				'timestamp' => time(),
				'ip_address' => $_SERVER["REMOTE_ADDR"],
				'user_agent' => '' . $_SERVER["HTTP_USER_AGENT"],
				'referring_url' => '' . @$_SERVER["HTTP_REFERER"]
			)
		);
	}

	public static function addURL($u)
	{
		Core::connectDB();
		global $Db;
		$ac = genAccessID($u);
		$Db->insert('urls',
			array(
				'access_code' => $ac,
				'creation_timestamp' => time(),
				'destination_url' => $u
			)
		);
		return new URL($ac);
	}
}
