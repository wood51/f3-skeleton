<?php
require("vendor/autoload.php");

$f3 = Base::instance();

$f3->set("DEBUG", 3);

setModulesPath("./app/modules/");

$f3->run();

function setModulesPath($dir)
{
    $f3=Base::instance();
    $current_dir = array_diff(scandir($dir), array('..', '.'));
    $ui="";
    $autoload="";
    foreach ($current_dir as  $value) {
        $ui.="$dir/$value/views/|";
        $autoload.="$dir/$value/controllers/|$dir/$value/models/|";
    }
    $f3->UI=substr($ui,0,-1);
    $f3->AUTOLOAD=substr($autoload,0,-1);
    
    try {
        AnnotationRoutingPlugin::instance();
    } catch (\Exception $ex) {
        $f3->error(500,$ex->getMessage());
    }
    
}
