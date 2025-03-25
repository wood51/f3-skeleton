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
        $f3->lots = \TolesService::instance()->get_all_lots();
        $f3->menu = MenuCore::instance()->loadMenu($f3->SESSION['role']);
        $f3->content = "/toles/toles.html";
        echo \Template::instance()->render("/core/templates/layout.html");
    }

    /**
     * @route("GET /toles/lots")
     */
    function lots($f3)
    {
        $f3->lots = \TolesService::instance()->get_all_lots();
        echo \Template::instance()->render("/toles/partials/lots-table.html");
    }

    /**
     * @route("GET /toles/form")
     */
    function form($f3)
    {
        echo \Template::instance()->render("/toles/partials/lots-form-modal.html");
    }

    /**
     * @route("POST /toles/save")
     */
    function add($f3)
    {
        try {
            $data = $f3->get('POST');
            // Nettoyage basique des champs vides → null
            foreach ($data as $key => $value) {
                if ($value === '') {
                    $data[$key] = null;
                }
            }

            \TolesService::instance()->save_lot($data);

            http_response_code(200);
            $f3->lots = \TolesService::instance()->get_all_lots();
            echo \Template::instance()->render("/toles/partials/lots-table.html");
            
        } catch (\Exception $ex) {
            http_response_code(500);
            $f3->error = $ex->getMessage();
            echo \Template::instance()->render("/core/templates/partials/toast.html");
        }
    }

    /**
     * @route("GET /toles/@id/edit")
     */
    function edit($f3, $params)
    {
        $f3->lot = \TolesService::instance()->get_lot($params["id"]);
        echo \Template::instance()->render("/toles/partials/lots-form-modal.html");
    }

    /**
     * @route("DELETE /toles/@id/delete")
     */
    function delete($f3, $params)
    {
        \TolesService::instance()->delete_lot($params["id"]);
        $f3->lots = \TolesService::instance()->get_all_lots();
        echo \Template::instance()->render("/toles/partials/lots-table.html");
    }
}
