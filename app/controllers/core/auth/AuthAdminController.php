<?php
class AuthAdminController
{

    static function menu()
    {
        return [
            'title' => 'Authentification',
            'submenu' => [
                ['title' => 'Liste utilisateurs', 'url' => '/admin/auth'],
                ['title' => 'Permissions', 'url' => '/admin/auth/permissions'],
            ]
        ];
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
     * @route("GET /admin/auth/users")
     */
    function users($f3)
    {
        AdminController::loadMenu($f3); // charge menu sidebar

        $f3->set('page_title', 'Gestion Utilisateurs');
        $f3->set('content', 'admin/auth/users.htm');

        echo \Template::instance()->render('/common/admin.html');
    }
}
