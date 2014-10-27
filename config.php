<?php

define('MYSQL_DSN','mysql:host=localhost;dbname=test1');
define('MYSQL_USER','test1');
define('MYSQL_PASSWORD','');


if (!defined('APP_ROOT')) define('APP_ROOT',__DIR__);

ini_set('display_errors','on');
spl_autoload_register('Autoload');

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
