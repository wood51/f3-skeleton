<?php

class InboxController
{


    /**
     * @route("GET /inbox")
     */
    function inbox($f3)
    {
        $user_id = $f3->SESSION["user_id"];
        $f3->messages = MessageDestinataireModel::get_inbox_for($user_id);
        echo \Template::instance()->render('/core/templates/inbox.html');
    }

    /**
     * @route("GET /inbox/messages")
     */
    function get_messages($f3)
    {
        $messages = [];
        $inboxModel = new MessageDestinataireModel();
        $receivedMessages = $inboxModel->get_message_list($f3->SESSION["user_id"]);

        foreach ($receivedMessages as $entry) {
            $messageModel = new MessageModel();

            $messageModel->get_message($entry->message_id);
            $sender = UsersModel::get_fullname_by_id($messageModel->expediteur_id);
            $messages[] = [
                'id'         => $messageModel->id,
                'sujet'      => $messageModel->sujet,
                'date_envoi' => $messageModel->sent_at,
                'is_read'    => $entry->is_read,
                'expediteur' => $sender
            ];
        }

        $f3->messages = $messages;
        echo \Template::instance()->render('/core/templates/partials/_message-list.html');
    }

    /**
     * @route("GET /inbox/message/@id")
     */
    function view_message($f3, $params)
    {
        $messageId = $params['id'];
        $userId = $f3->SESSION['user_id'];

        $dest = new MessageDestinataireModel();
        $dest->load(['message_id = ? AND destinataire_id = ?', $messageId, $userId]);

        if ($dest->dry()) {
            http_response_code(403);
            echo 'Message inaccessible';
            return;
        }

        $message = new MessageModel();
        $message->get_message($messageId);

        $expediteur = UsersModel::get_fullname_by_id($message->expediteur_id);

        if (!$dest->is_read) {
            $dest->is_read = 1;
            $dest->save();
        }

        $f3->set('sujet', $message->sujet);
        $f3->set('date_envoi', $message->sent_at);
        $f3->set('expediteur', $expediteur);
        $f3->set('message', nl2br($message->message));
        header('HX-Trigger: refresh-inbox');
        echo \Template::instance()->render('/core/templates/partials/_message-modal.html');
    }

    /**
     * @route("GET /inbox/message/write")
     */
    public function write_message() {
        echo \Template::instance()->render('/core/templates/partials/_message-write.html');
    }
}
