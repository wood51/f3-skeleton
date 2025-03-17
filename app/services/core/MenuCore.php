<?php
class MenuCore extends \Prefab
{
    public function filter_admin($menu, $user_role)
    {
        if ($user_role !== "admin") {
            $menu = array_filter($menu, function ($item) {
                return !isset($item['admin']) || $item['admin'] === false;
            });
        }

        return $menu;
    }
}
