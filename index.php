<?php
require("vendor/autoload.php");

$f3 = Base::instance();

$f3->set("DEBUG", 3);

// $f3->route(
//     "GET /",
//     function ($f3) {
//         $f3->UI ="";
//         setModulesPath("./app/modules/");
//         echo "<pre>".print_r($f3->UI)."</pre>";
//         echo "<pre>".print_r($f3->AUTOLOAD)."</pre>";
//     }
// );

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

    AnnotationRoutingPlugin::instance();
}
