<?php
class BidonController extends BaseController
{
    static function menu()
    {
        $menu = [
            [
                'title' => 'Bidon',
                'submenu' =>
                [
                    ['title' => 'menu 1 ', 'url' => '/bidon'],
                    ['title' => 'menu 2', 'url' => '/']
                ]
            ]
        ];

        return $menu;
    }

    /**
     * @route("GET /bidon")
     */
    function bidon ($f3) {
        \MenuHelper::instance()->filter_menu();
        $f3->content="/bidon/bidon.html";
        MainController::loadMenu();
        echo \Template::instance()->render("/core/templates/layout.html");
    }
}
