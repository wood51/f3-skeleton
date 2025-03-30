<?php
class UserController extends BaseController
{

  /**
   * @route("GET /user/parametres")
   */
  public function user_paramtres($f3)
  {
    echo \Template::instance()->render("/core/templates/partials/_modal-parametres.html");
  }

  /**
   * @route("GET /user/prefs/lang")
   */
  public function set_user_language($f3)
  {
    $lang =  $f3->GET['lang'];
    if ($lang) {
      $user = new \DB\SQL\Mapper($f3->DB, "user");
      $user->load(['id = ?', $f3->SESSION['user_id']]);
      if (!$user->dry()) {
        $user->lang = $lang;
        $user->update();
      }
    }
  }

  /**
   * @route("GET /user/prefs/theme")
   */
  public function set_user_theme($f3)
  {
    $theme = $f3->GET['theme'];
    if ($theme) {
      $user = new \DB\SQL\Mapper($f3->DB, "user");
      $user->load(['id = ?', $f3->SESSION['user_id']]);
      if (!$user->dry()) {
        $user->theme = $theme;
        $user->update();
      }
    }
  }

  /**
   * @route("POST /user/prefs/password")
   */
  public function change_user_password($f3)
  {
    try {

      // Chargement user
      $user = new \DB\SQL\Mapper($f3->DB, "users");
      $user->load(['id = ?', $f3->SESSION['user_id']]);

      // Verif user
      if ($user->dry()) {
        throw new Exception("L'utilisateur n'existe pas");
      }

      $data = [
        "old" => $f3->POST["old_password"],
        "new" => $f3->POST["new_password"],
        "confirm" => $f3->POST["password_confirm"],
        "old_hash" => $user->password
      ];

      // Verif champs vide
      foreach ($data as $key => $value) {
        if (empty($data[$key])) {
          throw new Exception("Champ $key vide");
        }
      }

      // Verif old password
      if (!password_verify($data["old"], $data["old_hash"])) {
        throw new Exception("Ancien mot de passe invalide");
      }

      // Verif confirmation
      if ($data["new"] != $data["confirm"]) {
        throw new Exception("La confirmation du mots de passe a échoué");
      }

      $user->password = password_hash($data["new"],PASSWORD_BCRYPT);
      $user->update();
      
    } catch (\Exception $ex) {
      $f3->error($ex->getMessage());
    }
  }
}
