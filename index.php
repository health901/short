<?php

function ExceptionHandler(Exception $e) {
    echo json_encode(array('status' => 0, 'msg' => $e->getMessage()));
    exit;
}

set_exception_handler('ExceptionHandler');

require_once('app.php');
$app = new \Robin\Short\App();
$app->run();