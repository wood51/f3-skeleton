<?php


class MessageDestinataireModel extends DB\SQL\Mapper
{
    public function __construct()
    {
        parent::__construct(\Base::instance()->DB, 'messages_destinataires');
    }

    public function get_message_list($user_id) {
        $this->load(["destinataire_id = ? and is_deleted = ?",$user_id,0]);
        return $this->query;
    }
}
