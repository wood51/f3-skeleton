<?php

class TolesModel extends DB\SQL\Mapper
{
    public function __construct()
    {
        parent::__construct(Base::instance()->DB, "toles");
    }

    public function all()
    {
        return $this->find();
    }

    public function get_by_id($id)
    {
        return $this->findone(["id = ?", $id]);
    }

    public function save_lot($data)
    {
        if (isset($data['id'])) {
            $this->load(["id = ?", $data['id']]);
        }

        $this->copyfrom($data);
        $this->save();
    }

    public function delete_lot($id) {
        $this->load(['id = ?',$id]);
        if (!$this->dry()) {
            $this->erase();
        }
    }
}
