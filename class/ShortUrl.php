<?php

/**
 * short ShortUrl
 *
 * @author VRobin
 * 
 */

namespace Robin\Short;

class ShortUrl {

    private $db;
    private $code;

    public function parse() {
	$this->db = \Robin\Short\App::DB();
	if ($this->parseCode()) {
	    $this->gotoDefault();
	}
	$url = $this->find($this->code);
	if (!$url) {
	    $this->gotoDefault();
	}
	$this->jump($url);
    }

    private function parseCode() {
	$code = $_GET['code'];
	if (!preg_match('/^\w{3,8}$/', $code)) {
	    return false;
	}
	$this->code = $code;
    }

    private function find() {
	$length = strlen($this->code);
	$table = 'len_' . $length;
	$sql = "SELECT * FROM {$table} WHERE code=binary('{$this->code}')";
	$result = $this->db->query($sql);
	if ($result->num_rows) {
	    return $result->fetch_object()->url;
	} else {
	    return false;
	}
    }

    private function gotoDefault() {
	throw new \Exception('url do not exist');
//	$this->jump(ST_DEFAULT_URL);
    }

    private function jump($url) {
	header('Location: ' . $url);
	exit;
    }

}

?>
