<?php 
class CorePublicController {

  /**
   * @route("GET /public")
   */
  public function index($f3) {
    echo \Template::instance()->render("/core/templates/layout_public.html");
  }
}