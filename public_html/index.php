<?php

define('APP_ROOT', dirname(__DIR__));
require(APP_ROOT.DIRECTORY_SEPARATOR.'config.php');


$req=new Request();

$controller_name = ucfirst($req->getPath()) . 'Controller';
if (!class_exists($controller_name)) $controller_name = 'PagenotfoundController';
$controller = new $controller_name($req);

$action_name = $req->getMethodName(). 'Action';
if (!method_exists($controller,$action_name)) $action_name='notfoundAction';



try {
    $result = $controller->$action_name();
} catch (RepositoryTableNotExistsException $e) {
    $controller = new InstallController($req);
    $result = $controller->installAction();
} catch (Exception $e) {
    $controller = new ErrorController($req);
    $result = $controller->exceptionAction($e);
}


$helper_name=ucfirst($req->getOutputFormatName().'Helper');
if (!$req->getOutputFormatName() || !class_exists($helper_name)) {
    $helper_name='JsonHelper';
    $controller=new AcceptController($req);
    $result=$controller->errorAction();

}

$helper=new $helper_name($result,$req);
$helper->display();



