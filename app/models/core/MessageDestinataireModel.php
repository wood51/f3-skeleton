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

    public static function get_inbox_for($userId)
    {
        $f3 = \Base::instance();
        $mapper = new self($f3->DB);

        // Exemple de requête simple : charge les messages non supprimés et non lus
        // Tu peux améliorer cette requête selon tes besoins (jointure, ordre, etc.)
        $results = $mapper->find([
            'destinataire_id = ? AND is_deleted = 0',
            $userId
        ],['order' => 'id DESC']);

        $inbox = [];
        foreach ($results as $entry) {
            $messageModel = new MessageModel($f3->DB);
            $messageModel->get_message($entry->message_id);

            // On récupère le nom de l'expéditeur avec le modèle statique UsersModel
            $sender = UsersModel::get_fullname_by_id($messageModel->expediteur_id);

            $inbox[] = [
                'id'         => $messageModel->id,
                'sujet'      => $messageModel->sujet,
                'date_envoi' => $messageModel->sent_at,
                'is_read'    => $entry->is_read,
                'expediteur' => $sender
            ];
        }
        return $inbox;
    }
    
}
