<?php
$category=@$_GET['category'];
$name=@$_GET['name'];
$action=@$_GET['action'];
$arg=@$_GET['arguments'];
$arguments=json_decode($arg, true);
if ($arguments==false && $arg!="") {
    $arguments=array($arg);
}
if (!isset($arguments)) {
    $arguments=array();
}

spl_autoload_register(function ($class_name) {
    $arguments = array_filter(explode("\\", $class_name));

    $file='plugins/'.$arguments[0].'/'.$arguments[1].'.php';
    if (file_exists($file)) {
        include_once $file;
    }
});

set_error_handler(function($errno, $errstr){
    //echo "<b>Error:</b> [$errno] $errstr<br>";

});

\plugin\script::call($category, $name, $action, $arguments);
