<?php

use Jose\Component\KeyManagement\Analyzer\MessageBag;

class InboxController
{
    /**
     * @route("GET /inbox")
     */
    function inbox($f3)
    {
        echo \Template::instance()->render('/core/templates/inbox.html');
    }

    /**
     * @route("GET /inbox/messages")
     */
    function get_messages($f3)
    {
        $messages = [];
        $message_destinataire = new MessageDestinataireModel();
        $msg_user =  $message_destinataire->get_message_list($f3->SESSION["user_id"]);
        foreach ($msg_user as $msg) {
            $m = new MessageModel();
            $m->get_message($msg->message_id);
            $messages[]=[
                'id'=>$m->id,
                "sujet"=>$m->sujet,
                "date_envoi"=>$m->sent_at,
                "is_read"=>$msg->is_read
            ];

        }
        $f3->messages = $messages;
        echo \Template::instance()->render('/core/templates/partials/_message-list.html');
    }
    
}
