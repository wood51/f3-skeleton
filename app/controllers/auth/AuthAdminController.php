<?php
class AuthAdminController
{

    static function menu()
    {
        return [
            'title' => 'Authentification',
            'submenu' => [
                ['title' => 'Liste utilisateurs', 'url' => '/admin/auth/users'],
                ['title' => 'Permissions', 'url' => '/admin/auth/permissions'],
            ]
        ];
    }

    /**
     * @route("GET /admin/auth/users")
     */
    function users($f3)
    {
        CoreAdminController::loadMenu($f3); // charge menu sidebar

        $f3->set('page_title', 'Gestion Utilisateurs');
        $f3->set('content', 'admin/auth/users.htm');

        echo \Template::instance()->render('/common/admin.html');
    }
}
