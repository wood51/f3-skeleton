<?php 
class PublicController {

  /**
   * @route("GET /public")
   */
  public function index($f3) {
    echo \Template::instance()->render("/core/templates/layout_public.html");
  }
}