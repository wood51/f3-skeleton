<?php

use \DB\SQL\Schema;

class MessageModel extends DB\SQL\Mapper
{
    public function __construct()
    {
        parent::__construct(\Base::instance()->DB, 'messages');
    }

    public function get_message($msg_id) {
        $this->load(["id=?",$msg_id]);
        return $this->query;
    }
}
