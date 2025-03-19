<?php

class TolesModel extends DB\SQL\Mapper
{
    public function __construct(\DB\SQL $db)
    {
        parent::__construct($db, "toles");
    }

    public function all() {
        $results = $this->find();
        return $results;
    }
}
