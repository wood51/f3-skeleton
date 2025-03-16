<?php 
class CoreAdminController {

  static function loadMenu($f3){
      $moduleCore = \ModulesCore::instance();

      $menu = [
          [
            'title' => 'Accueil', 'url' => '/admin'
          ],
      ];
      
      // Ajouter dynamiquement les menus modules
      $menu = array_merge($menu, $moduleCore->get_menu());

      $f3->set('menu', $menu);
  }
}