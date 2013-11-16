<?php

/**
 * short DB
 *
 * @author VRobin
 * 
 */

namespace Robin\Short;

class DB {

    private $mysqli;
    private $error;

    public function __construct() {
	$port = defined('ST_DB_PORT') ? ST_DB_PORT : 3306;
	$this->mysqli = new \mysqli(ST_DB_HOST, ST_DB_USER, ST_DB_PW, ST_DB_DATABASE, $port);
	if ($this->mysqli->connect_error) {
	    die('Connect Error (' . $this->mysqli->connect_errno . ') '
		    . $this->mysqli->connect_error);
	}
    }
    public function __destruct() {
	$this->mysqli->close();
    }

    public function query($sql,$multi = false) {
	if($multi){
	    $result = $this->mysqli->multi_query($sql);
	    while ($this->mysqli->more_results() && $this->mysqli->next_result()); 
	}else{
	    $result = $this->mysqli->query($sql);
	}
	
	if ($result === FALSE) {
	    $this->error = $this->mysqli->error;
	    return FALSE;
	}
	return $result;
    }

    public function getError() {
	return $this->error;
    }

}

?>
