<?php
class BaseController {
    /**
     * @route("GET /")
     */
    function index() {
        echo "Hello Index !!!!<br>";
        echo "<pre>".print_r(Base::instance()->ROUTES,true)."</pre>";
    }
}