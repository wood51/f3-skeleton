<?php
class TolesController extends MainController
{
    static function menu()
    {
        $menu = [
            ['title' => 'Gestion des tôles', 'url' => '/toles', 'view' => 'all']
        ];

        return $menu;
    }

    /**
     * @route("GET /toles")
     */
    function index($f3)
    {
        // $lots = new DB\SQL\Mapper($f3->DB, "toles");
        // $results = $lots->find();
        // $r = array_map([$lots, 'cast'], $results);
        // $f3->set('lots', $r);
        $toles=\TolesService::instance();
        $toles->get_all_lots();
        $f3->menu = MenuCore::instance()->loadMenu($f3->SESSION['role']);
        $f3->content = "/toles/toles.html";
        echo \Template::instance()->render("/core/templates/layout.html");
    }
}
