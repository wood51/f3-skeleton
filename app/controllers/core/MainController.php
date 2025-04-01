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
    $f3->has_unread_msg = MessageDestinataireModel::has_unread_msg($f3->SESSION['user_id']);
    $f3->html_class= isset($f3->SESSION['theme']) && $f3->SESSION['theme'] === 'dark' ? 'dark' : '';
    echo \Template::instance()->render("/core/templates/layout.html");
  }
}
