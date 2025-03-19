<?php
class MainController extends BaseController
{

  static function loadMenu()
  {
    $f3 = \Base::instance();
    $moduleCore = \ModulesCore::instance();

    $menu = [
      ['title' => 'Accueil', 'url' => '/'],
      ['title' => 'Admin', 'url' => '/admin', "view" => "admin"],
      ['title' => 'Logout', 'url' => '/logout'],
    ];

    // Ajouter dynamiquement les menus modules

    return $menu;
    //$f3->set('menu', $menu);
  }

  /**
   * @route("GET /")
   */
  public function index($f3)
  {
    $f3->menu = MenuCore::instance()->loadMenu($f3->SESSION['role']);
    echo \Template::instance()->render("/core/templates/layout.html");
  }
}
