<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * short User
 *
 * @author VRobin
 * 
 */
namespace Robin\Short;

class User {

    private $level;

    public function __construct() {
	if(!$this->verify()){
	    throw new \Exception('Unauthorized', 401);
	}
    }

    public function verify() {
	if (isset($_GET['u'])) {
	    $username = \Robin\Short\App::DB()->escape($_GET['u']);
	}else{
	    return false;
	}
	if (isset($_GET['p'])) {
	    $password = \Robin\Short\App::DB()->escape($_GET['p']);
	}
	if(!$username || !$password){
	    return FALSE;
	}
	$password = MD5($password);
	$sql = "SELECT * FROM user WHERE username='{$username}' AND password='{$password}'";

	$result = \Robin\Short\App::DB()->query($sql);

	if (!$result->num_rows) {
	    return FALSE;
	}
	$user = $result->fetch_object();
	$this->level = $user->level;
	return TRUE;
    }

    public function getLevel(){
	return $this->level;
    }
}
