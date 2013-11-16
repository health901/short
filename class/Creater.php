<?php

/**
 * short CreateUrl
 *
 * @author VRobin
 * 
 */

namespace Robin\Short;

class Creater {

    private $len;
    private $string;
    private $db;
    private $table;

    public function __construct() {
	$user = new \Robin\Short\User();
	$this->len = $user->getLevel();
	$this->table = 'len_' . $this->len;
	$this->db = \Robin\Short\App::DB();
    }

    public function create() {
	if (empty($_GET['url'])) {
	    throw new \Exception('Url Can Not Be Empty');
	    exit;
	}

	$url = $this->db->escape($_GET['url']);

	if ($_code = $this->checkExist($url)) {
	    $code = $_code;
	} else {
	    $code = $this->gen();
	    $this->insert($code, $url);
	}
	echo json_encode(array('status' => 1, 'code' => $code));
    }

    private function checkExist($url) {
	$hash = md5($url);
	$sql = "SELECT code FROM `{$this->table}` WHERE hash='{$hash}'";
	$result = $this->db->query($sql);
	if (!$result) {
	    echo $this->db->getError();
	    exit;
	} else {
	    if ($result->num_rows) {
		return $result->fetch_object()->code;
	    } else {
		return false;
	    }
	}
    }

    private function insert($code, $url) {
	$hash = md5($url);
	$sql = "INSERT INTO `{$this->table}` (code,hash,url) VALUES ('{$code}','{$hash}','{$url}')";
	$result = $this->db->query($sql);
	if (!$result) {
	    echo $this->db->getError();
	    exit;
	}
    }

    public function gen() {
	$id = $this->getLastId() + 1;
	$arrOrigin = $this->conv($id);
	$arr = $this->setOffset($arrOrigin);
	$hash = '';
	$string = $this->getString();
	for ($i = 0; $i < $this->len; $i++) {
	    $hash.=$string[$arr[$i]];
	}
	return $hash;
    }

    private function getString() {
	$sql = "SELECT * FROM `hash` WHERE length='{$this->len}'";
	$result = $this->db->query($sql);
	if (!$result) {
	    echo $this->db->getError();
	    exit;
	}
	return $result->fetch_object()->string;
    }

    private function getLastId() {
	$sql = "SELECT max(id) as lastid FROM `{$this->table}`";
	$result = $this->db->query($sql);
	if (!$result) {
	    echo $this->db->getError();
	    exit;
	}
	return $result->fetch_object()->lastid;
    }

    private function conv($num) {
	$n = $num;
	$r = 0;
	$array = array_fill(0, $this->len, 0);
	for ($i = 1; $i <= $this->len; $i++) {
	    $r = $n % 62;
	    $array[$this->len - $i] = $r;
	    $n = ($n - $r) / 62;
	    if ($n == 0) {
		break;
	    }
	}
	return $array;
    }

    private function setOffset($array) {
	$offset17 = array(59, 1, 17, 46, 30, 23, 2, 7, 24, 55, 47, 14, 40, 58, 26, 9, 33);
	$offset13 = array(8, 19, 44, 43, 31, 59, 1, 17, 46, 30, 23, 2, 7);
	$offset11 = array(44, 43, 31, 59, 1, 17, 46, 30, 23, 2, 7);
	$offset7 = array(59, 1, 17, 46, 30, 23, 2, 7);
	$offset5 = array(14, 40, 58, 26, 7);
	$offset3 = array(24, 55, 47);
	$offset2 = array(24, 55);

	$len = $offset_m = array(11, 5, 3, 2, 7);
	$offset = array_merge(array(17), array_slice($offset_m, 0, $this->len - 3), array(13));

	for ($i = 0; $i <= $this->len - 2; $i++) {
	    $offset_array_n = 'offset' . $offset[$i];
	    $offset_array = $$offset_array_n;
	    $x = $array[$this->len - 1] % $offset[$i];
	    $offset_v = $offset_array[$x];
	    $array[$i] = $array[$i] + $offset_v;
	    if ($array[$i] >= 62) {
		$array[$i]-=62;
	    }
	}
	return $array;
    }

}

?>
