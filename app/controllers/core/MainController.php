<?php
class MainController extends BaseController
{

  static function loadMenu()
  {
    $f3 = \Base::instance();
    $moduleCore = \ModulesCore::instance();

    $menu = [
      ['title' => 'Accueil','url' => '/'],
      ['title' => 'Admin','url' => '/admin','admin'=>true],
      ['title' => 'Logout', 'url' => '/logout'],
    ];

    // Ajouter dynamiquement les menus modules
    $menu = array_merge($menu, $moduleCore->get_menu());
    return $menu;
    //$f3->set('menu', $menu);
  }

  /**
   * @route("GET /")
   */
  public function index($f3)
  {
    $MenuCore = \MenuCore::instance();
    $menu = $this->loadMenu();
    $f3->menu = $MenuCore->filter_admin($menu,$f3->get("SESSION.role"));
  
    echo \Template::instance()->render("/core/templates/layout.html");
  }

}
