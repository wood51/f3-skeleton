<?php
class TolesService extends \Prefab
{
    
    public function validate_data($data) {
        $champs_obligatoire=["reference","quantite_servie"];
        foreach($champs_obligatoire as $champ) {
            if (empty($data[$champ])) {
                throw new Exception("Le champ '$champ' est obligatoire.");
            }
        }

        // Logique metier 
    }

    public function get_all_lots()
    {
        $lots = new TolesModel();
        $results = $lots->all_active();
        return array_map([$lots, 'cast'], $results);
    }

    public function get_lot($id)
    {
        $lot = new TolesModel();
        return  $lot->get_by_id($id);
    }

    public function save_lot($data) {
        $this->validate_data($data);
        $lots = new TolesModel();
        $lots->save_lot($data);
    }

    public function delete_lot($id) {
        $lot = new TolesModel();
        $lot->delete_lot($id);
    }
}
