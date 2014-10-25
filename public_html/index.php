<?php

ini_set('display_errors','on');

define('APP_ROOT', dirname(__DIR__));

spl_autoload_register('Autoload');

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
}


$helper_name=ucfirst($req->getOutputFormatName().'Helper');
if (!$req->getOutputFormatName() || !class_exists($helper_name)) {
    $helper_name='JsonHelper';
    $controller=new AcceptController($req);
    $result=$controller->errorAction();

}

$helper=new $helper_name($result,$req);
$helper->display();


function Autoload($classname)
{       

    $fname=$classname.'.php';
    $dir=APP_ROOT.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;



    $suffix='';
    if (preg_match('/[a-zA-Z0-9]+Controller$/', $classname)) {
        $suffix='controllers'.DIRECTORY_SEPARATOR;
    } elseif (preg_match('/[a-zA-Z0-9]+Model$/', $classname)) {
        $suffix='models'.DIRECTORY_SEPARATOR;
    } elseif (preg_match('/[a-zA-Z0-9]+View$/', $classname)) {
        $suffix='views'.DIRECTORY_SEPARATOR;
    } elseif (preg_match('/[a-zA-Z0-9]+Helper$/', $classname)) {
        $suffix='helpers'.DIRECTORY_SEPARATOR;
    } elseif (preg_match('/[a-zA-Z0-9]+Service$/', $classname)) {
        $suffix='services'.DIRECTORY_SEPARATOR;
    } elseif (preg_match('/[a-zA-Z0-9]+Repository$/', $classname)) {
        $suffix='repositories'.DIRECTORY_SEPARATOR;
    } elseif (preg_match('/[a-zA-Z0-9]+Exception$/', $classname)) {
        $suffix='exceptions'.DIRECTORY_SEPARATOR;
    }   
    return include($dir.$suffix.$fname);
}       

