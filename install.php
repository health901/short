<?php
if(file_exists('install.lock')){
    exit('Allready Installed');
}
require_once('config.php');
require_once(ST_DIR_CLASS . 'DB.php');

/**
 * create struct
 */
$sql = file_get_contents('install.sql');
$db = new \Robin\Short\DB();
if (!$db->query($sql, true)) {
    echo $db->getError();
    exit;
}
/**
 * insert data
 */
#create hash table
$string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$ss = array();
for ($i = 3; $i <= 8; $i++) {
    $ss[$i] = str_shuffle($string);

}
foreach ($ss as $len => $s) {
    $sql_string = "INSERT INTO `hash` (length,string) VALUES ('{$len}','{$s}');";
    if (!$db->query($sql_string)) {
	echo $sql_string . "<br>";
	echo $db->getError();
	exit;
    }
}
$pw = createPw();
$pw_md5 = md5($pw);
$sql_user = "INSERT INTO user (username,password,level) VALUES ('test','{$pw_md5}',3)";
if (!$db->query($sql_user)) {
    echo $sql_user . "<br>";
    echo $db->getError();
    exit;
}

file_put_contents('install.lock', '');
unset($db);

echo 'Install Success.<br>';
echo 'Your test user username and password:<br>';
echo 'test<br>';
echo $pw;

function createPw() {
    $string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $salt = "";
    for ($i = 1; $i <= 10; $i++) {
	$pos = rand(1, 62);
	$salt.=substr($string, $pos, 1);
    }
    return $salt;
}

?>
