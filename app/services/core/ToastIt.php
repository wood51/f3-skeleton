<?php

class ToastIt extends \Prefab
{
    private function _render($message, $type)
    {
        \Base::instance()->toast = [
            "message" => $message,
            "type" => $type
        ];
        echo \Template::instance()->render("/core/templates/partials/_toast.html");
    }

    public function __call($method, $args)
    {
        $types = ['success','info','warning','error'];
        if (in_array($method, $types)) {
            $message = $args[0] ?? '';
            $this->_render(htmlentities($message), $method);
        } else {
            throw new Exception("Type de toast inconnu : $method");
        }
    }
}
