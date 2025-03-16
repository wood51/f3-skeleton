<?php
class AuthController
{

    /**
     * @route("GET /login")
     */
    public function login_page($f3)
    {
        $f3->set('SESSION.csrf_token', bin2hex(random_bytes(32)));
        setcookie(
            "csrf_token",
            $f3->get('SESSION.csrf_token'),
            [
                "expires" => time() + 3600,
                "path" => "/",
                "secure" => true,
                "httponly" => false,
                "samesite" => "Strict"
            ]
        );
        echo \Template::instance()->render("/auth/templates/login.html");
    }

    /**
     * @route("POST /auth/login")
     */
    public function login($f3)
    {
        $db = $f3->get("DB");
        $data = json_decode($f3->get("BODY"), true);

        $username = $data["username"];
        $password = $data["password"];

        // Récupérer le token CSRF depuis les headers HTTP
        $csrf_token = $f3->get('HEADERS.X-Csrf-Token');

        if (!isset($csrf_token) || $csrf_token !== $f3->get("SESSION.csrf_token")) {
            header("Content-Type: application/json");
            http_response_code(403);
            echo json_encode(["error" => "Token CSRF invalide"]);
            return;
        }

        if (!isset($username, $password)) {
            header("Content-Type: application/json");
            http_response_code(400);
            echo json_encode(["error" => "Nom d'utlisateur et mots de passe requis"]);
            return;
        }

        $user = new DB\SQL\Mapper($db, "users");
        $fetch = $user->findone(["username = ?", htmlspecialchars($username)]);

        if (!$fetch) {
            header("Content-Type: application/json");
            http_response_code(401);
            echo json_encode(["error" => "Utilisateur introuvable"]);
            return;
        }

        if (!password_verify($password, $fetch["password"])) {
            header("Content-Type: application/json");
            http_response_code(401);
            echo json_encode(["error" => "Mot de passe incorrect"]);
            return;
        }


        $f3->set("SESSION.user_id", $fetch["id"]);
        $f3->set("SESSION.nom", $fetch["nom"]);
        $f3->set("SESSION.prenom", $fetch["prenom"]);
        $f3->set("SESSION.username", $fetch["username"]);
        $f3->set("SESSION.role", $fetch["role"]);




        header("Content-Type: application/json");
        http_response_code(200);
        echo json_encode([
            "message" => "Connexion réussie",
            "user" => [
                "id" =>  $fetch["id"],
                "nom" => $fetch["nom"],
                "prenom" => $fetch["prenom"],
                "username" => $fetch["username"],
                "role" => $fetch["role"]
            ]
        ]);
    }

    /**
     * @route("GET /auth/logout")
     */
    public function logout($f3)
    {
        $f3->clear("SESSION");
        $f3->reroute("/login");
    }

    /**
     * @route("GET /admin/auth")
     */
    public function admin($f3) {
        $f3->set('menu', [
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
        echo \Template::instance()->render("/auth/templates/admin.html");
    }
}
