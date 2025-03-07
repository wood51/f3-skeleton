<?php

require("vendor/autoload.php");

$f3 = \Base::instance();

$f3->AUTOLOAD = "app/controllers/";

AnnotationRoutingPlugin::instance();

$f3->run();