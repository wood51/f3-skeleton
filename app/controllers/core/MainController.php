<?php
class MainController extends BaseController
{

  static function loadMenu()
  {
    $f3 = \Base::instance();
    $moduleCore = \ModulesCore::instance();

    $menu = [
      ['title' => 'Accueil','url' => '/'],
      ['title' => 'Admin','url' => '/admin'],
      ['title' => 'Logout', 'url' => '/logout'],
    ];

    // Ajouter dynamiquement les menus modules
    $menu = array_merge($menu, $moduleCore->get_menu());

    $f3->set('menu', $menu);
  }

  /**
   * @route("GET /")
   */
  public function index($f3)
  {
    $this->loadMenu();
    echo \Template::instance()->render("/core/templates/layout.html");
  }

}
