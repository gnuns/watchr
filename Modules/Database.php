<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

class Database extends PDO {

	private $nQueries = 0;

	public function __construct($db_host, $db_port, $db_user, $db_pass, $s_db) {
		$this->mDb_Host = $db_host;
		$this->mDb_Port = $db_port;
		$this->mDb_User = $db_user;
		$this->mDb_Pass = $db_pass;
		$this->mDb_Name = $s_db;
	}

	public function startDb() {
		try {
			parent::__construct('mysql:host=' . $this->mDb_Host . ';port=' . $this->mDb_Port .
								';dbname=' . $this->mDb_Name, $this->mDb_User, $this->mDb_Pass,
								array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(exception $e) {
			error(__METHOD__.'()', $e);
		}
	}

    /**
     * select
     * @param string $sql An SQL string
     * @param array $array Paramters to bind
     * @param constant $fetchMode A PDO Fetch mode
     * @return mixed
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC)
    {
        $sth = $this->prepare($sql);
        /*foreach ($array as $key => $value) {
            $sth->bindParam("$key", $value);
        }*/

		++$this->nQueries;
        $sth->execute($array);
        return $sth->fetchAll($fetchMode);
    }

    /**
     * insert
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     */
    public function insert($table, $data)
    {
        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");

        /*foreach ($data as $key => $value) {
            $sth->bindParam(":$key", $value);
        }*/
        ++$this->nQueries;
        $sth->execute($data);
    }

    /**
     * update
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     * @param string $where the WHERE query part
     */
    public function update($table, $data, $where)
    {
        ksort($data);

        $fieldDetails = NULL;
        foreach($data as $key=> $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");

        /*foreach ($data as $key => $value) {
            $sth->bindParam(":$key", $value);
        }*/

        ++$this->nQueries;
        $sth->execute($data);
    }

    /**
     * update
     * @param string $table A name of table to insert into
     * @param string $data The data to update
     * @param string $where the WHERE query part
     */
    public function updateSimple($table, $data, $where)
    {

        $sth = $this->prepare("UPDATE $table SET $data WHERE $where");

        ++$this->nQueries;
        $sth->execute();
    }

    /**
     * delete
     *
     * @param string $table
     * @param string $where
     * @param integer $limit
     * @return integer Affected Rows
     */
    public function delete($table, $where, $limit = 1)
    {
    	++$this->nQueries;
        return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
    }

	public function queryCount() {
		return $this->nQueries;
	}

}
