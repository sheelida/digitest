<?php 
function loadClass($classname){

    //function to load the right directory of a class.
    $root = $_SERVER['DOCUMENT_ROOT'];
    $classdir = 'classes';
    $classfile = $root.'/'.'digitest' .'/'.$classdir.'/'.strtolower($classname).'.class.php';
    //setting up all together
    include $classfile;
}
//auto load register
spl_autoload_register('loadClass');
?>