<?php
class AuthController
{

    /**
     * @route("GET /login")
     */
    public function login_page($f3)
    {
        if (!$f3->exists('SESSION.csrf_token')) {
            $f3->set('SESSION.csrf_token', bin2hex(random_bytes(32)));
        }

        $f3->set('csrf_token', $f3->get('SESSION.csrf_token'));
        echo \Template::instance()->render("/core/templates/login.html");
    }

    /**
     * @route("POST /auth")
     */
    public function login($f3)
    {
        $data = $f3->get('POST');

        $username = $data["username"] ?? null;
        $password = $data["password"] ?? null;
        $csrf_token = $data["csrf_token"] ?? null;

        if ($csrf_token !== $f3->get("SESSION.csrf_token")) {
            $f3->set('error', 'Erreur CSRF. Veuillez recharger la page.');
            echo \Template::instance()->render("/core/templates/partials/login-response.html");
            return;
        }

        if (!$username || !$password) {
            // $f3->set('error', 'Identifiants requis.');
            // echo \Template::instance()->render("/core/templates/partials/login-response.html");
            $f3->set('error', 'Identifiants incorrects');
            echo \Template::instance()->render('/core/templates/partials/toast.html');
            return;
        }

        $user = new DB\SQL\Mapper($f3->get("DB"), "users");
        $fetch = $user->findone(["username = ?", htmlspecialchars($username)]);

        if (!$fetch || !password_verify($password, $fetch["password"])) {
            // $f3->set('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
            // echo \Template::instance()->render("/core/templates/partials/login-response.html");
            $f3->set('error', 'Identifiants incorrects');
            echo \Template::instance()->render('/core/templates/partials/toast.html');
            return;
        }

        // Auth OK
        $f3->set("SESSION.user_id", $fetch["id"]);
        $f3->set("SESSION.nom", $fetch["nom"]);
        $f3->set("SESSION.prenom", $fetch["prenom"]);
        $f3->set("SESSION.username", $fetch["username"]);
        $f3->set("SESSION.role", $fetch["role"]);

        $f3->set('toast', 'Connexion OK');
        echo \Template::instance()->render('/core/templates/partials/toast.html');
    }



    /**
     * @route("GET /logout")
     */
    public function logout($f3)
    {
        $f3->clear("SESSION");
        $f3->reroute("/login");
    }

    /**
     * @route("GET /admin/auth")
     */
    public function admin($f3)
    {
        $f3->set('menu', [
            ['title' => 'Acceuil', 'url' => '/admin'],
            [
                'title' => 'Utilisateurs',
                'submenu' => [
                    ['title' => 'Liste des utilisateurs', 'url' => '/admin/users'],
                    ['title' => 'Ajouter utilisateur', 'url' => '/admin/users/add'],
                ]
            ],
            [
                'title' => 'Gestion des Rôles',
                'submenu' => [
                    ['title' => 'Liste des rôles', 'url' => '/admin/roles'],
                    ['title' => 'Ajouter rôle', 'url' => '/admin/roles/add'],
                ]
            ],
            // chaque module peut ajouter ses propres items simplement ici
        ]);
        echo \Template::instance()->render("/core/templates/layout_admin.html");
    }
}
