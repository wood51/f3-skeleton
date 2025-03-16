<?php 
class AdminController {

  static function loadMenu(){
    $f3=\Base::instance();
      $moduleCore = \ModulesCore::instance();

      $menu = [
          ['title' => 'Accueil', 'url' => '/admin'],
          ['title' => 'Logout', 'url' => '/auth/logout'],
      ];
      
      // Ajouter dynamiquement les menus modules
      $menu = array_merge($menu, $moduleCore->get_menu());

      $f3->set('menu', $menu);
  }

  function beforeRoute($f3)
    {
      if ($f3->get("SESSION.user_id") === null || $f3->get("SESSION.role") !== "admin") {
        http_response_code(403);
        echo json_encode(['error' => 'Accès refusé']);
        $f3->reroute('/login');
        exit;
      }
    }

  /**
   * @route("GET /admin")
   */
  public function index($f3) {
    $this->loadMenu();
    echo \Template::instance()->render("/core/templates/layout_admin.html");
  }

 
}