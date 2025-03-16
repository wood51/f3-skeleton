<?php 
class BaseController {
    function beforeRoute($f3)
    {
      if ($f3->get("SESSION.user_id") === null) {
        http_response_code(403);
        echo json_encode(['error' => 'Accès refusé']);
        $f3->reroute('/login');
        exit;
      }
    }
}