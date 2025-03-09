<?php

require("vendor/autoload.php");

$f3 = \Base::instance();
$f3->DEBUG = 3;
$f3->PACKAGE ="Guinault";

$f3->AUTOLOAD = "app/controllers/|app/services/|app/test/";

//AnnotationRoutingPlugin::instance();

$modules = \ModulesCore::instance();

echo var_dump($modules->get_module_list())."<br>";
echo $modules->is_enabled("auth")."<br>";
$modules->disable("auth");
echo $modules->is_enabled("auth")."<br>";
$modules->save();
$f3->run();
