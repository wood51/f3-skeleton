<?php
class MenuCore extends \Prefab
{

    public function loadMenu($user_role = "user")
    {
        $menu = [
            ['title' => 'Accueil', 'url' => '/'],
            ['title' => 'Admin', 'url' => '/admin', "view" => "admin"],
            ['title' => 'Logout', 'url' => '/logout'],
        ];

        $menu = array_merge($menu, $this->get_modules_menu());
        $menu = $this->filter_menu($menu, $user_role);

        return $menu;
    }

    private function get_modules_menu()
    {
        $menus = [];
        $modules = \ModulesCore::instance()->get_modules();
        foreach ($modules as $module_name => $module) {
            if ($module->enabled) {
                $controller_class = ucfirst($module_name) . "Controller";
                if (class_exists($controller_class) && method_exists($controller_class, 'menu')) {
                    // $menus = $controller_class::menu();
                    $menus = array_merge($menus, $controller_class::menu());
                }
            }
        }
        return $menus;
    }

    private function filter_menu($menu, $user_role)
    {
        $menu = array_filter($menu, function ($item) use ($user_role) {
            if (isset($item['view'])) {
                if ($item['view'] === 'all') {
                    // Accessible à tous
                    return true;
                }
                if ($item['view'] === 'admin') {
                    // Accessible uniquement aux admin
                    return $user_role === 'admin';
                }
                if ($item['view'] === 'user') {
                    // Accessible uniquement aux utilisateurs non admin
                    return $user_role !== 'admin';
                }
                if ($item['view'] === 'none') {
                    // Accessible uniquement aux utilisateurs non admin
                    return false;
                }
                // Si 'view' est défini autrement, on ne l'affiche pas par défaut
                return false;
            }
            // Si 'view' n'est pas défini, on l'affiche)
            return true;
        });
        return $menu;
    }
}
