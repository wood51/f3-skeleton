<?php
class AuthController {
    /**
     * @ajax
     * @route("GET /auth")
     */
    function index() {
        echo "Hello Auth !!!!<br>";
        echo "<pre>".print_r(Base::instance()->ROUTES,true)."</pre>";
    }
}