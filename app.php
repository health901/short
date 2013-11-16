<?php

namespace Robin\Short;

require_once('config.php');
require_once(ST_DIR_CLASS . 'DB.php');

class App {

    public function run() {
	$this->exec($this->router());
    }

    protected function router() {
	if (!isset($_GET['r'])) {
	    return 'parse';
	} else {
	    if ($_GET['r'] == 'create') {
		return 'create';
	    } else {
		return '404';
	    }
	}
    }

    protected function exec($router) {
	if ($router == 'create') {
	    require_once(ST_DIR_CLASS . 'User.php');
	    require_once(ST_DIR_CLASS . 'Creater.php');
	    $handle = new \Robin\Short\Creater();
	    $handle->create();
	} elseif ($router == 'parse') {
	    require_once(ST_DIR_CLASS . 'ShortUrl.php');
	    $handle = new \Robin\Short\ShortUrl();
	    $handle->parse();
	}
    }

    static public function DB() {
	return new \Robin\Short\DB();
    }

}