<?php

require("vendor/autoload.php");

$f3 = \Base::instance();
$f3->DEBUG = 3;
$f3->PACKAGE ="wood51";

$f3->AUTOLOAD = "app/services/";
$f3->UI ="app/views";

$modules = \ModulesCore::instance();
$modules->load("auth");
AnnotationRoutingPlugin::instance();


$f3->run();
