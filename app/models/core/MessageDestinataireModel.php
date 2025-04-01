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

    public static function has_unread_msg($user_id)
    {
        $f3 = \Base::instance();
        $mapper = new self($f3->DB);
    
        $mapper->load([
            'destinataire_id = ? AND is_read = 0 AND is_deleted = 0',
            $user_id
        ]);
    
        return !$mapper->dry(); // Au moins un message non lu 
    }
    
}
