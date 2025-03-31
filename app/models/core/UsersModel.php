<?php

class UsersModel extends \DB\SQL\Mapper
{
    public function __construct()
    {
        parent::__construct(\Base::instance()->DB, 'users');
    }

    // Méthode utilitaire privée pour créer un Mapper
    protected static function _init_mapper()
    {
        $db = \Base::instance()->get('DB');
        return new \DB\SQL\Mapper($db, 'users');
    }

    // Méthode publique statique pour obtenir un nom complet
    public static function get_fullname_by_id($id)
    {
        $user = self::_init_mapper();
        $user->load(['id = ?', $id]);

        if ($user->dry()) {
            throw new \Exception("Utilisateur $id introuvable");
        }

        return ucfirst($user->prenom) . ' ' . strtoupper($user->nom);
    }
}
