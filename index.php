<?php

require("vendor/autoload.php");

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$f3 = \Base::instance();
$f3->LANGUAGE='fr';
$f3->AUTOLOAD = "app/controllers/core/|app/controllers/core/auth/|app/services/core/";
$f3->UI ="app/views";
//$f3->LOCALES = "app/dict/";
$f3->TZ ="Europe/Paris";

$f3->DEBUG = 3;
$f3->PACKAGE ="wood51";

$f3->set("DEBUG",3);

// Configurer la base de données MariaDB
try {
    $db_name = getenv('MARIADB_DATABASE');
    $db_user = getenv('MARIADB_USER');
    $db_password = getenv('MARIADB_PASSWORD');
    $db_host = getenv('MARIADB_HOST');

    $db = new \DB\SQL("mysql:host=$db_host;port=3306;dbname=$db_name", $db_user, $db_password);
    $f3->set('DB', $db);
} catch (PDOException $e) {
    die("Erreur de connexion à MariaDB : " . $e->getMessage());
}


$modules = \ModulesCore::instance();
$modules->load();
AnnotationRoutingPlugin::instance();
//Falsum\Run::handler();
$f3->run();

