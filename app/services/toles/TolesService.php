<?php
class TolesService extends \Prefab
{
    public function get_all_lots() {
        $lots = new TolesModel(\Base::instance()->DB);
        $results = $lots->all();

        // return array_map([])
        
    }
}
